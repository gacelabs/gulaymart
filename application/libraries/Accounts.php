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
		$this->has_session = $this->class->session->userdata('profile') ? TRUE : FALSE;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function check_credits($credits=FALSE, $table='users', $function=FALSE)
	{
		$allowed = FALSE; $user = FALSE; $msg = '';
		if ($credits) {
			if (isset($credits['email_address']) AND (isset($credits['password']) OR isset($credits['id']))) {
				if (isset($credits['ismd5']) AND $credits['ismd5']) {
					unset($credits['ismd5']);
				} else {
					if (!isset($credits['id']) AND strlen($credits['password']) > 0) {
						$credits['password'] = md5($credits['password']);
						unset($credits['id']);
					}
				}
				$email_address_query = $this->class->db->get_where($table, ['email_address' => $credits['email_address']]);

				$enter = FALSE;
				if ($email_address_query->num_rows() > 0) {
					if ($function === 'register') {
						$enter = $allowed = TRUE;
					} elseif ($function === 'login') {
						$enter = TRUE;
					}
				}
				// debug($enter, 'stop');

				if ($enter) {
					$query = $this->class->db->get_where($table, [
						'email_address' => $credits['email_address'], 
						'password' => $credits['password']
					]);
					// debug($query->row_array(), $credits, 'stop');
					if ($query->num_rows()) {
						$allowed = TRUE;
						$user = $query->row_array();
					} else {
						if ($allowed) {
							$msg = $function == 'login' ? 'Email address does not exist!' : 'Email address already exist!';
						} else {
							$msg = 'Invalid password!';
						}
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
		$allowed = FALSE; $user = FALSE; $passed = TRUE; $msg = '';
		if ($post) {
			// debug($post);
			if (isset($post['password']) AND isset($post['re_password'])) {
				if ($post['re_password'] !== $post['password']) {
					$passed = FALSE;
					$msg = 'Password mismatch!';
				}
			}
			if ($msg == '') {
				if (isset($post['email_address']) AND (isset($post['password']) AND strlen(trim($post['password'])) > 0)) {
					$credits = ['email_address'=>$post['email_address'], 'password'=>$post['password']];
					$return = $this->check_credits($credits, $table, 'register');
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
								$this->has_session = true;
							}
							if ($redirect_url != '') {
								redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
							}
						} else {
							$msg = '<p class="text-left">Account already exist! Please try Logging In...</p>';
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
			$return = $this->check_credits($credits, $table, 'login');
			if (isset($return['allowed']) AND $return['allowed']) {
				unset($return['profile']['password']);
				unset($return['profile']['re_password']);
				if (isset($credits['remember_me']) AND $credits['remember_me'] == 'on') {
					set_cookie('remember', $return['profile']['id'], 7776000); // 90 days
				} else {
					delete_cookie('remember');
				}
				$this->class->session->set_userdata('profile', $return['profile']);
				$this->has_session = true;
				$this->profile = $return['profile'];
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
			$return = $this->check_credits(['email_address' => $post['email'], 'password' => ''], 'users', 'register');
			$update_user = (isset($return['allowed']) AND $return['allowed'] AND $return['message'] == 'Email address already exist!');
			// debug($update_user, $return, 'stop');
			
			$fbuser = $this->class->db->get_where('users', ['fb_id' => $post['id']]);
			if ($fbuser->num_rows() == 0) {
				/*no fb_id yet*/
				if ($update_user) { /*but email already exists*/
					$qry = $this->class->db->get_where('users', ['email_address' => $post['email']]);
					$user = $qry->row_array();
					if (empty($user['fb_id']) OR (isset($user['fb_id']) AND $user['fb_id'] == $post['id'])) {
						$this->class->db->update('users', ['fb_id' => $post['id']], ['id' => $user['id']]);
					} else {
						$user = FALSE;
					}
				} else {
					$this->class->db->insert('users', [
						'fb_id' => $post['id'],
						'email_address' => $post['email'],
					]);
					$id = $this->class->db->insert_id();
					$qry = $this->class->db->get_where('users', ['id' => $id]);
					$user = $qry->row_array();
				}
			} else {
				$user = $fbuser->row_array();
				if (empty($user['email_address'])) {
					$this->class->db->update('users', ['email_address' => $post['email']], ['id' => $user['id']]);
				}
			}

			if (isset($post['name'])) {
				$fullname = explode(' ', trim($post['name']));
				if (count($fullname)) {
					$fbprofile = $this->class->gm_db->get('user_profiles', ['user_id' => $user['id']], 'row');
					if ($fbprofile AND (isset($fbprofile['firstname']) AND empty($fbprofile['firstname']))) {
						$this->class->db->insert('user_profiles', [
							'firstname' => trim($fullname[0]),
							'lastname' => trim($fullname[count($fullname)-1]),
							'user_id' => $user['id'],
						]);
					}
				}
			}
			// debug($user);
			$this->has_session = true;
			$this->profile = $user;
			if (!empty($user)) {
				set_cookie('remember', $user['id'], 7776000); // 90 days
				$this->refetch();
			}
			return TRUE;
		}
		/*else the user is logged in or session active*/
		return FALSE;
	}

	public function logout($redirect_url='')
	{
		// $profile = $this->class->session->userdata('profile');
		$this->class->session->unset_userdata('profile');
		// $this->class->session->sess_destroy();
		$this->profile = FALSE;
		$this->has_session = FALSE;
		delete_cookie('remember');
		if (is_bool($redirect_url) AND $redirect_url == TRUE) {
			return TRUE;
		} else {
			redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
		}
	}

	public function refetch($user_id=0)
	{
		if ($user_id > 0 AND $this->has_session == FALSE) {
			$mobile_user = $this->class->gm_db->get('users', ['id' => $user_id], 'row');
			if ($mobile_user) $this->login($mobile_user);
		}
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
				$request['lastname'] = $profile['lastname'];
			}
			$request['profile'] = $profile;

			$shippings = $this->class->gm_db->get('user_shippings', ['user_id' => $this->profile['id']]);
			$request['shippings'] = $shippings;
			if ($shippings) {
				foreach ($shippings as $key => $shipping) {
					if ($shipping['active'] == 1) {
						$this->class->latlng = ['lat' => $shipping['lat'], 'lng' => $shipping['lng']];
						$latlng = get_cookie('prev_latlng', true);
						if (empty($latlng)) {
							set_cookie('prev_latlng', serialize($this->class->latlng), 7776000); // 90 days
						}
						
						$address = explode(',', $shipping['address_2']);
						$city = remove_multi_space(str_replace('city of', '', strtolower(isset($address[0]) ? $address[0] : '')), true);
						$city = remove_multi_space(str_replace('city', '', strtolower($city)), true);
						$request['current_city'] = $city;
						$this->class->current_city = $city;
						$current_city = get_cookie('current_city', true);
						if (empty($current_city)) {
							set_cookie('current_city', trim($city), 7776000); // 90 days
						}
						/*$latlng = get_cookie('prev_latlng', true);
						if (!empty($latlng)) {
							$this->class->latlng = unserialize($latlng);
						} else {
							$this->class->latlng = ['lat' => $shipping['lat'], 'lng' => $shipping['lng']];
							set_cookie('prev_latlng', serialize($this->class->latlng), 7776000); // 90 days
						}*/
						break;
					}
				}
			}
			if (empty($request['lat']) AND empty($request['lng'])) {
				// debug($this->class->latlng, $request, 'stop');
				$request['lat'] = $this->class->latlng['lat'];
				$request['lng'] = $this->class->latlng['lng'];
			}
			
			$request = get_global_values($request);

			/*settings*/
			$settings = $this->class->gm_db->get('user_settings', ['user_id' => $this->profile['id']], 'result', 'setting, value');
			$settings_data = [];
			if ($settings) {
				foreach ($settings as $key => $set) {
					$settings_data[$set['setting']] = $set['value'];
				}
			}
			$request['settings'] = $settings_data;

			if ($profile AND $shippings AND $request['is_profile_complete'] == 0) {
				$request['is_profile_complete'] = 1;
				$this->class->db->update('users', ['is_profile_complete' => 1], ['id' => $request['id']]);
			}
			$this->device_id = device_id();
			if (empty($request['device_id'])) {
				$this->class->db->update('users', ['device_id' => $this->device_id], ['id' => $request['id']]);
			}
			$request['device_id'] = $this->device_id;
			
			$toktok_operator = $this->class->gm_db->get('operators', ['user_id' => $this->profile['id']], 'row');
			$request['operator'] = $toktok_operator;
			$request['operator_riders'] = false;
			if ($toktok_operator) {
				$operator_riders = $this->class->gm_db->get('operator_riders', ['operator_id' => $toktok_operator['id']]);
				$request['operator_riders'] = $operator_riders;
			}

			$this->class->session->set_userdata('profile', $request);
			$this->profile = $request;
			$this->has_session = TRUE;
			// debug($this->profile, 'stop');
			return $this;
		} else {
			$this->profile = FALSE;
			$this->has_session = FALSE;
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
}