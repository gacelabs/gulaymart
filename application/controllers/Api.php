<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public $allowed_methods = ['fetch_coordinates'/*, 'test_mail'*/];

	public function __construct()
	{
		parent::__construct();
	}

	public function test_mail($type='hello')
	{
		$mail = $this->smtpemail->setup($type);
		$email = ['email_body_message' => 'Test Email Sent!'];
		$email['email_subject'] = 'Email Testing';
		$email['email_to'] = 'gacelabs.inc@gmail.com';
		$email['email_bcc'] = ['sirpoigarcia@gmail.com'];
		// debug($email, 'stop');
		// $mail->debug = TRUE;
		$return = $mail->send($email, false, true);
		debug($return, 'stop');
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
				if (isset($post['email_address']) AND strlen(trim($post['email_address'])) > 0) {
					$this->gm_db->save('users', ['email_address' => $post['email_address']], ['id' => $id]);
				}
				$this->set_response('success', 'Profile info saved!', $post, 'profile');
			} elseif (!isset($post['id'])) {
				$id = $this->gm_db->new('user_profiles', $post);
				if (isset($post['email_address']) AND strlen(trim($post['email_address'])) > 0) {
					$this->gm_db->save('users', ['email_address' => $post['email_address']], ['id' => $id]);
				}
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

	public function fetch_coordinates()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		if ($post) {
			$coordinates = get_coordinates($post);
			// debug($coordinates, 'stop');
			if ($coordinates) {
				if ($this->accounts->has_session) {
					$this->accounts->refetch();
				} else {
					$this->latlng = (array) $coordinates;
				}
				// $this->session->set_userdata('prev_latlng', serialize($this->latlng));
				set_cookie('prev_latlng', serialize($this->latlng), 7776000); // 90 days
				$city = remove_multi_space(str_replace('city of', '', strtolower($post['city'])), true);
				$city = remove_multi_space(str_replace('city', '', strtolower($city)), true);
				set_cookie('current_city', trim($city), 7776000); // 90 days
				$this->set_response('success', 'You have set your residing address at '.ucwords($city).' City!', $coordinates, false, 'reloadState');
			}
		}
		$this->set_response('error', 'City not existing!', $post);
	}

	public function set_html($view=false)
	{
		$post = $this->input->post() ?: $this->input->get();
		// debug($view, $post, 'stop');
		if ($post AND $view) {
			if (isset($post['table'])) {
				$table = $post['table'];
				if (isset($post['data'])) {
					$data = $post['data'];
					$row = 'result';
					if (isset($post['row'])) $row = 'row';
					$results = $this->gm_db->get_in($table, $data, $row);
					// debug($results, 'stop');
					if ($results) {
						$html = $this->load->view('static/'.$view, $results, true);
						$object = ['html'=>$html,'identifier'=>''];
						if (isset($post['identifier'])) {
							$object['identifier'] = $post['identifier'];
						}
						$this->set_response('error', false, $object, false, 'renderHTML');
					}
				}
			}
		}
		$this->set_response('error', false, $post);
	}

	public function set_invoice_html($view=false)
	{
		$post = $this->input->post() ?: $this->input->get();
		// debug($view, $post, 'stop');
		if ($post AND $view) {
			if (isset($post['table'])) {
				$table = $post['table'];
				if (isset($post['data'])) {
					$data = $post['data'];
					$row = 'result';
					if (isset($post['row'])) $row = 'row';
					$results = $this->gm_db->get_in($table, $data, $row);
					$results['method'] = false;
					$referrer = str_replace(base_url('/'), '', $this->agent->referrer());
					$segments = explode('/', $referrer);
					if (count($segments)) $results['method'] = end($segments);
					// debug($results, 'stop');
					if ($results) {
						$html = $this->load->view('static/'.$view, $results, true);
						$object = ['html'=>$html,'identifier'=>''];
						if (isset($post['identifier'])) {
							$object['identifier'] = $post['identifier'];
						}
						$printable = file_get_contents(base_url('support/view_invoice/'.$results['order_id']));
						// debug($printable, 'stop');
						create_dirs('invoices');
						$filename = 'assets/data/files/invoices/'.$results['order_id'].'-invoice.html';
						$handle = fopen($filename, "w+");
						fwrite($handle, $printable);
						fclose($handle);
						$object['printable_link'] = base_url($filename);
						$this->set_response('error', false, $object, false, 'renderHTML');
					}
				}
			}
		}
		$this->set_response('error', false, $post);
	}

	public function fulfillment_process($profile_id=0)
	{
		if ($profile_id > 0 OR $this->accounts->has_session) {
			if ($this->accounts->has_session) $profile_id = $this->accounts->profile['id'];
			$this->load->library('baskets');
			$status_value = $this->input->post('status');
			$segment = $this->input->post('segment');
			$status_id = get_status_dbvalue($status_value);
			$baskets_merge = $this->baskets->get_baskets_merge(['seller_id' => $profile_id, 'status' => $status_id]);
			$baskets_merge_data = setup_fulfillments_data($baskets_merge);
			// debug($baskets_merge_data, 'stop');
			$baskets_merge_ids = [];
			if ($baskets_merge_data) {
				$this->load->library('toktokapi');
				// debug($this->toktokapi, 'stop');
				foreach ($baskets_merge_data as $key => $data) {
					$toktok_status = '';
					$valid = false;
					switch ($status_value) {
						case 'for+pick+up': /*if status is now on-delivery*/
							$toktok_status = 4;
							$valid = empty($data['delivery_id']);
							$GM_status = 3;
							break;
						case 'on+delivery': /*if status is now received*/
							$toktok_status = 6;
							$valid = !empty($data['delivery_id']);
							$GM_status = 4;
							break;
					}
					if ($valid) {
						$buyer_name = $data['buyer']['fullname'];
						$date_range = false;
						if (isset($data['schedule']) AND !empty($data['schedule'])) {
							$date_range = [
								'from' => date('m/d/Y', strtotime($data['schedule'])),
								'to' => date('m/d/Y', strtotime($data['schedule'])),
							];
						}
						// check toktok delivery status
						$delivery = $this->toktokapi->check_delivery($date_range, '', $toktok_status, $buyer_name);
						// $delivery = $this->toktokapi->check_delivery();
						if ($delivery->success AND count($delivery->response)) {
							// debug($buyer_name, $delivery, 'stop');
							foreach ($delivery->response as $order) {
								if (isset($order['details']) AND isset($order['details']['post'])) {
									// $order['details']['post']['notes'] = 'GulayMart Order#:6ED99B0438';
									$notes_data = explode('GulayMart Order#:', $order['details']['post']['notes']);
									$order_id = '';
									if (count($notes_data) AND isset($notes_data[1])) $order_id = trim($notes_data[1]);

									if ($order_id == $data['order_id']) {
										/*set new status*/
										$set = ['status' => $GM_status];
										if (empty($data['delivery_id'])) {
											/*delivery_id not set yet*/
											$delivery_id = $order['details']['post']['delivery_id'];
											$set['delivery_id'] = $delivery_id;
											$set['toktok_data'] = base64_encode(json_encode($order));
											/*update baskets*/
											$ids = explode(',', $data['basket_ids']);
											foreach ($ids as $id) {
												$this->gm_db->save('baskets', ['status' => $GM_status], ['id' => $id]);
											}
										}
										$response = $this->gm_db->save('baskets_merge', $set, ['id' => $data['id']]);
										if ($response) $baskets_merge_ids[] = $data['id'];
									}
								}
							}
						}
					}
				}
			}
			/*$this->senddataapi->trigger($segment.'-fulfillment', 'order-bookings', [
				'message' => 'You have available bookings passed from '.$segment,
				'data' => ['success' => false, 'ids' => $baskets_merge_ids, 'event' => $segment],
			]);*/
			if (count($baskets_merge_ids)) {
				echo json_encode(['success' => true, 'ids' => $baskets_merge_ids, 'event' => $segment]); exit();
			} else {
				echo json_encode(['success' => false, 'ids' => $baskets_merge_ids, 'event' => $segment]); exit();
			}
			// debug($baskets_merge_data, 'stop');
		}
	}

	public function order_process($profile_id=0)
	{
		if ($profile_id > 0 OR $this->accounts->has_session) {
			if ($this->accounts->has_session) $profile_id = $this->accounts->profile['id'];
			$this->load->library('baskets');
			$status_value = $this->input->post('status');
			$segment = $this->input->post('segment');
			$status_id = get_status_dbvalue($status_value);
			$baskets_merge = $this->baskets->get_baskets_merge(['buyer_id' => $this->accounts->profile['id'], 'status' => $status_id]);
			$baskets_merge_data = setup_orders_data($baskets_merge);
			// debug($baskets_merge_data, 'stop');
			$baskets_ids = [];
			if ($baskets_merge_data) {
				$this->load->library('toktokapi');
				// debug($this->toktokapi, 'stop');
				foreach ($baskets_merge_data as $key => $data) {
					$toktok_status = '';
					$valid = false;
					switch ($status_value) {
						case 'for+pick+up': /*if status is now on-delivery*/
							$toktok_status = 4;
							$valid = empty($data['delivery_id']);
							$GM_status = 3;
							break;
						case 'on+delivery': /*if status is now received*/
							$toktok_status = 6;
							$valid = !empty($data['delivery_id']);
							$GM_status = 4;
							break;
					}
					if ($valid) {
						$seller_name = remove_multi_space($data['seller']['profile']['firstname'].' '.$data['seller']['profile']['lastname'], true);
						$date_range = false;
						if (isset($data['schedule']) AND !empty($data['schedule'])) {
							$date_range = [
								'from' => date('m/d/Y', strtotime($data['schedule'])),
								'to' => date('m/d/Y', strtotime($data['schedule'])),
							];
						}
						// debug($date_range, $toktok_status, $seller_name, 'stop');
						// check toktok delivery status
						$delivery = $this->toktokapi->check_delivery($date_range, '', $toktok_status, $seller_name);
						// $delivery = $this->toktokapi->check_delivery();
						// debug($seller_name, $delivery, 'stop');
						if ($delivery->success AND count($delivery->response)) {
							foreach ($delivery->response as $order) {
								if (isset($order['details']) AND isset($order['details']['post'])) {
									// $order['details']['post']['notes'] = 'GulayMart Order#:6ED99B0438';
									$notes_data = explode('GulayMart Order#:', $order['details']['post']['notes']);
									$order_id = '';
									if (count($notes_data) AND isset($notes_data[1])) $order_id = trim($notes_data[1]);

									if ($order_id == $data['order_id']) {
										/*set new status*/
										$set = ['status' => $GM_status];
										if (empty($data['delivery_id'])) {
											/*delivery_id not set yet*/
											$delivery_id = $order['details']['post']['delivery_id'];
											$set['delivery_id'] = $delivery_id;
											$set['toktok_data'] = base64_encode(json_encode($order));
											/*update baskets*/
											$ids = explode(',', $data['basket_ids']);
											foreach ($ids as $id) {
												$this->gm_db->save('baskets', ['status' => $GM_status], ['id' => $id]);
											}
										}
										$response = $this->gm_db->save('baskets_merge', $set, ['id' => $data['id']]);
										$baskets_ids[] = array_merge($baskets_ids, $ids);
									}
								}
							}
						}
					}
				}
			}
			/*$this->senddataapi->trigger($segment.'-order', 'order-bookings', [
				'message' => 'You have available bookings passed from '.$segment,
				'data' => ['success' => false, 'ids' => $baskets_ids, 'event' => $segment],
			]);*/
			if (count($baskets_ids)) {
				echo json_encode(['success' => true, 'ids' => $baskets_ids, 'event' => $segment]); exit();
			} else {
				echo json_encode(['success' => false, 'ids' => $baskets_ids, 'event' => $segment]); exit();
			}
			// debug($baskets_merge_data, 'stop');
		}
	}

}