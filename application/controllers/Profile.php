<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public $allowed_methods = [];
	public $not_allowed_methods = [];

	public function index($id=false)
	{
		// debug($this, true);
		$is_basket_session = redirect_basket_orders();
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
					'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
					'plugins/markerclustererplus.min',
					'dashboard/profile',
					'dashboard/main'
				],
			],
		]);
	}

	public function save_operator_details()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			$riders = $post['riders']; unset($post['riders']);
			if (!isset($post['active'])) $post['active'] = 0;
			if (!isset($post['id'])) {
				$post['id'] = $this->gm_db->new('operators', $post);
			} else {
				$id = $post['id']; unset($post['id']);
				$this->gm_db->save('operators', $post, ['id' => $id]);
				$post['id'] = $id;
			}
			if (count($riders)) {
				foreach ($riders as $key => $rider) {
					$id = $rider['id']; unset($rider['id']);
					$rider['operator_id'] = $post['id'];
					if (empty($id)) {
						$riders[$key]['id'] = $this->gm_db->new('operator_riders', $rider);
					} else {
						$this->gm_db->save('operator_riders', $rider, ['id' => $id]);
						$riders[$key]['id'] = $id;
					}
				}
			}
			$post['riders'] = $riders;
			$this->set_response('success', 'Operator details Saved!', $post, false, 'appendOperatorID'); }
		$this->set_response('error', 'Unable to save Operator details!', $post);
	}

	public function deactivate_rider()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			if (isset($post['id'])) {
				$rider = $this->gm_db->get('operator_riders', $post);
				if ($rider) {
					$this->gm_db->save('operator_riders', ['active' => 0], $post);
					$this->set_response('success', 'Operator Rider Deactivated!', $post, false, 'deactivateRiderUI');
				}
			}
		}
		$this->set_response('error', 'Unable to deactivate Operator rider!', $post);
	}

	public function activate_rider()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			if (isset($post['id'])) {
				$rider = $this->gm_db->get('operator_riders', $post);
				if ($rider) {
					$this->gm_db->save('operator_riders', ['active' => 1], $post);
					$post['active'] = 1;
					$this->set_response('success', 'Operator Rider Activated!', $post, false, 'activateRiderUI');
				}
			}
			$post['active'] = 0;
		}
		$this->set_response('error', 'Unable to activate Operator rider!', $post);
	}
}