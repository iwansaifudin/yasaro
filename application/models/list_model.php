<?php
class List_model extends CI_Model {

	function get_list($sql, $limit) {
			
		$data = $this->run_query(1, 0, 0, $sql, $limit);
		$records = sizeof($data);
        $pr = $this->profile($records);
        $page = $pr['page'];
		$total = $pr['total'];
		$start = $pr['start'];
		$end = $pr['end'];

		$data = $this->run_query(2, $start, $end, $sql, $limit);

		return array('records' => $records, 'page' => $page, 'total' => $total, 'data' => $data);

	}
		
	function run_query($type, $start, $end, $sql, $limit) {
			
		// get parameter
		$sidx = (isset($_REQUEST['sidx'])?$_REQUEST['sidx']:1);
		$sord = (isset($_REQUEST['sord'])?$_REQUEST['sord']:'asc');
		
		// search options
		$wh = "";
		$filters = (isset($_REQUEST['filters'])?$_REQUEST['filters']:null);
		write_log($this, __METHOD__, "sidx : $sidx | sord : $sord | filters : $filters");
		if($filters <> null) {
				
			$filters_array = (array) json_decode($filters);
			
			for($i = 0; $i < sizeof((array)$filters_array['rules']); $i++) {

				$filters_sub = (array)$filters_array['rules'][$i];
				
				$op = $filters_sub['op'];
				$field = $filters_sub['field'];
				$data = $filters_sub['data'];
				if($op == 'cn') { // contain
					$wh .= " and lower(ifnull(a.$field, 'null')) like lower('%$data%')";
				} else if($op == 'eq') { // equivalent
					$wh .= " and ('All' = '$data' or a.$field = '$data')";
				}
				
			}

		}

		// query
		$sql = "
				select a.* 
				from (
						$sql
					) a
				where 1 = 1 $wh
				order by $sidx $sord
			";
		if($limit > 0) {
			$sql .= " limit $limit";
		}

		if($type == 2) {
			
			$length = $end - $start;
			$sql = "
			        select b.*
			        from (
			                $sql
			            ) b
			        limit $start, $length
				";
			
		}
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : $query->num_rows");
		
		return $query->result_array();
		
	}

	function profile($records) {
		
		// declare variable
		$page = $_REQUEST['page']; // get the requested page
		$rows = $_REQUEST['rows']; // get how many rows we want to have into the grid

		$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
		write_log($this, __METHOD__, "page : $page | rows : $rows | totalrows : $totalrows");
		if($totalrows) {
			$rows = $totalrows;
		}

		if( $records > 0 ) {
			$total = ceil($records / $rows);
		} else {
			$total = 0;
		}

        if ($page > $total) {
        	$page = $total;
		}

		$end = $rows * $page;
		$start = $end - $rows;
        if ($start < 0) $start = 0;
        
        return array("page" => $page, "total" => $total, "start" => $start, "end" => $end);
        
	}
		
}
?>