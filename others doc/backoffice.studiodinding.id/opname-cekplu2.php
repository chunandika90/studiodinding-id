<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcab = $_GET['kdcab'];
	$kdlok = $kdcab.'-0';
	$noplu = $_GET['noplu'];
	$soid = $_GET['so'];
	$double = 'T';
	
	$kdbrg = '????';
	$group = '????';
	$item = '????';
	$harga = 0;
	$net = 0;
	$butir = 0;
	$carat = 0;
	$karet 	= '' ;

	if (substr($noplu,0,3) == 'D14')
	{
		$noplu = $noplu;
	}
	
	else if (substr($noplu,0,3) != 'PLU')
	{
		$noplu = 'PLU'.substr('0000000'.$noplu,-7);
	}
	
	
	// Cek dulu udah diinput SO belum barangnya 
	$tsqlcek = "select count(*) as cekada from t_opname2 a, t_opname b where a.m_cabang = b.m_cabang and a.m_nomor = b.m_nomor and b.m_status = 'A' and b.m_soid = '".$soid."' and a.m_productid = '".$noplu."'";
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
	$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);
	$adadbl = $rowcek['cekada'];
	
	if( $adadbl == ''){$adadbl = 0 ;}
	if ($adadbl > 0)
	{
		$double = 'Y';
		$kdbrg = 'DOUBLE';
	}

	// Cek stock dulu ..... 
	$tsqlstock = " select a.* from t_stockopname a where m_cabang = '".$kdcab."' and m_nomor = '".$soid."' and m_productid = '".$noplu."' and m_qty > 0" ;
	$stmtstock = sqlsrv_query( $con_dbnew, $tsqlstock);
	$rowstock = sqlsrv_fetch_array( $stmtstock, SQLSRV_FETCH_ASSOC);
	
	echo $tsqlstock;
	if ($rowstock['m_productid'] == '')
	{
		$lokasi = '????';
	}
	else 
	{
		$lokasi = $rowstock['m_lokasi'];
	}
	
	if($double == 'T')
	{
		$tsql = "	select 	b.m_kodebarang, b.m_productid, b.m_rubberid, b.m_harga, b.m_item, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as co_namabarang
					from 	t_stockdata b, msbarang c
					where 	b.m_productid = '".$noplu."' and 
							b.m_kodebarang = c.m_kode " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		
		if ($row['m_productid'] != '')
		{
			$tsqlitem = "select m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."'";
			$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
			$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
			
			$kdbrg = $row['m_kodebarang'];
			$group = $row['co_namabarang'];
			$item = $rowitem['m_nama'];
			$harga = $row['m_harga'];
			$net = $row['m_netweight'];
			$butir = $row['m_butir'];
			$carat = $row['m_carat'];
			$dumb = explode('-',$row['m_rubberid']);
			$karet = $dumb[0];

		}
	}
?>

<input type="text" id="cek_kodebarang" name="cek_kodebarang" value="<?php echo $kdbrg ; ?>" />
<input type="text" id="cek_noplu" name="cek_noplu" value="<?php echo $noplu; ?>" />
<input type="text" id="cek_lokasi" name="cek_lokasi" value="<?php echo $lokasi; ?>" />
<input type="text" id="cek_group" name="cek_group" value="<?php echo $group ; ?>" />
<input type="text" id="cek_item" name="cek_item" value="<?php echo $item; ?>" />
<input type="text" id="cek_harga" name="cek_harga" value="<?php echo $harga; ?>" />
<input type="text" id="cek_net" name="cek_net" value="<?php echo $net; ?>" />
<input type="text" id="cek_butir" name="cek_butir" value="<?php echo $butir; ?>" />
<input type="text" id="cek_carat" name="cek_carat" value="<?php echo $carat; ?>" />
<input type="text" id="cek_karet" name="cek_karet" value="<?php echo $karet; ?>" />

