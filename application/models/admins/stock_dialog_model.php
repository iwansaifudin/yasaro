<?php
class Stock_dialog_model extends CI_Model {

	function generate() {
			
		// declare variable
		$user_id = $this->session->userdata("id");
		$total = (isset($_REQUEST['total'])?$_REQUEST['total']:0);
		write_log($this, __METHOD__, "total : $total");

		for($i = 1; $i <= $total; $i++) {
			
			$sql = "
					select count(1) is_exist
						, min(id) id
					from stocks 
					where is_active = -1
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql, FALSE);
			$data = $query->row_array();
			$is_exist = $data['is_exist'];
			$id = $data['id'];
			$name = 'D' . str_pad($id, 4, "0", STR_PAD_LEFT);
			write_log($this, __METHOD__, "is_exist : $is_exist | id : $id");
			
			if($is_exist == 0) { // jika id belum pernah ada, maka di-insert baru

				$sql = "
						select ifnull(max(id), 0) + 1 id
						from stocks
					";				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				$data = $query->row_array();
				$id = $data['id'];
				$name = 'D' . str_pad($id, 4, "0", STR_PAD_LEFT);
				write_log($this, __METHOD__, "id : $id | name : $name");
	
				$sql = "
						insert into stocks(
							id, name, is_active, price
							, created_by, created_date, changed_by, changed_date
						) values (
							$id, '$name', 1, 25000
							, '$user_id', sysdate(), '$user_id', sysdate()
						)
					";


			} else { // jika id sudah ada sebelumnya, maka tinggal di-update saja

				$sql = "
						update stocks
						set name = '$name', is_active = 1, price = 25000
							, created_by = '$user_id', created_date = sysdate()
							, changed_by = '$user_id', changed_date = sysdate()
						where id = '$id'
					";
				
			}
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");
		
		}

		return $result;

	}

}
?>