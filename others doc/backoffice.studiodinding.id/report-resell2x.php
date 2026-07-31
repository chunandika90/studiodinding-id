<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];

	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}



//connet to SQL


//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
	
 // Set Properties Cell
$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Tradein");
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

//SET WARNA COLOM
$phpColor  = new PHPExcel_Style_Color();
$phpColor ->setRGB('FF0000'); 
$objPHPExcel->getActiveSheet()->getStyle('L')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('M')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('N')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('O')->getFont()->setColor($phpColor );

$objPHPExcel->getActiveSheet()->mergeCells('A7:S7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang dan sales

$objPHPExcel->getActiveSheet()->setCellValue('A8', "ST");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "Tanggal");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Nomor");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Customer");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Product ID");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Harga");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "(+/-)%");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "ST");
$objPHPExcel->getActiveSheet()->setCellValue('M8', "Tanggal");
$objPHPExcel->getActiveSheet()->setCellValue('N8', "Nomor");
$objPHPExcel->getActiveSheet()->setCellValue('O8', "Harga Asal");
$objPHPExcel->getActiveSheet()->setCellValue('P8', "Net");
$objPHPExcel->getActiveSheet()->setCellValue('Q8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('R8', "Carat");





$objPHPExcel->getActiveSheet()->getStyle('A7:R8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:R8' )->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G:J')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('K:P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:R8')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);




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
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);



//Add some data
	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal2,103) as co_tglasal, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_resell z, t_resell2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_tanggal >= '".$tgl1."' and 
						z.m_tanggal <= '".$tgl2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode 
			";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal desc, z.m_nomor desc, a.m_kodebarang, b.m_kategori, b.m_item asc, b.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);


$b= 9;
$tqty = 0;
$tharga = 0;
$tdepr = 0;
$tharga2= 0;
$tnetw= 0;
$tbutir= 0;
$tcarat= 0;
			
while($row=sqlsrv_fetch_array($stmt))
{
	$depr = (( $row['m_harga2'] - $row['m_harga'] ) / $row['m_harga2'] ) * 100 ;
	
	$tqty = $tqty + $row['m_qty'] ;
	$tharga = $tharga + $row['m_harga'] ;
	$tdepr = $tdepr + $depr ;
	$tharga2 = $tharga2 + $row['m_harga2'] ;
	$tnetw = $tnetw + $row['m_netweight'] ;
	$tbutir = $tbutir + $row['m_butir'] ;
	$tcarat = $tcarat + $row['m_carat'] ;

$objPHPExcel->setActiveSheetIndex(0)


     ->setCellValue( "A" . $b, $row['m_cabang'])
     ->setCellValue( "C" . $b, $row['co_tgl'])
     ->setCellValue( "D" . $b, $row['m_nomor'])
     ->setCellValue( "E" . $b, $row['m_nama'])
	 ->setCellValue( "F" . $b, $row['m_productid'])
     ->setCellValue( "G" . $b, $row['namabarang'])
     ->setCellValue( "H" . $b, $row['namaitem'])
     ->setCellValue( "I" . $b, $row['m_qty'])
     ->setCellValue( "J" . $b, number_format($row['m_harga'], 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($depr, 2, '.', ','))     
	 ->setCellValue( "L" . $b, $row['m_cabang2'])
     ->setCellValue( "M" . $b, $row['co_tglasal'])
     ->setCellValue( "N" . $b, $row['m_nomor2'])
     ->setCellValue( "O" . $b, number_format($row['m_harga2'], 0, '.', ','))
     ->setCellValue( "P" . $b, number_format($row['m_netweight'], 2, '.', ','))
     ->setCellValue( "Q" . $b, number_format($row['m_butir'], 0, '.', ','))
     ->setCellValue( "R" . $b, number_format($row['m_carat'], 3, '.', ','));
	 $b++;;
}

$objPHPExcel->setActiveSheetIndex(0)

     ->setCellValue( "I" . $b, number_format($tqty, 0, '.', ','))
     ->setCellValue( "J" . $b, number_format($tharga, 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($tdepr, 2, '.', ','))
     ->setCellValue( "O" . $b, number_format($tharga2, 0, '.', ','))
     ->setCellValue( "P" . $b, number_format($tnetw, 2, '.', ','))
     ->setCellValue( "Q" . $b, number_format($tbutir, 0, '.', ','))
     ->setCellValue( "R" . $b, number_format($tcarat, 3, '.', ','));
	 $b++;;


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
header('Content-Disposition: attachment;filename="laporan_resell(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 