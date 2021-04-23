<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreateDev {

	protected $class = FALSE; 

	public function __construct()
	{
		$this->class =& get_instance();
	}

	public function create_users_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'fb_id' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'null' => TRUE,
			],
			'email_address' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			'password' => [
				'type' => 'VARCHAR',
				'constraint' => '32',
				'null' => TRUE,
			],
			'device_id' => [
				'type' => 'VARCHAR',
				'constraint' => '12',
				'default' => NULL,
				'null' => true,
			],
			'lat' => [
				'type' => 'VARCHAR',
				'constraint' => '32',
				'null' => TRUE,
			],
			'lng' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => NULL,
			],
			're_password' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => NULL,
			],
			'is_admin' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'is_profile_complete' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'is_agreed_terms' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('fb_id');
		$table_data = $this->class->dbforge->create_table('users', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		
		
		return $table_data;
	}

	public function create_user_farms_table()
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
			'name text DEFAULT NULL',
			'tagline' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'banner' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'cover_pic' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'profile_pic' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'about' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'messenger' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'youtube' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'instagram' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'facebook' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'ip_address' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('user_farms', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_user_farm_locations_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'farm_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			"lat VARCHAR(100) NULL DEFAULT NULL",
			"lng VARCHAR(100) NULL DEFAULT NULL",
			"address_1 VARCHAR(255) NULL DEFAULT NULL",
			"address_2 VARCHAR(255) NULL DEFAULT NULL",
			'ip_address' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			'active' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('farm_id');
		$table_data = $this->class->dbforge->create_table('user_farm_locations', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_user_settings_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'setting' => [
				'type' => 'VARCHAR',
				'constraint' => '20',
				'null' => TRUE,
			],
			'value' => ['type' => 'longtext'],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('user_settings', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_galleries_table()
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
				'null' => FALSE,
			],
			'is_admin' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'name' => ['type' => 'TEXT'],
			'url_path' => ['type' => 'TEXT'],
			'status' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '1',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('galleries', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_email_session_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'session_id' => ['type' => 'TEXT'],
			"past datetime DEFAULT CURRENT_TIMESTAMP",
			'deleted' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$table_data = $this->class->dbforge->create_table('email_session', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_user_shippings_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'user_id int DEFAULT NULL',
			"lat VARCHAR(100) NULL DEFAULT NULL",
			"lng VARCHAR(100) NULL DEFAULT NULL",
			"address_1 VARCHAR(255) NULL DEFAULT NULL",
			"address_2 VARCHAR(255) NULL DEFAULT NULL",
			'ip_address' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			'active' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => false,
				'default' => '0',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('user_shippings', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_user_profiles_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			"user_id INT NOT NULL DEFAULT '0'",
			"firstname VARCHAR(50) NULL DEFAULT NULL",
			"lastname VARCHAR(100) NULL DEFAULT NULL",
			"birth_month VARCHAR(20) NULL DEFAULT NULL",
			"birth_year VARCHAR(5) NULL DEFAULT NULL",
			"phone VARCHAR(15) NULL DEFAULT NULL",
			"added DATETIME NULL DEFAULT CURRENT_TIMESTAMP",
			"updated DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('user_profiles', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_products_table()
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
			'inclusion' => ['type' => 'TEXT', 'null' => true],
			'activity' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
			'category_id' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
			'subcategory_id' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('user_id');
		$this->class->dbforge->add_key('activity');
		$this->class->dbforge->add_key('category_id');
		$this->class->dbforge->add_key('subcategory_id');
		$table_data = $this->class->dbforge->create_table('products', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_products_category_table()
	{
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
		$table_data = $this->class->dbforge->create_table('products_category', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$categories = [['label' => 'Leafy', 'value' => 'leafy', 'photo' => 'assets/images/categories/leafy.png'],
		['label' => 'Root', 'value' => 'root', 'photo' => 'assets/images/categories/root.png'],
		['label' => 'Cruciferous', 'value' => 'cruciferous', 'photo' => 'assets/images/categories/cruciferous.png'],
		['label' => 'Marrow', 'value' => 'marrow', 'photo' => 'assets/images/categories/marrow.png'],
		['label' => 'Stem', 'value' => 'stem', 'photo' => 'assets/images/categories/stem.png'],
		['label' => 'Allium', 'value' => 'allium', 'photo' => 'assets/images/categories/allium.png']];
		$this->class->db->insert_batch('products_category', $categories);

		return $table_data;
	}

	public function create_products_subcategory_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			"cat_id int NOT NULL DEFAULT '0'",
			'label varchar(100) DEFAULT NULL',
			'value varchar(50) DEFAULT NULL',
			'photo longtext',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('cat_id');
		$table_data = $this->class->dbforge->create_table('products_subcategory', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$subcategories = [
			['cat_id' => 1, 'label' => 'Lettuce', 'value' => 'lettuce', 'photo' => 'assets/images/categories/leafy.png'],
			['cat_id' => 1, 'label' => 'Spinach', 'value' => 'spinach', 'photo' => 'assets/images/categories/leafy.png'],
			['cat_id' => 1, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/leafy.png'],

			['cat_id' => 2, 'label' => 'Carrot', 'value' => 'carrot', 'photo' => 'assets/images/categories/root.png'],
			['cat_id' => 2, 'label' => 'Potato', 'value' => 'potato', 'photo' => 'assets/images/categories/root.png'],
			['cat_id' => 2, 'label' => 'Turnip', 'value' => 'turnip', 'photo' => 'assets/images/categories/root.png'],
			['cat_id' => 2, 'label' => 'Radish', 'value' => 'radish', 'photo' => 'assets/images/categories/root.png'],
			['cat_id' => 2, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/root.png'],

			['cat_id' => 3, 'label' => 'Cabbage', 'value' => 'cabbage', 'photo' => 'assets/images/categories/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Cauliflower', 'value' => 'cauliflower', 'photo' => 'assets/images/categories/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Broccoli', 'value' => 'broccoli', 'photo' => 'assets/images/categories/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Brussels Sprout', 'value' => 'brussels-sprout', 'photo' => 'assets/images/categories/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/cruciferous.png'],

			['cat_id' => 4, 'label' => 'Squash', 'value' => 'squash', 'photo' => 'assets/images/categories/marrow.png'],
			['cat_id' => 4, 'label' => 'Cucumber', 'value' => 'cucumber', 'photo' => 'assets/images/categories/marrow.png'],
			['cat_id' => 4, 'label' => 'Zucchini', 'value' => 'zucchini', 'photo' => 'assets/images/categories/marrow.png'],
			['cat_id' => 4, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/marrow.png'],

			['cat_id' => 5, 'label' => 'Celery', 'value' => 'celery', 'photo' => 'assets/images/categories/stem.png'],
			['cat_id' => 5, 'label' => 'Asparagus', 'value' => 'asparagus', 'photo' => 'assets/images/categories/stem.png'],
			['cat_id' => 5, 'label' => 'Rosemary', 'value' => 'rosemary', 'photo' => 'assets/images/categories/stem.png'],
			['cat_id' => 5, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/stem.png'],

			['cat_id' => 6, 'label' => 'Onion', 'value' => 'onion', 'photo' => 'assets/images/categories/allium.png'],
			['cat_id' => 6, 'label' => 'Garlic', 'value' => 'garlic', 'photo' => 'assets/images/categories/allium.png'],
			['cat_id' => 6, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/categories/allium.png'],
		];
		$this->class->db->insert_batch('products_subcategory', $subcategories);

		return $table_data;
	}

	public function create_products_location_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'product_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'farm_location_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			"price decimal(10,2) NOT NULL DEFAULT '0.00'",
			'measurement' => [
				'type' => 'VARCHAR',
				'constraint' => '10',
				'default' => 'kilogram',
			],
			'stocks' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'sold' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
		]);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('farm_location_id');
		$table_data = $this->class->dbforge->create_table('products_location', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		

		return $table_data;
	}

	public function create_products_measurement_table()
	{
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
		$table_data = $this->class->dbforge->create_table('products_measurement', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$measurements = [['label' => 'Kilogram', 'value' => 'kilogram'],
		['label' => 'Bundle', 'value' => 'bundle'],
		['label' => 'Box', 'value' => 'box']];
		$this->class->db->insert_batch('products_measurement', $measurements);

		return $table_data;
	}

	public function create_products_photo_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'product_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			"name tinytext",
			"description text",
			'is_main' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0',
				'null' => false,
			],
			'url_path' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'status' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1',
				'null' => false,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('is_main');
		$table_data = $this->class->dbforge->create_table('products_photo', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_products_attribute_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'product_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'attribute' => [
				'type' => 'TEXT',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('product_id');
		$table_data = $this->class->dbforge->create_table('products_attribute', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_baskets_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'product_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'location_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'device_id' => [
				'type' => 'VARCHAR',
				'constraint' => '12',
				'default' => NULL,
				'null' => true,
			],
			'quantity' => [
				'type' => 'INT',
				'default' => '0',
				'null' => false,
			],
			'status' => [
				'type' => 'TINYINT',
				'constraint' => '2',
				'default' => '0',
				'null' => false,
			],
			'order_type' => [
				'type' => 'TINYINT',
				'constraint' => '2',
				'default' => '1',
				'null' => false,
			],
			'date_range' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => NULL,
				'null' => true,
			],
			'rawdata' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'at_date' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'at_time' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('user_id');
		$this->class->dbforge->add_key('location_id');
		$table_data = $this->class->dbforge->create_table('baskets', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
				
		return $table_data;
	}

	public function create_attributes_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'name' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'enable' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1',
				'null' => false,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$table_data = $this->class->dbforge->create_table('attributes', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$attributes = [
			['name' => 'How do you grow your plant?'],
			['name' => 'Sold ripe or unripe?'],
			['name' => 'Is the product in good shape?'],
			['name' => 'Freshness detail'],
			['name' => 'How do you package the product?']
		];
		$this->class->db->insert_batch('attributes', $attributes);

		return $table_data;
	}

	public function create_attribute_values_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'attribute_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'value' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'active' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1',
				'null' => false,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('attribute_id');
		$table_data = $this->class->dbforge->create_table('attribute_values', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		$attribute_values = [
			['attribute_id' => 1, 'value' => 'Home or commercially grown organically.'],
			['attribute_id' => 1, 'value' => 'Traditional soil-based plant.'],
			['attribute_id' => 1, 'value' => 'Grown using Hydrophonic technology.'],
			['attribute_id' => 1, 'value' => 'Utilized Acquaphonic technology.'],
			['attribute_id' => 1, 'value' => 'Used food-grade formulated plant grower.'],

			['attribute_id' => 2, 'value' => 'Riped organically, tasty and juicy.'],
			['attribute_id' => 2, 'value' => 'Sold unripe with roots intact.'],

			['attribute_id' => 3, 'value' => 'In good shape, smell, texture, and color.'],
			['attribute_id' => 3, 'value' => 'Slightly deformed, but presentable.'],

			['attribute_id' => 4, 'value' => 'Picked same day upon order.'],
			['attribute_id' => 4, 'value' => 'Freshly refrigirated.'],

			['attribute_id' => 5, 'value' => 'Delivered in an eco-friendly pouch.'],
			['attribute_id' => 5, 'value' => 'Packaged in a regular plastic bag.'],
		];
		$this->class->db->insert_batch('attribute_values', $attribute_values);

		return $table_data;
	}

	public function create_basket_transactions_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'location_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'basket_ids' => [
				'type' => 'TEXT',
				'default' => NULL,
				'null' => true,
			],
			'queue_status' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0',
				'null' => false,
			],
			'toktok_data' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('user_id');
		$this->class->dbforge->add_key('location_id');
		$table_data = $this->class->dbforge->create_table('basket_transactions', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		

		return $table_data;
	}

	public function create_messages_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'under' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'page_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			'entity_id' => [
				'type' => 'INT',
				'constraint' => '10',
				'default' => '0',
				'null' => false,
			],
			"`tab` enum('Notifications','Inquiries','Feedbacks') NOT NULL DEFAULT 'Notifications'",
			"`type` enum('System Update','Inventory','Comments','Orders') NOT NULL DEFAULT 'System Update'",
			"content LONGTEXT",
			'unread' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '1',
				'null' => false,
			],
			'datestamp' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$table_data = $this->class->dbforge->create_table('messages', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARACTER SET' => 'utf8mb4',
			'COLLATE' => 'utf8mb4_general_ci',
		]);
		

		return $table_data;
	}

	public function create_serviceable_areas_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'city' => [
				'type' => 'TINYTEXT',
				'null' => true,
			],
			'province' => [
				'type' => 'TINYTEXT',
				'null' => true,
			],
			'latlng' => [
				'type' => 'TEXT',
				'null' => true,
			],
			'place_id' => [
				'type' => 'TEXT',
				'null' => true,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$table_data = $this->class->dbforge->create_table('serviceable_areas', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		return $table_data;
	}

	public function create_baskets_merge_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => true
			],
			'order_id' => [
				'type' => 'TINYTEXT',
				'default' => NULL,
				'null' => true,
			],
			'basket_ids' => [
				'type' => 'TINYTEXT',
				'default' => NULL,
				'null' => true,
			],
			'seller' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'buyer' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'order_details' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'fee' => [
				'type' => 'DOUBLE',
				'constraint' => '10,2',
				'default' => '0.00',
				'null' => true,
			],
			'distance' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'duration' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => NULL,
				'null' => true,
			],
			'toktok_post' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$table_data = $this->class->dbforge->create_table('baskets_merge', true, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		

		return $table_data;
	}

}