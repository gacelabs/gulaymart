<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

	public $allowed_methods = ['sign_in', 'sign_up', 'fb_login', 'recover'];

	public function __construct()
	{
		$actions = explode('/', trim($_SERVER['REDIRECT_URL'], '/'));
		$this->action = str_replace(['/', '-'], ['', '_'], end($actions));
		parent::__construct();
		$this->load->library('smtpemail');
		if (!$this->accounts->has_session) $this->class_name = '';
	}

	public function index($id=false)
	{
		/*nothing yet here*/
	}

	public function sign_in()
	{
		// $post = ['username'=>'bong', 'password'=>2];
		$post = $this->input->post() ? $this->input->post() : $this->input->get();
		// debug($post);
		// $is_ok = $this->accounts->login($post, 'account');
		$referrer = str_replace(base_url('/'), '', $this->agent->referrer());
		if (empty($referrer)) $referrer = 'account';
		// debug($referrer);
		$is_ok = $this->accounts->login($post, $referrer);
		// debug($is_ok);
		$to = '/';
		if ($is_ok == false) $to = '?error=Invalid credentials';
		if ($this->accounts->has_session) {
			$to = 'account?error=You are already signed in!';
		}
		redirect(base_url($to));
	}

	public function sign_up()
	{
		// $post = ['email_address'=>'leng2@gmail.com', 'password'=>23, 're_password'=>23];
		$post = $this->input->post();
		// debug($post);
		$return = $this->accounts->register($post, 'account'); /*this will redirect to settings page */
		// debug($this->session); debug($return);
		if (isset($return['allowed']) AND $return['allowed'] == false) {
			if ($this->accounts->has_session) {
				redirect(base_url('account?error='.$return['message']));
			} else {
				redirect(base_url('?error='.$return['message']));
			}
		}
	}

	public function recover()
	{
		$post = $this->input->post();
		if ($this->accounts->has_session) {
			// debug($id); debug($post);
			$type = 'error';
			$message = 'Password mismatch!';
			if ($post) {
				$id = $this->accounts->profile['id'];
				if ($post['re_password'] === $post['password']) {
					$post['password'] = md5($post['password']);
					$this->eo_db->save('users', $post, ['id' => $id]);
					$type = 'success';
					$message = 'Password updated!';
				}
				unset($post['re_password']);
			}
		} else {
			$type = 'error';
			$message = 'Provide a valid email address!&modal=pw-reset-modal';
			if ($post AND (isset($post['email_address']) AND filter_var($post['email_address'], FILTER_VALIDATE_EMAIL))) {
				// debug($post);
				$check = $this->eo_db->get('users', ['email_address' => $post['email_address']], 'row');
				if ($check) {
					/*for emailing temp password*/
					$template_data = [
						'email' => $post['email_address'],
						'password' => $check['re_password']
					];
					// debug($template_data);
					$html = $this->load->view('page_requires/email_template', $template_data, TRUE);
					// debug($html);
					
					$mail = $this->smtpemail->setup();
					$email = [
						'email_body_message' => 'Hi <br><br>'.$html
					];
					$email['email_subject'] = 'Password Recovery';
					$email['email_to'] = $post['email_address'];
					$email['email_bcc'] = ['sirpoigarcia@gmail.com'];
					// debug($email);

					// $mail->debug = TRUE;
					$return = $mail->send($email, 5/*, TRUE*/); /*send after 5 mins*/
					// $return = 0;
					if ($return) {
						$type = 'success';
						$message = 'Your temporary password has been sent!';
					} else {
						$type = 'info';
						$message = 'Wait for 5 minutes before sending another temporary password request!';
					}
					/*debug($message);*/
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
				$id = $this->eo_db->new('profiles', $post);
			} else {
				$this->eo_db->save('profiles', $post, ['id' => $id]);
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
						$check = $this->eo_db->get('galleries', ['url_path' => $row['url_path']]);
						if ($check == false) { /*no dups*/
							$data_count++;
							$row['user_id'] = $this->accounts->profile['id'];
							$row['is_admin'] = $is_admin;
							$id = $this->eo_db->new('galleries', $row);
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
		// debug($post);
		$is_ok = $this->accounts->fb_login($post);
		// debug($is_ok);
		$to = 'account';
		if ($is_ok == false) $to = '?error=Invalid credentials';
		echo json_encode(['success' => $is_ok, 'redirect' => base_url($to)]);
	}
}