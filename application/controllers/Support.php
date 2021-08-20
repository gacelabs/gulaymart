<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = ['order_details', 'thankyou_page', 'view_invoice', 'terms', 'policy', 'fetch_order_cycles'];

	/*public function index()
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
	}*/

	public function view_invoice($order_id=false)
	{
		if ($order_id) {
			$results = $this->gm_db->get_in('baskets_merge', ['order_id' => $order_id], 'row');
			// debug($results, 'stop');
			if ($results) {
				$results['for_email'] = true;
				$this->render_page([
					'top' => [
						'css' => ['modal/invoice-modal', 'global/zigzag', 'global/order-table', 'print.min']
					],
					'middle' => [
						'body' => [
							'../static/invoice_middle_body'
						],
					],
					'bottom' => [
						'js' => ['plugins/print.min', 'plugins/html2canvas.min'],
					],
					'data' => $results,
				]);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
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
				if ($post['for'] == 'seller') {
					$results['data'] = json_decode(base64_decode($results['seller']), true);
				}
				if ($post['for'] == 'buyer') {
					$results['data'] = json_decode(base64_decode($results['buyer']), true);
					$results['data']['name'] = $results['data']['fullname'];
				}
				if (isset($results['data']) AND (isset($results['data']['name']) AND strlen(trim($results['data']['name'])) == 0)) {
					$results['data'] = ['name' => 'There'];
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

	public function fetch_order_cycles()
	{
		$post = $this->input->get() ?: $this->input->post();
		$data = false;
		if ($post) {
			$stat_qry = [
				'fulfillment' => [GM_RECEIVED_STATUS, GM_CANCELLED_STATUS],
				'baskets' => [GM_VERIFIED_SCHED, GM_VERIFIED_NOW],
				'orders' => [GM_RECEIVED_STATUS, GM_CANCELLED_STATUS]
			];
			foreach ($post as $what_id => $ids) {
				switch (trim(strtolower($what_id))) {
					case 'basket_id':
						$datatable = $this->gm_db->get_in('baskets', ['id' => $ids]);
						$user_ids = [];
						if ($datatable) {
							foreach ($datatable as $row) $user_ids[] = $row['user_id'];
						}
						$user_ids = array_unique($user_ids);
						$data = [
							'mode' => 'basket',
							'post' => $post,
							'params' => ['ids' => $ids, 'user_id' => $user_ids],
							'url' => [base_url('basket')],
							'id' => is_array($user_ids) ? $user_ids : [$user_ids],
							'counts' => [
								'baskets' => $this->gm_db->count('baskets', ['user_id' => $user_ids, 'status' => $stat_qry['baskets']]),
								'messages' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'to_id' => $user_ids]),
								'notifications' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Notifications', 'to_id' => $user_ids]),
								'feedbacks' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Feedbacks', 'to_id' => $user_ids]),
							]
						];
					break;
					case 'message_id':
						$datatable = $this->gm_db->get_in('messages', ['id' => $ids]);
						$user_ids = [];
						if ($datatable) {
							foreach ($datatable as $row) $user_ids[] = $row['to_id'];
						}
						$user_ids = array_unique($user_ids);
						$data = [
							'mode' => 'message',
							'post' => $post,
							'id' => is_array($user_ids) ? $user_ids : [$user_ids],
							'url' => [base_url('orders/messages')],
							'params' => ['ids' => $ids],
							'counts' => [
								'messages' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'to_id' => $user_ids]),
								'notifications' => $this->gm_db->count('messages', ['unread'=>1, 'tab'=>'Notifications', 'to_id'=>$user_ids]),
								'feedbacks' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Feedbacks', 'to_id' => $user_ids]),
							],
						];
					break;
					case 'merge_id':
						$datatable = $this->gm_db->get_in('baskets_merge', ['id' => $ids]);
						// $datatable = $this->gm_db->get_in('baskets_merge');
						if ($datatable) {
							/*sort by status*/
							$data = $by_status = [];
							foreach ($datatable as $key => $row) {
								$by_status[$row['status']]['ids'][] = $row['id'];
								$by_status[$row['status']]['seller_id'][] = $row['seller_id'];
								$by_status[$row['status']]['buyer_id'][] = $row['buyer_id'];
								$by_status[$row['status']]['basket_ids'] = explode(',', $row['basket_ids']);
							}
							$results = false;
							if ($by_status) {
								foreach ($by_status as $status => $row) {
									// $ids = array_unique($row['ids']);
									$post[trim(strtolower($what_id))] = $ids;
									$seller_ids = array_unique($row['seller_id']);
									$buyer_ids = array_unique($row['buyer_id']);
									$basket_ids = array_unique($row['basket_ids']);
									switch ($status) {
										case GM_PLACED_STATUS:
											$mode = 'placed';
										break;
										case GM_FOR_PICK_UP_STATUS:
											$mode = 'for-pick-up';
										break;
										case GM_ON_DELIVERY_STATUS:
											$mode = 'on-delivery';
										break;
										case GM_RECEIVED_STATUS:
											$mode = 'received';
										break;
										case GM_CANCELLED_STATUS:
											$mode = 'cancelled';
										break;
									}
									if (isset($mode)) {
										foreach ($seller_ids as $key => $seller_id) {
											$counts['seller'] = [
												'user_'.$seller_id => [
													'fulfillment' => $this->gm_db->count_not_in('baskets_merge', ['seller_id' => $seller_id, 'status' => $stat_qry['fulfillment']]),
													'baskets' => $this->gm_db->count('baskets', ['user_id' => $seller_id, 'status' => $stat_qry['baskets']]),
													'orders' => $this->gm_db->count_not_in('baskets_merge', ['buyer_id' => $seller_id, 'status' => $stat_qry['orders']]),
													'messages' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'to_id' => $seller_id]),
													'placed' => $this->gm_db->count('baskets_merge', ['seller_id' => $seller_id, 'status' => GM_PLACED_STATUS]),
													'for-pick-up' => $this->gm_db->count('baskets_merge', ['seller_id' => $seller_id, 'status' => GM_FOR_PICK_UP_STATUS]),
													'on-delivery' => $this->gm_db->count('baskets_merge', ['seller_id' => $seller_id, 'status' => GM_ON_DELIVERY_STATUS]),
													'received' => $this->gm_db->count('baskets_merge', ['seller_id' => $seller_id, 'status' => GM_RECEIVED_STATUS]),
													'cancelled' => $this->gm_db->count('baskets_merge', ['seller_id' => $seller_id, 'status' => GM_CANCELLED_STATUS]),
													'notifications' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Notifications', 'to_id' => $seller_id]),
													'feedbacks' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Feedbacks', 'to_id' => $seller_id]),
												]
											];
											$seller_requests['user_'.$seller_id] = [
												'fulfillment' => [
													'url' => base_url('fulfillment/'.$mode),
													'params' => ['ids' => $row['ids'], 'seller_id' => $seller_id]
												],
												'basket' => [
													'url' => base_url('basket'),
													'params' => ['ids' => $basket_ids, 'user_id' => $seller_id]
												],
												'orders' => [
													'url' => base_url('orders/'.$mode),
													'params' => ['ids' => $row['ids'], 'buyer_id' => $seller_id]
												],
												'messages' => [
													'url' => base_url('orders/messages'),
													'params' => ['user_id' => $seller_id]
												],
											];
										}
										foreach ($buyer_ids as $key => $buyer_id) {
											$counts['buyer'] = [
												'user_'.$buyer_id => [
													'fulfillment' => $this->gm_db->count_not_in('baskets_merge', ['seller_id' => $buyer_id, 'status' => $stat_qry['fulfillment']]),
													'baskets' => $this->gm_db->count('baskets', ['user_id' => $buyer_id, 'status' => $stat_qry['baskets']]),
													'orders' => $this->gm_db->count_not_in('baskets_merge', ['buyer_id' => $buyer_id, 'status' => $stat_qry['orders']]),
													'messages' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'to_id' => $buyer_id]),
													'placed' => $this->gm_db->count('baskets_merge', ['buyer_id' => $buyer_id, 'status' => GM_PLACED_STATUS]),
													'for-pick-up' => $this->gm_db->count('baskets_merge', ['buyer_id' => $buyer_id, 'status' => GM_FOR_PICK_UP_STATUS]),
													'on-delivery' => $this->gm_db->count('baskets_merge', ['buyer_id' => $buyer_id, 'status' => GM_ON_DELIVERY_STATUS]),
													'received' => $this->gm_db->count('baskets_merge', ['buyer_id' => $buyer_id, 'status' => GM_RECEIVED_STATUS]),
													'cancelled' => $this->gm_db->count('baskets_merge', ['buyer_id' => $buyer_id, 'status' => GM_CANCELLED_STATUS]),
													'notifications' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Notifications', 'to_id' => $buyer_id]),
													'feedbacks' => $this->gm_db->count('messages', ['unread' => GM_MESSAGE_UNREAD, 'tab' => 'Feedbacks', 'to_id' => $buyer_id]),
												],
											];
											$buyer_requests['user_'.$buyer_id] = [
												'fulfillment' => [
													'url' => base_url('fulfillment/'.$mode),
													'params' => ['ids' => $row['ids'], 'seller_id' => $buyer_id]
												],
												'basket' => [
													'url' => base_url('basket'),
													'params' => ['ids' => $basket_ids, 'user_id' => $buyer_id]
												],
												'orders' => [
													'url' => base_url('orders/'.$mode),
													'params' => ['ids' => $row['ids'], 'buyer_id' => $buyer_id]
												],
												'messages' => [
													'url' => base_url('orders/messages'),
													'params' => ['user_id' => $buyer_id]
												],
											];
										}
										if (isset($counts) AND isset($seller_requests) AND isset($buyer_requests)) {
											$results = [
												'mode' => $mode,
												'post' => $post,
												'requests' => [
													'seller' => $seller_requests,
													'buyer' => $buyer_requests,
												],
												'seller' => $seller_ids,
												'buyer' => $buyer_ids,
												'basket_ids' => $basket_ids,
												'counts' => $counts,
											];
										}
									}
								}
							}
							$data = $results;
						}
					break;
				}
			}
		}
		// debug($data, 'stop');
		echo clean_json_encode($data); exit();
	}
}