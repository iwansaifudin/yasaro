<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shu extends CI_Controller {
		
	function Shu() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/shu_model');
		$this->load->model('sequence');		
	}

	function division() {
			
		$data['form'] = $this->shu_model->get_form();
		$data['period'] = $this->shu_model->get_period();
		$this->load->view('transactions/shu_division', $data);

	}

	function cancellation() {
			
		$data['form'] = $this->shu_model->get_form();
		$data['period'] = $this->shu_model->get_period();
		$this->load->view('transactions/shu_cancellation', $data);

	}
	function period_change() {

		$data = $this->shu_model->period_change();
		echo json_encode($data);
		
	}	
	function get_stock_detail() {

		$data = $this->shu_model->get_stock_detail();
		echo json_encode($data);
		
	}
	function approve() {
		
		$result = $this->shu_model->approve();
		echo json_encode($result);
		
	}
}
	