<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdcabang = base64_decode($_GET['cb']);
	
	$nomor = base64_decode($_GET['nm']);

		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tglhapus,103) as co_tglhapus, b.m_nama, 
			c.m_nama as m_namalokasi, d.m_nama as m_namalokasi2
			from t_barang_in a
			left join mssupplier b on a.m_supplier = b.m_kode
			left join mslokasi c on a.m_lokasi = c.m_kode
			left join mslokasi d on a.m_lokasi2 = d.m_kode  
			where a.m_nomor = '".$nomor."'  " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Penerimaan Barang</title>
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
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Data Penerimaan Barang</td>
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
        <td>Supplier</td>
        <td>:</td>
        <td><?php echo $row['supplier'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Lokasi</td>
        <td>:</td>
        <td><?php echo $row['m_lokasi'] ." ( " . $row['m_namalokasi'] . " )"; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Lokasi 2</td>
        <td>:</td>
        <td><?php echo $row['m_lokasi2'] ." ( " . $row['m_namalokasi2'] . " )"; ?></td>
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
        <td style="border:1px solid #000;" align="center">Type</td>
        <td style="border:1px solid #000;" align="center">Tukang</td>
        <td style="border:1px solid #000;" align="center">Kode Barang</td>
        <td style="border:1px solid #000;" align="center">Qty</td>
        <td style="border:1px solid #000;" align="center">Gross.W</td>
        <td style="border:1px solid #000;" align="center">Keterangan</td>
	</tr>
	<?php
	$tsql2 = "	select 	a.*, d.m_nama as m_namabarang
				from 	t_barang_in2 a
				left join mstype b on a.m_type = b.m_type
				left join mslokasi c on a.m_tukang = c.m_nama
				left join msmaster d on d.m_type = 'MATERIAL' and a.m_kodebarang = d.m_kode
				where 	a.m_lokasi = '".$row['m_lokasi']."' and 
						a.m_nomor = '".$nomor."' " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	$jumrow = 0 ;
	$no = 1;
	$tqty = 0 ;
	$tgross = 0 ;
	
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tqty = $tqty + $row2['m_qty'] ;
		$tgross = $tgross + $row2['m_grossweight'] ;
		?>
        <tr height="20px"  style="border:1px solid #000;">
        	<td align="center"><?php echo number_format($no, 0, '.', ',') ;?></td>
        	<td align="center"><?php echo $row2['m_type'] ;?></td>
        	<td align="center"><?php echo $row2['m_tukang'] ;?></td>
        	<td align="center"><?php echo $row2['m_namabarang'] ;?></td>
        	<td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row2['m_grossweight'], 2, '.', ',')."g" ;?></td>
        	<td align="center"><?php echo $row2['m_keterangan'] ;?></td>
        </tr>
        <?php
		$no = $no + 1 ;
	}
	
    ?>
    <tr height="20px" style="border:1px solid #000;font-weight:bold">
        <td colspan="4"></td>
        <td align="center"><?php echo number_format($tqty, 0, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tgross, 2, '.', ',')."g"  ;?></td>
        <td></td>
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