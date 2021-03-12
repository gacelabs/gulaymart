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
		$this->new_veggy();
	}

	public function new_veggy()
	{
		$post = $this->input->post();
		if ($post) {
			$message = '';
			if (check_data_values($post)) {
				// debug($post, $this->products, 'stop');
				if ($this->products->new($post['products'])) {
					$this->set_response('success', 'New Veggie Added', $post);
				} else {
					$this->set_response('error', 'Unable to add product', $post);
				}
			}
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
						'dashboard/panel_left',
						'farm/new_veggy'
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
				'is_login' => 0
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
					'dashboard/panel_left',
					'farm/storefront'
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
			'is_login' => 0
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
					'dashboard/panel_left',
					'farm/inventory'
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
			'is_login' => 0,
			'products' => $this->products->get()
		];
		// debug($this->products->get(), 'stop');

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}