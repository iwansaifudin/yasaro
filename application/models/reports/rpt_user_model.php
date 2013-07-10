<?php
class Rpt_user_model extends CI_Model {
		
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
	
	function get_rpt_user() {
			
		$cluster_id = (isset($_REQUEST['cluster_id'])?$_REQUEST['cluster_id']:0);
		$user_name = (isset($_REQUEST['user_name'])?$_REQUEST['user_name']:null);
		write_log($this, __METHOD__, "cluster_id : $cluster_id | user_name : $user_name");

		$sql = "
				select u1.id, u1.name, u1.birth_place, date_format(u1.birth_date, '%d %b %Y') birth_date
				  , if(u1.gender = 'M', 'Laki-Laki', 'Perempuan') gender
				  , concat(u1.address1, ' ', u1.address2) address
				  , u1.telephone, u1.handphone, u2.name patriarch, f.name family, c.name cluster
				  , if(u1.nationality = 1, 'Indonesia', 'Asing') nationality
				  , u1.stock_qty, u1.stock_total_price, list_stock_stockholder(u1.id) list_stock
				  , if(u1.is_active = 1, 'Aktif', 'Tidak Aktif') status, u1.information
				from users u1
				  , users u2
				  , families f
				  , clusters c
				where (0 = $cluster_id or u1.cluster = $cluster_id)
				  and u1.name like '$user_name'
				  and u2.id = u1.patriarch
				  and f.id = u1.family
				  and c.id = u1.cluster
				order by c.id, u2.name, f.id, u1.birth_date
  			";
		write_log($this, __METHOD__, "sql : $sql");
		$query = $this->db->query($sql);
		write_log($this, __METHOD__, "count : " . $query->num_rows());

		return $query->result_array();
		
	}

	function get_rpt_user_excel() {

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

		$cluster_name = (isset($_REQUEST['cluster_name'])?$_REQUEST['cluster_name']:null);
		$user_name = (isset($_REQUEST['user_name'])?$_REQUEST['user_name']:null);
		write_log($this, __METHOD__, "cluster_name : $cluster_name | user_name : $user_name");

		$user_name = ($user_name=='%%'?'-':$user_name);
		
		// header & parameter
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Laporan Pemegang Saham')
		            ->setCellValue('A3', 'Kelompok')->setCellValue('C3', ": $cluster_name")
		            ->setCellValue('A4', 'Pemegang Saham')->setCellValue('C4', ": $user_name");
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle("A4")->getFont()->setBold(true);

		// declare column 
		$col_header = array(
			'ID', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'Telepon', 'Handphone'
			, 'Kepala Keluarga', 'Status dlm Keluarga', 'Kelompok', 'Kewarganegaraan'
			, 'Jml Saham', 'Total Harga', 'Daftar Saham', 'Status Keanggotaan', 'Keterangan');
		$col_body = array(
			'', '', '', '', '', '', 'text', 'text'
			, '', '', '', ''
			, '', '', '', '', '');
		$col_footer = array(
			'Total', 'count', '', '', '', '', '', ''
			, '', '', '', ''
			, 'sum', 'sum', '', '', '');
		
		// declare variable
		$total = array();
		$col = 'A';
		$row = 6;
		
		// set header column
		foreach ($col_header as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.$row, $value);
			$objPHPExcel->getActiveSheet()->getStyle($chr.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			$objPHPExcel->getActiveSheet()->getStyle($chr.$row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($chr.$row)->getFill()->applyFromArray(
			    array(
			        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			        // 'startcolor' => array('rgb' => 'c0c0c0'),			        'startcolor' => array('rgb' => '969696'),
			    )
			);
			$total[$i] = 0;
		}

		// set body column
		$patriarch_temp = "";
		$patriarch_flag = false;
		$data = $this->get_rpt_user();
		for($j = 0; $j < sizeof($data); $j++) {
				
			// declare parent & status flag
			$name = $data[$j]["name"];
			$patriarch = $data[$j]["patriarch"];
			$status = $data[$j]["status"];
			if($name == $patriarch) {
				$is_parent = true;
			} else {
				$is_parent = false;
			}
			
			// set family block flag
			if($patriarch_temp <> $patriarch) {
				if($patriarch_flag) {
					$patriarch_flag = false;
				} else {
					$patriarch_flag = true;
				}
				$patriarch_temp = $patriarch;
			}
			
			// set body value
			$i = 0;
			foreach ($data[$j] as $value) {
				
				$chr = chr(ord($col) + $i);
				if($col_body[$i] == "text") {
					$objPHPExcel->getActiveSheet()->setCellValueExplicit($chr.($row + $j + 1), $value, PHPExcel_Cell_DataType::TYPE_STRING);
				} else {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + $j + 1), $value);
				}
				
				// set different background color for different family
				if($patriarch_flag) {
					$objPHPExcel->getActiveSheet()->getStyle($chr.($row + $j + 1))->getFill()->applyFromArray(
					    array(
					        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
					        // 'startcolor' => array('rgb' => 'e9e9e9'),					        'startcolor' => array('rgb' => 'c0c0c0'),
					    )
					);
				}
				
				// set bold font for parent
				if($is_parent) {
					$objPHPExcel->getActiveSheet()->getStyle($chr.($row + $j + 1))->getFont()->setBold(true);
				}
				
				// set red color for user is not active
				if($status == "Tidak Aktif") {
					$objPHPExcel->getActiveSheet()->getStyle($chr.($row + $j + 1))->getFont()->getColor()->applyFromArray(array("rgb" => "ff0000"));
				}
				
				// acculumate column for count & sum
				if($col_footer[$i] == 'count') {
					$total[$i] ++;
				} else if($col_footer[$i] == 'sum') {
					$total[$i] += $value;
				}

				$i++;
			}
		
		}
		
		// set footer column
		foreach ($col_footer as $i => $value) {
			$chr = chr(ord($col) + $i);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($chr.($row + sizeof($data) + 1), ($value=='count'||$value=='sum'?$total[$i]:$value));
			$objPHPExcel->getActiveSheet()->getStyle($chr.($row + sizeof($data) + 1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($chr.($row + sizeof($data) + 1))->getFill()->applyFromArray(
			    array(
			        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
			        // 'startcolor' => array('rgb' => 'c0c0c0'),			        'startcolor' => array('rgb' => '969696'),
			    )
			);
		}
	            
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Laporan Pemegang Saham');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Laporan_Pemegang_Saham_'.now($this, '%Y-%m-%d').'.xls"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
				
	}

}
?>
