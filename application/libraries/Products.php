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
					$products[$key] = $this->gulay_assemble($products[$key], $justdata);
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

	public function get_in($where=true, $except_field=false, $limit=false, $justdata=true, $row=false)
	{
		if ($where != false) {
			if (is_bool($where) AND $where == true) {
				if (!is_bool($limit) AND is_numeric($limit)) {
					$this->class->db->limit($limit);
				}
				$this->class->db->where(['activity' => 1]);
			} elseif (is_array($where) OR is_string($where)) {
				$this->class->db->where(['activity' => 1]);
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
			}
			$data = $this->class->db->get('products');
			if (isset($data) AND $data->num_rows()) {
				$products = $data->result_array();
				$results = [];
				foreach ($products as $key => $product) {
					$product_id = $products[$key]['id'];
					$products[$key] = $this->gulay_assemble($products[$key], $justdata, $except_field);
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

	public function get_by_category_pages($where=false)
	{
		$clause = ['activity' => 1];
		if ($where != false) {
			$clause = $where + $clause;
		}
		// debug($clause, 'stop');
		$data = $this->class->gm_db->get('products', $clause);
		// $data = $this->get(['activity' => 1]);
		$tmp_all = $tmp_by_category = [];
		if ($data) {
			$tmp_all = $data;
			// debug($data, 'stop');
			$all = [];
			if (count($tmp_all) > PRODUCTSDATALIMIT) {
				for ($i=0; $i < PRODUCTSDATALIMIT; $i++) {
					if (isset($tmp_all[$i])) {
						$product = $tmp_all[$i];
						$product = $this->gulay_assemble($product);
						$all['data_page'][] = $product;
					}
				}
				for ($x=$i; $x < count($tmp_all); $x++) {
					if (isset($tmp_all[$x])) {
						$product = $tmp_all[$x];
						$all['next_page'][] = $product['id'];
					}
				}
				if (isset($all['next_page'])) {
					$next_data = $all['next_page'];
					$all['next_page'] = array_chunk($next_data, PRODUCTSDATALIMIT);
				}
			} else {
				foreach ($tmp_all as $key => $product) {
					$tmp_all[$key] = $this->gulay_assemble($product);
				}
				$all['data_page'] = $tmp_all;
				$all['next_page'] = 0;
			}
			// debug($all, 'stop');

			/*setup per category*/
			foreach ($data as $row) $tmp_by_category[$row['category_id']][] = $row;
			// debug($tmp_by_category, 'stop');

			$categories = [];
			foreach ($tmp_by_category as $category_id => $products) {
				$handler = [];
				if (count($products) > PRODUCTSDATALIMIT) {
					for ($i=0; $i < PRODUCTSDATALIMIT; $i++) {
						if (isset($products[$i])) {
							$product = $products[$i];
							$product = $this->gulay_assemble($product);
							$handler['data_page'][] = $product;
						}
					}
					for ($x=$i; $x < count($products); $x++) {
						if (isset($products[$x])) {
							$handler['next_page'][] = $products[$x]['id'];
						}
					}
					if (isset($handler['next_page'])) {
						$next_data = $handler['next_page'];
						$handler['next_page'] = array_chunk($next_data, PRODUCTSDATALIMIT);
					}
				} else {
					foreach ($products as $key => $product) {
						$products[$key] = $this->gulay_assemble($product);
					}
					$handler['data_page'] = $products;
					$handler['next_page'] = 0;
				}
				$categories[$category_id] = $handler;
			}

			$products = [
				'all' => $all,
				'categories' => $categories,
			];
			// debug($products, 'stop');
			return $products;
		}
	}

	public function count($where=false)
	{
		if ($where == false) {
			return $this->class->db->from('products')->count_all_results();
		} else {
			return $this->class->db->from('products')->where($where)->count_all_results();
		}
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

	public function gulay_assemble($product=false, $data_only=true, $except_field=false)
	{
		if ($product) {
			// $product['price'] = '&#8369;'.$product['price'];
			// $farm = $this->class->gm_db->get('user_farms', ['user_id' => $product['user_id']]);
			// $product['farm'] = false;
			// if ($farm) $product['farm'] = $farm;

			$product['category'] = false;
			$category = $this->class->gm_db->get('products_category', ['id' => $product['category_id']], 'row');
			if ($category) $product['category'] = $category['label'];

			$product['subcategory'] = false;
			$subcategory = $this->class->gm_db->get('products_subcategory', ['id' => $product['subcategory_id']], 'row');
			if ($subcategory) {
				$product['subcategory'] = $subcategory['label'];
			}

			$product['photos'] = false;
			$photos = $this->class->gm_db->get('products_photo', ['product_id' => $product['id'], 'status' => 1]);
			if ($photos) {
				foreach ($photos as $key => $photo) {
					if ($photo['is_main']) {
						$product['photos']['main'] = $photo;
						break;
					}
				}
				foreach ($photos as $key => $photo) {
					if (!$photo['is_main']) {
						$product['photos']['other'][] = $photo;
					}
				}
			}

			$products_location = $this->class->gm_db->get('products_location', ['product_id' => $product['id']]);
			$product['farms'] = false;
			if ($products_location) {
				foreach ($products_location as $key => $location) {
					$farm_location = $this->class->gm_db->get('user_farm_locations', ['farm_id' => $location['farm_id']], 'row');
					$product['farms'][] = $location;
				}
				// debug($product, 'stop');
			}

			$updated = $product['updated'];
			$product['activity'] = $product['activity'] ? 'Published' : 'Draft';

			$display = false;
			if ($except_field) {
				if (is_array($except_field)) {
					$display = [];
					foreach ($except_field as $value) {
						if (!isset($product[$value])) {
							$product[$value] = 0;
						} else {
							$product[$value] = $product[$value];;
						}
						$display[$value] = $product[$value];
					}
				} else {
					if (!isset($product[$except_field])) {
						$product[$except_field] = 0;
					}
					$display = $product[$except_field];
				}
			}

			if ($data_only) {
				unset($product['user_id']);
				unset($product['description']);
				unset($product['inclusion']);
				unset($product['category_id']);
				unset($product['subcategory_id']);
				unset($product['location_id']);
				unset($product['farms']);
				unset($product['photos']);
				unset($product['added']);
				unset($product['updated']);
			}

			if ($display OR $except_field != false) {
				if (is_array($display)) {
					foreach ($display as $field => $value) {
						$product[$field] = $value;
					}
				} else {
					$product[$except_field] = $display;
				}
			}
			
			$product['updated'] = $updated;
			// debug($product, 'stop');
		}
		return $product;
	}
}