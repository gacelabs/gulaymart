<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Support extends MY_Controller {

	public $allowed_methods = ['order_details', 'thankyou_page', 'view_invoice', 'terms', 'policy'];

	public function index()
	{
		$this->help_center();
	}

	public function help_center()
	{
		$this->render_page([
			'top' => [
				'css' => ['dashboard/main']
			],
			'middle' => [
				'body_class' => ['support', 'help-center'],
				'head' => ['dashboard/navbar'],
				'body' => [
					'dashboard/navbar_aside',
				],
			],
		]);
	}

    public function view_invoice($order_id=false)
    {
        if ($order_id) {
            $results = $this->gm_db->get_in('baskets_merge', ['order_id' => $order_id], 'row');
            // debug($results, 'stop');
            if ($results) {
                $results['for_email'] = true;
                $this->render_page([
                    'top' => [
                        'css' => ['modal/invoice-modal', 'global/zigzag', 'global/order-table', 'print.min']
                    ],
                    'middle' => [
                        'body' => [
                            '../static/invoice_middle_body'
                        ],
                    ],
                    'bottom' => [
                        'js' => ['plugins/print.min', 'plugins/html2canvas.min'],
                    ],
                    'data' => $results,
                ]);
            }
        }
    }

	public function order_details()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		if ($post) {
			$results = $this->gm_db->get_in('baskets_merge', ['id' => $post['id']], 'row');
			if ($results) {
				$results['action'] = $post['action'];
				$results['for'] = $post['for'];
				$results['status'] = $post['status'];
				$results['data'] = ['name' => 'There'];
				if ($post['for'] == 'seller') {
					$results['data'] = json_decode(base64_decode($results['seller']), true);
				}
				if ($post['for'] == 'buyer') {
					$results['data'] = json_decode(base64_decode($results['buyer']), true);
					$results['data']['name'] = $results['data']['fullname'];
				}
				// debug($results, 'stop');
				$this->load->view('global/email-seller', $results);
			}
		}
		return '';
	}

	public function check_menunav_counts()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		$data = ['id' => $this->accounts->profile['id'], 'nav' => false, 'total_items' => 0];
		if ($post) {
			if (isset($post['success']) AND $post['success'] AND !empty($post['id'])) {
				$user_ids = !is_array($post['id']) ? [$post['id']] : $post['id'];
				if (in_array($this->accounts->profile['id'], $user_ids)) {
					$data['nav'] = $nav = $post['nav'];
					switch (strtolower($nav)) {
						case 'basket':
							$data['total_items'] = $this->gm_db->count('baskets', ['user_id' => $user_ids, 'status' => [0,1]]);
							break;
						case 'order':
							$data['total_items'] = $this->gm_db->count('baskets_merge', ['buyer_id' => $user_ids, 'status !=' => 5]);
							break;
						case 'fulfill':
							$data['total_items'] = $this->gm_db->count('baskets_merge', ['seller_id' => $user_ids, 'status !=' => 5]);
							break;
					}
				}
			}
		}
		echo clean_json_encode($data); exit();
	}

	public function thankyou_page()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		if ($post) {
			$this->load->view('global/email-order', $post);
		}
		return '';
	}

	public function terms()
	{
		$this->render_page([
			'top' => [
				'css' => ['static/register', 'modal/modals'],
			],
			'middle' => [
				'head' => ['../global/global_navbar'],
				'body_class' => ['register'],
				'body' => [
					'support/terms',
				],
				'footer' => ['global/footer']
			],
			'data' => [
				'is_login' => 0
			]
		]);
	}

	public function policy()
	{
		$this->render_page([
			'top' => [
				'css' => ['static/register', 'modal/modals'],
			],
			'middle' => [
				'head' => ['../global/global_navbar'],
				'body_class' => ['register'],
				'body' => [
					'support/policy',
				],
				'footer' => ['global/footer']
			],
			'data' => [
				'is_login' => 0
			]
		]);
	}
}