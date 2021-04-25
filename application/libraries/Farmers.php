<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farmers {

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
			$data = $this->class->db->order_by($order_by)->get_where('user_farms', $where);
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
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
			$data = $this->class->db->order_by($order_by)->get('user_farms');
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
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
			$data = $this->class->db->order_by($order_by)->get('user_farms');
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
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
			$data = $this->class->db->order_by($order_by)->get('user_farms');
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
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
			$data = $this->class->db->order_by($order_by)->get('user_farms');
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
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
			$data = $this->class->db->order_by($order_by)->get('user_farms');
			if (isset($data) AND $data->num_rows()) {
				$farms = $data->result_array();
				$farms = $this->farms_assemble($farms);
				// debug($farms, 'stop');
				if ($row) {
					return $farms[0];
				} else {
					return $farms;
				}
			}
		}
		return false;
	}

	public function count($where=false)
	{
		if ($where != false) {
			foreach ($where as $key => $row) {
				if (is_array($row)) {
					$this->class->db->where_in($key, $row);
				} else {
					$this->class->db->where([$key => $row]);
				}
			}
		}
		return $this->class->db->from('user_farms')->count_all_results();
	}

	public function new($new=false)
	{
		if ($new) {
			$this->class->db->insert('user_farms', $new);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}

	public function save($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('user_farms', $set, $where);
			return true;
		}
		return false;
	}

	private function farms_assemble($farms=false)
	{
		if ($farms) {
			foreach ($farms as $key => $farm) {
				$farm_locations = $this->class->gm_db->get('user_farm_locations', ['farm_id' => $farm['id']]);
				$farms[$key]['farm_locations'] = false;
				if ($farm_locations) {
					$farms[$key]['farm_locations'] = [];
					foreach ($farm_locations as $location) {
						$farms[$key]['farm_location_ids'][] = $location['id'];
						$location['products'] = false;
						$products_location = $this->class->gm_db->get('products_location', ['farm_location_id' => $location['id']]);
						if ($products_location) {
							$product_details = [];
							foreach ($products_location as $product_location) {
								$product_details['product_ids'][] = $product_location['product_id'];
								$product_details['prices'][$product_location['product_id']] = $product_location['price'];
								$product_details['measurements'][$product_location['product_id']] = $product_location['measurement'];
								$product_details['stocks'][$product_location['product_id']] = $product_location['stocks'];
							}
							if (!empty($product_details)) {
								$products = $this->class->gm_db->get_in('products', ['id' => $product_details['product_ids']]);
								if ($products) {
									foreach ($products as $index => $product) {
										$products[$index]['price'] = $product_details['prices'][$product['id']];
										$products[$index]['measurement'] = $product_details['prices'][$product['id']];
										$products[$index]['stocks'] = $product_details['prices'][$product['id']];

										$category = $this->class->gm_db->get_in('products_category', ['id' => $product['category_id']], 'row');
										$products[$index]['category'] = $category;

										$subcategory = $this->class->gm_db->get_in('products_subcategory', ['id' => $product['subcategory_id']], 'row');
										$products[$index]['subcategory'] = $subcategory;

										$attributes = $this->class->gm_db->get_in('products_attribute', ['product_id' => $product['id']]);
										$products[$index]['attributes'] = $attributes;

										$main_photo = $this->class->gm_db->get_in('products_photo', ['product_id' => $product['id'], 'is_main' => 1], 'row');
										$photos = $this->class->gm_db->get_in('products_photo', ['product_id' => $product['id'], 'is_main' => 0]);

										$products[$index]['photos']['main'] = $main_photo;
										if ($main_photo == false AND $photos != false) {
											$products[$index]['photos']['main'] = $photos[0];
											unset($photos[0]);
										}
										$products[$index]['photos'] = $photos;
									}
									$location['products'] = $products;
								}
							}
						}
						$address = explode(',', $location['address_2']);
						$location['city'] = isset($address[0]) ? $address[0] : '';
						$location['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
						$farms[$key]['farm_locations'][$location['id']] = $location;
					}
				}
			}
		}
		return $farms;
	}

}