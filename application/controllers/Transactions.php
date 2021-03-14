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
			'middle' => [
				'body_class' => ['transactions', 'orders'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/orders',
				],
			],
		]);
	}

	public function messages()
	{
		$this->render_page([
			'middle' => [
				'body_class' => ['transactions', 'messages'],
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'transactions/messages',
				],
			],
		]);
	}
}