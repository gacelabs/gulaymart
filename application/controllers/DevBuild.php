<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DevBuild extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('CreateDev');
	}

	public function index()
	{
		$this->load->view('dev-build');
	}

	public function run()
	{
		/*DEFAULT PASSWORD IS 1*/
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post) AND $post['password'] === DEVBUILD_PASS) {
				$mode = false;
				if (isset($post['mode'])) $mode = $post['mode'];
				// debug($post, $mode, true);
				$this->build($mode);
			}
		}
		echo '<h1 style="color: green;">DONE!</h1><button onclick="history.back();">Back</button>'; exit();
	}

	private function build($mode=false)
	{
		/*re-create table*/
		$incremental = [];
		$insertdata = [];
		$datatables = $this->get_datatables();
		$not_this_tables = ['products_measurement', 'products_category', 'products_subcategory', 'attributes', 'attribute_values', 'serviceable_areas'];

		if ($mode != false) {
			$this->load->library('accounts');
			if ($this->accounts->has_session) $this->accounts->logout(true);
			$exists = method_exists($this->gm_db, 'drop_tables');
			if ($exists == true) {
				if ($mode == 'drop') {
					delete_cookie('prev_latlng');
					delete_cookie('current_city');
				} elseif ($mode == 'clear') {
					foreach ($datatables as $key => $table) {
						if ($this->db->table_exists($table)) {
							if (!in_array($table, $not_this_tables)) {
								$incremental[$table] = $this->gm_db->count($table);
								$tabledata = $this->gm_db->get($table);
								if ($tabledata) $insertdata[$table] = $tabledata;
							}
						}
					}
				}
				// debug($incremental, $insertdata, true);
				$return = $this->gm_db->drop_tables();
				if (isset($return)) {
					echo '<b style="color: red;">All Tables '.ucfirst($mode).'ed</b> <br><br>';
				}
			}
		}

		// debug($datatables, 'stop');
		foreach ($datatables as $key => $table) {
			if ($table != 'serviceable_areas') {
				/*create table for the first time*/
				$method = 'create_'.$table.'_table';
				// debug($method, $table, 'stop');
				if (method_exists($this->createdev, $method)) {
					$autoinc = 0;
					if (isset($incremental[$table])) $autoinc = $incremental[$table];
					// debug($method, $autoinc, $table, 'stop');
					$is_created = $this->createdev->{$method}();
					if ($is_created) {
						if ($autoinc) $this->db->query("ALTER TABLE ".$table." AUTO_INCREMENT = ".$autoinc);
						echo '<i>Table '.$table.' created!</i> <br>';
						if (isset($insertdata[$table])) {
							$this->db->reset_query();
							foreach ($insertdata[$table] as $key => $row) {
								foreach ($row as $field => $value) {
									if ($this->db->field_exists($field, $table) == false) {
										unset($row[$field]);
									}
								}
								if (isset($row['added']) AND isset($row['updated'])) {
									unset($row['added']); unset($row['updated']);
								}
								if (count($row)) {
									if ($table == 'admin_settings') {
										$record = $this->gm_db->get($table, false, 'row');
									} else {
										$record = $this->gm_db->get($table, $row, 'row');
									}
									if ($record) {
										// debug($record, 'stop');
										if (isset($record['added']) AND isset($record['updated'])) {
											unset($record['added']); unset($record['updated']);
										}
										$this->gm_db->save($table, $row, $record);
									} else {
										$this->gm_db->new($table, $row);
									}
								}
							}
							echo '- <b style="color: green;">'.count($insertdata[$table]).' record(s) restored.</b><br>';
						}
					} else {
						echo '<i style="color: orange;">Table '.$table.' existing!</i> <br>';
					}
				} else {
					echo '<i style="color: red;">Method '.$method.' does not exists!</i> <br>';
				}
				flush();
				ob_flush();
				sleep(1);
			}
		}

		echo '<br><h2 style="color: green;">All Tables and Values created/updated!</h2>';
		return;
	}

	public function fetch()
	{
		/*DEFAULT PASSWORD IS 1*/
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post) AND $post['password'] === DEVBUILD_PASS) {
				// debug($post, true);
				// $string = @file_get_contents(get_root_path('assets/data/deliveries-'.date('Y-m-d').'.json'));
				$string = @file_get_contents(get_root_path('assets/data/deliveries-2021-04-19.json'));
				// debug($string, true);
				if (!empty($string)) {
					$json = json_decode($string, true);

					$self = $this;
					// register for error logging in case of timeout
					$shutdown = function () use (&$self) { $self->shutdown(); };
					register_shutdown_function($shutdown);

					if (isset($json['pickup_dropoff'])) {
						$toktok_data = $json['pickup_dropoff'];
						$total_count = count($toktok_data);
						$total_fetched = $total_remaining = 0;

						echo '<b style="color: red;">Fetch Serviceable Areas executed</b><br><br><a href="'.base_url().'">Back</a>';

						foreach ($toktok_data as $key => $toktok) {
							$google_data = get_coordinates(['lat' => $toktok['sender_lat'], 'lng' => $toktok['sender_lon']], false);
							// debug($google_data, true);
							sleep(4);
							if ($google_data) {
								if ($this->gm_db->count('serviceable_areas', ['place_id' => $google_data->place_id]) == 0) {
									$tmp = [];
									foreach ($google_data->address_components as $object) {
										if (!isset($tmp['city']) AND in_array('locality', $object->types)) {
											$tmp['city'] = remove_multi_space(trim($object->long_name), true);
										}
										if (!isset($tmp['province']) AND in_array('administrative_area_level_1', $object->types)) {
											$tmp['province'] = remove_multi_space(trim($object->long_name), true);
										}
										if (isset($tmp['city']) AND isset($tmp['province'])) break;
									}
									if (isset($tmp['city']) AND isset($tmp['province'])) {
										if ($this->gm_db->count('serviceable_areas', $tmp) == 0) {
											$fetched = [
												'city' => $tmp['city'],
												'province' => $tmp['province'],
												'latlng' => json_encode($google_data->geometry->location),
												'place_id' => $google_data->place_id,
											];
											$this->gm_db->new('serviceable_areas', $fetched);
											++$total_fetched;
											echo '<b style="color: green;">'.$fetched['city'].', '.$fetched['province'].' added</b><br>';
											// if ($total_fetched == 5) break; // for testing only
										}
									}
								}
							}
							flush();
							ob_flush();
							sleep(1);

							unset($toktok_data[$key]);
							$total_remaining = (count($toktok_data) - 1 ?: 0);
							$jsonfile = fopen(get_root_path('assets/data/deliveries-2021-04-19.json'), "w+");
							$json_encoded = json_encode(['pickup_dropoff'=>$toktok_data]);
							fwrite($jsonfile, $json_encoded);
							fclose($jsonfile);
						}
						echo '<h2 style="color: green;">Serviceable Areas fetched/updated!</h2>';

						$logfile = fopen(get_root_path('assets/data/logs/fetched-cities.log'), "a+");
						$txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
						$txt .= "Total fetched: " . $total_fetched . " \n";
						$txt .= "Total record(s): " . $total_count . " \n";
						$txt .= "Total remaining: " . $total_remaining . " \n";
						$txt .= "--------------------------------" . "\n";
						fwrite($logfile, $txt);
						fclose($logfile);
					}
				}
			}
		}
		echo '<h1 style="color: green;">DONE!</h1><button onclick="history.back();">Back</button>'; exit();
	}

	private function get_datatables()
	{
		$data = [
			'users',
			'user_farms',
			'user_farm_locations',
			'user_settings',
			'galleries',
			'email_session',
			'user_shippings',
			'user_profiles',
			'products',
			'products_category',
			'products_subcategory',
			'products_location',
			'products_measurement',
			'products_photo',
			'products_attribute',
			'attributes',
			'attribute_values',
			'baskets',
			'basket_transactions',
			'messages',
			'serviceable_areas',
			'baskets_merge',
			'admin_settings',
			'operators',
			'operator_riders',
		];

		return $data;
	}

	private function shutdown()
	{
		$error = error_get_last();
		// if shutdown in error
		if ($error['type'] === E_ERROR) {
			// write contents to error log
			error_log('Serviceable Areas on shutdown', 0);
		}
		exit();
	}

}
