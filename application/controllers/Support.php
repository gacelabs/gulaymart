<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = ['order_details', 'thankyou_page', 'terms', 'policy'];

	public function index()
	{
		$this->help_center();
	}

	public function help_center()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main']
			],
			'middle' => [
				'body_class' => ['support', 'help-center'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
				],
			],
		]);
	}

	public function order_details()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		if ($post) {
			$results = $this->gm_db->get_in('baskets_merge', ['id' => $post['id']], 'row');
			if ($results) {
				$results['action'] = $post['action'];
				$results['for'] = $post['for'];
				$results['status'] = $post['status'];
				$results['data'] = ['name' => 'There'];
				if ($post['for'] == 'seller') {
					$results['data'] = json_decode(base64_decode($results['seller']), true);
				}
				if ($post['for'] == 'buyer') {
					$results['data'] = json_decode(base64_decode($results['buyer']), true);
					$results['data']['name'] = $results['data']['fullname'];
				}
				// debug($results, 'stop');
				$this->load->view('global/email-seller', $results);
			}
		}
		return '';
	}

	public function thankyou_page()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		if ($post) {
			$this->load->view('global/email-order', $post);
		}
		return '';
	}

	public function terms()
	{
		$this->render_page([
			'top' => [
				'css' => ['static/register', 'modal/modals'],
			],
			'middle' => [
				'head' => ['../global/global_navbar'],
				'body_class' => ['register'],
				'body' => [
					'support/terms',
				],
				'footer' => ['global/footer']
			],
			'data' => [
				'is_login' => 0
			]
		]);
	}

	public function policy()
	{
		$this->render_page([
			'top' => [
				'css' => ['static/register', 'modal/modals'],
			],
			'middle' => [
				'head' => ['../global/global_navbar'],
				'body_class' => ['register'],
				'body' => [
					'support/policy',
				],
				'footer' => ['global/footer']
			],
			'data' => [
				'is_login' => 0
			]
		]);
	}
}