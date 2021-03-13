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
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Help Center"',
					'property="og:description" content="Gulaymart | Help Center"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Help Center"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Help Center',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'support', 'help-center'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/panel_left',
				],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'css' => [],
				'js' => ['main'],
			],
		];
		$data = [
			'is_login' => 0
		];

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}