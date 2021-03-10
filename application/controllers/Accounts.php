<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

	public function index()
	{
		$data = array(
			'metas' => array(
				// facebook opengraph
				'property="fb:app_id" content="xxx"',
				'property="og:type" content="article"',
				'property="og:url" content="xxx"',
				'property="og:title" content="xxx"',
				'property="og:description" content="Either you`re a farmer or a customer, Gulaymart is your best avenue for anything veggies! Sign Up for FREE!"',
				'property="og:image" content="xxx"',
				// SEO generics
				'name="description" content="Either you`re a farmer or a customer, Gulaymart is your best avenue for anything veggies! Sign Up for FREE!"'
			),
			'index_page' => 'no',
			'page_title' => 'Gulaymart | Sign Up for FREE!',
			'css' => array(
				'head' => array('global', 'register', 'rwd'),
				'footer' => array()
			),
			'js' => array(
				'head' => array(),
				'footer' => array('main')
			),
			'body_class' =>array('register'),
			'content_top' => array(

			),
			'content_middle' => array(
				'global/register'
			),
			'content_footer' => array(
				
			),
			'modals' => array(
				'login_modal'
			),
			'page_data' => array(
			)
		);

		$this->load->view('templates/default', $data);
	}
}