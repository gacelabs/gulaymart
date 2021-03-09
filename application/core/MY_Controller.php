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
		$this->class_name = trim(strtolower(get_called_class()));
		$this->load->library('accounts');
		// debug($this->class_name, $this->accounts->has_session, $this->accounts->profile);
		
		/*check account logins here*/
		if ($this->accounts->has_session) {
			/*now allow all pages with session*/
			$this->accounts->refetch();
			// debug($this->accounts->profile);
		} else {
			/*now if ajax and ajax_no_entry_for_signed_out is TRUE redirect*/
			if ($this->input->is_ajax_request() AND $this->ajax_no_entry_for_signed_out) {
				do_jsonp_callback('reloadPage', ['type'=>'error', 'message'=>"Session has been expired!"]);
			}
			/*now if not ajax and no_entry_for_signed_out is TRUE redirect*/
			if (!$this->input->is_ajax_request() AND $this->no_entry_for_signed_out) {
				redirect(base_url());
			}
		}
	}

	public function set_response($type='error', $message='Error occured!', $data=[], $jscallback='alertBox')
	{
		if ($this->input->is_ajax_request()) {
			do_jsonp_callback($jscallback, ['type'=>$type, 'message'=>$message, 'data'=>$data]);
		} else {
			redirect(base_url($this->class_name.'?'.$type.'='.$message));
		}
	}
}