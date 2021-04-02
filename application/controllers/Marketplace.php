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
		// debug($this->products->get_by_category_pages(), 'stop');
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' is your neighborhood veggies supplier.',
					'name' => APP_NAME.' is your neighborhood veggies supplier.',
				],
				'index_page' => 'yes',
				'page_title' => APP_NAME.' | Veggies grown by community.',
				'css' => ['marketplace', 'farmer-card', 'product-item'],
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
				'products' => $this->products->get_by_category_pages()
			],
		]);
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