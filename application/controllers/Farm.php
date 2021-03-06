<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farm extends MY_Controller {

	public $allowed_methods = ['store', 'store_location', 'store_farm'];

	public function __construct()
	{
		parent::__construct();
		// debug($this->accounts->profile, 'stop');
		if ($this->accounts->has_session AND $this->accounts->profile['is_agreed_terms'] == 0 AND $this->accounts->profile['farms'] AND !in_array($this->action, ['storefront', 'store', 'store_location', 'store_farm'])) {
			redirect(base_url('farm/storefront'));
		}
	}

	public function index()
	{
		$this->sales();
	}

	public function sales()
	{
		if ($product_count = $this->products->count()) {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'farm/sales', 'chart.min'],
				],
				'middle' => [
					'body_class' => ['dashboard', 'sales'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/sales',
					],
				],
				'bottom' => [
					'js' => ['plugins/chart.min', 'farm/sales', 'dashboard/main'],
				],
				'data' => [
					'product_count' => $product_count
				],
			]);
		} else {
			redirect(base_url('farm/my-veggies/'));
		}
	}

	public function new_veggy()
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post AND isset($post['pos'])) {
			$message = 'Sorry product invalid values, request failed!';
			$successFunction = 'setProductScore';
			// debug($post, 'stop');
			$position = $post['pos'];
			$passed = isset($post['passed']) ? (int)$post['passed'] : 0; 
			$product_id = 0;
			if ($passed == 1) $message = 'Already have passed this form, please continue below.';
			if (isset($post['product_id']) AND $post['product_id'] > 0)	$product_id = $post['product_id'];
			$profile = $this->accounts->profile;
			switch ($position) {
				case '0':
					$product = $post['products'];
					if ($this->products->count($product) == 0) {
						$id = $this->products->new($post['products']);
						if ($id) {
							$passed = 1;
							$message = 'Passed on the 10% score, please continue below.';
							$post['product_id'] = $id;
						}
					} elseif ($passed == 0) {
						$message = 'Did not Pass on the 10% score, please try a different product or categories.';
					}
					break;
				case '1':
					if (isset($product_id) AND $product_id > 0) {
						$ids = [];
						// debug($post, 'stop');
						if (isset($post['products_attribute'])) {
							foreach ($post['products_attribute'] as $key => $attribute) {
								$attribute['product_id'] = $product_id;
								if (isset($attribute['id']) AND $attribute['id'] == '') {
									$ids[] = $post['products_attribute'][$key]['id'] = $this->products->new($attribute, 'products_attribute');
								} elseif (isset($attribute['id']) AND $attribute['id'] != '') {
									$this->products->save($attribute, ['id' => $attribute['id']], 'products_attribute');
									$ids[] = $post['products_attribute'][$key]['id'] = $attribute['id'];
								}
							}
						}
						if (count($ids) AND !in_array(false, $ids)) {
							if ($passed == 0) {
								$passed = 1;
								$message = 'Passed on the 30% score, please continue below.';
							}
						} else {
							$passed = 0;
							$message = 'Did not Pass on the 30% score, please check your attribute inputs.';
						}
						$post['products'] = $this->gm_db->get('products', ['id' => $product_id], 'row');
					}
					break;
				case '2':
					if (isset($product_id) AND $product_id > 0) {
						$product = $post['products'];
						$ok = $this->products->save($product, ['id' => $product_id]);
						if ($ok) {
							if (isset($post['products_location'])) {
								$this->gm_db->remove('products_location', ['product_id' => $product_id]);
								foreach ($post['products_location'] as $key => $location) {
									if (isset($location['farm_location_id']) AND $location['farm_location_id']) {
										if (!empty($location['price']) AND !empty($location['stocks'])) {
											$location['product_id'] = $product_id;
											$this->gm_db->new('products_location', $location);
											$post['products_location'][$key]['duration'] = '';
											$farm_location = $this->gm_db->get('user_farm_locations', ['id' => $location['farm_location_id']], 'row');
											if ($farm_location) {
												$driving_distance = get_driving_distance([
													['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
													['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
												]);
												$post['products_location'][$key]['duration'] = $driving_distance['duration'];
											}
										}
									}
								}
							}
							if ($passed == 0) {
								$passed = 1;
								$message = 'Passed on the 60% score, please continue below.';
							}
						} else {
							$passed = 0;
							$message = 'Did not Pass on the 60% score, product pricing seems to be wrong.';
						}
						$post['products'] = $this->gm_db->get('products', ['id' => $product_id], 'row');
					}
					break;
				case '3':
					if (isset($product_id) AND $product_id > 0) {
						$product = $post['products'];
						$id = $this->products->save($product, ['id' => $product_id]);
						if ($id) {
							if ($passed == 0) {
								$passed = 1;
								$message = 'Passed on the 80% score, please continue below.';
							}
						} else {
							$passed = 0;
							$message = 'Did not Pass on the 80% score, please describe your product.';
						}
						$post['products'] = $this->gm_db->get('products', ['id' => $product_id], 'row');
					}
					break;
				case '4':
					if (isset($product_id) AND $product_id > 0) {
						// debug($post, 'stop');
						if (!isset($post['activity'])) $post['activity'] = 0;
						$dir = 'products/'.str_replace('@', '-', $profile['email_address']);
						$uploads = files_upload($_FILES, $dir);
						$ids = [];
						if ($uploads) {
							// $this->gm_db->save('products_photo', ['is_main' => 0], ['product_id' => $product_id]);
							$this->gm_db->remove('products_photo', ['product_id' => $product_id]);
							foreach ($uploads as $key => $upload) {
								unset($upload['user_id']);
								$upload['product_id'] = $product_id;
								if ($key == $post['products_photo']['index']) {
									$upload['is_main'] = 1;
								} else {
									$upload['is_main'] = 0;
								}
								$ids[] = $this->products->new($upload, 'products_photo');
							}
							$this->products->save(['activity' => $post['activity']], ['id' => $product_id]);
							$post['file_photos'] = $this->gm_db->get('products_photo', ['product_id' => $product_id]);
						}
						$products_locations = $this->gm_db->get('products_location', ['product_id' => $product_id]);
						if ($products_locations) {
							foreach ($products_locations as $key => $location) {
								$farm_location = $this->gm_db->get('user_farm_locations', ['id' => $location['farm_location_id']], 'row');
								if ($farm_location) {
									$driving_distance = get_driving_distance([
										['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
										['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
									]);
									$address = explode(',', $farm_location['address_2']);
									$products_locations[$key]['city'] = isset($address[0]) ? $address[0] : '';
									$products_locations[$key]['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
									$products_locations[$key]['province'] = isset($address[1]) ? trim($address[1]) : '';
									$products_locations[$key]['duration'] = $driving_distance['duration'];
								}
							}
							$post['products_location'] = $products_locations;
						}
						if (count($ids) AND !in_array(false, $ids)) {
							if ($passed == 0) {
								$passed = 1;
								$message = 'Product Succesfully Added!';
								$successFunction = 'redirectNewProduct';
								/*email admins here*/
								$content = '<p>'.$message.'</p><p>Check product <a href="'.base_url('farm/save-veggy/'.$product_id.'/').'" data-readit="1">here</a>.</p>';
								send_gm_email($profile['id'], $content);
								send_gm_message($profile['id'], strtotime(date('Y-m-d')), $content, 'Notifications', 'Inventory', 'message', false, ['page_id' => $product_id]);
							}
						} else {
							$passed = 0;
							$message = 'Please check your image inputs.';
						}
						$post['products'] = $this->gm_db->get('products', ['id' => $product_id], 'row');
					}
					break;
			}
			// debug((string)$passed, $post, 'stop');
			$post['passed'] = $passed;
			if ($passed == 1) {
				$this->set_response('success', $message, $post, false, $successFunction);
			}
			$this->set_response('error', $message, $post, false, 'failedProductScore');
		} elseif (!empty($this->farms)) {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'looping/product-card', 'farm/new-veggy']
				],
				'middle' => [
					'body_class' => ['dashboard', 'new-veggy'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/veggy_form',
					],
				],
				'bottom' => [
					'modals' => ['veggy_form_help_modal'],
					'js' => [
						'plugins/jquery.inputmask.min',
						'plugins/inputmask.binding',
						'farm/main',
						'farm/new-veggy',
						'dashboard/main',
					],
				],
				'data' => [
					'has_products' => $this->products->count(),
					'pagination' => false,
					'product' => [],
					'is_edit' => false,
				],
			]);
		} else {
			redirect(base_url('farm/storefront/'));
		}
	}

	public function save_veggy($id=0, $name='')
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post AND $id > 0) {
			if (check_data_values($post)) {
				// debug($post, $_FILES, 'stop');
				$product = $this->products->get(['id' => $id], false, true, true);
				// debug($product, 'stop');
				if (isset($post['products'])) {
					$products = $post['products'];
					if ($this->products->save($products, ['id' => $id])) {
						$where = ['user_id' => $this->accounts->profile['id'], 'product_id' => $id];
						if (isset($products['location_id']) AND $this->products->save_location(['location_id' => $products['location_id']], $where)) {
							$post['products']['id'] = $id;
						}
					}
				}
				if (isset($post['products_attribute'])) {
					foreach ($post['products_attribute'] as $key => $attribute) {
						$attribute['product_id'] = $id;
						if (isset($attribute['id']) AND $attribute['id'] == '') {
							$post['products_attribute'][$key]['id'] = $this->products->new($attribute, 'products_attribute');
						} elseif (isset($attribute['id']) AND $attribute['id'] != '') {
							$this->products->save($attribute, ['id' => $attribute['id']], 'products_attribute');
							$post['products_attribute'][$key]['id'] = $attribute['id'];
						}
					}
				}
				if (isset($post['products_location'])) {
					$products_location = $post['products_location'];
					// debug($products_location, 'stop');
					$this->gm_db->remove('products_location', ['product_id' => $id]);
					foreach ($products_location as $farm_location_id => $location) {
						if (!empty($location['price']) AND !empty($location['stocks'])) {
							$location['product_id'] = $id;
							$this->gm_db->new('products_location', $location);
							$post['products_location'][$farm_location_id]['duration'] = '';
							$farm_location = $this->gm_db->get('user_farm_locations', ['id' => $farm_location_id], 'row');
							if ($farm_location) {
								$driving_distance = get_driving_distance([
									['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
									['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
								]);
								$post['products_location'][$farm_location_id]['duration'] = $driving_distance['duration'];
							}
						}
					}
				}
				if (isset($post['products_photo'])) {
					$dir = 'products/'.str_replace('@', '-', $this->accounts->profile['email_address']);
					$uploads = files_upload($_FILES, $dir);
					// debug($post, $uploads, 'stop');
					if ($uploads) {
						$this->gm_db->remove('products_photo', ['product_id' => $id]);
						foreach ($uploads as $key => $upload) {
							unset($upload['user_id']);
							$upload['product_id'] = $id;
							if ($key == $post['products_photo']['index']) {
								$upload['is_main'] = 1;
							} else {
								$upload['is_main'] = 0;
							}
							$this->products->new($upload, 'products_photo');
							$post['file_photos'][] = $upload;
						}
					}
					if (isset($post['products_photo']['id'])) {
						$this->gm_db->save('products_photo', ['is_main' => 0], ['product_id' => $id]);
						sleep(1);
						$this->gm_db->save('products_photo', ['is_main' => 1], ['id' => $post['products_photo']['id']]);
						$post['file_photos'] = $this->gm_db->get('products_photo', ['product_id' => $id]);
						if (isset($post['activity'])) {
							$this->products->save(['activity' => $post['activity']], ['id' => $id]);
						}
					}
					/*email admins here*/
					$content = '<p>Product '.ucwords($product['name']).' saved!</p><p>It is now being reviewed for approval.</p><p>Please check it <a href="'.base_url('farm/save-veggy/'.$id.'/'.nice_url($product['name'], true)).'" data-readit="1">here</a>.</p>';
					// debug($content, 'stop');
					if (send_gm_email($this->accounts->profile['id'], $content)) {
						send_gm_message($this->accounts->profile['id'], strtotime(date('Y-m-d')), $content);
					}
				}
				$post['product_id'] = $id;
				$post['updated'] = 1;
				$post['products'] = $product;
				$products_locations = $this->gm_db->get('products_location', ['product_id' => $id]);
				if ($products_locations) {
					foreach ($products_locations as $key => $location) {
						$farm_location = $this->gm_db->get('user_farm_locations', ['id' => $location['farm_location_id']], 'row');
						if ($farm_location) {
							$driving_distance = get_driving_distance([
								['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
								['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
							]);
							$address = explode(',', $farm_location['address_2']);
							$products_locations[$key]['city'] = isset($address[0]) ? $address[0] : '';
							$products_locations[$key]['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
							$products_locations[$key]['province'] = isset($address[1]) ? trim($address[1]) : '';
							$products_locations[$key]['duration'] = $driving_distance['duration'];
						}
					}
					$post['products_location'] = $products_locations;
				}
				// $this->set_response('success', 'Veggie Updated', $post, 'farm/inventory');
				$this->set_response('success', 'Veggie Updated', $post, false, 'redirectNewProduct');
			}
			$this->set_response('error', 'Unable to save product', $post);
		} else {
			// $product = $this->products->get_in(['id' => $id], ['description', 'category_id', 'farms'], false, true, true);
			$product = $this->products->products_with_location(['id' => $id, 'user_id' => $this->accounts->profile['id']], true);
			// debug($product, 'stop');
			if ($product) {
				if ($name !== nice_url($product['name'], true)) {
					redirect(base_url('farm/save-veggy/'.$id.'/'.nice_url($product['name'], true).'/'));
				}
				$this->render_page([
					'top' => [
						'css' => ['dashboard/main', 'looping/product-card', 'farm/new-veggy']
					],
					'middle' => [
						'body_class' => ['dashboard', 'new-veggy',  'save-veggy', 'static/product-list-card'],
						'head' => ['dashboard/navbar'],
						'body' => [
							'dashboard/navbar_aside',
							'farm/veggy_form',
						],
					],
					'bottom' => [
						'modals' => ['veggy_form_help_modal'],
						'js' => [
							'plugins/jquery.inputmask.min',
							'plugins/inputmask.binding',
							'farm/main',
							'farm/save-veggy',
							'dashboard/main',
						],
					],
					'data' => [
						'has_products' => $this->products->count(),
						'pagination' => $this->products->paginate($id, 'CONCAT("farm/save-veggy/", id, "/", REPLACE(LOWER(REPLACE(TRIM(name), " ", "-")), "", "), "))'),
						'product' => $product,
						'is_edit' => true,
					],
				]);
			} else {
				show_404();
			}
		}
	}

	public function remove_veggy($id=0, $name='', $perm_delete=0)
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		$mode = 'deactivate';
		if ($perm_delete == 0) $mode = 'unpublish';
		if ($post AND (!isset($post['_']) OR (isset($post['callback']) AND $post['callback'] != 'gmCall'))) {
			$product_id = isset($post['id']) ? $post['id'] : 0;
			$response = check_products_in_delivery($product_id);
			// debug($response, $post, 'stop');
			if ($response) {
				/*remove product*/
				$activity = GM_ITEM_DELETED;
				$redirect = 'removeEditBtn';
				$mode = 'unpublished';
				if (isset($post['perm_delete']) AND $post['perm_delete'] == 1) {
					$activity = GM_ITEM_NO_INVENTORY; 
					$redirect = 'removeOnTable';
					$mode = 'deactivated';
				}
				$this->products->save(['activity' => $activity], ['id' => $product_id]);
				/*email admins here*/
				$content = remove_multi_space('<p>Product item '.ucwords($post['name']).' has been '.$mode.'.</p>', true);
				// debug($content, 'stop');
				/*notify user*/
				send_gm_email($this->accounts->profile['id'], $content);
				send_gm_message($this->accounts->profile['id'], strtotime(date('Y-m-d')), $content, 'Notifications', 'Inventory', 'message', false, ['page_id' => $product_id]);
				$this->set_response('success', 'Product '.$mode.'', $post, false, $redirect);
			}
			$this->set_response('error', remove_multi_space('Unable to '.$mode.' '.ucwords($post['name']).' product', true), $post);
		} else {
			$mode = 'deactivate';
			if ($perm_delete == 0) $mode = 'unpublish';
			$this->set_response('confirm', 'Want to '.$mode.' this item?', ['id' => $id, 'name' => $name, 'perm_delete' => $perm_delete], false, 'removeItem');
		}
	}

	public function storefront()
	{
		$post = $this->input->post();
		if ($post) {
			$profile = $this->accounts->has_session ? $this->accounts->profile : false;
			// debug($post, 'stop');
			if ($profile) {
				$user_id = $profile['id'];
				$farm_id = $farm_location_id = 0;
				$first_save = false;
				if (isset($post['user_farms'])) {
					$farm_id = isset($post['user_farms']['id']) ? $post['user_farms']['id'] : 0;
					$post['user_farms']['user_id'] = $user_id;
					$post['user_farms']['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
					if ($farm_id == 0) {
						$first_save = true;
						$farm_id = $this->gm_db->new('user_farms', $post['user_farms']);
					} else {
						unset($post['user_farms']['id']);
						$this->gm_db->save('user_farms', $post['user_farms'], ['id' => $farm_id]);
					}
					$post['user_farms']['id'] = $farm_id;
				}
				if (isset($post['farm_loc']) AND isset($post['user_farm_locations']) AND $farm_id > 0) {
					$index = $post['farm_loc'];
					if (isset($post['user_farm_locations'][$index])) {
						$locations = $post['user_farm_locations'][$index];
						$farm_location = isset($post['locations'][$index]) ? $post['locations'][$index] : [];
						// debug($locations, 'stop');
						$data = [];
						foreach ($locations as $key => $location) {
							if (isset($farm_location[$key])) $location_id = $farm_location[$key]['id'];
							$location = json_decode($location, true);
							if (isset($location_id)) $location['id'] = $location_id;
							$data[] = $location;
						}
						// debug($data, $farm_id, 'stop');
						if (count($data) > 0) {
							foreach ($data as $row) {
								$row['farm_id'] = $farm_id;
								$row['active'] = $index;
								$row['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
								if (isset($row['id']) AND !empty($row['id'])) {
									$farm_location_id = $row['id'];
									unset($row['id']);
									$this->gm_db->save('user_farm_locations', $row, ['id' => $farm_location_id]);
								} else {
									$this->gm_db->new('user_farm_locations', $row);
								}
							}
						}
						if ($index != 0) {
							$user_farm_locations = $this->gm_db->get('user_farm_locations', [
								'farm_id' => $farm_id
							], 'result', 'id, lat, lng, address_1, address_2');
							if ($user_farm_locations) {
								foreach ($user_farm_locations as $key => $row) {
									$post['user_farm_locations'][$index][$key] = json_encode($row, JSON_NUMERIC_CHECK);
								}
							}
						}
					}
				}

				$message = 'Storefront Succesfully Created!';
				if ($this->farms) $message = 'Storefront Succesfully Updated!';
				if ($first_save) {
					/*email admins here*/
					$user_farm = $this->gm_db->get('user_farms', ['id' => $farm_id], 'row');
					if ($user_farm) {
						$content = '<p>You have created your Storefront!</p><p>Please check it <a href="'.base_url('store/'.$farm_id.'/'.$farm_location_id.'/'.nice_url($user_farm['name'], true)).'" data-readit="1">here</a>.</p>';
						// debug($content, 'stop');
						send_gm_email($profile['id'], $content);
						send_gm_message($profile['id'], strtotime(date('Y-m-d')), $content, 'Notifications', 'System Update', 'message', false, ['page_id' => $farm_id, 'entity_id' => $farm_location_id]);
					}
				}
				$this->set_response('info', $message, $post, 'farm/storefront/');
			}
			$this->set_response('error', 'Location verified!', $post);
		} else {
			$profile = $this->accounts->has_session ? $this->accounts->profile : false;
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'farm/storefront', '../js/plugins/chosen/chosen', 'ImageSelect', 'static/store'],
				],
				'middle' => [
					'body_class' => ['dashboard', 'storefront', 'farm'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/storefront',
					],
				],
				'bottom' => [
					'modals' => ['farmer_terms_modal', 'farm_location_modal', 'storefront_help_modal', 'media_modal'],
					'js' => [
						'plugins/chosen/new-chosen',
						'plugins/ImageSelect.jquery',
						'farm/main',
						'farm/storefront',
						'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
						'plugins/markerclusterplus.min',
						'dashboard/main'
					],
				],
				'data' => [
					'farms' => $this->accounts->profile['farms'],
					'farm_locations' => $this->accounts->profile['farm_locations'],
					'products' => $this->products->get_in(['user_id' => $profile['id']], ['category_id', 'photos']),
					'galleries' => $this->galleries,
				]
			]);
		}
	}

	public function inventory()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', '../js/plugins/DataTables/datatables.min', 'farm/inventory'],
			],
			'middle' => [
				'body_class' => ['dashboard', 'inventory'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/inventory',
				],
			],
			'bottom' => [
				'js' => ['farm/main', 'plugins/DataTables/datatables.min', 'dashboard/main'],
			],
			'data' => [
				'products' => $this->products->get_in(['user_id' => $this->accounts->profile['id'], 'activity !=' => GM_ITEM_NO_INVENTORY]),
				'field_lists' => ['ACTIONS', 'NAME', 'ACTIVITY', 'CATEGORY', 'SUBCATEGORY', 'LOCATIONS', 'UPDATED'],
			],
		]);
	}

	public function settings()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'farm/settings'],
			],
			'middle' => [
				'body_class' => ['dashboard', 'settings'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/settings',
				],
			],
			'bottom' => [
				'js' => ['farm/main', 'dashboard/main'],
			],
		]);
	}

	public function store($id=0, $farm_location_id=0, $name=false)
	{
		$profile = $this->accounts->has_session ? $this->accounts->profile : false;
		if ($name == 'preview') {
			$user_farm = $this->gm_db->get('user_farms', ['user_id' => $profile['id']], 'row');
		} else {
			$user_farm = $this->gm_db->get('user_farms', ['id' => $id], 'row');
		}
		$data = false;
		// debug($user_farm, 'stop');
		if ($user_farm) {
			if (empty($name)) $name = nice_url($user_farm['name'], true);
			if (is_numeric($farm_location_id) AND $farm_location_id > 0) {
				$farm_location = $this->gm_db->get('user_farm_locations', ['farm_id' => $user_farm['id'], 'id' => $farm_location_id], 'row');
			} else {
				$farm_location = $this->gm_db->get('user_farm_locations', ['farm_id' => $user_farm['id']], 'row');
			}
			if ($farm_location_id == 0 AND $farm_location) {
				$farm_location_id = $farm_location['id'];
				$this->store_location($id, $farm_location_id, $name);
			} elseif ($farm_location == false) {
				/*wrong farm location*/
				show_404();
			} else {
				$user_farm['farm_location_id'] = $farm_location['id'];
			}
			// debug($user_farm, 'stop');
			$destinations = $this->latlng;
			if (isset($farm_location['lat']) AND isset($farm_location['lng'])) {
				$destinations = ['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']];
			}
			$data_latlng = get_cookie('prev_latlng', true);
			if (empty($data_latlng)) {
				$origins = $this->latlng;
			} else {
				$origins = unserialize($data_latlng);
			}
			// debug($destinations, $origins, 'stop');
			$data = [
				'farm' => $user_farm,
				'location' => $farm_location,
				'products' => nearby_products($destinations, false, $user_farm['user_id'], $farm_location_id, $origins),
				'products_no_location_count' => $this->gm_db->count('products', ['user_id' => $user_farm['user_id']]),
			];
			// debug($data, 'stop');
			$this->render_page([
				'top' => [
					'index_page' => 'yes',
					'css' => ['static/store', 'looping/product-card', 'modal/modals'],
				],
				'middle' => [
					'body_class' => ['store'],
					'head' => ['../global/global_navbar'],
					'body' => [
						'../static/store',
					],
					'footer' => [
						'global/footer'
					],
				],
				'bottom' => [
					'modals' => ['media_modal'],
					'js' => ['farm/store', 'dashboard/main'],
				],
				'data' => $data
			]);
		} else {
			show_404();
		}
	}

	public function store_location($id=0, $farm_location_id=0, $name=false)
	{
		// $this->store($id, $name, $farm_location_id);
		redirect(base_url('store/'.$id.'/'.$farm_location_id.'/'.$name));
	}

	public function store_farm($id=0, $name=false)
	{
		// $this->store($id, $name, $farm_location_id);
		redirect(base_url('store/'.$id.'/0/'.$name));
	}
}