<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Basket extends My_Controller {

	public $allowed_methods = 'all';

	public function __construct()
	{
		parent::__construct();
		/*requesting samples to toktok portal*/
		$this->samples();
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

	private function samples()
	{
		// INITIALIZING TOKTOK OBJECT
		$this->load->library('toktokapi');
		// debug($this->toktokapi, 'stop');

		parse_str('term=9614068479&_type=query&q=9614068479', $rider);
		$this->toktokapi->app_request('rider', $rider);
		// debug($this->toktokapi->response, 'stop');
		$driver_id = $this->toktokapi->response['results'][0]['id'];

		// request PRICING
		parse_str('f_sender_lat=14.7884463&f_sender_lon=121.0283154&f_promo_code=&destinations[0][recipient_lat]=14.7803604&destinations[0][recipient_lon]=120.9768949&isExpress=false&isCashOnDelivery=false', $output);
		$this->toktokapi->app_request('pricing', $output);
		$hash = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['hash'];
		$pricing = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections']['pricing'];
		// debug($driver_id, $hash, $pricing, 'stop');

		// request DELIVERY POSTING
		parse_str('f_id=&f_post=&pac-input=Antipolo+Cathedral%2C+Dela+Paz+Street%2C+Antipolo%2C+Rizal%2C+Philippines&pac-input2=Antipolo+City+Hall%2C+M.+Santos+Street%2C+Antipolo%2C+Rizal%2C+Philippines&f_distance=&f_duration=&f_price=&f_distance_hidden=&f_duration_hidden=&f_driver_id=&f_promo_code=&f_promo_error=&f_some_error=&f_sender_name=Fr. Fernando&f_sender_mobile=09123456789&f_sender_landmark=Market&f_sender_address=Antipolo+Cathedral%2C+Dela+Paz+Street%2C+Antipolo%2C+Rizal%2C+Philippines&f_sender_address_lat=14.7884463&f_sender_address_lng=121.0283154&f_order_type_send=1&f_sender_date=&f_sender_datetime_from=&f_sender_datetime_to=&f_sen_add_in_city=&f_sen_add_in_pro=&f_sen_add_in_reg=&f_sen_add_in_coun=&f_recepient_name=Fr. Emmanuel&f_recepient_mobile=09198765432&f_recepient_landmark=Church&f_recepient_address=Antipolo+City+Hall%2C+M.+Santos+Street%2C+Antipolo%2C+Rizal%2C+Philippines&f_recepient_address_lat=14.7803604&f_recepient_address_lng=120.9768949&f_order_type_rec=1&f_recepient_date=&f_recepient_datetime_from=&f_recepient_datetime_to=&f_rec_add_in_city=&f_rec_add_in_pro=&f_rec_add_in_reg=&f_rec_add_in_coun=&f_collectFrom=S&f_recepient_notes=Please+keep+hot&f_cargo=Food&f_cargo_others=Food&f_recepient_cod=&f_express_fee_hidden=40.00', $params);
		/*paliatan dito*/
		$params['f_post'] = json_encode(['hash' => $hash]);
		$params['f_driver_id'] = $driver_id;
		$params['f_distance'] = $pricing['distance']. ' km';
		$params['f_duration'] = $pricing['duration']. ' minutes';
		$params['f_price'] = $pricing['price'];
		$params['pac-input'] = 'Harmony Hills I Subdivision, Harmony Hills, 1, San Jose del Monte City, Bulacan, Philippines';
		$params['pac-input2'] = 'R-Twin Trading, M. Villarica Road, Sta. Rosa, Marilao City, Bulacan, Philippines';
		$params['f_sender_name'] = 'Carriza';
		$params['f_sender_landmark'] = 'Aling Nena Store';
		$params['f_sender_address'] = 'Harmony Hills I Subdivision, Harmony Hills, 1, San Jose del Monte City, Bulacan, Philippines';
		$params['f_recepient_name'] = 'Evita Holmes';
		$params['f_recepient_landmark'] = 'Repair shop';
		$params['f_recepient_address'] = 'R-Twin Trading, M. Villarica Road, Sta. Rosa, Marilao City, Bulacan, Philippines';

		// debug(http_build_query($params), $params, $this->toktokapi, 'stop');
		$this->toktokapi->app_request('post_delivery', $params/*, 'website'*/);
		// STOPPING requests
		$this->toktokapi->stop();
		debug($this->toktokapi, 'stop');
	}
}