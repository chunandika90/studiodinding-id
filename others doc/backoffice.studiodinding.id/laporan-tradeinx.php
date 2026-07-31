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
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$stqlty = $_GET['ql'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
//connet to SQL
include "mssql-trading.php";

//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
 // Set Properties Cell
 $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Tradein");
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


$objPHPExcel->getActiveSheet()->mergeCells('A7:L7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang

$objPHPExcel->getActiveSheet()->setCellValue('A8', "Report Trade In");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Buyback");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Harga Awal");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "% Depr");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "Gross");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Net");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Carat");

$objPHPExcel->getActiveSheet()->getStyle('A7:L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('D:L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
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


//Add some data
$tsql = "select * from dbo.f_laporantradein('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdklas."', '".$kdkatg."', '".$kditem."', '".$kdplu."', '".$kdby."', '".$stqlty."')" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//$objPHPExcel->getActiveSheet()->setCellValue('L12', $tsql);
	
$b= 9;	

$tqty1 = 0 ;
$ttotal = 0 ;
$tasal = 0 ;
$tdepr = 0 ;
$tgross = 0 ;
$tnet = 0 ;
$tbutir = 0 ;
$tcarat = 0 ;

while($row=sqlsrv_fetch_array($stmt))
{
	$tqty1 = $tqty1 + $row['vf_qty'] ;
	$ttotal = $ttotal + $row['vf_total'] ;
	$tasal = $tasal + $row['vf_asal'] ;
	$tdepr = $tdepr + $depr ;
	$tgross = $tgross + $row['vf_gross'] ;
	$tnet = $tnet + $row['vf_net'] ;
	$tbutir = $tbutir + $row['vf_butir'] ;
	$tcarat = $tcarat + $row['vf_carat'] ;
	
	 $objPHPExcel->setActiveSheetIndex(0)
	 
     ->setCellValue( "A" . $b, $row['vf_nama'])
     ->setCellValue( "C" . $b, number_format($row['vf_qty'], 0, '.', ','))
     ->setCellValue( "D" . $b, number_format($row['vf_total'], 0, '.', ','))
     ->setCellValue( "E" . $b, number_format($row['vf_asal'], 0, '.', ','))
     ->setCellValue( "F" . $b, number_format($depr, 2, '.', ','))
     ->setCellValue( "G" . $b, number_format($row['vf_gross'], 2, '.', ','))
     ->setCellValue( "H" . $b, number_format($row['vf_net'], 2, '.', ','))
     ->setCellValue( "I" . $b, number_format($row['vf_butir'], 0, '.', ','))
     ->setCellValue( "J" . $b, number_format($row['vf_carat'], 3, '.', ','));
	 $b++;;
}
	 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "C" . $b, number_format($tqty1, 0, '.', ','))
     ->setCellValue( "D" . $b, number_format($ttotal, 0, '.', ','))
     ->setCellValue( "E" . $b, number_format($tasal, 0, '.', ','))
     ->setCellValue( "F" . $b, number_format($tdepr, 2, '.', ','))
     ->setCellValue( "G" . $b, number_format($tgross, 2, '.', ','))
     ->setCellValue( "H" . $b, number_format($tnet, 2, '.', ','))
     ->setCellValue( "I" . $b, number_format($tbutir, 0, '.', ','))
     ->setCellValue( "J" . $b, number_format($tcarat, 3, '.', ','));
	
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
header('Content-Disposition: attachment;filename="laporan_tradein(rekap).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 