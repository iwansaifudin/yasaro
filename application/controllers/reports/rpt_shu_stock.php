<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpt_shu_stock extends CI_Controller {
	
	function Rpt_shu_stock() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('reports/rpt_shu_stock_model');
		
	}
		
	function index() {
			
		$data['period'] = $this->rpt_shu_stock_model->get_period();
		$data['cluster'] = $this->rpt_shu_stock_model->get_cluster();
		$this->load->view('reports/rpt_shu_stock', $data);

	}
	
	public function get_rpt_shu_stock() {

		$data = $this->rpt_shu_stock_model->get_rpt_shu_stock();
		echo json_encode($data);

	}

	public function get_rpt_shu_stock_excel() {

		$this->rpt_shu_stock_model->get_rpt_shu_stock_excel();
		
	}

}
	