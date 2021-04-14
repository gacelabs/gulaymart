<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends My_Controller {

	public $allowed_methods = 'all';
	public $not_allowed_methods = ['index', 'checkout', 'place_order'];

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
		$items_by_farm = [];
		$basket_session = get_session_baskets();
		if (count($basket_session)) {
			// debug($basket_session, 'stop');
			/*reassemble data by farm location*/
			foreach ($basket_session as $date => $baskets) {
				foreach ($baskets as $key => $basket) {
					$basket['date'] = $date;
					$items_by_farm[$date][$basket['rawdata']['farm']['name']][] = $basket;
				}
			}
		}
		// debug($items_by_farm, 'stop');
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
				'baskets' => $items_by_farm
			],
		]);
	}

	public function add($product_id=0)
	{
		$data = $this->input->post() ?: $this->input->get();
		$post = add_item_to_basket($data, $product_id);
		// debug($post, 'stop');
		if (isset($post['baskets'])) {
			if ($this->input->get('callback') == 'gmCall') { /*from add to basket button*/
				$status = 0;
			} else { /*from checkout button*/
				$status = 1;
			}
			$existing = false;
			if (isset($post['existing'])) {
				$existing = $post['existing'];
				unset($post['existing']);
			}
			/*check if the user is logged in if false save post to session*/
			if ($this->accounts->has_session == false) {
				$this->session->set_userdata('basket_session', $post);
				$message = 'Item added to basket, Redirecting to the registration / login page!';
				$redirect = base_url('register');
				$callback = false;
			} else {
				/*save the add basket session in DB*/
				if ($existing AND isset($existing['quantity'])) {
					$quantity = $existing['quantity'] + (int)$post['baskets']['quantity'];
					$rawdata = json_decode(base64_decode($existing['rawdata']), true);
					if (isset($rawdata['basket_details']) AND isset($rawdata['basket_details']['stocks'])) {
						$quantity = ($quantity > (int)$rawdata['basket_details']['stocks']) ? $rawdata['basket_details']['stocks'] : $quantity;
					}
					$this->gm_db->save('baskets', ['quantity' => $quantity], ['id' => $existing['id']]);
				} else {
					$post['baskets']['id'] = $this->gm_db->new('baskets', $post['baskets']);
				}
				$message = $status ? 'Item added into your basket!, Proceeding checkout' : 'Item added to basket!';
				$redirect = $status ? base_url('basket/checkout') : false;
				$callback = $status ? false : 'stockChanged';
				$post['baskets']['rawdata'] = json_decode(base64_decode($post['baskets']['rawdata']), true);
			}
			// debug($post, $status, 'stop');
			$this->set_response('success', $message, $post, $redirect, $callback);
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

	public function verify($is_checkout=0)
	{
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
			if ($is_checkout == 0) {
				echo json_encode(['success' => true]); exit();
			} else {
				echo json_encode(['success' => true, 'type' => 'success', 'redirect' => 'basket/checkout', 'message' => 'Basket verified!, Proceeding checkout']); exit();
			}
		}
		echo json_encode(['success' => true, 'type' => 'error', 'redirect' => false, 'message' => 'Unable to proceed for checkout!']);
		exit();
	}

	public function checkout()
	{
		$basket_session = get_session_baskets(1);
		if (count($basket_session)) {
			// debug($basket_session, 'stop');
			/*reassemble data by farm location*/
			$items_by_farm = [];
			foreach ($basket_session as $date => $baskets) {
				foreach ($baskets as $key => $basket) {
					$basket['date'] = $date;
					$items_by_farm[$basket['rawdata']['farm']['name']][] = $basket;
				}
			}
			// debug($items_by_farm, 'stop');
			$this->render_page([
				'top' => [
					'index_page' => 'no',
					'page_title' => APP_NAME.' | Checkout',
					'css' => ['basket/checkout', 'modal/modals'],
				],
				'middle' => [
					'body_class' => ['checkout'],
					'head' => [
						'../global/global_navbar'
					],
					'body' => [
						'basket/checkout'
					],
					'footer' => [
					],
				],
				'bottom' => [
					'modals' => [],
					'js' => ['basket/checkout'],
				],
				'data' => [
					'baskets' => $items_by_farm
				],
			]);
		} else {
			$this->set_response('info', 'No more Orders to Checkout, Shop more!', false, 'marketplace/');
		}
	}

	/*
	 * status:
	 * 1 = verified (checkout page)
	 * 2 = placed
	 * 3 = on delivery
	 * 4 = received
	 * 5 = cancelled
	*/
	public function place_order()
	{
		$baskets = $this->baskets->get(['user_id' => $this->accounts->profile['id'], 'status' => 1]);
		if ($baskets) {
			// $this->book_delivery();
			$items_by_farm = $toktok_temp_data = [];
			foreach ($baskets as $key => $basket) $items_by_farm[$basket['location_id']][] = $basket;
			// debug($items_by_farm, 'stop');
			foreach ($items_by_farm as $location_id => $items) {
				$shipping_fee = $items[0]['fee'];
				$toktok_temp_data[$location_id] = [
					'user_id' => $this->accounts->profile['id'],
					'fee' => $shipping_fee,
					'total_price' => 0,
				];
				foreach ($items as $key => $item) {
					$toktok_temp_data[$location_id]['item'] = $item;
					$toktok_temp_data[$location_id]['total_price'] += (int)$item['quantity'] * (float) $item['rawdata']['basket_details']['price'];
				}
			}
			foreach ($toktok_temp_data as $location_id => $temp) {
				$toktok_post = toktok_post_delivery_format($temp['item']);
				$toktok_post['f_recepient_cod'] = $temp['total_price'] + $temp['fee'];
				$toktok_data = [
					'user_id' => $temp['user_id'],
					'location_id' => $location_id,
					'toktok_data' => base64_encode(json_encode($toktok_post)),
				];
				/*now save to DB for queueing*/
				$toktok = $this->gm_db->get('basket_transactions', [
					'user_id' => $temp['user_id'],
					'location_id' => $location_id,
				], 'row');
				if ($toktok == false) {
					$this->gm_db->new('basket_transactions', $toktok_data);
				} else {
					$this->gm_db->save('basket_transactions', $toktok_data, ['id' => $toktok['id']]);
				}
			}
			// debug($toktok_data, 'stop');
			foreach ($baskets as $key => $basket) {
				$this->baskets->save(['status' => 2], ['id' => $basket['id']]);
			}
			$this->set_response('success', 'Orders have been placed!', false, 'transactions/orders/');
		} else {
			$this->set_response('info', 'No more orders to place', false, 'basket/');
		}
	}

	private function book_delivery()
	{
		// DELIVERY POSTING
		$this->load->library('toktokapi');
		$params = [
			'f_id' => 'D1F36MSKT4',
			// 'referral_code' => 'PPS8083189',
			'f_post' => '',
			'pac-input' => 'Tierra Benita Subdivision, San Jose del Monte City, Bulacan, Philippines',
			'pac-input2' => 'Our Lady of Fatima University - Valenzuela Campus, MacArthur Highway, Valenzuela, Metro Manila, Philippines',
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
			'f_sender_address' => 'Tierra Benita Subdivision, San Jose del Monte City, Bulacan, Philippines',
			'f_sender_address_lat' => 14.7860947,
			'f_sender_address_lng' => 121.0322675,
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
			'f_recepient_address' => 'Our Lady of Fatima University - Valenzuela Campus, MacArthur Highway, Valenzuela, Metro Manila, Philippines',
			'f_recepient_address_lat' => 14.6778115,
			'f_recepient_address_lng' => 120.9803312,
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
			'f_cargo' => 'Other',
			'f_cargo_others' => 'Other',
			'f_is_cod' => '',
			'f_recepient_cod' => '', // if COD is checked real item price will appear here
			'f_express_fee' => '',
			'f_express_fee_hidden' => 40, // if express fee is checked - toktok fixed 40 pesos fee
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
			// $params['f_duration'] = format_duration($price_and_directions['duration']);
			$params['f_duration'] = $price_and_directions['duration'];
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

		// debug($params, 'stop'); // check parameters
		$this->toktokapi->app_request('post_delivery', $params);
		// STOPPING requests
		$this->toktokapi->stop();
		debug($this->toktokapi, 'stop');
	}

	private function check_delivery($driver_id='', $order_status='', $searchstring='D1F36MSKT4')
	{
		$this->load->library('toktokapi');
		parse_str('draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=1&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=2&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=3&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=4&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=5&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=6&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=7&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=8&columns%5B8%5D%5Bname%5D=&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=false&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=9&columns%5B9%5D%5Bname%5D=&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=DESC&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&date_from=03%2F30%2F2021&date_to=04%2F06%2F2021&driver_id=&order_status=&searchstring='.$searchstring, $output);
		// debug($output, 'stop');

		// CHECKING ORDER BY ID
		$this->toktokapi->app_request('check_orders', $output);
		// debug($this->toktokapi, 'stop');
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

	private function delivery_active_place($value='San Jose del Monte Cit')
	{
		// filter_method: today = 6, yesterday = 7, past 7 days = 8
		// delivery_origin: toktok = 0, toktok food = 1
		// searchstring: <place to search>
		/*GET deliveries today on specific place*/
		$this->load->library('toktokapi');
		$this->toktokapi->app_request('active_places', [
			'filter_method' => '6',
			'delivery_origin' => '0',
			'searchstring' => $value
		]);
		debug($this->toktokapi, 'stop');
	}

}