<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends MY_Controller {

	public $allowed_methods = 'all';

	public function index()
	{
		$data = array(
			'metas' => array(
				// facebook opengraph
				'property="fb:app_id" content="xxx"',
				'property="og:type" content="article"',
				'property="og:url" content="xxx"',
				'property="og:title" content="xxx"',
				'property="og:description" content="Gulaymart is your neighborhood veggies supplier."',
				'property="og:image" content="xxx"',
				// SEO generics
				'name="description" content="Gulaymart is your neighborhood veggies supplier."'
			),
			'page_title' => 'Gulaymart | Veggies grown by community.',
			'css' => array(
				'head' => array('marketplace'),
				'footer' => array()
			),
			'js' => array(
				'head' => array(),
				'footer' => array('isotope.min', 'main', 'fb-login')
			),
			'body_class' =>array('marketplace'),
			'content_top' => array(
			),
			'content_middle' => array(
				'marketplace/nav_body',
				'marketplace/product_item_body'
			),
			'content_footer' => array(

			),
			'modals' => array(
				'search_popup'
			),
			'page_data' => array(
				'is_login' => 0
			)
		);

		$this->load->view('templates/marketplace', $data);
	}
}