<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_shu extends CI_Controller {
	
	function Rpt_shu() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_shu_model');
		
	}
		
	function index() {
			
		$this->load->view('reports/rpt_shu');
	}
	
	public function get_rpt_shu() {

		$data = $this->rpt_shu_model->get_rpt_shu();
		echo json_encode($data);

	}

	public function get_rpt_shu_excel() {

		$this->rpt_shu_model->get_rpt_shu_excel();
		
	}

}
	