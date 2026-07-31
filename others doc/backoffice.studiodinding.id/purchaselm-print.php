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
<title>LM Purchase</title>
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
				from 	t_purchase a, msstore b
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and
						a.m_cabang = b.m_kode" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

?>

<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
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
	    <td width="75%" align="left" style="font-size:20px;font-weight:bold"><?php echo $row['namastore']; ?></td>
    	<td width="25%"></td>
    </tr>
    <tr>
	    <td valign="bottom"><?php echo $row['alamatstore1']; ?></td>
    	<td align="right" style="font-size:20px;font-weight:bold">LM Purchase</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td valign="top"><?php if($row['telpstore1'] != ''){ echo 'Phone.'.$row['telpstore1'];} ?><?php if($row['faxstore'] != ''){ echo ' / Fax.'.$row['faxstore'];} ?></td>
        <td align="right" style="font-size:20px;font-weight:bold"><?php echo $nomor ; ?></td>
    </tr>
</table>
<table width="75%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    <tr style="font-weight:bold">
        <td width="10%">Date / Tgl Faktur</td>
		<td width="1%">:</td>
		<td width="25%"><?php echo $row['co_tgl'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Supplier</td>
		<td>:</td>
        <td><?php echo $row['m_nama'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Dok.ID</td>
		<td>:</td>
        <td><?php echo $row['m_dokumen'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Harga/gr</td>
		<td>:</td>
        <td><?php echo number_format($row['m_harga1'], 0, '.', ','); ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Ongkos Tambahan</td>
		<td>:</td>
        <td><?php echo number_format($row['m_ongkos1'], 0, '.', ','); ?></td>
    </tr>
</table>
<br />
<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px"  style="font-weight:bold; border-bottom:1px solid #000;">
    	<td colspan="3"></td>
    </tr>
	<tr height="20px" style="font-weight:bold;">
        <td width="38%" align="left">ITEM &amp; Description/</td>
        <td width="5%" align="center">Qty</td>
        <td width="5%" align="center">Weight</td>
        <td width="15%" align="right">Harga/gr</td>
        <td width="15%" align="right">Ongkos/pcs</td>
        <td width="15%" align="right">Total</td>
	</tr>
	<tr height="20px"  style="font-weight:bold; border-bottom:1px solid #000;">
        <td align="left"><i>Nama Barang &amp; Keterangan</i></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
	</tr>
	<?php
	$jumrow = 0 ;
	$jumbrg = 0 ;
	$jumhrg = 0 ;
	$jumoks = 0 ;
	$jumwgh = 0 ;
	$ttotal = 0 ;
	$tsql2 = "	select 	a.*, c.m_nama as co_namabarang, c.m_kode2
				from 	t_purchase2 a, t_stockdata b, msmaster c 
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and 
						a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and 
						c.m_type = 'ITEM' and 
						b.m_item = c.m_kode " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$dumb = explode('-',$row2['m_kode2']);
		$berat = $dumb[1];
		$total = ( $berat * $row2['m_harga'] ) + ($row2['m_qty'] * $row2['m_ongkos']) ;
		$desc = $row2['co_namabarang'] ;
		if ($berat >= 10)
		{
			$desc = $desc.' ( '.$row2['m_productid'].' )' ;
		}
		
		?>
        <tr height="20px" >
        	<td><?php echo $desc ; ?></td>
            <td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_qty'] * $berat, 2, '.', ','); ?></td>
            <td align="right"><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></td>
            <td align="right"><?php echo number_format($row2['m_ongkos'], 0, '.', ','); ?></td>
            <td align="right"><?php echo number_format($total, 0, '.', ','); ?></td>
        </tr>
        <?php
		$jumrow = $jumrow + 1 ;
		$jumwgh = $jumwgh + ($row2['m_qty'] * $berat) ;
		$jumhrg = $jumhrg + $row2['m_harga'] ;
		$jumoks = $jumoks + $row2['m_ongkos'] ;
		$jumbrg = $jumbrg + $row2['m_qty'] ;
		$ttotal = $ttotal + $total ;
	}	
	if ($jumrow < 4)
	{	
		while ($jumrow <= 4)
		{
			?>
            <tr height="20px"><td colspan="4">&nbsp;</td></tr>
            <?php
			$jumrow = $jumrow + 1 ;
		}
	}

    ?>
	<tr height="20px" style="border-top:2px solid #000;">
        <td align="right"><i>Total :</i></td>
        <td style="border:1px solid" align="center"><?php echo number_format($jumbrg, 0, '.', ','); ?></td>
        <td style="border:1px solid" align="center"><?php echo number_format($jumwgh, 2, '.', ','); ?></td>
        <td style="border:1px solid" align="right"><?php echo number_format($jumhrg, 0, '.', ','); ?></td>
        <td style="border:1px solid" align="right"><?php echo number_format($jumoks, 0, '.', ','); ?></td>
        <td style="border:1px solid" align="right"><?php echo number_format($ttotal, 0, '.', ','); ?></td>
    </tr>
</table>

<table width="100%" >
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td style="font-size:10px"></td>
    <td width="26%" align="center" style="font-weight:bold;font-size:12px" ></td>
    <td width="25%" align="center" style="font-weight:bold;font-size:12px" ></td>
  </tr>
  <tr>
  	<td style="font-size:10px"></td>
  	<td align="center" style="font-size:12px"></td>
    <td align="center" style="font-size:12px"></td>
  </tr>
  <tr>
  	<td></td>
  	<td>Mengetahui</td>
    <td align="center" style="font-size:12px"><i>Staff Purchasing</i></td>
  </tr>
</table>

</body>
</html>