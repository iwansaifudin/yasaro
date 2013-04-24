<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_user extends CI_Controller {
	
	function Rpt_user() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_user_model');
		
	}
		
	function index() {
			
		$data['cluster'] = $this->rpt_user_model->get_cluster();
		$this->load->view('reports/rpt_user', $data);

	}
	
	public function get_rpt_user() {

		$data = $this->rpt_user_model->get_rpt_user();
		echo json_encode($data);

	}

	public function get_rpt_user_excel() {

		$this->rpt_user_model->get_rpt_user_excel();
		
	}

}
	