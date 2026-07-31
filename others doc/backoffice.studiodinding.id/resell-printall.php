<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Resell</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>
<?php
	include "phpfunction.php";
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
		
	$tsql = "	select 	a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, b.m_nama as namastore, b.m_alamat1 as alamatstore1, b.m_alamat2 as alamatstore2, b.m_kota as kotastore, b.m_telepon1 as telpstore, b.m_fax as faxstore
				from 	t_resell a, msstore b
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and
						a.m_cabang = b.m_kode" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);
	
?>

<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
	    <td width="75%" align="left" style="font-size:20px;font-weight:bold"><?php echo $row['namastore']; ?></td>
    	<td width="25%"></td>
    </tr>
    <tr>
	    <td width="75%" valign="bottom"><?php echo $row['alamatstore1']; ?></td>
    	<td width="25%" align="right" style="font-size:20px;font-weight:bold">RESELL</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td width="75%" valign="top"><?php if($row['telpstore1'] != ''){ echo 'Phone.'.$row['telpstore1'];} ?><?php if($row['faxstore'] != ''){ echo ' / Fax.'.$row['faxstore'];} ?></td>
        <td width="25%"  align="right" style="font-size:20px;font-weight:bold"><?php echo substr($nomor,-4) ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td width="25%" >Invoice Date / Tgl Faktur</td>
        <td width="75%" align="right" >To / Kepada</td>
    </tr>
    <tr style="font-weight:bold">
        <td width="25%" ><?php echo $row['co_tgl'] ; ?></td>
        <td width="75%" align="right"><?php echo $row['m_nama'] ; ?></td>
    </tr>
    <tr style="border-bottom:1px solid #000;">
	    <td colspan="2">&nbsp;</td>
    </tr>
</table>

<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold">
        <td width="15%">Product ID</td>
        <td width="38%" align="left">ITEM &amp; Description/</td>
        <td width="5%" align="center">Qty</td>
        <td width="15%" align="right">Price/Harga</td>
        <td width="15%" align="right">Payment/</td>
	</tr>
	<tr height="20px"  style="font-weight:bold; border-bottom:1px solid #000;">
        <td></td>
        <td align="left"><i>Nama Barang &amp; Keterangan</i></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="right"><i>Total Bayar</i></td>
	</tr>
	<?php
	$jumrow = 0 ;
	$jumbrg = 0 ;
	$ttotal = 0 ;
	$tsql2 = "	select 	a.*, c.m_nama as co_namabarang
				from 	t_reseLL2 a, msbarang c 
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and 
						a.m_kodebarang = c.m_kode " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tsqlr = "select * from t_stockdata where m_kodebarang = '".$row2['m_kodebarang']."' and m_productid = '".$row2['m_productid']."'" ;
		$stmtr = sqlsrv_query($con_dbnew, $tsqlr);
		$rowr = sqlsrv_fetch_array( $stmtr, SQLSRV_FETCH_ASSOC) ;
		
		$tsqlitem = "select m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$rowr['m_item']."'";
		$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
		$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
		
		$desc = $rowitem['m_nama'];
		if ($row2['m_kodebarang'] == 'P0000004')
		{
			$desc = $desc.' '.$rowr['m_netweight'].'gr ';
			$kadar = $rowr['m_kadar'] * 100;
			$desc = $desc.' '.$kadar.'%';
			if ($rowr['m_warna'] == 'KNG') { $warna = 'KUNING' ;} else { $warna = 'PUTIH' ;}
			$desc = $desc.' '.$warna;
		}

		$total = $row2['m_qty'] * $row2['m_harga'] ;		
		?>
        <tr height="20px" >
        	<td><?php echo $row2['m_productid']?></td>
        	<td><?php echo $desc ; ?></td>
            <td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
            <td align="right"><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></td>
            <td align="right"><?php echo number_format($total, 0, '.', ','); ?></td>
        </tr>
        <?php
		$jumrow = $jumrow + 1 ;
		$jumbrg = $jumbrg + 1 ;
		$ttotal = $ttotal + $total ;
	}	
	if ($jumrow < 4)
	{	
		while ($jumrow <= 4)
		{
			?>
            <tr height="20px"><td colspan="5">&nbsp;</td></tr>
            <?php
			$jumrow = $jumrow + 1 ;
		}
	}

    ?>
	<tr height="20px" style="border-top:2px solid #000;">
    	<td colspan="3" align="left">Say/<i>Terbilang</i> : <i><?php echo strtoupper(money1($ttotal)).' RUPIAH'; ?></i></td>
        <td align="right"><i>Total :</i></td>
        <td style="border:1px solid" align="right"><?php echo number_format($ttotal, 0, '.', ','); ?></td>
    </tr>
</table>

<table width="100%" >
  <tr>
  	<td colspan="3">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="3">&nbsp;</td>
  </tr>
  <tr>
  	<td style="font-size:10px"></td>
    <td width="26%" align="center" style="font-weight:bold;font-size:12px" ><u><?php echo $row['m_nama'];?></u></td>
    <td width="25%" align="center" style="font-weight:bold;font-size:12px" ><u><?php echo $rowjr['m_nama'] ; ?></u></td>
  </tr>
  <tr>
  	<td style="font-size:10px"></td>
  	<td align="center" style="font-size:12px">Customer/<i>Pelanggan</i></td>
    <td align="center" style="font-size:12px">Jewellery Representative</td>
  </tr>
  <tr>
  	<td></td>
  	<td></td>
    <td align="center" style="font-size:12px"><i>Staff Penjualan</i></td>
  </tr>
</table>

</body>
</html>