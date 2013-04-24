<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_pass extends CI_Controller {
		
	function Change_pass() {
		
		parent::__construct();
		$this->load->model('change_pass_model');
		
	}

	function index($user = null, $first_change_pass = 0, $old_pass_flag = 1, $new_pass_flag = 1) {
		
		write_log($this, __METHOD__, "user : $user | first_change_pass : $first_change_pass | old_pass_flag : $old_pass_flag | new_pass_flag : $new_pass_flag");
			
		$data['content'] = 'change_pass';
		$data['user'] = $user;
		$data['first_change_pass'] = $first_change_pass;
		$data['old_pass_flag'] = $old_pass_flag; // 0:show error; 1:not show error
		$data['new_pass_flag'] = $new_pass_flag; // 0:show error; 1:not show error
		$data['top'] = '110px';
		$this->load->view('template', $data);
		
	}

	function validate() {
		
		// get parameter
		$user = (isset($_REQUEST['user'])?$_REQUEST['user']:null);
		$first_change_pass = (isset($_REQUEST['first_change_pass'])?strtolower($_REQUEST['first_change_pass']):0);
		
		// validation
		$result = $this->change_pass_model->validate();
		$old_pass_flag = $result['old_pass_flag']; // 0:show error; 1:not show error
		$new_pass_flag = $result['new_pass_flag']; // 0:show error; 1:not show error
		$status = $result['status'];
		
		write_log($this, __METHOD__, "user : $user | first_change_pass : $first_change_pass | result : $result | old_pass_flag : $old_pass_flag | new_pass_flag : $new_pass_flag | status : $status");
		
		if($status) {
			
			$data['content'] = 'login';
			$data['user_pass_flag'] = 1; // 0:error user; 2:error password; 1:not show error
			$data['change_pass_flag'] = 1; // 0:not show message succeed; 1:show message succeed
			$this->load->view('template', $data);
		
		} else {

			$this->index($user, $first_change_pass, $old_pass_flag, $new_pass_flag);

		}

	}

	function nav_change_pass() {
			
		$data['user'] = $this->session->userdata("id");
		$data['first_change_pass'] = 0;
		$data['old_pass_flag'] = 1; // 0:show error; 1:not show error
		$data['new_pass_flag'] = 1; // 0:show error; 1:not show error
		$data['top'] = '70px';
		$this->load->view('change_pass', $data);
		
	}
	
	function nav_change_pass_approve() {
		
		$result = $this->change_pass_model->validate();
		echo json_encode($result);
		
	}
	
}