<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baskets {

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

	private function baskets_assemble($baskets=false)
	{
		if ($baskets) {
			foreach ($baskets as $key => $basket) {
				if (is_string($basket['rawdata'])) {
					$basket['rawdata'] = $baskets[$key]['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
					// debug($basket, 'stop');
					$driving_distance = get_driving_distance([
						['lat' => $basket['rawdata']['farm']['lat'], 'lng' => $basket['rawdata']['farm']['lng']],
						['lat' => $this->class->latlng['lat'], 'lng' => $this->class->latlng['lng']],
					]);
					$baskets[$key]['distance'] = $driving_distance['distanceval'];
					$baskets[$key]['duration'] = $driving_distance['durationval'];
					$baskets[$key]['distance_text'] = $driving_distance['distance'];
					$baskets[$key]['duration_text'] = $driving_distance['duration'];
				}
			}
		}
		return $baskets;
	}

	public function get($where=true, $row=false, $limit=false, $order_by='location_id')
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			$data = $this->class->db->order_by($order_by)->get_where('baskets', $where);
			if (isset($data) AND $data->num_rows()) {
				$baskets = $data->result_array();
				$baskets = $this->baskets_assemble($baskets);
				// debug($baskets, 'stop');
				if ($row) {
					return $baskets[0];
				} else {
					return $baskets;
				}
			}
		}
		return false;
	}

	public function get_baskets_merge($where=true, $row=false, $limit=false, $order_by='added')
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
			$data = $this->class->db->order_by($order_by)->get('baskets_merge');
			if (isset($data) AND $data->num_rows()) {
				$baskets_merge = $data->result_array();
				// debug($baskets_merge, 'stop');
				if ($row) {
					return $baskets_merge[0];
				} else {
					return $baskets_merge;
				}
			}
		}
		return false;
	}

	public function get_in($where=true, $row=false, $limit=false, $order_by='location_id')
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
			$data = $this->class->db->order_by($order_by)->get('baskets');
			if (isset($data) AND $data->num_rows()) {
				$baskets = $data->result_array();
				// debug($baskets, 'stop');
				$baskets = $this->baskets_assemble($baskets);
				if ($row) {
					return $baskets[0];
				} else {
					return $baskets;
				}
			}
		}
		return false;
	}

	public function count($where=false)
	{
		if ($where == false) {
			return $this->class->db->from('baskets')->count_all_results();
		} else {
			return $this->class->db->from('baskets')->where($where)->count_all_results();
		}
	}

	public function new($new=false)
	{
		if ($new) {
			$this->class->db->insert('baskets', $new);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}

	public function save($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('baskets', $set, $where);
			return true;
		}
		return false;
	}

	public function prepare_to_basket($data=false, $product_id=0)
	{
		if ($data AND isset($data['baskets']) AND $product_id) {
			$basket = $data['baskets'];
			$quantity = $basket['quantity'];

			$basket['product_id'] = $product_id;
			$timestamp = strtotime(date('Y-m-d')); $time = date('g:ia');
			$basket['at_date'] = $timestamp;
			$basket['at_time'] = $time;
			$basket['device_id'] = $this->class->device_id;
			$basket['status'] = 0;

			$where = ['product_id' => $product_id, 'location_id' => $basket['location_id'], 'at_date' => $timestamp, 'status' => [0,1]];
			$user_id = 0;
			if ($this->has_session) {
				$user_id = $where['user_id'] = $this->profile['id'];
			} else {
				$where['device_id'] = $this->class->device_id;
			}
			$existing = $this->class->gm_db->get_in('baskets', $where, 'row');
			$basket['user_id'] = $user_id;

			if ($existing) {
				$quantity += $existing['quantity'];
				$existing['quantity'] = $quantity;
				$rawdata = json_decode(base64_decode($existing['rawdata']), true);
				// $rawdata['details']['sub_total'] = (float)$rawdata['details']['price'] * (int)$quantity;
				$existing['rawdata'] = base64_encode(json_encode($rawdata));
				$basket = $existing;
				$basket['existing'] = 1;
			} else {
				$basket['existing'] = 0;
				$details = $this->class->gm_db->get('products_location',
					['product_id'=>$product_id, 'farm_location_id'=>$basket['location_id']]
					, 'row'
				);
				if ($details) {
					$product = $this->class->gm_db->get_in('products', ['id' => $product_id], 'row');
					if ($product) {
						// $details['sub_total'] = (float)$details['price'] * (int)$quantity;
						$product['farm_location_id'] = $basket['location_id'];

						unset($product['added']); unset($product['updated']);
						$category = $this->class->gm_db->get_in('products_category', ['id' => $product['category_id']], 'row');
						$product['category'] = $category;

						$subcategory = $this->class->gm_db->get_in('products_subcategory', ['id' => $product['subcategory_id']], 'row');
						$product['subcategory'] = $subcategory;

						$main_photo = $this->class->gm_db->get_in('products_photo', ['product_id' => $product_id, 'is_main' => 1], 'row');
						$photos = $this->class->gm_db->get_in('products_photo', ['product_id' => $product_id, 'is_main' => 0]);
						$product['photos']['main'] = $main_photo;
						if ($main_photo == false AND isset($photos[0])) {
							$product['photos']['main'] = $photos[0];
							unset($photos[0]);
						}
						$product['photos']['others'] = count($photos) ? $photos : false;
					}
					$farm_location = $this->class->gm_db->get_in('user_farm_locations', ['id' => $basket['location_id']], 'row');
					$farm = false;
					if ($farm_location) {
						$farm = $this->class->gm_db->get_in('user_farms', ['id' => $farm_location['farm_id']], 'row');
						$farm = array_merge($farm, $farm_location);
					}
					$basket['rawdata'] = [
						'details' => $details,
						'product' => $product,
						'farm' => $farm,
					];
					$basket['rawdata'] = base64_encode(json_encode($basket['rawdata']));
				}
			}
			// debug($basket, 'stop');
			return $basket;
		}
		return false;
	}

	public function new_baskets_merge($data=false)
	{
		if ($data) {
			$this->class->db->insert('baskets_merge', $data);
			$id = $this->class->db->insert_id();
			if ($id) return $id;
		}
		return false;
	}

	public function save_baskets_merge($set=false, $where=[])
	{
		if ($set) {
			$this->class->db->update('baskets_merge', $set, $where);
			return true;
		}
		return false;
	}

}