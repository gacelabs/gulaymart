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
			if (check_data_values($post)) {
				// debug($post, $this->accounts->profile, 'stop');
				if ($id = $this->products->new($post['products'])) {
					if ($this->products->new_location(['user_id' => $this->accounts->profile['id'], 'product_id' => $id, 'location_id' => $post['products']['location_id']])) {
						$post['products']['id'] = $id;
						$this->set_response('success', 'New Veggie Added', $post);
					}
				}
			}
			$this->set_response('error', 'Unable to add product', $post);
		} else {
			$this->render_page([
				'middle' => [
					'body_class' => ['farm', 'new-veggy'],
					'head' => ['dashboard/nav_top'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/new_veggy',
					],
				],
				'bottom' => [
					'js' => ['farm'],
				],
			]);
		}
	}

	public function storefront()
	{
		$this->render_page([
			'middle' => [
				'body_class' => ['farm', 'storefront'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/storefront',
				],
			],
			'bottom' => [
				'js' => ['farm'],
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