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
				if ($this->accounts->has_session) $this->accounts->logout(true);
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
			'users' => [
				'is_agreed_terms' => [
					'definition' => [
						'type' => 'TINYINT',
						'constraint' => '1',
						'default' => '0',
					],
				],
				'lat' => [
					'definition' => [
						'type' => 'VARCHAR',
						'constraint' => '100',
						'default' => NULL,
					],
				],
				'lng' => [
					'definition' => [
						'type' => 'VARCHAR',
						'constraint' => '100',
						'default' => NULL,
					],
				],
			],
			'user_farms' => [
				'tagline' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'banner' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'cover_pic' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'profile_pic' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'about' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'messenger' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'youtube' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'instagram' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'facebook' => [
					'definition' => [
						'type' => 'TEXT',
						'default' => NULL,
						'null' => true,
					],
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
			],
			'user_farm_locations',
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
				],
				'inclusion' => [
					'alter' => "ALTER TABLE products DROP COLUMN inclusion;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'inclusion:change' => [
					'definition' => [
						'type' => 'TEXT',
					],
					'after' => 'description',
				],
				'activity' => [
					'alter' => "ALTER TABLE products CHANGE COLUMN activity activity SMALLINT(5) NOT NULL DEFAULT '0';",
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
				'farm_id' => [
					'alter' => "ALTER TABLE products DROP COLUMN farm_id;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'procedure' => [
					'alter' => "ALTER TABLE products DROP COLUMN `procedure`;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'stocks' => [
					'alter' => "ALTER TABLE products DROP COLUMN `stocks`;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'measurement' => [
					'alter' => "ALTER TABLE products DROP COLUMN measurement;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'price' => [
					'alter' => "ALTER TABLE products DROP COLUMN price;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'old_price' => [
					'alter' => "ALTER TABLE products DROP COLUMN old_price;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'location_id' => [
					'alter' => "ALTER TABLE products DROP COLUMN location_id;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'delivery_option_id' => [
					'alter' => "ALTER TABLE products DROP COLUMN delivery_option_id;",
					'altered' => [
						'status' => 'removed',
					],
				],
			],
			'products_category',
			'products_subcategory',
			'products_location' => [
				'user_id' => [
					'alter' => "ALTER TABLE products_location DROP COLUMN user_id;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'product_id' => [
					'alter' => "ALTER TABLE products_location DROP COLUMN product_id;",
					'altered' => [
						'status' => 'removed',
					],
				],
				'product_id' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
						'default' => '0',
						'null' => false,
					],
					'add_key' => "ALTER TABLE products_location ADD INDEX product_id (product_id);",
				],
				'location_id' => [
					'alter' => "ALTER TABLE products_location CHANGE COLUMN location_id farm_id INT(10) NOT NULL DEFAULT '0' AFTER product_id;",
					'altered' => [
						'status' => 'changed to farm_id',
						'column' => 'farm_id',
					],
				],
				'price' => [
					'definition' => [
						'type' => 'DECIMAL',
						'constraint' => '10,2',
						'default' => '0.00',
						'null' => false,
					],
				],
				'measurement' => [
					'definition' => [
						'type' => 'VARCHAR',
						'constraint' => '10',
						'default' => 'kg',
					],
				],
				'stocks' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
					],
				],
				'added' => [
					'definition' => [
						'type' => 'DATETIME',
					],
				],
				'updated' => [
					'definition' => [
						'type' => 'DATETIME',
					],
				],
				'added:change' => [
					'alter' => "ALTER TABLE `products_location` CHANGE COLUMN `added` `added` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `stocks`;",
					'altered' => [
						'status' => 'changed default value',
					],
				],
				'updated:change' => [
					'alter' => "ALTER TABLE `products_location` CHANGE COLUMN `updated` `updated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `added`;",
					'altered' => [
						'status' => 'changed default value',
					],
				],
			],
			'products_measurement',
			'products_photo' => [
				'url_path' => [
					'definition' => [
						'type' => 'LONGTEXT',
						'default' => NULL,
						'null' => true,
					],
				],
				'status' => [
					'definition' => [
						'type' => 'TINYINT',
						'constraint' => '1',
						'default' => '1',
						'null' => false,
					],
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
				],
				'attribute' => [
					'definition' => [
						'type' => 'TEXT',
					],
				],
			],
		];

		return $data;
	}

}