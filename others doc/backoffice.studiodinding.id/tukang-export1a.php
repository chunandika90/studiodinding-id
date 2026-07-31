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
	
	
	
	if ($kdcabang == ''){$kdcabang = 'ALL' ;}
	if ($kdgroup == ''){$kdgroup = 'ALL' ;}
	if ($kditem == ''){$kditem = 'ALL' ;}
	if ($kdplu == ''){$kdplu = 'ALL' ;}
	if ($rubberid ==''){$rubberid = 'ALL';}
	if ($kodesupplier ==''){$kodesupplier = 'ALL';}
	if ($kdsupplier == ''){$kdsupplier = 'ALL' ;}
	if ($kddesigner == ''){$kddesigner = 'ALL' ;}
	if ($kdtype ==''){$kdtype = 'ALL';}
	if ($kdcust ==''){$kdcust = 'ALL';}

	
	
	if ( $detby == '01' ){ $kdcabang = $kd ;  }
	else if ( $detby == '02' ){ $kdgroup = $kd ;  }
	else if ( $detby == '03' ){ $kditem = $kd ;  }
	else if ( $detby == '04' ){ $kdsupplier = $kd ;  }
	else if ( $detby == '05' ){ $kddesigner = $kd ;  }
	else if ( $detby == '06' ){ $kdtype = $kd ;  }
	else if ( $detby == '07' ){ $kdcust = $kd ;  }
	

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	
	$tsql = "exec dbo.sp_returcust_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdgroup."','".$kditem."','".$kdplu."','".$rubberid."','".$kodesupplier."','".$kdsupplier."','".$kddesigner."','".$kdtype."','".$kdcust."','".$kdby."' ";
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
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Cabang");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Group");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Nomor");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Customer");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Tanggal");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "ProductID");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Item");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Designer");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('K1', "Kode Barang");
	$objPHPExcel->getActiveSheet()->setCellValue('L1', "Kode Supplier");
	$objPHPExcel->getActiveSheet()->setCellValue('M1', "Qty");
	$objPHPExcel->getActiveSheet()->setCellValue('N1', "Harga");
	$objPHPExcel->getActiveSheet()->setCellValue('O1', "Berat");
	$objPHPExcel->getActiveSheet()->setCellValue('P1', "Butir");
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', "Carat");
	$objPHPExcel->getActiveSheet()->setCellValue('R1', "Keterangan");
	
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
				 ->setCellValue( "B" . $baris, $row['vf_cabang'])
				 ->setCellValue( "C" . $baris, $row['vf_group'])
				 ->setCellValue( "D" . $baris, $row['vf_nomor'])
				 ->setCellValue( "E" . $baris, $row['vf_customer'])
				 ->setCellValue( "F" . $baris, $row['vf_tanggal'])
				 ->setCellValue( "G" . $baris, $row['vf_productid'])
				 ->setCellValue( "H" . $baris, $row['vf_item'])
				 ->setCellValue( "I" . $baris, $row['vf_designer'])
				 ->setCellValue( "J" . $baris, $row['vf_supplier'])
				 ->setCellValue( "K" . $baris, $row['vf_rubberid'])
				 ->setCellValue( "L" . $baris, $row['vf_kodesupplier'])
				 ->setCellValue( "M" . $baris, $row['vf_qty'])
				 ->setCellValue( "N" . $baris, $row['vf_harga'])
				 ->setCellValue( "O" . $baris, $row['vf_grossweight'])
				 ->setCellValue( "P" . $baris, $row['vf_totbutir'])
				 ->setCellValue( "Q" . $baris, $row['vf_totcarat'])
				 ->setCellValue( "R" . $baris, $row['vf_keterangan']);
				 $baris++;;
				
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Report Retur Cust.xls"');
	header('Cache-Control: max-age=0');
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
