<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdcabang = base64_decode($_GET['cb']);
	
	$nomor = base64_decode($_GET['nm']);

			
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam, 
			 a.m_cabang, c.m_nama as m_namalokasi, a.m_cabang2, d.m_nama as m_namalokasi2,
			 e.m_nama as m_namatukang
			 from t_transfersb a
			 left join mstukang e on a.m_tukang = e.m_kode
			 left join mslokasi c on a.m_cabang = c.m_kode
			 left join mslokasi d on a.m_cabang2 = d.m_kode 
			 where a.m_cabang = '".$kdcabang."' and a.m_nomor = '".$nomor."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sisa Stone</title>
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
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Sisa Stone</td>
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
        <td>From Lokasi</td>
        <td>:</td>
        <td><?php echo $row['m_cabang'] ." ( " . $row['m_namalokasi'] . " )"; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>To Lokasi</td>
        <td>:</td>
        <td><?php echo $row['m_cabang2'] ." ( " . $row['m_namalokasi2'] . " )"; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>SPK</td>
        <td>:</td>
        <td><?php echo $row['m_spk'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Tukang</td>
        <td>:</td>
        <td><?php echo $row['m_namatukang'] ; ?></td>
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
        <td style="border:1px solid #000;" align="center">Shape</td>
        <td style="border:1px solid #000;" align="center">Size</td>
        <td style="border:1px solid #000;" align="center">Dimensi</td>
        <td style="border:1px solid #000;" align="center">Dimensi 2</td>
        <td style="border:1px solid #000;" align="center">Dimensi 3</td>
        <td style="border:1px solid #000;" align="center">GIA</td>
        <td style="border:1px solid #000;" align="center">Total Butir</td>
        <td style="border:1px solid #000;" align="center">Total Carat</td>
	</tr>
	<?php
	$no = 1;
	
	$tbutir = 0;
	$tcarat = 0;
	
	$tsql2 = "	select 	a.*
                        from 	t_transfersb2 a
                        where 	a.m_nomor = '".$nomor."'  " ;
   // echo $tsql3;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tsqlcek = "	select 	*
						from 	msstone a
						where 	a.m_shape = '".$row2['m_shape']."' and a.m_size = '".$row2['m_size']."'  " ;
		$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
		$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC) ;
		
		$ukuran =$rowcek ['m_ukuran'];
		
		$tbutir = $tbutir + $row2['m_butir'];
		$tcarat = $tcarat + $row2['m_carat'];
		
		?>
        <tr height="20px"  style="border:1px solid #000;">
        	<td align="center"><?php echo number_format($no, 0, '.', ',') ;?></td>
        	<td align="center"><?php echo $row2['m_shape'] ;?></td>
        	<td align="center"><?php echo $ukuran ." ( ".$row2['m_size']." ) " ;?></td>
        	<td align="center"><?php echo $row2['m_dimensi'] ;?></td>
        	<td align="center"><?php echo $row2['m_dimensi2'] ;?></td>
        	<td align="center"><?php echo $row2['m_dimensi3'] ;?></td>
        	<td align="center"><?php echo $row2['m_gia'] ;?></td>
        	<td align="center"><?php echo number_format($row2['m_butir'], 0, '.', ',') ;?></td>
        	<td align="center"><?php echo number_format($row2['m_carat'], 3, '.', ',') ;?></td>
        </tr>
        <?php
		$no = $no + 1 ;
	}
	
    ?>
    <tr height="20px" style="border:1px solid #000;font-weight:bold">
        <td colspan="7"></td>
        <td align="center"><?php echo number_format($tbutir, 0, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tcarat, 3, '.', ',')  ;?></td>
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