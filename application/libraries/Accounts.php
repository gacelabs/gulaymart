<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts {

	protected $class = FALSE; 
	public $has_session = FALSE; 
	public $profile = FALSE;
	public $device_id = FALSE;

	public function __construct()
	{
		$this->class =& get_instance();
		if (!$this->class->db->table_exists('users')) {
			/*create table for the first time*/
			$is_created = $this->create_users_table();
			if (empty($is_created)) {
				throw new Exception("Table Users not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('user_farms')) {
			/*create table for the first time*/
			$is_created = $this->create_user_farms_table();
			if (empty($is_created)) {
				throw new Exception("Table user_farms not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('user_settings')) {
			/*create table for the first time*/
			$is_created = $this->create_user_settings_table();
			if (empty($is_created)) {
				throw new Exception("Table user_Settings not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('galleries')) {
			/*create table for the first time*/
			$is_created = $this->create_galleries_table();
			if (empty($is_created)) {
				throw new Exception("Table Galleries not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('email_session')) {
			/*create table for the first time*/
			$is_created = $this->create_email_session_table();
			if (empty($is_created)) {
				throw new Exception("Table Email Session not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('user_locations')) {
			/*create table for the first time*/
			$is_created = $this->create_user_locations_table();
			if (empty($is_created)) {
				throw new Exception("Table User Locations not created!", 403);
			}
		}
		if (!$this->class->db->table_exists('user_profiles')) {
			/*create table for the first time*/
			$is_created = $this->create_user_profiles_table();
			if (empty($is_created)) {
				throw new Exception("Table User Profiles not created!", 403);
			}
		}
		$this->has_session = $this->class->session->userdata('profile') ? TRUE : FALSE;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function check_credits($credits=FALSE, $table='users', $function=FALSE)
	{
		$allowed = FALSE; $user = FALSE; $msg = '';
		if ($credits) {
			if (isset($credits['email_address']) AND isset($credits['password'])) {
				if (isset($credits['ismd5']) AND $credits['ismd5']) {
					unset($credits['ismd5']);
				} else {
					$credits['password'] = md5($credits['password']);
				}
				$email_address_query = $this->class->db->get_where($table, ['email_address' => $credits['email_address']]);

				$enter = FALSE;
				if ($email_address_query->num_rows() > 0) {
					if ($function == 'register') {
						$enter = $allowed = TRUE;
					} elseif ($function == 'login') {
						$enter = TRUE;
					}
				}
				// debug($enter);

				if ($enter) {
					$query = $this->class->db->get_where($table, $credits);
					// debug($query->row_array());
					if ($query->num_rows()) {
						$allowed = TRUE;
						$user = $query->row_array();
					} else {
						$msg = 'Invalid password!';
					}
				} else {
					$msg = $function == 'login' ? 'Email address does not exist!' : 'Email address already exist!';
				}
			}
		}

		return ['allowed' => $allowed, 'message' => $msg, 'profile' => $user];
	}

	public function register($post=FALSE, $redirect_url='', $table='users')
	{
		$allowed = FALSE; $user = FALSE;; $passed = TRUE; $msg = '';
		if ($post) {
			// debug($post);
			if (isset($post['password']) AND isset($post['re_password'])) {
				if ($post['re_password'] !== $post['password']) {
					$passed = FALSE;
					$msg = 'Password mismatch!';
				}
			}
			if (isset($post['email_address']) AND (isset($post['password']) AND strlen(trim($post['password'])) > 0)) {
				$credits = ['email_address'=>$post['email_address'], 'password'=>$post['password']];
				$return = $this->check_credits($credits, $table, __FUNCTION__);
				// debug(isset($return['allowed']) AND $return['allowed'] == FALSE);
				if ($passed) {
					if (isset($return['allowed']) AND $return['allowed'] == FALSE) {
						$post['password'] = md5($post['password']);
						$query = $this->class->db->insert($table, $post);
						$id = $this->class->db->insert_id();
						// debug($id);
						if ($id) {
							$msg = '';
							$allowed = TRUE;
							$qry = $this->class->db->get_where($table, ['id' => $id]);
							$user = $qry->row_array();
							// debug($user);
							unset($user['password']);
							unset($user['re_password']);
							$this->class->session->set_userdata('profile', $user);
							$this->profile = $user;
						}
						if ($redirect_url != '') {
							redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
						}
					} else {
						$msg = 'Account already exist! Try signing in';
					}
				}
			} else {
				if (isset($post['email_address']) AND empty(trim($post['email_address']))) {
					$msg = 'Email address is required!';
				} elseif (isset($post['password']) AND empty(trim($post['password']))) {
					$msg = 'Password is required!';
				} else {
					$msg = 'Email address and password are required!';
				}
			}
		} else {
			$msg = 'Empty request found!';
		}

		return ['allowed' => $allowed, 'message' => $msg, 'profile' => $user];
	}

	public function login($credits=FALSE, $redirect_url='', $table='users')
	{
		if ($credits != FALSE AND is_array($credits)) {
			/*user is logging in*/
			$return = $this->check_credits($credits, $table, __FUNCTION__);
			if (isset($return['allowed']) AND $return['allowed']) {
				unset($return['profile']['password']);
				unset($return['profile']['re_password']);
				$this->class->session->set_userdata('profile', $return['profile']);
				$this->refetch();
				if ($redirect_url != '') {
					redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
				} else {
					return TRUE;
				}
			}
		}
		/*else the user is logged in or session active*/
		return FALSE;
	}

	public function fb_login($post=FALSE)
	{
		// debug($post);
		if ($post != FALSE AND (is_array($post) AND count($post) > 0)) {
			/*user is logging in*/
			$fbuser = $this->class->db->get_where('users', $post);
			if ($fbuser->num_rows() > 0) {
				$user = $fbuser->row_array();
			} else { /*register this user*/
				$query = $this->class->db->insert('users', $post);
				$id = $this->class->db->insert_id();
				$qry = $this->class->db->get_where('users', ['id' => $id]);
				$user = $qry->row_array();
			}
			// debug($user);
			unset($user['password']); unset($user['re_password']);
			$this->class->session->set_userdata('profile', $user);
			$this->profile = $user;
			return TRUE;
		}
		/*else the user is logged in or session active*/
		return FALSE;
	}

	public function logout($redirect_url='')
	{
		$profile = $this->class->session->userdata('profile');
		$this->class->session->unset_userdata('profile');
		$this->class->session->sess_destroy();
		$this->profile = FALSE;
		$this->has_session = FALSE;
		// $this->class->pushthru->trigger('logout-profile', 'browser-'.$this->device_id.'-sessions-logout', $profile);
		// redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
		if (is_bool($redirect_url) AND $redirect_url == TRUE) {
			return TRUE;
		} else {
			redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
		}
	}

	public function refetch()
	{
		$user = $this->class->db->get_where('users', ['id' => $this->profile['id']]);
		if ($user->num_rows()) {
			$request = $user->row_array();
			unset($request['password']); unset($request['re_password']);
			// debug($request, 'stop');
			$request['fullname'] = '';
			$request['firstname'] = '';
			// $request['farms'] = $this->assemble_table_fields('user_farms');
			/*profiles*/
			$profile = $this->class->gm_db->get('user_profiles', ['user_id' => $this->profile['id']], 'row');
			if ($profile) {
				$request['fullname'] = trim($profile['firstname'].' '.$profile['lastname']);
				$request['firstname'] = $profile['firstname'];
				$profile['latlng'] = '';
				if (!empty($profile['lat']) AND !empty($profile['lng'])) {
					$profile['latlng'] = json_encode(['lat' => (float)$profile['lat'], 'lng' => (float)$profile['lng']]);
				}
			}
			$request['profile'] = $profile;

			/*farms*/
			$request['farms'] = [];
			$farms = $this->class->db->query("SELECT uf.*, ul.id AS location_id, ul.lat, ul.lng FROM user_farms uf LEFT JOIN user_locations ul ON ul.farm_id = uf.id");
			if ($farms->num_rows() > 0) {
				$request['farms'] = $farms->result_array();
			}

			/*categories*/
			$categories = $this->class->gm_db->get('products_category');
			$request['categories'] = [];
			if ($categories) {
				$request['categories'] = $categories;
			}

			/*settings*/
			$settings = $this->class->gm_db->get('user_settings', ['user_id' => $this->profile['id']], 'result', 'setting, value');
			$settings_data = [];
			if ($settings) {
				foreach ($settings as $key => $set) {
					$settings_data[$set['setting']] = $set['value'];
				}
			}
			$request['settings'] = $settings_data;

			if (strlen(trim($request['fullname'])) AND $request['profile']) {
				$request['is_profile_complete'] = 1;
				$this->class->db->update('users', ['is_profile_complete' => 1], ['id' => $request['id']]);
			}

			$this->class->session->set_userdata('profile', $request);
			$this->profile = $request;
			// $this->device_id = format_ip();
			// debug($this->profile, 'stop');
			return $this;
		}
		return FALSE;
	}

	private function assemble_table_fields($table=FALSE)
	{
		if ($table) {
			$table_fields = $this->class->db->field_data($table);
			$field_data = [];
			foreach ($table_fields as $key => $field) {
				$field_data[$field->name] = FALSE;
			}
			// debug($field_data);
			return $field_data;
		}
		throw new Exception("Add user_farms table in DB");	
	}

	private function create_users_table()
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

	private function create_user_farms_table()
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
			'farm_name text',
			'address longtext',
			"country_code varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''",
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

	private function create_user_settings_table()
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

	private function create_galleries_table()
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

	private function create_email_session_table()
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

	private function create_user_locations_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '10',
				'auto_increment' => TRUE
			],
			'farm_id int DEFAULT NULL',
			'lat varchar(100) DEFAULT NULL',
			'lng varchar(100) DEFAULT NULL',
			'added datetime DEFAULT CURRENT_TIMESTAMP',
			'updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('farm_id');
		$table_data = $this->class->dbforge->create_table('user_locations', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}

	private function create_user_profiles_table()
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
			"lat VARCHAR(100) NULL DEFAULT NULL",
			"lng VARCHAR(100) NULL DEFAULT NULL",
			"address_1 VARCHAR(255) NULL DEFAULT NULL",
			"address_2 VARCHAR(255) NULL DEFAULT NULL",
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
}