<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);

		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl,b.m_nama as namadesigner
					, convert(varchar(10),a.m_tanggal_jatuh_tempo,103) as co_tgl_jt
					, convert(varchar(10),a.m_tanggal_approval,103) as co_tgl_approve
					, c.*, d.m_nama as m_item, e.m_nama as m_segmen
					
			 from t_spk a , msdesigner b, t_spk2 c, msmaster d, mssegmen_in e
			 where  a.m_designer = b.m_kode and 
					a.m_nomor = c.m_nomor and
					c.m_item = d.m_kode and d.m_type = 'ITEM' and
					c.m_segmen = e.m_kode and
					a.m_cabang = '".$kdstore."' and 
					a.m_nomor = '".$nomor."'  ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;



	
	$folder = 'W:\file_temp';
	$file = $row['m_rubberid'].".jpg";
	
	$folder2 = 'W:\MY WORK\MY PROGRAMS\toko\images';
	$file2 = "logo_aideo.jpg";

//echo $tsql;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>INVENTORY RECEIVE</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>

<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr style="border:2px solid #000;">
	    <td align="center" style="font-size:20px;font-weight:bold" colspan="10">SPK</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight:bold;border:2px solid #000;" width="20%">LDKP / Validasi</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%"><?php echo $row['m_nomor'] ; ?></td>
	    <td colspan="2" rowspan = "4" style="text-align:center;position: static"width="5%">
		
		
		<div>
			<img src="getfile.php?folder=<?php echo $folder2 ; ?>&file=<?php echo $file2 ; ?>" width="40%" >
		</div>
		
		
		
		</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%">Tipe</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" colspan = "3" width="30%"><?php echo $row['m_type'] ; ?></td>
    </tr>
    <tr>
        <td style="font-weight:bold;border:2px solid #000;">Designer</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%"><?php echo $row['namadesigner'] ; ?></td>
        <td style="font-weight:bold;border:2px solid #000;">Tingkat Kesulitan</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%" colspan = "3" ><?php echo $row['m_konstruksi'] ; ?></td>
    </tr>
    <tr>
        <td style="font-weight:bold;border:2px solid #000;">Tukang Rakit</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%"><?php echo $row['m_tukang'] ; ?></td>
        <td style="font-weight:bold;border:2px solid #000;" colspan = "5">&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight:bold;border:2px solid #000;">Approval SPK</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%"><?php echo $row['m_approval'] ; ?></td>
        <td style="font-weight:bold;border:2px solid #000;">Tanggal Approval</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%" colspan = "3" ><?php echo $row['co_tgl_approve'] ; ?></td>
    </tr>
    <tr>
        <td style="font-weight:bold;border:2px solid #000;">Keterangan / Customer</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%" colspan = "2"><?php echo $row['m_keterangan'] ; ?></td>
        <td style="font-weight:bold;border:2px solid #000;" colspan = "2" >Tanggal Jatuh Tempo</td>
        <td align = "center">:</td>
        <td style="font-weight:bold;border:2px solid #000;" width="20%" colspan = "3"  ><?php echo $row['co_tgl_jt'] ; ?></td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr style="font-weight:bold;border:2px solid #000;">
	    <td style="border:2px solid #000;"  colspan = "2">Kode Karet</td>
		<td style="border:2px solid #000;" >Item</td>
		<td style="border:2px solid #000;" width= "10%">Jumlah</td>
		<td style="border:2px solid #000;">Berat</td>
		<td style="border:2px solid #000;">Kelas Harga</td>
		<td style="border:2px solid #000;">Ring Size (R/S)</td>
		<td style="border:2px solid #000;">Panjang</td>
		<td style="border:2px solid #000;">Lebar</td>
		<td style="border:2px solid #000;">Warna</td>
		
    </tr>
	<tr style="font-weight:bold;border:2px solid #000;font-size :16px" rowspan = "2">
	    <td style="border:2px solid #000;" colspan = "2"><?php echo $row['m_rubberid'] ; ?></td>
	    <td style="border:2px solid #000;" colspan = "1"><?php echo $row['m_item'] ; ?></td>
	    <td style="border:2px solid #000;"><?php echo "1 PCS" ; ?></td>
	    <td style="border:2px solid #000;"><?php echo  number_format($row['m_grossweight'], 2, '.', ',') ; ?></td>
	    <td style="border:2px solid #000;" ><?php echo $row['m_segmen'] ; ?></td>
	    <td style="border:2px solid #000;" ><?php echo $row['m_ringsize'] ; ?></td>
	    <td style="border:2px solid #000;"><?php echo  " " ; ?></td>
	    <td style="border:2px solid #000;"><?php echo  " " ; ?></td>
	    <td style="border:2px solid #000;"><?php echo $row['m_warna'] ; ?></td>
		
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="5">
		<div class="container span4">
			<img src="getfile.php?folder=<?php echo $folder ; ?>&file=<?php echo $file ; ?>" width="300" height="300">
		</div>
		
		
		</td>
		<td colspan="5" align = "right" style= "vertical-align: text-top">
			<table width="80%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold">
				<thead>
					<tr>
						<td style="border:2px solid #000;">Shape</td>
						<td style="border:2px solid #000;">Ukuran</td>
						<td style="border:2px solid #000;">Dimensi</td>
						<td style="border:2px solid #000;">Butir</td>
						<td style="border:2px solid #000;">Carat</td>
					</tr>
				</thead>
				<tbody>
						<?php
							$tsql3 = "	select 	a.*, b.m_ukuran
										from 	t_spk3 a, msstone b
										where 	a.m_nomor = '".$nomor."' and a.m_shape = b.m_shape and a.m_size = b.m_size " ;
							//echo $tsql3;
							$stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
							while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
							{
								
								?>
								<tr>
									<td style="border:2px solid #000;"><?php echo $row3['m_shape']; ?></td>
									<td style="border:2px solid #000;"><?php echo $row3['m_ukuran']."(".$row3['m_size'].")"; ?></td>
									<td style="border:2px solid #000;"><?php echo number_format($row3['m_dimensi'], 0, '.', ','); ?></td>
									<td style="border:2px solid #000;"><?php echo number_format($row3['m_butir'], 0, '.', ','); ?></td>
									<td style="border:2px solid #000;"><?php echo number_format($row3['m_carat'], 3, '.', ','); ?></td>
								</tr>
								<?php
							}
						?>
					</tbody>
				
				
				
			</table>
		</td>
		
    </tr>
	
	
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="10">&nbsp;</td>
    </tr>
	
    <tr style="font-weight:bold;border:2px solid #000;">
	    <td style="border:2px solid #000;" width = "20%">JCAD</td>
	    <td style="border:2px solid #000;">Penurunan SPK</td>
		<td style="border:2px solid #000;" width = "20%">Selesai Rangka</td>
		<td style="border:2px solid #000;" width = "15%">QC RANGKA</td>
		<td style="border:2px solid #000;" width = "18%"colspan = "2">Poles Rangka</td>
		<td style="border:2px solid #000;" width = "18%"colspan = "2">Pasang Batu</td>
		<td style="border:2px solid #000;" width = "18%"colspan = "2">QC PB</td>
    </tr>
    <tr style="font-weight:bold;border:2px solid #000;">
	    <td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" height="50">&nbsp;</td>
	    <td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" colspan = "2" height="50"></td>
		<td style="border:2px solid #000;" colspan = "2" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" colspan = "2" height="50">&nbsp;</td>
    </tr>
    <tr style="font-weight:bold;border:2px solid #000;">
	    <td style="border:2px solid #000;">Setel Rangka</td>
		<td style="border:2px solid #000;">Poles BRJ</td>
		<td style="border:2px solid #000;">QC BRJ</td>
		<td style="border:2px solid #000;">SK BRJ</td>
		<td style="border:2px solid #000;" colspan = "6">REP</td>
    </tr>
    <tr style="font-weight:bold;border:2px solid #000;">
	    <td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" height="50">&nbsp;</td>
		<td style="border:2px solid #000;" colspan = "6" height="50">&nbsp;</td>
    </tr>
	
	
</table>

</body>
</html>