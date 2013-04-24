<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
		
	function Login() {
		
		parent::__construct();
		$this->load->model('login_model');
		
	}

	function index($user_pass_flag = 1, $change_pass_flag = 0) {
			
		write_log($this, __METHOD__, "user_pass_flag : $user_pass_flag | change_pass_flag : $change_pass_flag");
			
		$data['content'] = 'login';
		$data['user_pass_flag'] = $user_pass_flag;
		$data['change_pass_flag'] = $change_pass_flag;

		$this->load->view('template', $data);
		
	}

	function validate() {
			
		// get parameter	
		$user = $this->input->post('user');
		$pass = strtolower($this->input->post('pass'));
		$pass = ($pass==''?'null':$pass);
		
		// validation
		$result = $this->login_model->validate();
		$status = $result['status'];
		
		write_log($this, __METHOD__, "user : $user | pass : $pass | status : $status");

		if($status == 1) { // login succeed
				
			if($pass == 'yasaro') { // first change password
					
				$data['content'] = 'change_pass';
				$data['user'] = $user;
				$data['first_change_pass'] = 1;
				$data['old_pass_flag'] = 1; // 0:show error; 1:not show error
				$data['new_pass_flag'] = 1; // 0:show error; 1:not show error
				$this->load->view('template', $data);

			} else { // login succeed
				
				// get profile
				$result = $this->login_model->get_profile($user);
	
				// get ip local
				if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
					$ip=$_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
					$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip=$_SERVER['REMOTE_ADDR'];
				}

				// set session variable
				$data = array(
					'is_logged_in' => true
					, 'id' => $result['id']
					, 'name' => $result['name']
					, 'gender' => $result['gender']
					, 'cluster' => $result['cluster']
					, 'menu_expand' => $result['menu_expand']
					, 'ip' => $ip
				);
				
				$this->session->set_userdata($data);
				write_log($this, __METHOD__, $result['id']." | ".$result['name']." | ".$result['gender']." | ".$result['cluster']." | $ip");
				
				// update is login
				$this->login_model->set_login($user);
				
				redirect('main');
			
			}
			
		} else { // login failed

			$this->index($status, 0);

		}
	}

	function test() {
	}
	
}