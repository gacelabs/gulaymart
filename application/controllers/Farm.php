<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farm extends MY_Controller {

	public $allowed_methods = ['store'];

	public function __construct()
	{
		parent::__construct();
		// debug($this->action, 'stop');
		if ($this->accounts->has_session AND $this->accounts->profile['is_agreed_terms'] == 0 
			AND !in_array($this->action, ['storefront', 'store'])) {
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
		$post = $this->input->post();
		if ($post) {
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
								if (!isset($attribute['id'])) {
									$ids[] = $post['products_attribute'][$key]['id'] = $this->products->new($attribute, 'products_attribute');
								} else {
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
					}
					break;
				case '2':
					if (isset($product_id) AND $product_id > 0) {
						$product = $post['products'];
						$ok = $this->products->save($product, ['id' => $product_id]);
						if (isset($post['products_location'])) {
							$this->gm_db->remove('products_location', ['product_id' => $product_id]);
							foreach ($post['products_location'] as $key => $location) {
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
						if ($ok) {
							if ($passed == 0) {
								$passed = 1;
								$message = 'Passed on the 60% score, please continue below.';
							}
						} else {
							$passed = 0;
							$message = 'Did not Pass on the 60% score, product pricing seems to be wrong.';
						}
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
							$post['products'] = $this->gm_db->get('products', ['id' => $product_id]);
							$products_locations = $this->gm_db->get('products_location', ['product_id' => $product_id]);
							if ($products_locations) {
								foreach ($products_locations as $key => $location) {
									$farm_location = $this->gm_db->get('user_farm_locations', ['id' => $location['farm_location_id']], 'row');
									if ($farm_location) {
										$driving_distance = get_driving_distance([
											['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
											['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
										]);
										$products_locations[$key]['duration'] = $driving_distance['duration'];
									}
								}
								$post['products_location'] = $products_locations;
							}
							$post['file_photos'] = $this->gm_db->get('products_photo', ['product_id' => $product_id]);
						}
						if (count($ids) AND !in_array(false, $ids)) {
							if ($passed == 0) {
								$passed = 1;
								$message = 'Product Succesfully Added!';
								$successFunction = 'redirectNewProduct';
							}
						} else {
							$passed = 0;
							$message = 'Please check your image inputs.';
						}
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
					'body_class' => ['dashboard', 'new-veggy', 'static/product-list-card'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/veggy_form',
					],
				],
				'bottom' => [
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
		$post = $this->input->post();
		if ($post AND $id > 0) {
			if (check_data_values($post)) {
				// debug($post, $_FILES, 'stop');
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
						if (!isset($attribute['id'])) {
							$post['products_attribute'][$key]['id'] = $this->products->new($attribute, 'products_attribute');
						} else {
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
					}
				}
				$post['product_id'] = $id;
				$post['updated'] = 1;
				// $this->set_response('success', 'Veggie Updated', $post, 'farm/inventory');
				$this->set_response('success', 'Veggie Updated', $post, false, 'redirectNewProduct');
			}
			$this->set_response('error', 'Unable to save product', $post);
		} else {
			// $product = $this->products->get_in(['id' => $id], ['description', 'category_id', 'farms'], false, true, true);
			$product = $this->products->products_with_location(['id' => $id, 'user_id' => $this->accounts->profile['id']], true);
			// debug($product, 'stop');
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
					'product' => $product,
					'is_edit' => true,
				],
			]);
		}
	}

	public function remove_veggy($id=0, $name='')
	{
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post)) {
				// debug($post, 'stop');
				$this->products->save(['activity' => 2], $post);
				$this->set_response('success', 'Product removed', $post, false, 'removeOnTable');
			}
			$this->set_response('error', remove_multi_space('Unable to save '.$name.' product'), $post);
		} else {
			$this->set_response('confirm', 'Want to remove this item?', $id, false, 'removeItem');
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
				$farm_id = 0;
				if (isset($post['user_farms'])) {
					$farm_id = isset($post['user_farms']['id']) ? $post['user_farms']['id'] : 0;
					$post['user_farms']['user_id'] = $user_id;
					$post['user_farms']['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
					if ($farm_id == 0) {
						$farm_id = $this->gm_db->new('user_farms', $post['user_farms']);
					} else {
						unset($post['user_farms']['id']);
						$this->gm_db->save('user_farms', $post['user_farms'], ['id' => $farm_id]);
					}
				}
				$post['user_farms']['id'] = $farm_id;
				if (isset($post['farm_loc']) AND isset($post['user_farm_locations']) AND $farm_id > 0) {
					$index = $post['farm_loc'];
					if (isset($post['user_farm_locations'][$index])) {
						$locations = $post['user_farm_locations'][$index];
						$data = [];
						foreach ($locations as $key => $location) {
							$data[] = json_decode($location, true);
						}
						// debug($data, 'stop');
						if ($index == 0) {
							$this->gm_db->remove('user_farm_locations', ['farm_id' => $farm_id]);
							foreach ($data as $row) {
								$row['farm_id'] = $farm_id;
								$row['active'] = $index;
								$row['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
								$this->gm_db->new('user_farm_locations', $row);
							}
						} else {
							foreach ($data as $row) {
								$row['farm_id'] = $farm_id;
								$row['active'] = $index;
								$row['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
								if (isset($row['id'])) {
									$farm_location_id = $row['id']; unset($row['id']);
									$this->gm_db->save('user_farm_locations', $row, ['id' => $farm_location_id]);
								} else {
									$this->gm_db->new('user_farm_locations', $row);
								}
							}
						}
					}
				}
				$message = 'Storefront Succesfully Created!';
				if ($this->farms) $message = 'Storefront Succesfully Updated!';
				$this->set_response('info', $message, $post, false, 'refreshStorePreview');
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
					'modals' => ['farmer_terms_modal', 'farm_location_modal', 'media_modal', 'farm_location_help_modal'],
					'js' => [
						'plugins/chosen/new-chosen',
						'plugins/ImageSelect.jquery',
						'farm/main',
						'farm/storefront',
						'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA&libraries=places',
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
		// debug($this->products->get_in(), 'stop');
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
				'products' => $this->products->get_in(['user_id' => $this->accounts->profile['id']]),
				'field_lists' => $this->gm_db->fieldlists('products', unserialize(NON_PRODUCT_KEYS)),
			],
		]);
	}

	public function settings()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', '../js/plugins/DataTables/datatables.min'],
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

	public function store($id=0, $name=false)
	{
		if ($name != false AND (strtolower($name) != 'preview' OR $this->accounts->has_session)) {
			$profile = $this->accounts->has_session ? $this->accounts->profile : false;
			if ($name == 'preview') {
				$user_farm = $this->gm_db->get('user_farms', ['user_id' => $profile['id']], 'row');
			} else {
				$user_farm = $this->gm_db->get('user_farms', ['id' => $id], 'row');
			}
			$data = false;
			// debug($user_farm, 'stop');
			if ($user_farm) {
				$data = [
					'farm' => $user_farm,
					'locations' => $this->gm_db->get('user_farm_locations', ['farm_id' => $user_farm['id']]),
					'products' => nearby_products($this->latlng),
				];
				// debug($data, 'stop');
			}
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
			redirect(base_url('farm/'));
		}
	}
}