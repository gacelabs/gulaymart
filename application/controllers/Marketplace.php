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
		marketplace_data($category_ids, $not_ids, $has_ids);
	}

	public function category($category=false, $keywords=false)
	{
		if ($category == false) {
			redirect(base_url('/'));
		}
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
				marketplace_data($category_ids, false, $has_ids, $keywords);
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
			marketplace_data(false, false, $has_ids, $keywords);
		} else {
			redirect(base_url('/'));
		}
	}
}