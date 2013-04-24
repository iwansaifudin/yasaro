<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
		
		
	function Menu() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/menu_model');
		$this->load->model('list_model');
		
	}
		
	function index() {

		// get profile grid
		$sql = "
				select id, name, parent, seq
					, seq_flow(id) seq_flow
				    , is_active status, is_folder, link 
				from menus
				where is_active >= 0
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array(null,$row['id'],$row['name'],$row['parent'],$row['seq'],$row['seq_flow'],$row['status'],$row['is_folder'],$row['link']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}

	public function get_combobox() {

		$data = $this->menu_model->get_combobox();
		echo json_encode($data);

	}

	function change() {
		
		$result = $this->menu_model->change();

	}

}
	