<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_stock_trans extends CI_Controller {
	
	function Rpt_stock_trans() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_stock_trans_model');
		
	}
		
	function index() {
			
		$data['form'] = $this->rpt_stock_trans_model->get_form();
		$this->load->view('reports/rpt_stock_trans', $data);

	}
	
	public function get_rpt_stock_trans() {

		$data = $this->rpt_stock_trans_model->get_rpt_stock_trans();
		echo json_encode($data);

	}

	public function get_rpt_stock_trans_excel() {

		$this->rpt_stock_trans_model->get_rpt_stock_trans_excel();
		
	}

}
	