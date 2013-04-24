<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_shu_trans extends CI_Controller {
	
	function Rpt_shu_trans() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_shu_trans_model');
		
	}
		
	function index() {
		
		$data['period'] = $this->rpt_shu_trans_model->get_period();
		$data['form'] = $this->rpt_shu_trans_model->get_form();
		$this->load->view('reports/rpt_shu_trans', $data);

	}
	
	public function get_rpt_shu_trans() {

		$data = $this->rpt_shu_trans_model->get_rpt_shu_trans();
		echo json_encode($data);

	}

	public function get_rpt_shu_trans_excel() {

		$this->rpt_shu_trans_model->get_rpt_shu_trans_excel();
		
	}

}
	