<?php
class User_layout_model extends CI_Model {

	function get_list_1() {
			
		$key = (isset($_REQUEST['key'])?$_REQUEST['key']:'null');
		write_log($this, __METHOD__, "key : $key");

		$sql = "
				select u.id, u.code, u.name, c.name cluster, u.address
				from users u
					, clusters c
				where (u.code like '%$key%' or u.name like '%$key%' or c.name like '%$key%')
					and c.id = u.cluster
				order by u.name
				limit 100
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}
	
	function get_list_2() {
			
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:'null');
		write_log($this, __METHOD__, "id : $id");

		$sql = "
				select a.id, a.name
				  , a.family, a.stock_qty
				from (
				    select u2.id, u2.name
				      , f.name family, u2.stock_qty
				    from users u1
				      , users u2
				      , families f
				    where u1.id = '$id'
				      and u2.id = u1.patriarch
				      and f.id = u2.family
				    union
				    select u2.id, u2.name
				      , f.name family, u2.stock_qty
				    from users u1
				      , users u2
				      , families f
				    where u1.id = '$id'
				      and u2.patriarch = u1.patriarch
				      and f.id = u2.family
				    union
				    select u.id, u.name
				      , f.name family, u.stock_qty
				    from users  u
				      , families f
				    where u.patriarch = '$id'
				      and f.id = u.family
				  ) a
				order by a.name
				limit 100
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}
	
	function check_code() {
			
		$code = (isset($_REQUEST['code'])?$_REQUEST['code']:'null');
		write_log($this, __METHOD__, "code : $code");

		$sql = "
				select count(1) exist
				from users
				where code = '$code'
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		$data = $query->row_array();
		write_log($this, __METHOD__, "count : " . $data['exist']);
				
		return array('exist' => $data['exist']);

	}

}
?>