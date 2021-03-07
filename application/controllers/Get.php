<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends CI_Controller {

	public function index()
	{
		setcookie('toktok-tet', 'test'/*, 0, '/', 'portal.toktok.ph'*/);
		$this->output->set_header('Access-Control-Allow-Origin: *');
		if ($_POST) $this->debug($_POST);
		$this->load->view('welcome_message');
	}

	private function debug($value='')
	{
		echo '<pre>';
		print_r($value);
		echo '</pre>';
		exit();
	}
}
