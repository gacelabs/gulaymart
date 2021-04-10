<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public $allowed_methods = [];

	public function index($id=false)
	{
		$basket_session = $this->session->userdata('basket_session');
		if ($basket_session AND $this->accounts->profile['is_profile_complete'] == 1) {
			redirect(base_url('basket/'));
		}
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => 'Either you`re a farmer or a customer, '.APP_NAME.' is your best avenue for anything veggies! Sign Up for FREE!',
					'name' => 'Either you`re a farmer or a customer, '.APP_NAME.' is your best avenue for anything veggies! Sign Up for FREE!',
				],
				'css' => ['dashboard/main', 'dashboard/profile', 'global/shipping-form'],
			],
			'middle' => [
				'body_class' => 'dashboard profile',
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'dashboard/profile'
				],
			],
			'bottom' => [
				'modals' => ['login_modal'],
				'js' => [
					'plugins/jquery.inputmask.min',
					'plugins/inputmask.binding',
					'https://maps.googleapis.com/maps/api/js?key=AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA&libraries=places',
					'plugins/markerclustererplus.min',
					'dashboard/profile',
					'dashboard/main'
				],
			],
		]);
	}
}