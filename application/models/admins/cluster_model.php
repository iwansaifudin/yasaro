<?php
class Cluster_model extends CI_Model {

	function get_combobox() {
			
		$sql = "
				select id, name 
				from clusters
				where is_active >= 0
					and id in (
					    select distinct parent 
					    from clusters
					    where is_active >= 0
					)
				order by id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
		
		return $query->result_array();
		
	}

	function change() {
			
		// declare variable
		$user_login = $this->session->userdata("id");
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:0);
		$name = (isset($_REQUEST['name'])?$_REQUEST['name']:null);
		$status = (isset($_REQUEST['status'])?$_REQUEST['status']:null);
		$level = (isset($_REQUEST['level'])?$_REQUEST['level']:null);
		$parent = (isset($_REQUEST['parent'])?$_REQUEST['parent']:null);
		$oper = (isset($_REQUEST['oper'])?$_REQUEST['oper']:null);
		write_log($this, __METHOD__, "id : $id | name : $name | status : $status | level : $level | parent : $parent | oper : $oper");
		
		if($oper == 'add') { // insert data

			$sql = "
					select count(1) is_exist
						, min(id) id
					from clusters
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
						from clusters
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql);
				$data = $query->row_array();
				$id = $data['id'];
				write_log($this, __METHOD__, "id : $id");
	
				$sql = "
						insert into clusters(
							id, name, is_active, level, parent
							, created_by, created_date, changed_by, changed_date
						)
						values(
							$id, '$name', $status, $level, $parent
							, '$user_login', sysdate(), '$user_login', sysdate()
						)
					";
				
			} else { // jika id sudah ada sebelumnya, maka tinggal di-update saja

				$sql = "
						update clusters
						set name = '$name', is_active = $status
							, level = $level, parent = $parent
							, created_by = '$user_login', created_date = sysdate()
							, changed_by = '$user_login', changed_date = sysdate()
						where id = '$id'
					";
				
			}
			
		} else if($oper == 'edit') { // update data

			$sql = "
					update clusters
					set name = '$name', is_active = $status
						, level = $level, parent = $parent
						, changed_by = '$user_login', changed_date = sysdate()
					where id = '$id'
				";

		} else if($oper == 'del') { // delete data

			$sql = "
					update clusters
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