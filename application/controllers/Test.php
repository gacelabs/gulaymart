<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {

	public $allowed_methods = ['mail', 'msg', 'send'];

	public function __construct()
	{
		parent::__construct();
		if (!$this->accounts->has_session) {
			redirect(base_url('/'));
		} elseif (!user('is_admin')) {
			redirect(base_url('/'));
		}
	}

	public function mail($type='hello')
	{
		$mail = $this->smtpemail->setup($type);
		$email = ['email_body_message' => 'Test Email Sent!'];
		$email['email_subject'] = 'Email Testing';
		$email['email_to'] = 'gacelabs.inc@gmail.com';
		$email['email_bcc'] = ['sirpoigarcia@gmail.com'];
		// debug($email, 'stop');
		// $mail->debug = TRUE;
		$return = $mail->send($email, false, true);
		debug($return, 'stop');
	}

	public function msg($id=0)
	{
		$id = $id == 0 ? $this->accounts->profile['id'] : $id;
		$sent = send_gm_message($id, strtotime(date('Y-m-d')), 'TEST!!!!', 'Notifications', 'Orders', 'test message');
		debug($sent, 'stop');
	}

	public function send($mode='basket', $other=0)
	{
		$sent = false;
		switch (strtolower(trim($mode))) {
			case 'basket':
				$data = [
					'baskets' => [
						'quantity' => 1,
						'location_id' => 1,
					],
					'order_type' => 1
				];
				$context = http_build_query($data);
				redirect(base_url('basket/add/'.$other.'/1/?'.$context));
				break;
			case 'verify_basket':
				$baskets = $this->gm_db->get_in('baskets', [
					'order_type' => $other,
					'order_by' => 'added',
					'direction' => 'DESC',
					'limit' => 2
				]);
				if ($baskets) {
					$data = [];
					foreach ($baskets as $key => $basket) {
						$data[] = [
							'id' => $basket['id'],
							'order_type' => $basket['order_type'],
						];
					}
					$context = http_build_query(['data' => $data]);
					redirect(base_url('basket/verify/0/?'.$context));
				}
				break;
			case 'checkout_basket':
				redirect(base_url('basket/place_order/'));
				break;
			case 'tambay':
				
				break;
		}
		debug($mode, 'stop');
	}


}