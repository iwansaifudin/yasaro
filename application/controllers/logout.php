<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
		
	function Logout() {
			
		parent::__construct();
		$this->load->model('logout_model');
		
	}
	
	function index() {

		$data = array(
				'is_logged_in' => false
			);
			
		$this->session->set_userdata($data);

		$id = $this->session->userdata("id");
		$name = $this->session->userdata("name");
		$gender = $this->session->userdata("gender");
		$cluster = $this->session->userdata("cluster");
		$ip = $this->session->userdata("ip");
		write_log($this, __METHOD__, "$id | $name | $gender | $cluster | $ip");

		// update is login
		$this->logout_model->set_logout($id);

		redirect('login');
		
	}
	
}