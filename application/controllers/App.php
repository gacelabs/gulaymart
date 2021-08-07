<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function install($model=false)
	{
		if ($model) {
			switch (strtolower(trim($model))) {
				case 'android':
					$this->load->view('landing/index');
				break;
				
				default:
					show_404();
					break;
			}
		}
	}
}

