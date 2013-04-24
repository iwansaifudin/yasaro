<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cluster extends CI_Controller {
		
	function Cluster() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/cluster_model');
		$this->load->model('list_model');
		
	}
	
	function index() {

		// get profile grid
		$sql = "
				select id, name, is_active status
					, level, parent 
				from clusters
				where is_active >= 0
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array(null,$row['id'],$row['name'],$row['status'],$row['level'],$row['parent']);
            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);
		
	}

	public function get_combobox() {

		$data = $this->cluster_model->get_combobox();
		echo json_encode($data);

	}

	function change() {
			
		$result = $this->cluster_model->change();

	}

}
	