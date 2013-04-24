<?php
class Stock_model extends CI_Model {

	function get_form() {
			
		$user_id = (isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0);
		$trans_id = (isset($_REQUEST['trans_id'])?$_REQUEST['trans_id']:0);
		write_log($this, __METHOD__, "user_id : $user_id | trans_id : $trans_id");

		if($trans_id > 0) {

			$sql = "
					select st.id trans_id, st.ref_no
						, date_format(st.trans_date, '%d %b %Y %H:%i:%s') trans_date, st.trans_status
						, st.stockholder_id_from, u1.name stockholder_name_from, c1.name stockholder_cluster_from
						, st.stockholder_id_to, u2.name stockholder_name_to,  c2.name stockholder_cluster_to
						, st.qty, st.total_price
						, st.qty_from_before, st.qty_from_after
						, st.qty_to_before, st.qty_to_after
						, st.message
					from stock_trans st
						left outer join users u2 on u2.id = st.stockholder_id_to
						left outer join clusters c2 on c2.id = u2.cluster
						, users u1
						, clusters c1
					where st.id = $trans_id
						and u1.id = st.stockholder_id_from
						and c1.id = u1.cluster
				";
      			
		} else {

			$sql = "
					select u.id stockholder_id_from, u.name stockholder_name_from
						, c.name stockholder_cluster_from, u.stock_qty qty_from_before
					from users u
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
	
	function get_stock_detail() {
			
		$trans_id = (isset($_REQUEST['trans_id'])?$_REQUEST['trans_id']:0);
		write_log($this, __METHOD__, "trans_id : $trans_id");

		$sql = "
				select std.stock_id, s.name stock_name
				from stock_trans_detail std
					, stocks s
				where std.stock_trans_id = $trans_id
					and std.status = 1
					and s.id = std.stock_id
			";
		write_log($this, __METHOD__, "sql : " . $sql);
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();

	}	
	function approve() {
		
		// declare variable public 
		$user_id = $this->session->userdata("id");
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$stockholder_id_from = (isset($_REQUEST['stockholder_id_from'])?$_REQUEST['stockholder_id_from']:0);
		$stockholder_id_to = (isset($_REQUEST['stockholder_id_to'])?$_REQUEST['stockholder_id_to']:0);
		$stock_qty = (isset($_REQUEST['stock_qty'])?$_REQUEST['stock_qty']:0);
		$stock_total_price = (isset($_REQUEST['stock_total_price'])?$_REQUEST['stock_total_price']:0);
		$stock_qty_from_before = (isset($_REQUEST['stock_qty_from_before'])?$_REQUEST['stock_qty_from_before']:0);
		$stock_qty_from_after = (isset($_REQUEST['stock_qty_from_after'])?$_REQUEST['stock_qty_from_after']:0);
		$stock_qty_to_before = (isset($_REQUEST['stock_qty_to_before'])?$_REQUEST['stock_qty_to_before']:0);
		$stock_qty_to_after = (isset($_REQUEST['stock_qty_to_after'])?$_REQUEST['stock_qty_to_after']:0);
		$message = (isset($_REQUEST['message'])?$_REQUEST['message']:null);
		write_log($this, __METHOD__, "user_id : 
			$user_id | form_id : $form_id | stockholder_id_from : $stockholder_id_from | stockholder_id_to : $stockholder_id_to 
			| stock_qty : $stock_qty | stock_qty_from_before : $stock_qty_from_before | stock_qty_from_after : $stock_qty_from_after
			| stock_qty_to_before : $stock_qty_to_before | stock_qty_to_after : $stock_qty_to_after
			| message : $message
		");
		

		// generate trans_id & ref_no
		$trans_id = $this->sequence->get_sequence('stock_id');
		$trans_id = $this->sequence->get_date('%y') . str_pad($trans_id, 5, "0", STR_PAD_LEFT);

		if($form_id == 1) { // buy
			
			$buy_id = $this->sequence->get_sequence('buy_id');
			$ref_no = 'B/' . $this->sequence->get_date('%Y') . '/' . romanic_number($this->sequence->get_date('%m')) . '/' . str_pad($buy_id, 5, "0", STR_PAD_LEFT);

		} else if($form_id == 2) { // mutation
			
			$mutation_id = $this->sequence->get_sequence('mutation_id');
			$ref_no = 'M/' . $this->sequence->get_date('%Y') . '/' . romanic_number($this->sequence->get_date('%m')) . '/' . str_pad($mutation_id, 5, "0", STR_PAD_LEFT);

		} else if($form_id == 3) { // sell

			$sell_id = $this->sequence->get_sequence('sell_id');
			$ref_no = 'S/' . $this->sequence->get_date('%Y') . '/' . romanic_number($this->sequence->get_date('%m')) . '/' . str_pad($sell_id, 5, "0", STR_PAD_LEFT);
			
		}
		write_log($this, __METHOD__, "trans_id : $trans_id | ref_no : $ref_no");
			
		/*** DETAIL ***/
		for($j = 1; $j <= $stock_qty; $j++) {
				
			// declare stock variable
			$stock_id = (isset($_REQUEST['stock_id_' . $j])?$_REQUEST['stock_id_' . $j]:null);

			// update data transaksi detail
			$sql = "
					insert into stock_trans_detail(stock_trans_id, stock_id, status)
					values($trans_id, $stock_id, 1)
				";
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");
			
			// update data stock detail
			if($form_id == 1) { // buy
			
				$sql = "
						update stocks
						set stockholder_id = $stockholder_id_from
							, buy_date = sysdate()
						where id = $stock_id
					";
			
			} else if($form_id == 2) { // mutation

				$sql = "
						update stocks
						set stockholder_id = $stockholder_id_to
						where id = $stock_id
					";
			
			} else if($form_id == 3) { // sell
			
				$sql = "
						update stocks
						set stockholder_id = null
							, buy_date = null
						where id = $stock_id
					";
			
			}
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");
			
		}

		/*** HEADER ***/
		//updat data transaksi header
		$sql = "
				insert into stock_trans(
				  id, form_id, ref_no, trans_date, trans_status, user_id, stockholder_id_from, stockholder_id_to
				  , qty, total_price
				  , qty_from_before, qty_from_after, qty_to_before, qty_to_after
				  , message
				)
				values(
					$trans_id, $form_id, '$ref_no', sysdate(), 1, '$user_id', $stockholder_id_from, $stockholder_id_to
					, $stock_qty, $stock_total_price
					, $stock_qty_from_before, $stock_qty_from_after, $stock_qty_to_before, $stock_qty_to_after
					, '$message'
				)
			";
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
		
		if($form_id == 1) { // buy
		
			$sql = "
					update users
					set stock_qty = $stock_qty_from_after
						, stock_total_price = ifnull(stock_total_price, 0) + $stock_total_price
					where id = '$stockholder_id_from'
				";
				
		} else if($form_id == 2) { // mutation

			$sql = "
					update users
					set stock_qty = $stock_qty_to_after
						, stock_total_price = ifnull(stock_total_price, 0) + $stock_total_price
					where id = '$stockholder_id_to'
				";
			write_log($this, __METHOD__, "sql : $sql");
			$result = $this->db->query($sql);
			write_log($this, __METHOD__, "result : $result");

			$sql = "
					update users
					set stock_qty = $stock_qty_from_after
						, stock_total_price = ifnull(stock_total_price, 0) - $stock_total_price
					where id = '$stockholder_id_from'
				";
				
		} else if($form_id == 3) { // sell
		
			$sql = "
					update users
					set stock_qty = $stock_qty_from_after
						, stock_total_price = ifnull(stock_total_price, 0) - $stock_total_price
					where id = '$stockholder_id_from'
				";
				
		}
		write_log($this, __METHOD__, "sql : $sql");
		$result = $this->db->query($sql);
		write_log($this, __METHOD__, "result : $result");
	
		return array('trans_id' => $trans_id, 'ref_no' => $ref_no, 'trans_status' => ($result?1:0));

	}

}
?>