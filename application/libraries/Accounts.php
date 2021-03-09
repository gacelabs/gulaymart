<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts {

	private $class = FALSE; 
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
			} else {
				$this->create_profiles_table();
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
							redirect(base_url($redirect_url == 'home' ? '' : $redirect_url));
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
		// debug($credits);
		if ($credits != FALSE AND is_array($credits) AND $this->has_session == FALSE) {
			/*user is logging in*/
			$return = $this->check_credits($credits, $table, __FUNCTION__);
			// debug($return);
			if (isset($return['allowed']) AND $return['allowed']) {
				unset($return['profile']['password']);
				unset($return['profile']['re_password']);
				$this->class->session->set_userdata('profile', $return['profile']);
				$this->profile = $return['profile'];
				if ($redirect_url != '') {
					redirect(base_url($redirect_url == 'home' ? '' : $redirect_url));
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
		redirect(base_url($redirect_url == 'home' ? '' : $redirect_url));
	}

	public function refetch()
	{
		$user = $this->class->db->get_where('users', ['id' => $this->profile['id']]);
		if ($user->num_rows()) {
			$request = $user->row_array();
			unset($request['password']);
			unset($request['re_password']);

			$request['info'] = $this->assemble_table_fields('profiles');
			$info = $this->class->db->get_where('profiles', ['user_id' => $this->profile['id']]);
			if ($info->num_rows() > 0) {
				$request['info'] = $info->row_array();
			}
			// if (is_null($request['ip_address'])) {
			$request['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$this->class->db->update('users', ['ip_address' => $request['ip_address']], ['id' => $this->profile['id']]);
			// }
			// debug($request);
			$this->class->session->set_userdata('profile', $request);
			$this->profile = $request;
			$this->device_id = format_ip();
			// debug($this);
			return $this->profile;
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
		throw new Exception("Add profiles table in DB");	
	}

	private function create_users_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '11',
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
			'ip_address' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			'added DATETIME DEFAULT CURRENT_TIMESTAMP',
			'updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$table_data = $this->class->dbforge->create_table('users', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}

	private function create_profiles_table()
	{
		$this->class->load->dbforge();
		$this->class->dbforge->add_field([
			'id' => [
				'type' => 'INT',
				'constraint' => '11',
				'auto_increment' => TRUE
			],
			'user_id' => [
				'type' => 'INT',
				'constraint' => '11',
			],
			'firstname' => [
				'type' => 'VARCHAR',
				'constraint' => '20',
				'null' => TRUE,
			],
			'lastname' => [
				'type' => 'VARCHAR',
				'constraint' => '50',
				'null' => TRUE,
			],
			"gender enum('Male','Female','Others') DEFAULT NULL",
			"country_code varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''",
		]);
		$this->class->dbforge->add_key('id', TRUE);
		$this->class->dbforge->add_key('user_id');
		$table_data = $this->class->dbforge->create_table('profiles', FALSE, [
			'ENGINE' => 'InnoDB',
			'DEFAULT CHARSET' => 'utf8'
		]);
		return $table_data;
	}
}