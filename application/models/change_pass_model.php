<?php
class Change_pass_model extends CI_Model {
	
	function validate() {
			
		// get parameter
		$user = (isset($_REQUEST['user'])?$_REQUEST['user']:null);
		$first_change_pass = (isset($_REQUEST['first_change_pass'])?strtolower($_REQUEST['first_change_pass']):0);
		$old_pass = (isset($_REQUEST['old_pass'])?strtolower($_REQUEST['old_pass']):null);
		$new_pass1 = (isset($_REQUEST['new_pass1'])?strtolower($_REQUEST['new_pass1']):null);
		$new_pass2 = (isset($_REQUEST['new_pass2'])?strtolower($_REQUEST['new_pass2']):null);
		write_log($this, __METHOD__, "user : $user | first_change_pass : $first_change_pass | old_pass : $old_pass | new_pass1 : $new_pass1 | new_pass2 : $new_pass2");
		
		$old_pass = ($old_pass==''||$old_pass==null?'null':$old_pass);
		$new_pass1 = ($new_pass1==''||$new_pass1==null?'null':$new_pass1);
		$new_pass2 = ($new_pass2==''||$new_pass2==null?'null':$new_pass2);

		// validation
		$sql = "
				select count(1) qty 
				from users 
				where code = '$user' and pass = md5('$old_pass')
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql, FALSE);
		$data = $query->row_array();
		write_log($this, __METHOD__, "qty : " . $data['qty']);

		if($data['qty'] > 0) { // 0:show error; 1:not show error
			$old_pass_flag = 1;
		} else {
			$old_pass_flag = 0;
		}

		if($new_pass1 == $new_pass2) { // 0:show error; 1:not show error
			$new_pass_flag = 1;
		} else {
			$new_pass_flag = 0;
		}
		
		// change password & set return variable
		$result['old_pass_flag'] = $old_pass_flag;
		$result['new_pass_flag'] = $new_pass_flag;

		if($old_pass_flag == 1 and $new_pass_flag == 1) {

			$sql = "
					update users
					set pass = md5('$new_pass1')
					where code = '$user' and pass = md5('$old_pass')
				";
			write_log($this, __METHOD__, "sql : $sql");
			$return = $this->db->query($sql);
			write_log($this, __METHOD__, "return : $return");
			
			$result['status'] = true;
			
		} else {

			$result['status'] = false;

		}
		
		return $result;

	}
	
}
?>	