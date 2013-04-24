<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_dialog extends CI_Controller {
		
	function Stock_dialog() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/stock_dialog_model');
		
	}

	function generate() {
	
		$result = $this->stock_dialog_model->generate();
		echo json_encode(array('result' => $result));
	
	}
	
}
	