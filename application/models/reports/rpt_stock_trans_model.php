<?php
class Rpt_stock_trans_model extends CI_Model {
		
	function get_form() {
			
		$sql = "
				select id, name
				from forms
				where groups = 1 and is_active = 1
				order by id
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_stock_trans() {
			
		$form_id = (isset($_REQUEST['form_id'])?$_REQUEST['form_id']:0);
		$trans_date1 = (isset($_REQUEST['trans_date1'])?$_REQUEST['trans_date1']:null);
		$trans_date2 = (isset($_REQUEST['trans_date2'])?$_REQUEST['trans_date2']:null);
		write_log($this, __METHOD__, "form_id : $form_id | trans_date1 : $trans_date1 | $trans_date2 : $trans_date2");

		$sql = "
				select st.id, f.name form_name, st.ref_no
				  , date_format(st.trans_date, '%m/%d/%Y %H:%i:%s') trans_date
				  , u1.name user_name, st.qty, st.total_price, list_stock_trans(st.id) list_stock
				  , u2.name stockholder_name_from, c1.name stockholder_cluster_from
				  , st.qty_from_before, st.qty_from_after
				  , u3.name stockholder_name_to, c2.name stockholder_cluster_to
				  , st.qty_to_before, st.qty_to_after
				  , st.message
				from stock_trans st
				  left outer join users u3 on u3.id = st.stockholder_id_to
				  left outer join clusters c2 on c2.id = u3.cluster
				  , forms f
				  , users u1
				  , users u2
				  , clusters c1
				where (0 = $form_id or st.form_id = $form_id)
				  and st.trans_date between str_to_date('$trans_date1', '%d %b %Y') 
				  and str_to_date('$trans_date2 23:59:59', '%d %b %Y %H:%i:%s')
				  and f.id = st.form_id and f.groups = 1
				  and u1.code = st.user_id
				  and u2.id = st.stockholder_id_from
				  and c1.id = u2.cluster
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_stock_trans_excel() {

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

		$form_name = (isset($_REQUEST['form_name'])?$_REQUEST['form_name']:null);
		$trans_date1 = (isset($_REQUEST['trans_date1'])?$_REQUEST['trans_date1']:null);
		$trans_date2 = (isset($_REQUEST['trans_date2'])?$_REQUEST['trans_date2']:null);
		
		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Transaksi')
		            ->setCellValue('A3', 'Transaksi')->setCellValue('B3', ": $form_name")
		            ->setCellValue('A4', 'Tanggal')->setCellValue('B4', ": $trans_date1 - $trans_date2");

		// table header 
		$cols = array(
			'ID', 'Form', 'No. Ref', 'Tanggal', 'User', 'Jml Saham', 'Total Harga', 'Daftar Saham'
			, 'Pemegang Saham 1', 'Kelompok 1', 'Jml Sblm 1', 'Jml Ssdh 1'
			, 'Pemegang Saham 2', 'Kelompok 2', 'Jml Sblm 2', 'Jml Ssdh 2', 'Keterangan');
		$cols_flag = array(
			'Total', '', '', '', '', 'sum', 'sum', ''
			, '', '', 'sum', 'sum'
			, '', '', 'sum', 'sum', '');
		$total = array();
		$col = 'A';
		$row = 6;
		foreach ($cols as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.$row, $value);
			$total[$i] = 0;
		}

		// get weekly report data
		$data = $this->get_rpt_stock_trans();
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
		$objPHPExcel->getActiveSheet()->setTitle('Laporan Transaksi Saham');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_Transaksi_Saham_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
