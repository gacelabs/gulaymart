<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends CI_Controller {

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
				'footer' => array()
			),
			'body_class' =>array('marketplace'),
			'content_top' => array(

			),
			'content_middle' => array(
				'global/nav_panel',
				'content/marketplace'
			),
			'content_footer' => array(

			),
			'modals' => array(

			),
			'page_data' => array(

			)
		);

		$this->load->view('templates/main', $data);
	}
}