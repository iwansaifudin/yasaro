<?php
class Stock_dialog_model extends CI_Model {
	
	function get_cluster() {
			
		$sql = "
				select id, name
				from clusters
				where is_active = 1
				order by id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
		
		return $query->result_array();
		
	}

}
?>