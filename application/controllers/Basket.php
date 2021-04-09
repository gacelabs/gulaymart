<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends My_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
		// INITIALIZING TOKTOK OBJECT
		// $this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');
	}

	public function index()
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' is your neighborhood veggies supplier.',
					'name' => 'Product Name -'.APP_NAME,
				],
				'index_page' => 'yes',
				'page_title' => 'Product Name -'.APP_NAME,
				'css' => ['marketplace', 'productpage'],
			],
			'middle' => [
				'body' => [
					'marketplace/navbar',
					'productpage/top',
					'productpage/middle',
					'productpage/banner'
				],
				'footer' => [
					'static/footer'
				],
			],
			'bottom' => [
				'js' => ['productpage'],
			],
			'data' => [
			],
		]);
	}

	public function book_delivery()
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
			'f_collectFrom' => 'S',
			'f_recepient_notes' => 'Please keep hot',
			'f_cargo' => 'Food',
			'f_cargo_others' => 'Food',
			'f_is_cod' => '',
			'f_recepient_cod' => '',
			'f_express_fee' => '',
			'f_express_fee_hidden' => 40.00,
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
		// if COD is checked
		if ($params['f_is_cod'] == 'on') {
			$params['f_collectFrom'] = 'R';
		}

		debug($params, 'stop'); // check parameters
		$this->toktokapi->app_request('post_delivery', $params);
		// STOPPING requests
		$this->toktokapi->stop();
		debug($this->toktokapi, 'stop');
	}

	public function check_delivery($driver_id='', $order_status='', $searchstring='D1F2444PJ8')
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