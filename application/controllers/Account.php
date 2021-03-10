<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		$actions = explode('/', trim($_SERVER['REDIRECT_URL'], '/'));
		$this->action = str_replace(['/', '-'], ['', '_'], end($actions));
		parent::__construct();
		$this->load->library('smtpemail');
		if (!$this->accounts->has_session) $this->class_name = '';
		// debug(get_class_methods(__CLASS__));
	}

	public function index($id=false)
	{
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'property="fb:app_id" content="xxx"',
					'property="og:type" content="article"',
					'property="og:url" content="xxx"',
					'property="og:title" content="xxx"',
					'property="og:description" content="Either you`re a farmer or a customer, Gulaymart is your best avenue for anything veggies! Sign Up for FREE!"',
					'property="og:image" content="xxx"',
					// SEO generics
					'name="description" content="Either you`re a farmer or a customer, Gulaymart is your best avenue for anything veggies! Sign Up for FREE!"'
				],
				'page_title' => 'Gulaymart | Sign Up for FREE!',
				'css' => ['global', 'register', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['register'],
				/* found in views/templates */
				'head' => [],
				'body' => [
					'accounts/register'
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