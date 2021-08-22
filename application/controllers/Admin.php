<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public $allowed_methods = ['push_orders_to_toktok', 'orders_to_receive'];
	public $not_allowed_methods = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('baskets');
		$this->load->library('accounts');
		if ($this->accounts->has_session AND $this->accounts->profile['is_admin'] != 1) {
			redirect(base_url('profile'));
		}
	}

	public function index()
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' - Admin',
					'name' => APP_NAME.' | Admin',
				],
				'index_page' => 'no',
				'page_title' => APP_NAME.' | Admin',
				'css' => ['admin/main'],
			],
			'middle' => [
				'body_class' => ['admin-stats'],
				'head' => [
					'../global/global_navbar',
					'admin/navbar'
				],
				'body' => [
					'admin/statistics'
				],
				'footer' => [
					'global/footer'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => ['admin/main'],
			],
			'data' => [
				'users_count' => $this->gm_db->count('users', ['is_admin' => 1]),
				'farmers_count' => $this->gm_db->count('user_farms', ['user_id >' => 0]),
				'bookings_count' => [
					'succeeded' => $this->gm_db->count('baskets_merge', ['status' => GM_RECEIVED_STATUS, 'is_sent' => 1]),
					'failed' => $this->gm_db->count('baskets_merge', ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 2]),
				],
			],
		]);
	}

	public function stats($mode=false)
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post) {
			// debug($post, 'stop');
			if (method_exists($this, $mode)) {
				$tables = explode(',', $post['tables']);
				$results = $this->$mode($post, $tables, __FUNCTION__);
				$this->set_response('success', '', $results, false, 'drawData'.ucfirst($mode));
			}
		}
		$this->set_response('error', remove_multi_space('Admin method '.$mode.' does not exist!', true), $post, false);
	}

	public function bookings($mode=false)
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post) {
			if ($mode == 'updatedLogs') {
				$post['logs'] = print_cron_log($post['name'], false, $post['date']);
				$post['next'] = date('g:i:s a', (time() + CRON_RERUN_SECONDS));
				$this->set_response('success', '', $post, false, 'drawData'.ucfirst($mode));
			} else {
				// debug($post, 'stop');
				if (method_exists($this, $mode)) {
					$tables = isset($post['tables']) ? explode(',', $post['tables']) : false;
					$results = $this->$mode($post, $tables, __FUNCTION__);
					$this->set_response('success', '', $results, false, 'drawData'.ucfirst($mode));
				}
			}
			$this->set_response('error', remove_multi_space('Admin method '.$mode.' does not exist!', true), $post, false);
		} else {
			$admin_settings = $this->gm_db->get('admin_settings');
			// debug($admin_settings, true);
			$this->render_page([
				'top' => [
					'metas' => [
						'description' => APP_NAME.' - Admin',
						'name' => APP_NAME.' | Admin',
					],
					'index_page' => 'no',
					'page_title' => APP_NAME.' | Admin',
					'css' => ['admin/main'],
				],
				'middle' => [
					'body_class' => ['admin-bookings'],
					'head' => [
						'../global/global_navbar',
						'admin/navbar'
					],
					'body' => [
						'admin/bookings'
					],
					'footer' => [
						'global/footer'
					],
				],
				'bottom' => [
					'modals' => [],
					'js' => ['hideshow', 'admin/main', 'admin/bookings'],
				],
				'data' => [
					'settings' => $admin_settings,
					'bookings_count' => [
						'succeeded' => $this->gm_db->count('baskets_merge', ['status' => GM_RECEIVED_STATUS, 'is_sent' => 1, 'operator' => -1]),
						'manual' => $this->gm_db->count('baskets_merge', ['status' => GM_RECEIVED_STATUS, 'is_sent' => 1, 'operator >' => 0]),
						'failed' => $this->gm_db->count('baskets_merge', ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 2]),
					],
				],
			]);
		}
	}

	private function counts($post=false, $tables=false, $function=false)
	{
		$results = [];
		if ($post AND $tables) {
			$where = format_time_label($post);
			if ($function == 'stats') {
				$users_where = $farmers_where = $where;
				$users_where['is_admin'] = 1;
				$farmers_where['user_id >'] = 0;
				$results = [
					'users-count' => $this->gm_db->count('users', $users_where),
					'farmers-count' => $this->gm_db->count('user_farms', $farmers_where),
				];
			} elseif ($function == 'bookings') {
				$succeed_where = $manual_where = $failed_where = $where;

				$succeed_where['status'] = GM_RECEIVED_STATUS;
				$succeed_where['is_sent'] = 1;
				$manual_where['status'] = GM_RECEIVED_STATUS;
				$manual_where['is_sent'] = 1;
				$manual_where['operator >'] = 0;
				$failed_where['status'] = GM_ON_DELIVERY_STATUS;
				$failed_where['is_sent'] = 0;

				$results = [
					'bookings-succeeded' => $this->gm_db->count('baskets_merge', $succeed_where),
					'bookings-manualed' => $this->gm_db->count('baskets_merge', $manual_where),
					'bookings-failed' => $this->gm_db->count('baskets_merge', $failed_where),
				];
			}
		}
		return $results;
	}

	private function automation($post=false)
	{
		if ($post AND isset($post['admin_pass'])) {
			// if ($post['admin_pass'] == ADMIN_PASS) {
			$user = $this->gm_db->get_in('users', ['id' => $this->accounts->profile['id']], 'row');
			if ($user AND (md5($post['admin_pass']) == $user['password'])) {
				// debug($post, 'stop');
				foreach ($post['admin_settings'] as $key => $data) {
					$id = $data['id'];
					if (!isset($data['value']['switch'])) $data['value']['switch'] = '0';
					// debug($data, 'stop');
					$this->gm_db->save('admin_settings', ['value' => json_encode($data['value'], JSON_NUMERIC_CHECK)], ['id' => $id]);
				}
				$this->set_response('success', 'Settings updated!', $post['admin_settings'], false, 'clearForm');
			}
		}
		$this->set_response('error', 'Your admin password does not match!', 
			(($post AND isset($post['admin_settings'])) ? $post['admin_settings'] : ''), false);
	}

	public function notify_operator_booking()
	{
		return false; /*Disable OPERATOR distributions*/
		$toktok_for_operators = $this->baskets->merge_disassembled([
			'status' => GM_FOR_PICK_UP_STATUS, 'operator >' => 0, 'is_sent' => 0,
		], false, false, 'added');
		// debug($toktok_for_operators, 'stop');
		if ($toktok_for_operators) {
			$operators = $this->gm_db->columns('operator', $toktok_for_operators);
			// debug($operators, 'stop');
			if ($operators) {
				foreach ($operators as $key => $operator_id) {
					/*now send alerts to operators*/
					$senddataapi = $this->senddataapi->trigger('operator-bookings', 'send-bookings', [
						'message' => 'You have available bookings, please press on BOOK NOW',
						'operator_id' => $operator_id,
						'delivery' => $toktok_for_operators[0],
						'count' => $this->gm_db->count('baskets_merge', ['status' => GM_FOR_PICK_UP_STATUS, 'operator' => $operator_id, 'is_sent' => 1]),
						'total' => $this->gm_db->count('baskets_merge', ['status' => GM_FOR_PICK_UP_STATUS, 'operator' => $operator_id, 'is_sent' => [0,1]]),
					]);
					if (in_array($senddataapi->response_code, [403,404])) {
						cronlogger($senddataapi->response_text, ['operator_id' => $operator_id], 'operator-bookings');
					}
				}
			}
		} else {
			$automation_settings = $this->gm_db->get('admin_settings', ['setting' => 'automation'], 'row');
			if ($automation_settings) {
				$set = json_decode($automation_settings['value'], true);
				$set['switch'] = 1; /*enable switch*/
				$this->gm_db->save('admin_settings', ['value' => json_encode($set, JSON_NUMERIC_CHECK)], ['id' => $automation_settings['id']]);
			}
			/*when cron job runs again it will continue our bookings based on settings*/
		}
	}

	public function run_operator_booking()
	{
		return false; /*Disable OPERATOR distributions*/
		$post = $this->input->post() ?: $this->input->get();
		if (isset($post['id'])) {
			$toktok = $this->baskets->merge_disassembled(['id' => $post['id']], true);
			if ($toktok) {
				$toktok_post = json_decode(base64_decode($toktok['toktok_post']), true);
				// debug($toktok_post, 'stop');
				if ($toktok_post) {
					$operator = $this->gm_db->get('operators', ['id' => $toktok['operator'], 'active' => 1], 'row');
					// debug($operator, 'stop');
					if ($operator) {
						$this->load->library('ToktokApi');
						$pricing = toktok_price_directions_format([
							'sender_lat' => $toktok_post['f_sender_address_lat'],
							'sender_lng' => $toktok_post['f_sender_address_lng'],
							'receiver_lat' => $toktok_post['f_recepient_address_lat'],
							'receiver_lng' => $toktok_post['f_recepient_address_lng'],
						]);
						$this->toktokapi->app_request('price_and_directions', $pricing);
						if ($this->toktokapi->success) {
							$toktok_dpd = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections'];
							unset($toktok_dpd['directions']);
							// debug($toktok_dpd, 'stop');
							$toktok_post['f_post'] = json_encode(['hash'=>$toktok_dpd['hash']], JSON_NUMERIC_CHECK);
							$toktok_post['f_distance'] = $toktok_dpd['pricing']['distance'] . ' km';
							$toktok_post['f_duration'] = format_duration($toktok_dpd['pricing']['duration']);
							$toktok_post['f_price'] = $toktok_dpd['pricing']['price'];
							$toktok_post['f_sender_mobile'] = preg_replace('/-/', '', $toktok_post['f_sender_mobile']);
							$toktok_post['f_recepient_mobile'] = preg_replace('/-/', '', $toktok_post['f_recepient_mobile']);
							$toktok_post['referral_code'] = $operator['referral_code'];
							
							/*$toktok_post['f_driver_id'] = '';
							if (isset($post['rider_mobile']) AND strlen(trim($post['rider_mobile'])) > 0) {
								// GET RIDER
								$rider = ['term'=>ltrim($post['rider_mobile'], '0'), '_type'=>'query', 'q'=>ltrim($post['rider_mobile'], '0')];
								$this->toktokapi->app_request('rider', $rider);
								if ($this->toktokapi->success) {
									$toktok_post['f_driver_id'] = $this->toktokapi->response['results'][0]['id'];
								}
							}
							debug($toktok_post, 'stop');*/
							
							if (ENVIRONMENT == 'production') {
								$this->toktokapi->app_request('post_delivery', $toktok_post);
								// debug($this->toktokapi, 'stop');
								if ($this->toktokapi->success) {
									$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 1];
								} else {
									$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 2]; // 'is_sent' => 2 FAILED
								}
							} else {
								$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 1];
							}
							$this->gm_db->save('baskets_merge', $raw, ['id' => $toktok['id']]);
							$basket_ids = explode(',', $toktok['basket_ids']);
							if ($basket_ids) {
								foreach ($basket_ids as $basket_id) {
									$this->gm_db->save('baskets', ['status' => GM_ON_DELIVERY_STATUS], ['id' => $basket_id]);
								}
							}

							sleep(3);
							$toktok_for_operators = $this->baskets->merge_disassembled([
								'status' => GM_FOR_PICK_UP_STATUS, 'operator' => $operator['id'], 'is_sent' => 0,
							], false, false, 'added');

							if ($toktok_for_operators) {
								$buyer_id = $toktok['buyer_id'];
								$seller_id = $toktok['seller_id'];
								$merge_id = $toktok['id'];
								// send realtime 
								$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge_id]);

								$this->set_response('success', false, [
									'operator_id' => $operator['id'],
									'delivery' => $toktok_for_operators[0],
									'count' => $this->gm_db->count('baskets_merge', ['status'=>6, 'operator'=>$operator['id'], 'is_sent'=>1]),
									'total' => $this->gm_db->count('baskets_merge', ['status'=>6, 'operator'=>$operator['id'], 'is_sent'=>[0,1]]),
								], false, 'bookDelivery');
							} else {
								$this->set_response('info', 'No bookings available for now!', $post, false, 'noAvailableBookings');
							}
						} else {
							cronlogger('Error while pushing operator orders to toktok!', $operator, 'operator-bookings');
						}
					}
				}
			}
		}
		$this->set_response('error', 'No Bookings available!', $post, false);
	}

	public function approvals($approved=0)
	{
		$id = $this->input->post('id');
		if ($this->accounts->has_session AND $this->accounts->profile['is_admin'] AND !empty($id)) {
			// debug($id, 'stop');
			// $approved = 1;
			$response = false;
			if (is_array($id)) {
				$products = $this->gm_db->get_in('products', ['id' => $id, 'activity' => [GM_ITEM_DRAFT, GM_ITEM_DELETED, GM_ITEM_NO_INVENTORY]]);
				if ($products) {
					$response = [];
					foreach ($products as $key => $product) {
						$this->gm_db->save('products', ['activity' => $approved], ['id' => $product['id']]);
						$activity = get_activity_text($approved);
						$response[$product['id']] = 'Product '.$product['name'].' '.$activity;
					}
				}
			} elseif (is_numeric($id)) {
				$product = $this->gm_db->get_in('products', ['id' => $id, 'activity' => [GM_ITEM_DRAFT, GM_ITEM_DELETED, GM_ITEM_NO_INVENTORY]], 'row');
				if ($product) {
					$this->gm_db->save('products', ['activity' => $approved], ['id' => $id]);
					$activity = get_activity_text($approved);
					$response = 'Product '.$product['name'].' '.$activity;
				}
			}
			echo json_encode(['success' => true, 'data' => ['messages' => $response], 'callback' => 'removeItem'], JSON_NUMERIC_CHECK); exit();
		}
		// debug(get_items('products'), 'stop');
		$this->render_page([
			'top' => [
				'index_page' => 'no',
				'css' => ['admin/main', 'modal/modals', 'marketplace/main', 'looping/product-card', 'looping/farmer-card'],
			],
			'middle' => [
				'body_class' => ['admin-approvals'],
				'head' => [
					'../global/global_navbar',
					'admin/navbar'
				],
				'body' => [
					'admin/approvals',
				],
				'footer' => [
					'global/footer'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => ['admin/main'],
			],
			'data' => [
				'column' => 'category_id',
				'result' =>  get_products()
			],
		]);
	}

	/*this will be run on cron job*/
	public function push_orders_to_toktok()
	{
		$automation_settings = $this->gm_db->get('admin_settings', ['setting' => 'automation'], 'row');
		// debug($automation_settings, 'stop');
		cronsequence('Running orders to post...');
		if ($automation_settings) {
			$set = json_decode($automation_settings['value'], true);
			$toktok_data = $this->gm_db->get('baskets_merge', [
				'status' => GM_FOR_PICK_UP_STATUS, 'operator' => 0, 'is_sent' => 0,
				'order_by' => 'added', 'direction' => 'ASC',/* 'limit' => $set['booking_limit'],*/
			]);
			// debug($automation_settings, $toktok_data, 'stop');
			if ($toktok_data) {
				cronsequence('Preparing toktok data...');
				$this->load->library('ToktokApi');
				/*do not start if switch is off*/
				if ($set['switch'] == 1) {
					cronsequence('Posting is switched on...');
					$admin_turned_off = false;
					$buyer_ids = $seller_ids = $merge_ids = [];
					foreach ($toktok_data as $key => $toktok) {
						/*recheck automation settings if switched is on/off*/
						$check = $this->gm_db->get('admin_settings', ['setting' => 'automation'], 'row');
						if ($check) {
							$checkset = json_decode($check['value'], true);
							if ($checkset['switch'] == 0) {
								cronsequence('Posting was switched off...', 'warning');
								$admin_turned_off = true;
								break; /*IF SET OFF*/
							}
						}
						/*else continue to send toktok post deliveries*/
						$post = json_decode(base64_decode($toktok['toktok_post']), true);
						cronsequence('Parsing toktok data...');
						if ($post) {
							$pricing = toktok_price_directions_format([
								'sender_lat' => $post['f_sender_address_lat'],
								'sender_lng' => $post['f_sender_address_lng'],
								'receiver_lat' => $post['f_recepient_address_lat'],
								'receiver_lng' => $post['f_recepient_address_lng'],
							]);
							cronsequence('Fetching prices and directions...');
							$this->toktokapi->app_request('price_and_directions', $pricing);
							if ($this->toktokapi->success) {
								$toktok_dpd = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections'];
								unset($toktok_dpd['directions']);
								// debug($toktok_dpd, 'stop');
								$post['f_post'] = json_encode(['hash'=>$toktok_dpd['hash']], JSON_NUMERIC_CHECK);
								$post['f_distance'] = $toktok_dpd['pricing']['distance'] . ' km';
								$post['f_duration'] = format_duration($toktok_dpd['pricing']['duration']);
								$post['f_price'] = $toktok_dpd['pricing']['price'];
								$post['f_sender_mobile'] = preg_replace('/-/', '', $post['f_sender_mobile']);
								$post['f_recepient_mobile'] = preg_replace('/-/', '', $post['f_recepient_mobile']);
								cronsequence('Merging gulaymart bookings to toktok...');

								// GET RIDER
								/*$rider = ['term'=>ltrim($set['rider_mobile'], '0'), '_type'=>'query', 'q'=>ltrim($set['rider_mobile'], '0')];
								$this->toktokapi->app_request('rider', $rider);
								if ($this->toktokapi->success) {
									$post['f_driver_id'] = $this->toktokapi->response['results'][0]['id'];
								}
								debug($post, 'stop');*/

								if (ENVIRONMENT == 'production') {
									$this->toktokapi->app_request('post_delivery', $post);
									// debug($this->toktokapi, 'stop');
									if ($this->toktokapi->success) {
										$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 1, 'operator' => -1]; // 'operator' => -1 is us
										cronsequence('Order pushed SUCCESSFULLY!', 'success');
									} else {
										$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 2, 'operator' => -1]; // 'is_sent' => 2 FAILED
										cronsequence('Order pushing FAILED!', 'danger');
									}
								} else {
									$raw = ['status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 1, 'operator' => -1]; // 'operator' => -1 is us
									cronsequence('Test Order pushed SUCCESSFULLY!', 'success');
								}
								$this->gm_db->save('baskets_merge', $raw, ['id' => $toktok['id']]);
								$basket_ids = explode(',', $toktok['basket_ids']);
								if ($basket_ids) {
									foreach ($basket_ids as $basket_id) {
										$this->gm_db->save('baskets', ['status' => GM_ON_DELIVERY_STATUS], ['id' => $basket_id]);
									}
								}
								$buyer_ids[] = $toktok['buyer_id'];
								$seller_ids[] = $toktok['seller_id'];
								$merge_ids[] = $toktok['id'];
								cronsequence('Order pushed DONE!', 'success');
								cron_finished($toktok);
							} else {
								cronlogger('Error while pushing orders to toktok!', $toktok, 'gulaymart-bookings');
							}
						} else {
							cronlogger('Error unable to parse totok data!', $toktok, 'gulaymart-bookings');
							cronsequence('Unable to parse totok data...');
						}
					}

					if (count($merge_ids)) {
						// send realtime process
						$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $merge_ids]);
						/*email admins here*/
						foreach ($merge_ids as $merge_id) {
							$order = $this->gm_db->get('baskets_merge', ['id' => $merge_id], 'row');
							if ($order AND $order['status'] == GM_ON_DELIVERY_STATUS) {
								$data = ['id' => $merge_id, 'action' => 'On Delivery', 'status' => 'on-delivery'];
								/*message buyer*/
								$data['for'] = 'buyer';
								$context = make_stream_context($data);
								$html_email = file_get_contents(base_url('support/order_details/'), false, $context);
								$html_notif = '<p>View On Delivery Order(s) <a href="orders/on-delivery/" data-readit="1">here</a></p>';
								send_gm_email($order['buyer_id'], $html_email);
								send_gm_message($order['buyer_id'], strtotime(date('Y-m-d')), $html_notif);

								/*message sellers*/
								$data['for'] = 'seller';
								$context = make_stream_context($data);
								$html_email = file_get_contents(base_url('support/order_details/'), false, $context);
								$html_notif = '<p>View your On Delivery Order(s) <a href="fulfillment/on-delivery/" data-readit="1">here</a></p>';
								send_gm_email($order['seller_id'], $html_email);
								send_gm_message($order['seller_id'], strtotime(date('Y-m-d')), $html_notif);
							}
						}
					}
					// Disable OPERATOR distributions FOR NOW
					/*check first is the switch off by some admin*/
					/*if ($admin_turned_off == false) {
						// booking_limit reached, turn off switch
						$set['switch'] = 0;
						$this->gm_db->save('admin_settings', [
							'value' => json_encode($set, JSON_NUMERIC_CHECK)
						], ['id' => $automation_settings['id']]);
						
						// now if switch is off process the manual interval
						$toktok_for_operators = $this->gm_db->get('baskets_merge', [
							'status' => GM_FOR_PICK_UP_STATUS, 'operator' => 0, 'is_sent' => 0,
							'order_by' => 'added', 'direction' => 'ASC', 'limit' => $set['manual_interval'],
						]);
						if ($toktok_for_operators) {
							$operators = $this->gm_db->get('operators', ['active' => 1]);
							if ($operators) {
								$operator_cnt = count($operators);
								$chunk_count = floor(count($toktok_for_operators) / $operator_cnt); // this will be average
								if ($chunk_count > 0) {
									$toktok_for_operators = array_chunk($toktok_for_operators, $chunk_count);
									// now loop from operators and give them bookings
									foreach ($operators as $key => $operator) {
										if (isset($toktok_for_operators[$key])) {
											$deliveries = $toktok_for_operators[$key];
											foreach ($deliveries as $delivery) {
												// update the baskets_merge data for this operator
												$this->gm_db->save('baskets_merge', ['operator' => $operator['id']], ['id' => $delivery['id']]);
											}
										}
									}
								} else {
									cronlogger('Operator count is greater than the records', $operators, 'operator-bookings');
								}
							}
						} else {
							cronlogger('No records available for operators', $toktok_for_operators, 'operator-bookings');
						}
						// then let the cron job run this until all operator bookings are done
						// there will be another method that checks these and switches back ON again
						$this->notify_operator_booking();
					}*/
				} else {
					cronsequence('Posting is switched off!', 'danger');
					// Disable OPERATOR distributions FOR NOW
					/*$this->notify_operator_booking();*/
				}
			} else {
				cronlogger('push_orders_to_toktok 547: no available post!', false, 'gulaymart-bookings');
				cronsequence('No available orders to post...', 'warning');
			}
		} else {
			cronlogger('push_orders_to_toktok 551: admin setting unknown!', false, 'gulaymart-bookings');
			cronsequence('Unable to run empty orders...', 'danger');
		}
		$this->senddataapi->trigger('booking-log', 'incoming-gm-logs', ['type' => 'sequence']);
	}

	/*this will be run on cron job*/
	public function orders_to_receive()
	{
		$baskets_merge = $this->gm_db->get('baskets_merge', [
			'status' => GM_ON_DELIVERY_STATUS, 'is_sent' => 1,
			'order_by' => 'added', 'direction' => 'ASC'
		]);
		cronreturns('Listening on processed orders...');
		// debug($baskets_merge, 'stop');
		if ($baskets_merge) {
			$baskets_merge_data = setup_orders_data($baskets_merge);
			cronreturns('Setting up toktok orders data...');
			// debug($baskets_merge_data, 'stop');
			$baskets_ids = $merge_ids = $baskets_merge_ids = [];
			if ($baskets_merge_data) {
				cronreturns('Preparing data for updating...');
				$this->load->library('ToktokApi');
				// debug($this->toktokapi, 'stop');
				foreach ($baskets_merge_data as $key => $data) {
					$valid = empty($data['delivery_id']); 
					if ($valid) {
						$seller_name = remove_multi_space($data['seller']['profile']['firstname'].' '.$data['seller']['profile']['lastname'], true);

						$seller_id = $data['seller']['profile']['id'];
						$buyer_id = $data['buyer']['profile']['id'];

						$date_range = false;
						if (isset($data['schedule']) AND !empty($data['schedule'])) {
							$date_range = [
								'from' => date('m/d/Y', strtotime($data['schedule'])),
								'to' => date('m/d/Y', strtotime($data['schedule'])),
							];
						}
						// debug($date_range, TT_RECEIVED_STATUS, $seller_name, 'stop');
						// check toktok delivery status
						cronreturns('Checking up delivery...');
						if (ENVIRONMENT == 'development') {
							$delivery = $this->toktokapi->check_delivery();
						} else {
							$delivery = $this->toktokapi->check_delivery($date_range, '', TT_RECEIVED_STATUS, $seller_name);
						}
						// debug($seller_name, $delivery, 'stop');
						if ($delivery->success AND count($delivery->response)) {
							cronreturns('Delivery found...');
							foreach ($delivery->response as $order) {
								if (isset($order['details']) AND isset($order['details']['post'])) {
									if (ENVIRONMENT == 'development') {
										$order['details']['post']['notes'] = 'GulayMart Order#:'.$data['order_id'];
									}
									$notes_data = explode('GulayMart Order#:', $order['details']['post']['notes']);
									$order_id = 0;
									if (count($notes_data) AND isset($notes_data[1])) $order_id = trim($notes_data[1]);
									// debug($order_id, $data['order_id'], 'stop');
									cronreturns('Comparing order...');
									if ($order_id == $data['order_id']) {
										cronreturns('Order matched!', 'success');
										/*set new status*/
										$set = ['status' => GM_RECEIVED_STATUS];
										if (empty($data['delivery_id'])) {
											/*delivery_id not set yet*/
											$delivery_id = $order['details']['post']['delivery_id'];
											$set['delivery_id'] = $delivery_id;
											$set['toktok_data'] = base64_encode(json_encode($order, JSON_NUMERIC_CHECK));
										}
										/*update baskets*/
										$ids = explode(',', $data['basket_ids']);
										foreach ($ids as $id) {
											if (GM_STATUSES_TEST != 1) {
												$this->gm_db->save('baskets', ['status' => GM_RECEIVED_STATUS], ['id' => $id]);
											}
											$baskets_ids[$id] = $id;
										}
										if (GM_STATUSES_TEST != 1) {
											$this->gm_db->save('baskets_merge', $set, ['id' => $data['id']]);
										}
										$baskets_merge_ids[$id] = $data['id'];
										cronreturns('GulayMart order id:'.$data['order_id'].' updated!', 'success');
									} else {
										cronlogger('Error order ids not match!', $data, 'gulaymart-bookings');
										cronreturns('Order '.$order_id.' != '.$data['order_id'].' not match!', 'danger');
									}
								} else {
									cronlogger('Error while Fetching orders from toktok!', $data, 'gulaymart-bookings');
									cronreturns('No delivery fetched for order_id:'.$data['order_id'], 'danger');
								}
							}
						} else {
							cronlogger('Error while receiving orders from toktok!', $data, 'gulaymart-bookings');
							cronreturns('No delivery fetched for order_id:'.$data['order_id'], 'danger');
						}
					} else {
						cronlogger('Error Delivery ID existing!', $data, 'gulaymart-bookings');
						cronreturns('Delivery '.$data['delivery_id'].' already exist.', 'warning');
					}
				}
				// debug($merge_ids, $baskets_ids, 'stop');
				if (count($baskets_merge_ids) > 0) {
					$this->senddataapi->trigger('order-cycle', 'incoming-gm-process', ['merge_id' => $baskets_merge_ids]);
					/*email admins here*/
					foreach ($baskets_merge_ids as $merge_id) {
						$order = $this->gm_db->get('baskets_merge', ['id' => $merge_id], 'row');
						if ($order AND $order['status'] == GM_RECEIVED_STATUS) {
							$data = ['id' => $merge_id, 'action' => 'Received', 'status' => 'received'];
							/*message buyer*/
							$data['for'] = 'buyer';
							$context = make_stream_context($data);
							$html_email = file_get_contents(base_url('support/order_details/'), false, $context);
							$html_notif = '<p>View Received Order(s) <a href="orders/received/" data-readit="1">here</a></p>';
							send_gm_email($order['buyer_id'], $html_email);
							send_gm_message($order['buyer_id'], strtotime(date('Y-m-d')), $html_notif);

							/*message sellers*/
							$data['for'] = 'seller';
							$context = make_stream_context($data);
							$html_email = file_get_contents(base_url('support/order_details/'), false, $context);
							$html_notif = '<p>View your Received Order(s) <a href="fulfillment/received/" data-readit="1">here</a></p>';
							send_gm_email($order['seller_id'], $html_email);
							send_gm_message($order['seller_id'], strtotime(date('Y-m-d')), $html_notif);
						}
					}
					cronreturns('All Deliveries updated!', 'success');
				}
			} else {
				cronlogger('orders_to_receive 620: failed setup!', $baskets_merge, 'gulaymart-bookings');
				cronreturns('Failed setting up toktok orders data...', 'danger');
			}
		} else {
			cronlogger('orders_to_receive 679: baskets unknown!', false, 'gulaymart-bookings');
			cronreturns('No available orders from now...', 'danger');
		}
		$this->senddataapi->trigger('booking-log', 'incoming-gm-logs', ['type' => 'returns']);
	}
}