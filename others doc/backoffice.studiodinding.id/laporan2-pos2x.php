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
	$kdsize = str_replace(",","",$_GET['sz']);	
	$kdsize2 = str_replace(",","",$_GET['sz2']);	
	$kdcert = $_GET['crt'];

	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$vkode = $_GET['vkode'];
	$vnama = $_GET['vnama'];	
	$stqlty = $_GET['ql'];
	$kdbasic = $_GET['bsc'];

	if (($_SESSION['store'] <> '00') && ($_SESSION['store'] <> 'M0') && $_SESSION['store'] <> $kdcabang) {$kdcabang = 'XX' ;}
	if ($_SESSION['store'] == 'M0'){ $kdgroup = 'M0000001';}
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}
	if ($kdbasic ==''){$kdbasic = 'ALL';}
	if ($kdcert ==''){$kdcert = 'ALL';}
	if ($kdsize == ''){$kdsize = 0 ;}
	if ($kdsize2 == ''){$kdsize2 = 0 ;}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}
	
	// cek ukuran batu dulu
	$cekbatu = 'T';
	if (( $kdsize > 0 ) && ( $kdsize2 > 0 ) && ( $kdsize <= $kdsize2 )){ $cekbatu = 'Y';}

	$kdbrand = 'TP';
	$tcekperiod = " select max(m_periode) as coperiod from t_basicinv where m_brand = '".$kdbrand."' ";
	$stmtperiod = sqlsrv_query( $con_dbnew, $tcekperiod);
	$rowperiod = sqlsrv_fetch_array( $stmtperiod, SQLSRV_FETCH_ASSOC );

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
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Tanggal");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Nomor");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Customer");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "Sales");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "No.PLU");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "Warna");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('M8', "Tukaran Beli");
$objPHPExcel->getActiveSheet()->setCellValue('N8', "Disc.Reguler");
$objPHPExcel->getActiveSheet()->setCellValue('O8', "Disc.VIP");
$objPHPExcel->getActiveSheet()->setCellValue('P8', "Disc.Promo");
$objPHPExcel->getActiveSheet()->setCellValue('Q8', "Pembulatan");
$objPHPExcel->getActiveSheet()->setCellValue('R8', "Total Disc");
$objPHPExcel->getActiveSheet()->setCellValue('S8', "Net Sales");
$objPHPExcel->getActiveSheet()->setCellValue('T8', "Net Weight");
$objPHPExcel->getActiveSheet()->setCellValue('U8', "Gross Weight");


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
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);



//Add some data
$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),z.m_tanggal,108) as co_jam, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodecust, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_pos z, t_pos2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_tanggal >= '".$tanggal1."' and 
						z.m_tanggal <= '".$tanggal2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode ";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsql = $tsql . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kddist != 'ALL' ){$tsql = $tsql . " and b.m_distribusi = '".$kddist."'";}
	if ( $kdseg != 'ALL' ){$tsql = $tsql . " and b.m_segmen= '".$kdseg."'";}
	if ( $kdplu != 'ALL' ){$tsql = $tsql . " and a.m_productid = '".$kdplu."'";}
	if ( $stqlty != 'ALL' ){$tsql = $tsql . " and b.m_kelas = '".$stqlty."'";}
	if ( $kdbasic == 'm_basic' )
	{	
		$tsql = $tsql . " and b.m_kodekaret in ( select m_rubberid from t_basicinv where m_brand = '".$kdbrand."' and m_periode = '".$rowperiod['coperiod']."' ) ";	
	}
	elseif ( $kdbasic == 'm_nbasic' )
	{	
		$tsql = $tsql . " and b.m_kodekaret not in ( select m_rubberid from t_basicinv where m_brand = '".$kdbrand."' and m_periode = '".$rowperiod['coperiod']."' ) ";	
	}
	if ( $cekbatu == 'Y' )
	{	
		$tsql = $tsql . " and a.m_productid in ( select distinct m_productid 
												from t_stockdetail 
												where m_butir > 0 and m_carat > 0 and m_carat/m_butir >= ".$kdsize." and m_carat/m_butir <= ".$kdsize2." ) ";	
	}
	if ( $cekcert == 'm_cert' )
	{	
		$tsql = $tsql . " and a.m_productid in ( select distinct m_productid 
												from t_stockdetail 
												where m_sertifikat is not null and m_sertifikat <> '' ) ";	
	}
	else if ( $cekcert == 'm_ncert' )
	{	
		$tsql = $tsql . " and a.m_productid not in ( select distinct m_productid 
												from t_stockdetail 
												where m_sertifikat is not null and m_sertifikat <> '' ) ";	
	}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsql = $tsql." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsql = $tsql." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and b.m_item = '".$vkode."'";}
	else if ( $kdby == 'm_distribusi' ){ $tsql = $tsql." and b.m_distribusi = '".$vkode."'";}
	else if ( $kdby == 'm_segmen' ){ $tsql = $tsql." and b.m_segmen = '".$vkode."'";}
	else if ( $kdby == 'm_tanggal' ){ $tsql = $tsql." and CONVERT(varchar(8),z.m_tanggal,112) = '".$vkode."'";}
	else if ( $kdby == 'm_jam' ){ $tsql = $tsql." and left(CONVERT(varchar(8),z.m_tanggal,108),2) = '".$vkode."'";}
	
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal asc, z.m_nomor asc, a.m_kodebarang asc, a.m_productid asc" ;
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
$tdisc1 = 0 ;
$tdisc2 = 0 ;
$tdisc3 = 0 ;
$tdisc4 = 0 ;
$ttotdisc = 0 ;
$netsales = 0 ;
$tnet = 0 ;
$tgross = 0 ;

