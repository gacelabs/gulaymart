<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products {

	protected $class = false; 
	public $has_session = false; 
	public $profile = false;
	public $device_id = false;

	public function __construct()
	{
		$this->class =& get_instance();
		if (!$this->class->db->table_exists('products')) {
			/*create table for the first time*/
			$is_created = $this->create_products_table();
			if (empty($is_created)) {
				throw new Exception("Table Users not created!", 403);
			}
		}
		$this->has_session = $this->class->session->userdata('profile') ? true : false;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function get($where=true, $row=false, $justdata=true)
	{
		if ($where != false) {
			if (is_bool($where) AND $where == true) {
				$data = $this->class->db->get('products');
			} elseif (is_array($where) OR is_string($where)) {
				$data = $this->class->db->get_where('products', $where);
			}
			if (isset($data) AND $data->num_rows()) {
				$products = $data->result_array();
				foreach ($products as $key => $product) {
					$location = $this->class->gm_db->get('user_locations', ['id' => $product['location_id']], 'row');
					// debug($location, 'stop');
					if ($location) {
						$products[$key]['latitude'] = $location['lat'];
						$products[$key]['longitude'] = $location['lng'];
					}
					if ($justdata) {
						unset($products[$key]['id']);
						unset($products[$key]['user_id']);
						unset($products[$key]['delivery_option_id']);
						unset($products[$key]['activity_id']);
						unset($products[$key]['category_id']);
						unset($products[$key]['location_id']);
						unset($products[$key]['added']);
					}
				}
				// debug($products, 'stop');
				return $products;
			}
		}
		return false;
	}

	public function new($new=false)
	{
		if ($new) {
			$this->class->db->insert('products', $new);
			$id = $this->class->db->insert_id();
			if ($id) return true;
		}
		return false;
	}

	private function create_products_table()
	{
		$this->class->load->dbforge();

		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'name' => ['type' => 'TINYTEXT', 'null' => true],
			'description' => ['type' => 'TEXT', 'null' => true],
			'stocks' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'measurement' => [
				'type' => 'VARCHAR',
				'constraint' => '10',
				'default' => 'kg',
			],
			"current_price decimal(10,2) NOT NULL DEFAULT '0.00'",
			"old_price decimal(10,2) NOT NULL DEFAULT '0.00'",
			'delivery_option_id' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
			'activity_id' => [
				'type' => 'SMALLINT',
				'default' => '1',
			],
			'category_id' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
			'location_id' => [
				'type' => 'INT',
				'default' => '0',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('delivery_option_id');
		$this->class->dbforge->add_key('activity_id');
		$this->class->dbforge->add_key('category_id');
		$this->class->dbforge->add_key('location_id');
		$table_data = $this->class->dbforge->create_table('products', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'label varchar(100) DEFAULT NULL',
			'value varchar(50) DEFAULT NULL',
			'photo longtext',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->create_table('products_category', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'user_id int NOT NULL',
			'product_id int NOT NULL',
			'location_id int NOT NULL'
		]);
		$this->class->dbforge->create_table('products_location', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'label tinytext',
			'value tinytext',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->create_table('products_measurement', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			"product_id int NOT NULL DEFAULT '0'",
			"name tinytext",
			"description text",
			"path longtext",
			"is_main tinyint(1) NOT NULL DEFAULT '0'",
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('is_main');
		$this->class->dbforge->create_table('products_photo', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		return $table_data;
	}

	/*public function drop()
	{
		$this->class->db->query("DROP TABLE IF EXISTS products;");
		$this->class->db->query("DROP TABLE IF EXISTS products_category;");
		$this->class->db->query("DROP TABLE IF EXISTS products_location;");
		$this->class->db->query("DROP TABLE IF EXISTS products_measurement;");
		$this->class->db->query("DROP TABLE IF EXISTS products_photo;");
	}*/
}