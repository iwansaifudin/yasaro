<?php
class Logout_model extends CI_Model {
	
	function set_logout($user) {
		
		$menu_expand = (isset($_REQUEST['menu_expand'])?$_REQUEST['menu_expand']:null);
		write_log($this, __METHOD__, "user : $user | menu_expand : $menu_expand");

		$sql = "
				update users
				set menu_expand = menu_expand('$menu_expand')
					, is_login = 0, login_date = null
				where code = '$user'
			";
		write_log($this, __METHOD__, "sql : $sql");
		$return = $this->db->query($sql);
		write_log($this, __METHOD__, "return : $return");		
	}
	
}
?>	