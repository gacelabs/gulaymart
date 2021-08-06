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
		// $this->load->library('ToktokApi');
		// debug($this->toktokapi, 'stop');
	}

	public function index($status='placed')
	{
		$status_id = get_status_dbvalue($status);
		// debug($status_id, 'stop');
		$filters = ['seller_id' => $this->accounts->profile['id'], 'status' => $status_id];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$filters['id'] = $this->input->post('ids');
			$filters['seller_id'] = $this->input->post('seller_id');
			// $filters['id'] = ["25", "28", "31", "41"];
		}
		$baskets_merge = setup_fulfillments_data($this->baskets->get_baskets_merge($filters));
		// debug($baskets_merge, 'stop');
		$farm = $this->farmers->get(['user_id' => $this->accounts->profile['id']], true);
		// debug($farm, 'stop');
		if ($this->input->is_ajax_request()) {
				$total_items = 0;
				if (is_array($baskets_merge)) $total_items = count($baskets_merge);
				echo json_encode(['total_items' => $total_items, 'html' => $this->load->view('templates/fulfillment/ff_product_container', [
				'data' => [
					'farm' => $farm,
					'orders' => $baskets_merge,
					'status' => $status,
					'counts' => [
						'placed' => count_by_status(['seller_id' => $filters['seller_id'], 'status' => 2]),
						'for+pick+up' => count_by_status(['seller_id' => $filters['seller_id'], 'status' => 6]),
						'on+delivery' => count_by_status(['seller_id' => $filters['seller_id'], 'status' => 3]),
						'received' => count_by_status(['seller_id' => $filters['seller_id'], 'status' => 4]),
						'cancelled' => count_by_status(['seller_id' => $filters['seller_id'], 'status' => 5]),
					],
					'no_rec_ui' => true,
				]
			], true)], JSON_NUMERIC_CHECK);
			exit();
		} else {
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
						['order_details' => base64_encode(json_encode($order_details, JSON_NUMERIC_CHECK))], 
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
				/*send it realtime to buyer*/
				/*$response = $this->senddataapi->trigger('status-ordered-items', 'change-order-status', [
					'data' => $post['data'],
					'buyer_id' => $merge['buyer_id']
				]);*/
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
									'cancel_by' => ($row['status'] == GM_CANCELLED_STATUS) ? $this->accounts->profile['id'] : 0,
									'reason' => $row['reason'],
								], ['id' => $row['basket_id']]);
							}
						}
					}
					// debug($order_details, 'stop');
					$this->gm_db->save('baskets_merge', 
						['order_details' => base64_encode(json_encode($order_details, JSON_NUMERIC_CHECK))], 
						['id' => $post['merge_id']]
					);
				}

				$status_value = GM_FOR_PICK_UP_STATUS;
				if (count($post['data']) == count($cancelled)) $status_value = GM_CANCELLED_STATUS;

				$count = $this->gm_db->count('baskets_merge', ['id' => $post['merge_id'], 'status' => $status_value]);
				if ($count == 0) {
					// set status for pick-up this will now also send to toktok post delivery
					$this->gm_db->save('baskets_merge', ['status' => $status_value], ['id' => $post['merge_id']]);
					/*send it realtime to buyer*/
					/*$response = $this->senddataapi->trigger('status-ordered-items', 'change-order-status', [
						'data' => $post,
						'buyer_id' => $merge['buyer_id']
					]);*/
					$redirect = 'fulfillment/for-pick-up';
					$action = 'Ready for Pick Up';
					if ($status_value == GM_CANCELLED_STATUS) {
						$redirect = 'fulfillment/cancelled';
						$action = 'Cancelled';
					}

					switch ($status_value) {
						case GM_FOR_PICK_UP_STATUS:
							// send realtime placed order
							$this->senddataapi->trigger('placed-order', 'incoming-orders', [
								'success' => true, 'ids' => $merge['id'], 'buyer_id' => $merge['buyer_id'], 'event' => 'for-pick-up', 'remove' => 'placed'
							]);
							// send realtime for-pick-up order
							$this->senddataapi->trigger('for-pick-up-order', 'incoming-orders', [
								'success' => true, 'ids' => $merge['id'], 'buyer_id' => $merge['buyer_id'], 'event' => 'for-pick-up', 'remove' => false
							]);
							// send realtime placed fulfillment
							$this->senddataapi->trigger('placed-fulfillment', 'incoming-fulfillment', [
								'success' => true, 'ids' => $merge['id'], 'seller_id' => $this->accounts->profile['id'], 'event' => 'for-pick-up', 'remove' => 'placed'
							]);
							// send realtime for-pick-up fulfillment
							$this->senddataapi->trigger('for-pick-up-fulfillment', 'incoming-fulfillment', [
								'success' => true, 'ids' => $merge['id'], 'seller_id' => $this->accounts->profile['id'], 'event' => 'for-pick-up', 'remove' => false
							]);

							$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
								'success' => true, 'id' => $merge['buyer_id'], 'menu' => 'orders', 'tab' => 'for-pick-up'
							]);
							$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
								'success' => true, 'id' => $this->accounts->profile['id'], 'menu' => 'fulfillments', 'tab' => 'for-pick-up'
							]);
							break;
						case GM_CANCELLED_STATUS:
							// send realtime placed order
							$this->senddataapi->trigger('placed-order', 'incoming-orders', [
								'success' => true, 'ids' => $merge['id'], 'buyer_id' => $merge['buyer_id'], 'event' => 'cancelled', 'remove' => 'placed'
							]);
							// send realtime cancelled order
							$this->senddataapi->trigger('cancelled-order', 'incoming-orders', [
								'success' => true, 'ids' => $merge['id'], 'buyer_id' => $merge['buyer_id'], 'event' => 'cancelled', 'remove' => false
							]);
							// send realtime placed fulfillment
							$this->senddataapi->trigger('placed-fulfillment', 'incoming-fulfillment', [
								'success' => true, 'ids' => $merge['id'], 'seller_id' => $this->accounts->profile['id'], 'event' => 'cancelled', 'remove' => 'placed'
							]);
							// send realtime cancelled fulfillment
							$this->senddataapi->trigger('cancelled-fulfillment', 'incoming-fulfillment', [
								'success' => true, 'ids' => $merge['id'], 'seller_id' => $this->accounts->profile['id'], 'event' => 'cancelled', 'remove' => false
							]);

							$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
								'success' => true, 'id' => $merge['buyer_id'], 'menu' => 'orders', 'tab' => 'cancelled'
							]);
							$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
								'success' => true, 'id' => $this->accounts->profile['id'], 'menu' => 'fulfillments', 'tab' => 'cancelled'
							]);
							break;
					}

					$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
						'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'order'
					]);
					$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
						'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'fulfill'
					]);
					$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
						'success' => true, 'id' => $merge['buyer_id'], 'nav' => 'order'
					]);
					$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
						'success' => true, 'id' => $merge['buyer_id'], 'nav' => 'fulfill'
					]);

					$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
						'success' => true, 'id' => $merge['buyer_id'], 'menu' => 'orders', 'tab' => 'placed'
					]);
					$this->senddataapi->trigger('count-item-in-tab', 'incoming-tab-counts', [
						'success' => true, 'id' => $this->accounts->profile['id'], 'menu' => 'fulfillments', 'tab' => 'placed'
					]);

					$buyer = json_decode(base64_decode($merge['buyer']), true);
					$seller = json_decode(base64_decode($merge['seller']), true);
					notify_order_details($merge, $buyer, [$seller['user_id']], $action, str_replace(' ', '-', urldecode(get_status_value($status_value))));

					$redirect = false;
					$this->set_response('success', 'Order is now Set For Pick Up!', $post, $redirect);
				} else {
					$this->set_response('info', 'Order Already set For Pick Up!', $post, false);
				}
			}
		}
		$this->set_response('error', remove_multi_space('Unable to set Order for pick up'), $post);
	}

	public function check_fulfillments()
	{
		$this->load->library('ToktokApi');
		// debug($this->toktokapi, 'stop');
		$this->toktokapi->check_delivery();
	}

}