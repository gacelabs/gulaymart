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
		} elseif (!empty($this->farms) AND $this->products->count() == 0) {
			redirect(base_url('farm/my-veggies/?success=Add-in your veggies!'));
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
		$ids = [];
		$filters = ['seller_id' => $this->accounts->profile['id'], 'status' => $status_id];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$ids = is_array($this->input->post('ids')) ? array_values($this->input->post('ids')) : $this->input->post('ids');
			$filters['id'] = $this->input->post('ids');
			$filters['seller_id'] = $this->input->post('seller_id');
			// $filters['id'] = ["25", "28", "31", "41"];
		}
		$baskets_merge = setup_fulfillments_data($this->baskets->get_baskets_merge($filters));
		// debug($baskets_merge, 'stop');
		$farm = $this->farmers->get(['user_id' => $this->accounts->profile['id']], true);
		// debug($farm, 'stop');
		if ($this->input->is_ajax_request()) {
			$htmls = [];
			if ($baskets_merge) {
				foreach ($baskets_merge as $key => $merge) {
					$htmls[$merge['id']] = $this->load->view('templates/fulfillment/ff_fulfill_item', [
						'farm' => $farm,
						'orders' => $merge,
						'status_text' => $status,
						'status_id' => $status_id,
					], true);
				}
			}
			// debug($htmls, 'stop');
			echo json_encode(['html' => $htmls, 'merge_ids' => $ids, 'panel' => 'fulfillment'], JSON_NUMERIC_CHECK);
			exit();
		} else {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'fulfillment/index', 'global/zigzag', 'modal/invoice-modal', 'fulfillment/table', 'print.min']
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
					'status_id' => $status_id,
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
				$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge['id']]);

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
						if (!isset($row['status'])) $row['status'] = GM_PLACED_STATUS;
						if ($row['status'] == GM_CANCELLED_STATUS) $cancelled[] = $row['basket_id'];

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
				
				/*send it realtime to buyer*/
				$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge['id']]);

				$count = $this->gm_db->count('baskets_merge', ['id' => $post['merge_id'], 'status' => $status_value]);
				if ($count == 0) {
					// set status for pick-up this will now also send to toktok post delivery
					$this->gm_db->save('baskets_merge', ['status' => $status_value], ['id' => $post['merge_id']]);
					
					$action = 'Ready for Pick Up';
					if ($status_value == GM_CANCELLED_STATUS) $action = 'Cancelled';

					$buyer = json_decode(base64_decode($merge['buyer']), true);
					$seller = json_decode(base64_decode($merge['seller']), true);
					$status_text = str_replace(' ', '-', urldecode(get_status_value($status_value)));
					notify_order_details($merge, $buyer, [$seller['user_id']], $action, $status_text, ($status_value == GM_CANCELLED_STATUS));

					$this->set_response('success', 'Order is now '.$action.'!', $post, false);
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