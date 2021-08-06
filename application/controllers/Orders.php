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
		// $this->load->library('ToktokApi');
		// debug($this->toktokapi, 'stop');
	}

	public function index($status='placed')
	{
		$status_id = get_status_dbvalue($status);
		// debug($status_id, 'stop');
		$filters = ['buyer_id' => $this->accounts->profile['id'], 'status' => $status_id];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$filters['id'] = $this->input->post('ids');
			$filters['buyer_id'] = $this->input->post('buyer_id');
			// $filters['id'] = ["25", "28", "31", "41"];
		}
		$baskets_merge = setup_orders_data($this->baskets->get_baskets_merge($filters));
		// debug($baskets_merge, 'stop');
		if ($this->input->is_ajax_request()) {
			$total_items = 0;
			if (is_array($baskets_merge)) $total_items = count($baskets_merge);
			echo json_encode(['total_items' => $total_items, 'html' => $this->load->view('templates/orders/o_order_items', [
				'data' => [
					'orders' => $baskets_merge,
					'status' => $status,
					'counts' => [
						'placed' => count_by_status(['buyer_id' => $filters['buyer_id'], 'status' => 2]),
						'for+pick+up' => count_by_status(['buyer_id' => $filters['buyer_id'], 'status' => 6]),
						'on+delivery' => count_by_status(['buyer_id' => $filters['buyer_id'], 'status' => 3]),
						'received' => count_by_status(['buyer_id' => $filters['buyer_id'], 'status' => 4]),
						'cancelled' => count_by_status(['buyer_id' => $filters['buyer_id'], 'status' => 5]),
					],
					'no_rec_ui' => true,
				]
			], true)], JSON_NUMERIC_CHECK);
			exit();
		} else {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'global/order-table', 'orders/main', 'global/zigzag', 'modal/invoice-modal', 'print.min']
				],
				'middle' => [
					'body_class' => ['dashboard', 'orders-active', 'orders-'.$status],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'orders/o_container',
					],
				],
				'bottom' => [
					'modals' => ['ff_invoice_modal'],
					'js' => ['plugins/print.min', 'plugins/html2canvas.min', 'orders/main', 'orders/o-'.clean_string_name(urldecode($status))],
				],
				'data' => [
					'orders' => $baskets_merge,
					'status' => $status,
					'counts' => [
						'placed' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => 2]),
						'for+pick+up' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => 6]),
						'on+delivery' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => 3]),
						'received' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => 4]),
						'cancelled' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => 5]),
					],
				]
			]);
		}
	}

	public function messages()
	{
		$messages = $data_messages = false;
		if ($this->farms AND $this->products->count()) {
			$ids = $this->gm_db->columns('id', $this->products->get_in(['user_id' => $this->accounts->profile['id']]));
			$messages = $this->gm_db->get_or_in('messages', [
				'to_id' => $this->accounts->profile['id'],
				'page_id' => $ids,
				'order_by' => ['under', 'added'],
				'direction' => ['ASC', 'DESC'],
			]);
		} else {
			$messages = $this->gm_db->get_in('messages', [
				'to_id' => $this->accounts->profile['id'],
				'order_by' => ['under', 'added'],
				'direction' => ['ASC', 'DESC'],
			]);
		}
		// debug($messages, 'stop');
		if ($messages) {
			$data_messages = [];
			foreach ($messages as $key => $message) {
				if (in_array($message['unread'], [0,1])) {
					$message['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $message['from_id']], 'row');
					if ($message['profile'] == false) {
						$message['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $message['to_id']], 'row');
					}
					$message['farm'] = $this->gm_db->get('user_farms', ['user_id' => $message['from_id']], 'row');
					if ($message['farm'] == false) {
						$message['farm'] = $this->gm_db->get('user_farms', ['user_id' => $message['to_id']], 'row');
					}

					if ($message['tab'] == 'Feedbacks' AND $message['type'] == 'Comments') {
						$message['product'] = $this->gm_db->get('products', ['id' => $message['page_id']], 'row');
						$message['product']['photos'] = false;
						$photos = $this->gm_db->get('products_photo', ['product_id' => $message['page_id'], 'status' => 1]);
						if ($photos) {
							foreach ($photos as $key => $photo) {
								if ($photo['is_main']) {
									$message['product']['photos']['main'] = $photo;
									break;
								}
							}
							foreach ($photos as $key => $photo) {
								if (!$photo['is_main']) {
									$message['product']['photos']['other'][] = $photo;
								}
							}
						}
						$message['product']['farm_location_id'] = $message['entity_id'];
						$message['location'] = $this->gm_db->get('products_location', [
							'product_id' => $message['page_id'],
							'farm_location_id' => $message['entity_id']
						], 'row');
						$message['bought'] = $this->gm_db->count('baskets', [
							'user_id' => $message['to_id'],
							'product_id' => $message['page_id'],
							'status >' => 2,
						]);
						$message['photo'] = $this->gm_db->get('products_photo', ['product_id' => $message['page_id'], 'is_main' => 1], 'row');
						$data_messages[$message['tab']][($message['under'] ? 'replies' : 'first')][] = $message;
					} else {
						$data_messages[$message['tab']][] = $message;
					}
				}
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
		$order_ids = $this->session->userdata('typage_session');
		// debug(!empty($typage_session), 'stop');
		if (!empty($order_ids)) {
			$baskets_merge = $this->baskets->get_baskets_merge(['order_id' => $order_ids]);
			// debug($baskets_merge, 'stop');
			if ($baskets_merge) {
				$total = 0;
				foreach ($baskets_merge as $key => $merge) {
					$toktok_post = json_decode(base64_decode($merge['toktok_post']), true);
					$total += (float)$toktok_post['f_recepient_cod'];
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

			$post['added'] = date('Y-m-d H:i:s');
			$post['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $post['from_id']], 'row');
			if ($post['profile'] == false) {
				$post['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $post['to_id']], 'row');
			}
			$post['farm'] = $this->gm_db->get('user_farms', ['user_id' => $post['from_id']], 'row');
			if ($post['farm'] == false) {
				$post['farm'] = $this->gm_db->get('user_farms', ['user_id' => $post['to_id']], 'row');
			}
			$post['product'] = $this->gm_db->get('products', ['id' => $post['page_id']], 'row');
			$post['product']['entity_id'] = $post['entity_id'];
			
			$html = $this->load->view('static/commented', $post, true);

			$post['html'] = $html;
			$this->set_response('success', false, $post, false, 'appendComment');
		}
		$this->set_response('error', 'Unable to post comment, try again later.', $post);
	}

	public function delete($all=0)
	{
		$post = $this->input->post();
		$get = $this->input->get();
		if ($post AND isset($post['data'])) {
			// debug($post, 'stop');
			$basket_ids = $merge_ids = $seller_ids = [];
			foreach ($post['data'] as $key => $row) {
				$merge = $this->gm_db->get('baskets_merge', ['id' => $row['merge_id']], 'row');
				if ($merge) {
					$seller_ids[$merge['seller_id']] = $merge['seller_id'];
					$merge_ids[$merge['id']] = $merge['id'];
					$order_details = json_decode(base64_decode($merge['order_details']), true);
					if ($order_details) {
						/*modify the status of the product*/
						foreach ($order_details as $index => $detail) {
							if ($all == 0) {
								if ($detail['product_id'] == $row['product_id']) {
									$order_details[$index]['status'] = 5;
								}
							} else {
								$order_details[$index]['status'] = 5;
							}
						}
						// debug($order_details, 'stop');
						$this->gm_db->save('baskets_merge', 
							['order_details' => base64_encode(json_encode($order_details, JSON_NUMERIC_CHECK))], 
							['id' => $row['merge_id']]
						);
					}
					if ($all == 0) {
						$this->baskets->save([
							'status' => 5,
							'cancel_by' => $this->accounts->profile['id'],
							'reason' => 'Removed by buyer',
						], ['id' => $row['basket_id']]);
					} else {
						$basket_ids = explode(',', $merge['basket_ids']);
						if (count($basket_ids)) {
							foreach ($basket_ids as $basket_id) {
								$this->baskets->save([
									'status' => 5,
									'cancel_by' => $this->accounts->profile['id'],
									'reason' => 'Removed by buyer',
								], ['id' => $basket_id]);
							}
						}
					}
				}
			}
			$callback = 'removeOnOrder';
			if ($all) { /*do this when all items was deleted*/
				$callback = 'removeOnAllOrder';
				/*check here if all baskets have placed order status*/
				$basket_count = $this->baskets->count(['status' => 2, 'id' => $basket_ids, 'user_id' => $this->accounts->profile['id']]);
				if ($basket_count == 0) {
					/*now set all merged basket to cancelled*/
					foreach ($post['data'] as $key => $row) {
						$this->gm_db->save('baskets_merge', ['status' => 5], ['id' => $row['merge_id']]);
					}
				}
			}
			/*$senddata = $this->senddataapi->trigger('remove-fulfilled-items', 'remove-item', [
				'all' => $all, 
				'data' => $post['data'],
				'seller_id' => $seller_ids
			]);*/
			if (count($merge_ids)) {
				// send realtime placed order
				$this->senddataapi->trigger('placed-order', 'incoming-orders', [
					'success' => true, 'ids' => $merge_ids, 'buyer_id' => $this->accounts->profile['id'], 'event' => 'cancelled', 'remove' => 'placed'
				]);
				// send realtime cancelled order
				$this->senddataapi->trigger('cancelled-order', 'incoming-orders', [
					'success' => true, 'ids' => $merge_ids, 'buyer_id' => $this->accounts->profile['id'], 'event' => 'cancelled', 'remove' => false
				]);
				// send realtime placed fulfillment
				$this->senddataapi->trigger('placed-fulfillment', 'incoming-fulfillment', [
					'success' => true, 'ids' => $merge_ids, 'seller_id' => $seller_ids, 'event' => 'cancelled', 'remove' => 'placed'
				]);
				// send realtime cancelled fulfillment
				$this->senddataapi->trigger('cancelled-fulfillment', 'incoming-fulfillment', [
					'success' => true, 'ids' => $merge_ids, 'seller_id' => $seller_ids, 'event' => 'cancelled', 'remove' => false
				]);

				$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
					'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'order'
				]);
				$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
					'success' => true, 'id' => $seller_ids, 'nav' => 'fulfill'
				]);
			}

			// debug($senddata, 'stop');
			$this->set_response('success', 'Product removed on Order(s)', $post['data'], false, $callback);
		} elseif ($get AND isset($get['data'])) {
			// debug($get, 'stop');
			$callback = 'removeOrderItem';
			if ($all) $callback = 'removeAllOrderItem';
			$this->set_response('confirm', 'Want to remove product(s)?', $get['data'], false, $callback);
		}
		$this->set_response('error', remove_multi_space('Unable to remove product(s)', true), $post);
	}

	public function receive($id=0)
	{
		$get = $this->input->get();
		$post = $this->input->post();
		$data = [];
		// debug($id, $post, 'stop');
		if ($get AND $id > 0 AND (isset($get['confirm']) AND $get['confirm'] == 1)) {
			$data = $get;
			$merge = $this->gm_db->get('baskets_merge', ['id' => $id], 'row');
			if ($merge) {
				$this->set_response('confirm', 'Is this item(s) already received?', ['id' => $id], false, 'moveToReceiveOrders');
			}
		} elseif ($post AND $id == 0 AND (isset($post['todo']) AND $post['todo'] == 1)) {
			$data = $post;
			$merge = $this->gm_db->get('baskets_merge', ['id' => $post['data']['id']], 'row');
			debug($merge, 'stop');
			if ($merge) {
				$basket_ids = explode(',', $merge['basket_ids']);
				$this->gm_db->save('baskets_merge', ['status' => 4], ['id' => $post['data']['id']]);
				$this->gm_db->save('baskets', ['status' => 4], ['id' => $basket_ids]);
			}
		}
		$this->set_response(
			'error', 
			remove_multi_space('Something went wrong on changing item status, please try again.', true), 
			$data
		);
	}
}