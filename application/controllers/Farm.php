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
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Dashboard"',
					'property="og:description" content="Gulaymart | Dashboard"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Dashboard"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Dashboard',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'farm', 'sales'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
				],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => [],
				'css' => [],
				'js' => ['main'],
			],
		];
		$data = [
		];

		$this->load->view('main', ['view' => $view, 'data' => $data]);
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
			$view = [
				'top' => [
					'metas' => [
						// facebook opengraph
						'property="fb:app_id" content="xxx"',
						'property="og:type" content="article"',
						'property="og:url" content="xxx"',
						'property="og:title" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
						'property="og:description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
						'property="og:image" content="xxx"',
						// SEO generics
						'name="description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"'
					],
					'index_page' => 'no',
					'page_title' => 'Gulaymart | Farm » '.fix_title(__FUNCTION__).'',
					'css' => ['global', 'logged-in', 'rwd'],
					'js' => [],
				],
				'middle' => [
					'body_class' => ['logged-in', 'farm', 'new-veggy'],
					/* found in views/templates */
					'head' => ['dashboard/nav_top'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/new_veggy'
					],
					'footer' => [],
					/* found in views/templates */
				],
				'bottom' => [
					'modals' => ['login_modal'],
					'css' => [],
					'js' => ['main', 'farm'],
				],
			];
			$data = [
			];
			$this->load->view('main', ['view' => $view, 'data' => $data]);
		}
	}

	public function storefront()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Farm » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'farm', 'storefront'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/storefront'
				],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'css' => [],
				'js' => ['main', 'farm'],
			],
		];
		$data = [
		];

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}

	public function inventory()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Farm » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'farm', 'inventory'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'farm/inventory'
				],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'css' => [],
				'js' => ['main', 'farm'],
			],
		];
		$data = [
			'products' => $this->products->get()
		];
		// debug($this->products->get(), 'stop');

		$this->load->view('main', ['view' => $view, 'data' => $data]);
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
			$view = [
				'top' => [
					'metas' => [
						// facebook opengraph
						'property="fb:app_id" content="xxx"',
						'property="og:type" content="article"',
						'property="og:url" content="xxx"',
						'property="og:title" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
						'property="og:description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"',
						'property="og:image" content="xxx"',
						// SEO generics
						'name="description" content="Gulaymart | Farm » '.fix_title(__FUNCTION__).'"'
					],
					'index_page' => 'no',
					'page_title' => 'Gulaymart | Farm » '.fix_title(__FUNCTION__).'',
					'css' => ['global', 'logged-in', 'rwd'],
					'js' => [],
				],
				'middle' => [
					'body_class' => ['logged-in', 'farm', 'new-veggy'],
					/* found in views/templates */
					'head' => ['dashboard/nav_top'],
					'body' => [
						'dashboard/navbar_aside',
						'farm/edit_veggy'
					],
					'footer' => [],
					/* found in views/templates */
				],
				'bottom' => [
					'modals' => ['login_modal'],
					'css' => [],
					'js' => ['main', 'farm'],
				],
			];
			$data = [
				'product' => $product
			];
			$this->load->view('main', ['view' => $view, 'data' => $data]);
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
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Transactions » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'farm', 'settings'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
				],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'css' => [],
				'js' => ['main'],
			],
		];
		$data = [
		];

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}