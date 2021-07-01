<?php

function check_value($key, $data=[], $is_profile=false, $is_echo=true)
{
	$ci =& get_instance();
	$echo = '';
	$post = $ci->input->post() ?: $ci->input->get();
	if ($is_profile AND $ci->accounts->has_session) {
		$post = $ci->accounts->profile;
	}

	if (!$post) $post = (array) $data;

	if (is_array($post) AND count($post)) {
		$echo = element($key, $post);
		// debug($echo);
	}
	if ($is_echo) {
		echo $echo;
	} else {
		return $echo;
	}
}

function toktok_post_delivery_format($data=false)
{
	// debug($data, 'stop');
	$ci =& get_instance();
	$pricing = $data['toktok_details']['pricing'];
	$buyer = $ci->accounts->profile;
	$seller = $data['seller'];
	$shippings = $buyer['shippings'];
	$shipping = false;
	foreach ($shippings as $key => $row) {
		if ($row['active']) {
			$shipping = $row;
			break;
		}
	}
	$params = [
		'f_id' => '',
		'referral_code' => REFERRAL_CODE,
		'f_post' => $data ? json_encode(['hash' => $data['hash']]) : '',
		'pac-input' => $seller ? remove_multi_space($seller['address_1'].' '.$seller['address_2'], true) : '',
		'pac-input2' => $shipping ? remove_multi_space($shipping['address_1'].' '.$shipping['address_2'], true) : '',
		'f_distance' => $pricing ? $pricing['distance'] . 'km' : '0 km',
		'f_duration' => $pricing ? format_duration($pricing['duration']) : '0 minutes',
		'f_price' => $pricing ? $pricing['price'] : '',
		'f_distance_hidden' => '',
		'f_duration_hidden' => '',
		'f_driver_id' => isset($data['mobile']) ? $data['mobile'] : '80109',
		'f_promo_code' => '',
		'f_promo_error' => '',
		'f_some_error' => '',
		'f_sender_name' => $seller ? remove_multi_space($seller['profile']['firstname'].' '.$seller['profile']['lastname'], true) : '', 
		'f_sender_mobile' => $seller ? $seller['profile']['phone'] : '',
		'f_sender_landmark' => $seller ? $seller['name'] : '', 
		'f_sender_address' => $seller ? remove_multi_space($seller['address_1'].' '.$seller['address_2'], true) : '',
		'f_sender_address_lat' => $seller ? $seller['lat'] : 0,
		'f_sender_address_lng' => $seller ? $seller['lng'] : 0,
		'f_order_type_send' => 1,
		'f_sender_date' => '',
		'f_sender_datetime_from' => '',
		'f_sender_datetime_to' => '',
		'f_sen_add_in_city' => '',
		'f_sen_add_in_pro' => '',
		'f_sen_add_in_reg' => '',
		'f_sen_add_in_coun' => '',
		'f_recepient_name' => $buyer['fullname'],
		'f_recepient_mobile' => $buyer['profile']['phone'],
		'f_recepient_landmark' => '',
		'f_recepient_address' => $shipping ? remove_multi_space($shipping['address_1'].' '.$shipping['address_2'], true) : '',
		'f_recepient_address_lat' => $shipping['lat'],
		'f_recepient_address_lng' => $shipping['lng'],
		'f_order_type_rec' => 1,
		'f_recepient_date' => '',
		'f_recepient_datetime_from' => '',
		'f_recepient_datetime_to' => '',
		'f_rec_add_in_city' => '',
		'f_rec_add_in_pro' => '',
		'f_rec_add_in_reg' => '',
		'f_rec_add_in_coun' => '',
		'f_collectFrom' => 'R', // where to collect the delivery fees // S is SENDER R is RECEPIENT
		'f_recepient_notes' => '',
		'f_cargo' => 'Food',
		'f_cargo_others' => 'Food',
		'f_is_cod' => 'on',
		'f_recepient_cod' => '', // if COD is checked real item price will appear here
		'f_express_fee' => '',
		'f_express_fee_hidden' => 40, // if express fee is checked - toktok fixed 40 pesos fee
	];
	return $params;
}

function toktok_price_directions_format($data=false)
{
	$ci =& get_instance();
	$pricing = [
		'f_sender_lat' => $data ? $data['sender_lat'] : 0,
		'f_sender_lon' => $data ? $data['sender_lng'] : 0,
		'f_promo_code' => '',
		'destinations' => [
			[
				'recipient_lat' => $data ? $data['receiver_lat'] : 0,
				'recipient_lon' => $data ? $data['receiver_lng'] : 0,
			]
		],
		'isExpress' => ($data AND isset($data['express']) AND $data['express'] == true) ? 'true' : 'false',
		// 'isCashOnDelivery' => ($data AND isset($data['cod']) AND $data['cod'] == true) ? 'true' : 'false',
		'isCashOnDelivery' => 'true',
	];
	return $pricing;
}

