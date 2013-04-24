<?php
class Sequence extends CI_Model {
	
	function get_date($format) {
		
		write_log($this, __METHOD__, "format : $format");

		$sql = "
				select date_format(sysdate(), '$format') dt
			";
		$query = $this->db->query($sql);
		$data = $query->row_array();
		
		return $data['dt'];
		
	}
	
	function get_sequence($seq_name) {
		
		write_log($this, __METHOD__, "seq_name : $seq_name");

		$sql = "
				select ifnull(counter, 0) counter
				from sequences 
				where seq_name = '$seq_name'
			";
		$query = $this->db->query($sql);

		$counter = 0;
		if($query->num_rows() > 0) {

			$data = $query->row_array();
			$counter = $data['counter'] + 1;

			$sql = "
				    update sequences
				    set counter = $counter
				    where seq_name = '$seq_name'
				";
			$result = $this->db->query($sql);

		} 

		return $counter;

	}
	
}
?>