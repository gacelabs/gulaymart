<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users {

	protected $class = false; 
	public $has_session = false; 
	public $profile = false;
	public $device_id = false;

	public function __construct()
	{
		$this->class =& get_instance();
		$this->has_session = $this->class->session->userdata('profile') ? true : false;
		$this->profile = $this->class->session->userdata('profile');
	}

	public function get($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			$data = $this->class->db->order_by($order_by)->get_where('users', $where);
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function get_or_where($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->or_where($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('users');
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function get_in($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->where_in($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('users');
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function get_not_in($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->where_not_in($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('users');
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function get_or_in($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->or_where_in($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('users');
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function get_or_not_in($where=true, $row=false, $limit=false, $order_by='id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						$this->class->db->or_where_not_in($field, $wrow);
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->order_by($order_by)->get('users');
			if (isset($data) AND $data->num_rows()) {
				$users = $data->result_array();
				$users = $this->users_assemble($users);
				// debug($users, 'stop');
				if ($row) {
					return $users[0];
				} else {
					return $users;
				}
			}
		}
		return false;
	}

	public function count($where=false)
	{
		if ($where == false) {
			return $this->class->db->from('users')->count_all_results();
		} else {
			return $this->class->db->from('users')->where($where)->count_all_results();
		}
	}

	public function new($new=false)
	{
		if ($new) {
			$this->class->db->insert('users', $new);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}


	public function save($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('users', $set, $where);
			return true;
		}
		return false;
	}

	private function users_assemble($users=false)
	{
		if ($users) {
			foreach ($users as $key => $user) {
				$profile = $this->class->gm_db->get('user_profiles', ['user_id' => $user['id']], 'row');
				if ($profile) $users[$key] = array_merge($user, $profile);
				$settings = $this->class->gm_db->get('user_settings', ['user_id' => $user['id']]);
				if ($settings) $users[$key]['settings'] = $settings;
				$shippings = $this->class->gm_db->get('user_shippings', ['user_id' => $user['id']]);
				$users[$key]['shippings'] = false;
				if ($shippings) {
					$users[$key]['shippings'] = [];
					foreach ($shippings as $shipping) {
						if ($shipping['active']) {
							$users[$key]['shippings']['active'] = $shipping;
						} else {
							$users[$key]['shippings']['inactive'][] = $shipping;
						}
					}
				}
			}
		}
		return $users;
	}

}