function compute_eta($eta=0, $noprefix=false, $echo=true)
{
	$time = gmdate("H:i:s", $eta);
	$chunks = array_map('trim', explode(':', $time));
	$duration = [];
	foreach ($chunks as $key => $value) {
		switch ($key) {
			case '0':
				if ($value > 0) {
					$duration[] = (int)$value.' hr'.((int)$value > 1 ? 's' : '');
				}
				break;
			case '1':
				if ($value > 0) {
					$duration[] = $value.' min'.($value > 1 ? 's' : '');
				}
				break;
			/*case '2':
				if ($value > 0) {
					$duration[] = $value.' sec'.($value > 1 ? 's' : '');
				}
				break;*/
		}
	}
	// debug($chunks, $duration, 'stop');
	$return = NULL;
	if (count($duration)) {
		$return = ($noprefix ? '' : '(ETA: ').implode(' ', $duration).($noprefix ? '' : ')');
	}
	if ($echo) {
		echo $return;
	} else {
		return $return;
	}
}

function redirect_basket_orders()
{
	$ci =& get_instance();
	$basket_session = $ci->session->userdata('basket_session');
	// debug($basket_session, 'stop');
	if ($ci->accounts->has_session) {
		if ($basket_session AND $ci->accounts->profile['is_profile_complete'] == 1) {
			redirect(base_url('basket/'));
		}
	}
	return false;
}

function get_session_baskets($where=false)
{
	$ci =& get_instance();
	$ci->load->library('baskets');
	$basket_session = [];
	$session_baskets = $ci->session->userdata('basket_session');
	if ($session_baskets) {
		if (is_array($session_baskets)) {
			foreach ($session_baskets as $key => $basket) {
				$existing = $basket['existing'];
				unset($basket['existing']);
				$basket['user_id'] = $ci->accounts->has_session ? $ci->accounts->profile['id'] : 0;
				// debug($basket, json_decode(base64_decode($basket['rawdata']), true), true);
				if ($existing == 0) {
					$id = $ci->gm_db->new('baskets', $basket);
				} elseif ($existing == 1) {
					$id = $basket['id']; unset($basket['id']);
					$ci->gm_db->save('baskets', $basket, ['id' => $id]);
				}
			}
		}
		$ci->session->unset_userdata('basket_session');
	}
	/*get all session basket*/
	if (is_array($where)) {
		if (!isset($where['status'])) $where['status'] = [0,1];
	} else {
		$where = ['status' => [0,1]];
	}
	if ($ci->accounts->has_session) {
		$where['user_id'] = $ci->accounts->profile['id'];
	} else {
		$where['device_id'] = $ci->device_id;
	}
	$baskets = $ci->baskets->get_in($where, false, false, 'added:DESC');
	if (is_array($baskets)) {
		foreach ($baskets as $key => $basket) {
			$date = date('F j, Y', $basket['at_date']);
			$basket_session[$date][] = $basket;
		}
	}
	// debug($basket_session, 'stop');
	return $basket_session;
}

