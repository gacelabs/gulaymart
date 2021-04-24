<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {

	public $allowed_methods = ['comment'];
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

	public function placed()
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
				'css' => ['dashboard/main', 'global/order-table', 'orders/main']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders-active', 'orders-placed'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'orders/orders_container',
				],
			],
			'bottom' => [
				'js' => ['orders/main'],
			],
			'data' => [
				'orders' => $assembled
			]
		]);
	}

	public function delivery()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'orders/main', 'global/order-table', 'global/zigzag', 'modal/invoice-modal']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders-active', 'orders-delivery'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'orders/orders_container',
				],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => [],
			],
			'data' => []
		]);
	}

	public function received()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'orders/main', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders-active', 'orders-received'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'orders/orders_container',
				],
			],
			'bottom' => [
				'js' => [],
			],
			'data' => []
		]);
	}

	public function cancelled()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'orders/main', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders-active', 'orders-cancelled'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'orders/orders_container',
				],
			],
			'bottom' => [
				'js' => [],
			],
			'data' => []
		]);
	}

	public function messages()
	{
		$messages = $this->gm_db->get('messages', ['user_id' => $this->accounts->profile['id'], 'unread' => 1]);
		// debug($messages, 'stop');
		$data_messages = false;
		if ($messages) {
			$data_messages = [];
			foreach ($messages as $key => $message) {
				if ($message['tab'] == 'Feedbacks' AND $message['type'] == 'Comments') {
					$message['product'] = $this->gm_db->get('products', ['id' => $message['page_id']], 'row');
					$message['product']['farm_location_id'] = $message['entity_id'];
					$message['location'] = $this->gm_db->get('products_location', [
						'product_id' => $message['page_id'],
						'farm_location_id' => $message['entity_id']
					], 'row');
					$message['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $message['user_id']], 'row');
					$message['bought'] = $this->gm_db->count('baskets', [
						'user_id' => $message['user_id'],
						'product_id' => $message['page_id'],
						'status >' => 2,
					]);
					$message['photo'] = $this->gm_db->get('products_photo', ['product_id' => $message['page_id'], 'is_main' => 1], 'row');
				}
				$data_messages[$message['tab']][] = $message;
			}
		}
		// debug($data_messages, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'orders/main', 'orders/messages']
			],
			'middle' => [
				'body_class' => ['dashboard', 'messages'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'orders/messages_container',
				],
			],
			'bottom' => [
				'modals' => ['reply_modal'],
				'js' => ['hideshow', 'plugins/readmore.min', 'transactions/messages', 'dashboard/main'],
			],
			'data' => [
				'messages' => $data_messages
			]
		]);
	}

	public function thankyou()
	{
		$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status >' => 1]);
		// debug($baskets, 'stop');
		if (!is_bool($baskets) AND count($baskets)) {
			$total = 0;
			$fees = [];
			foreach ($baskets as $key => $basket) {
				$price = $basket['quantity'] *  $basket['rawdata']['basket_details']['price'];
				$total += $price;
				$fees[$basket['location_id']] = $basket['fee'];
			}
			foreach ($fees as $location_id => $value) $total += (float)$value;
			// debug($total, $fees, 'stop');
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
				'data' => [
					'total' => $total
				]
			]);
		} else {
			redirect(base_url('transactions/'));
		}
	}

	public function comment()
	{
		$post = $this->input->post() ?: $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			$post['datestamp'] = strtotime(date('Y-m-d'));
			$id = $this->gm_db->new('messages', $post);
			if ($post['under'] == 0) {
				$post['id'] = $id;
			} else {
				$post['id'] = $post['under'];
			}

			$post['added'] = strtotime(date('Y-m-d H:i:s'));
			$post['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $post['user_id']], 'row');
			$post['product'] = $this->gm_db->get('products', ['id' => $post['page_id']], 'row');
			$post['product']['entity_id'] = $post['entity_id'];
			
			$html = $this->load->view('static/commented', $post, true);

			$post['html'] = $html;
			$this->set_response('success', false, $post, false, 'appendComment');
		}
		$this->set_response('error', 'Unable to post comment, try again later.', $post);
	}
}