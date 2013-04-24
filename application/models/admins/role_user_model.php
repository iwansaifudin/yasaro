<?php
class Role_user_model extends CI_Model {

	function get_role() {
			
		$sql = "
				select id, name 
				from roles 
				where is_active = 1
				order by upper(name)
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_user_list() {
			
		$role_id = (isset($_REQUEST['role_id'])?$_REQUEST['role_id']:'');
		$search_key = (isset($_REQUEST['search_key'])?$_REQUEST['search_key']:'');
		write_log($this, __METHOD__, "role_id : $role_id | search_key : $search_key");

		$sql = "
				select u.code id, u.name, c.name cluster
				from users u
				  , clusters c
				where (
				        lower(u.code) like lower('%$search_key%')
				        or lower(u.name) like lower('%$search_key%')
				        or lower(c.name) like lower('%$search_key%')
				    ) 
				    and not exists (
				        select 1
				        from role_user ru
				        where ru.role_id = $role_id and ru.is_active = 1
				            and ru.user_id = u.code
				    )
				    and u.is_active = 1
				    and c.id = u.cluster
				order by u.code
				limit 25
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
		
		return $query->result_array();
		
	}
	
	function get_user_role_list() {

		$role_id = (isset($_REQUEST['role_id'])?$_REQUEST['role_id']:'');
		write_log($this, __METHOD__, "role_id : $role_id");

		$sql = "
				select ru.user_id id, u.name, c.name cluster
				from role_user ru
				    , users u
            		, clusters c
				where ru.role_id = $role_id and ru.is_active = 1
				    and u.code = ru.user_id 
	            	and c.id = u.cluster
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function update_user_role() {
			
		// declare
		$user_login = $this->session->userdata("id");
		$role_id = (isset($_REQUEST['role_id'])?$_REQUEST['role_id']:'');
		$length = (isset($_REQUEST['length'])?$_REQUEST['length']:0);
		write_log($this, __METHOD__, "role_id : $role_id | length : $length");
				
		// reset data dulu
		$sql = "
				update role_user
		        set is_active = -1
		        where role_id = $role_id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");

		// insert data kembali
		for($i = 1;  $i <= $length; $i++) {
			
			$user_id = (isset($_REQUEST["user_id$i"])?$_REQUEST["user_id$i"]:null);
			
			$sql = "
					select is_active 
					from role_user
					where role_id = $role_id and user_id = '$user_id'
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql);
			$is_exist = $query->num_rows();
			write_log($this, __METHOD__, "is_exist : $is_exist");

			if($is_exist > 0) { // jika sudah ada datanya maka di-update status saja
				$sql = "
						update role_user
						set is_active = 1
							, changed_by = '$user_login', changed_date = sysdate()
						where role_id = $role_id and user_id = '$user_id'
					";
			} else { // jika belum ada datanya makan di-insert baru
				$sql = "
						insert into role_user(
							role_id, user_id, is_active
							, created_by, created_date, changed_by, changed_date
						) values (
							$role_id, '$user_id', 1
							, '$user_login', sysdate(), '$user_login', sysdate()
						)
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