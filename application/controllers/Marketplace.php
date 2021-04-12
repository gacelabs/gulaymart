<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends MY_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		// debug(nearby_farms($this->latlng), nearby_products($this->latlng), 'stop');
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' is your neighborhood veggies supplier.',
					'name' => APP_NAME.' is your neighborhood veggies supplier.',
				],
				'index_page' => 'yes',
				'page_title' => APP_NAME.' | Veggies grown by community.',
				'css' => ['modal/modals', 'marketplace/main', 'looping/product-card', 'looping/farmer-card', 'global/veggy-nearby'],
			],
			'middle' => [
				'body' => [
					'../global/global_navbar',
					'marketplace/carousel',
					'../global/veggy_nearby',
					'marketplace/category',
					'marketplace/products_container',
					'marketplace/famers_container'
				],
				'footer' => [
					'global/footer'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => ['marketplace/main', 'plugins/fb-login'],
			],
			'data' => [
				'products' => nearby_products($this->latlng),
				'nearby_farms' => nearby_farms($this->latlng),
			],
		]/*, true*/);
	}

	public function loadmore()
	{
		$post = $this->input->post();
		$html = '';
		if ($post) {
			foreach ($post['page'] as $key => $id) {
				$product = $this->products->get(['id' => $id], false, true, true);
				$html .= $this->load->view('looping/product_item', ['data'=>$product, 'id'=>$id, 'forajax'=>true], true);
			}
			// debug($post, $html, 'stop');
			if (strlen(trim($html)) > 0) {
				echo json_encode(['success' => true, 'data' => ['post' => $post, 'html' => $html], 'callback' => 'renderMoreVeggies']); exit();
			}
		}
		echo json_encode(['success' => false, 'data' => ['post' => $post, 'html' => $html]]); exit();
	}
}