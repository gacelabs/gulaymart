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
			// debug($post, 'stop');
			if (isset($post['id']) AND $post['id'] > 0) {
				$id = $post['id']; unset($post['id']);
				$this->gm_db->save('user_profiles', $post, ['id' => $id]);
				$this->set_response('success', 'Shipping address saved!', $post);
			} elseif (!isset($post['id'])) {
				$this->gm_db->new('user_profiles', $post);
				$this->set_response('success', 'Shipping address added!', $post, 'profile');
			}
		}
		$this->set_response('error', 'Unable to save shipping address!', $post);
	}

	public function save_info()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($post) {
			// debug($post, 'stop');
			if (isset($post['id']) AND $post['id'] > 0) {
				$id = $post['id']; unset($post['id']);
				$this->gm_db->save('user_profiles', $post, ['id' => $id]);
				$this->set_response('success', 'Profile info saved!', $post);
			} elseif (!isset($post['id'])) {
				$this->gm_db->new('user_profiles', $post);
				$this->set_response('success', 'Profile info added!', $post, 'profile');
			}
		}
		$this->set_response('error', 'Unable to save profile info!', $post);
	}

	public function save_notif()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			if (isset($post['user_id'])) {
				$user_id = $post['user_id']; unset($post['user_id']);
			}
			$user_id = $this->accounts->has_session ? $this->accounts->profile['id'] : $user_id;
			$is_removed = $this->gm_db->remove('user_settings', ['user_id' => $user_id]);
			$news = [];
			foreach ($post as $setting => $value) {
				$news[] = ['setting' => $setting, 'value' => ($value == 'on' ? 'checked' : ''), 'user_id' => $user_id];
			}
			if ($is_removed) {
				$this->gm_db->new_batch('user_settings', $news);
				$this->set_response('success', 'Notification Settings Saved!', $post);
			}
		}
		$this->set_response('error', 'Unable to save profile info!', $post);
	}

}