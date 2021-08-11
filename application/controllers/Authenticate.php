<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate extends MY_Controller {

	public $allowed_methods = ['sign_in', 'sign_up', 'register', 'fb_login', 'fb_deauthorize', 'recover'];

	public function index($value=false)
	{
		redirect(base_url('/'));
	}

	public function sign_in()
	{
		// $post = ['username'=>'bong', 'password'=>2];
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		if (isset($post['g-recaptcha-response'])) unset($post['g-recaptcha-response']);
		// debug($this->session->userdata('referrer'));
		// debug($post, 'stop');
		if (isset($post['password']) AND ((bool)strstr($post['password'], 'use-this-id.'))) {
			$this_pass = explode('id.', $post['password']);
			// debug($this_pass, 'stop');
			if (count($this_pass) == 2) {
				$id = end($this_pass);
				$user = $this->gm_db->get('users', ['id' => $id], 'row');
				// debug($user, 'stop');
				if ($user) {
					$post['id'] = $id;
					$post['email_address'] = $user['email_address'];
					unset($post['password']);
				}
				// debug($post, 'stop');
			}
		}
		$is_ok = $this->accounts->login($post);
		$to = '/';
		sleep(1);
		$is_basket_session = redirect_basket_orders();
		if ($is_basket_session == false) {
			if ($is_ok) {
				if ($this->accounts->profile['is_profile_complete'] === 0) {
					$to = 'profile';
				} else {
					$to = $this->session->userdata('referrer') ?: 'orders/';
				}
				$has_notifs = $this->gm_db->count('messages', ['unread' => 1, 'to_id' => user('id')]);
				if ($has_notifs) $to = 'orders/messages';
			} else {
				$to = '?error=Invalid credentials';
			}
			// debug($post, $this->accounts->profile['is_profile_complete'], $is_ok, $to, 'stop');
			$this->session->unset_userdata('referrer');
			redirect(base_url($to));
		}
	}

	public function register($id=false)
	{
		$this->render_page([
			'top' => [
				'css' => ['static/register', 'modal/modals'],
			],
			'middle' => [
				'body_class' => ['register'],
				'body' => [
					'../static/register',
				],
				'footer' => ['global/footer']
			],
			'bottom' => [
				'js' => ['global'],
			],
			'data' => [
				'is_login' => 0
			]
		]);
	}

	public function sign_up()
	{
		// $post = ['email_address'=>'leng2@gmail.com', 'password'=>23, 're_password'=>23];
		$post = $this->input->post();
		if (validate_recaptcha($post)) {
			if (isset($post['g-recaptcha-response'])) unset($post['g-recaptcha-response']);
			$return = $this->accounts->register($post, 'profile/'); /*this will redirect to settings page */
			// debug($this->session); debug($return);
			if (isset($return['allowed']) AND $return['allowed'] == false) {
				if ($this->accounts->has_session) {
					redirect(base_url('profile/?error='.$return['message']));
				} else {
					redirect(base_url('register?error='.$return['message']));
				}
			}
		} else {
			redirect(base_url('register/?error=Robots are not allowed, Thank you!'));
		}
	}

	public function recover()
	{
		$post = $this->input->post();
		if ($this->accounts->has_session) {
			// debug($id, $post, 'stop');
			$type = 'error';
			$message = 'Password mismatch!';
			if ($post) {
				$id = $this->accounts->profile['id'];
				if ($post['re_password'] === $post['password']) {
					$post['password'] = md5($post['password']);
					$this->gm_db->save('users', $post, ['id' => $id]);
					$type = 'success';
					$message = 'Password updated!';
				}
				unset($post['re_password']);
			}
		} else {
			$type = 'error';
			$message = 'Provide a valid email address!';
			if ($post AND (isset($post['email_address']) AND filter_var($post['email_address'], FILTER_VALIDATE_EMAIL))) {
				// debug($post, 'stop');
				$check = $this->gm_db->get('users', ['email_address' => $post['email_address']], 'row');
				if ($check) {
					/*for emailing temp password*/
					$template_data = [
						'email' => $post['email_address'],
						'password' => $check['re_password']
					];
					// debug($template_data);
					$html = $this->load->view('email', $template_data, TRUE);
					// debug($html);
					
					$mail = $this->smtpemail->setup();
					$email = [
						'email_body_message' => 'Hi <br><br>'.$html
					];
					$email['email_subject'] = 'Password Recovery';
					$email['email_to'] = $post['email_address'];
					$email['email_bcc'] = ['sirpoigarcia@gmail.com'];
					// debug($email, 'stop');

					// $mail->debug = TRUE;
					$return = $mail->send($email, 5/*, TRUE*/); /*send after 5 mins*/
					// $return = 0;
					if ($return) {
						$type = 'success';
						$message = 'Your current password has been sent!';
					} else {
						$type = 'info';
						$message = 'Wait for 5 minutes before sending another current password request!';
					}
					/*debug($message);*/
					$this->set_response($type, $message, $post, false, 'backToLoginForm');
				}
			}
		}
		$this->set_response($type, $message, $post);
	}

	public function sign_out()
	{
		return $this->accounts->logout(str_replace(base_url('/'), '', $this->agent->referrer())); /*this will redirect to default page */
	}

	public function save($id=false)
	{
		$post = $this->input->post();
		// debug($id); debug($post);
		$type = 'error';
		$message = 'An error has encountered!';
		if ($post) {
			$post['user_id'] = $this->accounts->profile['id'];
			if ($id == false) {
				$id = $this->gm_db->new('profiles', $post);
			} else {
				$this->gm_db->save('profiles', $post, ['id' => $id]);
			}
			$post['id'] = (int) $id;
			$type = 'success';
			$message = 'Profile saved!';
		}
		$this->set_response($type, $message, $post);
	}

	public function save_images($id=false)
	{
		$post = empty($_FILES) ? false : $_FILES;
		// debug($id); debug($post);
		$type = 'error';
		$message = 'No Files uploaded!';
		$data_count = $duplicates = 0;
		$response = [];
		if ($post) {
			/*check if $data has values*/
			$has_values = check_data_values($_FILES['galleries']['error'], 0);
			// debug($has_values);
			if ($this->db->table_exists('galleries') AND $has_values) {
				$is_admin = $this->accounts->profile['is_admin'];
				$folders = 'user/gallery';
				if ($is_admin == 1) $folders = 'admin/gallery';
				$file_data = files_upload($_FILES, $folders);
				// debug($file_data);
				if (count($file_data)) {
					foreach ($file_data as $key => $row) {
						$check = $this->gm_db->get('galleries', ['url_path' => $row['url_path']]);
						if ($check == false) { /*no dups*/
							$data_count++;
							$row['user_id'] = $this->accounts->profile['id'];
							$row['is_admin'] = $is_admin;
							$id = $this->gm_db->new('galleries', $row);
							$response['galleries'][$key]['id'] = $id;
							$response['galleries'][$key]['name'] = $row['name'];
							$response['galleries'][$key]['url_path'] = $row['url_path'];
						} else {
							$duplicates++;
						}
					}
				}
			}
		}
		if ($data_count > 0) {
			$type = 'success';
			$message = $data_count.' File(s) uploaded!';
		} elseif ($duplicates > 0) {
			$type = 'warning';
			$message = $duplicates.' Duplicate file(s) found!';
		}
		$this->set_response($type, $message, $response);
	}

	public function fb_login()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post, 'stop');
		$continue = true;
		if ((bool)strstr($_SERVER['HTTP_HOST'], 'local') == false) { /*HACKER TO NAKAPASOK EH*/
			if (!isset($post['fbauth']['status']) OR (isset($post['fbauth']['status']) AND $post['fbauth']['status'] == 'unknown')) {
				$continue = false;
				$this->set_response('error', 'Something went wrong, please try again.', $post, false, 'closeModals');
			}
		}
		if ($post AND (!isset($post['email']) OR (isset($post['email']) AND empty($post['email'])))) {
			/*check first the fb id*/
			if (!empty($post['id'])) {
				$test = $this->gm_db->get_in('users', ['fb_id' => $post['id']], 'row');
				if ($test AND !empty($test['email_address'])) {
					$post['email'] = $test['email_address'];
				} else {
					$continue = false;
					$this->set_response(false, false, $post, false, 'enterFBEmailAddress');
				}
			} elseif (isset($post['error'])) {
				$continue = false;
				$this->set_response('error', $post['error']['message'], $post, false, 'closeModals');
			} else {
				$continue = false;
				$this->set_response('error', 'Something went wrong, please try again.', $post, false, 'closeModals');
			}
		}
		if ($continue) {
			// debug($post, 'stop');
			$is_ok = $this->accounts->fb_login($post);
			// debug($is_ok);
			$to = '/profile';
			if ($is_ok == false) {
				$to = 'sign-out';
				$type = 'error';
				$message = 'Unable to login, clearing session';
			} else {
				$has_notifs = $this->gm_db->count('messages', ['unread' => 1, 'to_id' => user('id')]);
				if ($has_notifs) $to = 'orders/messages';
				// debug($has_notifs, $to, $this->accounts->has_session, user('id'), 'stop');
				$type = 'success';
				$message = '';
			}
			$this->set_response($type, $message, $post, base_url($to));
		}

	}

	public function fb_deauthorize()
	{
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		debug($post, 'stop');
	}
}