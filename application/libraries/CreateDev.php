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
			're_password' => [
				'type' => 'VARCHAR',
				'constraint' => '32',
				'null' => TRUE,
			],
			'is_admin' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => TRUE,
				'default' => '0',
			],
			'is_profile_complete' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => '0',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('fb_id');
		$table_data = $this->class->dbforge->create_table('users', FALSE, [
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
			'lat varchar(100) DEFAULT NULL',
			'lng varchar(100) DEFAULT NULL',
			"address_1 VARCHAR(255) NULL DEFAULT NULL",
			"address_2 VARCHAR(255) NULL DEFAULT NULL",
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
		$table_data = $this->class->dbforge->create_table('user_farms', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}

	public function create_user_farm_locations_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
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
				'null' => TRUE,
				'default' => '0',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('farm_id');
		$table_data = $this->class->dbforge->create_table('user_farm_locations', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}

	public function create_user_farm_contents_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'farm_id' => [
				'type' => 'INT',
				'constraint' => '10',
			],
			'products longtext',
			"story_title VARCHAR(50) NULL DEFAULT NULL",
			'story_content' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true
			],
			'galleries longtext',
			'about' => [
				'type' => 'LONGTEXT',
				'default' => NULL,
				'null' => true
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('farm_id');
		$table_data = $this->class->dbforge->create_table('user_farm_contents', FALSE, [
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
		$table_data = $this->class->dbforge->create_table('user_settings', FALSE, [
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
				'null' => TRUE,
				'default' => '0',
			],
			'name' => ['type' => 'TEXT'],
			'file_path' => ['type' => 'TEXT'],
			'url_path' => ['type' => 'TEXT'],
			'status' => [
				'type' => 'TINYINT',
				'constraint' => '1',
				'null' => TRUE,
				'default' => '1',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('galleries', FALSE, [
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
				'null' => TRUE,
				'default' => '0',
			],
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$table_data = $this->class->dbforge->create_table('email_session', FALSE, [
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
				'null' => TRUE,
				'default' => '0',
			],
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('user_shippings', FALSE, [
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
		$table_data = $this->class->dbforge->create_table('user_profiles', FALSE, [
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
			'farm_id' => [
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
			"price decimal(10,2) NOT NULL DEFAULT '0.00'",
			"old_price decimal(10,2) NOT NULL DEFAULT '0.00'",
			'delivery_option_id' => [
				'type' => 'SMALLINT',
				'default' => '0',
			],
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
			'location_id' => [
				'type' => 'INT',
				'default' => '0',
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('user_id');
		$this->class->dbforge->add_key('farm_id');
		$this->class->dbforge->add_key('delivery_option_id');
		$this->class->dbforge->add_key('activity');
		$this->class->dbforge->add_key('category_id');
		$this->class->dbforge->add_key('subcategory_id');
		$this->class->dbforge->add_key('location_id');
		$table_data = $this->class->dbforge->create_table('products', false, [
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
		$table_data = $this->class->dbforge->create_table('products_category', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$categories = [['label' => 'Leafy', 'value' => 'leafy', 'photo' => 'assets/images/leafy.png'],
		['label' => 'Root', 'value' => 'root', 'photo' => 'assets/images/root.png'],
		['label' => 'Cruciferous', 'value' => 'cruciferous', 'photo' => 'assets/images/cruciferous.png'],
		['label' => 'Marrow', 'value' => 'marrow', 'photo' => 'assets/images/marrow.png'],
		['label' => 'Stem', 'value' => 'stem', 'photo' => 'assets/images/plant-stem.png'],
		['label' => 'Allium', 'value' => 'allium', 'photo' => 'assets/images/allium.png']];
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
		$table_data = $this->class->dbforge->create_table('products_subcategory', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$subcategories = [
			['cat_id' => 1, 'label' => 'Lettuce', 'value' => 'lettuce', 'photo' => 'assets/images/leafy.png'],
			['cat_id' => 1, 'label' => 'Spinach', 'value' => 'spinach', 'photo' => 'assets/images/leafy.png'],
			['cat_id' => 1, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/leafy.png'],

			['cat_id' => 2, 'label' => 'Carrot', 'value' => 'carrot', 'photo' => 'assets/images/root.png'],
			['cat_id' => 2, 'label' => 'Potato', 'value' => 'potato', 'photo' => 'assets/images/root.png'],
			['cat_id' => 2, 'label' => 'Turnip', 'value' => 'turnip', 'photo' => 'assets/images/root.png'],
			['cat_id' => 2, 'label' => 'Radish', 'value' => 'radish', 'photo' => 'assets/images/root.png'],
			['cat_id' => 2, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/root.png'],

			['cat_id' => 3, 'label' => 'Cabbage', 'value' => 'cabbage', 'photo' => 'assets/images/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Cauliflower', 'value' => 'cauliflower', 'photo' => 'assets/images/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Broccoli', 'value' => 'broccoli', 'photo' => 'assets/images/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Brussels Sprout', 'value' => 'brussels-sprout', 'photo' => 'assets/images/cruciferous.png'],
			['cat_id' => 3, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/cruciferous.png'],

			['cat_id' => 4, 'label' => 'Squash', 'value' => 'squash', 'photo' => 'assets/images/marrow.png'],
			['cat_id' => 4, 'label' => 'Cucumber', 'value' => 'cucumber', 'photo' => 'assets/images/marrow.png'],
			['cat_id' => 4, 'label' => 'Zucchini', 'value' => 'zucchini', 'photo' => 'assets/images/marrow.png'],
			['cat_id' => 4, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/marrow.png'],

			['cat_id' => 5, 'label' => 'Celery', 'value' => 'celery', 'photo' => 'assets/images/plant-stem.png'],
			['cat_id' => 5, 'label' => 'Asparagus', 'value' => 'asparagus', 'photo' => 'assets/images/plant-stem.png'],
			['cat_id' => 5, 'label' => 'Rosemary', 'value' => 'rosemary', 'photo' => 'assets/images/plant-stem.png'],
			['cat_id' => 5, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/plant-stem.png'],

			['cat_id' => 6, 'label' => 'Onion', 'value' => 'onion', 'photo' => 'assets/images/allium.png'],
			['cat_id' => 6, 'label' => 'Garlic', 'value' => 'garlic', 'photo' => 'assets/images/allium.png'],
			['cat_id' => 6, 'label' => 'Other', 'value' => 'other', 'photo' => 'assets/images/allium.png'],
		];
		$this->class->db->insert_batch('products_subcategory', $subcategories);

		return $table_data;
	}

	public function create_products_location_table()
	{
		$this->class->load->dbforge();
		sleep(3);
		$this->class->dbforge->add_field([
			'user_id int NOT NULL',
			'product_id int NOT NULL',
			'location_id int NOT NULL',
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$table_data = $this->class->dbforge->create_table('products_location', false, [
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
		$table_data = $this->class->dbforge->create_table('products_measurement', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);

		sleep(5);
		$measurements = [['label' => 'Kilo', 'value' => 'kg'],
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
			"product_id int NOT NULL DEFAULT '0'",
			"name tinytext",
			"description text",
			"path longtext",
			"is_main tinyint(1) NOT NULL DEFAULT '0'",
		]);
		$this->class->dbforge->add_key('id', true);
		$this->class->dbforge->add_key('product_id');
		$this->class->dbforge->add_key('is_main');
		$table_data = $this->class->dbforge->create_table('products_photo', false, [
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
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', true);
		$table_data = $this->class->dbforge->create_table('products_attribute', false, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}

}