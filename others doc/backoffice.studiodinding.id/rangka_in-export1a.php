<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

 	include "mssql-dbnew.php";

	$kdlokasi = $_GET['kdlokasi'];
	$kdbarang = $_GET['kdbarang'];
	$kdtukang = $_GET['kdtukang'];
	$kdby = $_GET['by'];
	
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];
	
	$tgl1 = $_GET['tgl1'];
	$tgl2 = $_GET['tgl2'];
	
	
	
	
	
	if ($kdlokasi ==''){$kdlokasi = 'ALL';}
	if ($kdbarang ==''){$kdbarang = 'ALL';}
	if ($kdtukang ==''){$kdtukang = 'ALL';}

	
	
	
	if ( $detby == '01' ){ $kdlokasi = $kd ;  }
	else if ( $detby == '02' ){ $kdbarang = $kd ;  }
	else if ( $detby == '03' ){ $kdtukang = $kd ;  }

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	
	
	$tsql = "exec dbo.sp_barang_in_all '".$tanggal1."','".$tanggal2."','".$kdlokasi."','".$kdbarang."','".$kdtukang."','".$kdby."' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	//echo $kd.' - '.$tsql.'<br/>';


//Add some data
require_once 'plugins/excel/PHPExcel.php';
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A1', "No");
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Lokasi");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Nomor");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Tanggal");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "KodeBarang");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Tukang");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Keterangan");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Grossweight");
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

	$baris = 2;
	$no = 0 ;
	do {
		while( $row = sqlsrv_fetch_array( $stmt ))
		{
			
				
			$no = $no + 1 ;
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $no)
				 ->setCellValue( "B" . $baris, $row['vf_lokasi'])
				 ->setCellValue( "C" . $baris, $row['vf_nomor'])
				 ->setCellValue( "D" . $baris, $row['vf_tanggal'])
				 ->setCellValue( "E" . $baris, $row['vf_supplier'])
				 ->setCellValue( "F" . $baris, $row['vf_kodebarang'])
				 ->setCellValue( "G" . $baris, $row['vf_tukang'])
				 ->setCellValue( "H" . $baris, $row['vf_keterangan'])
				 ->setCellValue( "I" . $baris, $row['vf_designer'])
				 ->setCellValue( "J" . $baris, $row['vf_supplier']);
				 $baris++;;
				
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Report Terima Barang.xls"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
