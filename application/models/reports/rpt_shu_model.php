<?php
class Rpt_shu_model extends CI_Model {
		
	function get_rpt_shu() {
			
		$sql = "
				select s.id, s.period, s.nominal
				  , date_format(s.last_buy_date, '%m/%d/%Y') last_buy_date
				  , s.stockholder_qty
				  , s.stock_qty, s.stock_received
				  , (s.stock_qty - s.stock_received) stock_remain
				  , s.shu_qty, s.shu_received
				  , (s.shu_qty - s.shu_received) shu_remain
				from shu s
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_shu_excel() {

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

		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Saham');

		// table header 
		$cols = array(
			'ID', 'Periode', 'Nominal', 'Batas Tgl Beli', 'Jml Jmh'
			, 'Jml Shm', 'Shm diterima', 'Sisa Shm'
			, 'Jml SHU', 'SHU diterima', 'Sisa SHU');
		$cols_flag = array(
			'Total', '', '', '', 'sum'
			, 'sum', 'sum', 'sum'
			, 'sum', 'sum', 'sum');
		$total = array();
		$col = 'A';
		$row = 3;
		foreach ($cols as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.$row, $value);
			$total[$i] = 0;
		}					
		// get weekly report data
		$data = $this->get_rpt_shu();
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
		$objPHPExcel->getActiveSheet()->setTitle('Laporan SHU');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_SHU_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
