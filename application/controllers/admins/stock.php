<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {
		
	function Stock() {
		
		parent::__construct();
		is_logged_in($this);
		$this->load->model('admins/stock_model');
		$this->load->model('list_model');
		
	}

	function get_data() {

		// get profile grid
		$sql = "
				select s.id, s.name, s.is_active status, s.price 
				  , (select u.name from users u where u.id = s.stockholder_id) stockholder
				  , date_format(s.buy_date, '%d %b %Y') buy_date
				from stocks s
				where is_active >= 0
			";
		$list = $this->list_model->get_list($sql, 100);
		write_log($this, __METHOD__, "sql : $sql | records : ".$list['records']." | page : ".$list['page']." | total : ".$list['total']);
		
		// get data
        $i = 0;
		$price = 0;
		$data = $list['data'];
		foreach ($data as $row) {

			$response->rows[$i]['id'] = $row['id']; 
			$response->rows[$i]['cell'] = array(null, $row['id'], $row['name'], $row['status'], $row['price'], $row['stockholder'], $row['buy_date']);

            $i++;
			$price += $row['price'];
			
		}

	    $response->records = $list['records'];
	    $response->page = $list['page'];
		$response->total = $list['total'];
		
		$response->userdata['id'] = 'Total';
		$response->userdata['name'] = $i;
		$response->userdata['price'] = $price;

        echo json_encode($response);

	}

	function change() {
			
		$result = $this->stock_model->change();
		echo json_encode(Array('result' => $result));

	}

}
	