<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DevBuild extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('createdev');
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
		echo "<br>DONE!<br><br><button onclick='history.back();'>Back</button>"; exit();
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
					if (isset($json['pickup_dropoff'])) {
						$toktok_data = $json['pickup_dropoff'];
						$total_count = count($toktok_data);
						$total_fetched = $total_remaining = 0;
						foreach ($toktok_data as $key => $toktok) {
							$google_data = get_coordinates(['lat' => $toktok['sender_lat'], 'lng' => $toktok['sender_lon']], false);
							// debug($google_data, true);
							sleep(3);
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
											// if ($total_fetched == 2) break; // for testing only
										}
									}
								}
							}
							unset($toktok_data[$key]);
							$total_remaining = (count($toktok_data) - 1 ?: 0);
							$jsonfile = fopen(get_root_path('assets/data/deliveries-2021-04-19.json'), "w+");
							$json_encoded = json_encode(['pickup_dropoff'=>$toktok_data]);
							fwrite($jsonfile, $json_encoded);
							fclose($jsonfile);
							sleep(7);
						}
						$logfile = fopen(get_root_path('assets/data/logs/fetched-cities.log'), "a+");
						$txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
						$txt .= "Total fetched: " . $total_fetched . " \n";
						$txt .= "Total records: " . $total_count . " \n";
						$txt .= "Total remaining: " . $total_remaining . " \n";
						$txt .= "--------------------------------" . "\n";
						fwrite($logfile, $txt);
						fclose($logfile);
					}
				}
			}
		}
		echo "<br>DONE!<br><br><button onclick='history.back();'>Back</button>"; exit();
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
				} elseif ($mode == 'clear') {
					foreach ($datatables as $key => $table) {
						if ($this->db->table_exists($table)) {
							if (!in_array($table, $not_this_tables)) {
								$incremental[$table] = $this->gm_db->count($table);
								$insertdata[$table] = $this->gm_db->get($table);
							}
						}
					}
				}
				// debug($incremental, true);
				$return = $this->gm_db->drop_tables();
				if (isset($return)) {
					echo "All Tables ".ucfirst($mode)."ed <br>";
				}
			}
		}
		sleep(10);

		// debug($datatables, 'stop');
		foreach ($datatables as $key => $table) {
			$fields = false;
			if (is_array($table)) {
				$fields = $table;
				$table = $key;
			}
			// debug($this->db->table_exists($table), $table, 'stop');
			if ((bool)strstr($table, ':recreate')) {
				$chunks = explode(':', $table);
				$table = trim($chunks[0]);
			}
			// debug($table);
			/*create table for the first time*/
			$method = 'create_'.$table.'_table';
			// debug($method, $table, 'stop');
			if (method_exists($this->createdev, $method)) {
				$autoinc = 0;
				if (isset($incremental[$table])) $autoinc = $incremental[$table];
				// debug($method, $autoinc, $table, 'stop');
				$is_created = $this->createdev->{$method}();
				sleep(7);
				if ($is_created) {
					if ($autoinc) {
						$this->db->query("ALTER TABLE ".$table." AUTO_INCREMENT = ".$autoinc);
					}
					echo "Table ".$table." created! <br>";
				} else {
					echo "Table ".$table." existing! <br>";
				}
			} else {
				echo "Method ".$method." does not exists! <br>";
			}

			if ($fields) {
				foreach ($fields as $column => $row) {
					if ((bool)strstr($column, ':change')) {
						$chunks = explode(':', $column);
						$column = trim($chunks[0]);
					}
					if (!$this->db->field_exists($column, $table) AND !isset($row['alter'])) {
						$this->load->dbforge();
						$is_col_created = $this->dbforge->add_column($table, [$column => $row['definition']], (isset($row['after']) ? $row['after'] : NULL));
						if ($is_col_created AND (isset($row['add_key']) AND strlen(trim($row['add_key'])))) {
							// debug($row, $column);
							$this->db->query($row['add_key']);
						}
					} elseif (isset($row['alter']) AND $this->db->field_exists($column, $table)) {
						// debug($row, $column, $table, 'stop');
						if (!(bool)strstr($row['alter'], 'DROP COLUMN') AND !(bool)strstr($row['alter'], 'CHANGE COLUMN')) {
							$this->db->query("ALTER TABLE $table DROP COLUMN $column;");
						}
						$this->db->query($row['alter']);
						$is_col_altered = 1;
					}
					if (isset($row['alter']) AND isset($is_col_altered) AND $is_col_altered) {
						echo "Field ".$column." ".$row['altered']['status']." in Table ".$table."! <br>";
					} elseif (isset($is_col_created) AND $is_col_created) {
						echo "Field ".$column." in Table ".$table." created! <br>";
					} else {
						echo "Field ".$column." in Table ".$table." existing! <br>";
					}
				}
			}
		}
		
		if (count($insertdata)) {
			foreach ($insertdata as $table => $insert) {
				if (!in_array($table, $not_this_tables) AND $insert) {
					// debug($insert, true);
					foreach ($insert as $key => $row) {
						foreach ($row as $field => $value) {
							if ($this->db->field_exists($field, $table) == false) {
								unset($row[$field]);
							}
						}
						if (count($row)) $this->gm_db->new($table, $row);
					}
				}
			}
		}

		if (!isset($is_created)) {
			echo "<br>All Tables and Values created/updated! <br>";
		}
		return;
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
		];

		return $data;
	}

}