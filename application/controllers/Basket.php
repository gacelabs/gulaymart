<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends My_Controller {

	public $allowed_methods = 'all';
	public $not_allowed_methods = ['checkout'];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('baskets');
		// INITIALIZING TOKTOK OBJECT
		// $this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');
	}

	public function index()
	{
		$sessions = $this->session->userdata('basket_session');
		$basket_session = [];
		if ($sessions) {
			// debug($sessions, 'stop');
			foreach ($sessions as $key => $basket) {
				$basket['user_id'] = $sessions[$key]['user_id'] = $this->accounts->has_session ? $this->accounts->profile['id'] : 0;
				$id = $this->gm_db->new('baskets', $basket);
				$basket['id'] = $sessions[$key]['id'] = $id;
				$basket['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
				$driving_distance = get_driving_distance([
					['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
					['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
				]);
				$basket['distance'] = $driving_distance['distanceval'];
				$basket['duration'] = $driving_distance['durationval'];
				$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
			}
			// debug($basket_session, 'stop');
			$this->session->unset_userdata('basket_session');
		} else {
			/*query the baskets in DB*/
			if ($this->accounts->has_session) {
				$sessions = $this->baskets->get_in(['user_id' => $this->accounts->profile['id'], 'status' => [0, 1]]);
				// debug($sessions, 'stop');
				if (is_array($sessions)) {
					foreach ($sessions as $key => $basket) {
						$basket['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
						// debug($basket['rawdata'], 'stop');
						$driving_distance = get_driving_distance([
							['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
							['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
						]);
						$basket['distance'] = $driving_distance['distanceval'];
						$basket['duration'] = $driving_distance['durationval'];
						$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
					}
				}
			}
		}
		if (count($basket_session)) {
			// debug($basket_session, 'stop');
		}
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'basket/main']
			],
			'middle' => [
				'body_class' => ['dashboard', 'basket'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'basket/basket_container',
				],
			],
			'bottom' => [
				'modals' => ['reply_modal'],
				'js' => [
					'plugins/jquery.inputmask.min',
					'plugins/inputmask.binding',
					'dashboard/main',
					'basket/main',
				],
			],
			'data' => [
				'baskets' => $basket_session
			],
		]);
	}

	public function add($product_id=0)
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post AND $product_id) {
			if (isset($post['baskets'])) {
				$timestamp = strtotime(date('Y-m-d')); $time = date('g:ia');

				$product = $this->products->product_by_farm_location($product_id, $post['baskets']['location_id']);
				// debug($post['baskets'], $product, 'stop');
				if ($product) {
					$post['baskets']['product_id'] = $product_id;
					$post['baskets']['user_id'] = $this->accounts->has_session ? $this->accounts->profile['id'] : 0;
					$post['baskets']['at_date'] = $timestamp;
					$post['baskets']['at_time'] = $time;
					$post['baskets']['rawdata'] = base64_encode(json_encode($product));

					$check_basket = $this->gm_db->get('baskets', [
						'product_id' => $post['baskets']['product_id'],
						'user_id' => $post['baskets']['user_id'],
						'location_id' => $post['baskets']['location_id'],
						'at_date' => $post['baskets']['at_date'],
					], 'row');

					$post['baskets']['fee'] = 0;
					if ($check_basket) {
						$post['baskets']['fee'] = $check_basket['fee'];
					} else {
						/*get toktok fee if not existing in baskets table*/
						$pricing = toktok_price_directions_format([
							'sender_lat' => $product['farm_location']['lat'],
							'sender_lng' => $product['farm_location']['lng'],
							'receiver_lat' => $this->latlng['lat'],
							'receiver_lng' => $this->latlng['lng'],
						]);
						$this->load->library('toktokapi');
						$this->toktokapi->app_request('price_and_directions', $pricing);
						if ($this->toktokapi->success) {
							$price_and_directions = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['pricing'];
							$post['baskets']['fee'] = $price_and_directions['price'];
						}
					}
					// debug($post['baskets'], 'stop');
					/*check if the user is logged in if false save post to session*/
					if ($this->accounts->has_session == false) {
						$this->session->set_userdata('basket_session', $post);
						$message = 'Item added to basket, Redirecting to the registration / login page!';
						$redirect = base_url('register');
					} else {
						/*save the add basket session in DB*/
						if ($check_basket) {
							$quantity = $check_basket['quantity'] + (int)$post['baskets']['quantity'];
							$this->gm_db->save('baskets', ['quantity' => $quantity], ['id' => $check_basket['id']]);
						} else {
							$post['baskets']['id'] = $this->gm_db->new('baskets', $post['baskets']);
						}
						$message = 'Item added to basket!';
						$redirect = false;
					}
					$this->set_response('success', $message, $post, $redirect);
				}
			}
		}
		$this->set_response('error', 'No item(s) found', $post, false);
	}

	public function view($product_id=0, $farm_location_id=0, $product_name='')
	{
		$product = false;
		if ($product_id AND $farm_location_id) {
			// debug($product_id, $farm_location_id, 'stop');
			$product = $this->products->product_by_farm_location($product_id, $farm_location_id);
		}
		// debug($product, 'stop');
		$this->render_page([
			'top' => [
				'css' => ['modal/modals', 'basket/productpage', 'static/store']
			],
			'middle' => [
				'body_class' => ['product-page'],
				'head' => ['../global/global_navbar'],
				'body' => [
					'basket/productpage_top',
					'basket/productpage_middle',
					'../global/banner_support_locals',
				],
				'footer' => ['global/footer']
			],
			'bottom' => [
				'modals' => [],
				'js' => ['basket/productpage', 'plugins/fb-login'],
			],
			'data' => [
				'product' => $product
			],
		]);
	}

	public function delete()
	{
		$post = $this->input->post();
		$get = $this->input->get();
		if ($post AND isset($post['data'])) {
			// debug($post, 'stop');
			foreach ($post['data'] as $key => $row) {
				$this->baskets->save(['status' => 5], $row); /*cancelled*/
			}
			$this->set_response('success', 'Product removed in basket', $post['data'], 'basket/', 'removeOnBasket');
		} elseif ($get AND isset($get['data'])) {
			// debug($get, 'stop');
			$this->set_response('confirm', 'Want to delete selected product(s)?', $get['data'], false, 'removeBasketItem');
		}
		$this->set_response('error', remove_multi_space('Unable to delete '.$name.' product'), $post);
	}

	public function verify()
	{
		/*
		 * status:
		 * 1 = verified (checkout page)
		 * 2 = placed
		 * 3 = on delivery
		 * 4 = received
		 * 5 = cancelled
		*/
		$post = $this->input->post();
		if ($post AND isset($post['data'])) {
			// debug($post, 'stop');
			$ids = array_keys($post['data']);
			$baskets = $this->baskets->get_in(['id' => $ids]);
			// debug($baskets, 'stop');
			foreach ($post['data'] as $id => $row) {
				$basket = $this->baskets->get(['id' => $id], true);
				// debug($basket, 'stop');
				if ($basket) {
					$order_type = 1; 
					if ($post['type'] != 'deliver_now') {
						$order_type = 2;
						/*compute date range between the ETA*/
						$etas = [];
						foreach ($baskets as $key => $bskt) {
							$etas[$bskt['location_id']] = (float) $bskt['duration'];
						}
						$eta_duration = 0;
						foreach ($etas as $location_id => $duration) {
							$eta_duration += $duration;
						}
						$eta = $eta_duration;
					}
					$this->baskets->save([
						'quantity' => $row['quantity'],
						'status' => $row['checked'], // verified can be viewed on checkout
						'order_type' => $order_type,
					], ['id' => $id]);
				}
			}
			echo json_encode(['success' => true, 'type' => 'success', 'redirect' => 'basket/checkout', 'message' => 'Basket verified, proceeding to check-out...']);
			exit();
		}
		echo json_encode(['success' => true, 'type' => 'error', 'redirect' => false, 'message' => 'Unable to proceed for checkout!']);
		exit();
	}

	public function checkout()
	{
		$basket_session = [];
		$sessions = $this->baskets->get_in(['user_id' => $this->accounts->profile['id'], 'status' => [0, 1]]);
		// debug($sessions, 'stop');
		if (is_array($sessions)) {
			foreach ($sessions as $key => $basket) {
				$basket['rawdata'] = json_decode(base64_decode($basket['rawdata']), true);
				// debug($basket['rawdata'], 'stop');
				$driving_distance = get_driving_distance([
					['lat' => $basket['rawdata']['farm_location']['lat'], 'lng' => $basket['rawdata']['farm_location']['lng']],
					['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
				]);
				$basket['distance'] = $driving_distance['distanceval'];
				$basket['duration'] = $driving_distance['durationval'];
				// debug($basket, 'stop');
				$basket_session[date('F j, Y', $basket['at_date'])][] = $basket;
			}
		}
		if (count($basket_session)) {
			// debug($basket_session, 'stop');
		}
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main', 'transactions/main', 'basket/main']
			],
			'middle' => [
				'body_class' => ['dashboard', 'basket'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
					'basket/basket_checkout',
				],
			],
			'bottom' => [
				'modals' => ['reply_modal'],
				'js' => [
					'plugins/jquery.inputmask.min',
					'plugins/inputmask.binding',
					'dashboard/main',
					'basket/main',
				],
			],
			'data' => [
				'baskets' => $basket_session
			],
		]);
	}

	private function book_delivery()
	{
		// DELIVERY POSTING
		$params = [
			'f_id' => '',
			'referral_code' => 'PPS8083189',
			'f_post' => '',
			'pac-input' => 'Harmony Hills I Subdivision, Harmony Hills, 1, San Jose del Monte City, Bulacan, Philippines',
			'pac-input2' => 'R-Twin Trading, M. Villarica Road, Sta. Rosa, Marilao City, Bulacan, Philippines',
			'f_distance' => '',
			'f_duration' => '',
			'f_price' => '',
			'f_distance_hidden' => '',
			'f_duration_hidden' => '',
			'f_driver_id' => '',
			'f_promo_code' => '',
			'f_promo_error' => '',
			'f_some_error' => '',
			'f_sender_name' => 'Carriza',
			'f_sender_mobile' => '09123456789',
			'f_sender_landmark' => 'Aling Nena Store',
			'f_sender_address' => 'Harmony Hills I Subdivision, Harmony Hills, 1, San Jose del Monte City, Bulacan, Philippines',
			'f_sender_address_lat' => 14.7884463,
			'f_sender_address_lng' => 121.0283154,
			'f_order_type_send' => 1,
			'f_sender_date' => '',
			'f_sender_datetime_from' => '',
			'f_sender_datetime_to' => '',
			'f_sen_add_in_city' => '',
			'f_sen_add_in_pro' => '',
			'f_sen_add_in_reg' => '',
			'f_sen_add_in_coun' => '',
			'f_recepient_name' => 'Evita Holmes',
			'f_recepient_mobile' => '09198765432',
			'f_recepient_landmark' => 'Repair shop',
			'f_recepient_address' => 'R-Twin Trading, M. Villarica Road, Sta. Rosa, Marilao City, Bulacan, Philippines',
			'f_recepient_address_lat' => 14.7803604,
			'f_recepient_address_lng' => 120.9768949,
			'f_order_type_rec' => 1,
			'f_recepient_date' => '',
			'f_recepient_datetime_from' => '',
			'f_recepient_datetime_to' => '',
			'f_rec_add_in_city' => '',
			'f_rec_add_in_pro' => '',
			'f_rec_add_in_reg' => '',
			'f_rec_add_in_coun' => '',
			'f_collectFrom' => 'S', // where to collect the delivery fees // S is SENDER R is RECEPIENT
			'f_recepient_notes' => 'Please keep hot',
			'f_cargo' => 'Food',
			'f_cargo_others' => 'Food',
			'f_is_cod' => '',
			'f_recepient_cod' => '', // if COD is checked real item price will appear here
			'f_express_fee' => '',
			'f_express_fee_hidden' => 40.00, // if express fee is checked - toktok fixed 40 pesos fee
		];
		// GET RIDER
		$rider = ['term' => '9614068479', '_type' => 'query', 'q' => '9614068479'];
		$this->toktokapi->app_request('rider', $rider);
		if ($this->toktokapi->success) {
			$driver_id = $this->toktokapi->response['results'][0]['id'];
			$params['f_driver_id'] = $driver_id;
		}
		// GET PRICE AND DIRECTIONS
		$pricing = [
			'f_sender_lat' => $params['f_sender_address_lat'],
			'f_sender_lon' => $params['f_sender_address_lng'],
			'f_promo_code' => '',
			'destinations' => [
				['recipient_lat' => $params['f_recepient_address_lat'], 'recipient_lon' => $params['f_recepient_address_lng']]
			],
			'isExpress' => 'false',
			'isCashOnDelivery' => 'false',
		];
		$this->toktokapi->app_request('price_and_directions', $pricing);
		if ($this->toktokapi->success) {
			$hash = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['hash'];
			$price_and_directions = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['pricing'];

			$params['f_post'] = json_encode(['hash' => $hash]);
			$params['f_distance'] = $price_and_directions['distance']. ' km';
			$params['f_duration'] = format_duration($price_and_directions['duration']);
			$params['f_price'] = $price_and_directions['price'];
		}
		// if Sender Order Type is SCHEDULED
		if ($params['f_order_type_send'] == 2) {
			$params['f_sender_date'] = "03/02/2021";
			$params['f_sender_datetime_from'] = "02:13:23";
			$params['f_sender_datetime_to'] = "03:13:27";
		}
		// if Recepient Order Type is SCHEDULED
		if ($params['f_order_type_rec'] == 2) {
			$params['f_recepient_date'] = "03/02/2021";
			$params['f_recepient_datetime_from'] = "02:13:23";
			$params['f_recepient_datetime_to'] = "03:13:27";
		}
		// if COD is checked matic collect from Recepient
		if ($params['f_is_cod'] == 'on') {
			$params['f_collectFrom'] = 'R';
		}

		debug($params, 'stop'); // check parameters
		$this->toktokapi->app_request('post_delivery', $params);
		// STOPPING requests
		$this->toktokapi->stop();
		debug($this->toktokapi, 'stop');
	}

	private function check_delivery($driver_id='', $order_status='', $searchstring='D1F2444PJ8')
	{
		parse_str('draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=1&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=2&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=3&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=4&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=5&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=6&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=7&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=8&columns%5B8%5D%5Bname%5D=&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=false&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=9&columns%5B9%5D%5Bname%5D=&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=DESC&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&date_from=03%2F30%2F2021&date_to=04%2F06%2F2021&driver_id=&order_status=&searchstring='.$searchstring, $output);
		// debug($output, 'stop');

		// CHECKING ORDER BY ID
		$this->toktokapi->app_request('check_orders', $output);
		if ($this->toktokapi->success) {
			$order_tags = [
				'button' => $this->toktokapi->response['data'][0][11],
				'a' => $this->toktokapi->response['data'][0][10],
			];
			// debug($order_tags, 'stop');

			// VIEWING DELIVERY DATA
			$html_button = simplexml_load_string($order_tags['button']);
			$delivery_hash = $html_button->attributes()->{'id'};
			// debug($delivery_hash, 'stop');
			$this->toktokapi->app_request('view_delivery', $delivery_hash);
			$delivery = false;
			if ($this->toktokapi->success) {
				$delivery = [
					'details' => $this->toktokapi->response['message']['message'],
					'logs' => $this->toktokapi->response['message']['delivery_logs'],
				];
				// debug($delivery, 'stop');
			}
			
			// GET RIDER LOCATION BY DELIVERY ID
			$a_tag = simplexml_load_string($order_tags['a']);
			$href = $a_tag->attributes()->{'href'};
			$exploded = explode('/', $href);
			$delivery_id = end($exploded);
			// debug($delivery_id, 'stop');
			$this->toktokapi->app_request('rider_location', ['delivery_id' => $delivery_id], 'website');
			$rider_location = false;
			if ($this->toktokapi->success) {
				$rider_location = [
					'current' => $this->toktokapi->response['location_info'],
					'earlier' => $this->toktokapi->response['list_delivery_status'],
				];
			}
			debug($delivery, $rider_location, 'stop');
		}
	}

}