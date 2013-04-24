<?php
class Role_menu_model extends CI_Model {

	function get_role() {
			
		$sql = "
				select id, name 
				from roles
				where id > 1 and is_active = 1
				order by upper(name)
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_role_menu() {
			
		$role_id = (isset($_REQUEST['role_id'])?$_REQUEST['role_id']:'');
		write_log($this, __METHOD__, "role_id : $role_id");

		$sql = "
				select m.id, m.name, m.parent
				  , if(isnull(rm.menu_id), 0, 1) selected
				  , m.is_active
				from menus m
				    left join role_menu rm
				    on rm.menu_id = m.id and rm.role_id = $role_id
				    	and rm.is_active = 1
		    	where m.is_active >= 0
				order by m.parent, m.seq
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
		
		return $query->result_array();
		
	}	

	function update_role_menu() {
			
		// declare
		$user_login = $this->session->userdata("id");
		$role_id = (isset($_REQUEST['role_id'])?$_REQUEST['role_id']:0);
		$menu = (isset($_REQUEST['menu'])?$_REQUEST['menu']:null);
		write_log($this, __METHOD__, "role_id : $role_id | menu : $menu");

		// delete data dulu
		$sql = "
				update role_menu
				set is_active = -1
				where role_id = $role_id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");

		$sub_menu = explode(';', $menu);
		for($i = 0; $i < sizeof($sub_menu); $i++) {
			
			$sub_menu2 = explode(',', $sub_menu[$i]);
			$seq = $sub_menu2[0];
			$menu_id = $sub_menu2[1];

			$sql = "
					select is_active 
					from role_menu
					where role_id = $role_id and menu_id = '$menu_id'
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql);
			$is_exist = $query->num_rows();
			write_log($this, __METHOD__, "is_exist : $is_exist");

			if($is_exist > 0) { // jika sudah ada datanya maka di-update status saja
				$sql = "
						update role_menu
						set is_active = 1
							, changed_by = '$user_login', changed_date = sysdate()
						where role_id = $role_id and menu_id = '$menu_id'
					";
			} else { // jika belum ada datanya makan di-insert baru
				$sql = "
						insert into role_menu(
						    role_id, menu_id, is_active
						    , created_by, created_date, changed_by, changed_date
						) values (
							$role_id, $menu_id, 1
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