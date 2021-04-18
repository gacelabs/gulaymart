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
			'messages' => [
				'type' => [
					'alter' => "ALTER TABLE messages CHANGE COLUMN `type` `type` ENUM('System Update','Inventory','Comments') NOT NULL DEFAULT 'System Update' AFTER `tab`",
					'altered' => [
						'status' => 'Added ENUM `Comments` on Length/Set'
					]
				],
				'under' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
						'default' => '0',
						'null' => FALSE,
					],
					'after' => 'id'
				],
				'page_id' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
						'default' => '0',
						'null' => FALSE,
					],
					'after' => 'user_id'
				],
				'entity_id' => [
					'definition' => [
						'type' => 'INT',
						'constraint' => '10',
						'default' => '0',
						'null' => FALSE,
					],
					'after' => 'page_id'
				],
			],
		];

		return $data;
	}

}