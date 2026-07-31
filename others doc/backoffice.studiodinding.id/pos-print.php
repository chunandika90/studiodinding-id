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
<title>Print Faktur</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>
<?php
	include "phpfunction.php";
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
	$kdbrg = base64_decode($_GET['kdbrg']);
	$productid = base64_decode($_GET['productid']);
	$ctkpic = $_GET['p'];
		
	$tsql = "	select 	a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, b.m_nama as namastore, b.m_alamat1, b.m_alamat2, b.m_telepon1, b.m_kota
				from 	t_pos a, mscabang b
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and
						a.m_cabang = b.m_kode" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);
	
?>

<table width="70%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
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
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="3" align="right" style="font-size:15px;font-weight:bold;color:#000"><?php echo $row['co_tgl']; ?></td>
    </tr>
    <tr>
	    <td width="60%" align="left"  style="font-size:20px;font-weight:bold;color:#000" ><?php echo $row['namastore']; ?></td>
        <td width="25%" align="left"  style="font-size:15px;font-weight:bold;color:#000" ><?php echo $row['m_nama']; ?></td>
    	<td width="25%" align="right" valign="top" style="font-size:20px;font-weight:bold">INVOICE NO.</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td width="50%" valign="top"><?php echo $row['m_alamat1']; ?> <br> <?php echo $row['m_alamat2']; ?>  <br> <?php echo "Telp.  ". $row['m_telepon1'] ."    ". $row['m_kota']  ; ?> </td>
        <td width="25%" align="left"  style="font-size:10px;font-weight:bold;color:#000" ></td>
        <td width="25%"  align="right" style="font-size:20px;font-weight:bold"><?php echo substr($nomor,-4) ; ?></td>
    </tr>
</table>

<table width="70%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold" >
        <td width="5%"  align="center" style="border:1px solid #000">Qty</td>
        <td width="25%" align="center" style="border:1px solid #000">ITEM &amp; Description/</td>
        <td width="10%" align="Center" style="border:1px solid #000">Unit Price</td>
        <td width="10%" align="Center" style="border:1px solid #000">Amount</td>
	</tr>
	<?php
	$jumrow = 0 ;
	$jumbrg = 0 ;
	$ttotal = 0 ;
	$tsql2 = "	select 	a.*, c.m_nama as co_namabarang
				from 	t_pos2 a, msbarang c 
				where 	a.m_cabang = '".$kdstore."' and 
						a.m_nomor = '".$nomor."' and 
						a.m_kodebarang = '".$kdbrg."' and 
						a.m_productid = '".$productid."' and
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

		$dist = $rowr['m_distribusi'];
		$crt = $rowr['m_carat'];
		$katg = $rowr['m_kategori'];
		$kdbrg = $row2['m_kodebarang'];
		$dumb = explode('-',$rowr['m_rubberid']);
		$karet = $dumb[0];
		
		$desc = $rowitem['m_nama'] ."<br>" . "Berat =". number_format($rowr['m_grossweight'], 2, '.', ',') . "<br> Butir =" .number_format($rowr['m_butir'], 0, '.', ','). "<br> Carat =" .number_format($rowr['m_carat'], 3, '.', ',') ;
		
		$total = ( $row2['m_qty'] * $row2['m_harga'] ) - $row2['m_discount'] - $row2['m_discount2'] - $row2['m_discount3'] - $row2['m_discount4'] ;		
		?>
        <tr height="20px" >
            <td align="center" style="border:1px solid #000"><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
        	<td align="left" style="border:1px solid #000"><?php echo $row2['m_productid']. "<br>" . $desc ; ?></td>
            <td align="right" style="border:1px solid #000"><?php echo number_format($total, 0, '.', ','); ?></td>
            <td align="right" style="border:1px solid #000"><?php echo number_format($total, 0, '.', ','); ?></td>
        </tr>
        <?php
		$jumrow = $jumrow + 1 ;
		$jumbrg = $jumbrg + 1 ;
		$ttotal = $ttotal + $total ;
	}	
	
    ?>
	<tr height="20px" style="border-top:2px solid #000;">
    	<td colspan="2" align="left">Say/<i>Terbilang</i> : <i><?php echo strtoupper(money1($ttotal)).' RUPIAH'; ?></i></td>
        <td align="right"><i>Total :</i></td>
        <td style="border:1px solid" align="right"><?php echo number_format($ttotal, 0, '.', ','); ?></td>
    </tr>
</table>

<table width="80%" >
  <tr>
  	<td colspan="2" align="right">RECEIVED BY :</td>
  	<td align="left" style="font-size:15px;font-weight:bold;color:#000"><?php echo $row['m_nama']; ?></td>
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
</table>

<table width="80%" >
  <tr>
  	<td align="Center" colspan="2"  style="font-size:25px;font-weight:bold;color:#000">PERHATIAN</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000"> - Perhiasan Berlian 0 - 5 tahun dan tanggal pembelian, kembali potong 20%</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000"> - Perhiasan Berlian 0 - 5 tahun dan tanggal pembelian, tukar / tambah potong 15%</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Perhiasan Berlian 5 - 10 tahun dan tanggal pembelian, kembali potong 25%</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Perhiasan Berlian 5 - 10 tahun dan tanggal pembelian, tukar / tambah potong 15%</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Perhiasan Berlian 10 tahun keatas dan tanggal pembelian, kembali atau tukar / tambah menurut kebijaksanaan toko</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Perhiasan Mutiara, Batu-Batuan, model dengan kode tertentu tidak dapat kembali atau tukar / tambah</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Barang pesanan dan cincin kawin tidak dapat kembali</td>
  </tr>
  <tr>
  	<td colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	<td align="left" colspan="2" style="font-size:15px;font-weight:bold;color:#000">  - Perhiasan Emas kembali atau tukar / tambah menurut harga pasaran</td>
  </tr>
</table>

<?php 
if ($jumbrg == 1)
{
	include "pos-asp.php" ; 
}
?>

</body>
</html>