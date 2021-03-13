<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
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
				'index_page' => 'no',
				'page_title' => 'Gulaymart | Sign Up for FREE!',
				'css' => ['global', 'logged-in', 'rwd'],
				'js' => [],
			],
			'middle' => [
				'body_class' => ['logged-in', 'profile'],
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
				'js' => [
					'main',
					'jquery.inputmask.min',
					'inputmask.binding',
					'profile',
					'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA&libraries=places',
					'markerclustererplus.min'
				],
			],
		];
		$data = [
		];

		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}