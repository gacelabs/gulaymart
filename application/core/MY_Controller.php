<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public $allowed_methods = [];
	public $class_name = FALSE;
	public $device_id = FALSE;
	public $no_entry_for_signed_out = TRUE;
	public $ajax_no_entry_for_signed_out = TRUE;
	public $profile = FALSE;
	public $action = 'index';

	public $farms = FALSE;
	public $categories = FALSE;
	public $measurements = FALSE;

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
		
		/*check account logins here*/
		if ($this->accounts->has_session) {
			/*now allow all pages with session*/
			$this->accounts->refetch();
			// debug($this->class_name, 'stop');
			if ($this->accounts->profile['is_profile_complete'] == 0 AND !in_array($this->class_name, ['profile', 'api', 'authenticate'])) {
				redirect(base_url('profile'));
			}
		} else {
			/*now if ajax and ajax_no_entry_for_signed_out is TRUE redirect*/
			if ($this->input->is_ajax_request() AND $this->ajax_no_entry_for_signed_out) {
				echo do_jsonp_callback('ajaxSuccessResponse', ['type'=>'error', 'message'=>"Session has been expired!", 'redirect'=>'/']); exit();
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
			$this->farms = isset($data['farms']) ? $data['farms'] : [];
			$this->categories = isset($data['categories']) ? $data['categories'] : [];
			$this->measurements = isset($data['measurements']) ? $data['measurements'] : [];
		}
	}
}