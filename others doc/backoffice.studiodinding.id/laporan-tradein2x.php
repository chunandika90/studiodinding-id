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
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$vkode = $_GET['vkode'];
	$vnama = $_GET['vst'];	

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

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

//SET WARNA COLOM
$phpColor  = new PHPExcel_Style_Color();
$phpColor ->setRGB('FF0000'); 
$objPHPExcel->getActiveSheet()->getStyle('O')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('P')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('Q')->getFont()->setColor($phpColor );
$objPHPExcel->getActiveSheet()->getStyle('R')->getFont()->setColor($phpColor );

 // Set Properties Cell
 $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report List Barang Trade IN");
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




$objPHPExcel->getActiveSheet()->mergeCells('A7:U7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang

$objPHPExcel->getActiveSheet()->setCellValue('A8', "No");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "St");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Tanggal");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Nomor");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Customer");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "No.PLU");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Warna");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "Tukaran Beli");
$objPHPExcel->getActiveSheet()->setCellValue('M8', "Harga");
$objPHPExcel->getActiveSheet()->setCellValue('N8', "(+/-)%");
$objPHPExcel->getActiveSheet()->setCellValue('O8', "St");
$objPHPExcel->getActiveSheet()->setCellValue('P8', "Tanggal");
$objPHPExcel->getActiveSheet()->setCellValue('Q8', "Nomor");
$objPHPExcel->getActiveSheet()->setCellValue('R8', "Harga Asal");
$objPHPExcel->getActiveSheet()->setCellValue('S8', "Net");
$objPHPExcel->getActiveSheet()->setCellValue('T8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('U8', "Carat");


$objPHPExcel->getActiveSheet()->getStyle('A7:U8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);



//Add some data
$tsqlx = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal2,103) as co_tglasal, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem,b.m_warna,b.m_tukarb
				from 	t_pos z, t_tradein2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_type = 'T' and
						z.m_tanggal >= '".$tanggal1."' and 
						z.m_tanggal <= '".$tanggal2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode ";
	if ( $kdcabang != 'ALL' ){$tsqlx = $tsqlx . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsqlx = $tsqlx . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsqlx = $tsqlx . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsqlx = $tsqlx . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsqlx = $tsqlx . " and b.m_item = '".$kditem."'";}
	if ( $kdplu != 'ALL' ){$tsqlx = $tsqlx . " and a.m_productid = '".$kdplu."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsqlx = $tsqlx." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsqlx = $tsqlx." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsqlx = $tsqlx." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsqlx = $tsqlx." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsqlx = $tsqlx." and b.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsqlx = $tsqlx." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsqlx = $tsqlx." and b.m_item = '".$vkode."'";}
	
	$tsqlx = $tsqlx." order by z.m_cabang asc, z.m_tanggal asc, z.m_nomor asc, a.m_kodebarang asc, a.m_productid asc" ;	
	$stmtx = sqlsrv_query( $con_dbnew, $tsqlx);
	
$b= 9;	


$i = 0 ;
$tqty1 = 0 ;
$tharga = 0 ;
$tdepr = 0 ;
$tharga2 = 0 ;
$tnet = 0 ;
$tbutir = 0 ;
$tcarat = 0 ;
$thargam = 0 ;
$thargar = 0 ;

while( $rowx = sqlsrv_fetch_array( $stmtx, SQLSRV_FETCH_ASSOC))
{
	
	$depr = (( $rowx['m_harga2'] - $rowx['m_harga'] ) / $rowx['m_harga2'] ) * 100 ;
	
	$i++ ;
	$tqty1 = $tqty1 + $rowx['m_qty'] ;
	$tharga = $tharga + $rowx['m_harga'] ;
	$tdepr = $tdepr + $depr;
	$tharga2 = $tharga2 + $rowx['m_harga2'] ;
	$tnet = $tnet + $rowx['m_netweight'] ;
	$tbutir = $tbutir + $rowx['m_butir'] ;
	$tcarat = $tcarat + $rowx['m_carat'] ;
	$thargam = $thargam + $rowx['m_hargam'] ;
	$thargar = $thargar + $rowx['m_hargar'] ;
	
	 $objPHPExcel->setActiveSheetIndex(0)
	 ->setCellValue( "A" . $b, $i)
     ->setCellValue( "C" . $b, $rowx['m_cabang'])
     ->setCellValue( "D" . $b, $rowx['co_tgl'])
     ->setCellValue( "E" . $b, $rowx['m_nomor'])
     ->setCellValue( "F" . $b, $rowx['m_nama'])
     ->setCellValue( "G" . $b, $rowx['m_productid'])
     ->setCellValue( "H" . $b, $rowx['namabarang'])
     ->setCellValue( "I" . $b, $rowx['namaitem'])
     ->setCellValue( "J" . $b, $rowx['m_warna'])
     ->setCellValue( "K" . $b, number_format($rowx['m_qty'], 0, '.', ','))
     ->setCellValue( "L" . $b, number_format($rowx['m_tukarb'], 3, '.', ','))
     ->setCellValue( "M" . $b, number_format($rowx['m_harga'], 0, '.', ','))
     ->setCellValue( "N" . $b, number_format($depr, 2, '.', ','))
     ->setCellValue( "O" . $b, $rowx['m_cabang2'])
     ->setCellValue( "P" . $b, $rowx['co_tglasal'])
     ->setCellValue( "Q" . $b, $rowx['m_nomor2'])
     ->setCellValue( "R" . $b, number_format($rowx['m_harga2'], 0, '.', ','))
     ->setCellValue( "S" . $b, number_format($rowx['m_netweight'], 2, '.', ','))
     ->setCellValue( "T" . $b, number_format($rowx['m_butir'], 0, '.', ','))
     ->setCellValue( "U" . $b, number_format($rowx['m_carat'], 3, '.', ','));
	 $b++;;
}
	 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "K" . $b, number_format($tqty1, 0, '.', ','))
     ->setCellValue( "M" . $b, number_format($tharga, 0, '.', ','))
     ->setCellValue( "N" . $b, number_format($tdepr, 2, '.', ','))
     ->setCellValue( "R" . $b, number_format($tharga2, 0, '.', ','))
     ->setCellValue( "S" . $b, number_format($tnet, 2, '.', ','))
     ->setCellValue( "T" . $b, number_format($tbutir, 0, '.', ','))
     ->setCellValue( "U" . $b, number_format($tcarat, 3, '.', ','));
	
	
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
header('Content-Disposition: attachment;filename="laporan_list_barang_tradein(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 