<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends MY_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
	}

	public function index($category=false, $not_ids=false, $has_ids=false)
	{
		$category_ids = false;
		if ($category != false) {
			$category_ids = get_data_in('products_category', ['value' => $category], 'id');
		} else {
			$category_ids = get_data_in('products_category', false, 'id');
		}
		$data = marketplace_data($category_ids, $not_ids, $has_ids);
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
				'head' => ['../global/global_navbar'],
				'body' => [
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
				'modals' => ['check_loc_modal'],
				'js' => [
					'plugins/jquery.inputmask.min',
					'plugins/inputmask.binding',
					'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
					'plugins/markerclustererplus.min',
					'marketplace/main',
				],
			],
			'data' => $data,
		]);
	}

	public function category($category=false, $keywords=false)
	{
		if ($category == false) redirect(base_url('/'));
		$keywords = $this->input->get('keywords') ? $this->input->get('keywords') : $keywords;
		if ($keywords === false) {
			$this->index($category);
		} else {
			// debug($keywords, 'stop');
			if ($this->input->get() AND !is_null($this->uri->segment(4))) {
				redirect(base_url('marketplace/category/'.$category.'/'.$keywords));
			} else {
				$category_ids = false;
				if ($category != false) {
					$category_ids = get_data_in('products_category', ['value' => $category], 'id');
				} else {
					$category_ids = get_data_in('products_category', false, 'id');
				}
				$has_ids = get_data_like('products', ['name' => $keywords], 'id');
				$data = marketplace_data($category_ids, false, $has_ids, $keywords);
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
						'head' => ['../global/global_navbar'],
						'body' => [
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
						'modals' => ['check_loc_modal'],
						'js' => [
							'plugins/jquery.inputmask.min',
							'plugins/inputmask.binding',
							'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
							'plugins/markerclustererplus.min',
							'marketplace/main',
						],
					],
					'data' => $data,
				]);
			}
		}
	}

	public function search($keywords=false)
	{
		$keywords = $this->input->get('keywords') ? $this->input->get('keywords') : $keywords;
		if ($keywords) {
			$has_ids = get_data_like('products', ['name' => $keywords], 'id');
			$referrer = parse_url($this->agent->referrer());
			if (isset($referrer['path']) AND strlen($referrer['path'])) {
				if ((bool)strstr($referrer['path'], '/category/') == true) {
					$path_data = explode('/category/', $referrer['path']);
					$category = $path_data[1];
					redirect(base_url('marketplace/category/'.$category.'?keywords='.$keywords));
				}
			}
			// debug($has_ids, 'stop');
			$data = marketplace_data(false, false, $has_ids, $keywords);
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
					'head' => ['../global/global_navbar'],
					'body' => [
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
					'modals' => ['check_loc_modal'],
					'js' => [
						'plugins/jquery.inputmask.min',
						'plugins/inputmask.binding',
						'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
						'plugins/markerclustererplus.min',
						'marketplace/main',
					],
				],
				'data' => $data,
			]);
		} else {
			redirect(base_url('/'));
		}
	}
}