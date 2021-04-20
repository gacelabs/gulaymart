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
				/*$drop = ((isset($post['drop']) AND $post['drop']) ? 'true' : 'false');
				debug($post, $drop, true);*/
				$this->build((isset($post['drop']) AND $post['drop']) ? true : false);
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
									$fetched = [
										'city' => $tmp['city'],
										'province' => $tmp['province'],
										'latlng' => json_encode($google_data->geometry->location),
										'place_id' => $google_data->place_id,
									];
									if ($this->gm_db->count('serviceable_areas', $tmp) == 0) {
										$this->gm_db->new('serviceable_areas', $fetched);
										++$total_fetched;
										// if ($total_fetched == 10) break; // for testing only
									}
								}
							}
							unset($toktok_data[$key]);
							$total_remaining = (count($toktok_data) - 1 ?: 0);
							$jsonfile = fopen(get_root_path('assets/data/deliveries-2021-04-19.json'), "a+");
							$json_encoded = json_encode($toktok_data);
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
						/*overwrite json file, to avoid re-uploading*/
						// $jsonfile = fopen(get_root_path('assets/data/deliveries-'.date('Y-m-d').'.json'), "a+");
						/*$jsonfile = fopen(get_root_path('assets/data/deliveries-2021-04-19.json'), "a+");
						fwrite($jsonfile, json_encode($toktok_data));
						fclose($jsonfile);*/

						/*OLD PROCESS*/
						// $chunks = array_chunk($json['pickup_dropoff'], 5);
						// $cnt = 0;
						// again:
						// // debug($chunks[$cnt], true);
						// $fetched = [];
						// foreach ($chunks[$cnt] as $toktok) {
						// 	$google_data = get_coordinates(['lat' => $toktok['sender_lat'], 'lng' => $toktok['sender_lon']], false);
						// 	// debug($google_data, true);
						// 	sleep(3);
						// 	if ($google_data) {
						// 		$tmp = [];
						// 		foreach ($google_data->address_components as $object) {
						// 			if (!isset($tmp['city']) AND in_array('locality', $object->types)) {
						// 				$tmp['city'] = remove_multi_space(trim($object->long_name), true);
						// 			}
						// 			if (!isset($tmp['province']) AND in_array('administrative_area_level_1', $object->types)) {
						// 				$tmp['province'] = remove_multi_space(trim($object->long_name), true);
						// 			}
						// 			if (isset($tmp['city']) AND isset($tmp['province'])) break;
						// 		}
						// 		if (isset($tmp['city']) AND isset($tmp['province'])) {
						// 			$fetched[] = [
						// 				'city' => $tmp['city'],
						// 				'province' => $tmp['province'],
						// 				'latlng' => json_encode($google_data->geometry->location),
						// 				'place_id' => $google_data->place_id,
						// 			];
						// 		}
						// 	}
						// }
						// // if ($cnt > 0) debug($fetched, 'stop');
						// foreach ($fetched as $key => $raw) {
						// 	if ($this->gm_db->count('serviceable_areas', ['city' => $raw['city']]) == 0) {
						// 		$this->gm_db->new('serviceable_areas', $raw);
						// 	}
						// }
						// $cnt++;
						// // debug((string)$cnt, true);
						// echo "sleeping for 17 seconds";
						// sleep(17);
						// goto again;
					}
				}
			}
		}
		echo "<br>DONE!<br><br><button onclick='history.back();'>Back</button>"; exit();
	}

	private function build($drop_all=false)
	{
		if ($drop_all) {
			$exists = method_exists($this->gm_db, 'drop_tables');
			if ($exists == true) {
				$this->load->library('accounts');
				if ($this->accounts->has_session) $this->accounts->logout(true);
				delete_cookie('prev_latlng');
				sleep(3);
				$return = $this->gm_db->drop_tables();
				if ($return) {
					echo "All Tables dropped <br>";
				} else {
					echo "All Tables already dropped <br>";
				}
				sleep(10);
			}
		}

		/*re-create table*/
		foreach ($this->get_data() as $key => $table) {
			$fields = false;
			if (is_array($table)) {
				$fields = $table;
				$table = $key;
			}
			if (!$this->db->table_exists($table)) {
				if ((bool)strstr($table, ':recreate')) {
					$chunks = explode(':', $table);
					$table = trim($chunks[0]);
					if ($this->db->table_exists($table)) $this->db->query('DROP TABLE '.$table);
				}
				// debug($table);
				/*create table for the first time*/
				$method = 'create_'.$table.'_table';
				if (method_exists($this->createdev, $method)) {
					$is_created = $this->createdev->{$method}();
					if ($is_created) {
						echo "Table ".$table." created! <br>";
					} else {
						echo "Table ".$table." existing! <br>";
					}
				} else {
					echo "Method ".$method." does not exists! <br>";
				}
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
		if (!isset($is_created)) {
			echo "<br>All Tables and Values already exists! <br>";
		}
		return;
	}

	private function get_data()
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
		];

		return $data;
	}

}