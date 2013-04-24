<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_menu extends CI_Controller {
		
	function Role_menu() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/role_menu_model');
		
	}
		
	function index() {
		
		$data['role'] = $this->role_menu_model->get_role();
		$this->load->view('admins/role_menu', $data);
		
	}

	public function get_role_menu() {

		$data = $this->role_menu_model->get_role_menu();
		echo json_encode($data);

	}
	
	public function update_role_menu() {

		$result = $this->role_menu_model->update_role_menu();
		echo json_encode(Array("result" => $result));

	}

}
	