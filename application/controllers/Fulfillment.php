<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fulfillment extends My_Controller {

	public $allowed_methods = [];
	public $not_allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('baskets');
		// debug($this->products->count(), 'stop');
		if (empty($this->farms) AND $this->products->count() == 0) {
			redirect(base_url('basket/'));
		}
		$this->load->library('farmers');
		// INITIALIZING TOKTOK OBJECT
		// $this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');
	}

	public function index($status='placed')
	{
		$status_id = get_status_dbvalue($status);
		// debug($status_id, 'stop');
		$baskets_merge = $this->baskets->get_baskets_merge(['seller_id' => $this->accounts->profile['id'], 'status' => $status_id]);
		// debug($baskets_merge, 'stop');
		if ($baskets_merge) {
			foreach ($baskets_merge as $key => $merged) {
				$baskets_merge[$key]['seller'] = json_decode(base64_decode($baskets_merge[$key]['seller']), true);
				$baskets_merge[$key]['buyer'] = json_decode(base64_decode($baskets_merge[$key]['buyer']), true);
				$baskets_merge[$key]['order_details'] = json_decode(base64_decode($baskets_merge[$key]['order_details']), true);
				foreach ($baskets_merge[$key]['order_details'] as $index => $details) {
					// $baskets_merge[$key]['order_details'][$index]['status'] = 2;
					if (!isset($baskets_merge[$key]['order_type'])) {
						$baskets_merge[$key]['order_type'] = $details['when'];
						$baskets_merge[$key]['schedule'] = '';
						if ($details['when'] == 2) {
							$baskets_merge[$key]['schedule'] = date('F j, Y', strtotime($details['schedule']));
						}
					}
					$basket = $this->gm_db->get('baskets', ['id' => $details['basket_id']], 'row');
					$baskets_merge[$key]['order_details'][$index]['cancel_by'] = '';
					$baskets_merge[$key]['order_details'][$index]['reason'] = '';
					if ($basket) {
						$baskets_merge[$key]['order_details'][$index]['cancel_by'] = $basket['cancel_by'];
						$baskets_merge[$key]['order_details'][$index]['reason'] = $basket['reason'];
					}
				}
				$baskets_merge[$key]['toktok_post'] = json_decode(base64_decode($baskets_merge[$key]['toktok_post']), true);
			}
		}
		// debug($baskets_merge, 'stop');
		$farm = $this->farmers->get(['user_id' => $this->accounts->profile['id']], true);
		// debug($farm, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table', 'print.min']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-'.$status],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['plugins/print.min', 'plugins/html2canvas.min', 'fulfillment/main', 'fulfillment/ff-'.clean_string_name(urldecode($status))],
			],
			'data' => [
				'farm' => $farm,
				'orders' => $baskets_merge,
				'status' => $status,
				'counts' => [
					'placed' => count_by_status(['seller_id' => $this->accounts->profile['id'], 'status' => 2]),
					'for+pick+up' => count_by_status(['seller_id' => $this->accounts->profile['id'], 'status' => 6]),
					'on+delivery' => count_by_status(['seller_id' => $this->accounts->profile['id'], 'status' => 3]),
					'received' => count_by_status(['seller_id' => $this->accounts->profile['id'], 'status' => 4]),
					'cancelled' => count_by_status(['seller_id' => $this->accounts->profile['id'], 'status' => 5]),
				],
			]
		]);
	}

	public function change_status($all=0)
	{
		$post = $this->input->post() ?: $this->input->get();
		// debug($post, 'stop');
		if ($post AND isset($post['data'])) {
			$row = $post['data'];
			$merge = $this->gm_db->get('baskets_merge', ['id' => $row['merge_id']], 'row');
			if ($merge) {
				$order_details = json_decode(base64_decode($merge['order_details']), true);
				if ($order_details) {
					/*modify the status of the product*/
					foreach ($order_details as $index => $detail) {
						if ($detail['product_id'] == $row['product_id']) {
							$order_details[$index]['status'] = $row['status'];
						}
					}
					// debug($order_details, 'stop');
					$this->gm_db->save('baskets_merge', 
						['order_details' => base64_encode(json_encode($order_details))], 
						['id' => $row['merge_id']]
					);
				}

				$basket = $this->gm_db->get('baskets', ['id' => $row['basket_id']], 'row');
				// debug($basket, 'stop');
				if ($basket) {
					if ($basket['product_id'] == $row['product_id']) {
						$this->baskets->save([
							'status' => $row['status'],
							'cancel_by' => $this->accounts->profile['id'],
							'reason' => $row['reason'],
						], ['id' => $row['basket_id']]);
					}
				}

				$response = $this->senddataapi->trigger('change-order-status', 'ordered-items', ['data'=>$post['data']]);
				// debug($response, 'stop');
				$this->set_response('success', 'Product status on Order(s) changed', $post['data'], false, 'changeOnFulfillment');
			}
		}
		$this->set_response('error', remove_multi_space('Unable to change product(s) status'), $post);
	}

	public function ready()
	{
		$post = $this->input->post() ?: $this->input->get();
		// debug($post, 'stop');
		if ($post AND isset($post['merge_id']) AND isset($post['data'])) {
			$cancelled = [];

			$merge = $this->gm_db->get('baskets_merge', ['id' => $post['merge_id']], 'row');
			if ($merge) {
				$order_details = json_decode(base64_decode($merge['order_details']), true);
				if ($order_details) {
					/*modify the status of the product*/
					foreach ($post['data'] as $key => $row) {
						if ($row['status'] == 5) $cancelled[] = $row['basket_id'];

						foreach ($order_details as $index => $detail) {
							if ($detail['product_id'] == $row['product_id']) {
								$order_details[$index]['status'] = $row['status'];
							}
						}
						$basket = $this->gm_db->get('baskets', ['id' => $row['basket_id']], 'row');
						// debug($basket, 'stop');
						if ($basket) {
							if ($basket['product_id'] == $row['product_id']) {
								$this->baskets->save([
									'status' => $row['status'],
									'cancel_by' => ($row['status'] == 5) ? $this->accounts->profile['id'] : 0,
									'reason' => $row['reason'],
								], ['id' => $row['basket_id']]);
							}
						}
					}
					// debug($order_details, 'stop');
					$this->gm_db->save('baskets_merge', 
						['order_details' => base64_encode(json_encode($order_details))], 
						['id' => $post['merge_id']]
					);
				}

				$status_value = 6;
				if (count($post['data']) == count($cancelled)) $status_value = 5;

				$count = $this->gm_db->count('baskets_merge', ['id' => $post['merge_id'], 'status' => $status_value]);
				if ($count == 0) {
					// set status for pick-up this will now also send to toktok post delivery
					$this->gm_db->save('baskets_merge', ['status' => $status_value], ['id' => $post['merge_id']]);
					
					/*send it realtime to buyer*/
					$response = $this->senddataapi->trigger('change-order-status', 'ordered-items', ['data'=>$post]);

					$redirect = 'fulfillment/for-pick-up';
					$action = 'Ready for Pick Up';
					if ($status_value == 5) {
						$redirect = 'fulfillment/cancelled';
						$action = 'Cancelled';
					}

					$buyer = json_decode(base64_decode($merge['buyer']), true);
					$seller = json_decode(base64_decode($merge['seller']), true);
					notify_invoice_orders($merge, $buyer, [$seller['user_id']], $action, str_replace(' ', '-', urldecode(get_status_value($status_value))));

					$this->set_response('success', 'Order is now Set For Pick Up!', $post, $redirect);
				} else {
					$this->set_response('info', 'Order Already set For Pick Up!', $post, false);
				}
			}
		}
		$this->set_response('error', remove_multi_space('Unable to set Order for pick up'), $post);
	}

}