while($row=sqlsrv_fetch_array($stmt))
{
	$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4'];
				
	$tsqlsales = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtsales = sqlsrv_query( $con_dbnew, $tsqlsales);
	$rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC);
	
	
	$i++ ;
	$tqty1 = $tqty1 + $row['m_qty'] ;
	$tdisc1 = $tdisc1 + $row['m_discount'] ;
	$tdisc2 = $tdisc2 + $row['m_discount2'] ;
	$tdisc3 = $tdisc3 + $row['m_discount3'] ;
	$tdisc4 = $tdisc4 + $row['m_discount4'] ;
	$ttotdisc = $ttotdisc + $totdisc;
	$netsales = $netsales + ($row['m_harga'] - $totdisc) ;
	$tnet = $tnet + $row['m_netweight'] ;
	$tgross = $tgross + $row['m_grossweight'] ;
	
	 $objPHPExcel->setActiveSheetIndex(0)
	 ->setCellValue( "A" . $b, $i)
     ->setCellValue( "C" . $b, $row['m_cabang'])
     ->setCellValue( "D" . $b, $row['co_tgl'])
     ->setCellValue( "E" . $b, $row['m_nomor'])
     ->setCellValue( "F" . $b, $row['m_nama'])
     ->setCellValue( "G" . $b, $rowsales['m_nama'])
     ->setCellValue( "H" . $b, $row['m_productid'])
     ->setCellValue( "I" . $b, $row['namabarang'])
     ->setCellValue( "J" . $b, $row['namaitem'])
     ->setCellValue( "K" . $b, $row['m_warna'])
     ->setCellValue( "L" . $b, number_format($row['m_qty'], 0, '.', ','))
     ->setCellValue( "M" . $b, number_format($row['m_tukarb'], 3, '.', ','))
     ->setCellValue( "N" . $b, number_format($row['m_discount'], 0, '.', ','))
     ->setCellValue( "O" . $b, number_format($row['m_discount2'], 0, '.', ','))
     ->setCellValue( "P" . $b, number_format($row['m_discount3'], 0, '.', ','))
     ->setCellValue( "Q" . $b, number_format($row['m_discount4'], 0, '.', ','))
     ->setCellValue( "R" . $b, number_format($totdisc, 0, '.', ','))
     ->setCellValue( "S" . $b, number_format($row['m_harga'] - $totdisc, 0, '.', ','))
     ->setCellValue( "T" . $b, number_format($row['m_netweight'], 2, '.', ','))
     ->setCellValue( "U" . $b, number_format($row['m_grossweight'], 2, '.', ','));
	 $b++;;
}
	 $objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "L" . $b, number_format($tqty1, 0, '.', ','))
     ->setCellValue( "N" . $b, number_format($tdisc1, 0, '.', ','))
     ->setCellValue( "O" . $b, number_format($tdisc2, 0, '.', ','))
     ->setCellValue( "P" . $b, number_format($tdisc3, 0, '.', ','))
     ->setCellValue( "Q" . $b, number_format($tdisc4, 0, '.', ','))
     ->setCellValue( "R" . $b, number_format($ttotdisc, 0, '.', ','))
     ->setCellValue( "S" . $b, number_format($netsales, 0, '.', ','))
     ->setCellValue( "T" . $b, number_format($tnet ,2, '.', ','))
     ->setCellValue( "U" . $b, number_format($tgross, 2, '.', ','));
	
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
header('Content-Disposition: attachment;filename="laporan_penjualan(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 