<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public $allowed_methods = [];

	public function index()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Profile"',
					'property="og:description" content="Gulaymart | Profile"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Profile"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Profile',
				'css' => ['global', 'dashboard', 'profile', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['dashboard', 'profile'],
				/* found in views/templates */
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/panel_left',
					'dashboard/panel_right'
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