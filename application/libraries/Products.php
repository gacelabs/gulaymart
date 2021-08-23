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
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						if (count($wrow)) {
							$this->class->db->where($field, $wrow);
						} else {
							$this->class->db->where($field, 0);
						}
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->get('products');
			if (isset($data) AND $data->num_rows()) {
				$products = $data->result_array();
				$results = [];
				foreach ($products as $key => $product) {
					$product_id = $products[$key]['id'];
					$products[$key] = $this->products_assemble($products[$key], $justdata);
					$products[$key]['id'] = $product_id;
				}
				// debug($products, $row, 'stop');
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
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_array($where)) {
				foreach ($where as $field => $wrow) {
					if (is_array($wrow)) {
						if (count($wrow)) {
							$this->class->db->where_in($field, $wrow);
						} else {
							$this->class->db->where_in($field, 0);
						}
					} else {
						$this->class->db->where([$field => $wrow]);
					}
				}
			}
			$data = $this->class->db->get('products');
			if (isset($data) AND $data->num_rows()) {
				$products = $data->result_array();
				$results = [];
				foreach ($products as $key => $product) {
					$product_id = $products[$key]['id'];
					$products[$key] = $this->products_assemble($products[$key], $justdata, $except_field);
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
		$clause = [];
		if ($where != false) $clause = $where + $clause;
		// debug($clause, 'stop');
		$data = $this->class->gm_db->get('products', $clause);
		$tmp_all = $tmp_by_category = [];
		if ($data) {
			$tmp_all = $data;
			// debug($data, 'stop');
			$all = [];
			if (count($tmp_all) > PRODUCTSDATALIMIT) {
				for ($i=0; $i < PRODUCTSDATALIMIT; $i++) {
					if (isset($tmp_all[$i])) {
						$product = $tmp_all[$i];
						$product = $this->products_assemble($product);
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
					$tmp_all[$key] = $this->products_assemble($product);
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
							$product = $this->products_assemble($product);
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
						$products[$key] = $this->products_assemble($product);
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
			debug($products, 'stop');
			return $products;
		}
	}

	public function count($where=false)
	{
		if (!is_bool($where)) {
			foreach ($where as $key => $row) {
				if (is_array($row)) {
					if (count($row)) {
						$this->class->db->where_in($field, $row);
					} else {
						$this->class->db->where_in($field, 0);
					}
				} else {
					$this->class->db->where([$key => $row]);
				}
			}
			return $this->class->db->from('products')->count_all_results();
		} else {
			if ($this->has_session) {
				return $this->class->db->from('products')->where(['user_id' => $this->profile['id']])->count_all_results();
			} else {
				return $this->class->db->from('products')->count_all_results();
			}
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

	public function products_with_location($where=true, $row=false, $limit=false)
	{
		if ($where != false) {
			if (!is_bool($limit) AND is_numeric($limit)) {
				$this->class->db->limit($limit);
			}
			if (is_bool($where) AND $where == true) {
				$products = $this->class->db->get('products');
			} elseif (is_array($where) OR is_string($where)) {
				$products = $this->class->db->get_where('products', $where);
			}
			$results = false;
			if ($products->num_rows()) {
				$products = $products->result_array();
				// debug($products, 'stop');
				foreach ($products as $key => $product) {
					$added = $product['added'];
					$updated = $product['updated'];
					unset($product['added']); unset($product['updated']);
					$product['feedbacks'] = false;

					$product['category'] = false;
					$category = $this->class->gm_db->get('products_category', ['id' => $product['category_id']], 'row');
					if ($category) $product['category'] = $category['label'];

					$product['subcategory'] = false;
					$subcategory = $this->class->gm_db->get('products_subcategory', ['id' => $product['subcategory_id']], 'row');
					if ($subcategory) {
						$product['subcategory'] = $subcategory['label'];
					}

					$farm = $this->class->gm_db->get('user_farms', ['user_id' => $product['user_id']], 'row');
					// $farm['storefront'] = storefront_url($farm);
					$product['farm'] = $farm;

					$product['location'] = false;
					$farm_location = $this->class->gm_db->get('user_farm_locations', ['farm_id' => $farm['id']]);
					if ($farm_location) {
						foreach ($farm_location as $latlng) {
							$location = $this->class->gm_db->get('products_location', ['product_id' => $product['id'], 'farm_location_id' => $latlng['id']], 'row');
							// debug($location, $latlng, 'stop');
							$driving_distance = get_driving_distance([
								['lat' => $this->class->latlng['lat'], 'lng' => $this->class->latlng['lng']],
								['lat' => $latlng['lat'], 'lng' => $latlng['lng']],
							]);
							$address = explode(',', $latlng['address_2']);
							$product['latlng'][$latlng['id']] = [
								'id' => $latlng['id'],
								'lat' => $latlng['lat'],
								'lng' => $latlng['lng'],
								'city' => isset($address[0]) ? $address[0] : '',
								'city_prov' => (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '',
								'distance' => $driving_distance['distance'],
								'duration' => $driving_distance['duration'],
								'price' => ($location ? $location['price'] : 0),
								'measurement' => ($location ? $location['measurement'] : ''),
								'stocks' => ($location ? $location['stocks'] : 0),
								'checked' => ($location ? 'checked' : ''),
							];
						}
					}

					$product['attribute'] = [];
					$attributes = $this->class->gm_db->get('products_attribute', ['product_id' => $product['id']]);
					if ($attributes) {
						$product['attribute'] = $attributes;
					}

					$photos = $this->class->gm_db->get('products_photo', ['product_id' => $product['id'], 'status' => 1]);
					$product['photos'] = false;
					if ($photos) {
						$product['photos'] = [];
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
					$product['added'] = $added;
					$product['updated'] = $updated;
					$results[] = $product;
				}
				return ($row AND isset($results[0])) ? $results[0] : $results;
			}
			// debug($results, 'stop');
		}

		return false;
	}

	public function product_by_farm_location($product_id=false, $farm_location_id=false)
	{
		if ($product_id != false AND $farm_location_id != false) {
			$products_location = $this->class->db->get_where('products_location', [
				'product_id' => $product_id,
				'farm_location_id' => $farm_location_id,
			]);
			if ($products_location->num_rows()) {
				$product = $this->class->gm_db->get('products', ['id' => $product_id, 'include_activity' => 1], 'row');
				// debug($product, 'stop');
				if ($product) {
					$product['product_url'] = product_url(['id'=>$product_id, 'farm_location_id'=>$farm_location_id, 'name'=>$product['name']]);
					
					$added = $product['added'];
					$updated = $product['updated'];
					unset($product['added']); unset($product['updated']);
					$feedbacks = $this->class->gm_db->get('messages', [
						'page_id' => $product_id,
						'entity_id' => $farm_location_id,
						'tab' => 'Feedbacks',
						'type' => 'Comments',
					]);
					$feedbacks_data = false;
					if ($feedbacks) {
						$feedbacks_data = [];
						foreach ($feedbacks as $key => $feedback) {
							if ($feedback['under'] == 0) { /*to_id is the farmer*/
								$feedback['is_buyer'] = 1;
								$feedback['farm'] = $this->class->gm_db->get('user_farms', ['user_id' => $feedback['to_id']], 'row');
								$feedback['profile'] = $this->class->gm_db->get('user_profiles', ['user_id' => $feedback['from_id']], 'row');
								$feedbacks_data[$feedback['id']]['first'] = $feedback;
							} else { /*to_id is the profile*/
								$feedback['is_buyer'] = 0;
								$feedback['farm'] = $this->class->gm_db->get('user_farms', ['user_id' => $feedback['from_id']], 'row');
								$feedback['profile'] = $this->class->gm_db->get('user_profiles', ['user_id' => $feedback['to_id']], 'row');
								$feedbacks_data[$feedback['under']]['replies'][] = $feedback;
							}
						}
						// debug($feedbacks_data, $feedbacks, 'stop');
					}
					$product['feedbacks'] = $feedbacks_data;
					if ($this->has_session) {
						$product_not_cancelled = $this->class->gm_db->count('baskets', [
							'user_id' => $this->profile['id'],
							'product_id' => $product_id,
							'status !=' => GM_CANCELLED_STATUS,
						]);
						$has_message = $this->class->gm_db->count('messages', ['from_id'=>$this->profile['id'],'page_id'=>$product_id]);
						$is_not_owner = $this->class->gm_db->count('products', ['user_id'=>$this->profile['id'],'id'=>$product_id]);
						$product['can_comment'] = ($product_not_cancelled > 0 AND $has_message == 0 AND $is_not_owner == 0);
					} else {
						$product['can_comment'] = false;
					}

					$product['category'] = false;
					$category = $this->class->gm_db->get('products_category', ['id' => $product['category_id']], 'row');
					if ($category) $product['category'] = $category['label'];
					if ($category) $product['category_value'] = $category['value'];

					$product['subcategory'] = false;
					$subcategory = $this->class->gm_db->get('products_subcategory', ['id' => $product['subcategory_id']], 'row');
					if ($subcategory) {
						$product['subcategory'] = $subcategory['label'];
					}

					$farm_location = $this->class->gm_db->get('user_farm_locations', ['id' => $farm_location_id], 'row');
					if ($farm_location) {
						$address = explode(',', $farm_location['address_2']);
						$farm_location['city'] = isset($address[0]) ? $address[0] : '';
						$farm_location['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
						$coordinates = [
							['lat' => $this->class->latlng['lat'], 'lng' => $this->class->latlng['lng']],
							['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
						];
						// $latlng = get_cookie('prev_latlng', true);
						// if (!empty($latlng)) $coordinates[0] = unserialize($latlng);
						$driving_distance = get_driving_distance($coordinates);
						$farm_location['distance'] = $driving_distance['distance'];
						$farm_location['duration'] = $driving_distance['duration'];
						$farm_location['distanceval'] = $driving_distance['distanceval'];
						$farm_location['durationval'] = $driving_distance['durationval'];
					}
					$product['farm_location'] = $farm_location;

					$product['barns'] = false;
					$barns = $this->class->gm_db->get('user_farm_locations', ['farm_id' => $farm_location['farm_id']]);
					if ($barns) {
						$product['barns'] = [];
						foreach ($barns as $key => $barn) {
							$address = explode(',', $barn['address_2']);
							$barn['city'] = isset($address[0]) ? $address[0] : '';
							$barn['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
							$product['barns'][] = $barn;
						}
					}

					$farm = $this->class->gm_db->get('user_farms', ['id' => $farm_location['farm_id']], 'row');
					$farm['farm_location_id'] = $farm_location_id;
					$farm['storefront'] = storefront_url($farm);
					$product['farm'] = $farm;

					$products_location = $products_location->row_array();
					/*check here the number of quantity ordered*/
					$stocks = $products_location['stocks'];
					$sold = $products_location['sold'];
					$basket = $this->class->gm_db->get_in('baskets', [
						'product_id' => $product_id,
						'status' => [GM_ON_DELIVERY_STATUS, GM_RECEIVED_STATUS, GM_FOR_PICK_UP_STATUS],
						// 'at_date' => strtotime(date('Y-m-d'))
					], 'result', 'quantity');
					if ($basket) {
						if ($stocks > 0) {
							foreach ($basket as $item) $stocks -= $item['quantity'];
						}
					}
					$products_location['stocks'] = ($stocks <= 0) ? 0 : $stocks; /*set no available*/
					// debug($products_location, 'stop');
					if ($stocks <= 0) {
						/*update product stocks*/
						$sold += abs($stocks);
						$this->class->gm_db->save('products_location',
							['sold' => $sold],
							['product_id' => $product_id, 'farm_location_id' => $farm_location_id]
						);
						// send message to the user has to replenish the needed stocks for delivery
						$name = $product['name'];
						$base_url = base_url('farm/save-veggy/'.$product_id.'/'.nice_url($name, true).'#score-2');
						$datestamp = strtotime(date('Y-m-d'));
						$content = "Product item <a href='".$base_url."'>$name</a> is low on stocks [<em>$stocks pcs remaining</em>]";
						send_gm_email($this->profile['id'], $content, 'Product item '.$name.' is low on stocks, Thank you!');
						send_gm_message($this->profile['id'], strtotime(date('Y-m-d')), $content, 'Notifications', 'Inventory', 'message', false, ['page_id' => $product_id, 'entity_id' => $farm_location_id]);
					}
					// debug($products_location, 'stop');
					$product['basket_details'] = $products_location;

					$product['attribute'] = [];
					$attributes = $this->class->gm_db->get('products_attribute', ['product_id' => $product_id]);
					if ($attributes) {
						$product['attribute'] = $attributes;
					}

					$product['photos'] = false;
					$photos = $this->class->gm_db->get('products_photo', ['product_id' => $product_id, 'status' => 1]);
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
					// debug($product, 'stop');
					$product['added'] = $added;
					$product['updated'] = $updated;

					return $product;
				}
			}
		}
		return false;
	}

	private function products_assemble($product=false, $data_only=true, $except_field=false)
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
			$product['locations'] = 'None';
			if ($products_location) {
				$locations = [];
				foreach ($products_location as $key => $location) {
					$farm_location = $this->class->gm_db->get('user_farm_locations', ['id' => $location['farm_location_id']], 'row');
					$product['farms'][] = $location;
					$address = explode(',', $farm_location['address_2']);
					$product_url = product_url([
						'id' => $product['id'],
						'farm_location_id' => $location['farm_location_id'],
						'name' => $product['name'],
					]);
					$locations[$location['farm_location_id']] = (isset($address[0])) ? '<a href="'.$product_url.'">'.$address[0].'</a>' : '';
				}
				// debug($product, 'stop');
				$product['locations'] = implode(' | ', $locations);
			}

			$updated = $product['updated'];
			$product['activity'] = get_activity_text($product['activity']);

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

	public function paginate($id=0, $edit_url=false, $loop=true)
	{
		if ($edit_url) {
			$user_id = $this->profile['id'];
			$return = $this->class->gm_db->query("
				SELECT DISTINCT id, $edit_url AS edit_url, updated 
				FROM products 
				WHERE user_id = '$user_id' AND activity NOT IN (".GM_ITEM_REJECTED.", ".GM_ITEM_DELETED.", ".GM_ITEM_NO_INVENTORY.")
				ORDER BY updated
			");
			$result = [];
			if ($return) {
				foreach ($return as $key => $row) {
					if ($key == 0 AND $loop == false) {
						$result['first'] = rtrim($return[$key]['edit_url'], '/').'/';
					}
					if ($row['id'] == $id) {
						if ($key != 0) {
							$result['prev'] = rtrim($return[$key-1]['edit_url'], '/').'/';
						} else {
							if ($loop) {
								$result['prev'] = rtrim($return[count($return)-1]['edit_url'], '/').'/';
							} else {
								$result['prev'] = 0;
							}
						}
						// $result[] = $row['edit_url'];
						if (isset($return[$key+1])) {
							$result['next'] = rtrim($return[$key+1]['edit_url'], '/').'/';
						} else {
							if ($loop) {
								$result['next'] = rtrim($return[count($return)-($key+1)]['edit_url'], '/').'/';
							} else {
								$result['next'] = 0;
							}
						}
					}
					if (count($return)-1 == $key AND $loop == false) {
						$result['last'] = rtrim($return[$key]['edit_url'], '/').'/';
					}
				}
			}
			// debug($result, 'stop');
			return $result;
		}
		return false;
	}
}