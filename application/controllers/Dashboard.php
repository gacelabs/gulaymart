<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		$data = array(
			'metas' => array(
				// facebook opengraph
				'property="fb:app_id" content="xxx"',
				'property="og:type" content="article"',
				'property="og:url" content="xxx"',
				'property="og:title" content="Gulaymart | Profile"',
				'property="og:description" content="Gulaymart | Profile"',
				'property="og:image" content="xxx"',
				// SEO generics
				'name="description" content="Gulaymart | Profile"'
			),
			'index_page' => 'no',
			'page_title' => 'Gulaymart | Profile',
			'css' => array(
				'head' => array('global', 'dashboard','profile', 'rwd'),
				'footer' => array()
			),
			'js' => array(
				'head' => array(),
				'footer' => array('main')
			),
			'body_class' =>array('dashboard', 'profile'),
			'content_top' => array(
				'profile/nav_top'
			),
			'content_middle' => array(
				'profile/panel_left',
				'profile/panel_right'
			),
			'content_footer' => array(
				
			),
			'modals' => array(
				
			),
			'page_data' => array(
			)
		);

		$this->load->render('templates/default', $data);
	}
}