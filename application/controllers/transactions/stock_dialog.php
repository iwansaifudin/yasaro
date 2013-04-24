<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_dialog extends CI_Controller {
		
	function Stock_dialog() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/stock_dialog_model');
		$this->load->model('list_model');
		
	}

	function get_list_stock_dialog_stockholder() {
		
		// get profile grid
		$sql = "
				select u.id, u.name
				  , u.cluster cluster_id, c.name cluster_name
				  , u.stock_qty qty_before
				from users  u
				  , clusters c
				where u.is_active = 1 
				  and c.id = u.cluster
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array($row['id'], $row['name'], $row['cluster_id'], $row['cluster_name'], $row['qty_before']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);
		
	}

	public function get_cluster() {

		$data = $this->stock_dialog_model->get_cluster();
		echo json_encode($data);

	}
	
	function get_list_stock_dialog_stock() {
			
		// declare variable
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$stockholder_id_from = (isset($_REQUEST['stockholder_id_from'])?$_REQUEST['stockholder_id_from']:null);
		write_log($this, __METHOD__, "form_id : $form_id | stockholder_id_from : $stockholder_id_from");
		
		// get profile grid
		if($form_id == 1) { // buy
		
			$sql = "
					select id, name, price
					from stocks
					where is_active = 1 
						and (stockholder_id is null or stockholder_id = 0)
				";
				
		} else { // mutation & sell

			$sql = "
					select id, name, price
					from stocks
					where is_active = 1 and stockholder_id = '$stockholder_id_from'
				";
			
		}
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);

        // get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array($row['id'], $row['name'], $row['price']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}

}
