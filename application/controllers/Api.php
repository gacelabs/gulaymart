<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public $allowed_methods = ['fetch_coordinates'];

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
			$tmp = $post;
			if (isset($post['email_address']) AND strlen(trim($post['email_address'])) > 0) {
				unset($post['email_address']);
			}
			$post['firstname'] = ucwords($post['firstname']);
			$post['lastname'] = ucwords($post['lastname']);
			if (isset($post['id']) AND $post['id'] > 0) {
				$id = $post['id']; unset($post['id']);
				$this->gm_db->save('user_profiles', $post, ['id' => $id]);
				if (isset($tmp['email_address']) AND strlen(trim($tmp['email_address'])) > 0) {
					$this->gm_db->save('users', ['email_address' => $tmp['email_address']], ['id' => $post['user_id']]);
				}
				$this->set_response('success', 'Profile info saved!', $post, 'profile');
			} elseif (!isset($post['id'])) {
				$id = $this->gm_db->new('user_profiles', $post);
				if (isset($tmp['email_address']) AND strlen(trim($tmp['email_address'])) > 0) {
					$this->gm_db->save('users', ['email_address' => $tmp['email_address']], ['id' => $post['user_id']]);
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
			$post = ['data' => $post];
			$farm = $this->gm_db->get_in('user_farms', ['user_id' => $this->accounts->profile['id']], 'row');
			$post['farm_locations'] = false;
			if ($farm) {
				$farm_locations = $this->gm_db->get_in('user_farm_locations', ['farm_id' => $farm['id']]);
				$post['farm_locations'] = $farm_locations;
			}
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
			$city = $post['city']; unset($post['city']);
			$coordinates = get_coordinates($post, false);
			// debug($coordinates, 'stop');
			if ($coordinates) {
				set_cookie('prev_latlng', serialize((array) $coordinates->geometry->location), 7776000); // 90 days
				$city = remove_multi_space(str_replace('city of', '', strtolower($city)), true);
				$city = remove_multi_space(str_replace('city', '', strtolower($city)), true);
				set_cookie('current_city', trim($city), 7776000); // 90 days
				$this->set_response('success', 'You have set your residing address at '.ucwords($city).' City!', $coordinates->geometry->location, false, 'reloadState');
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
						$filename = 'assets/data/files/invoices/'.$results['order_id'].'-invoice.html';
						// if (!file_exists($filename)) {
							$printable = file_get_contents(base_url('support/view_invoice/'.$results['order_id']));
							// debug($printable, 'stop');
							create_dirs('invoices');
							$handle = fopen($filename, "w+");
							fwrite($handle, $printable);
							fclose($handle);
						// }
						$object['printable_link'] = base_url($filename);
						$this->set_response('error', false, $object, false, 'renderHTML');
					}
				}
			}
		}
		$this->set_response('error', false, $post);
	}

	public function fulfillment_process($seller_id=0)
	{
		if ($seller_id > 0 OR $this->accounts->has_session) {
			if ($seller_id == 0 AND $this->accounts->has_session) $seller_id = $this->accounts->profile['id'];
			$this->load->library('baskets');
			$status_value = $this->input->post('status');
			$segment = $this->input->post('segment');
			$status_id = get_status_dbvalue($status_value);
			$baskets_merge = $this->baskets->get_baskets_merge(['seller_id' => $seller_id, 'status' => $status_id]);
			$baskets_merge_data = setup_fulfillments_data($baskets_merge);
			// debug($baskets_merge_data, 'stop');
			$baskets_merge_ids = $buyer_ids = [];
			if ($baskets_merge_data) {
				$this->load->library('ToktokApi');
				// debug($this->toktokapi, 'stop');
				foreach ($baskets_merge_data as $key => $data) {
					$toktok_status = '';
					$valid = false;
					switch ($status_value) {
						case 'for+pick+up': /*if status is now on-delivery*/
							$toktok_status = TT_ON_DELIVERY_STATUS;
							$valid = empty($data['delivery_id']);
							$GM_status = GM_ON_DELIVERY_STATUS;
							break;
						case 'on+delivery': /*if status is now received*/
							$toktok_status = TT_RECEIVED_STATUS;
							$valid = !empty($data['delivery_id']);
							$GM_status = GM_RECEIVED_STATUS;
							break;
					}
					if ($valid) {
						$buyer_name = $data['buyer']['fullname'];
						$buyer_ids[] = $data['buyer']['id'];
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
											$set['toktok_data'] = base64_encode(json_encode($order, JSON_NUMERIC_CHECK));
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
			if (count($baskets_merge_ids)) {

				$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $baskets_merge_ids]);

				echo json_encode(['success' => true, 'ids' => $baskets_merge_ids, 'seller_id' => $seller_id, 'event' => $segment], JSON_NUMERIC_CHECK); exit();
			} else {
				echo json_encode(['success' => false, 'ids' => $baskets_merge_ids, 'seller_id' => $seller_id, 'event' => $segment], JSON_NUMERIC_CHECK); exit();
			}
			// debug($baskets_merge_data, 'stop');
		}
	}

	public function order_process($buyer_id=0)
	{
		if ($buyer_id > 0 OR $this->accounts->has_session) {
			if ($buyer_id == 0 AND $this->accounts->has_session) $buyer_id = $this->accounts->profile['id'];
			$this->load->library('baskets');
			$status_value = $this->input->post('status');
			$segment = $this->input->post('segment');
			$status_id = get_status_dbvalue($status_value);
			$baskets_merge = $this->baskets->get_baskets_merge(['buyer_id' => $buyer_id, 'status' => $status_id]);
			$baskets_merge_data = setup_orders_data($baskets_merge);
			// debug($baskets_merge_data, 'stop');
			$baskets_ids = $merge_ids = [];
			if ($baskets_merge_data) {
				$this->load->library('ToktokApi');
				// debug($this->toktokapi, 'stop');
				foreach ($baskets_merge_data as $key => $data) {
					$toktok_status = '';
					$valid = false;
					switch ($status_value) {
						case 'for+pick+up': /*if status is now on-delivery*/
							$toktok_status = TT_ON_DELIVERY_STATUS;
							$valid = empty($data['delivery_id']);
							$GM_status = GM_ON_DELIVERY_STATUS;
							break;
						case 'on+delivery': /*if status is now received*/
							$toktok_status = TT_RECEIVED_STATUS;
							$valid = !empty($data['delivery_id']);
							$GM_status = GM_RECEIVED_STATUS;
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
											$set['toktok_data'] = base64_encode(json_encode($order, JSON_NUMERIC_CHECK));
											/*update baskets*/
											$ids = explode(',', $data['basket_ids']);
											foreach ($ids as $id) {
												$this->gm_db->save('baskets', ['status' => $GM_status], ['id' => $id]);
											}
										}
										$response = $this->gm_db->save('baskets_merge', $set, ['id' => $data['id']]);
										$merge_ids[] = $data['id'];
										$baskets_ids[] = array_merge($baskets_ids, $ids);
									}
								}
							}
						}
					}
				}
			}

			if (count($baskets_ids)) {
				$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge_ids]);

				echo json_encode(['success' => true, 'ids' => $baskets_ids, 'buyer_id' => $buyer_id, 'event' => $segment], JSON_NUMERIC_CHECK); exit();
			} else {
				echo json_encode(['success' => false, 'ids' => $baskets_ids, 'buyer_id' => $buyer_id, 'event' => $segment], JSON_NUMERIC_CHECK); exit();
			}
			// debug($baskets_merge_data, 'stop');
		}
	}

	public function update($object=false, $id=false)
	{
		$message = 'Unable to do request!';
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if ($object AND $id AND $post) {
			$where = ['id' => $id];
			if (is_numeric($id)) {
				$where = ['id' => $id];
				$message = ''; // No errors
			} elseif (is_string($id)) {
				$where = json_decode($id, true);
				switch (json_last_error()) {
					case JSON_ERROR_NONE:
						$message = ''; // No errors
					break;
					case JSON_ERROR_DEPTH:
						$message = 'Maximum stack depth exceeded';
					break;
					case JSON_ERROR_STATE_MISMATCH:
						$message = 'Underflow or the modes mismatch';
					break;
					case JSON_ERROR_CTRL_CHAR:
						$message = 'Unexpected control character found';
					break;
					case JSON_ERROR_SYNTAX:
						$message = 'Syntax error, malformed JSON';
					break;
					case JSON_ERROR_UTF8:
						$message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
					break;
					default:
						$message = 'Unknown error';
					break;
				}
			}
			unset($post['_']); unset($post['callback']);

			if ($message == '') {
				$data = $this->gm_db->get_in($object, $where);
				// debug($data, $post, $message, $where, 'stop');
				if ($data) {
					$fn = false;
					if (isset($post['fn'])) {
						$fn = $post['fn'];
						unset($post['fn']);
					}
					$this->gm_db->save($object, $post, $where);
					if ($object == 'messages') {
						$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['message_id' => $id]);
					}
					$this->set_response('success', false, $data, false, $fn);
				}
			}

		}
		$this->set_response('error', $message, $post);
	}

}