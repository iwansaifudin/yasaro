<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {
		
	function Stock() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/stock_model');
		$this->load->model('sequence');		
	}

	function buy() {
			
		$data['form'] = $this->stock_model->get_form();
		$this->load->view('transactions/stock_buy', $data);

	}

	function mutation() {
			
		$data['form'] = $this->stock_model->get_form();
		$this->load->view('transactions/stock_mutation', $data);

	}
	function sell() {
			
		$data['form'] = $this->stock_model->get_form();
		$this->load->view('transactions/stock_sell', $data);

	}	
	function get_stock_detail() {

		$data = $this->stock_model->get_stock_detail();
		echo json_encode($data);
		
	}
	function approve() {
		
		$result = $this->stock_model->approve();
		echo json_encode($result);
		
	}

}
	