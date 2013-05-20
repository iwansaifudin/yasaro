<?php
class User_model extends CI_Model {

	function get_cluster() {

		$sql = "
				select id, name
				from clusters
				where is_active = 1 and level = 2
				order by name
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_family() {

		$sql = "
				select id, name
				from families
				where is_active = 1
				order by id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_form() {

		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:null);
		write_log($this, __METHOD__, "id : $id");
		
		$sql = "
				select u.id, u.code, u.name
				  , u.birth_place, date_format(u.birth_date, '%e %b %Y') birth_date
				  , date_format(from_days(to_days(now()) - to_days(u.birth_date)), '%Y') + 0 age, u.gender
				  , u.address1, u.address2, u.telephone, u.handphone
				  , u.patriarch patriarch_id, (select name from users where id = u.patriarch) patriarch_name
				  , u.family, u.cluster, u.nationality
				  , u.stock_qty, u.stock_total_price, u.is_active status, u.information
				from users u
				where u.id = '$id' 
			";
		write_log($this, __METHOD__, "sql : $sql");
		
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			$data = $query->row_array();
			write_log($this, __METHOD__, "id : " . $data['id'] . " | code : " . $data['code'] . " | name : " . $data['name'] . " | gender : " . $data['gender']);
		} else {
			$data = null;
		}

		return $data;
		
	}
	
	function save() {
			
		$user_id = $this->session->userdata("id");
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:null);
		$code = (isset($_REQUEST['code'])?$_REQUEST['code']:null);
		$name = (isset($_REQUEST['name'])?$_REQUEST['name']:null);
		$status = (isset($_REQUEST['status'])?$_REQUEST['status']:null);
		$birth_place = (isset($_REQUEST['birth_place'])?$_REQUEST['birth_place']:null);
		$birth_date = (isset($_REQUEST['birth_date'])?$_REQUEST['birth_date']:null);
		$birth_date = (($birth_date==null||$birth_date='')?"str_to_date(null, '%e %b %Y')":"str_to_date('$birth_date', '%e %b %Y')");
		$gender = (isset($_REQUEST['gender'])?$_REQUEST['gender']:null);
		$address1 = (isset($_REQUEST['address1'])?$_REQUEST['address1']:null);
		$address2 = (isset($_REQUEST['address2'])?$_REQUEST['address2']:null);
		$telephone = (isset($_REQUEST['telephone'])?$_REQUEST['telephone']:null);
		$handphone = (isset($_REQUEST['handphone'])?$_REQUEST['handphone']:null);
		$patriarch = (isset($_REQUEST['patriarch'])?$_REQUEST['patriarch']:null);
		$family = (isset($_REQUEST['family'])?$_REQUEST['family']:0);
		$cluster = (isset($_REQUEST['cluster'])?$_REQUEST['cluster']:0);
		$nationality = (isset($_REQUEST['nationality'])?$_REQUEST['nationality']:0);
		$information = (isset($_REQUEST['information'])?$_REQUEST['information']:null);
		write_log($this, __METHOD__, "
			id : $id | code : $code | name : $name | status : $status | birth_place : $birth_place | birth_date : $birth_date 
			| gender : $gender | address1 : $address1 | address2 : $address2 
			| telephone : $telephone | patriarch : $patriarch | family : $family | cluster : $cluster | nationality : $nationality | information : $information
		");

		$sql = "
				select count(1) exist
				from users 
				where id = '$id'
			";
		$query = $this->db->query($sql);
		
		$data = $query->row_array();
		if($data['exist'] == 0) { // insert data

			$sql = "
					select ifnull(max(id), 0) + 1 id
					from users
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql);
			$data = $query->row_array();
			$id = $data['id'];
			if($patriarch == null) $patriarch = $id;
			write_log($this, __METHOD__, "id : $id");
	
			$sql = "
					insert into users(
						id, code, name, is_active
						, birth_place, birth_date, gender
						, address1, address2, telephone, handphone
						, patriarch, family, cluster, nationality, information
						, created_by, created_date, changed_by, changed_date
					) values(
						'$id', '$code', '$name', '$status'
						, '$birth_place', $birth_date, '$gender'
						, '$address1', '$address2', '$telephone', '$handphone'
						, '$patriarch', '$family', '$cluster', '$nationality', '$information'
						, '$user_id', now(), '$user_id', now()
					)
				";

		} else { // update data

			$sql = "
					update users
					set code = '$code', name = '$name', is_active = '$status'
						, birth_place = '$birth_place', birth_date = $birth_date
						, gender = '$gender'
						, address1 = '$address1', address2 = '$address2'
						, telephone = '$telephone', handphone = '$handphone'
						, patriarch = '$patriarch', family = '$family'
						, cluster = '$cluster', nationality = '$nationality', information = '$information'
						, changed_by = '$user_id', changed_date = now()
					where id = '$id'
				";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
		
		return array('id' => $id, 'result' => $result);
		
	}

	function reset_pass() {
			
		// declare variable
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:0);
		write_log($this, __METHOD__, "id : $id");

		// update password to 'yasaro'
		$sql = "
				update users
				set pass = '75161991bad7005d02b876c305090571'
				where id = '$id'
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
		
		if($result) {return 1;} else {return 0;}
		
	}

}
?>