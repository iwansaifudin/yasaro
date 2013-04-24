<?php
class Stock_layout_model extends CI_Model {

	function get_list_1() {
			
		$key = (isset($_REQUEST['key'])?$_REQUEST['key']:'null');
		write_log($this, __METHOD__, "key : $key");

		$sql = "
				select a.id, a.name, a.cluster
					, a.address, a.stock_qty, a.stock_price
				from (
						select u.id, u.name, c.name cluster
							, u.address, u.stock_qty
							, (u.stock_qty * 25000) stock_price
						from users u
							, clusters c
						where c.id = u.cluster
					) a
				where a.id like '%$key%' or a.name like '%$key%' or a.cluster like '%$key%'
				order by a.name
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
				select id, form_id, form_name
					, ref_no, trans_date, status
				from (
				    select st.id, st.form_id
				      , if(st.form_id = 2, concat(f.name, '(-)'), f.name) form_name
				      , st.ref_no, date_format(st.trans_date, '%Y/%m/%d') trans_date
				      , st.trans_status status
				    from stock_trans st
				      , forms f
				    where st.stockholder_id_from = '$id'
				      and f.id = st.form_id and f.groups = 1
				    union all
				    select st.id, st.form_id
				      , if(st.form_id = 2, concat(f.name, '(+)'), f.name) form_name
				      , st.ref_no
				      , date_format(st.trans_date, '%Y/%m/%d') trans_date
				      , st.trans_status status
				    from stock_trans st
				      , forms f
				    where st.stockholder_id_to = '$id'
				      and f.id = st.form_id and f.groups = 1
				    ) a
				order by id desc
			";
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}	
}
?>