<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends MY_Controller {

	public $allowed_methods = [];
	public $not_allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('baskets');
		// INITIALIZING TOKTOK OBJECT
		// $this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');
	}

	public function index()
	{
		$this->orders();
	}

	public function orders()
	{
		$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status >' => 1]);
		// debug($baskets, 'stop');
		$assembled = false;
		if (!is_bool($baskets) AND count($baskets)) {
			$products = [];
			foreach ($baskets as $key => $basket) {
				if (isset($products[$basket['product_id']][$basket['status']])) {
					$basket['quantity'] = $products[$basket['product_id']][$basket['status']]['quantity'] += $basket['quantity'];
					$basket['id'] = $products[$basket['product_id']][$basket['status']]['id'] .= ','.$basket['id'];
				}
				$products[$basket['product_id']][$basket['status']] = $basket;
			}
			// debug($products, 'stop');
			$assembled = [];
			foreach ($products as $product_id => $product) {
				foreach ($product as $status => $basket) {
					$date = date('F j, Y', strtotime($basket['updated']));
					$basket['uptime'] = date('g:ia', strtotime($basket['updated']));
					$assembled[get_status_value($status)][$date][$basket['rawdata']['farm']['name']][] = $basket;
				}
			}
		}
		// debug($assembled, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'transactions/orders']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/orders',
				],
			],
			'bottom' => [
				'js' => ['hideshow', 'dashboard/main'],
			],
			'data' => [
				'orders' => $assembled
			]
		]);
	}

	public function messages()
	{
		$messages = $this->gm_db->get('messages', ['user_id' => $this->accounts->profile['id'], 'unread' => 1]);
		// debug($messages, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'transactions/messages']
			],
			'middle' => [
				'body_class' => ['dashboard', 'messages'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/messages',
				],
			],
			'bottom' => [
				'modals' => ['reply_modal'],
				'js' => ['hideshow', 'plugins/readmore.min', 'transactions/messages', 'dashboard/main'],
			],
			'data' => [
				'messages' => $messages
			]
		]);
	}

	public function thankyou()
	{
		$this->render_page([
			'top' => [
				'css' => ['static/thankyou']
			],
			'middle' => [
				'body_class' => ['thankyou'],
				'head' => ['../global/global_navbar'],
				'body' => [
					'../static/thankyou'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => [],
			],
		]);
	}
}