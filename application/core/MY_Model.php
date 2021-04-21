<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		// $this->load->library('accounts');
		// $this->load->library('smtpemail');
	}

	public function get($table=FALSE, $where=FALSE, $func='result', $field=FALSE, $redirect_url='')
	{
		if ($table) {
			if ($field) {
				$this->db->select($field);
			}
			if (isset($where['order_by']) AND isset($where['direction'])) {
				$this->db->order_by($where['order_by'], $where['direction']);
				unset($where['order_by']);
				unset($where['direction']);
			}
			if (isset($where['limit'])) {
				$this->db->limit($where['limit']);
				unset($where['limit']);
			}
			if ($where) {
				$this->db->where($where);
			}
			$data = $this->db->get($table);
			// debug($data);
			if ($data->num_rows()) {
				if ($redirect_url != '') {
					redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
				} else {
					return $data->{$func.'_array'}();
				}
			}
		}
		return FALSE;
	}

	public function get_in($table=FALSE, $where=FALSE, $func='result', $field=FALSE, $redirect_url='')
	{
		if ($table) {
			if ($field) {
				$this->db->select($field);
			}
			if ($where) {
				foreach ($where as $key => $row) {
					if (is_array($row)) {
						$this->db->where_in($key, $row);
					} else {
						$this->db->where([$key => $row]);
					}
				}
			}
			$data = $this->db->get($table);
			// debug($data);
			if ($data->num_rows()) {
				if ($redirect_url != '') {
					redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
				} else {
					return $data->{$func.'_array'}();
				}
			}
		}
		return FALSE;
	}

	public function get_not_in($table=FALSE, $where=FALSE, $func='result', $field=FALSE, $redirect_url='')
	{
		if ($table) {
			if ($field) {
				$this->db->select($field);
			}
			if ($where) {
				foreach ($where as $key => $row) {
					if (is_array($row)) {
						$this->db->where_not_in($key, $row);
					} else {
						$this->db->where([$key => $row]);
					}
				}
			}
			$data = $this->db->get($table);
			// debug($data);
			if ($data->num_rows()) {
				if ($redirect_url != '') {
					redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
				} else {
					return $data->{$func.'_array'}();
				}
			}
		}
		return FALSE;
	}

	public function query($string=FALSE, $func='result')
	{
		if ($string) {
			$data = $this->db->query($string);
			// debug($data);
			if ($data->num_rows()) {
				return $data->{$func.'_array'}();
			}
		}
		return FALSE;
	}

	public function new($table=FALSE, $post=FALSE, $redirect_url='')
	{
		if ($table AND $post) {
			if ($this->db->field_exists('version', $table)) {
				$post = (array)$post;
				$post['version'] = 1;
			}
			// $post['last_updated'] = date('Y-m-d H:i:s');
			$this->db->insert($table, $post);
			$insert_id = $this->db->insert_id();
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return $insert_id;
			}
		}
		return FALSE;
	}

	public function new_batch($table=FALSE, $post=FALSE, $redirect_url='')
	{
		if ($table AND $post) {
			if ($this->db->field_exists('version', $table)) {
				$post = (array)$post;
				foreach ($post as $key => $row) {
					$post[$key]['version'] = 1;
				}
			}
			// $post['last_updated'] = date('Y-m-d H:i:s');
			$this->db->insert_batch($table, $post);
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return $this->db->insert_id();
			}
		}
		return FALSE;
	}

	public function save($table=FALSE, $set=FALSE, $where=FALSE, $redirect_url='')
	{
		if ($table AND $set AND $where) {
			if ($this->db->field_exists('version', $table)) {
				$data = $this->get($table, $where, 'row', 'version');
				$set = (array)$set;
				$set['version'] = $data ? (int)$data['version'] + 1 : 1;
			}
			$this->db->update($table, $set, $where);
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function save_batch($table=FALSE, $set=FALSE, $where=FALSE, $redirect_url='')
	{
		if ($table AND $set AND $where) {
			if ($this->db->field_exists('version', $table)) {
				$data = $this->get($table, $where, 'result', 'version');
				foreach ($data as $key => $row) {
					if (isset($set[$key])) {
						$set[$key] = (array)$set[$key];
						$set[$key]['version'] = $row ? (int)$row['version'] + 1 : 1;
					}
				}
			}
			// debug($set, 'stop');
			$this->db->update_batch($table, $set, $where);
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function remove($table=FALSE, $where=FALSE, $redirect_url='')
	{
		if ($table AND $where) {
			$this->db->delete($table, $where);
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function drop_tables()
	{
		if (DROP_ALL_TABLE) {
			// debug($this->db->database, 'stop');
			$tables = $this->db->query("SHOW TABLES");
			// debug($tables->num_rows(), 'stop');
			if ($tables->num_rows() > 0) {
				$tables = $tables->result_array();
				// debug($tables, 'stop');
				foreach ($tables as $key => $table) {
					// $this->db->query('TRUNCATE TABLE '.$table['Tables_in_'.$this->db->database]);
					$db_table = $table['Tables_in_'.$this->db->database];
					if (in_array($db_table, ['products_measurement', 'products_category', 'products_subcategory', 'attributes', 'attribute_values'])) {
						// $this->db->query('DROP TABLE IF EXISTS '.$table['Tables_in_'.$this->db->database]);
					} else {
						$tabledata = $this->get($db_table);
						if ($tabledata) {
							foreach ($tabledata as $key => $row) $this->remove($db_table, $row);
						}
					}
				}
				return true;
			}
		}
		return false;
	}

	public function count($table=FALSE, $where=FALSE)
	{
		if ($table) {
			if ($where) {
				return $this->db->from($table)->where($where)->count_all_results();
			} else {
				return $this->db->from($table)->count_all_results();
			}
		}
		return 0;
	}

	public function fieldlists($table=false, $remove_fields=[])
	{
		if ($table) {
			$lists = $this->db->list_fields($table);
			foreach ($lists as $key => $field) {
				if (in_array($field, $remove_fields)) {
					unset($lists[$key]);
				}
			}
			// debug($lists, 'stop');
			return $lists;
		}
		return false;
	}
}
