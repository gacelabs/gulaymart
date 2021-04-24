<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends MY_Controller {

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

	public function orders()
	{
		$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status >' => 1]);
		if ($baskets) {
			foreach ($baskets as $key => $basket) $basket_ids[] = $basket['id'];
			$baskets_merge = $this->baskets->get_baskets_merge(['basket_ids' => implode(',', $basket_ids)]);
			foreach ($baskets_merge as $key => $merged) {
				$baskets_merge[$key]['seller'] = json_decode(base64_decode($baskets_merge[$key]['seller']), true);
				$baskets_merge[$key]['buyer'] = json_decode(base64_decode($baskets_merge[$key]['buyer']), true);
				$baskets_merge[$key]['order_details'] = json_decode(base64_decode($baskets_merge[$key]['order_details']), true);
				$baskets_merge[$key]['toktok_post'] = json_decode(base64_decode($baskets_merge[$key]['toktok_post']), true);
			}
			debug($baskets_merge, 'stop');
		}
		$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status >' => 1]);
		// debug($baskets, 'stop');
		$assembled = false;
		if ($baskets) {
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
		debug($assembled, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'transactions/orders', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/transactions_container',
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
				'css' => ['dashboard/main', 'transactions/main', 'transactions/messages']
			],
			'middle' => [
				'body_class' => ['dashboard', 'messages'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/messages_container',
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
		$typage_session = $this->session->userdata('typage_session');
		if (!$typage_session) {
			$this->session->unset_userdata('typage_session');
			$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status >' => 1]);
			if ($baskets) {
				$basket_ids = [];
				foreach ($baskets as $key => $basket) $basket_ids[] = $basket['id'];
				$baskets_merge = $this->baskets->get_baskets_merge(['basket_ids' => implode(',', $basket_ids)]);

				$total = 0;
				if ($baskets_merge) {
					foreach ($baskets_merge as $key => $merge) {
						$toktok_post = json_decode(base64_decode($merge['toktok_post']), true);
						$total += (float)$toktok_post['f_recepient_cod'];
					}
				}
				// debug($total, 'stop');
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
						'total' => (float)$total
					]
				]);
			}
		}
		$this->set_response('info', 'Orders already been Placed.', false, 'transactions/');
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