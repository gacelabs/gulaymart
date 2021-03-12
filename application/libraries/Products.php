<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products {

	protected $class = FALSE; 
	public $has_session = FALSE; 
	public $profile = FALSE;
	public $device_id = FALSE;

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
		$this->has_session = $this->class->session->userdata('profile') ? TRUE : FALSE;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function save($new=false)
	{
		if ($new) {
			$this->class->db->new('products');
		}
	}

	private function create_products_table()
	{
		$this->class->load->dbforge();

		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'name' => ['type' => 'TINYTEXT', 'null' => TRUE],
			'description' => ['type' => 'TEXT', 'null' => TRUE],
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
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('delivery_option_id');
		$this->class->dbforge->add_key('activity_id');
		$this->class->dbforge->add_key('category_id');
		$this->class->dbforge->add_key('location_id');
		$table_data = $this->class->dbforge->create_table('products', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'label varchar(100) DEFAULT NULL',
			'value varchar(50) DEFAULT NULL',
			'photo longtext',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->create_table('products_category', FALSE, [
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
		$this->class->dbforge->create_table('products_location', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'label tinytext',
			'value tinytext',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->create_table('products_measurement', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			"product_id int NOT NULL DEFAULT '0'",
			"name tinytext",
			"description text",
			"path longtext",
			"is_main tinyint(1) NOT NULL DEFAULT '0'",
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('is_main');
		$this->class->dbforge->create_table('products_photo', FALSE, [
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