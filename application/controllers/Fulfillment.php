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
				$baskets_merge[$key]['toktok_post'] = json_decode(base64_decode($baskets_merge[$key]['toktok_post']), true);
			}
		}
		// debug($baskets_merge, 'stop');
		$farm = $this->farmers->get(['user_id' => $this->accounts->profile['id']], true);
		// debug($farm, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
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
				'js' => ['fulfillment/main'],
			],
			'data' => [
				'farm' => $farm,
				'orders' => $baskets_merge,
				'status' => $status,
				'counts' => [
					'placed' => order_count_by_status(['location_id' => $farm['farm_location_ids'], 'status' => 2]),
					'for+pick+up' => order_count_by_status(['location_id' => $farm['farm_location_ids'], 'status' => 6]),
					'on+delivery' => order_count_by_status(['location_id' => $farm['farm_location_ids'], 'status' => 3]),
					'received' => order_count_by_status(['location_id' => $farm['farm_location_ids'], 'status' => 4]),
					'cancelled' => order_count_by_status(['location_id' => $farm['farm_location_ids'], 'status' => 5]),
				],
			]
		]);
	}

	public function placed()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-placed'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

	public function pickup()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-pick-up'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

	public function delivery()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-delivery'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

	public function received()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-received'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

	public function cancelled()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main', 'global/zigzag', 'modal/invoice-modal', 'global/order-table']
			],
			'middle' => [
				'body_class' => ['dashboard', 'fulfillment', 'ff-cancelled'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'fulfillment/ff_container'
				],
				'footer' => [],
			],
			'bottom' => [
				'modals' => ['ff_invoice_modal'],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

	public function delete($all=0)
	{
		$response = $this->senddataapi->trigger('remove-item', 'ordered-items', ['all'=>$all/*, 'data'=>$post['data']*/]);
		// debug($response, 'stop');
	}

}