<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

 	include "mssql-dbnew.php";

	$kdcabang = $_GET['kdcabang'];
	$kdshape = $_GET['kdshape'];
	$kdsize = $_GET['kdsize'];
	$dimensi = $_GET['dimensi'];
	
	
	$kdby = $_GET['kdby'];
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];
	
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
	if ($kdcabang ==''){$kdcabang = 'ALL';}
	if ($kdcabang2 ==''){$kdcabang2 = 'ALL';}
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}
	if ($tukang ==''){$tukang = 'ALL';}

	
	$tsql = "exec dbo.sp_transfersb_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdcabang2."','".$kdshape."','".$kdsize."','".$dimensi."','".$tukang."','".$kdby."' ";
//	echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	//,echo $kd.' - '.$tsql.'<br/>';


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
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Cabang");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Nomor");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Rate");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Tanggal");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Shape");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Size");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Dimensi");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Dimensi 2");
	$objPHPExcel->getActiveSheet()->setCellValue('K1', "Dimensi 3");
	$objPHPExcel->getActiveSheet()->setCellValue('L1', "GIA");
	$objPHPExcel->getActiveSheet()->setCellValue('M1', "Total Carat");
	$objPHPExcel->getActiveSheet()->setCellValue('N1', "Jumlah");
	$objPHPExcel->getActiveSheet()->setCellValue('O1', "Total");
	
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

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
				 ->setCellValue( "D" . $baris, $row['vf_supplier'])
				 ->setCellValue( "E" . $baris, $row['vf_rate'])
				 ->setCellValue( "F" . $baris, $row['vf_tanggal'])
				 ->setCellValue( "G" . $baris, $row['vf_shape'])
				 ->setCellValue( "H" . $baris, $row['vf_size'])
				 ->setCellValue( "I" . $baris, $row['vf_dimensi'])
				 ->setCellValue( "J" . $baris, $row['vf_dimensi2'])
				 ->setCellValue( "K" . $baris, $row['vf_dimensi3'])
				 ->setCellValue( "L" . $baris, $row['vf_gia'])
				 ->setCellValue( "M" . $baris, $row['vf_carat'])
				 ->setCellValue( "N" . $baris, $row['vf_jumlah'])
				 ->setCellValue( "O" . $baris, $row['vf_total']);
				 $baris++;;
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Report Penerimaan Batu.xls"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
