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
	$kdtype = $_GET['kdtype'];
	
	$kdby = $_GET['kdby'];
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];
	
	$tgl1 = $_GET['tgl1'];
	$tgl2 = $_GET['tgl2'];
	

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
	if ($kdsupplier == ''){$kdsupplier = 'ALL' ;}
	if ($kddesigner == ''){$kddesigner = 'ALL' ;}
	if ($kdtype ==''){$kdtype = 'ALL';}
	if ($kdkonstruksi ==''){$kdkonstruksi = 'ALL';}


	
	$tsql = "exec dbo.sp_ttb_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdgroup."','".$kditem."','".$kdplu."','".$rubberid."','".$kodesupplier."','".$kdsupplier."','".$kddesigner."','".$kdtype."','".$kdkonstruksi."','".$kdby."' ";
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
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Group");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Nomor");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Tanggal");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "ProductID");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Item");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Kode Barang");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Kode Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('K1', "Designer");
	$objPHPExcel->getActiveSheet()->setCellValue('L1', "Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('M1', "Keterangan");
	$objPHPExcel->getActiveSheet()->setCellValue('N1', "Grossweight");
	$objPHPExcel->getActiveSheet()->setCellValue('O1', "Total Butir");
	$objPHPExcel->getActiveSheet()->setCellValue('P1', "Total Carat");
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', "Harga M");
	$objPHPExcel->getActiveSheet()->setCellValue('R1', "Harga R");
	$objPHPExcel->getActiveSheet()->setCellValue('S1', "Harga Jual");
	$objPHPExcel->getActiveSheet()->setCellValue('T1', "Harga Barcode");
	
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
				 ->setCellValue( "B" . $baris, $row['vf_cabang'])
				 ->setCellValue( "C" . $baris, $row['vf_group'])
				 ->setCellValue( "D" . $baris, $row['vf_nomor'])
				 ->setCellValue( "E" . $baris, $row['vf_supplier'])
				 ->setCellValue( "F" . $baris, $row['vf_tanggal'])
				 ->setCellValue( "G" . $baris, $row['vf_productid'])
				 ->setCellValue( "H" . $baris, $row['vf_item'])
				 ->setCellValue( "I" . $baris, $row['vf_rubberid'])
				 ->setCellValue( "J" . $baris, $row['vf_kodesupplier'])
				 ->setCellValue( "K" . $baris, $row['vf_designer'])
				 ->setCellValue( "L" . $baris, $row['vf_qty'])
				 ->setCellValue( "M" . $baris, $row['vf_keterangan'])
				 ->setCellValue( "N" . $baris, $row['vf_grossweight'])
				 ->setCellValue( "O" . $baris, $row['vf_totbutir'])
				 ->setCellValue( "P" . $baris, $row['vf_totcarat'])
				 ->setCellValue( "Q" . $baris, $row['vf_hargam'])
				 ->setCellValue( "R" . $baris, $row['vf_hargar'])
				 ->setCellValue( "S" . $baris, $row['vf_hargajual'])
				 ->setCellValue( "T" . $baris, $row['vf_hargabarcode']);
				 $baris++;;
				/*
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $row['vf_nomor'])
				 ->setCellValue( "B" . $baris, $row['vf_productid'])
				 ->setCellValue( "C" . $baris, $row['vf_rubberid'])
				 ->setCellValue( "D" . $baris, $row['vf_supplier'])
				 ->setCellValue( "E" . $baris, $row['vf_kodesupplier'])
				 ->setCellValue( "F" . $baris, $row['vf_item'])
				 ->setCellValue( "G" . $baris,   number_format($row['vf_qty'], 0, ',', '.'))
				 ->setCellValue( "H" . $baris,   number_format($row['vf_grossweight'], 2, '.', ','))
				 ->setCellValue( "I" . $baris,   number_format($row['vf_totbutir'], 0, ',', '.'))
				 ->setCellValue( "J" . $baris,   number_format($row['vf_totcarat'], 0, '.', ','))
				 ->setCellValue( "K" . $baris,   number_format($row['vf_hargar'], 0, ',', '.'))
				 ->setCellValue( "L" . $baris,   number_format($row['vf_hargajual'], 0, ',', '.'));
				 $baris++;;
				 */
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Report TTB.xls"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
