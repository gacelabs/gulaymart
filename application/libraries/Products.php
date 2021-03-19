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
					$user_id = $products[$key]['user_id'];

					$products[$key]['farm'] = '';
					$farm = $this->class->gm_db->get('user_farms', ['id' => $product['farm_id'], 'user_id' => $product['user_id']], 'row');
					// debug($farm, 'stop');
					if ($farm) $products[$key]['farm'] = $farm['name'];

					$category = $this->class->gm_db->get('products_category', ['id' => $product['category_id']], 'row');
					if ($category) {
						$products[$key]['category'] = $category['label'];
					}
					if ($justdata) {
						unset($products[$key]['id']);
						unset($products[$key]['user_id']);
						unset($products[$key]['farm_id']);
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
}