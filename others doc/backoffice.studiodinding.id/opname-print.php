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
<title>STOCK OPNAME</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>
<?php
	include "mssql-dbnew.php" ;
	include "phpfunction.php";
	
	$cabang = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);

	$tsql0 = " 	select a.*, convert(varchar(12),a.m_tanggal,103) as co_tgl, convert(varchar(12),a.m_tanggal,108) as co_jam 
				from t_opname a
				where a.m_cabang = '".$cabang."' and a.m_nomor = '".$nomor."'" ;
	$stmt0 = sqlsrv_query( $con_dbnew, $tsql0);
	$row0 = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_ASSOC) ;

	$tsql2 = "	select 	a.*, b.m_item, b.m_netweight, b.m_butir, b.m_carat, b.m_harga, c.m_nama as co_namabarang
				from 	t_opname2 a, t_stockdata b, msbarang c 
				where 	a.m_cabang = '".$cabang."' and 
						a.m_nomor = '".$nomor."' and 
						a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);

?>

<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Stock Opname</td>
    </tr>
    <tr style="font-weight:bold">
        <td width="8%">No.SO</td>
        <td width="1%">:</td>
        <td width="91%"><?php echo $row0['m_nomor'] ; ?>,  SO.ID : <?php echo $row0['m_soid'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Tanggal</td>
        <td>:</td>
        <td><?php echo $row0['co_tgl'].' '.$row0['co_jam'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>User SO</td>
        <td>:</td>
        <td><?php echo $row0['m_nama'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Keterangan</td>
        <td>:</td>
        <td><?php echo $row0['m_keterangan'] ; ?></td>
    </tr>
    <tr style="border-bottom:1px solid #000;">
	    <td colspan="3">&nbsp;</td>
    </tr>
</table>

<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold">
        <td style="border:1px solid #000;" align="center">No.</td>
        <td style="border:1px solid #000;" align="center">No.PLU</td>
        <td style="border:1px solid #000;" align="center">Group</td>
        <td style="border:1px solid #000;" align="center">Item</td>
        <td style="border:1px solid #000;" align="center">Lokasi</td>
        <td style="border:1px solid #000;" align="center">Net Weight</td>
        <td style="border:1px solid #000;" align="center">Butir</td>
        <td style="border:1px solid #000;" align="center">Carat</td>
        <td style="border:1px solid #000;" align="center">Harga Jual</td>
        <td style="border:1px solid #000;" align="center">No-Pic</td>
        <td style="border:1px solid #000;" align="center">Beda-Pic</td>
        <td style="border:1px solid #000;" align="center">Beda-Tag</td>        
	</tr>
	<?php
	$jumrow = 0 ;
	$no = 1;
	$qty = 0 ;
	$nett = 0 ;
	$butir = 0 ;
	$carat = 0 ;
	$harga = 0 ;
	while( $row = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."'";
		$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
		$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
		?>
        <tr height="25px"  style="border:1px solid #000;">
        	<td align="center"><?php echo number_format($no, 0, '.', ',') ;?></td>
        	<td align="center"><?php echo $row['m_productid'] ;?></td>
        	<td align="center"><?php echo $row['co_namabarang'] ;?></td>
        	<td align="center"><?php echo $rowitem['m_nama']; ?></td>
        	<td align="center"><?php echo $row['m_lokasi'] ;?></td>
        	<td align="center"><?php echo number_format($row['m_nett'], 3, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row['m_butir'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row['m_carat'], 3, '.', ',') ;?></td>
        	<td align="right"><?php echo number_format($row['m_harga'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo $row['m_nopic'] ;?></td>
        	<td align="center"><?php echo $row['m_bedapic'] ;?></td>
        	<td align="center"><?php echo $row['m_bedabandrok'] ;?></td>
        </tr>
        <?php
		$no = $no + 1 ;
	}
	
    ?>
</table>

<table width="100%">
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td align="center" style="font-weight:bold;font-size:12px" ></td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" ></td>
    <td width="21%" align="center" style="font-weight:bold;font-size:12px" >Petugas SO</td>
    <td width="21%" align="center" style="font-weight:bold;font-size:12px" >Mengetahui</td>
  </tr>
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td align="center" style="font-weight:bold;font-size:12px" ></td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" ></td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  </tr>
</table>

</body>
</html>