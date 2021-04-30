<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function index()
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' - Admin',
					'name' => APP_NAME.' | Admin',
				],
				'index_page' => 'no',
				'page_title' => APP_NAME.' | Admin',
				'css' => ['admin/main'],
			],
			'middle' => [
				'body_class' => ['admin-stats'],
				'head' => [
					'../global/global_navbar',
					'admin/navbar'
				],
				'body' => [
					'admin/statistics'
				],
				'footer' => [
					'global/footer'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => [],
			],
			'data' => [],
		]);
	}

	public function bookings()
	{
		$this->render_page([
			'top' => [
				'metas' => [
					'description' => APP_NAME.' - Admin',
					'name' => APP_NAME.' | Admin',
				],
				'index_page' => 'no',
				'page_title' => APP_NAME.' | Admin',
				'css' => ['admin/main'],
			],
			'middle' => [
				'body_class' => ['admin-bookings'],
				'head' => [
					'../global/global_navbar',
					'admin/navbar'
				],
				'body' => [
					'admin/bookings'
				],
				'footer' => [
					'global/footer'
				],
			],
			'bottom' => [
				'modals' => [],
				'js' => ['hideshow'],
			],
			'data' => [],
		]);
	}

}