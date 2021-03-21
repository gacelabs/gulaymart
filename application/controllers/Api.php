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
			// debug($post, 'stop');
			$user_id = $this->accounts->has_session ? $this->accounts->profile['id'] : 0;
			$has_shippings = $this->gm_db->count('user_shippings', ['user_id' => $user_id]);
			// debug($has_shippings, 'stop');
			if ($has_shippings == 0 AND !isset($post['active'])) $post['active'] = 1;
			$post['ip_address'] = $_SERVER['REMOTE_ADDR'];

			if (isset($post['id']) AND $post['id'] > 0) {
				$id = $post['id']; unset($post['id']);
				$this->gm_db->save('user_shippings', $post, ['id' => $id]);
				$post['id'] = $id;
				$this->set_response('success', 'Shipping address saved!', $post, false, 'updateSavedObjects');
			} elseif (!isset($post['id'])) {
				$id = $this->gm_db->new('user_shippings', $post);
				$this->set_response('success', 'Shipping address added!', $post, 'profile');
			}
		}
		$this->set_response('error', 'Unable to save shipping address!', $post);
	}

	public function save_active_shipping()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($post) {
			// debug($post, 'stop');
			$user_id = $this->accounts->has_session ? $this->accounts->profile['id'] : 0;
			$has_shippings = $this->gm_db->count('user_shippings', ['user_id' => $user_id]);
			if ($has_shippings) {
				$this->gm_db->save('user_shippings', ['active' => 0], ['user_id' => $user_id, 'active' => 1]);
				if (isset($post['id']) AND $post['id'] > 0) {
					$id = $post['id'];
					$this->gm_db->save('user_shippings', ['active' => 1], ['id' => $id]);
				}
			}
		}
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
		$this->set_response('error', 'Unable to save notification settings!', $post);
	}

}