function add_item_to_basket($post, $product_id)
{
	$ci =& get_instance();
	if ($post) {
		if (isset($post['baskets'])) {
			$timestamp = strtotime(date('Y-m-d')); $time = date('g:ia');
			$product = $ci->products->product_by_farm_location($product_id, $post['baskets']['location_id']);
			// debug($post['baskets'], $product, 'stop');
			if ($product) {
				$post['baskets']['product_id'] = $product_id;
				$post['baskets']['user_id'] = $ci->accounts->has_session ? $ci->accounts->profile['id'] : 0;
				$post['baskets']['at_date'] = $timestamp;
				$post['baskets']['at_time'] = $time;
				$post['baskets']['rawdata'] = base64_encode(json_encode($product));
				$post['baskets']['device_id'] = $ci->device_id;

				$where = [
					'product_id' => $post['baskets']['product_id'],
					'location_id' => $post['baskets']['location_id'],
					'at_date' => $post['baskets']['at_date'],
					'status' => [0,1],
				];
				if ($ci->accounts->has_session) {
					$where['user_id'] = $ci->accounts->profile['id'];
				} else {
					$where['device_id'] = $ci->device_id;
				}

				$existing = $ci->gm_db->get_in('baskets', $where, 'row');
				// debug($existing, $ci->device_id, 'stop');

				$post['baskets']['fee'] = 0;
				$post['baskets']['status'] = 0;
				$post['baskets']['hash'] = '';
				if ($existing) {
					$post['baskets']['fee'] = $existing['fee'];
					$post['baskets']['status'] = $existing['status'];
					$post['baskets']['hash'] = $existing['hash'];
				} else {
					/*get toktok fee if not existing in baskets table*/
					$pricing = toktok_price_directions_format([
						'sender_lat' => $product['farm_location']['lat'],
						'sender_lng' => $product['farm_location']['lng'],
						'receiver_lat' => $ci->latlng['lat'],
						'receiver_lng' => $ci->latlng['lng'],
					]);
					$ci->load->library('toktokapi');
					$ci->toktokapi->app_request('price_and_directions', $pricing);
					if ($ci->toktokapi->success) {
						$price_and_directions = $ci->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['pricing'];
						$post['baskets']['fee'] = $price_and_directions['price'];
						$hash = $ci->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['hash'];
						$post['baskets']['hash'] = json_encode(['hash' => $hash]);
					}
				}
	
				return [
					'baskets' => $post['baskets'],
					'existing' => $existing,
				];
			}
		}
	}
	return false;
}

function compute_summary($baskets=false, &$sub_total=0)
{
	$shipping_fee = 0;
	if ($baskets) {
		$fees_per_farm = [];
		foreach ($baskets as $farm => $basket) {
			// debug($basket, 'stop');
			if (isset($basket[0])) {
				foreach ($basket as $key => $row) {
					$fees_per_farm[$row['location_id']] = (float) $row['fee'];
					$sub_total += (int)$row['quantity'] * (float) $row['rawdata']['basket_details']['price'];
				}
			} else {
				$fees_per_farm[$basket['location_id']] = (float) $basket['fee'];
				$sub_total += (int)$basket['quantity'] * (float) $basket['rawdata']['basket_details']['price'];
			}
		}
		foreach ($fees_per_farm as $location_id => $fee) {
			$shipping_fee += $fee;
		}
	}
	return $shipping_fee;
}

function toktok_rider($rider=['term'=>'9614068479','_type'=>'query','q'=>'9614068479'])
{
	if ($rider) {
		$ci =& get_instance();
		// GET RIDER
		$ci->toktokapi->app_request('rider', $rider);
		if ($ci->toktokapi->success) {
			$driver_id = $ci->toktokapi->response['results'][0]['id'];
			return $driver_id;
		}
	}
	// return '';
	return '80109'; /*for now its arturo the rider*/
}

function get_status_value($status=false)
{
	if ($status) {
		$ci =& get_instance();
		switch (strtolower(trim($status))) {
			case '1': /*verified*/
				return 'verified';
			break;
			case '2': /*placed*/
				return 'placed';
			break;
			case '3': /*on delivery*/
				return 'on+delivery';
			break;
			case '4': /*received*/
				return 'received';
			break;
			case '5': /*cancelled*/
				return 'cancelled';
			break;
			case '6': /*for pick up*/
				return 'for+pick+up';
			break;
		}
	}
	return '';
}

function get_status_dbvalue($status=false)
{
	if ($status) {
		$ci =& get_instance();
		switch (strtolower(trim($status))) {
			case 'verified': /*verified*/
				return 1;
			break;
			case 'placed': /*placed*/
				return 2;
			break;
			case 'on+delivery': /*on delivery*/
				return 3;
			break;
			case 'received': /*received*/
				return 4;
			break;
			case 'cancelled': /*cancelled*/
				return 5;
			break;
			case 'for+pick+up': /*for pick up*/
				return 6;
			break;
		}
	}
	return 0;
}

function get_toktokstatus_value($status=false)
{
	if ($status) {
		$ci =& get_instance();
		switch (strtolower(trim($status))) {
			case '1': /*Placed Order*/
				return 'placed+order';
			break;
			case '2': /*Scheduled for Delivery*/
				return 'scheduled+for+delivery';
			break;
			case '3': /*On the Way to Sender*/
				return 'on+the+way+to+sender';
			break;
			case '4': /*Item Picked Up*/
				return 'item+picked+up';
			break;
			case '5': /*On the Way to Recipient*/
				return 'on+the+way+to+recipient';
			break;
			case '6': /*Item Delivered*/
				return 'item+delivered';
			break;
		}
	}
	return '';
}

