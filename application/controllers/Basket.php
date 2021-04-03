<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends My_Controller {

	public $allowed_methods = 'all';

	public function index()
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' is your neighborhood veggies supplier.',
					'name' => 'Product Name -'.APP_NAME,
				],
				'index_page' => 'yes',
				'page_title' => 'Product Name -'.APP_NAME,
				'css' => ['marketplace', 'productpage'],
			],
			'middle' => [
				'body' => [
					'marketplace/navbar',
					'productpage/top'
				],
				'footer' => [
					'static/footer'
				],
			],
			'bottom' => [
				'js' => ['productpage'],
			],
			'data' => [
			],
		]);
	}
}