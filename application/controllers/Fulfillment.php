<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fulfillment extends My_Controller {

	public function index() {
		$this->placed();
	}

	public function placed()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'fulfillment/main']
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
				'modals' => [],
				'js' => ['fulfillment/main'],
			],
			'data' => []
		]);
	}

}