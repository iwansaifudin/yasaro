<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shu_dialog extends CI_Controller {
		
	function Shu_dialog() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/shu_dialog_model');
		$this->load->model('list_model');
		
	}

	function get_list_shu_dialog_stockholder() {
		
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		write_log($this, __METHOD__, "shu_id : $shu_id");
		
		// get profile grid
		$sql = "
				select su.stockholder_id id, u.name
				  , u.cluster cluster_id, c.name cluster_name, s.nominal shu_nominal
				  , su.stock_qty, su.stock_received stock_before
				  , su.shu_qty, su.shu_received shu_before
				from shu s
				  , shu_user su
				  , users u
				  , clusters c
				where s.id = $shu_id
				  and su.shu_id = s.id 
				  and u.id = su.stockholder_id 
				  and c.id = u.cluster
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array($row['id'], $row['name'], $row['cluster_id'], $row['cluster_name'], $row['shu_nominal'], $row['stock_qty'], $row['stock_before'], $row['shu_qty'], $row['shu_before']);
            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);
		
	}
	public function get_cluster() {

		$data = $this->shu_dialog_model->get_cluster();
		echo json_encode($data);

	}	
	function get_list_shu_dialog_stock() {
			
		// declare variable
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		$stockholder_id = (isset($_REQUEST['stockholder_id'])?$_REQUEST['stockholder_id']:0);
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$status = ($form_id==1?1:2); // 1: aktif atau shu nya belum diambil; 2: shu nya telah diambil
		write_log($this, __METHOD__, "shu_id : $shu_id | stockholder_id : $stockholder_id | form_id : $form_id | status : $status");
		
		// get profile grid
		$sql = "
				select sus.stock_id id, st.name
				from shu s
				  , shu_user su
				  , shu_user_stock sus
				  , stocks st
				where s.id = $shu_id
				  and su.shu_id = s.id and su.stockholder_id = $stockholder_id
				  and sus.shu_user_id = su.id and sus.is_active = $status
				  and st.id = sus.stock_id
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);

        // get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array($row['id'], $row['name']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}

}
