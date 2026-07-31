<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcab = $_GET['kdcab'];
	$kdlok = $kdcab.'-0';
	$noplu = $_GET['noplu'];
	$tgl = date('Y-m-d 23:59:59');
	
	$tsql = "	select 	a.*, b.m_hargajual as coharga, b.m_rubberid, b.m_item, b.m_netweight, b.m_grossweight, b.m_butir, b.m_carat, b.m_kadar, b.m_warna, c.m_nama as co_namabarang
				from 	t_stockinv a, t_stockdata b, msbarang c
				where 	a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and 
						a.m_kodebarang <> 'M0000001' and 
						a.m_cabang = '".$kdcab."' and 
						
						a.m_lokasi = '".$kdlok."' and 
						a.m_qty > 0 and 
						a.m_status = '0' and 
						a.m_kodebarang = c.m_kode 
						(a.m_productid = '".$noplu."' or b.m_rubberid = '".$noplu."') " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	
	$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."'";
	$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
	$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);

	$harga = $row['coharga'] ;

	
?>
<input type="text" id="cek_kodebarang" name="cek_kodebarang" value="<?php echo $row['m_kodebarang']; ?>" />
<input type="text" id="cek_noplu" name="cek_noplu" value="<?php echo $tsql; ?>" />
<input type="text" id="cek_group" name="cek_group" value="<?php echo $row['co_namabarang']; ?>" />
<input type="text" id="cek_item" name="cek_item" value="<?php echo $rowitem['m_nama']; ?>" />
<input type="text" id="cek_harga" name="cek_harga" value="<?php echo $tsql; ?>" />
<input type="text" id="cek_karet" name="cek_karet" value="<?php echo $row['m_rubberid']; ?>" />