function get_toktokstatus_dbvalue($status=false)
{
	if ($status) {
		$ci =& get_instance();
		switch (strtolower(trim($status))) {
			case 'placed+order': /*verified*/
				return 1;
			break;
			case 'scheduled+for+delivery': /*placed*/
				return 2;
			break;
			case 'on+the+way+to+sender': /*on delivery*/
				return 3;
			break;
			case 'item+picked+up': /*received*/
				return 4;
			break;
			case 'on+the+way+to+recipient': /*cancelled*/
				return 5;
			break;
			case 'item+delivered': /*for pick up*/
				return 6;
			break;
		}
	}
	return 0;
}

function storefront_url($farm=false, $echo=false)
{
	$return = false;
	if ($farm) {
		$farm_id = $farm['id'];
		if (isset($farm['farm_id'])) {
			$farm_id = $farm['farm_id'];
		}
		if (isset($farm['farm_location_id'])) {
			$return = base_url('store_location/'.$farm_id.'/'.$farm['farm_location_id'].'/'.nice_url($farm['name'], true));
		} elseif (isset($farm['farm_id']) AND isset($farm['id'])) {
			$return = base_url('store_location/'.$farm_id.'/'.$farm['id'].'/'.nice_url($farm['name'], true));
		} else {
			$return = base_url('store_farm/'.$farm_id.'/'.nice_url($farm['name'], true));
		}
	}
	// debug($return, true);
	if ($echo) {
		echo $return;
	} else {
		return $return;
	}
}

function product_url($item=false, $echo=false)
{
	$return = false;
	if ($item) {
		$return = base_url('basket/view/'.$item['id'].'/'.$item['farm_location_id'].'/'.nice_url($item['name'], true));
	}
	if ($echo) {
		echo $return;
	} else {
		return $return;
	}
}

function send_gm_message($user_id=false, $datestamp=false, $content=false, $tab='Notifications', $type='Inventory')
{
	$ci =& get_instance();
	if ($user_id AND $datestamp AND $content) {
		// send message to the user has to replenish the needed stocks for delivery
		$check_msgs = $ci->gm_db->get('messages', [
			'tab' => $tab, 'type' => $type,
			'user_id' => $user_id, 'unread' => 1,
			'datestamp' => $datestamp,
			'content' => $content,
		], 'row');
		if ($check_msgs == false) {
			$ci->gm_db->new('messages', [
				'tab' => $tab, 'type' => $type,
				'user_id' => $user_id, 'datestamp' => $datestamp,
				'content' => $content,
			]);
			return true;
		}
	}
	return false;
}

function send_gm_email($user_id=false, $content=false, $subject='Email Notification')
{
	$ci =& get_instance();
	if ($user_id AND $content) {
		$user = $ci->gm_db->get('users', ['id' => $user_id], 'row');
		// debug($post, 'stop');
		if ($user AND filter_var($user['email_address'], FILTER_VALIDATE_EMAIL)) {
			$mail = $ci->smtpemail->setup();
			$email = ['email_body_message' => $content];
			$email['email_subject'] = $subject;
			$email['email_to'] = $user['email_address'];
			$email['email_bcc'] = ['sirpoigarcia@gmail.com', 'gacelabs.inc@gmail.com'];
			// debug($email, 'stop');

			// $mail->debug = TRUE;
			$return = $mail->send($email, 5/*, TRUE*/); /*send after 5 mins*/
			// debug($return, 'stop');
		}
	}
	return false;
}

function count_by_status($where=false)
{
	$ci =& get_instance();
	if ($where !== false) {
		$fulfillments = $ci->gm_db->get_in('baskets_merge', $where);
		$products = false;
		if ($fulfillments) {
			$fulfilled = [];
			foreach ($fulfillments as $key => $fulfillment) {
				$fulfilled[] = $fulfillment;
			}
			// debug($fulfilled, 'stop');
			return $fulfilled == false ? 0 : count($fulfilled);
		}
	}
	return 0;
}

