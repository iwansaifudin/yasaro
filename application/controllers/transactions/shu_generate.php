<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shu_generate extends CI_Controller {
		
	function Shu_generate() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('transactions/shu_generate_model');
		$this->load->model('list_model');
		
	}
		
	function index() {

		// get profile grid
		$sql = "
				select id, period, nominal
				  , date_format(last_buy_date, '%d %b %Y') last_buy_date
				  , stockholder_qty
				  , stock_qty, stock_received
				  , (stock_qty - stock_received) stock_remain
				  , shu_qty, shu_received
				  , (shu_qty - shu_received) shu_remain
				from shu
				where is_active >= 0
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array(null, $row['id'], $row['period'], $row['nominal'], $row['last_buy_date'], $row['stockholder_qty'], $row['stock_qty'], $row['shu_qty'], $row['shu_received'], $row['shu_remain']);

            $i++;
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];

        echo json_encode($response);

	}

	function change() {
			
		$result = $this->shu_generate_model->change();
		echo json_encode(Array('result' => $result));
		
	}
	
}
	