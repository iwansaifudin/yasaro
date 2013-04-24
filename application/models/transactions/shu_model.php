<?php
class Shu_model extends CI_Model {

	function get_form() {
			
		$user_id = (isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0);
		$trans_id = (isset($_REQUEST['trans_id'])?$_REQUEST['trans_id']:0);
		write_log($this, __METHOD__, "user_id : $user_id | trans_id : $trans_id");

		if($trans_id > 0) {

			$sql = "
					select st.id trans_id, st.ref_no
					  , date_format(st.trans_date, '%d %b %Y %H:%i:%s') trans_date, st.trans_status
					  , st.shu_id, s.nominal shu_nominal
					  , st.stockholder_id, u.name stockholder_name, c.name stockholder_cluster
					  , st.stock_qty, st.stock_trans, st.stock_before, st.stock_after
					  , st.shu_qty, st.shu_trans, st.shu_before, st.shu_after
					  , st.message
					from shu_trans st
					  , shu s
					  , users u
					  , clusters c
					where st.id = $trans_id
					  and s.id = st.shu_id
					  and u.id = st.stockholder_id
					  and c.id = u.cluster
				";

		} else {

			$sql = "
	  				select a.shu_id, u.id stockholder_id, u.name stockholder_name
					  , c.name stockholder_cluster, a.shu_nominal
					  , a.stock_qty, a.stock_before
					  , a.shu_qty, a.shu_before
					from users u
					  left outer join (
					    select s.id shu_id, su.stockholder_id, s.nominal shu_nominal
					      , su.stock_qty, su.stock_received stock_before
					      , su.shu_qty, su.shu_received shu_before
					    from shu s
					      , shu_user su
					    where s.period = (select max(period) from shu)
					      and su.shu_id = s.id
					  ) a
					    on a.stockholder_id = u.id
					  , clusters c
					where u.id = $user_id
					  and c.id = u.cluster
  				";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->row_array();

	}
	
	function get_period() {
			
		$sql = "
				select id, period
				from shu
				where is_active = 1
				order by period desc
			";
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}
	
	function get_stock_detail() {
			
		$trans_id = (isset($_REQUEST['trans_id'])?$_REQUEST['trans_id']:0);
		write_log($this, __METHOD__, "trans_id : $trans_id");

		$sql = "
				select std.stock_id, stk.name stock_name
				from shu_trans st
				  , shu_trans_detail std
				  , stocks stk
				where st.id = $trans_id
				  and std.shu_trans_id = st.id
				  and stk.id = std.stock_id
			";
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}
	function period_change() {
			
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		$stockholder_id = (isset($_REQUEST['stockholder_id'])?$_REQUEST['stockholder_id']:0);
		write_log($this, __METHOD__, "shu_id : $shu_id | stockholder_id : $stockholder_id");

		$sql = "
				select s.nominal shu_nominal
				  , su.stock_qty, su.stock_received stock_before
				  , su.shu_qty, su.shu_received shu_before
				from shu s
				  , shu_user su
				where s.id = $shu_id
				  and su.shu_id = s.id and stockholder_id = '$stockholder_id'
			";
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->row_array();

	}	
	function approve() {
		
		// declare variable public 
		$user_id = $this->session->userdata("id");
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		$stockholder_id = (isset($_REQUEST['stockholder_id'])?$_REQUEST['stockholder_id']:0);
		$stock_qty = (isset($_REQUEST['stock_qty'])?$_REQUEST['stock_qty']:0);
		$stock_trans = (isset($_REQUEST['stock_trans'])?$_REQUEST['stock_trans']:0);
		$stock_before = (isset($_REQUEST['stock_before'])?$_REQUEST['stock_before']:0);
		$stock_after = (isset($_REQUEST['stock_after'])?$_REQUEST['stock_after']:0);
		$shu_qty = (isset($_REQUEST['shu_qty'])?$_REQUEST['shu_qty']:0);
		$shu_trans = (isset($_REQUEST['shu_trans'])?$_REQUEST['shu_trans']:0);
		$shu_before = (isset($_REQUEST['shu_before'])?$_REQUEST['shu_before']:0);
		$shu_after = (isset($_REQUEST['shu_after'])?$_REQUEST['shu_after']:0);
		$message = (isset($_REQUEST['message'])?$_REQUEST['message']:null);
		write_log($this, __METHOD__, "user_id : $user_id 
			| form_id : $form_id | stockholder_id : $stockholder_id 
			| stock_qty : $stock_qty | stock_trans : $stock_trans | stock_before : $stock_before | stock_after : $stock_after
			| shu_qty : $shu_qty | shu_trans : $shu_trans | shu_before : $shu_before | shu_after : $shu_after
			| message : $message
		");

		// generate trans_id & ref_no
		$trans_id = $this->sequence->get_sequence('shu_id');
		$trans_id = $this->sequence->get_date('%y') . str_pad($trans_id, 5, "0", STR_PAD_LEFT);

		if($form_id == 1) { // division
			
			$division_id = $this->sequence->get_sequence('division_id');
			$ref_no = 'D/' . $this->sequence->get_date('%Y') . '/' . romanic_number($this->sequence->get_date('%m')) . '/' . str_pad($division_id, 5, "0", STR_PAD_LEFT);

		} else if($form_id == 2) { // cancellation
			
			$cancellation_id = $this->sequence->get_sequence('cancellation_id');
			$ref_no = 'C/' . $this->sequence->get_date('%Y') . '/' . romanic_number($this->sequence->get_date('%m')) . '/' . str_pad($cancellation_id, 5, "0", STR_PAD_LEFT);

		}
		write_log($this, __METHOD__, "trans_id : $trans_id | ref_no : $ref_no");
			
		/*** DETAIL ***/
		for($j = 1; $j <= $stock_trans; $j++) {
				
			// declare stock variable
			$stock_id = (isset($_REQUEST['stock_id_' . $j])?$_REQUEST['stock_id_' . $j]:null);

			// update data transaksi detail
			$sql = "
					insert into shu_trans_detail(shu_trans_id, stock_id, status)
					values($trans_id, $stock_id, 1)
				";
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");
			
			// update data stock detail
			$status = ($form_id==1?2:1);
			
			$sql = "
					update shu_user_stock sus
					set sus.is_active = $status
					where sus.shu_user_id = (
					    select id
					    from shu_user su
					    where su.shu_id = $shu_id and su.stockholder_id = $stockholder_id
					  )
					  and sus.stock_id = $stock_id
				";
			
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");
			
		}

		/*** HEADER ***/
		//updat data transaksi header
		$sql = "
				insert into shu_trans(
				  id, form_id, ref_no, trans_date, trans_status
				  , user_id, stockholder_id, shu_id
				  , stock_qty, stock_trans, stock_before, stock_after
				  , shu_qty, shu_trans, shu_before, shu_after
				  , message
				)
				values(
					$trans_id, $form_id, '$ref_no', sysdate(), 1
					, '$user_id', $stockholder_id, $shu_id
				    , $stock_qty, $stock_trans, $stock_before, $stock_after
				    , $shu_qty, $shu_trans, $shu_before, $shu_after
					, '$message'
				)
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
		
		$sql = "
				update shu_user
				set stock_received = $stock_after, shu_received = $shu_after
				where shu_id = $shu_id and stockholder_id = $stockholder_id
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
		
		// update shu table
		if($form_id == 1) { // division
			
			$sql = "
					update shu
					set stock_received = stock_received + $stock_trans
					  , shu_received = shu_received + $shu_trans
					where id = $shu_id
				";

		} else if($form_id == 2) { // cancellation
			
			$sql = "
					update shu
					set stock_received = stock_received - $stock_trans
					  , shu_received = shu_received - $shu_trans
					where id = $shu_id
				";

		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
	
		return array('trans_id' => $trans_id, 'ref_no' => $ref_no, 'trans_status' => ($result?1:0));

	}
}
?>