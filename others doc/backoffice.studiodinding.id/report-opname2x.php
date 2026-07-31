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
	$kdstock = $_GET['kdst'];
	$kdby = $_GET['kdby'];
	$periode  = $_GET['pr'];
	$soid = $_GET['so'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	$stat = $_GET['strep'];
	$vkode = $_GET['vkode'];
	$judul = '' ;
	
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
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Report Opname");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Cabang                   :");
$objPHPExcel->getActiveSheet()->setCellValue('C3', $kdcabang);//row cabang
$objPHPExcel->getActiveSheet()->setCellValue('A4', "Group :");
$objPHPExcel->getActiveSheet()->setCellValue('C4', $kdgroup);//row product kategory
$objPHPExcel->getActiveSheet()->setCellValue('A5', "Product Item        :");
$objPHPExcel->getActiveSheet()->setCellValue('C5', $kditem);//row product kategory
$objPHPExcel->getActiveSheet()->setCellValue('A6', "Product Kategory    :");
$objPHPExcel->getActiveSheet()->setCellValue('C5', $kdkatg);//row product kategory

$objPHPExcel->getActiveSheet()->mergeCells('A7:E7');
$objPHPExcel->getActiveSheet()->setCellValue('A7', "The Palace");//row cabang dan sales

$objPHPExcel->getActiveSheet()->setCellValue('A8', "No.PLU");
$objPHPExcel->getActiveSheet()->setCellValue('C8', "Kode Karet");
$objPHPExcel->getActiveSheet()->setCellValue('D8', "Group");
$objPHPExcel->getActiveSheet()->setCellValue('E8', "Item");
$objPHPExcel->getActiveSheet()->setCellValue('F8', "Keterangan");


$objPHPExcel->getActiveSheet()->getStyle('A7:E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I:L')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A1:E8')->getFont()->setName('Calibri');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D2')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);



//Add some data
if ($stat == '3')
	{
		$judul = 'SO ada, Stock tidak ada';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode and 
							a.m_productid not in ( select m_productid from t_stockopname x, t_stockopname0 y where x.m_cabang = y.m_cabang and x.m_nomor = y.m_nomor and y.m_nomor = '".$soid."' and y.m_status = 'A' ) ";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}
	elseif ($stat == '4')
	{
		$judul = 'SO tidak ada, Stock ada';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg
					from 	t_stockopname a, t_stockopname0 b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_nomor = '".$soid."' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode and 
							a.m_productid not in ( select m_productid from t_opname2 x, t_opname y where x.m_cabang = y.m_cabang and x.m_nomor = y.m_nomor and y.m_soid  = '".$soid."' and y.m_status = 'A' ) ";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
		
	}
	elseif ($stat == '5')
	{
		$judul = 'Todak ada gambar';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_nopic = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}

	elseif ($stat == '6')
	{
		$judul = 'Beda gambar';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_bedapic = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}

	}
	elseif ($stat == '7')
	{
		$judul = 'Beda bandrol';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_bedabandrol = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}
	
	if ($kdby == 'm_cabang')
		{$tsql = $tsql." and a.m_cabang = '".$vkode."'" ;}
	else if ($kdby == 'm_group')
		{$tsql = $tsql." and a.m_kodebarang = '".$vkode."'" ;}
	else if ($kdby == 'm_kategori')
		{$tsql = $tsql." and c.m_kategori = '".$vkode."'" ;}
	else if ($kdby == 'm_item')
		{$tsql = $tsql." and c.m_status = '".$vkode."'" ;}
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);


$b= 9;

			
while($row=sqlsrv_fetch_array($stmt))
{

$objPHPExcel->setActiveSheetIndex(0)
     ->setCellValue( "A" . $b, $row['m_productid'])
	 ->setCellValue( "C" . $b, $row['m_rubberid'])
     ->setCellValue( "D" . $b, $row['namabrg'])
     ->setCellValue( "E" . $b, $row['namaitem'])
     ->setCellValue( "F" . $b, $row['m_keterangan']);
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
header('Content-Disposition: attachment;filename="laporan_opname(detail).xls"');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
 