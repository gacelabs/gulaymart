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
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' is your neighborhood veggies supplier.',
					'name' => APP_NAME.' is your neighborhood veggies supplier.',
				],
				'index_page' => 'yes',
				'page_title' => APP_NAME.' | Veggies grown by community.',
				'css' => ['marketplace', 'farmer-card'],
			],
			'middle' => [
				'body' => [
					'marketplace/navbar',
					'marketplace/carousel',
					'marketplace/banner',
					'marketplace/category',
					'marketplace/products',
					'marketplace/famers'
				],
				'footer' => [
					'templates/marketplace/poster',
					'static/footer'
				],
			],
			'bottom' => [
				'modals' => ['search_popup'],
				'js' => ['isotope.min', 'marketplace', 'fb-login'],
			],
			'data' => [
				'products' => $this->products->get(),
				'total' => $this->products->count()
			],
		]);
	}
}