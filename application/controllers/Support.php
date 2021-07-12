<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = ['view_invoice', 'view_thankyou_page', 'terms', 'policy'];

	public function index()
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
	}

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
			}
		}
	}

	public function view_thankyou_page($final_total=false)
	{
		if ($final_total) {
			// debug($final_total, 'stop');
			$this->render_page([
				'top' => [
					'css' => ['static/thankyou']
				],
				'middle' => [
					'body' => ['../static/thankyou'],
				],
				'data' => ['for_email' => true, 'total'=>$final_total]
			]);
		}
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
}