<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = [];

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
}