<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdklas = $_GET['ks'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kddist = $_GET['dst'];
	$kdseg = $_GET['sg'];
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	$ststock = $_GET['st'];
	$stqlty = $_GET['ql'];

	$vkode = $_GET['vkode'];
	$vnama = $_GET['vnama'];

	if (($_SESSION['store'] <> '00') && ($_SESSION['store'] <> 'M0') && $_SESSION['store'] <> $kdcabang) {$kdcabang = 'XX' ;}
	if ($_SESSION['store'] == 'M0'){ $kdgroup = 'M0000001';}
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($ststock ==''){$ststock = 'X';}
	if ($kdby ==''){$kdby = 'm_cabang';}
	if ($stqlty ==''){$stqlty = 'ALL';}

//connet to SQL
include "mssql-trading.php";

//Add some data
require_once 'plugins/excel/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
 // Set Properties Cell
 $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Penjualan Rekap");
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


$objPHPExcel->getActiveSheet()->mergeCells('A7:W7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang

$objPHPExcel->getActiveSheet()->setCellValue('A8', "No");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "St");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "No.PLU");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Kode Karet");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "Klasifikasi");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Kategori");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "In Trans");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "Jumlah");
$objPHPExcel->getActiveSheet()->setCellValue('M8', "M");
$objPHPExcel->getActiveSheet()->setCellValue('N8', "R");
$objPHPExcel->getActiveSheet()->setCellValue('O8', "Net-W");
$objPHPExcel->getActiveSheet()->setCellValue('P8', "Gross-W");
$objPHPExcel->getActiveSheet()->setCellValue('Q8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('R8', "Carat");


$objPHPExcel->getActiveSheet()->getStyle('A7:W8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('K:S')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);



	$tsql = "	select 	a.*, dbo.f_harga(a.m_kodebarang,a.m_productid) as coharga,  b.m_klasifikasi,  b.m_kategori,  b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_stockinv a, t_stockdata b, msbarang c, msmaster e
				where 	a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode and 
						( a.m_qty <> 0 or a.m_otw <> 0 ) ";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsql = $tsql . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kddist != 'ALL' ){$tsql = $tsql . " and b.m_distribusi = '".$kddist."'";}
	if ( $kdseg != 'ALL' ){$tsql = $tsql . " and b.m_segmen = '".$kdseg."'";}
	if ( $kdplu != 'ALL' ){$tsql = $tsql . " and a.m_productid = '".$kdplu."'";}
	if ( $ststock != 'X' ){$tsql = $tsql . " and b.m_status = '".$ststock."'";}
	if ( $stqlty != 'ALL' ){$tsql = $tsql . " and b.m_kelas = '".$stqlty."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsql = $tsql." and b.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and b.m_item = '".$vkode."'";}
	else if ( $kdby == 'm_distribusi' ){ $tsql = $tsql." and b.m_distribusi = '".$vkode."'";}
	else if ( $kdby == 'm_segmen' ){ $tsql = $tsql." and b.m_segmen = '".$vkode."'";}
	
	$tsql = $tsql." order by a.m_cabang asc, a.m_kodebarang asc, a.m_productid asc" ;
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
if( $stmt === false)
{
	 echo "Error in query preparation/execution.\n";
	 die( print_r( sqlsrv_errors(), true));
}
$tsql0 = " select * from msmaster where m_type= 'STORE' and m_kode = '".$kdcabang."' " ;
$stmt0 = sqlsrv_query($conn, $tsql0);
$row0 = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_ASSOC);
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
$i = 1;
while($row=sqlsrv_fetch_array($stmt))
{
	$tqty1 = $tqty1 + $row['m_qty'] ;
	$totw = $totw + $row['m_otw'] ;
	$ttotal = $ttotal + $row['coharga'] ;
	$thargam = $thargam + $row['m_hargam'] ;
	$thargar = $thargar + $row['m_hargar'] ;
	$tnet = $tnet + $row['m_netweight'] ;
	$tgross = $tgross + $row['m_grossweight'] ;
	$tbutir = $tbutir + $row['m_butir'] ;
	$tcarat = $tcarat + $row['m_carat'] ;
	
	 $objPHPExcel->setActiveSheetIndex(0)
	 
	 ->setCellValue( "A" . $b, $i)
     ->setCellValue( "C" . $b, $row['m_cabang'])
     ->setCellValue( "D" . $b, $row['m_productid'])
     ->setCellValue( "E" . $b, $row['m_rubberid'])
     ->setCellValue( "F" . $b, $row['namabarang'])
     ->setCellValue( "G" . $b, $row['m_klasifikasi'])
     ->setCellValue( "H" . $b, $row['m_kategori'])
     ->setCellValue( "I" . $b, $row['namaitem'])
     ->setCellValue( "J" . $b, number_format($row['m_qty'], 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($row['m_otw'], 0, '.', ','))
     ->setCellValue( "L" . $b, number_format($row['coharga'], 0, '.', ','))
     ->setCellValue( "M" . $b, number_format($row['m_hargam'], 2, '.', ','))
     ->setCellValue( "N" . $b, number_format($row['m_hargar'], 2, '.', ','))
     ->setCellValue( "O" . $b, number_format($row['m_netweight'], 2, '.', ','))
     ->setCellValue( "P" . $b, number_format($row['m_grossweight'], 2, '.', ','))
     ->setCellValue( "Q" . $b, number_format($row['m_butir'], 0, '.', ','))
     ->setCellValue( "R" . $b, number_format($row['m_carat'], 3, '.', ','));
	 $b++;;
	 $i++;;
}
	 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "J" . $b, number_format($tqty1, 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($totw, 0, '.', ','))
     ->setCellValue( "L" . $b, number_format($ttotal, 0, '.', ','))
     ->setCellValue( "M" . $b, number_format($thargam, 2, '.', ','))
     ->setCellValue( "N" . $b, number_format($thargar, 2, '.', ','))
     ->setCellValue( "O" . $b, number_format($tnet, 2, '.', ','))
     ->setCellValue( "P" . $b, number_format($tgross, 2, '.', ','))
     ->setCellValue( "Q" . $b, number_format($tbutir ,0, '.', ','))
     ->setCellValue( "R" . $b, number_format($tcarat, 3, '.', ','));
	
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
header('Content-Disposition: attachment;filename="laporan_stock(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 