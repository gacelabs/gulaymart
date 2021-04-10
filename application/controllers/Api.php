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

	public function agree_terms()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			if (isset($post['farmer_terms']) AND isset($post['farmer_policy'])) {
				if ($post['farmer_terms'] == 'on' AND $post['farmer_policy'] == 'on') {
					$user_id = $this->accounts->profile['id'];
					$ok = $this->gm_db->save('users', ['is_agreed_terms' => 1], ['id' => $user_id]);
					if ($ok) {
						$this->set_response('success', 'Terms & Policy Accepted!', $post, 'farm/storefront', 'agreementSigned');
					}
				}
			}
		}
		$this->set_response('error', 'Unable to Accept Terms & Policy aggreement!', $post);
	}

	public function media_uploader()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			$profile = $this->accounts->profile;
			$dir = 'medias/'.str_replace('@', '-', $profile['email_address']);
			$uploads = files_upload($_FILES, $dir);
			// debug($post, $uploads, 'stop');
			$index = false;
			if (isset($post['galleries'])) {
				foreach ($post['galleries'] as $table => $row) {
					if (is_array($row)) {
						$index = isset($row['index']) ? $row['index'] : false;
					} else {
						$index = $row ?: false;
					}
					break;
				}
			}
			if ($uploads) {
				foreach ($uploads as $key => $upload) {
					$gallery = $this->gm_db->get('galleries', $upload);
					if ($gallery == false) {
						$this->gm_db->new('galleries', $upload);
					}
				}
			}
			// debug($uploads, $index, 'stop');
			if ($index !== false AND isset($uploads[$index])) {
				$post['selected'] = $uploads[$index];
				$this->set_response('success', 'Media Upload successful! Image selected', $post, false, 'changeUIImage');
			} elseif ($uploads AND !isset($post['selected'])) {
				$post['selected'] = $uploads;
				$this->set_response('success', 'Media Upload successful!', $post, false, 'changeUIImage');
			} else {
				$this->set_response('success', 'Image selected!', $post, false, 'changeUIImage');
			}
		}
		$this->set_response('error', 'Unable to upload images!', $post);
	}

	public function farm_locations()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($post) {
			$this->set_response('info', 'Location verified!', $post, false, 'setStoreFarmLocation');
		}
		$this->set_response('error', 'Unable to set location!', $post);
	}

	public function save_latlng()
	{
		$saved = 'users geolocation not saved';
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($post) {
			if ($this->accounts->has_session) {
				$user_id = $this->accounts->profile['id'];
				$this->gm_db->save('users', $post, ['id' => $user_id]);
				$saved = 'users geolocation saved';
			}
		}
		echo $saved; exit();
	}

}