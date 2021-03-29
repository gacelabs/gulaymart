<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Farm extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('products');
	}

	public function index()
	{
		$this->sales();
	}

	public function sales()
	{
		$this->render_page([
			'top' => [
				'css' => ['sales', 'chart.min'],
			],
			'middle' => [
				'body_class' => ['farm', 'sales'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/sales',
				],
			],
			'bottom' => [
				'js' => ['farm', 'chart.min', 'sales'],
			],
		]);
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
						$dir = str_replace('@', '-', $profile['email_address']);
						$uploads = files_upload($_FILES, $dir);
						$ids = [];
						if ($uploads) {
							$this->gm_db->remove('products_photo', ['product_id' => $product_id]);
							foreach ($uploads as $key => $upload) {;
								$upload['product_id'] = $product_id;
								if ($key == $post['products_photo']['index']) {
									$upload['is_main'] = 1;
								} else {
									$upload['is_main'] = 0;
								}
								$ids[] = $this->products->new($upload, 'products_photo');
								unset($upload['path']);
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
					'css' => ['new-veggy', 'product-item']
				],
				'middle' => [
					'body_class' => ['farm', 'new-veggy'],
					'head' => ['dashboard/nav_top'],
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
						'new-veggy'
					],
				],
			]);
		}
	}

	public function storefront()
	{
		$this->render_page([
			'top' => [
				'css' => ['storefront', 'storefront-page'],
			],
			'middle' => [
				'body_class' => ['farm', 'storefront'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/storefront',
				],
			],
			'bottom' => [
				'modals' => ['farmer_terms_modal', 'farm_location_modal'],
				'js' => ['farm', 'storefront'],
			],
		]);
	}

	public function inventory()
	{
		$this->render_page([
			'middle' => [
				'body_class' => ['farm', 'inventory'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/inventory',
				],
			],
			'bottom' => [
				'js' => ['farm'],
			],
			'data' => [
				'products' => $this->products->get()
			],
		]);
	}

	public function edit($id=0)
	{
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post)) {
				// debug($post, $this->accounts->profile, 'stop');
				if ($this->products->save($post['products'], ['id' => $id])) {
					$where = ['user_id' => $this->accounts->profile['id'], 'product_id' => $id];
					if ($this->products->save_location(['location_id' => $post['products']['location_id']], $where)) {
						$post['products']['id'] = $id;
						$this->set_response('success', 'Veggie Updated', $post, 'farm/inventory');
					}
				}
			}
			$this->set_response('error', 'Unable to save product', $post);
		} else {
			$product = $this->products->get(['id' => $id], false, false, true);
			// debug($product, 'stop');
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
					'js' => ['farm'],
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
			'middle' => [
				'body_class' => ['farm', 'settings'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/settings',
				],
			],
			'bottom' => [
				'js' => ['farm'],
			],
		]);
	}
}