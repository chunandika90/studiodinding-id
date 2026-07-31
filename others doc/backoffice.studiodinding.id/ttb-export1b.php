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
	if ($kddesigner == ''){$kddesigner = 'ALL' ;}
	if ($kdsupplier == ''){$kdsupplier = 'ALL' ;}
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

/*
	$objPHPExcel->getActiveSheet()->setCellValue('A1', "Kode Barang Cetak");
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Item");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Harga Barcode");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Butir 1");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Carat 1");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "Butir 2");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Carat 2");
	$objPHPExcel->getActiveSheet()->setCellValue('H1', "Butir 3");
	$objPHPExcel->getActiveSheet()->setCellValue('I1', "Carat 3");
	$objPHPExcel->getActiveSheet()->setCellValue('J1', "Butir 4");
	$objPHPExcel->getActiveSheet()->setCellValue('K1', "Carat 4");
	$objPHPExcel->getActiveSheet()->setCellValue('L1', "Butir 5");
	$objPHPExcel->getActiveSheet()->setCellValue('M1', "Carat 5");
	$objPHPExcel->getActiveSheet()->setCellValue('N1', "Butir 6");
	$objPHPExcel->getActiveSheet()->setCellValue('O1', "Carat 6");
*/	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

	$baris = 1;
	$no = 0 ;
	do {
		while( $row = sqlsrv_fetch_array( $stmt ))
		{
			
			if ($row['vf_butir1'] == 0 ){ $butir1 = '';} else { $butir1 =  number_format($row['vf_butir1'], 0, '.', ',');}
			if ($row['vf_butir2'] == 0 ){ $butir2 = ''; } else { $butir2 =  number_format($row['vf_butir2'], 0, '.', ',');}
			if ($row['vf_butir3'] == 0 ){ $butir3 = ''; } else { $butir3 =  number_format($row['vf_butir3'], 0, '.', ',');}
			if ($row['vf_butir4'] == 0 ){ $butir4 = ''; } else { $butir4 =  number_format($row['vf_butir4'], 0, '.', ',');}
			if ($row['vf_butir5'] == 0 ){ $butir5 = ''; } else { $butir5 =  number_format($row['vf_butir5'], 0, '.', ',');}
			if ($row['vf_butir6'] == 0 ){ $butir6 = ''; } else { $butir6 =  number_format($row['vf_butir6'], 0, '.', ',');}
			if ($row['vf_butir7'] == 0 ){ $butir7 = ''; } else { $butir7 =  number_format($row['vf_butir7'], 0, '.', ',');}
			if ($row['vf_butir8'] == 0 ){ $butir8 = ''; } else { $butir8 =  number_format($row['vf_butir8'], 0, '.', ',');}
			if ($row['vf_butir9'] == 0 ){ $butir9 = ''; } else { $butir9 =  number_format($row['vf_butir9'], 0, '.', ',');}
			if ($row['vf_butir10'] == 0 ){ $butir10 = ''; } else { $butir10 =  number_format($row['vf_butir10'], 0, '.', ',');}
			if ($row['vf_butir11'] == 0 ){ $butir11 = ''; } else { $butir11 =  number_format($row['vf_butir11'], 0, '.', ',');}
			if ($row['vf_butir12'] == 0 ){ $butir12 = ''; } else { $butir12 =  number_format($row['vf_butir12'], 0, '.', ',');}
			
			if ($row['vf_carat1'] == 0 ){ $carat1 = ''; }else { $carat1 =  number_format($row['vf_carat1'], 3, '.', ',');}
			if ($row['vf_carat2'] == 0 ){ $carat2 = ''; }else { $carat2 =  number_format($row['vf_carat2'], 3, '.', ',');}
			if ($row['vf_carat3'] == 0 ){ $carat3 = ''; }else { $carat3 =  number_format($row['vf_carat3'], 3, '.', ',');}
			if ($row['vf_carat4'] == 0 ){ $carat4 = ''; }else { $carat4 =  number_format($row['vf_carat4'], 3, '.', ',');}
			if ($row['vf_carat5'] == 0 ){ $carat5 = ''; }else { $carat5 =  number_format($row['vf_carat5'], 3, '.', ',');}
			if ($row['vf_carat6'] == 0 ){ $carat6 = ''; }else { $carat6 =  number_format($row['vf_carat6'], 3, '.', ',');}
			if ($row['vf_carat7'] == 0 ){ $carat7 = ''; }else { $carat7 =  number_format($row['vf_carat7'], 3, '.', ',');}
			if ($row['vf_carat8'] == 0 ){ $carat8 = ''; }else { $carat8 =  number_format($row['vf_carat8'], 3, '.', ',');}
			if ($row['vf_carat9'] == 0 ){ $carat9 = ''; }else { $carat9 =  number_format($row['vf_carat9'], 3, '.', ',');}
			if ($row['vf_carat10'] == 0 ){ $carat10 = ''; }else { $carat10 =  number_format($row['vf_carat10'], 3, '.', ',');}
			if ($row['vf_carat11'] == 0 ){ $carat11 = ''; }else { $carat11 =  number_format($row['vf_carat11'], 3, '.', ',');}
			if ($row['vf_carat12'] == 0 ){ $carat12 = ''; }else { $carat12 =  number_format($row['vf_carat12'], 3, '.', ',');}
				
			$no = $no + 1 ;
			
			
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $row['vf_rubberid']." - ".number_format($row['vf_grossweight'], 2, '.', ',').' GR'.
				 ','.$row['vf_item'].','.number_format($row['vf_hargabarcode'], 0, ',', '.').','. 
				 $butir1.','.$carat1.','.  
				 $butir2.','.$carat2.','.  
				 $butir3.','.$carat3.','.  
				 $butir4.','.$carat4.','.  
				 $butir5.','.$carat5.','.  
				 $butir6.','.$carat6.','.  
				 $butir7.','.$carat7.','.  
				 $butir8.','.$carat8.','.  
				 $butir9.','.$carat9.','.  
				 $butir10.','.$carat10.','.  
				 $butir11.','.$carat11.','.  
				 $butir12.','.$carat12.',' )

			;		
/*			
			$objPHPExcel->setActiveSheetIndex(0)
				 ->setCellValue( "A" . $baris, $row['vf_rubberid']." - ".number_format($row['vf_grossweight'], 2, '.', ',').' GR'.
				 ','.$row['vf_item'].','.number_format($row['vf_hargajual'], 0, ',', '.').','. 
				 $row['vf_butir1'].','.$row['vf_carat1'].','. 
				 $row['vf_butir2'].','.$row['vf_carat2'].','. 
				 $row['vf_butir3'].','.$row['vf_carat3'].','. 
				 $row['vf_butir4'].','.$row['vf_carat4'].','. 
				 $row['vf_butir5'].','.$row['vf_carat5'].','. 
				 $row['vf_butir6'].','.$row['vf_carat6'].','. 
				 $row['vf_butir7'].','.$row['vf_carat7'].','. 
				 $row['vf_butir8'].','.$row['vf_carat8'].','. 
				 $row['vf_butir9'].','.$row['vf_carat9'].','. 
				 $row['vf_butir10'].','.$row['vf_carat10'].','. 
				 $row['vf_butir11'].','.$row['vf_carat11'].','. 
				 $row['vf_butir12'].','.$row['vf_carat12'])

			;		
*/
/*
				$tsql2 = " select * from t_stockdetail where m_productid = '".$row['vf_productid']."'";
				$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
				//,echo $kd.' - '.$tsql.'<br/>';
				 	//die(chr(69));		
					$i =68;						 
				//while (sqlsrv_next_result($stmT2)){
					while( $row2 = sqlsrv_fetch_array( $stmt2 ))
					{
						$objPHPExcel->setActiveSheetIndex(0)
							 ->setCellValue( chr($i++) . $baris, number_format($row2['m_butir'], 3, '.', ','))
							 ->setCellValue( chr($i++) . $baris, number_format($row2['m_carat'], 3, '.', ','));		
						//$i++;
					}
				//} 
				*/
		$baris++;; 
		}
	} while (sqlsrv_next_result($stmt));
	
	// Redirect output to a client's web browser (Excel5)
	
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Print Barcode.xls"');
	header('Cache-Control: max-age=0');
	
	
	 
	 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
	
	?>
