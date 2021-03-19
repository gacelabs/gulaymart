<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DevBuild extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('createdev');
	}

	public function index()
	{
		$this->load->view('dev-build');
	}

	public function run()
	{
		/*DEFAULT PASSWORD IS 1*/
		$post = $this->input->post();
		if ($post) {
			if (check_data_values($post) AND $post['password'] === '1') {
				$drop = ((isset($post['drop']) AND $post['drop']) ? 'true' : 'false');
				// debug($post, $drop, true);
				$this->build((isset($post['drop']) AND $post['drop']) ? true : false);
			}
		}
		echo "<br>DONE!<br><br><button onclick='history.back();'>Back</button>"; exit();
	}

	private function build($drop_all=false)
	{
		if ($drop_all) {
			$exists = method_exists($this->gm_db, 'drop_tables');
			if ($exists == true) {
				$this->load->library('accounts');
				if ($this->accounts->has_session) {
					$this->accounts->logout(true);
				}
				sleep(3);
				$this->gm_db->drop_tables();
				echo "All Tables dropped <br>";
				sleep(10);
			}
		}
		/*re-create table*/
		$tables = [ 'users', 'user_farms', 'user_settings', 'galleries', 'email_session', 'user_shippings', 'user_profiles', 'products', 'products_category', 'products_subcategory', 'products_location', 'products_measurement', 'products_photo' ];
		foreach ($tables as $key => $table) {
			if (!$this->db->table_exists($table)) {
				/*create table for the first time*/
				$method = 'create_'.$table.'_table';
				if (method_exists($this->createdev, $method)) {
					$is_created = $this->createdev->{$method}();
					if ($is_created) {
						echo "Table ".$table." created! <br>";
					} else {
						echo "Table ".$table." existing! <br>";
					}
				} else {
					echo "Method ".$method." does not exists! <br>";
				}
			}
		}
		if (!isset($is_created)) {
			echo "All Tables and Values already exists! <br>";
		}
		return;
	}

}