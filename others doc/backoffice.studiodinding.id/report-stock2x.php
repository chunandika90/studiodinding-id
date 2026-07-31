<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$tgl = date('Y-m-d 23:59:59');
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdstock = $_GET['kdst'];
	
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

$objPHPExcel->getActiveSheet()->mergeCells('A7:N7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang dan sales

$objPHPExcel->getActiveSheet()->setCellValue('A8', "Cabang");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "Product ID");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Kode Karet");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Kategori");
$objPHPExcel->getActiveSheet()->setCellValue('G8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('H8', "Qty");
$objPHPExcel->getActiveSheet()->setCellValue('I8', "Trst");
$objPHPExcel->getActiveSheet()->setCellValue('J8', "Harga");
$objPHPExcel->getActiveSheet()->setCellValue('K8', "Gross");
$objPHPExcel->getActiveSheet()->setCellValue('L8', "Net");
$objPHPExcel->getActiveSheet()->setCellValue('M8', "Butir");
$objPHPExcel->getActiveSheet()->setCellValue('N8', "Carat");


$objPHPExcel->getActiveSheet()->getStyle('A7:N8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H:N')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:N8')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);



//Add some data
	$tsql = "	select 	a.*, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, d.m_nama as namakatg, e.m_nama as namaitem,b.m_rubberid
				from 	t_stockinv a, t_stockdata b, msbarang c, msmaster d, msmaster e
				where 	a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						( a.m_qty > 0 or a.m_otw > 0 ) and 
						a.m_kodebarang = c.m_kode and
						d.m_type = 'CATEGORY' and
						b.m_kategori = d.m_kode and 
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode 
			";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kdstock != 'ALL' ){$tsql = $tsql . " and b.m_status = '".$kdstock."'";}
	
	$tsql = $tsql." order by a.m_cabang asc, a.m_kodebarang, b.m_kategori, b.m_item asc, a.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	$tkursLD = "select * from msrate where m_kode = 'LD' and m_tanggal = ( select max(m_tanggal) from msrate where m_kode = 'LD' and  m_tanggal <= '".$tgl."' )";
	$stmtLD= sqlsrv_query( $con_dbnew, $tkursLD);
	$rowLD = sqlsrv_fetch_array( $stmtLD, SQLSRV_FETCH_ASSOC);


$b= 9;
$tqty = 0;
$ttrst = 0;
$tharga = 0;
$tgross = 0;
$tnetw= 0;
$tbutir= 0;
$tcarat= 0;

			
while($row=sqlsrv_fetch_array($stmt))
{
	$tqty = $tqty + $row['m_qty'] ;
	$ttrst = $ttrst + $row['m_otw'] ;
	$tharga = $tharga + $row['m_harga'] ;
	$tgross = $tgross + $row['m_grossweight'] ;
	$tnetw = $tnetw + $row['m_netweight'] ;
	$tbutir = $tbutir + $row['m_butir'] ;
	$tcarat = $tcarat + $row['m_carat'] ;

$objPHPExcel->setActiveSheetIndex(0)

     ->setCellValue( "A" . $b, $row['m_cabang'])
	 ->setCellValue( "C" . $b, $row['m_productid'])
     ->setCellValue( "D" . $b, $row['m_rubberid'])
     ->setCellValue( "E" . $b, $row['namabarang'])
     ->setCellValue( "F" . $b, $row['namakatg'])
     ->setCellValue( "G" . $b, $row['namaitem'])
     ->setCellValue( "H" . $b, number_format($row['m_qty'], 0, '.', ','))
     ->setCellValue( "I" . $b, number_format($row['m_otw'], 0, '.', ','))
     ->setCellValue( "J" . $b, number_format($row['m_harga'], 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($row['m_grossweight'], 2, '.', ','))
     ->setCellValue( "L" . $b, number_format($row['m_netweight'], 2, '.', ','))
     ->setCellValue( "M" . $b, number_format($row['m_butir'], 0, '.', ','))
     ->setCellValue( "N" . $b, number_format($row['m_carat'], 3, '.', ','));
	 $b++;;
}

$objPHPExcel->setActiveSheetIndex(0)

     ->setCellValue( "H" . $b, number_format($tqty, 0, '.', ','))
     ->setCellValue( "I" . $b, number_format($ttrst, 0, '.', ','))
     ->setCellValue( "J" . $b, number_format($tharga, 0, '.', ','))
     ->setCellValue( "K" . $b, number_format($tgross, 0, '.', ','))
     ->setCellValue( "L" . $b, number_format($tnetw, 2, '.', ','))
     ->setCellValue( "M" . $b, number_format($tbutir, 0, '.', ','))
     ->setCellValue( "N" . $b, number_format($tcarat, 3, '.', ','));
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
header('Content-Disposition: attachment;filename="laporan_stock(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 