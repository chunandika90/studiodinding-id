<?php
  	include "mssql-dbnew.php";
	
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kditem = $_GET['kdst'];

	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}



//connet to SQL


//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
	
 // Set Properties Cell
$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Stock");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Cabang                   :");
$objPHPExcel->getActiveSheet()->setCellValue('C3', $kdcabang);//row cabang
$objPHPExcel->getActiveSheet()->setCellValue('A4', "Group :");
$objPHPExcel->getActiveSheet()->setCellValue('C4', $kdgroup);//row product kategory
$objPHPExcel->getActiveSheet()->setCellValue('A5', "Product Item        :");
$objPHPExcel->getActiveSheet()->setCellValue('C5', $kditem);//row product kategory
$objPHPExcel->getActiveSheet()->setCellValue('A6', "Product Kategory    :");
$objPHPExcel->getActiveSheet()->setCellValue('C5', $kdkatg);//row product kategory

$objPHPExcel->getActiveSheet()->mergeCells('A7:I7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang dan sales

$objPHPExcel->getActiveSheet()->mergeCells('A8:E8');
$objPHPExcel->getActiveSheet()->setCellValue('A8', "Keterangan");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Stock");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "In Transit");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Total");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Gross");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Net");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "Carat");


$objPHPExcel->getActiveSheet()->getStyle('A7:L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F:L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:L8')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D2')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);



//Add some data
$tsql = "select * from dbo.f_reportstock('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."')" ;
$stmt = sqlsrv_query( $con_dbnew, $tsql);
$b= 9;
			
while($row=sqlsrv_fetch_array($stmt))
{
	
if ($row['vf_level']=='1')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "A" . $b, $row['vf_nama']);
	}
if ($row['vf_level']=='2')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "C" . $b, $row['vf_nama']);
	}
if ($row['vf_level']=='3')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "D" . $b, $row['vf_nama']);
	}
if ($row['vf_level']=='4')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "E" . $b, $row['vf_nama']);
	}

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "F" . $b, $row['vf_qty'])
     ->setCellValue( "G" . $b, number_format($row['vf_otw'], 0, '.', ','))
     ->setCellValue( "H" . $b, number_format($row['vf_total'], 0, '.', ','))
     ->setCellValue( "I" . $b, number_format($row['vf_gross'], 2, '.', ','))
     ->setCellValue( "J" . $b, number_format($row['vf_net'], 2, '.', ','))
     ->setCellValue( "K" . $b, number_format($row['vf_butir'], 0, '.', ','))
     ->setCellValue( "L" . $b, number_format($row['vf_carat'], 3, '.', ','));
	 $b++;;
}


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
 