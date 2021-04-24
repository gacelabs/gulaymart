<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ToktokApi {

	// public $ci = false;
	public $ch = false;
	public $connected = false;
	public $closed = false;
	public $url = 'https://portal.toktok.ph/';
	public $portal = 'https://portal.toktok.ph/';
	public $website = 'https://toktok.ph/';
	public $endpoint = NULL;
	public $success = false;
	public $response = false;

	public function __construct()
	{
		// $ci =& get_instance();
		// initial request with login data
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_URL, $this->portal.'auth/authentication/login');
		curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, "username=PPS8083189&password=L3n6gARc1a2021");
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'cp_toktok_session_prod');  // could be empty, but cause problems on some hosts
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, '/d/keep-alive/tmp');  // could be empty, but cause problems on some hosts
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
			// 'Content-Type: application/json',
			'Connection: Keep-Alive'
		]);
		$json = curl_exec($this->ch);
		$this->response = json_decode($json, true);
		$this->set_response();
	}

	public function app_request($method=false, $params=[], $type='portal', $files=[])
	{
		$list = $this->endpoint_list($type);
		if ($method != false AND isset($list[$method])) {
			$this->endpoint = $list[$method];
			// debug($this->url.$this->endpoint.'?'.http_build_query($params), 'stop');
			sleep(3);
			try {
				if (!in_array($method, ['post_delivery', 'view_delivery', 'fetch_riders'])) {
					curl_setopt($this->ch, CURLOPT_POST, false);
					curl_setopt($this->ch, CURLOPT_URL, $this->url.$this->endpoint.'?'.http_build_query($params));
				} elseif (in_array($method, ['view_delivery'])) {
					curl_setopt($this->ch, CURLOPT_POST, true);
					curl_setopt($this->ch, CURLOPT_URL, $this->url.$this->endpoint.'/'.$params);
				} else {
					curl_setopt($this->ch, CURLOPT_POST, true);
					curl_setopt($this->ch, CURLOPT_URL, $this->url.$this->endpoint);
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($params));
					// $this->curl_custom_postfields($params, $files);
					// curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($params));
				}
				curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($this->ch, CURLOPT_HEADER, 1);
				$json = curl_exec($this->ch);

				$header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
				$header = substr($json, 0, $header_size);
				$body = substr($json, $header_size);
				// if (!in_array($method, ['pricing', 'rider'])) debug($json, $header, json_decode($body, true));

				$this->response = json_decode($body, true);
			} catch (Exception $e) { 
				$this->response = $e;
				$this->connected = false;
				$this->stop();
			}
		} else {
			$this->response = '';
			$this->connected = false;
			$this->stop();
			return $this;
		}
		return $this->set_response();
	}

	public function endpoint_list($type='portal')
	{
		$this->type = $type;
		if ($type == 'portal') {
			$this->url = $this->portal;
			return [
				'price_and_directions' => 'app/deliveries/getDeliveryPriceAndDirections/',
				'post_delivery' => 'app/deliveries/operatorPostDelivery/',
				'rider' => 'app/driver/fetch_riders_by_mobile_number/',
				'fetch_riders' => 'sys/toktok_riders/rider_summary_list_table?start=0&length=100&reg_status=1&order[0][column]=0',
				'rider_ids' => 'app/deliveries/getConsumerDriverId/',
				'check_orders' => 'app/deliveries/deliveries_operator_list_table/',
				'view_delivery' => 'app/deliveries/view_deliveries/', // LzhmV1VRRGdjOE9HOVhZeWg1S0k5Zz09 makikita sa View button (check_orders)
				'active_places' => 'sys/pickup_dropoff_point/map_pickup_dropoff_point_list',
			];
		} elseif ($type == 'website') {
			$this->url = $this->website;
			return [
				'price_and_directions' => 'app/websiteBooking/getDeliveryPriceAndDirections',
				'validate' => 'app/websiteBooking/validate_website_inputs',
				'otp' => 'app/websiteBooking/send_otp?mobile_number=',
				'post_delivery' => 'app/websiteBooking/operatorPostDelivery',
				'rider_location' => 'app/trackBooking/getDriverLastLocation/', // ?delivery_id=TnRITjBxUHVhQURUdE9ZY3JsMDJ4UT09 makikita sa Click to view and copy delivery link (check_orders)
				'cancel_reasons' => 'app/trackBooking/get_cancellation_categories/',
				'confirm_cancel' => 'app/trackBooking/confirmCancelBooking/', // ?deliveryId=769723&categoryId=9 (extracted from deliveryId=view_delivery and categoryId=cancel_reasons)
			];
		}
		return [];
	}

	public function stop()
	{
		curl_close($this->ch);
		$this->closed = true;
		return $this;
	}

	public function clear()
	{
		if ($this->closed == false) {
			curl_setopt($this->ch, CURLOPT_COOKIELIST, 'ALL');
			curl_exec($this->ch);
		}
		return $this;
	}

	private function set_response()
	{
		$error = curl_error($this->ch);
		if ($error) {
			$this->response = $error;
			$this->connected = false;
			$this->stop();
		} elseif (isset($this->response['success'])) {
			$this->connected = true;
			$this->success = $this->response['success'];
		}
		$this->log_activities();
		return $this;
	}

	private function curl_custom_postfields(array $assoc = [], array $files = [])
	{
		// invalid characters for "name" and "filename"
		static $disallow = ["\0", "\"", "\r", "\n"];

		// build normal parameters
		foreach ($assoc as $k => $v) {
			$k = str_replace($disallow, "_", $k);
			$body[] = implode("\r\n", [
				"Content-Disposition: form-data; name=\"{$k}\"",
				"",
				filter_var($v),
			]);
		}
		// debug($body, 'stop');
		// build file parameters
		foreach ($files as $k => $v) {
			switch (true) {
				case false === $v = realpath(filter_var($v)):
				case !is_file($v):
				case !is_readable($v):
				// or return false, throw new InvalidArgumentException
			}
			$data = file_get_contents($v);
			$v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
			$k = str_replace($disallow, "_", $k);
			$v = str_replace($disallow, "_", $v);
			$body[] = implode("\r\n", [
				"Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
				"Content-Type: application/octet-stream",
				"",
				$data,
			]);
		}

		// generate safe boundary
		do {
			// $boundary = "------WebKitFormBoundary" . /*strtoupper*/(substr(md5(mt_rand() . microtime()), 3, 18));
			$boundary = "------WebKitFormBoundary" . /*strtoupper*/(substr(hash('sha256', uniqid('', true)), 0, 15));
		} while (preg_grep("/{$boundary}/", $body));

		// add boundary for each parameters
		array_walk($body, function (&$part) use ($boundary) {
			$part = "{$boundary}\r\n{$part}";
		});

		// add final boundary
		$body[] = "{$boundary}";
		$body[] = "";
		// debug($body, 'stop');

		// set options
		return @curl_setopt_array($this->ch, [
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => implode("\r\n", $body),
			CURLOPT_HTTPHEADER => [
				"Expect: 100-continue",
				"X-Requested-With: XMLHttpRequest",
				"Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
			],
		]);
	}

	private function log_activities()
	{
		$logfile = fopen(get_root_path('assets/data/logs/toktok-api.log'), "a+");
		$txt = "Date: " . Date('Y-m-d H:i:s') . "\n";
		$txt .= "Response: " . json_encode($this) . " \n";
		$txt .= "--------------------------------" . "\n";
		fwrite($logfile, $txt);
		fclose($logfile);
	}
}