function notify_placed_orders($final_total, $merge_ids, $seller_ids, $buyer)
{
	$html = file_get_contents(base_url('support/view_thankyou_page/'.$final_total));
	// debug($html, 'stop');
	/*create_dirs('placed_orders');
	$all_orders_id = strtoupper(substr(md5(implode(',', $merge_ids)), 0, 10));
	$filename = 'assets/data/files/placed_orders/'.$all_orders_id.'-placed.html';
	$handle = fopen($filename, "w+");
	fwrite($handle, $html);
	fclose($handle);*/
	
	/*message buyer*/
	send_gm_email($buyer['id'], $html, 'Your Order have been Placed, Thank you!');
	$html = '<p>Order have been placed, <a href="orders/placed/">Check here</a></p>';
	send_gm_message($buyer['id'], strtotime(date('Y-m-d')), $html, 'Notifications', 'Orders');
	
	/*message sellers*/
	$html = '<p>Order from '.$buyer['fullname'].' have been placed, <a href="fulfillment/placed/">Check here</a></p>';
	foreach ($seller_ids as $seller_id) {
		send_gm_message($seller_id, strtotime(date('Y-m-d')), $html, 'Notifications', 'Orders');
	}
	/*LOGS FOR TRACKING*/
	$logfile = fopen(get_root_path('assets/data/logs/placed-orders.log'), "a+");
	$txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
	$txt .= "Basket Merge IDs: " . implode(',', $merge_ids) . " \n";
	$txt .= "Price total: " . $final_total . " \n";
	$txt .= "Buyer ID: " . $buyer['id'] . " \n";
	$txt .= "Seller IDs: " . implode(',', $seller_ids) . " \n";
	$txt .= "--------------------------------" . "\n";
	fwrite($logfile, $txt);
	fclose($logfile);
}

function notify_invoice_orders($merge, $buyer, $seller_ids, $action='Ready for pick up', $status='for+pick+up')
{
	$html = file_get_contents(base_url('support/view_invoice/'.$merge['order_id']));
	// debug($printable, 'stop');
	/*create_dirs('invoices');
	$filename = 'assets/data/files/invoices/'.$merge['order_id'].'-invoice.html';
	$handle = fopen($filename, "w+");
	fwrite($handle, $printable);
	fclose($handle);*/
	
	/*message buyer*/
	send_gm_email($buyer['id'], $html, 'Your Order is '.$action.', Thank you!');
	$html = '<p>Order is '.$action.', <a href="orders/'.$status.'/">Check here</a></p>';
	send_gm_message($buyer['id'], strtotime(date('Y-m-d')), $html, 'Notifications', 'Orders');
	
	/*message sellers*/
	$html = '<p>Order from '.$buyer['fullname'].' are '.$action.', <a href="fulfillment/'.$status.'/">Check here</a></p>';
	foreach ($seller_ids as $seller_id) {
		send_gm_message($seller_id, strtotime(date('Y-m-d')), $html, 'Notifications', 'Orders');
	}
	/*LOGS FOR TRACKING*/
	$logfile = fopen(get_root_path('assets/data/logs/'.$status.'-orders.log'), "a+");
	$txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
	$txt .= "Order ID: " . $merge['order_id'] . " \n";
	$txt .= "Buyer ID: " . $buyer['id'] . " \n";
	$txt .= "Seller IDs: " . implode(',', $seller_ids) . " \n";
	$txt .= "--------------------------------" . "\n";
	fwrite($logfile, $txt);
	fclose($logfile);
}

function setup_basketmerge_data($baskets_merge=false)
{
	if ($baskets_merge) {
		$ci =& get_instance();
		foreach ($baskets_merge as $key => $merged) {
			$baskets_merge[$key]['seller'] = json_decode(base64_decode($baskets_merge[$key]['seller']), true);
			$baskets_merge[$key]['buyer'] = json_decode(base64_decode($baskets_merge[$key]['buyer']), true);
			$baskets_merge[$key]['order_details'] = json_decode(base64_decode($baskets_merge[$key]['order_details']), true);
			foreach ($baskets_merge[$key]['order_details'] as $index => $details) {
				// $baskets_merge[$key]['order_details'][$index]['status'] = 2;
				if (!isset($baskets_merge[$key]['order_type'])) {
					$baskets_merge[$key]['order_type'] = $details['when'];
					$baskets_merge[$key]['schedule'] = '';
					if ($details['when'] == 2) {
						$baskets_merge[$key]['schedule'] = date('F j, Y', strtotime($details['schedule']));
					}
				}
				$basket = $ci->gm_db->get('baskets', ['id' => $details['basket_id']], 'row');
				$baskets_merge[$key]['order_details'][$index]['cancel_by'] = '';
				$baskets_merge[$key]['order_details'][$index]['reason'] = '';
				if ($basket) {
					$baskets_merge[$key]['order_details'][$index]['cancel_by'] = $basket['cancel_by'];
					$baskets_merge[$key]['order_details'][$index]['reason'] = $basket['reason'];
				}
			}
			$baskets_merge[$key]['toktok_post'] = json_decode(base64_decode($baskets_merge[$key]['toktok_post']), true);
		}
	}
	return $baskets_merge;
}