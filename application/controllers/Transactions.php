<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->orders();
	}

	public function orders()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Transactions » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'transactions', 'orders'],
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

	public function messages()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Transactions » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'transactions', 'messages'],
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

	public function settings()
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Gulaymart | Transactions » '.fix_title(__FUNCTION__).'"'
				],
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Transactions » '.fix_title(__FUNCTION__).'',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'transactions', 'settings'],
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