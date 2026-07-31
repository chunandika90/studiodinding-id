<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);

		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_transfer a where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	
	$tsqllok = "select m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$row['m_lokasi']."'";
	$stmtlok = sqlsrv_query( $con_dbnew, $tsqllok);
	$rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC) ;
	
	$tsqllok2 = "select m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$row['m_lokasi2']."'";
	$stmtlok2 = sqlsrv_query( $con_dbnew, $tsqllok2);
	$rowlok2 = sqlsrv_fetch_array( $stmtlok2, SQLSRV_FETCH_ASSOC) ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>INVENTORY TRANSFER</title>
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
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Inventory Transfer</td>
    </tr>
    <tr style="font-weight:bold">
        <td width="11%">No.Dokumen</td>
        <td width="1%">:</td>
        <td width="88%"><?php echo $row['m_cabang'].'-'.$row['m_nomor'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Tanggal</td>
        <td>:</td>
        <td><?php echo $row['co_tgl'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Nama</td>
        <td>:</td>
        <td><?php echo $row['m_nama'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Keterangan</td>
        <td>:</td>
        <td><?php echo $row['m_keterangan'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Dari</td>
        <td>:</td>
        <td><?php echo $rowlok['m_nama'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Ke</td>
        <td>:</td>
        <td><?php echo $rowlok2['m_nama'] ; ?></td>
    </tr>
</table>

<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold">
        <td style="border:1px solid #000;" align="center">No.</td>
        <td style="border:1px solid #000;" align="center">Item</td>
        <td style="border:1px solid #000;" align="center">No.PLU</td>
        <td style="border:1px solid #000;" align="center">Qty</td>
        <td style="border:1px solid #000;" align="center">Berat/pcs</td>
        <td style="border:1px solid #000;" align="center">T.Berat</td>
	</tr>
	<?php
	$tsql2 = "	select 	a.*, b.m_item, d.m_kode2, d.m_nama as co_namaitem
				from 	t_transfer2 a, t_stockdata b, msmaster d
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and 
						a.m_kodebarang = b.m_kodebarang and 
						a.m_productid = b.m_productid and
						d.m_type = 'ITEM' and 
						b.m_item = d.m_kode " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	
	$jumrow = 0 ;
	$no = 1;
	$tqty = 0 ;
	$tberat = 0 ;
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$dumb = explode('-',$row2['m_kode2']);
		$tqty = $tqty + $row2['m_qty'] ;
		$tberat = $tberat + $dumb[1] ;
		?>
        <tr height="20px"  style="border:1px solid #000;">
        	<td align="center"><?php echo number_format($no, 0, '.', ',') ;?></td>
        	<td><?php echo $row2['co_namaitem'] ;?></td>
        	<td align="center"><?php echo $row2['m_productid'] ;?></td>
        	<td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($dumb[1], 2, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row2['m_qty']*$dumb[1], 2, '.', ',') ;?></td>
        </tr>
        <?php
		$no = $no + 1 ;
	}
	
    ?>
    <tr height="20px" style="border:1px solid #000;font-weight:bold">
        <td colspan="3"></td>
        <td align="center"><?php echo number_format($tqty, 0, '.', ',') ;?></td>
        <td align="center"></td>
        <td align="center"><?php echo number_format($tberat, 2, '.', ',') ;?></td>
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
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >Dikirin Oleh</td>
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
  	<td width="21%" align="center" style="font-weight:bold;font-size:12px" >(______________________________)</td>
  </tr>
</table>

</body>
</html>