<?php
class Stock_model extends CI_Model {

	function change() {
			
		// declare variable
		$user_id = $this->session->userdata("id");
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:0);
		$name = (isset($_REQUEST['name'])?$_REQUEST['name']:null);
		$status = (isset($_REQUEST['status'])?$_REQUEST['status']:null);
		$oper = (isset($_REQUEST['oper'])?$_REQUEST['oper']:null);
		write_log($this, __METHOD__, "id : $id | name : $name | status : $status | oper : $oper");

		if($oper == 'edit') { // update data

			$sql = "
					update stocks
					set is_active = $status
						, changed_by = '$user_id', changed_date = sysdate()
					where id = '$id'
				";

		} else if($oper == 'del') { // delete data

			$sql = "
					select id
					from stocks
					where id = $id
					  and stockholder_id > 0
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql, FALSE);
			$is_exists = $query->num_rows();
			
			if($is_exists == 1) { // untuk kondisi jika data sudah ada 
				
				return 2; // return untuk data sudah exist
				
			} else {
				
				$sql = "
						update stocks
						set is_active = -1
						where id = '$id'
					";
				
			}
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");

		if($result) {
			return 1;
		} else {
			return 0;
		}
		
	}

}
?>