<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farm extends MY_Controller {

	public $allowed_methods = ['store'];

	public function __construct()
	{
		parent::__construct();
		// debug($this->action, 'stop');
		if ($this->accounts->has_session AND $this->accounts->profile['is_agreed_terms'] == 0 AND !in_array($this->action, ['storefront', 'store'])) {
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
					'js' => ['chart.min', 'farm/sales', 'dashboard/main'],
				],
				'data' => [
					'product_count' => $product_count
				],
			]);
		} else {
			redirect(base_url('farm/new-veggy'));
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
						$id = $this->products->save($product, ['id' => $product_id]);
						if ($id) {
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
								$post['file_photos'][] = $upload;
							}
							$this->products->save(['activity' => $post['activity']], ['id' => $product_id]);
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
		} else {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'looping/product-card', 'farm/new-veggy']
				],
				'middle' => [
					'body_class' => ['dashboard', 'new-veggy', 'static/product-list-card'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/new_veggy',
					],
				],
				'bottom' => [
					'js' => [
						'jquery.inputmask.min',
						'inputmask.binding',
						'farm',
						'farm/new-veggy',
						'dashboard/main',
						'common'
					],
				],
			]);
		}
	}

	public function storefront()
	{
		$post = $this->input->post();
		if ($post) {
			// debug();
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
						$this->gm_db->remove('user_farm_locations', ['farm_id' => $farm_id]);
						foreach ($data as $row) {
							$row['farm_id'] = $farm_id;
							$row['active'] = $index;
							$row['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
							$this->gm_db->new('user_farm_locations', $row);
						}
					}
				}
				if (isset($post['user_farm_contents']) AND $farm_id > 0) {
					$this->gm_db->remove('user_farm_contents', ['farm_id' => $farm_id]);
					$post['user_farm_contents']['farm_id'] = $farm_id;
					$post['user_farm_contents']['products'] = json_encode($post['user_farm_contents']['products']);
					$post['user_farm_contents']['galleries'] = json_encode($post['user_farm_contents']['galleries']);
					$this->gm_db->new('user_farm_contents', $post['user_farm_contents']);
				}
				$this->set_response('info', 'Storefront Succesfully Created!', $post, false, 'refreshStorePreview');
			}
			$this->set_response('error', 'Location verified!', $post);
		} else {
			$profile = $this->accounts->has_session ? $this->accounts->profile : false;
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'farm/storefront', '../js/chosen/chosen', 'ImageSelect', 'static/store'],
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
						'chosen/new-chosen',
						'plugins/ImageSelect.jquery',
						'farm/farm',
						'farm/storefront',
						'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA&libraries=places',
						'plugins/markerclusterplus.min',
						'dashboard/main'
					],
				],
				'data' => [
					'farms' => $this->accounts->profile['farms'],
					'farm_locations' => $this->accounts->profile['farm_locations'],
					'farm_contents' => $this->accounts->profile['farm_contents'],
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
				'css' => ['dashboard/main', '../js/DataTables/datatables.min', 'farm/inventory'],
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
				'js' => ['farm', 'inventory', 'DataTables/datatables.min', 'dashboard/main'],
			],
			'data' => [
				'products' => $this->products->get_in()
			],
		]);
	}

	public function edit($id=0)
	{
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post)) {
				// debug($post, $this->accounts->profile, 'stop');
				$products = $post['products'];
				if ($this->products->save($products, ['id' => $id])) {
					$where = ['user_id' => $this->accounts->profile['id'], 'product_id' => $id];
					if (isset($products['location_id']) AND $this->products->save_location(['location_id' => $products['location_id']], $where)) {
						$post['products']['id'] = $id;
					}
					$this->set_response('success', 'Veggie Updated', $post, 'farm/inventory');
				}
			}
			$this->set_response('error', 'Unable to save product', $post);
		} else {
			$product = $this->products->get(['id' => $id], false, false, true);
			// debug($this->accounts->profile, $product, 'stop');
			$this->render_page([
				'middle' => [
					'body_class' => ['farm', 'new-veggy'],
					'head' => ['dashboard/nav_top'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/edit_veggy',
					],
				],
				'bottom' => [
					'js' => ['farm', 'dashboard/main'],
				],
				'data' => [
					'product' => $product
				],
			]);
		}
	}

	public function remove($id=0, $remove=0)
	{
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post)) {
				// debug($post, 'stop');
				$this->products->save(['activity' => 0], $post);
				$this->set_response('success', 'Product removed', $post, false, 'removeOnTable');
			}
			$this->set_response('error', 'Unable to save product', $post);
		} else {
			$this->set_response('confirm', 'Want to remove this item?', $id, false, 'removeItem');
		}
	}

	public function settings()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', '../js/DataTables/datatables.min'],
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
				'js' => ['farm', 'dashboard/main'],
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
				$user_farm = $this->gm_db->get('user_farms', ['id' => $id, 'name' => str_replace('-', ' ', $name)], 'row');
			}
			$data = false;
			// debug($user_farm, 'stop');
			if ($user_farm) {
				$data = [
					'farm' => $user_farm,
					'locations' => $this->gm_db->get('user_farm_locations', ['farm_id' => $user_farm['id']]),
				];
				$contents = $this->gm_db->get('user_farm_contents', ['farm_id' => $user_farm['id']], 'row');
				// debug($contents, 'stop');
				$products_html = $galleries_html = '';
				if ($contents) {
					$productids = json_decode($contents['products'], true);
					$products = $this->products->get_in(['id' => $productids, 'user_id' => $user_farm['user_id']], ['category_id', 'photos']);
					// debug($products, 'stop');
					foreach ($products as $key => $product) {
						$products_html .= $this->load->view('looping/product_item', ['data'=>$product, 'forajax'=>1, 'id'=>$product['category_id']], true);
					}
					// debug($products_html, 'stop');
					$galleriesids = json_decode($contents['galleries'], true);
					$galleries = $this->gm_db->get_in('galleries', ['id' => $galleriesids, 'user_id' => $user_farm['user_id']]);
					// debug($galleries, 'stop');
					$galleries_html = $this->load->view('looping/gallery_item', ['data'=>$galleries, 'title'=> 'Galleries'], true);
					// debug($galleries_html, 'stop');
					$data['contents'] = [
						'products_html' => $products_html,
						'stories' => [
							'title' => $contents['story_title'],
							'content' => $contents['story_content'],
						],
						'galleries_html' => $galleries_html,
						'about' => $contents['about'],
					];
				}
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
						'static/footer'
					],
				],
				'bottom' => [
					'js' => ['store', 'dashboard/main'],
				],
				'data' => $data
			]);
		} else {
			redirect(base_url('/'));
		}
	}
}