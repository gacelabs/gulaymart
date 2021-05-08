<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public $allowed_methods = ['post_deliveries'];
	public $not_allowed_methods = [];

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
			'data' => [],
		]);
	}

	public function bookings()
	{
		$post = $this->input->post() ?: $this->input->get();
		if ($post AND isset($post['admin_pass'])) {
			if ($post['admin_pass'] == ADMIN_PASS) {
				// debug($post, 'stop');
				foreach ($post['admin_settings'] as $key => $data) {
					$id = $data['id'];
					if (!isset($data['value']['switch'])) $data['value']['switch'] = '0';
					// debug($data, 'stop');
					$this->gm_db->save('admin_settings', ['value' => json_encode($data['value'])], ['id' => $id]);
				}
				$this->set_response('success', 'Settings updated!', $post['admin_settings'], false, 'clearForm');
			}
			$this->set_response('error', 'Admin password does not match!', $post['admin_settings'], false);
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
					'settings' => $admin_settings
				],
			]);
		}
	}

	/*this will be run on cron job*/
	public function post_deliveries()
	{
		$automation_settings = $this->gm_db->get('admin_settings', ['setting' => 'automation'], 'row');
		if ($automation_settings) {
			$set = json_decode($automation_settings['value'], true);
			$toktok_data = $this->gm_db->get('baskets_merge', [
				'status' => 6, 'operator' => 0, 'is_sent' => 0,
				'order_by' => 'added', 'direction' => 'ASC', 'limit' => $set['booking_limit'],
			]);
			// debug($automation_settings, $toktok_data, 'stop');
			if ($toktok_data) {
				$this->load->library('toktokapi');
				// $this->toktokapi->check_delivery();
				/*do not start if switch is off*/
				if ($set['switch'] == 1) {
					$admin_turned_off = false;
					foreach ($toktok_data as $key => $toktok) {
						/*recheck automation settings if switched is on/off*/
						$check = $this->gm_db->get('admin_settings', ['setting' => 'automation'], 'row');
						if ($check) {
							$checkset = json_decode($check['value'], true);
							if ($checkset['switch'] == 0) {
								$admin_turned_off = true;
								break; /*IF SET OFF*/
							}
						}
						/*else continue to send toktok post deliveries*/
						$post = json_decode(base64_decode($toktok['toktok_post']), true);
						if ($post) {
							$pricing = toktok_price_directions_format([
								'sender_lat' => $post['f_sender_address_lat'],
								'sender_lng' => $post['f_sender_address_lng'],
								'receiver_lat' => $post['f_recepient_address_lat'],
								'receiver_lng' => $post['f_recepient_address_lng'],
							]);
							$this->toktokapi->app_request('price_and_directions', $pricing);
							if ($this->toktokapi->success) {
								$toktok_dpd = $this->toktokapi->response['result']['data']['getDeliveryPriceAndDirections'];
								unset($toktok_dpd['directions']);
								// debug($toktok_dpd, 'stop');
								$post['f_post'] = json_encode(['hash'=>$toktok_dpd['hash']]);
								$post['f_distance'] = $toktok_dpd['pricing']['distance'] . ' km';
								$post['f_duration'] = format_duration($toktok_dpd['pricing']['duration']);
								$post['f_price'] = $toktok_dpd['pricing']['price'];
								$post['f_sender_mobile'] = preg_replace('/-/', '', $post['f_sender_mobile']);
								$post['f_recepient_mobile'] = preg_replace('/-/', '', $post['f_recepient_mobile']);
								// $post['referral_code'] = 'PPS9665253';
								// $post['f_driver_id'] = '164515';
								// unset($post['referral_code']);
								debug($post, 'stop');
								// $this->toktokapi->app_request('post_delivery', $post);
								// debug($this->toktokapi, 'stop');
								if ($this->toktokapi->success) {
									$raw = ['is_sent' => 1, 'operator' => -1]; /*'operator' => -1 is us*/
								} else {
									$raw = ['is_sent' => 2, 'operator' => -1]; /*'is_sent' => 2 FAILED*/
								}
								$this->gm_db->save('baskets_merge', $raw, ['id' => $toktok['id']]);
							}
						}
					}
					/*check first is the switch off by some admin*/
					if ($admin_turned_off == false) {
						/*booking_limit reached, turn off switch*/
						$set['switch'] = 0;
						$this->gm_db->save('admin_settings', ['value' => json_encode($set)], ['id' => $automation_settings['id']]);
						
						/*now if switch is off process the manual interval*/
						$toktok_for_operators = $this->gm_db->get('baskets_merge', [
							'status' => 6, 'operator' => 0, 'is_sent' => 0,
							'order_by' => 'added', 'direction' => 'ASC', 'limit' => $set['manual_interval'],
						]);
						if ($toktok_for_operators) {
							$operators = $this->gm_db->get('operators', ['active' => 1]);
							if ($operators) {
								$operators = count($operators);
								$chunk_count = floor(count($toktok_for_operators) / $operators); /*this will be average*/
								if ($chunk_count > 0) {
									$toktok_for_operators = array_chunk($toktok_for_operators, $chunk_count);
									/*now loop from operators and give them bookings*/
									foreach ($operators as $key => $operator) {
										if (isset($toktok_for_operators[$key])) {
											$toktok = $toktok_for_operators[$key];

											/*update the baskets_merge data for this operator*/
											$this->gm_db->save('baskets_merge', ['operator' => $operator['id']], ['id' => $toktok['id']]);

											/*send this data to this operator in realtime*/
											$merge_ids = $this->gm_db->columns('id', $toktok);
											$app = $this->senddataapi->trigger('operator-bookings', 'send-bookings', [
												'message' => 'You have available bookings, please click "BOOK NOW"',
												'merge_ids' => $merge_ids,
											]);
											
											/*log here*/
											operatorlogger($toktok, $app, $operator);
										}
									}
								} else {
									/*log here*/
									operatorlogger('Operator count is greater than the records');
								}
							}
						} else {
							/*log here*/
							operatorlogger('No records available for operators');
						}
						/*then let the cron job run this until all operator bookings are done*/
						/*there will be another cron job that checks these and switches back ON again*/
					}
				}
			}
		}
	}

	public function run_operator_booking()
	{
		/*SAMPLE LANG MAG-REFLECT TO IN REALTIME SA admin/bookings PAGE*/
		$this->senddataapi->trigger('operator-bookings', 'send-bookings', [
			'message' => 'You have available bookings, please press on BOOK NOW',
			'merge_ids' => false,
		]);
		$post = $this->input->post() ?: $this->input->get();
		debug($post, 'stop');
	}
}