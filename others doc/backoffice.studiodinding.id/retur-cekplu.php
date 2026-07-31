<?php
  	include "mssql-dbnew.php";
	
	$kdcab = $_GET['kdcab'];
	$kdlok = $_GET['kdlok'];
	$noplu = $_GET['noplu'];
	
	if ( strlen($noplu) < 9 )
	{
		if (substr($noplu,0,3) != 'PLU')
		{
			$noplu = 'PLU'.substr('0000000'.$noplu,-7);
		}
	}
	
	$tsql = "	select 	a.*, b.m_harga, b.m_item, b.m_netweight, b.m_butir, b.m_carat, b.m_status, c.m_nama as co_namabarang, d.m_nama as ststock
				from 	t_stockinv a, t_stockdata b, msbarang c, msmaster d
				where 	a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and 
						a.m_cabang = '".$kdcab."' and 
						a.m_productid = '".$noplu."' and 
						a.m_lokasi = '".$kdlok."' and 
						a.m_qty > 0 and
						a.m_kodebarang = c.m_kode and 
						d.m_type = 'STINV' and 
						b.m_status = d.m_kode " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."'";
	$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
	$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);

?>

<input type="text" id="cek_kodebarang" name="cek_kodebarang" value="<?php echo $row['m_kodebarang']; ?>" />
<input type="text" id="cek_noplu" name="cek_noplu" value="<?php echo $row['m_productid']; ?>" />
<input type="text" id="cek_group" name="cek_group" value="<?php echo $row['co_namabarang']; ?>" />
<input type="text" id="cek_item" name="cek_item" value="<?php echo $rowitem['m_nama']; ?>" />
<input type="text" id="cek_harga" name="cek_harga" value="<?php echo $row['m_harga']; ?>" />
<input type="text" id="cek_net" name="cek_net" value="<?php echo $row['m_netweight']; ?>" />
<input type="text" id="cek_butir" name="cek_butir" value="<?php echo $row['m_butir']; ?>" />
<input type="text" id="cek_carat" name="cek_carat" value="<?php echo $row['m_carat']; ?>" />
<input type="text" id="cek_status" name="cek_status" value="<?php echo $row['ststock']; ?>" />
