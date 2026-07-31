<?php
  	include "mssql-dbnew.php";
	
	$kdcabang = base64_decode($_GET['cb']);
	$kdcara = base64_decode($_GET['cr']);
	$kdedc = base64_decode($_GET['ed']);
	$kdbank = base64_decode($_GET['bn']);
	$kdkartu = base64_decode($_GET['jn']);
	$kdcicil = base64_decode($_GET['cl']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);

	$tgl1 = base64_decode($_GET['tg1']);
	$tgl2 = base64_decode($_GET['tg2']);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdcara ==''){$kdcara = 'ALL';}
	if ($kdedc ==''){$kdedc = 'ALL';}
	if ($kdbank ==''){$kdbank = 'ALL';}
	if ($kdkartu ==''){$kdkartu = 'ALL';}
	if ($kdcicil ==''){$kdcicil = 'ALL';}
	
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
			
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';


//connet to SQL


//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
	
 // Set Properties Cell
$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Pembayaran");
$objPHPExcel->getActiveSheet()->setCellValue('A2', "Periode                  :");
$objPHPExcel->getActiveSheet()->setCellValue('C2', $tgl1);//row tanggal awal
$objPHPExcel->getActiveSheet()->setCellValue('D2', "s/d");
$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('E2', $tgl2);//row tanggal akhir
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
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Total");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "MDR");


$objPHPExcel->getActiveSheet()->getStyle('A7:Q8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H:I')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:Q8')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D2')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);



//Add some data
$tsql = "select * from dbo.f_reportpembayaran('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdcara."', '".$kdedc."', '".$kdbank."', '".$kdkartu."', '".$kdcicil."')" ;
$stmt = sqlsrv_query( $con_dbnew, $tsql);
$b= 9;

			
while($row=sqlsrv_fetch_array($stmt))
{
	$depr = (( $row['vf_asal'] - $row['vf_total'] ) / $row['vf_asal'] ) * 100 ;
	
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
if ($row['vf_level']=='5')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "F" . $b, $row['vf_nama']);
	}
if ($row['vf_level']=='6')
	{
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue( "G" . $b, $row['vf_nama']);
	}

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "H" . $b, number_format($row['vf_total'], 0, '.', ','))
     ->setCellValue( "I" . $b, number_format($row['vf_mdr'], 0, '.', ','));
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
header('Content-Disposition: attachment;filename="laporan_pembayaran(rekap).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 