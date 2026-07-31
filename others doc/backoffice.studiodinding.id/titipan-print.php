<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdcabang = base64_decode($_GET['cb']);
	
	$nomor = base64_decode($_GET['nm']);

		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_canceldate,103) as co_tglhapus
			from t_titipan a
			left join mscustomer b on a.m_kodecust = b.m_kode
			where a.m_nomor = '".$nomor."'  " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Titipan Customer</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>

<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Titipan Customer</td>
    </tr>
    <tr style="font-weight:bold">
        <td width="11%">No.Dokumen</td>
        <td width="1%">:</td>
        <td width="88%"><?php echo $row['m_lokasi'].'-'.$row['m_nomor'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Tanggal</td>
        <td>:</td>
        <td><?php echo $row['co_tgl'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Customer</td>
        <td>:</td>
        <td><?php echo $row['m_nama'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Keterangan</td>
        <td>:</td>
        <td><?php echo $row['m_keterangan'] ; ?></td>
    </tr>
</table>

<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold">
        <td style="border:1px solid #000;" align="center">No.</td>
        <td style="border:1px solid #000;" align="center">KodeBarang</td>
        <td style="border:1px solid #000;" align="center">ProductID</td>
        <td style="border:1px solid #000;" align="center">Item</td>
        <td style="border:1px solid #000;" align="center">Segmen</td>
        <td style="border:1px solid #000;" align="center">Qty</td>
        <td style="border:1px solid #000;" align="center">Harga</td>
	</tr>
	<?php
	$tsql2 = "	select 	a.*, d.m_nama as item, c.m_nama as segmen, b.m_rubberid
				from 	t_titipan2 a, t_stockdata b
						left join mssegmen_in c on b.m_segmen = c.m_kode
						left join msmaster d on d.m_type = 'ITEM' and b.m_item = d.m_kode
				where 	a.m_cabang = '".$row['m_cabang']."' and 
						a.m_nomor = '".$nomor."' and a.m_productid = b.m_productid " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	$jumrow = 0 ;
	$no = 1;
	$tqty = 0 ;
	$tharga = 0 ;
	
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tqty = $tqty + $row2['m_qty'] ;
		$tharga = $tharga + $row2['m_harga'] ;
		?>
        <tr height="20px"  style="border:1px solid #000;">
        	<td align="center"><?php echo number_format($no, 0, '.', ',') ;?></td>
        	<td align="center"><?php echo $row2['m_rubberid'] ;?></td>
        	<td align="center"><?php echo $row2['m_productid'] ;?></td>
        	<td align="center"><?php echo $row2['item'] ;?></td>
        	<td align="center"><?php echo $row2['segmen'] ;?></td>
        	<td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row2['m_harga'], 0, '.', ',') ;?></td>
        </tr>
        <?php
		$no = $no + 1 ;
	}
	
    ?>
    <tr height="20px" style="border:1px solid #000;font-weight:bold">
        <td colspan="5"></td>
        <td align="center"><?php echo number_format($tqty, 0, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tharga, 0, '.', ',')  ;?></td>
    </tr>
</table>

<table width="100%">
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  	<td align="center" style="font-weight:bold;font-size:12px" ></td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >Dibuat Oleh</td>
    <td width="21%" align="center" style="font-weight:bold;font-size:12px" >Mengetahui</td>
    <td width="21%" align="center" style="font-weight:bold;font-size:12px" >Penerima</td>
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
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  </tr>
</table>

</body>
</html>