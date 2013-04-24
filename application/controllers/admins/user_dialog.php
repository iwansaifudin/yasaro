<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_dialog extends CI_Controller {
		
	function User_dialog() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/user_dialog_model');
		$this->load->model('list_model');
		
	}
	
	function get_list_user_dialog() {

		// get profile grid
		$sql = "
				select u.id, u.name, u.cluster
				from users  u
				where u.is_active = 1 
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array($row['id'], $row['name'], $row['cluster']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}
	
	public function get_combobox() {

		$data = $this->user_dialog_model->get_combobox();
		echo json_encode($data);

	}

}
	