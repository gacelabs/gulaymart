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
						case 'fulfill': case 'fulfills': case 'fulfillments':
							$data['total_items'] = $this->gm_db->count('baskets_merge', ['seller_id' => $user_ids, 'status !=' => 5]);
							break;
						case 'basket': case 'baskets':
							$data['total_items'] = $this->gm_db->count('baskets', ['user_id' => $user_ids, 'status' => [0,1]]);
							break;
						case 'order': case 'orders':
							$data['total_items'] = $this->gm_db->count('baskets_merge', ['buyer_id' => $user_ids, 'status !=' => 5]);
							break;
						case 'message': case 'messages':
							$data['total_items'] = $this->gm_db->count('messages', ['unread' => 1, 'to_id' => $user_ids]);
							break;
					}
				}
			}
		}
		echo clean_json_encode($data); exit();
	}

	public function check_stattab_counts()
	{
		$post = $this->input->get() ?: $this->input->post();
		// debug($post, 'stop');
		$data = ['id' => $this->accounts->profile['id'], 'menu' => false, 'tab' => false, 'total_items' => 0];
		if ($post) {
			if (isset($post['success']) AND $post['success'] AND !empty($post['id'])) {
				$user_ids = !is_array($post['id']) ? [$post['id']] : $post['id'];
				if (in_array($this->accounts->profile['id'], $user_ids)) {
					$data['menu'] = $menu = $post['menu'];
					$data['tab'] = $tab = $post['tab'];
					$params = false;
					if (in_array($menu, ['fulfillments'])) {
						$params = ['seller_id' => $user_ids, 'status' => GM_ITEM_REMOVED];
					} elseif (in_array($menu, ['orders'])) {
						$params = ['buyer_id' => $user_ids, 'status' => GM_ITEM_REMOVED];
					} elseif (in_array($menu, ['messages'])) {
						$params = ['unread' => GM_MESSAGE_UNREAD, 'tab' => '', 'to_id' => $user_ids];
					}
					if ($params) {
						$table = 'baskets_merge';
						switch (strtolower($tab)) {
							case 'placed':
								$params['status'] = GM_PLACED_STATUS;
								break;
							case 'for-pick-up':
								$params['status'] = GM_FOR_PICK_UP_STATUS;
								break;
							case 'on-delivery':
								$params['status'] = GM_ON_DELIVERY_STATUS;
								break;
							case 'received':
								$params['status'] = GM_RECEIVED_STATUS;
								break;
							case 'cancelled':
								$params['status'] = GM_CANCELLED_STATUS;
								break;
							case 'notifications':
								$table = 'messages';
								$params['tab'] = 'Notifications';
								break;
							case 'feedbacks':
								$table = 'messages';
								$params['tab'] = 'Feedbacks';
								break;
						}
						$data['total_items'] = $this->gm_db->count($table, $params);
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