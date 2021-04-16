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
	$ci =& get_instance();
	$rawdata = $data['rawdata'];
	$user = $ci->accounts->profile;
	$farm = $ci->gm_db->get('user_profiles', ['user_id' => $rawdata ? $rawdata['user_id'] : 0], 'row');
	$shippings = $user['shippings'];
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
		'f_post' => $data ? $data['hash'] : '',
		'pac-input' => $rawdata ? remove_multi_space($rawdata['farm_location']['address_1'].' '.$rawdata['farm_location']['address_2'], true) : '',
		'pac-input2' => $shipping ? remove_multi_space($shipping['address_1'].' '.$shipping['address_2'], true) : '',
		'f_distance' => $data ? $data['distance_text'] : '',
		'f_duration' => $data ? $data['duration_text'] : '',
		'f_price' => $data ? $data['fee'] : '',
		'f_distance_hidden' => '',
		'f_duration_hidden' => '',
		'f_driver_id' => toktok_rider(isset($data['mobile']) ? $data['mobile'] : false),
		'f_promo_code' => '',
		'f_promo_error' => '',
		'f_some_error' => '',
		'f_sender_name' => $rawdata ? remove_multi_space($farm['firstname'].' '.$farm['lastname'], true) : '', 
		'f_sender_mobile' => $farm ? $farm['phone'] : '',
		'f_sender_landmark' => $rawdata ? $rawdata['farm']['name'] : '', 
		'f_sender_address' => $rawdata ? remove_multi_space($rawdata['farm_location']['address_1'].' '.$rawdata['farm_location']['address_2'], true) : '',
		'f_sender_address_lat' => $rawdata ? $rawdata['farm_location']['lat'] : 0,
		'f_sender_address_lng' => $rawdata ? $rawdata['farm_location']['lng'] : 0,
		'f_order_type_send' => 1,
		'f_sender_date' => '',
		'f_sender_datetime_from' => '',
		'f_sender_datetime_to' => '',
		'f_sen_add_in_city' => '',
		'f_sen_add_in_pro' => '',
		'f_sen_add_in_reg' => '',
		'f_sen_add_in_coun' => '',
		'f_recepient_name' => $user['fullname'],
		'f_recepient_mobile' => $user['profile']['phone'],
		'f_recepient_landmark' => '',
		'f_recepient_address' => $shipping ? remove_multi_space($shipping['address_1'].' '.$shipping['address_2'], true) : '',
		'f_recepient_address_lat' => $user['lat'],
		'f_recepient_address_lng' => $user['lng'],
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

function compute_eta($eta=0)
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
	if (count($duration)) {
		return 'ETA: '.implode(' ', $duration);
	}
	return NULL;
}

function redirect_basket_orders()
{
	$ci =& get_instance();
	$basket_session = $ci->session->userdata('basket_session');
	if ($ci->accounts->has_session) {
		if ($basket_session AND $ci->accounts->profile['is_profile_complete'] == 1) {
			redirect(base_url('basket/'));
		}
	}
	return false;
}

function get_session_baskets($status=[0,1])
{
	$ci =& get_instance();
	$ci->load->library('baskets');
	$basket_session = [];
	$is_userdata = false;
	$session_baskets = $ci->session->userdata('basket_session');
	if ($session_baskets) {
		$is_userdata = true;
		$ci->session->unset_userdata('basket_session');
	} elseif ($ci->accounts->has_session) {
		$session_baskets = $ci->baskets->get_in(['user_id' => $ci->accounts->profile['id'], 'status' => $status]);
	}
	// debug($session_baskets, 'stop');
	if (is_array($session_baskets)) {
		foreach ($session_baskets as $key => $basket) {
			if ($is_userdata) {
				$basket['user_id'] = $ci->accounts->has_session ? $ci->accounts->profile['id'] : 0;
				$id = $ci->gm_db->new('baskets', $basket);
				$basket['id'] = $id;
				$basket['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
				$driving_distance = get_driving_distance([
					['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
					['lat' => $ci->latlng['lat'], 'lng' => $ci->latlng['lng']],
				]);
				$basket['distance'] = $driving_distance['distanceval'];
				$basket['duration'] = $driving_distance['durationval'];
				$basket['distance_text'] = $driving_distance['distance'];
				$basket['duration_text'] = $driving_distance['duration'];
				// debug($basket, 'stop');
			}
			$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
		}
	}
	// debug($basket_session, 'stop');
	if ($is_userdata) { /*user is now logged in or registered*/
		$baskets = $ci->baskets->get_in(['user_id' => ($ci->accounts->has_session ? $ci->accounts->profile['id'] : 0), 'status' => [0, 1]]);
		if (is_array($baskets)) {
			foreach ($baskets as $key => $basket) {
				$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
			}
		}
	}

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

				$existing = $ci->gm_db->get_in('baskets', [
					'product_id' => $post['baskets']['product_id'],
					'user_id' => $post['baskets']['user_id'],
					'location_id' => $post['baskets']['location_id'],
					'at_date' => $post['baskets']['at_date'],
					'status' => [0,1],
				], 'row');

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
		switch ($status) {
			case '1': /*verified*/
				return 'verified';
			break;
			case '2': /*placed*/
				return 'placed';
			break;
			case '3': /*otw*/
				return 'delivery';
			break;
			case '4': /*received*/
				return 'received';
			break;
			case '5': /*cancelled*/
				return 'cancelled';
			break;
		}
	}
	return '';
}