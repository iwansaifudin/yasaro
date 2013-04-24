<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_layout extends CI_Controller {
		
	function Stock_layout() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/stock_layout_model');
		
	}
		
	function list_1() {
			
		$data['flag'] = 'list_1';
		$this->load->view('transactions/stock_layout', $data);

	}
	
	function list_2() {
			
		$data['flag'] = 'list_2';
		$this->load->view('transactions/stock_layout', $data);

	}

	function button() {
			
		$data['flag'] = 'button';
		$this->load->view('transactions/stock_layout', $data);

	}

	function get_list_1() {
		
		$data = $this->stock_layout_model->get_list_1();
		echo json_encode($data);
		
	}
	function get_list_2() {
		
		$data = $this->stock_layout_model->get_list_2();
		echo json_encode($data);
		
	}	
}
	