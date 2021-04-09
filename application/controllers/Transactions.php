<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends MY_Controller {

	public $allowed_methods = [];

	public function index()
	{
		$this->orders();
	}

	public function orders()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'transactions/orders']
			],
			'middle' => [
				'body_class' => ['dashboard', 'orders'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/orders',
				],
			],
			'bottom' => [
				'js' => ['hideshow', 'dashboard/main'],
			],
		]);
	}

	public function messages()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'transactions/messages']
			],
			'middle' => [
				'body_class' => ['dashboard', 'messages'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/messages',
				],
			],
			'bottom' => [
				'modals' => ['reply_modal'],
				'js' => ['hideshow', 'readmore.min', 'messages', 'dashboard/main'],
			],
		]);
	}
}