<?php
class Family_model extends CI_Model {

	function change() {
			
		// declare variable
		$user_id = $this->session->userdata("id");
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:0);
		$name = (isset($_REQUEST['name'])?$_REQUEST['name']:null);
		$status = (isset($_REQUEST['status'])?$_REQUEST['status']:null);
		$oper = (isset($_REQUEST['oper'])?$_REQUEST['oper']:null);
		write_log($this, __METHOD__, "id : $id | name : $name | status : $status | oper : $oper");
		
		if($oper == 'add') { // insert data

			$sql = "
					select count(1) is_exist
						, min(id) id
					from families 
					where is_active = -1
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql, FALSE);
			$data = $query->row_array();
			$is_exist = $data['is_exist'];
			$id = $data['id'];
			write_log($this, __METHOD__, "is_exist : $is_exist | id : $id");

			if($is_exist == 0) { // jika id belum pernah ada, maka di-insert baru

				$sql = "
						select max(id)+ 1 id
						from families
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				$data = $query->row_array();
				$id = $data['id'];
				write_log($this, __METHOD__, "id : $id");
	
				$sql = "
						insert into families(
							id, name, is_active
							, created_by, created_date, changed_by, changed_date
						) values (
							$id, '$name', $status
							, '$user_id', sysdate(), '$user_id', sysdate()
						)
					";
				
			} else { // jika id sudah ada sebelumnya, maka tinggal di-update saja

				$sql = "
						update families
						set name = '$name', is_active = $status
							, created_by = '$user_id', created_date = sysdate()
							, changed_by = '$user_id', changed_date = sysdate()
						where id = '$id'
					";
				
			}
			
		} else if($oper == 'edit') { // update data

			$sql = "
					update families
					set name = '$name', is_active = $status
						, changed_by = '$user_id', changed_date = sysdate()
					where id = '$id'
				";

		} else if($oper == 'del') { // delete data

			$sql = "
					update families
					set is_active = -1
					where id = '$id'
				";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");

		return $result;
		
	}

}
?>