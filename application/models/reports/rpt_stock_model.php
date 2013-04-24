<?php
class Rpt_stock_model extends CI_Model {
		
	function get_rpt_stock() {
			
		$stock_name = (isset($_REQUEST['stock_name'])?$_REQUEST['stock_name']:null);
		$stockholder_name = (isset($_REQUEST['stockholder_name'])?$_REQUEST['stockholder_name']:null);
		write_log($this, __METHOD__, "stock_name : $stock_name | stockholder_name : $stockholder_name");

		$sql = "
				select a.stock_id, a.stock_name, a.price
				  , a.stockholder_name, a.stockholder_cluster
				  , a.buy_date
				from (
				    select s.id stock_id, s.name stock_name, s.price
				      , ifnull(u.name, '') stockholder_name
				      , ifnull(c.name, '') stockholder_cluster
				      , date_format(s.buy_date, '%m/%d/%Y') buy_date
				    from stocks s
				      left outer join users u on u.id = s.stockholder_id
				      left outer join clusters c on c.id = u.cluster
				    where s.is_active = 1
				  ) a
				where a.stock_name like '$stock_name'
				  and a.stockholder_name like '$stockholder_name'
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_stock_excel() {

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

		$stock_name = (isset($_REQUEST['stock_name'])?$_REQUEST['stock_name']:null);
		$stockholder_name = (isset($_REQUEST['stockholder_name'])?$_REQUEST['stockholder_name']:null);
		write_log($this, __METHOD__, "stock_name : $stock_name | stockholder_name : $stockholder_name");
		
		$stock_name = ($stock_name=='%%'?'-':$stock_name);
		$stockholder_name = ($stockholder_name=='%%'?'-':$stockholder_name);
		
		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Saham')
					->setCellValue('A3', 'Saham')->setCellValue('B3', ": $stock_name")
					->setCellValue('A4', 'Pemegang Saham')->setCellValue('B4', ": $stockholder_name");

		// table header 
		$cols = array('ID', 'Saham', 'Harga', 'Pemegang Saham', 'Kelompok', 'Tgl Pembelian');
		$cols_flag = array('Total', 'count', 'sum', '', '', '');
		$total = array();
		$col = 'A';
		$row = 6;
		foreach ($cols as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.$row, $value);
			$total[$i] = 0;
		}					
		// get weekly report data
		$data = $this->get_rpt_stock();
		for($j = 0; $j < sizeof($data); $j++) {
				
			$i = 0;			foreach ($data[$j] as $value) {
				
				$chr = chr(ord($col) + $i);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + $j + 1), $value);
				if($cols_flag[$i] == 'count') {
					$total[$i] ++;
				} else if($cols_flag[$i] == 'sum') {
					$total[$i] += $value;
				}

				$i++;
			}						
		}

		// total
		foreach ($cols_flag as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + sizeof($data) + 1), ($value=='count'||$value=='sum'?$total[$i]:$value));
		}
	            
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Laporan Saham');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_Saham_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
