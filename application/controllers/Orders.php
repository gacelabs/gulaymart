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
		$ids = [];
		$filters = ['buyer_id' => $this->accounts->profile['id'], 'status' => $status_id];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$ids = is_array($this->input->post('ids')) ? array_values($this->input->post('ids')) : $ids;
			$filters['id'] = $this->input->post('ids');
			$filters['buyer_id'] = $this->input->post('buyer_id');
		}
		$baskets_merge = setup_orders_data($this->baskets->get_baskets_merge($filters));
		// debug($baskets_merge, 'stop');
		if ($this->input->is_ajax_request()) {
			$htmls = [];
			if ($baskets_merge) {
				foreach ($baskets_merge as $key => $merge) {
					$htmls[$merge['id']] = $this->load->view('templates/orders/o_order_items', [
						'orders' => $merge,
						'status' => $status,
						'status_id' => $status_id,
					], true);
				}
			}
			// debug($htmls, 'stop');
			echo json_encode(['html' => $htmls, 'merge_ids' => $ids, 'panel' => 'orders'], JSON_NUMERIC_CHECK);
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
					'status_id' => $status_id,
					'counts' => [
						'placed' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => GM_PLACED_STATUS]),
						'for+pick+up' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => GM_FOR_PICK_UP_STATUS]),
						'on+delivery' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => GM_ON_DELIVERY_STATUS]),
						'received' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => GM_RECEIVED_STATUS]),
						'cancelled' => count_by_status(['buyer_id' => $this->accounts->profile['id'], 'status' => GM_CANCELLED_STATUS]),
					],
				]
			]);
		}
	}

	public function messages()
	{
		$data_messages = false; $filters = [];
		if ($this->input->is_ajax_request() AND $this->input->post()) {
			if ($this->input->post('ids')) {
				$filters['id'] = $this->input->post('ids');
			} elseif ($this->input->post('user_id')) {
				$filters['to_id'] = $this->input->post('user_id');
			}
		}
		if (count($filters)) {
			$filters['unread'] = [GM_MESSAGE_READ, GM_MESSAGE_UNREAD];
			$filters['order_by'] = ['under', 'added'];
			$filters['direction'] = ['ASC', 'DESC'];
		} else {
			$filters = [
				'to_id' => $this->accounts->profile['id'],
				'unread' => [GM_MESSAGE_READ, GM_MESSAGE_UNREAD],
				'order_by' => ['under', 'added'],
				'direction' => ['ASC', 'DESC'],
			];
		}
		$messages = $this->gm_db->get_in('messages', $filters);
		// debug($messages, 'stop');
		$message_ids = [];
		if ($messages) {
			$data_messages = []; 
			foreach ($messages as $key => $message) {
				if ($message['under'] == 0) { /*to_id is the farmer*/
					$message['is_buyer'] = 1;
					$message['farm'] = $this->gm_db->get('user_farms', ['user_id' => $message['to_id']], 'row');
					$message['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $message['from_id']], 'row');
				} else { /*to_id is the profile*/
					$message['is_buyer'] = 0;
					$message['farm'] = $this->gm_db->get('user_farms', ['user_id' => $message['from_id']], 'row');
					$message['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $message['to_id']], 'row');
				}
				$message['product'] = $message['location'] = $message['bought'] = $message['photo'] = [];
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
				}
				$message['reply'] = false;
				$replies = $this->gm_db->get('messages', ['under' => $message['id']]);
				if ($replies) {
					foreach ($replies as $index => $reply) {
						if ($reply['under'] == $message['id']) {
							$reply['is_buyer'] = 0;
							$reply['farm'] = $this->gm_db->get('user_farms', ['user_id' => $reply['from_id']], 'row');
							$reply['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $reply['to_id']], 'row');

							$reply['product'] = $reply['location'] = $reply['bought'] = $reply['photo'] = [];

							if ($reply['tab'] == 'Feedbacks' AND $reply['type'] == 'Comments') {
								$reply['product'] = $this->gm_db->get('products', ['id' => $reply['page_id']], 'row');
								$reply['product']['photos'] = false;
								$photos = $this->gm_db->get('products_photo', ['product_id' => $reply['page_id'], 'status' => 1]);
								if ($photos) {
									foreach ($photos as $key => $photo) {
										if ($photo['is_main']) {
											$reply['product']['photos']['main'] = $photo;
											break;
										}
									}
									foreach ($photos as $key => $photo) {
										if (!$photo['is_main']) {
											$reply['product']['photos']['other'][] = $photo;
										}
									}
								}
								$reply['product']['farm_location_id'] = $reply['entity_id'];
								$reply['location'] = $this->gm_db->get('products_location', [
									'product_id' => $reply['page_id'],
									'farm_location_id' => $reply['entity_id']
								], 'row');
								$reply['bought'] = $this->gm_db->count('baskets', [
									'user_id' => $reply['to_id'],
									'product_id' => $reply['page_id'],
									'status >' => 2,
								]);
								$reply['photo'] = $this->gm_db->get('products_photo', ['product_id' => $reply['page_id'], 'is_main' => 1], 'row');
							}
							$message['reply'] = $reply;
							break;
						}
					}
				}
				$data_messages[$message['tab']][] = $message;
				$message_ids[] = $message['id'];
			}
		}

		// debug($data_messages, 'stop');
		if ($this->input->is_ajax_request()) {
			$htmls = $tabs = [];
			if (!empty($data_messages)) {
				foreach ($data_messages as $tab => $msgs) {
					foreach ($msgs as $key => $msg) {
						$tabs[$msg['id']] = trim(strtolower($tab));
						$htmls[$msg['id']] = $this->load->view('templates/orders/'.strtolower($tab), [strtolower($tab) => $msg], true);
					}
				}
			}
			// debug($htmls, 'stop');
			if (count($message_ids)) $message_ids = array_unique($message_ids);
			echo json_encode(['html' => $htmls, 'tabs' => $tabs, 'panel' => 'messages', 'message_ids' => $message_ids], JSON_NUMERIC_CHECK);
			exit();
		} else {
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
				$over = $this->gm_db->get_in('messages', ['id' => $post['id']], 'row');
				if ($over) {
					$this->gm_db->save('messages', ['unread' => 0], ['id' => $post['id']]);
				}
			}

			$post['added'] = date('Y-m-d H:i:s');

			if ($post['under'] == 0) { /*to_id is the farmer*/
				$post['is_buyer'] = 1;
				$post['buyer_id'] = $post['from_id'];
				$post['seller_id'] = $post['to_id'];
				$post['farm'] = $this->gm_db->get('user_farms', ['user_id' => $post['to_id']], 'row');
				$post['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $post['from_id']], 'row');
			} else { /*to_id is the profile*/
				$post['is_buyer'] = 0;
				$post['buyer_id'] = $post['to_id'];
				$post['seller_id'] = $post['from_id'];
				$post['farm'] = $this->gm_db->get('user_farms', ['user_id' => $post['from_id']], 'row');
				$post['profile'] = $this->gm_db->get('user_profiles', ['user_id' => $post['to_id']], 'row');
			}

			$post['product'] = $this->gm_db->get('products', ['id' => $post['page_id']], 'row');
			$post['product']['entity_id'] = $post['entity_id'];
			$post['html'] = $this->load->view('static/commented', $post, true);

			$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['message_id' => $post['id']]);

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
					$seller_ids[] = $merge['seller_id'];
					$merge_ids[] = $merge['id'];
					$order_details = json_decode(base64_decode($merge['order_details']), true);
					if ($order_details) {
						/*modify the status of the product*/
						foreach ($order_details as $index => $detail) {
							if ($all == 0) {
								if ($detail['product_id'] == $row['product_id']) {
									$order_details[$index]['status'] = GM_CANCELLED_STATUS;
								}
							} else {
								$order_details[$index]['status'] = GM_CANCELLED_STATUS;
							}
						}
						// debug($order_details, 'stop');
						$this->gm_db->save('baskets_merge', 
							['order_details' => base64_encode(json_encode($order_details, JSON_NUMERIC_CHECK))], 
							['id' => $row['merge_id']]
						);
					}
					$basket_ids = explode(',', $merge['basket_ids']);
					if ($all == 0) {
						$this->baskets->save([
							'status' => GM_CANCELLED_STATUS,
							'cancel_by' => $this->accounts->profile['id'],
							'reason' => 'Removed by buyer',
						], ['id' => $row['basket_id']]);
					} else {
						if (count($basket_ids)) {
							foreach ($basket_ids as $basket_id) {
								$this->baskets->save([
									'status' => GM_CANCELLED_STATUS,
									'cancel_by' => $this->accounts->profile['id'],
									'reason' => 'Removed by buyer',
								], ['id' => $basket_id]);
							}
						}
					}
				}
			}
			
			/*check here if all baskets have placed order status*/
			$basket_count = $this->gm_db->count('baskets', ['status' => GM_PLACED_STATUS, 'id' => $basket_ids]);
			// debug($basket_count, 'stop');
			if ($basket_count == 0) {
				/*now set all merged basket to cancelled*/
				foreach ($merge_ids as $key => $merge_id) {
					$this->gm_db->save('baskets_merge', ['status' => GM_CANCELLED_STATUS], ['id' => $merge_id]);
				}
			}

			if (count($merge_ids)) {
				$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge_ids]);
			}

			$this->set_response('success', 'Product removed on Order(s)', $post['data']);
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