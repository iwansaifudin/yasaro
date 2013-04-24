<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_stock extends CI_Controller {
	
	function Rpt_stock() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_stock_model');
		
	}
		
	function index() {
			
		$this->load->view('reports/rpt_stock');
	}
	
	public function get_rpt_stock() {

		$data = $this->rpt_stock_model->get_rpt_stock();
		echo json_encode($data);

	}

	public function get_rpt_stock_excel() {

		$this->rpt_stock_model->get_rpt_stock_excel();
		
	}

}
	