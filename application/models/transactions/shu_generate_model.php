<?php
class Shu_generate_model extends CI_Model {

	function change() {
			
		// declare variable
		$user_id = $this->session->userdata("id");
		$id = (isset($_REQUEST['id'])?$_REQUEST['id']:0);		$period = (isset($_REQUEST['period'])?$_REQUEST['period']:null);
		$nominal = (isset($_REQUEST['nominal'])?$_REQUEST['nominal']:null);
		$last_buy_date = (isset($_REQUEST['last_buy_date'])?$_REQUEST['last_buy_date']:null);

		$result = false;
		$oper = (isset($_REQUEST['oper'])?$_REQUEST['oper']:null);
		write_log($this, __METHOD__, "id : $id | period : $period | nominal : $nominal | last_buy_date : $last_buy_date | oper : $oper");
		if($oper == 'add') { // insert data

			$sql = "
					select period
					from shu 
					where period = $period
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql, FALSE);
			$is_exists = $query->num_rows();
			
			if($is_exists == 1) { // untuk kondisi jika data sudah ada 
				
				return 2; // return untuk data sudah exist
				
			} else {
				
				// generate shu_id
				$sql = "
						select ifnull(max(id), 0) + 1 shu_id
						from shu
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				$data = $query->row_array();
				$shu_id = $data['shu_id'];
				write_log($this, __METHOD__, "shu_id : $shu_id");
	
				// generate shu_user_id
				$sql = "
						select ifnull(max(id), 0) + 1 shu_user_id
						from shu_user
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				$data = $query->row_array();
				$shu_user_id = $data['shu_user_id'];
				write_log($this, __METHOD__, "shu_user_id : $shu_user_id");
	
				// declare variable
				$stockholder_qty = 0;
				$stock_qty = 0;
				$shu_qty = 0;
				
				// insert data stock shu
				$sql = "
						select u.id, count(1) stock_qty
						  , (count(1) * $nominal) shu_qty
						from users u
						  , stocks s
						where s.stockholder_id = u.id
						  and s.buy_date <= str_to_date('$last_buy_date 23:59:59', '%d %b %Y %H:%i:%s')
						  and s.is_active = 1
						group by u.id
					";
				write_log($this, __METHOD__, "sql : $sql");
				$query = $this->db->query($sql, FALSE);
				foreach($query->result_array() as $value) {
					
					$shu_user_id++;
					
					// insert shu_user_stock
					$sql = "
							insert into shu_user_stock (
							    shu_user_id, stock_id, is_active
							    , created_by, created_date, changed_by, changed_date
							  )
							select $shu_user_id, id, 1
							  , '$user_id', sysdate(), '$user_id', sysdate()
							from stocks
							where stockholder_id = " . $value['id'] . " and is_active = 1
							  and buy_date <= str_to_date('$last_buy_date 23:59:59', '%d %b %Y %H:%i:%s')
						";
					write_log($this, __METHOD__, "sql : $sql");
					$result = $this->db->query($sql);
					write_log($this, __METHOD__, "result : $result");
			
					// insert shu_user
					$sql = "
							insert into shu_user (
							    id, shu_id, stockholder_id, is_active
							    , stock_qty, shu_qty
							    , created_by, created_date, changed_by, changed_date
							  )
							values (
								$shu_user_id, $shu_id, " . $value['id'] . ", 1
								, " . $value['stock_qty'] . ", " . $value['shu_qty'] . "
								, '$user_id', sysdate(), '$user_id', sysdate()
							  )  
						";
					write_log($this, __METHOD__, "sql : $sql");
					$result = $this->db->query($sql);
					write_log($this, __METHOD__, "result : $result");
					
					$stockholder_qty++;
					$stock_qty = $stock_qty + $value['stock_qty'];
					$shu_qty = $shu_qty + $value['shu_qty'];
	
			
				}
				write_log($this, __METHOD__, "stockholder_qty : $stockholder_qty | stock_qty : $stock_qty | shu_qty : $shu_qty");							
				// insert shu data
				$sql = "
						insert into shu(
							id, period, nominal, is_active, last_buy_date
							, stockholder_qty, stock_qty, shu_qty
							, created_by, created_date, changed_by, changed_date
						) values (
							$shu_id, '$period', '$nominal', 1, str_to_date('$last_buy_date', '%d %b %Y')
							, $stockholder_qty, $stock_qty, $shu_qty
							, '$user_id', sysdate(), '$user_id', sysdate()
						)
					";
				write_log($this, __METHOD__, "sql : $sql");
				$result = $this->db->query($sql);
				write_log($this, __METHOD__, "result : $result");
			
			}

		} if($oper == 'del') { // insert data
				
			$sql = "
					select stock_received
					from shu s
					where s.id = $id
				";
			write_log($this, __METHOD__, "sql : $sql");
			$query = $this->db->query($sql, FALSE);
			$data = $query->row_array();
			$stock_received = $data['stock_received'];
			
			if($stock_received > 0) {
				
				return 2;
				
			} else {
					
				// delete shu_user_stock
				$sql = "
						delete from shu_user_stock
						where shu_user_id in (
						    select id
						    from shu_user
						    where shu_id = $id
						  )
					";
				write_log($this, __METHOD__, "sql : $sql");
				$result = $this->db->query($sql);
				write_log($this, __METHOD__, "result : $result");
				
				// delete shu_user
				$sql = "
						delete from shu_user
						where shu_id = $id
					";
				write_log($this, __METHOD__, "sql : $sql");
				$result = $this->db->query($sql);
				write_log($this, __METHOD__, "result : $result");

				// delete shu
				$sql = "
						delete from shu 
						where id = $id
					";
				write_log($this, __METHOD__, "sql : $sql");
				$result = $this->db->query($sql);
				write_log($this, __METHOD__, "result : $result");
  				
			}
				
		}
		
		if($result) {
			return 1;
		} else {
			return 0;
		}
				
	}

}
?>