<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public $allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function save_shipping()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($post) {
			$coordinates = json_decode($post['latlng'], true);
			unset($post['latlng']);
			$post['lat'] = $coordinates['lat'];
			$post['lng'] = $coordinates['lng'];
			// debug($post, 1);
			if (isset($post['id']) AND $post['id'] > 0) {
				$this->gm_db->save('user_profiles', $post);
				$this->set_response('success', 'Shipping address saved!', $post, 'profile');
			} elseif (!isset($post['id'])) {
				$this->gm_db->new('user_profiles', $post, 'profile');
				$this->set_response('success', 'Shipping address added!', $post, 'profile');
			}
		}
		$this->set_response('error', 'Unable to save shipping address!', $post);
	}

}