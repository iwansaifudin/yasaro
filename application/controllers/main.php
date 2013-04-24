<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	function Main() {
			
		parent::__construct();
		is_logged_in($this);
		$this->load->model('menu');
		
	}

	public function index() {
		
		// Content
		$data['content'] = 'main';
		$data['menu'] = $this->menu->get_menu();
		
		$this->load->view('template', $data);

	}

}
	