<?php
/**
 * GaceLabs, Inc.
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2016 - 2018.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	GaceLabs
 * @author	GaceLabs Dev Team
 * @copyright	Copyright (c) 2016 - 2018, GaceLabs, Inc. (https://gacelabs.com/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://send-data.co
 * @since	Version 1.0.0
 * @filesource
 */

/**
 * GaceLabs SendData Class
 *
 * This class transmits realtime data accross any web platforms using php and javascript
 *
 * @package	GaceLabs
 * @subpackage	Libraries
 * @category	Libraries
 * @author 	GaceLabs Dev Team
 * @link 	https://send-data.co/?page=documentation
 */
class SendDataApi {

	/**
	 * Data SendData application URL
	 *
	 * @var string
	 */
	public $app_url = 'https://app.send-data.co/';

	/**
	 * Whether the application is loaded or not
	 *
	 * @var bool
	 */
	public $is_loaded = FALSE;

	/**
	 * Client or Account application key
	 *
	 * @var string
	 */
	protected $app_key = NULL;

	/**
	 * Client / Account application secret key
	 *
	 * @var string
	 */
	protected $app_secret = NULL;

	/**
	 * Header response code
	 *
	 * @var string
	 */
	public $response_code = 403;

	/**
	 * Header response code message
	 *
	 * @var string
	 */
	public $response_text = 'Connection did not pass with your App key preference.';

	// --------------------------------------------------------------------

	/**
	 * Class constructor
	 *
	 * Loads the SendData application using the app_key.
	 *
	 * @param	array	$config	allowed index is app_key only 
	 * @return	void
	 */
	public function __construct($config='')
	{
		if (is_array($config) OR is_object($config)) {
			if (is_object($config)) $config = (array) $config;
			if (isset($config['app_key'])) {
				$this->initialize($config['app_key']);
			} else {
				throw new Exception('Index app_key not found', 403);
			}
		} else {
			if ($config != '') {
				$app_key = $config;
				$this->initialize($app_key);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize the constructor
	 *
	 * @param	string	the client's application key
	 * @return	SendData Object Class
	 */
	public function initialize($app_key=NULL)
	{
		$this->clear();
		$this->app_key = $app_key;
		$this->is_loaded = $this->load_app();
		return $this;
	}

	// ---------------------------------- Private methods ----------------------------------

	/**
	 * Checks and loads the SendData application 
	 *
	 * @return	Boolean if the client is registered with the app_key given
	 */
	private function load_app()
	{
		$response = $this->do_curl('get/script/'.$this->app_key.'/2');
		$data = json_decode($response, TRUE);
		if (is_array($data) AND count($data)) {
			$this->app_secret = $data['app_secret'];
			$this->catch_response('successful');
			return TRUE;
		} else {
			$this->catch_response('forbidden');
		}
		return FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Performs a request to the send-data server and catch the response
	 *
	 * @param	string	uri segment or the function call
	 * @param	array	request params for the function call
	 * @return	mixed 	servers response
	 */
	private function do_curl($segment='', $data=array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->app_url.$segment);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		// receive server response...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$server_output = curl_exec($ch);
		
		curl_close($ch);

		// echo $server_output; exit();
		return $server_output;
	}

	// --------------------------------------------------------------------

	/**
	 * Catch the server's response
	 *
	 * @param	string	response term / type
	 * @return	mixed 	SendData Object Class
	 */
	private function catch_response($response='forbidden')
	{
		switch ($response) {
			case 'non_existed_channel':
				$this->response_code = 403;
				$this->response_text = 'Cant create event with non existing channel.';
				break;
			case 'empty_params':
				$this->response_code = 404;
				$this->response_text = 'Expected arguments are 3, empty given.';
				break;
			case 'channel_removed':
				$this->response_code = 404;
				$this->response_text = 'Channel has been removed.';
				break;
			case 'channel_created':
				$this->response_code = 200;
				$this->response_text = 'Channel has been created.';
				break;
			case 'transmitted':
				$this->response_code = 200;
				$this->response_text = 'Data has been transmitted to channel.';
				break;
			case 'successful':
				$this->response_code = 200;
				$this->response_text = 'Connection Successful.';
				break;
			
			default: /*forbidden*/
				$this->response_code = 403;
				$this->response_text = 'Connection did not pass with your App key preference.';
				break;
		}
		return $this;
	}

	// ---------------------------------- End of private methods ----------------------------------

	/**
	 * Create and trigger the event to the specific channels
	 *
	 * @param	string	event name
	 * @param	string	channel name
	 * @param	array	data to transmit
	 * @return	mixed 	SendData Object Class
	 */
	public function trigger($event='', $channel='', $push_data=array())
	{
		if ($this->is_loaded) {
			if ($channel != '' AND $event != '') {
				$check = $this->do_curl('app/channels/'.$this->app_secret.'/0', array('channel' => $channel));
				if ($check) {
					$data_object = array(
 						'channel' => $channel,
						'event' => $event,
						'app_key' => $this->app_key,
 						'blacklist' => 0,
 						'whitelist' => 0,
						'data' => $push_data
					);
					$response = $this->do_curl('app/transmit/'.$this->app_secret.'/2', $data_object);
					$type = 'forbidden';
					$response = json_decode($response, TRUE);
					if (count($response)) {
						if ($response['code'] == 200) {
							$type = 'transmitted';
						} else {
							$type = 'non_existed_channel';
						}
					}
					return $this->catch_response($type);
				} else {
					return $this->catch_response('non_existed_channel');
				}
			} else {
				return $this->catch_response('empty_params');
			}
		}
		return $this->catch_response('forbidden');
	}

	// --------------------------------------------------------------------

	/**
	 * Clears all properties and set the default values
	 *
	 * @param	boolean	whether initialize the app or referencial
	 * @return	mixed 	SendData Object Class
	 */
	public function clear($initialize=FALSE)
	{
		$this->is_loaded = FALSE;
		$this->app_secret = NULL;
		$this->response_code = 403;
		$this->response_text = 'Connection did not pass with your App key preference.';
		if ($initialize) $this->load_app();
		return $this;
	}
}