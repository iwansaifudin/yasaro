<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_user extends CI_Controller {
		
	function Role_user() {
	
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/role_user_model');
	
	}
	
	function index() {
		
		$this->load->view('admins/role_user');
		
	}

	public function get_role() {

		$data = $this->role_user_model->get_role();
		echo json_encode($data);

	}
		
	public function get_user_list() {

		$data = $this->role_user_model->get_user_list();
		echo json_encode($data);

	}

	public function get_user_role_list() {

		$data = $this->role_user_model->get_user_role_list();
		echo json_encode($data);

	}
	
	public function update_user_role() {

		$result = $this->role_user_model->update_user_role();
		echo json_encode(Array("result" => $result));

	}

}
	