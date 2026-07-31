<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdklas = $_GET['ks'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kddist = $_GET['dst'];
	$kdseg = $_GET['sg'];
	$kdplu = $_GET['pl'];
	$stqlty = $_GET['ql'];

	$kdby = $_GET['by'];
	$ststock = $_GET['st'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($ststock ==''){$ststock = 'X';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}

	if ($kdby ==''){$kdby = 'm_group';}

	include "mssql-dbnew.php" ;
//connet to SQL

//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
 // Set Properties Cell
 $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Stock Rekap");
$objPHPExcel->getActiveSheet()->setCellValue('A2', "Periode                  :");
$objPHPExcel->getActiveSheet()->setCellValue('C2', $tanggal1);//row tanggal awal
$objPHPExcel->getActiveSheet()->setCellValue('D2', "s/d");
$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('E2', $tanggal2);//row tanggal akhir
$objPHPExcel->getActiveSheet()->setCellValue('G2', "Report by :");
$objPHPExcel->getActiveSheet()->setCellValue('H2', $rekapby);//row pilihan report by
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Cabang                   :");
$objPHPExcel->getActiveSheet()->setCellValue('C3', $kdcabang);//row cabang
$objPHPExcel->getActiveSheet()->setCellValue('A4', "Product Category  :");
$objPHPExcel->getActiveSheet()->setCellValue('C4', $kdkatg);//row product kategory
$objPHPExcel->getActiveSheet()->setCellValue('A5', "Product Item        :");
$objPHPExcel->getActiveSheet()->setCellValue('C5', $kditem);//row product kategory


$objPHPExcel->getActiveSheet()->mergeCells('A7:P7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang
$objPHPExcel->getActiveSheet()->setCellValue('A8', "Keterangan ");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "In Transf.");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Jumlah");
$objPHPExcel->getActiveSheet()->setCellValue('F8', " M ");
$objPHPExcel->getActiveSheet()->setCellValue('G8', " R ");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Net-W");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Gross-W");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "Carat");


$objPHPExcel->getActiveSheet()->getStyle('A7:P8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C:G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:Q9')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);



//Add some data
$tsql = "select * from dbo.f_laporanstock('".$kdcabang."', '".$kdgroup."', '".$kdklas."', '".$kdkatg."', '".$kditem."', '".$kdplu."', '".$kdby."', '".$ststock."', '".$stqlty."', '".$kddist."', '".$kdseg."') order by vf_kode asc" ;
$stmt = sqlsrv_query( $con_dbnew, $tsql);
//$objPHPExcel->getActiveSheet()->setCellValue('L12', $tsql);
	
$b= 9;	


$tqty1 = 0 ;
$totw = 0 ;
$ttotal = 0 ;
$thargam = 0 ;
$thargar = 0 ;
$tnet = 0;
$tgross = 0;
$tbutir = 0 ;
$tcarat = 0 ;


while($row=sqlsrv_fetch_array($stmt))
{
	$tqty1 = $tqty1 + $row['vf_qty'] ;
	$totw = $totw + $row['vf_otw'] ;
	$ttotal = $ttotal + $row['vf_total'] ;
	$thargam = $thargam + $row['vf_hargam'] ;
	$thargar = $thargar + $row['vf_hargar'] ;
	$tnet = $tnet + $row['vf_net'] ;
	$tgross = $tgross + $row['vf_gross'] ;
	$tbutir = $tbutir + $row['vf_butir'] ;
	$tcarat = $tcarat + $row['vf_carat'] ;
	
	 $objPHPExcel->setActiveSheetIndex(0)
	 
     ->setCellValue( "A" . $b, $row['vf_nama'])
     ->setCellValue( "C" . $b, number_format($row['vf_qty'], 0, '.', ','))
     ->setCellValue( "D" . $b, number_format($row['vf_otw'], 0, '.', ','))
     ->setCellValue( "E" . $b, number_format($row['vf_total'], 0, '.', ','))
     ->setCellValue( "F" . $b, number_format($row['vf_hargam'], 2, '.', ','))
     ->setCellValue( "G" . $b, number_format($row['vf_hargar'], 2, '.', ','))
     ->setCellValue( "H" . $b, number_format($row['vf_net'], 2, '.', ','))
     ->setCellValue( "I" . $b, number_format($row['vf_gross'], 2, '.', ','))
     ->setCellValue( "J" . $b, number_format($row['vf_butir'], 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($row['vf_carat'], 3, '.', ','));
	 $b++;;
}
	 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "C" . $b, number_format($tqty1, 0, '.', ','))
     ->setCellValue( "D" . $b, number_format($totw, 0, '.', ','))
     ->setCellValue( "E" . $b, number_format($ttotal, 0, '.', ','))
     ->setCellValue( "F" . $b, number_format($thargam ,2, '.', ','))
     ->setCellValue( "G" . $b, number_format($thargar, 2, '.', ','))
     ->setCellValue( "H" . $b, number_format($tnet, 2, '.', ','))
     ->setCellValue( "I" . $b, number_format($tgross, 2, '.', ','))
     ->setCellValue( "J" . $b, number_format($tbutir, 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($tcarat, 3, '.', ','));
	
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('laporan');

// Set page orientation and size
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
 array('borders' => array(
 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
 'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
 'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
 ),
 ));
 

// Redirect output to a client's web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="laporan_stock(rekap).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 