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
		// $this->load->library('ToktokApi');
		// debug($this->toktokapi, 'stop');
	}

	public function index()
	{
		$filters = [];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$filters['id'] = $this->input->post('ids');
			$filters['user_id'] = $this->input->post('buyer_id');
		}
		$items_by_farm = false;
		$session = get_session_baskets($filters);
		if (count($session)) {
			$items_by_farm = [];
			// debug($session, 'stop');
			/*reassemble data by farm location*/
			foreach ($session as $date => $baskets) {
				foreach ($baskets as $key => $basket) {
					$rawdata = $basket['rawdata'];
					$farm = $rawdata['farm'];
					$basket['rawdata']['product']['farm_location_id'] = $farm['farm_location_id'] = $basket['location_id'];
					unset($basket['rawdata']['farm']);

					$profile = $this->users->get(['id' => $farm['user_id']], true);
					unset($profile['password']); unset($profile['re_password']);
					unset($profile['settings']);
					$farm['profile'] = $profile;

					$address = explode(',', $farm['address_2']);
					$farm['city'] = isset($address[0]) ? $address[0] : '';
					$farm['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';

					$location_and_sched = $basket['location_id'].'|'.$basket['order_type'].'|'.$basket['schedule'];
					$items_by_farm[$location_and_sched]['farm'] = $farm;

					$items_by_farm[$location_and_sched]['products'][] = $basket;
					$items_by_farm[$location_and_sched]['checkout_data'][] = [
						'id' => $basket['id'],
						'order_type' => $basket['order_type'],
						'schedule' => $basket['schedule'],
					];
					$items_by_farm[$location_and_sched]['added'] = $basket['added'];
				}
			}
		}
		// debug($items_by_farm, 'stop');
		if ($this->input->is_ajax_request()) {
			$total_items = 0;
			if ($items_by_farm AND isset($items_by_farm['checkout_data'])) $total_items = count($items_by_farm['checkout_data']);
			echo json_encode(['total_items' => $total_items, 'html' => $this->load->view('templates/basket/basket_items', [
				'data_baskets' => $items_by_farm
			], true)], JSON_NUMERIC_CHECK);
			exit();
		} else {
			$this->render_page([
				'top' => [
					'css' => ['dashboard/main', 'basket/main', 'global/order-table', 'global/plus-minus-input', 'basket/main']
				],
				'middle' => [
					'body_class' => ['dashboard', 'basket'],
					'head' => ['dashboard/navbar'],
					'body' => [
						'dashboard/navbar_aside',
						'basket/b_container',
					],
				],
				'bottom' => [
					'modals' => ['reply_modal'],
					'js' => [
						'plugins/jquery.inputmask.min',
						'plugins/inputmask.binding',
						'plugins/plus_minus_input',
						'dashboard/main',
						'basket/main',
					],
				],
				'data' => [
					'baskets' => $items_by_farm
				],
			]);
		}
	}

	public function add($product_id=0)
	{
		$data = $this->input->post() ?: $this->input->get();
		$post = $this->baskets->prepare_to_basket($data, $product_id);
		// debug($data, $post, 'stop');
		if ($post) {
			if ($this->input->get('callback') == 'gmCall') { /*from add to basket button*/
				$post['status'] = GM_VERIFIED_SCHED;
			} else { /*from checkout button*/
				$post['status'] = GM_VERIFIED_NOW;
			}
			/*check if the user is logged in if false save post to session*/
			if ($this->accounts->has_session == false) {
				$this->session->set_userdata('basket_session', ['baskets'=>$post]);
				$message = 'Item added to basket, Redirecting to the registration / login page!';
				$redirect = base_url('register');
				$callback = false;
			} else {
				if ($post['existing'] == 1) {
					unset($post['existing']);
					$this->gm_db->save('baskets', $post, ['id' => $post['id']]);
				} else {
					unset($post['existing']);
					$post['id'] = $this->gm_db->new('baskets', $post);
				}
				$other_orders = $this->gm_db->get('baskets', [
					'user_id' => $post['user_id'],
					'order_type' => $post['order_type'],
					'at_date' => $post['at_date'],
				]);
				// debug($other_orders, 'stop');
				$hash = '';
				$basket_ids = [$post['id']];
				if ($other_orders) {
					foreach ($other_orders as $other) $basket_ids[$other['id']] = $other['id'];
				}
				$hash = (base64_encode(json_encode($basket_ids, JSON_NUMERIC_CHECK)));
				$message = $post['status'] ? 'Item added into your basket!, Proceeding checkout' : 'Item added to basket! <a href="basket/">Check here</a>';
				$redirect = ($post['status'] == GM_VERIFIED_NOW) ? base_url('basket/checkout/'.$hash) : false;
				$callback = ($post['status'] == GM_VERIFIED_NOW) ? false : 'stockChanged';;
				$post['rawdata'] = json_decode(base64_decode($post['rawdata']), true);

				/*send realtime basket*/
				$this->senddataapi->trigger('add-to-basket', 'incoming-baskets', [
					'success' => true, 'ids' => $basket_ids, 'buyer_id' => $this->accounts->profile['id']
				]);
				$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
					'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'basket', 'total_items' => $this->gm_db->count('baskets', ['user_id' => $this->accounts->profile['id'], 'status' => [0,1]])
				]);
			}
			// debug($this->gm_db->get_or_in('baskets', ['id'=>$post['id'], 'order_type'=>$post['order_type'], 'status'=>[0,1]]), 'stop');
			// debug($post, 'stop');

			$this->set_response('success', $message, ['baskets'=>$post], $redirect, $callback, true);
		}
		$this->set_response('error', 'No item(s) found', ['baskets'=>$post], false);
	}

	public function view($product_id=0, $farm_location_id=0, $product_name='')
	{
		$product = false;
		if ($product_id AND $farm_location_id) {
			// debug($product_id, $farm_location_id, 'stop');
			$product = $this->products->product_by_farm_location($product_id, $farm_location_id);
		}
		// debug($product, 'stop');
		if ($product == false) {
			show_404();
			// redirect(base_url('/'));
		}
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
				'modals' => ['login_modal'],
				'js' => ['basket/productpage'],
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
				$checkout_pricing = $this->session->userdata('checkout_pricing_'.$row['location_id']);
				if ($checkout_pricing) {
					$this->session->unset_userdata('checkout_pricing_'.$row['location_id']);
				}
				unset($row['location_id']);
				$this->baskets->save(['status' => GM_ITEM_REMOVED], $row); /*removed*/
			}
			$this->set_response('success', 'Product removed in basket', $post['data'], false, 'removeOnBasket');
		} elseif ($get AND isset($get['data'])) {
			// debug($get, 'stop');
			$this->set_response('confirm', 'Want to remove product(s)?', $get['data'], false, 'removeBasketItem');
		}
		$this->set_response('error', remove_multi_space('Unable to remove product(s)'), $post);
	}

	public function verify($is_checkout=0)
	{
		$post = $this->input->post();
		if ($post AND isset($post['data'])) {
			// debug($post, 'stop');
			$basket_ids = [];
			foreach ($post['data'] as $key => $row) {
				$id = $row['id']; unset($row['id']);
				$basket = $this->baskets->get(['id' => $id], true);
				// debug($basket, 'stop');
				if ($basket) {
					$row['status'] = 1;
					if ($row['order_type'] == 1) $row['schedule'] = NULL;
					$this->baskets->save($row, ['id' => $id]);
					$basket_ids[] = $id;
				}
			}
			echo json_encode(['success' => true, 'type' => 'success', 'redirect' => 'basket/checkout/'.(base64_encode(json_encode($basket_ids))), 'message' => 'Basket verified!, Proceeding checkout'], JSON_NUMERIC_CHECK); exit();
		} else {
			echo json_encode(['success' => true, 'type' => 'error', 'redirect' => false, 'message' => 'Unable to proceed for checkout!'], JSON_NUMERIC_CHECK);
			exit();
		}
	}

	public function checkout($base64_basket_ids=false)
	{
		if ($base64_basket_ids) {
			$ids = json_decode(base64_decode($base64_basket_ids), true);
			// debug(array_unique($ids), 'stop');
			$where = ['id' => array_unique($ids), 'status' => 1];
			// $baskets = $this->baskets->get_in($where);
			$baskets = get_session_baskets($where);
			// debug($where, $baskets, 'stop');
			$this->checkout_handler($baskets);
		} else {
			redirect(base_url('basket/?info=Nothing+to+Checkout'));
		}
	}

	private function checkout_handler($basket_session=false)
	{
		// debug($basket_session, 'stop');
		if ($basket_session) {
			$items_by_farm = [];
			foreach ($basket_session as $date => $baskets) {
				foreach ($baskets as $key => $basket) {
					$scheduled_value = 'SCHEDULED';
					if ($basket['order_type'] == 2) {
						if (!is_null($basket['schedule'])) {
							$scheduled_value .= ' | '.date('F j, Y', strtotime($basket['schedule']));
						} else {
							continue;
						}
					}
					// $this->session->unset_userdata('checkout_pricing_'.$basket['location_id']);
					$farm = $basket['rawdata']['farm'];
					unset($basket['rawdata']['farm']);
					$seller = $items_by_farm[$basket['location_id']]['seller'] = $farm;
					$checkout_pricing = $this->session->userdata('checkout_pricing_'.$basket['location_id']);
					if (empty($checkout_pricing)) {
						$this->load->library('ToktokApi');
						/*get toktok fee if not existing in baskets table*/
						$pricing = toktok_price_directions_format([
							'sender_lat' => $seller['lat'],
							'sender_lng' => $seller['lng'],
							'receiver_lat' => $this->latlng['lat'],
							'receiver_lng' => $this->latlng['lng'],
						]);
						$this->toktokapi->app_request('price_and_directions', $pricing);
						// debug($this->toktokapi, 'stop');
						if ($this->toktokapi->success) {
							$checkout_pricing = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections'];
							// $checkout_pricing['pricing']['price'];
							// $checkout_pricing['hash'];
							unset($checkout_pricing['directions']);
							$this->session->set_userdata('checkout_pricing_'.$basket['location_id'], $checkout_pricing);
						}
					}
					$type_name = $basket['order_type'] == 1 ? 'TODAY' : $scheduled_value;
					$items_by_farm[$basket['location_id']]['order_type'] = ['id'=>$basket['order_type'], 'type_name'=>$type_name];
					$items_by_farm[$basket['location_id']]['order_details'][$basket['order_type']][] = $basket;
					$items_by_farm[$basket['location_id']]['toktok_details'] = $checkout_pricing;
				}
			}
			// debug($items_by_farm, 'stop');
			if (count($items_by_farm)) {
				$this->session->set_userdata('place_order_session', $items_by_farm);
			}
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

	public function place_order()
	{
		$place_order_session = $this->session->userdata('place_order_session');
		// debug($place_order_session, 'stop');
		$place_order = $basket_ids = $all_basket_ids = $farm_location_ids = $seller_ids = $order_ids = false;
		$final_total = 0;
		if ($place_order_session) {
			$place_order = $farm_location_ids = $seller_ids = $order_ids = [];
			$timestamp = strtotime(date('Y-m-d H:i:s'));

			foreach ($place_order_session as $farm_location_id => $session) {
				$place_order[$farm_location_id]['basket_ids'] = [];

				$seller = $session['seller'];
				$seller_ids[] = $seller['user_id'];
				$profile = $this->users->get(['id' => $seller['user_id']], true);
				unset($profile['password']); unset($profile['re_password']);
				unset($profile['settings']); unset($profile['shippings']);
				$seller['profile'] = $profile;

				$address = explode(',', $seller['address_2']);
				$seller['city'] = isset($address[0]) ? $address[0] : '';
				$seller['city_prov'] = (isset($address[0]) AND isset($address[1])) ? $address[0] .','. $address[1] : '';
				$seller['farm_location_id'] = $farm_location_id;
				
				$session['seller'] = $place_order[$farm_location_id]['seller'] = $seller;

				$buyer = $this->accounts->profile;
				unset($buyer['farms']); unset($buyer['farm_locations']);
				unset($buyer['categories']); unset($buyer['subcategories']);
				unset($buyer['measurements']); unset($buyer['galleries']);
				unset($buyer['attributes']); unset($buyer['settings']);
				$place_order[$farm_location_id]['buyer'] = $buyer;

				$pricing = $session['toktok_details']['pricing'];
				$fee = (float)$pricing['price'];
				// $session['mobile'] = '80109'; /*test rider id*/
				$hash = $session['hash'] = $session['toktok_details']['hash'];
				$toktok_post = toktok_post_delivery_format($session);

				$initial_total = 0;
				foreach ($session['order_details'] as $order_type => $orders) {
					$toktok_post['f_order_type_send'] = $toktok_post['f_order_type_rec'] = $order_type;

					foreach ($orders as $key => $order) {
						$sub_total = $order['quantity'] * $order['rawdata']['details']['price'];
						$product = $order['rawdata']['product'];
						$product['farm_location_id'] = $farm_location_id;
						$place_order[$farm_location_id]['order_details'][] = [
							'product' => $product,
							'product_id' => $order['product_id'],
							'farm_location_id' => $farm_location_id,
							'quantity' => $order['quantity'],
							'price' => $order['rawdata']['details']['price'],
							'measurement' => $order['rawdata']['details']['measurement'],
							'stocks' => $order['rawdata']['details']['stocks'],
							'sub_total' => $sub_total,
							'timestamp' => $timestamp,
							'status' => 2,
							'when' => $order_type,
							'schedule' => $order['schedule'],
							'basket_id' => $order['id'],
						];
						$place_order[$farm_location_id]['basket_ids'][] = $order['id'];
						$all_basket_ids[] = $order['id'];
						$initial_total += $sub_total;

						if ($order_type == 2 AND (empty($toktok_post['f_sender_date']) AND empty($toktok_post['f_recepient_date']))) {
							$receive_time = date('H:i:s', strtotime('+1 hour'));

							$toktok_post['f_sender_date'] = date('d/m/Y', strtotime($order['schedule']));
							$toktok_post['f_sender_datetime_from'] = date('H:i:s');
							$toktok_post['f_sender_datetime_to'] = $receive_time;

							$toktok_post['f_recepient_date'] = date('d/m/Y', strtotime($order['schedule']));
							$toktok_post['f_recepient_datetime_from'] = $receive_time;
							$toktok_post['f_recepient_datetime_to'] = date('H:i:s', strtotime('+'.((float)$pricing['duration'] + 60).' minute'));
						}
					}
				}

				$toktok_post['f_recepient_cod'] = $initial_total + $fee;
				$final_total += $toktok_post['f_recepient_cod'];

				$basket_ids = $place_order[$farm_location_id]['basket_ids'];
				$place_order[$farm_location_id]['basket_ids'] = implode(',', $basket_ids);
				$order_ids[] = $place_order[$farm_location_id]['order_id'] = strtoupper(substr(md5(implode(',', $basket_ids)), 0, 10));
				$toktok_post['f_recepient_notes'] = 'GulayMart Order#:'.$place_order[$farm_location_id]['order_id'];
				$place_order[$farm_location_id]['fee'] = $fee;
				$place_order[$farm_location_id]['distance'] = $pricing['distance'];
				$place_order[$farm_location_id]['duration'] = $pricing['duration'];

				$place_order[$farm_location_id]['location_id'] = $farm_location_id;
				$place_order[$farm_location_id]['status'] = 2;
				$place_order[$farm_location_id]['toktok_post'] = base64_encode(json_encode($toktok_post, JSON_NUMERIC_CHECK));
				$place_order[$farm_location_id]['seller'] = base64_encode(json_encode($place_order[$farm_location_id]['seller'], JSON_NUMERIC_CHECK));
				$place_order[$farm_location_id]['seller_id'] = $seller['user_id'];
				$place_order[$farm_location_id]['buyer'] = base64_encode(json_encode($place_order[$farm_location_id]['buyer'], JSON_NUMERIC_CHECK));
				$place_order[$farm_location_id]['buyer_id'] = $buyer['id'];
				$place_order[$farm_location_id]['order_details'] = base64_encode(json_encode($place_order[$farm_location_id]['order_details'], JSON_NUMERIC_CHECK));

				$farm_location_ids[] = $farm_location_id;
			}
		}

		// debug($place_order, 'stop');
		if ($place_order AND $all_basket_ids) {
			$merge_ids = [];
			foreach ($place_order as $data) {
				$merge_ids[] = $this->baskets->new_baskets_merge($data);
			}

			/*email and add notification here*/
			notify_placed_orders($final_total, $merge_ids, $seller_ids, $this->accounts->profile);

			// debug($place_order, $all_basket_ids, 'stop');
			foreach ($all_basket_ids as $id) $this->baskets->save(['status' => 2], ['id' => $id]);
			foreach ($farm_location_ids as $location_id) $this->session->unset_userdata('checkout_pricing_'.$location_id);
			$this->session->unset_userdata('place_order_session');
			$this->session->set_userdata('typage_session', $order_ids);

			/*send realtime placed order*/
			$this->senddataapi->trigger('placed-order', 'incoming-orders', [
				'success' => true, 'ids' => $merge_ids, 'buyer_id' => $this->accounts->profile['id'], 'event' => 'placed', 'remove' => false
			]);
			/*send realtime placed fulfillment*/
			$this->senddataapi->trigger('placed-fulfillment', 'incoming-fulfillment', [
				'success' => true, 'ids' => $merge_ids, 'seller_id' => $seller_ids, 'event' => 'placed', 'remove' => false
			]);

			$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
				'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'basket', 'total_items' => $this->gm_db->count('baskets', ['user_id' => $this->accounts->profile['id'], 'status' => [0,1]])
			]);
			$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
				'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'order', 'total_items' => $this->gm_db->count('baskets_merge', ['buyer_id' => $this->accounts->profile['id'], 'status !=' => 5])
			]);
			$this->senddataapi->trigger('count-item-in-menu', 'incoming-menu-counts', [
				'success' => true, 'id' => $this->accounts->profile['id'], 'nav' => 'fulfill', 'total_items' => $this->gm_db->count('baskets_merge', ['seller_id' => $this->accounts->profile['id'], 'status !=' => 5])
			]);

			$this->set_response('success', 'Orders have been Placed!', false, 'orders/thank-you/');
		} else {
			$this->set_response('info', 'No orders to be place, Redirecting to your basket...', false, 'basket/');
		}
	}

	private function book_delivery()
	{
		// DELIVERY POSTING
		$this->load->library('ToktokApi');
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

			$params['f_post'] = json_encode(['hash' => $hash], JSON_NUMERIC_CHECK);
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

	private function check_delivery($driver_id='', $order_status='', $searchstring='D1F2444PJ8')
	{
		$this->load->library('ToktokApi');
		parse_str('draw=1&columns%5B0%5D%5Bdata%5D=0&columns%5B0%5D%5Bname%5D=&columns%5B0%5D%5Bsearchable%5D=true&columns%5B0%5D%5Borderable%5D=true&columns%5B0%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B0%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B1%5D%5Bdata%5D=1&columns%5B1%5D%5Bname%5D=&columns%5B1%5D%5Bsearchable%5D=true&columns%5B1%5D%5Borderable%5D=true&columns%5B1%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B1%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B2%5D%5Bdata%5D=2&columns%5B2%5D%5Bname%5D=&columns%5B2%5D%5Bsearchable%5D=true&columns%5B2%5D%5Borderable%5D=true&columns%5B2%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B2%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B3%5D%5Bdata%5D=3&columns%5B3%5D%5Bname%5D=&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&columns%5B3%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B3%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B4%5D%5Bdata%5D=4&columns%5B4%5D%5Bname%5D=&columns%5B4%5D%5Bsearchable%5D=true&columns%5B4%5D%5Borderable%5D=true&columns%5B4%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B4%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B5%5D%5Bdata%5D=5&columns%5B5%5D%5Bname%5D=&columns%5B5%5D%5Bsearchable%5D=true&columns%5B5%5D%5Borderable%5D=true&columns%5B5%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B5%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B6%5D%5Bdata%5D=6&columns%5B6%5D%5Bname%5D=&columns%5B6%5D%5Bsearchable%5D=true&columns%5B6%5D%5Borderable%5D=true&columns%5B6%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B6%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B7%5D%5Bdata%5D=7&columns%5B7%5D%5Bname%5D=&columns%5B7%5D%5Bsearchable%5D=true&columns%5B7%5D%5Borderable%5D=false&columns%5B7%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B7%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B8%5D%5Bdata%5D=8&columns%5B8%5D%5Bname%5D=&columns%5B8%5D%5Bsearchable%5D=true&columns%5B8%5D%5Borderable%5D=false&columns%5B8%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B8%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B9%5D%5Bdata%5D=9&columns%5B9%5D%5Bname%5D=&columns%5B9%5D%5Bsearchable%5D=true&columns%5B9%5D%5Borderable%5D=false&columns%5B9%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B9%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B10%5D%5Bdata%5D=10&columns%5B10%5D%5Bname%5D=&columns%5B10%5D%5Bsearchable%5D=true&columns%5B10%5D%5Borderable%5D=false&columns%5B10%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B10%5D%5Bsearch%5D%5Bregex%5D=false&columns%5B11%5D%5Bdata%5D=11&columns%5B11%5D%5Bname%5D=&columns%5B11%5D%5Bsearchable%5D=true&columns%5B11%5D%5Borderable%5D=false&columns%5B11%5D%5Bsearch%5D%5Bvalue%5D=&columns%5B11%5D%5Bsearch%5D%5Bregex%5D=false&order%5B0%5D%5Bcolumn%5D=0&order%5B0%5D%5Bdir%5D=DESC&start=0&length=10&search%5Bvalue%5D=&search%5Bregex%5D=false&date_from=03%2F30%2F2021&date_to=04%2F06%2F2021&driver_id='.$driver_id.'&order_status='.$order_status.'&searchstring='.$searchstring, $output);
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

	private function delivery_active_place($value='San Jose del Monte City')
	{
		// filter_method: today = 6, yesterday = 7, past 7 days = 8
		// delivery_origin: toktok = 0, toktok food = 1
		// searchstring: <place to search>
		/*GET deliveries today on specific place*/
		$this->load->library('ToktokApi');
		$this->toktokapi->app_request('active_places', [
			'filter_method' => '6',
			'delivery_origin' => '0',
			'searchstring' => $value
		]);
		debug($this->toktokapi, 'stop');
	}

}