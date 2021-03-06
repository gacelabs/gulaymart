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
		$filters = $ids = [];
		if ($this->input->is_ajax_request() AND $this->input->post('ids')) {
			$ids = is_array($this->input->post('ids')) ? array_values($this->input->post('ids')) : $this->input->post('ids');
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
					if (empty($farm['name'])) {
						$user_farms = $this->gm_db->get('user_farms', ['id' => $farm['id']], 'row');
						$farm['name'] = $user_farms ? $user_farms['name'] : '';
					}
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
					if (!isset($items_by_farm[$location_and_sched]['updated'])) {
						$items_by_farm[$location_and_sched]['updated'] = $basket['updated'];
					}
					$items_by_farm[$location_and_sched]['basket_ids'][] = $basket['id'];
				}
			}
		}
		// debug($items_by_farm, 'stop');
		if ($this->input->is_ajax_request()) {
			$html = $html_ids = [];
			if (!empty($items_by_farm)) {
				foreach ($items_by_farm as $location_by_order_type => $basket_items) {
					$key_data = explode('|', $location_by_order_type);
					$location_id = $key_data[0];
					$order_type = $key_data[1];
					$basket_ids = $basket_items['basket_ids'];
					asort($basket_ids);
					$combi = " id-".implode("-item id-", $basket_ids)."-item";
					$html_ids[] = $attr = "[id-".implode("-item],[id-", $basket_ids)."-item]";
					$schedule = (!is_null($key_data[2]) AND $key_data[2] == '0000-00-00') ? '' : $key_data[2];
					$row_data = [
						'id_combi' => $combi,
						'baskets' => $basket_items,
						'location_id' => $location_id,
						'order_type' => $order_type,
						'schedule' => $schedule,
					];
					$html[trim($attr)] = $this->load->view('templates/basket/basket_items', $row_data, true);
				}
			} else if (!empty($ids)) {
				if (!is_array($ids)) $ids = [$ids];
				asort($ids);
				$html_ids[] = "[id-".implode("-item],[id-", $ids)."-item]";
			}
			// debug($html, 'stop');
			echo json_encode(['html' => $html, 'basket_ids' => array_unique($html_ids), 'panel' => 'basket'], JSON_NUMERIC_CHECK);
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

	public function add($product_id=0, $is_test=0)
	{
		$data = $this->input->post() ?: $this->input->get();
		$check = $this->gm_db->get_in('products', ['id' => $product_id, 'activity' => [GM_ITEM_REJECTED, GM_ITEM_DELETED, GM_ITEM_NO_INVENTORY]]);
		// debug($data, $check, 'stop');
		if ($check) { /*if product was already deactivated*/
			$this->set_response('error', 'Sorry, No more stocks available for this product!', $data, false, 'removeAddButtons');
		} else {
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
					$message = 'Item added to basket, Redirecting to the registration or login page!';
					$redirect = base_url('register');
					$callback = false;
				} else {
					unset($post['added']); unset($post['updated']);
					if ($post['existing'] == 1) {
						unset($post['existing']);
						$this->gm_db->save('baskets', $post, ['id' => $post['id']]);
					} else {
						unset($post['existing']);
						$post['id'] = $this->gm_db->new('baskets', $post);
					}
					$other_orders = $this->gm_db->get('baskets', [
						'status' => $post['status'],
						'user_id' => $post['user_id'],
						'order_type' => $post['order_type'],
						'at_date' => $post['at_date'],
					]);
					// debug($other_orders, 'stop');
					$hash = '';
					$basket_ids = [$post['id']];
					if ($other_orders) {
						foreach ($other_orders as $other) $basket_ids[] = $other['id'];
					}
					$hash = (base64_encode(json_encode(array_unique($basket_ids), JSON_NUMERIC_CHECK)));
					$message = $post['status'] == GM_VERIFIED_SCHED ? 'Item added to basket! <a href="basket/">Check it here</a>' : 'Item added into your basket!, Proceeding checkout';
					$redirect = ($post['status'] == GM_VERIFIED_NOW) ? base_url('basket/checkout/'.$hash) : false;
					$callback = ($post['status'] == GM_VERIFIED_NOW) ? false : 'stockChanged';;
					$post['rawdata'] = json_decode(base64_decode($post['rawdata']), true);

					/*send realtime basket*/
					$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['basket_id' => array_unique($basket_ids)]);
				}
				// debug($this->gm_db->get_or_in('baskets', ['id'=>$post['id'], 'order_type'=>$post['order_type'], 'status'=>[0,1]]), 'stop');
				// debug($post, 'stop');

				if ($is_test) {
					redirect(base_url('test/send/tambay'));
				} else {
					$this->set_response('success', $message, ['baskets'=>$post], $redirect, $callback, true);
				}
			}
			$this->set_response('error', 'No item(s) found', ['baskets'=>$post], false);
		}
	}

	public function view($product_id=0, $farm_location_id=0, $product_name=false)
	{
		$product = false;
		if ($product_id AND $farm_location_id) {
			// debug($product_id, $farm_location_id, 'stop');
			$product = $this->products->product_by_farm_location($product_id, $farm_location_id);
			if ($product_name == false AND $product) {
				redirect(base_url('basket/view/'.$product['id'].'/'.$product['farm_location']['id'].'/'.$product['name']));
			}
		}
		// debug($product, 'stop');
		if ($product == false) show_404();
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

	public function verify($is_not_test=1)
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post AND isset($post['data'])) {
			// debug($post, 'stop');
			$basket_ids = [];
			foreach ($post['data'] as $key => $row) {
				$id = $row['id']; unset($row['id']);
				$basket = $this->baskets->get(['id' => $id], true);
				// debug($basket, 'stop');
				if ($basket) {
					$row['status'] = GM_VERIFIED_NOW;
					if ($row['order_type'] == GM_BUY_NOW) $row['schedule'] = NULL;
					$this->baskets->save($row, ['id' => $id]);
					$basket_ids[] = $id;
				}
			}
			if ($is_not_test == 0) {
				redirect(base_url('basket/checkout/'.(base64_encode(json_encode($basket_ids)))));
			} else {
				echo json_encode(['success' => true, 'type' => 'success', 'redirect' => 'basket/checkout/'.(base64_encode(json_encode($basket_ids))), 'message' => 'Basket verified!, Proceeding checkout'], JSON_NUMERIC_CHECK); exit();
			}
		} else {
			echo json_encode(['success' => true, 'type' => 'error', 'redirect' => false, 'message' => 'Unable to proceed for checkout!'], JSON_NUMERIC_CHECK);
			exit();
		}
	}

	public function checkout($base64_basket_ids=false, $multi=0)
	{
		if ($base64_basket_ids) {
			$ids = json_decode(base64_decode($base64_basket_ids), true);
			if (empty($ids)) $ids = [0]; 
			$baskets = $this->baskets->get_in(['status' => [GM_VERIFIED_SCHED, GM_VERIFIED_NOW]]);
			if ($baskets AND $multi == 0) {
				$base64_basket_ids = $this->gm_db->columns('id', $baskets);
				$new_ids = array_unique(array_merge($ids, $base64_basket_ids));
				$ids = array_values($new_ids);
				redirect(base_url('basket/checkout/'.(base64_encode(json_encode($ids))).'/1'));
			}
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
					if ($basket['order_type'] == GM_SCHEDULED) {
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
					$type_name = $basket['order_type'] == GM_BUY_NOW ? 'TODAY' : $scheduled_value;
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
			$this->set_response('info', 'No more Orders to Checkout!', false, 'basket/');
		}
	}

	public function place_order()
	{
		$place_order_session = $this->session->userdata('place_order_session');
		/*unset it, run once only*/
		$this->session->unset_userdata('place_order_session');
		sleep(3);
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
							'status' => GM_PLACED_STATUS,
							'when' => $order_type,
							'schedule' => $order['schedule'],
							'basket_id' => $order['id'],
						];
						$place_order[$farm_location_id]['basket_ids'][] = $order['id'];
						$all_basket_ids[] = $order['id'];
						$initial_total += $sub_total;

						if ($order_type == GM_SCHEDULED AND (empty($toktok_post['f_sender_date']) AND empty($toktok_post['f_recepient_date']))) {
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
				$place_order[$farm_location_id]['status'] = GM_PLACED_STATUS;
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

			$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge_ids]);

			// debug($place_order, $all_basket_ids, 'stop');
			foreach ($all_basket_ids as $id) $this->baskets->save(['status' => GM_PLACED_STATUS], ['id' => $id]);
			foreach ($farm_location_ids as $location_id) $this->session->unset_userdata('checkout_pricing_'.$location_id);
			$this->session->set_userdata('typage_session', $order_ids);

			$this->set_response('success', 'Orders have been Placed!', false, 'orders/thank-you/');
		} else {
			$this->set_response('info', 'No orders to be place, Redirecting to your basket...', false, 'basket/');
		}
	}

}