<?php
class Menu_model extends CI_Model {

	function get_combobox() {
			
		$sql = "
				select id, name 
				from menus
				where is_active >= 0
				  and (
				    id in (
				      select distinct parent
				      from menus
				      where is_active >= 0
				    )
				    or is_folder = 1
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
		$parent = (isset($_REQUEST['parent'])?$_REQUEST['parent']:0);
		$seq = (isset($_REQUEST['seq'])?$_REQUEST['seq']:0);
		$status = (isset($_REQUEST['status'])?$_REQUEST['status']:1);
		$is_folder = (isset($_REQUEST['is_folder'])?$_REQUEST['is_folder']:0);
		$link = (isset($_REQUEST['link'])?$_REQUEST['link']:null);
		$oper = (isset($_REQUEST['oper'])?$_REQUEST['oper']:null);
		write_log($this, __METHOD__, "id : $id | name : $name | parent : $parent | seq : $seq | status : $status | is_folder : $is_folder | link : $link | oper : $oper");
		
		if($oper == 'add') { // insert data

			$sql = "
					select count(1) is_exist
						, min(id) id
					from menus 
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
						select (max(id) + 1) id 
						from menus
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				$data = $query->row_array();
				$id = $data['id'];
				write_log($this, __METHOD__, "id : $id");
	
				$sql = "
						insert into menus(
						    id, name, parent, seq, is_active, is_folder, link
						    , created_by, created_date, changed_by, changed_date
						) values (
							$id, '$name', $parent, $seq, $status, $is_folder, '$link'
							, '$user_login', sysdate(), '$user_login', sysdate()
						)
					";
				
			} else { // jika id sudah ada sebelumnya, maka tinggal di-update saja

				$sql = "
					update menus
					set name = '$name', parent = $parent
						, seq = $seq, is_active = $status, is_folder = $is_folder, link = '$link'
						, created_by = '$user_login', created_date = sysdate()
						, changed_by = '$user_login', changed_date = sysdate()
					where id = $id
				";
				
			}

		} else if($oper == 'edit') { // update data

			$sql = "
					update menus
					set name = '$name', parent = $parent
						, seq = $seq, is_active = $status, is_folder = $is_folder, link = '$link'
						, changed_by = '$user_login', changed_date = sysdate()
					where id = $id
				";
				
		} else if($oper == 'del') { // delete data
		
			$sql = "
					update menus
					set is_active = -1
					where id = $id
				";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");

		return $result;
		
	}

}
?>