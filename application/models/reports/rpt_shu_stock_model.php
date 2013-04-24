<?php
class Rpt_shu_stock_model extends CI_Model {
		
	function get_period() {
			
		$sql = "
				select id, period name
				from shu
				order by period desc
			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());
				
		return $query->result_array();
		
	}

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
	
	function get_rpt_shu_stock() {
			
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		$cluster_id = (isset($_REQUEST['cluster_id'])?$_REQUEST['cluster_id']:0);
		$user_name = (isset($_REQUEST['user_name'])?$_REQUEST['user_name']:null);
		write_log($this, __METHOD__, "cluster_id : $cluster_id | user_name : $user_name");

		$sql = "
				select su.id, s.period, s.nominal
				  , u.name stockholder, c.name cluster
				  , su.stock_qty, su.stock_received
				  , (su.stock_qty - su.stock_received) stock_remain
				  , su.shu_qty, su.shu_received
				  , (su.shu_qty - su.shu_received) shu_remain
				  , list_shu_stock(su.id, 2) list_stock_received
				  , list_shu_stock(su.id, 1) list_stock_remain
				from shu_user su
				  , shu s
				  , users u
				  , clusters c
				where (0 = $shu_id or su.shu_id = $shu_id)
				  and s.id = su.shu_id
				  and u.id = su.stockholder_id
				  and (0 = $cluster_id or u.cluster = $cluster_id)
				  and u.name like '$user_name'
				  and c.id = u.cluster
  			";			
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_shu_stock_excel() {

		/** Error reporting */
		error_reporting(E_ALL);
		
		date_default_timezone_set('Europe/London');
		
		/** PHPExcel */
		require_once 'libs/export/PHPExcel.php';
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Iwan Saifudin")
									 ->setLastModifiedBy("Iwan Saifudin")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

		$shu_name = (isset($_REQUEST['shu_name'])?$_REQUEST['shu_name']:null);
		$cluster_name = (isset($_REQUEST['cluster_name'])?$_REQUEST['cluster_name']:null);
		$user_name = (isset($_REQUEST['user_name'])?$_REQUEST['user_name']:null);
		write_log($this, __METHOD__, "shu_name : $shu_name | cluster_name : $cluster_name | user_name : $user_name");

		$user_name = ($user_name=='%%'?'-':$user_name);
		
		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Saham SHU')
		            ->setCellValue('A3', 'Periode')->setCellValue('B3', ": $shu_name")
		            ->setCellValue('A4', 'Kelompok')->setCellValue('B4', ": $cluster_name")
		            ->setCellValue('A5', 'Pemegang Saham')->setCellValue('B5', ": $user_name");

		// table header 
		$cols = array(
			'ID', 'Periode', 'Nominal', 'Pemegang Saham', 'Kelompok'
			, 'Jml Shm', 'Shm diterima', 'Sisa Shm'
			, 'Jml SHU', 'SHU diterima', 'Sisa SHU'
			, 'Daftar Shm diterima', 'Daftar Sisa Shm');
		$cols_flag = array(
			'Total', '', '', '', ''
			, 'sum', 'sum', 'sum'
			, 'sum', 'sum', 'sum'
			, '', '');
		$total = array();
		$col = 'A';
		$row = 7;
		foreach ($cols as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.$row, $value);
			$total[$i] = 0;
		}

		// get weekly report data
		$data = $this->get_rpt_shu_stock();
		for($j = 0; $j < sizeof($data); $j++) {
				
			$i = 0;
			foreach ($data[$j] as $value) {
				
				$chr = chr(ord($col) + $i);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + $j + 1), $value);
				
				if($cols_flag[$i] == 'count') {
					$total[$i] ++;
				} else if($cols_flag[$i] == 'sum') {
					$total[$i] += $value;				}
				
				$i++;
			}
						
		}
		
		// total
		foreach ($cols_flag as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + sizeof($data) + 1), ($value=='count'||$value=='sum'?$total[$i]:$value));
		}
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Laporan Saham SHU');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_Saham_SHU_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
