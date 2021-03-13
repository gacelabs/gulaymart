<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
		$referrer = str_replace(base_url('/'), '', $this->agent->referrer());
		if ($this->accounts->profile['is_profile_complete'] == 0) {
			$to = 'profile?error=Finish your Profile!';
		} elseif (!empty($referrer)) {
			$to = $referrer;
		}
		if (isset($to)) redirect(base_url($to));
	}

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