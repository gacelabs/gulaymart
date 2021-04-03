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

	private function build($drop_all=false)
	{
		if ($drop_all) {
			$exists = method_exists($this->gm_db, 'drop_tables');
			if ($exists == true) {
				$this->load->library('accounts');
				if ($this->accounts->has_session) {
					$this->accounts->logout(true);
				}
				sleep(3);
				$this->gm_db->drop_tables();
				echo "All Tables dropped <br>";
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
					// debug($row, true);
					if (!$this->db->field_exists($column, $table) AND !isset($row['alter'])) {
						$this->load->dbforge();
						$is_col_created = $this->dbforge->add_column($table, [$column => $row['definition']], (isset($row['after']) ? $row['after'] : NULL));
						if ($is_col_created AND (isset($row['add_key']) AND strlen(trim($row['add_key'])))) {
							$this->db->query($row['add_key']);
						}
					} elseif (isset($row['alter']) AND $this->db->field_exists($column, $table)) {
						$this->db->query($row['alter']);
						$is_col_altered = 1;
					} elseif (isset($row['remove']) AND $this->db->field_exists($column, $table)) {
					}
					if (isset($row['alter']) AND isset($is_col_altered) AND $is_col_altered) {
						echo "Field ".$column." ".$row['altered']['status']." in Table ".$table."! <br>";
					} elseif (isset($is_col_created) AND $is_col_created) {
						echo "Field ".$column." in Table ".$table." created! <br>";
					/*} else {
						echo "Field ".$column." in Table ".$table." existing! <br>";*/
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
			'users' => [
				'is_agreed_terms' => [
					'definition' => [
						'type' => 'TINYINT',
						'constraint' => '1',
						'default' => '0',
					],
					'after' => 'is_profile_complete',
				]
			],
			'user_farms' => [
				'tagline' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'name',
				],
				'banner' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'about',
				],
				'cover_pic' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'banner',
				],
				'profile_pic' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'cover_pic',
				],
				'messenger' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'ip_address',
				],
				'youtube' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'ip_address',
				],
				'instagram' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'ip_address',
				],
				'facebook' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'ip_address',
				],
				'lat' => [
					'alter' => "ALTER TABLE user_farms DROP COLUMN lat;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'lng' => [
					'alter' => "ALTER TABLE user_farms DROP COLUMN lng;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'address_1' => [
					'alter' => "ALTER TABLE user_farms DROP COLUMN address_1;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'address_2' => [
					'alter' => "ALTER TABLE user_farms DROP COLUMN address_2;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'about' => [
					'alter' => "ALTER TABLE user_farms DROP COLUMN about;",
					'altered' => [
						'status' => 'removed',
					],
				],
			],
			'user_farm_locations' => [
				'address' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
				],
			],
			'user_farm_contents',
			'user_settings',
			'galleries' => [
				'file_path' => [
					'alter' => "ALTER TABLE galleries DROP COLUMN file_path;",
					'altered' => [
						'status' => 'removed',
					],
				]
			],
			'email_session',
			'user_shippings',
			'user_profiles',
			'products' => [
				'subcategory_id' => [
					'definition' => [
						'type' => 'SMALLINT',
						'default' => '0',
						'null' => false,
					],
					'add_key' => "ALTER TABLE products ADD INDEX subcategory_id (subcategory_id);",
					'after' => 'category_id',
				],
				'inclusion' => [
					'definition' => [
						'type' => 'TEXT',
					],
					'after' => 'description',
				],
				'activity' => [
					'alter' => "ALTER TABLE products CHANGE COLUMN activity activity SMALLINT(5) NOT NULL DEFAULT '0' AFTER delivery_option_id;",
					'altered' => [
						'status' => 'changed default value',
					],
				],
				'current_price' => [
					'alter' => "ALTER TABLE products CHANGE COLUMN current_price price DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER measurement;",
					'altered' => [
						'status' => 'changed to price',
						'column' => 'price',
					],
				],
			],
			'products_category',
			'products_subcategory',
			'products_location',
			'products_measurement',
			'products_photo' => [
				'url_path' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
					'after' => 'path',
				],
				'status' => [
					'definition' => [
						'type' => 'TINYINT',
						'constraint' => '1',
						'default' => '1',
						'null' => false,
					],
					'after' => 'url_path',
				],
				'path' => [
					'alter' => "ALTER TABLE products_photo DROP COLUMN `path`;",
					'altered' => [
						'status' => 'removed',
					],
				]
			],
			'products_attribute' => [
				'product_id' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
						'default' => '0',
						'null' => false,
					],
					'add_key' => "ALTER TABLE products_attribute ADD INDEX product_id (product_id);",
					'after' => 'id',
				],
				'attribute' => [
					'definition' => [
						'type' => 'TEXT',
					],
					'after' => 'product_id',
				],
			],
		];

		return $data;
	}

}