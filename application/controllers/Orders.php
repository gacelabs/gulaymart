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

	public function index($status='placed')
	{
		$status_id = get_status_dbvalue($status);
		// debug($status_id, 'stop');
		if ($status_id > 0) {
			$baskets_merge = false;
			$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status' => $status_id]);
			if ($baskets) {
				foreach ($baskets as $key => $basket) $basket_ids[] = $basket['id'];
				$baskets_merge = $this->baskets->get_baskets_merge(['basket_ids' => implode(',', $basket_ids)]);
				foreach ($baskets_merge as $key => $merged) {
					$baskets_merge[$key]['seller'] = json_decode(base64_decode($baskets_merge[$key]['seller']), true);
					$baskets_merge[$key]['buyer'] = json_decode(base64_decode($baskets_merge[$key]['buyer']), true);
					$baskets_merge[$key]['order_details'] = json_decode(base64_decode($baskets_merge[$key]['order_details']), true);
					$baskets_merge[$key]['toktok_post'] = json_decode(base64_decode($baskets_merge[$key]['toktok_post']), true);
				}
			}
			// debug($baskets_merge, 'stop');
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'global/order-table', 'orders/main']
				],
				'middle' => [
					'body_class' => ['dashboard', 'orders-active', 'orders-'.$status],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'orders/container',
					],
				],
				'bottom' => [
					'js' => ['orders/main'],
				],
				'data' => [
					'orders' => $baskets_merge,
					'status' => $status,
					'counts' => [
						'placed' => order_count_by_status(2, $this->accounts->profile['id']),
						'on+delivery' => order_count_by_status(3, $this->accounts->profile['id']),
						'received' => order_count_by_status(4, $this->accounts->profile['id']),
						'cancelled' => order_count_by_status(5, $this->accounts->profile['id']),
					],
				]
			]);
		} else {
			show_404();
		}
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
					'orders/container',
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
					'orders/container',
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
					'orders/container',
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
				'js' => ['hideshow', 'plugins/readmore.min', 'orders/messages', 'dashboard/main'],
			],
			'data' => [
				'messages' => $data_messages
			]
		]);
	}

	public function thankyou()
	{
		$typage_session = $this->session->userdata('typage_session');
		// debug(!empty($typage_session), 'stop');
		if (!empty($typage_session)) {
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
				$this->session->unset_userdata('typage_session');
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
			}
		} else {
			$this->set_response('info', 'Orders already been Placed.', [], 'orders/placed');
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