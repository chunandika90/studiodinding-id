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
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Nama Tukang");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Tanggal");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Kode Barang");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Keterangan");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Segmen");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Product Item");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Gross Weight");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Jumlah");
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	$baris = 2;
	$no = 0 ;
	
	$namatukang = '';
	$totalberat = 0;
	$totaljumlah = 0;
	
	do {
		while( $row = sqlsrv_fetch_array( $stmt ))
		{
			
			if ($namatukang <> $row['vf_tukang'])
			{
				if ($namatukang <> '')
				{
					$objPHPExcel->setActiveSheetIndex(0)
					 ->setCellValue( "A" . $baris, 'Sub Total')
					 ->setCellValue( "H" . $baris, $totalberat)
					 ->setCellValue( "I" . $baris, $totaljumlah);
					 $baris++;;
					 $namatukang = $row['vf_tukang'];
					 
					$baris = $baris + 1;
				}
				
			}
			
			$totalberat = $totalberat + $row['vf_grossweight'];
			$totaljumlah = $totaljumlah + $row['vf_jumlah'];
				
			$no = $no + 1 ;
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $no)
				 ->setCellValue( "B" . $baris, $row['vf_tukang'])
				 ->setCellValue( "C" . $baris, $row['vf_tanggal'])
				 ->setCellValue( "D" . $baris, $row['vf_rubberid'])
				 ->setCellValue( "E" . $baris, $row['vf_keterangan'])
				 ->setCellValue( "F" . $baris, $row['vf_segmen'])
				 ->setCellValue( "G" . $baris, $row['vf_item'])
				 ->setCellValue( "H" . $baris, $row['vf_grossweight'])
				 ->setCellValue( "I" . $baris, $row['vf_jumlah']);
				 $baris++;;
				 $namatukang = $row['vf_tukang'];
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
