<?php
class Rpt_shu_trans_model extends CI_Model {

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

	function get_form() {
			
		$sql = "
				select id, name
				from forms
				where groups = 2 and is_active = 1
				order by id
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_shu_trans() {
			
		$shu_id = (isset($_REQUEST['shu_id'])?$_REQUEST['shu_id']:0);
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$trans_date1 = (isset($_REQUEST['trans_date1'])?$_REQUEST['trans_date1']:null);
		$trans_date2 = (isset($_REQUEST['trans_date2'])?$_REQUEST['trans_date2']:null);
		write_log($this, __METHOD__, "shu_id : $shu_id | form_id : $form_id | trans_date1 : $trans_date1 | trans_date2 : $trans_date2");

		$sql = "
				select st.id, f.name form, st.ref_no
				  , date_format(st.trans_date, '%m/%d/%Y %H:%i:%s') trans_date
				  , u.name stockholder, s.period, s.nominal
				  , st.stock_qty, st.stock_trans, st.stock_before, st.stock_after
				  , st.shu_qty, st.shu_trans, st.shu_before, st.shu_after
				  , list_shu_trans(st.id) list_stock, st.message
				from shu_trans st
				  , forms f
				  , users u
				  , shu s
				where (0 = $shu_id or st.shu_id = $shu_id)
				  and (0 = $form_id or st.form_id = $form_id)
				  and st.trans_date between str_to_date('$trans_date1', '%d %b %Y') 
				  and str_to_date('$trans_date2 23:59:59', '%d %b %Y %H:%i:%s')
				  and f.id = st.form_id and f.groups = 2
				  and u.id = st.stockholder_id
				  and s.id = st.shu_id
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_shu_trans_excel() {

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
		$form_name = (isset($_REQUEST['form_name'])?$_REQUEST['form_name']:null);
		$trans_date1 = (isset($_REQUEST['trans_date1'])?$_REQUEST['trans_date1']:null);
		$trans_date2 = (isset($_REQUEST['trans_date2'])?$_REQUEST['trans_date2']:null);
		
		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Transaksi')
					->setCellValue('A3', 'Periode')->setCellValue('B3', ": $shu_name")
		            ->setCellValue('A4', 'Transaksi')->setCellValue('B4', ": $form_name")
		            ->setCellValue('A5', 'Tanggal')->setCellValue('B5', ": $trans_date1 - $trans_date2");

		// table header 
		$cols = array(
			'ID', 'Form', 'No. Ref', 'Tanggal', 'Pemegang Saham', 'Periode', 'Nominal'
			, 'Jml Shm', 'Shm Trans', 'Shm Sebelum', 'Shm Sesudah'
			, 'Jml SHU', 'SHU Trans', 'SHU Sebelum', 'SHU Sesudah'
			, 'Daftar Saham', 'Keterangan');
		$cols_flag = array(
			'Total', 'count', '', '', '', '', ''
			, 'sum', 'sum', 'sum', 'sum'
			, 'sum', 'sum', 'sum', 'sum'
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
		$data = $this->get_rpt_shu_trans();
		for($j = 0; $j < sizeof($data); $j++) {
				
			$i = 0;
			foreach ($data[$j] as $value) {
				
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
		$objPHPExcel->getActiveSheet()->setTitle('Laporan Transaksi SHU');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_Transaksi_SHU_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
