<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $allowed_methods = [];
	public $not_allowed_methods = [];
	public $class_name = FALSE;
	public $device_id = FALSE;
	public $no_entry_for_signed_out = TRUE;
	public $ajax_no_entry_for_signed_out = TRUE;
	public $profile = FALSE;
	public $action = 'index';
	public $referrer = FALSE;
	public $latlng = ['lat' => 14.628538456333938, 'lng' => 120.97507784318562];
	public $current_city = FALSE;

	public function __construct()
	{
		parent::__construct();
		$user_timezone = get_cookie('user_timezone', true);
		// debug(date_default_timezone_get(), $user_timezone, 'stop');
		if (empty($user_timezone)) {
			$zone_details = ip_info((bool)strstr($_SERVER['HTTP_HOST'], 'local') ? '120.29.109.66' : NULL);
			// debug($zone_details, 'stop');
			if (!empty($zone_details)) {
				$user_timezone = $zone_details['timezone'];
				set_cookie('user_timezone', $user_timezone, 7776000); // 90 days
			}
		}
		if (!empty($user_timezone)) {
			@ini_set('date.timezone', $user_timezone);
			if ($user_timezone != 'Asia/Manila') date_default_timezone_set($user_timezone);
		}
		setup_db_timezone();
		// debug($user_timezone, date_default_timezone_get(), get_cookie('user_timezone', true), 'stop');
		// debug($this->session->userdata(), 'stop');
		// $this->session->sess_destroy();
		// debug($this->latlng, 'stop');
		$latlng = get_cookie('prev_latlng', true);
		// $latlng = $this->session->userdata('prev_latlng');
		if (!empty($latlng)) {
			// debug($latlng, 'stop');
			$this->latlng = unserialize($latlng);
		}
		$current_city = get_cookie('current_city', true);
		if (!empty($current_city)) {
			// debug($current_city, 'stop');
			$this->current_city = urldecode($current_city);
		}
		$is_mobile = $this->agent->is_mobile();
		if ($is_mobile == true) {
			// debug($is_mobile, 'stop');
			$this->config->set_item('sess_expire_on_close', FALSE);
		}
		// debug($this->latlng, 'stop');
		$this->class_name = strtolower(trim($this->router->class));
		$this->action = strtolower(trim($this->router->method));
		// $this->load->library('controllerlist');
		// $controllerlist = $this->controllerlist->getControllers();
		// debug($this->action, 'stop');
		/*
		see allowed methods to this class and set the variables no_entry_for_signed_out, 
		ajax_no_entry_for_signed_out to false to allow entry
		*/
		if ($this->allowed_methods == 'all') {
			$this->no_entry_for_signed_out = $this->ajax_no_entry_for_signed_out = FALSE;
		} else {
			if (in_array($this->action, $this->allowed_methods)) {
				$this->no_entry_for_signed_out = $this->ajax_no_entry_for_signed_out = FALSE;
			}
		}
		/*now check again if the not allowed methods exists then do not allow it*/
		if (count($this->not_allowed_methods)) {
			if (in_array($this->action, $this->not_allowed_methods)) {
				$this->no_entry_for_signed_out = $this->ajax_no_entry_for_signed_out = TRUE;
			}
		}
		/*debug($this->allowed_methods, $this->action, $this->no_entry_for_signed_out, $this->session);*/

		/*DEPLOY CSS AND JS MINIFIER WHEN IN PRODUCTION ONLY*/
		// $this->load->library('minify');
		// debug($this->minify, 'stop');
		// if (defined('ENVIRONMENT') AND ENVIRONMENT == 'development') $this->minify->enabled = FALSE;

		// debug($this);
		$this->load->library('accounts');
		$this->load->library('users');
		$this->load->library('products');
		$this->load->library('SmtpEmail');
		$this->load->library('SendDataApi', ['app_key'=>SENDDATA_APPKEY]);
		// debug($this->class_name, $this->accounts->has_session, $this->accounts->profile);
		$this->set_form_valid_fields();
		$this->set_global_values();
		$this->device_id = device_id();
		// debug($this->products->get(), 'stop');
		// debug($this, 'stop');
		
		/*check account logins here*/
		if ($this->accounts->has_session) {
			/*now allow all pages with session*/
			$results = $this->accounts->refetch();
			// debug($this->accounts->profile, 'stop');
			if ($results AND $this->accounts->profile['is_profile_complete'] == 0 AND !in_array($this->class_name, ['profile', 'api', 'authenticate'])) {
				redirect(base_url('profile/'));
			}
			// debug(get_class($this), $this->session->userdata('referrer'), 'stop');
		} else {
			$referrer = $this->session->userdata('referrer');
			if (!in_array(get_class($this), ['Admin','Api','App','Authenticate','DevBuild','Sitemap','Test'])) {
				if (!empty($referrer)) {
					$this->referrer = $referrer;
				} else {
					$this->referrer = str_replace(base_url('/'), '', current_full_url());
				}
				$this->session->set_userdata('referrer', $this->referrer);
			}
			// debug($this->referrer, 'stop');

			/*now if ajax and ajax_no_entry_for_signed_out is TRUE redirect*/
			if ($this->input->is_ajax_request() AND $this->ajax_no_entry_for_signed_out) {
				$data = [
					'type' => 'error',
					'message' => "Nothing happened, Session has been expired! Reloading browser...",
					'redirect' => '/'
				];
				if ($this->input->get('callback') == 'gmCall') {
					echo do_jsonp_callback('ajaxSuccessResponse', $data);
				} else {
					echo json_encode($data, JSON_NUMERIC_CHECK);
				}
				exit();
			}
			/*now if not ajax and no_entry_for_signed_out is TRUE redirect*/
			if (!$this->input->is_ajax_request() AND $this->no_entry_for_signed_out) {
				redirect(base_url());
			}
		}
	}

	public function set_response($type='error', $message='Error occured!', $data=[], $redirect_url=false, $callback=false, $unclose=false)
	{
		if ($this->input->is_ajax_request()) {
			echo do_jsonp_callback('ajaxSuccessResponse', [
				'type' => $type,
				'message' => $message,
				'data' => $data,
				'redirect' => $redirect_url,
				'callback' => $callback,
				'unclose' => $unclose,
			]); exit();
		} else {
			if (is_string($redirect_url)) {
				redirect(base_url($redirect_url.'?'.$type.'='.$message));
			} elseif ($redirect_url == false) {
				redirect(base_url($this->class_name.'?'.$type.'='.$message));
			}
		}
	}

	public function set_form_valid_fields($valids=FALSE)
	{
		$defaults = [
			'firstname' => ['required' => TRUE],
			'lastname' => ['required' => TRUE],
			'email_address' => ['required' => TRUE, 'emailExt' => TRUE],
			'password' => ['required' => TRUE],
			're_password' => ['required' => TRUE],
			'farmer_terms' => ['required' => TRUE],
			'farmer_policy' => ['required' => TRUE],
			'email' => ['required' => TRUE, 'emailExt' => TRUE],
		];
		if ($valids) {
			foreach ($valids as $field => $variable) {
				if (is_array($variable)) {
					$boolean = isset($variable['required']) ? (bool)$variable['required']: FALSE;
					$function = isset($variable['function']) ? $variable['function']: FALSE;
					if (is_string($field)) {
						$values = ['required' => $boolean];
						if ($function != FALSE) {
							$values[$function] = TRUE;
						}
						$defaults[$field] = $values;
					}
				}
			}
		}
		$this->valid_fields = $defaults;
	}

	public function set_global_values()
	{
		$data = get_global_values([]);
		if (count($data)) {
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}
		}
		/*set minify folders*/
		/*foreach (['assets/css/compiled/', 'assets/js/compiled/'] as $path) {
			if (is_dir(get_root_path($path)) == false) {
				$folder_chunks = explode('/', $path);
				if (count($folder_chunks)) {
					$uploaddir = get_root_path();
					foreach ($folder_chunks as $key => $folder) {
						$uploaddir .= $folder.'/';
						@mkdir($uploaddir);
					}
				}
				@chmod($uploaddir, 0755);
			}
		}*/
		/**/
		$this->basket_count = $this->order_count = $this->fulfill_count = $this->message_count = false;
		if ($this->accounts->has_session) {
			$fulfill_count = $this->gm_db->count_not_in('baskets_merge', 
				['seller_id' => $this->accounts->profile['id'], 'status' => [4,5]]);
			$this->fulfill_count = $fulfill_count;

			$baskets = $this->gm_db->count('baskets', ['user_id' => $this->accounts->profile['id'], 'status' => [0,1]]);
			$this->basket_count = $baskets;

			$order_count = $this->gm_db->count_not_in('baskets_merge', 
				['buyer_id' => $this->accounts->profile['id'], 'status' => [4,5]]);
			$this->order_count = $order_count;
			
			$msg_count = $this->gm_db->count('messages', ['unread' => 1, 'to_id' => $this->accounts->profile['id']]);
			$this->message_count = $msg_count;
		}
		// debug($products, 'stop');
	}

	public function render_page($rawdata=false, $variable=false)
	{
		// debug($rawdata, 'stop');
		// debug($this->action, 'stop');
		$body_classes = ($this->accounts->has_session ? ($this->class_name != 'marketplace' ? ['logged-in', $this->class_name] : [$this->class_name]) : [$this->class_name]);
		$top_css = (($this->accounts->has_session AND $this->action != 'store') ? ['logged-in'] : []);
		$view = [
			'top' => [
				'metas' => [
					// facebook opengraph
					'app_id' => 'property="fb:app_id" content="'.FB_APPID.'"',
					'type' => 'property="og:type" content="XXX"',
					'url' => 'property="og:url" content="XXX"',
					'title' => 'property="og:title" content="XXX"',
					'description' => 'property="og:description" content="XXX"',
					'image' => 'property="og:image" content="XXX"',
					// SEO generics
					'name' => 'name="description" content="XXX"'
				],
				'index_page' => 'XXX',
				'page_title' => 'XXX',
				'css' => $top_css,
			],
			'middle' => [
				'body_class' => $body_classes,
				/* found in views/templates */
				'head' => [],
				'body' => [],
				'footer' => [],
				/* found in views/templates */
			],
			'bottom' => [
				'modals' => [],
				'js' => [],
			],
		];
		// debug($this->uri->segment(1), 'stop');
		if (!$this->accounts->has_session) {
			if (!in_array($this->uri->segment(1), ['register','support'])) {
				$view['top']['css'][] = 'marquee';
				$view['bottom']['modals'][] = 'check_loc_modal';
				$view['bottom']['js'][] = 'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places';
				$view['bottom']['js'][] = 'plugins/markerclustererplus.min';
				$view['bottom']['js'][] = 'plugins/marquee';
			}
		}
		$view['bottom']['js'][] = 'plugins/fb-login';
		$data = false;

		if ($rawdata) {
			/*START top manipulation*/
			if (isset($rawdata['top'])) {
				foreach ($rawdata['top'] as $key => $row) {
					switch (strtolower($key)) {
						case 'metas':
							foreach ($row as $index => $value) {
								if (isset($view['top']['metas'][$index])) {
									$meta_value = $view['top']['metas'][$index];
									$view['top']['metas'][$index] = str_replace('XXX', $value, $meta_value);
								}
							}
							break;
						case 'css':
							foreach ($row as $index => $value) {
								array_push($view['top'][$key], $value);
							}
							break;
						default:
							$view['top'][$key] = $row;
							break;
					}
				}
			}
			/*STOP top manipulation*/

			/*START middle manipulation*/
			if (isset($rawdata['middle'])) {
				foreach ($rawdata['middle'] as $key => $row) {
					if (isset($view['middle'][$key])) {
						if (is_array($view['middle'][$key])) {
							if (is_string($row)) {
								$view['middle'][$key][] = $row;
							} elseif (is_array($row)) {
								$view['middle'][$key] = array_unique(array_merge($view['middle'][$key], $row));
							}
						} elseif (is_string($view['middle'][$key])) {
							if (is_array($row)) {
								$view['middle'][$key] = implode(' ', $row);
							} elseif (is_string($row)) {
								$view['middle'][$key] = $row;
							}
						}
					}
				}
			}
			/*STOP middle manipulation*/

			/*START bottom manipulation*/
			if (isset($rawdata['bottom'])) {
				foreach ($rawdata['bottom'] as $key => $row) {
					if (isset($view['bottom'][$key])) {
						if (is_array($view['bottom'][$key])) {
							if (is_string($row)) {
								$view['bottom'][$key][] = $row;
							} elseif (is_array($row)) {
								$view['bottom'][$key] = array_unique(array_merge($view['bottom'][$key], $row));
							}
						} elseif (is_string($view['bottom'][$key])) {
							if (is_array($row)) {
								$view['bottom'][$key] = implode(' ', $row);
							} elseif (is_string($row)) {
								$view['bottom'][$key] = $row;
							}
						}
					}
				}
			}
			/*STOP bottom manipulation*/

			if (isset($rawdata['data'])) {
				$data = $rawdata['data'];
			}
		}
		/*set view to top meta contents*/
		foreach ($view['top']['metas'] as $key => $value) {
			if ((bool)strstr($value, 'XXX')) {
				if (isset($view['top']['metas'][$key])) {
					$meta_value = $view['top']['metas'][$key];
					switch (strtolower($key)) {
						case 'type':
							$view['top']['metas'][$key] = str_replace('XXX', 'website', $meta_value);
							break;
						case 'url':
							$view['top']['metas'][$key] = str_replace('XXX', current_full_url(), $meta_value);
							break;
						case 'title': 
							$view['top']['metas'][$key] = str_replace('XXX', APP_NAME.' '.(ucwords(urldecode(document_title()))), $meta_value);
							break;
						case 'description': case 'name':
							$view['top']['metas'][$key] = str_replace('XXX', APP_DESCRIPTION, $meta_value);
							break;
						case 'image':
							$view['top']['metas'][$key] = str_replace('XXX', base_url(DEFAULT_OG_IMAGE), $meta_value);
							break;
					}
				}
			}
		}
		/*set default to index_page*/
		$index_page = $view['top']['index_page'];
		if ((bool)strstr($index_page, 'XXX')) $view['top']['index_page'] = str_replace('XXX', 'no', $index_page);
		/*set default to page_title*/
		$page_title = $view['top']['page_title'];
		if ((bool)strstr($page_title, 'XXX')) $view['top']['page_title'] = str_replace('XXX', APP_NAME.' | '.(ucwords(urldecode(document_title()))), $page_title);

		// debug($view, $data, 'stop');
		if ($variable) {
			return $this->load->view('main', ['view' => $view, 'data' => $data], true);
		} else {
			$this->load->view('main', ['view' => $view, 'data' => $data]);
		}
	}
}
