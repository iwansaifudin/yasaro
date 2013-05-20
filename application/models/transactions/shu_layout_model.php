<?php
class Shu_layout_model extends CI_Model {

	function get_list_1() {
			
		$key = (isset($_REQUEST['key'])?$_REQUEST['key']:'null');
		write_log($this, __METHOD__, "key : $key");
		
		$where = "";
		$keys = explode(" ", trim($key));
		for($i = 0;  $i < sizeof($keys); $i++) {
			
			if($i == 0) {
				$where = " where ";
			} else {
				$where .= " and ";
			}
			
			$where .= "concat(name, cluster, period) like '%".$keys[$i]."%'";

		}

		$sql = "
				select id, name, cluster, period
			      , stock_qty, stock_received, stock_remain
			      , shu_qty, shu_received, shu_remain
				from (
						select u.id, u.name, c.name cluster, a.period
					      , a.stock_qty, a.stock_received, a.stock_remain
					      , a.shu_qty, a.shu_received, a.shu_remain
						from users u
						  left outer join (
						    select su.stockholder_id, s.period
						      , su.stock_qty, su.stock_received
						      , (su.stock_qty - su.stock_received) stock_remain
						      , su.shu_qty, su.shu_received
						      , (su.shu_qty - su.shu_received) shu_remain
						    from shu s
						      , shu_user su
						    where s.period = (select max(period) from shu)
						      and su.shu_id = s.id
						  ) a
						    on a.stockholder_id = u.id
						  , clusters c
						where c.id = u.cluster
					) a
				$where
				order by name
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
				select st.id, st.form_id, f.name form_name
				  , s.period, st.ref_no
				  , date_format(st.trans_date, '%Y/%m/%d') trans_date
				  , st.trans_status status
				from shu_trans st
				  , forms f
				  , shu s
				where st.stockholder_id = $id
				  and f.id = st.form_id and f.groups = 2
				  and s.id = st.shu_id
				order by st.id desc
			";
  
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}	
}
?>