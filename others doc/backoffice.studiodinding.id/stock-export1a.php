<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

 	include "mssql-dbnew.php";

	$kdcabang = $_GET['kdcabang'];
	$kdgroup = $_GET['kdgroup'];
	$kditem = $_GET['kditem'];
	$kdplu = $_GET['kdplu'];
	$rubberid = $_GET['rubberid'];
	$kodesupplier = $_GET['kodesupplier'];
	$kdsupplier = $_GET['kdsupplier'];
	
	$kdby = $_GET['kdby'];
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
	
	if ($kdcabang == ''){$kdcabang = 'ALL' ;}
	if ($kdgroup == ''){$kdgroup = 'ALL' ;}
	if ($kditem == ''){$kditem = 'ALL' ;}
	if ($kdplu == ''){$kdplu = 'ALL' ;}
	if ($rubberid ==''){$rubberid = 'ALL';}
	if ($kodesupplier ==''){$kodesupplier = 'ALL';}
	if ($kdsupplier ==''){$kdsupplier = 'ALL';}
	if ($kddesigner ==''){$kddesigner = 'ALL';}


	
		$tsql = "exec dbo.sp_stock_all '".$kdcabang."','".$kdgroup."','".$kditem."','".$kdplu."','".$rubberid."','".$kodesupplier."','".$kdsupplier."','".$kddesigner."','".$kdby."' ";
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
	$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('O1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('P1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('Q1')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->setCellValue('A1', "No");
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Item");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Kode Barang");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Kode Barang");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Berat");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Butir");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Carat");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Harga M");
	$objPHPExcel->getActiveSheet()->setCellValue('K1', "Harga R");
	$objPHPExcel->getActiveSheet()->setCellValue('L1', "Harga jual");
	$objPHPExcel->getActiveSheet()->setCellValue('M1', "Harga barcode");
	
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

	$baris = 2;
	$no = 0 ;
	do {
		while( $row = sqlsrv_fetch_array( $stmt ))
		{
			
				
			$no = $no + 1 ;
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $no)
				 ->setCellValue( "B" . $baris, $row['vf_item'])
				 ->setCellValue( "C" . $baris, $row['vf_rubberid'])
				 ->setCellValue( "D" . $baris, $row['vf_productid'])
				 ->setCellValue( "E" . $baris, $row['vf_supplier'])
				 ->setCellValue( "F" . $baris, $row['vf_qty'])
				 ->setCellValue( "G" . $baris, $row['vf_grossweight'])
				 ->setCellValue( "H" . $baris, $row['vf_totbutir'])
				 ->setCellValue( "I" . $baris, $row['vf_totcarat'])
				 ->setCellValue( "J" . $baris, $row['vf_hargam'])
				 ->setCellValue( "K" . $baris, $row['vf_hargar'])
				 ->setCellValue( "L" . $baris, $row['vf_hargajual'])
				 ->setCellValue( "M" . $baris, $row['vf_hargabarcode']);
				 $baris++;;
			
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Report Stock.xls"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
