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

	public function get($where=true, $limit=false, $justdata=true, $row=false)
	{
		if ($where != false) {
			if (is_bool($where) AND $where == true) {
				if (!is_bool($limit) AND is_numeric($limit)) {
					$this->class->db->limit($limit);
				}
				$data = $this->class->db->get_where('products', ['activity' => 1]);
			} elseif (is_array($where) OR is_string($where)) {
				if (is_array($where)) {
					$where['activity'] = 1;
				} else {
					if (strlen(trim($where)) > 0) {
						$where .= ' AND activity = 1';
					} else {
						$where = 'activity = 1';
					}
				}
				if (!is_bool($limit) AND is_numeric($limit)) {
					$this->class->db->limit($limit);
				}
				$data = $this->class->db->get_where('products', $where);
			}
			if (isset($data) AND $data->num_rows()) {
				$products = $data->result_array();
				$results = [];
				foreach ($products as $key => $product) {
					$product_id = $products[$key]['id'];
					$location = $this->class->gm_db->get('user_locations', ['id' => $product['location_id']], 'row');
					// debug($location, 'stop');
					if ($location) {
						$farm = $this->class->gm_db->get('user_farms', ['id' => $location['farm_id']], 'row');
						$products[$key]['farm'] = $farm['farm_name'];
					}

					$category = $this->class->gm_db->get('products_category', ['id' => $product['category_id']], 'row');
					if ($category) {
						$products[$key]['category'] = $category['label'];
					}
					if ($justdata) {
						unset($products[$key]['id']);
						unset($products[$key]['user_id']);
						unset($products[$key]['delivery_option_id']);
						unset($products[$key]['activity']);
						unset($products[$key]['category_id']);
						unset($products[$key]['location_id']);
						unset($products[$key]['added']);
					}
					$products[$key]['id'] = $product_id;
				}
				// debug($products, 'stop');
				if ($row) {
					return $products[0];
				} else {
					return $products;
				}
			}
		}
		return false;
	}

	public function count()
	{
		return $this->class->db->from('products')->count_all_results();
	}

	public function new($new=false, $table='products')
	{
		if ($new) {
			$this->class->db->insert($table, $new);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}

	public function save($set=false, $where=[], $table='products')
	{
		if ($set) {
			$this->class->db->update($table, $set, $where);
			return true;
		}
		return false;
	}

	public function new_location($new=false)
	{
		if ($new) {
			$this->class->db->insert('products_location', $new);
			$affected = $this->class->db->affected_rows();
			if ($affected) return $affected;
		}
		return false;
	}

	public function save_location($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('products_location', $set, $where);
			return true;
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
			'procedure' => ['type' => 'TEXT', 'null' => true],
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
			'activity' => [
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
		$this->class->dbforge->add_key('activity');
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

		sleep(5);
		$categories = [['label' => 'Leafy', 'value' => 'Leafy', 'photo' => 'assets/images/leafy.png'],
		['label' => 'Root', 'value' => 'Root', 'photo' => 'assets/images/root.png'],
		['label' => 'Cruciferous', 'value' => 'Cruciferous', 'photo' => 'assets/images/cruciferous.png'],
		['label' => 'Marrow', 'value' => 'Marrow', 'photo' => 'assets/images/marrow.png'],
		['label' => 'Stem', 'value' => 'Stem', 'photo' => 'assets/images/plant-stem.png'],
		['label' => 'Allium', 'value' => 'Allium', 'photo' => 'assets/images/allium.png']];
		$this->class->db->insert_batch('products_category', $categories);


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

		sleep(5);
		$measurements = [['label' => 'Kilo', 'value' => 'kg'],
		['label' => 'Bundle', 'value' => 'bundle'],
		['label' => 'Box', 'value' => 'box']];
		$this->class->db->insert_batch('products_measurement', $measurements);

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