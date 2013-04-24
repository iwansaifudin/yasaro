<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
		
	function User() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/user_model');
		
	}

	function index() {

		// get variable
		$data['form'] = $this->user_model->get_form();
		$data['cluster_group'] = $this->user_model->get_cluster();
		$data['family_group'] = $this->user_model->get_family();

		// display
		$this->load->view('admins/user', $data);
		
	}

	function save() {
		
		$result = $this->user_model->save();
		echo json_encode($result);
		
	}
	
	function reset_pass() {

		$status = $this->user_model->reset_pass();
		echo json_encode(Array('status' => $status));
		
	}

}
	