<?php
class Login_model extends CI_Model {
	
	function validate() {
		
		// get parameter
		$user = $this->input->post('user');
		$pass = strtolower($this->input->post('pass'));
		$pass = ($pass==''?'null':$pass);
		$pass = md5($pass);
		write_log($this, __METHOD__, "user : $user | pass : $pass");
		
		// validation user
		$sql = "
				select is_active
					, case when pass = '$pass' then 1 else 0 end pass_flag
				from users
				where code = '$user'
			";
		write_log($this, __METHOD__, "sql : $sql");		$query = $this->db->query($sql, FALSE);
		$count = $query->num_rows();
		write_log($this, __METHOD__, "count : $count");		
		$is_active = 0;
		$pass_flag = 0;
		if($count > 0) {
		
			$data = $query->row_array();
			$is_active = $data['is_active'];
			$pass_flag = $data['pass_flag'];
			write_log($this, __METHOD__, "user : $user | is_active : $is_active");			
		}

		/* information
		status = -1 (user unregisterd)
		status = 0 (user is not active)
		status = 1 (user is active)
		status = 2 (password is wrong)
		*/
		if($count == 0) { // user unregitered
			
			$result['status'] = -1;
			
		} else if($is_active == 0) { // user is not active
			
			$result['status'] = 0;
			
		// } else if($pass_flag == 0) { // password is wrong -> untuk tes, tutup bagian ini
// 			
			// $result['status'] = 2;
		} else { // user is active

			$result['status'] = 1;

		}
		
		return $result;

	}
	
	function get_profile($user) {
		
		write_log($this, __METHOD__, "user : $user");

		$sql = "
				select u.code id, u.name
				  , if(u.gender = 'M', 'Laki-Laki', 'Perempuan') gender
				  , c.name cluster 
				  , u.menu_expand
				from users u
				  , clusters c
				where u.code = '$user' and u.is_active = 1
				  and c.id = u.cluster
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql, FALSE);
		$data = $query->row_array();
		write_log($this, __METHOD__, "id : " . $data['id'] . " | name : " . $data['name'] . " | gender : " . $data['gender'] . " | cluster : " . $data['cluster']);

		return $data;
		
	}
	
	function set_login($user) {
		
		write_log($this, __METHOD__, "user : $user");

		$sql = "
				update users
				set is_login = 1, login_date = sysdate()
				where code = '$user'
			";
		write_log($this, __METHOD__, "sql : $sql");
		$return = $this->db->query($sql);
		write_log($this, __METHOD__, "return : $return");
		
	}
	
}
?>	