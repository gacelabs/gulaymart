<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('accounts');
		$this->load->library('smtpemail');
	}

	public function get($table=FALSE, $where=FALSE, $func='result', $field=FALSE, $redirect_url='')
	{
		if ($table) {
			if ($field) {
				$this->db->select($field);
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
			// $set['last_updated'] = date('Y-m-d H:i:s');
			$this->db->update($table, $set, $where);
			if ($redirect_url != '') {
				redirect(base_url($redirect_url == '/' ? '' : $redirect_url));
			} else {
				return $this->db->insert_id();
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
				return $this->db->affected_rows();
			}
		}
		return FALSE;
	}
}
