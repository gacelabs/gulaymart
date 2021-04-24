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
		// INITIALIZING TOKTOK OBJECT
		// $this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');
	}

	public function index() {
		$this->placed();
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

}