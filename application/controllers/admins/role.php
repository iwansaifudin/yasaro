<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Controller {
		
	function Role() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/role_model');
		$this->load->model('list_model');
		
	}
		
	function index() {

		// get profile grid
		$sql = "
				select id, name, is_active status 
				from roles
				where is_active >= 0
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array(null,$row['id'],$row['name'],$row['status']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}

	function change() {
			
		$result = $this->role_model->change();

	}

}
	