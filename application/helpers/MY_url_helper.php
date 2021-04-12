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
	$params = [
		'f_id' => '',
		'referral_code' => REFERRAL_CODE,
		'f_post' => '',
		'pac-input' => '',
		'pac-input2' => '',
		'f_distance' => '',
		'f_duration' => '',
		'f_price' => '',
		'f_distance_hidden' => '',
		'f_duration_hidden' => '',
		'f_driver_id' => '',
		'f_promo_code' => '',
		'f_promo_error' => '',
		'f_some_error' => '',
		'f_sender_name' => '',
		'f_sender_mobile' => '',
		'f_sender_landmark' => '',
		'f_sender_address' => '',
		'f_sender_address_lat' => $data ? $data['lat'] : 0,
		'f_sender_address_lng' => $data ? $data['lng'] : 0,
		'f_order_type_send' => 1,
		'f_sender_date' => '',
		'f_sender_datetime_from' => '',
		'f_sender_datetime_to' => '',
		'f_sen_add_in_city' => '',
		'f_sen_add_in_pro' => '',
		'f_sen_add_in_reg' => '',
		'f_sen_add_in_coun' => '',
		'f_recepient_name' => '',
		'f_recepient_mobile' => '',
		'f_recepient_landmark' => '',
		'f_recepient_address' => '',
		'f_recepient_address_lat' => $ci->latlng['lat'],
		'f_recepient_address_lng' => $ci->latlng['lng'],
		'f_order_type_rec' => 1,
		'f_recepient_date' => '',
		'f_recepient_datetime_from' => '',
		'f_recepient_datetime_to' => '',
		'f_rec_add_in_city' => '',
		'f_rec_add_in_pro' => '',
		'f_rec_add_in_reg' => '',
		'f_rec_add_in_coun' => '',
		'f_collectFrom' => 'S', // where to collect the delivery fees // S is SENDER R is RECEPIENT
		'f_recepient_notes' => '',
		'f_cargo' => '',
		'f_cargo_others' => '',
		'f_is_cod' => '',
		'f_recepient_cod' => '', // if COD is checked real item price will appear here
		'f_express_fee' => '',
		'f_express_fee_hidden' => 40.00, // if express fee is checked - toktok fixed 40 pesos fee
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
		'isCashOnDelivery' => ($data AND isset($data['cod']) AND $data['cod'] == true) ? 'true' : 'false',
	];
	return $pricing;
}

function compute_eta($eta=0)
{
	// $time = gmdate("g:i:s", $eta);
	$time = gmdate("g:i", $eta);
	$chunks = array_map('trim', explode(':', $time));
	$duration = [];
	foreach ($chunks as $key => $value) {
		switch ($key) {
			case '0':
				if ($value > 0) {
					$duration[] = $value.' hr'.($value > 1 ? 's' : '');
				}
				break;
			case '1':
				if ($value > 0) {
					$duration[] = $value.' min'.($value > 1 ? 's' : '');
				}
				break;
			case '2':
				if ($value > 0) {
					$duration[] = $value.' sec'.($value > 1 ? 's' : '');
				}
				break;
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

function get_session_baskets()
{
	$ci =& get_instance();
	$basket_session = [];
	$is_userdata = false;
	$session_baskets = $ci->session->userdata('basket_session');
	if ($session_baskets) {
		$is_userdata = true;
		$ci->session->unset_userdata('basket_session');
	} elseif ($ci->accounts->has_session) {
		$session_baskets = $ci->baskets->get_in(['user_id' => $ci->accounts->profile['id'], 'status' => [0, 1]]);
	}
	// debug($session_baskets, 'stop');
	if (is_array($session_baskets)) {
		foreach ($session_baskets as $key => $basket) {
			if ($is_userdata) {
				$basket['user_id'] = $sessions[$key]['user_id'] = $ci->accounts->has_session ? $ci->accounts->profile['id'] : 0;
				$id = $ci->gm_db->new('baskets', $basket);
				$basket['id'] = $sessions[$key]['id'] = $id;
			}
			$basket['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
			$driving_distance = get_driving_distance([
				['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
				['lat' => $ci->latlng['lat'], 'lng' => $ci->latlng['lng']],
			]);
			$basket['distance'] = $driving_distance['distanceval'];
			$basket['duration'] = $driving_distance['durationval'];
			$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
		}
	}

	return $basket_session;
}