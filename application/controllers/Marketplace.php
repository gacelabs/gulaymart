<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends MY_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
	}

	public function index($category=false)
	{
		$category_ids = false;
		if ($category != false) {
			$category_ids = get_data_in('products_category', ['value' => $category], 'id');
		} else {
			$category_ids = get_data_in('products_category', false, 'id');
		}
		if ($this->input->is_ajax_request()) {
			// debug($category_ids, 'stop');
			$not_ids = $this->input->post('not_ids') ?: $this->input->get('not_ids');

			if (is_array($category_ids) AND count($category_ids)) {
				$nearby_products = nearby_products($this->latlng, ['category_ids' => $category_ids, 'not_ids' => $not_ids]);
			} else {
				$nearby_products = nearby_products($this->latlng, ['category_ids' => $category_ids, 'not_ids' => false]);
			}
			$html = '';
			if ($nearby_products) {
				foreach ($nearby_products as $key => $product) {
					$html .= $this->load->view('looping/product_card', ['data'=>$product, 'id'=>$product['category_id']], true);
				}
				$nearby_products = nearby_products($this->latlng, ['category_ids' => $category_ids, 'not_ids' => false, 'limit' => false]);
			}
			// debug($nearby_products, 'stop');
			echo json_encode(['success' => ($html != ''), 'html' => $html, 'count' => (is_array($nearby_products) ? count($nearby_products) : 0)]); exit();
		}
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
					'plugins/fb-login', 
					'global',
				],
			],
			'data' => [
				'nearby_veggies' => nearby_veggies($this->latlng),
				'nearby_products' => nearby_products($this->latlng, ['category_ids' => $category_ids, 'not_ids' => false]),
				'nearby_products_count' => nearby_products($this->latlng, ['category_ids' => $category_ids, 'not_ids' => false, 'limit' => false]),
				'nearby_farms' => nearby_farms($this->latlng),
			],
		]/*, true*/);
	}

	public function category($category=false)
	{
		if ($category == false) {
			redirect(base_url('/'));
		}
		$this->index($category);
	}
}