<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends MY_Controller {

	public $allowed_methods = 'all';

	public function index()
	{
		$data = [[
			'loc' => base_url(),
			'lastmod' => date('Y-m-d'),
			'changefreq' => 'always',
			'images' => [
				['url' => base_url('assets/images/logo.png')]
			]
		]];
		/*PRODUCTS*/
		$products = $this->gm_db->get_join(
			'products', ['products.activity' => 1], 'products_location', 'products_location.product_id = products.id', 'left', 
			'products.*, products_location.farm_location_id'
		);
		// debug($products, 'stop');
		if ($products) {
			foreach ($products as $key => $product) {
				$images = [];
				$photos = $this->gm_db->get_in('products_photo', ['product_id' => $product['id']]);
				// debug($photos, 'stop');
				if ($photos) {
					foreach ($photos as $photo) $images[] = ['url' => base_url($photo['url_path'])];
				}
				$data[] = [
					'loc' => product_url($product),
					'lastmod' => date('Y-m-d', strtotime($product['updated'])),
					'changefreq' => calculate($product, 'frequency'),
					'images' => count($images) ? $images : FALSE
				];
			}
		}
		/*FARMS*/
		$user_farms = $this->gm_db->get_join(
			'user_farms', false, 'user_farm_locations', 'user_farm_locations.farm_id = user_farms.id', 'left', 
			'user_farms.*, user_farm_locations.id AS farm_location_id'
		);
		// debug($user_farms, 'stop');
		if ($user_farms) {
			foreach ($user_farms as $key => $farm) {
				$images = [
					['url' => base_url($farm['cover_pic'])],
					['url' => base_url($farm['profile_pic'])],
				];
				$data[] = [
					'loc' => base_url('store/'.$farm['id'].'/'.$farm['farm_location_id'].'/'.nice_url($farm['name'], true)),
					'lastmod' => date('Y-m-d', strtotime($farm['updated'])),
					'changefreq' => calculate($farm, 'frequency'),
					'images' => count($images) ? $images : FALSE
				];
			}
		}
		// debug($data, 'stop');
		$this->load->view('static/sitemap', ['items' => $data]);
	}
}