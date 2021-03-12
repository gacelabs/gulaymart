<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends MY_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('products');
	}

	public function index()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="xxx"',
					'property="og:description" content="Gulaymart is your neighborhood veggies supplier."',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart is your neighborhood veggies supplier."'
				],
				'index_page' => 'yes',
				'page_title' => 'Gulaymart | Veggies grown by community.',
				'css' => ['marketplace', 'global', 'farmer-card', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['marketplace'],
				/* found in views/templates */
				'head' => [],
				'body' => [
					'marketplace/navbar',
					'marketplace/carousel',
					'marketplace/banner',
					'marketplace/category',
					'marketplace/products',
					'marketplace/famers'
				],
				'footer' => [
				/* found in views/templates */
					'templates/marketplace/poster',
					'static/footer'
				],
			],
			'bottom' => [
				'modals' => ['search_popup'],
				'css' => [],
				'js' => ['isotope.min', 'main', 'marketplace', 'fb-login'],
			],
		];
		$data = [
			'is_login' => 0,
			'products' => $this->products->get(),
			'total' => $this->products->count()
		];
		// debug($data, 'stop');

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}