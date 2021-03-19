<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $allowed_methods = [];
	public $class_name = FALSE;
	public $device_id = FALSE;
	public $no_entry_for_signed_out = TRUE;
	public $ajax_no_entry_for_signed_out = TRUE;
	public $profile = FALSE;
	public $action = 'index';

	public function __construct()
	{
		parent::__construct();
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
		/*debug($this->allowed_methods, $this->action, $this->no_entry_for_signed_out, $this->session);*/

		// debug($this);
		$this->load->library('accounts');
		$this->load->library('smtpemail');
		// debug($this->class_name, $this->accounts->has_session, $this->accounts->profile);
		$this->set_form_valid_fields();
		$this->set_global_values();
		$this->device_id = device_id();
		
		/*check account logins here*/
		if ($this->accounts->has_session) {
			/*now allow all pages with session*/
			$this->accounts->refetch();
			// debug($this->accounts->profile, 'stop');
			if ($this->accounts->profile['is_profile_complete'] == 0 AND !in_array($this->class_name, ['profile', 'api', 'authenticate'])) {
				redirect(base_url('profile/'));
			}
		} else {
			/*now if ajax and ajax_no_entry_for_signed_out is TRUE redirect*/
			if ($this->input->is_ajax_request() AND $this->ajax_no_entry_for_signed_out) {
				echo do_jsonp_callback('ajaxSuccessResponse', [
					'type'=>'error',
					'message'=>"Session has been expired! Reloading page...",
					'redirect'=>'/'
				]); exit();
			}
			/*now if not ajax and no_entry_for_signed_out is TRUE redirect*/
			if (!$this->input->is_ajax_request() AND $this->no_entry_for_signed_out) {
				redirect(base_url());
			}
		}
	}

	public function set_response($type='error', $message='Error occured!', $data=[], $redirect_url=false, $callback=false)
	{
		if ($this->input->is_ajax_request()) {
			echo do_jsonp_callback('ajaxSuccessResponse', [
				'type' => $type,
				'message' => $message,
				'data' => $data,
				'redirect' => $redirect_url,
				'callback' => $callback,
			]); exit();
		} else {
			redirect(base_url($this->class_name.'?'.$type.'='.$message));
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
		$this->session->set_userdata('valid_fields', $defaults);
	}

	public function set_global_values()
	{
		$data = get_global_values([]);
		if (count($data)) {
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	public function render_page($rawdata=false, $debug=false)
	{
		$body_classes = ($this->accounts->has_session ? ($this->class_name != 'marketplace' ? ['logged-in', $this->class_name] : [$this->class_name]) : [$this->class_name]);
		$top_css = ($this->accounts->has_session ? ['logged-in'] : []);
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
				'js' => [],
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
				'css' => [],
				'js' => [$this->class_name],
			],
		];
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
						case 'css': case 'js':
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
							$view['top']['metas'][$key] = str_replace('XXX', 'article', $meta_value);
							break;
						case 'url':
							$view['top']['metas'][$key] = str_replace('XXX', current_full_url(), $meta_value);
							break;
						case 'title': 
							$view['top']['metas'][$key] = str_replace('XXX', APP_NAME.' '.document_title(), $meta_value);
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
		if ((bool)strstr($page_title, 'XXX')) $view['top']['page_title'] = str_replace('XXX', APP_NAME.' | '.document_title(), $page_title);

		if ($debug) debug($view, $data, 'stop');
		$this->load->view('main', ['view' => $view, 'data' => $data]);
	}
}