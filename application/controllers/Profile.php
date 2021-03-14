<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public $allowed_methods = [];

	public function index($id=false)
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => 'Either you`re a farmer or a customer, '.APP_NAME.' is your best avenue for anything veggies! Sign Up for FREE!',
					'name' => 'Either you`re a farmer or a customer, '.APP_NAME.' is your best avenue for anything veggies! Sign Up for FREE!',
				],
			],
			'middle' => [
				'body_class' => 'dashboard profile',
				'head' => ['dashboard/nav_top'],
				'body' => [
					'dashboard/navbar_aside',
					'dashboard/profile'
				],
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'js' => [
					'jquery.inputmask.min',
					'inputmask.binding',
					'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA&libraries=places',
					'markerclustererplus.min',
					'profile',
				],
			],
		]);
	}
}