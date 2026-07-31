<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdstore = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);

		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl,b.m_nama as namasupplier, c.m_nama as namabarang, e.m_nama as namalokasi from t_ttb a , mssupplier b , msbarang c, msmaster e
	where   a.m_supplier = b.m_kode and
			a.m_kodebarang = c.m_kode and
			a.m_cabang = '".$kdstore."' and 
			a.m_nomor = '".$nomor."' and 
			a.m_lokasi = e.m_kode and
			e.m_type = 'LOKASI' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

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
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
	    <td colspan="3">&nbsp;</td>
    </tr>
    <tr style="border-bottom:2px solid #000;">
	    <td align="left" style="font-size:20px;font-weight:bold" colspan="3">Inventory Receive</td>
    </tr>
    <tr style="font-weight:bold">
        <td width="11%">Nomor</td>
        <td width="1%">:</td>
        <td width="88%"><?php echo $row['m_cabang'].'-'.$row['m_nomor'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Tanggal</td>
        <td>:</td>
        <td><?php echo $row['co_tgl'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Nama Supplier</td>
        <td>:</td>
        <td><?php echo $row['namasupplier'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>Keterangan</td>
        <td>:</td>
        <td><?php echo $row['m_keterangan'] ; ?></td>
    </tr>
    <tr style="font-weight:bold">
        <td>No SJ Supplier</td>
        <td>:</td>
        <td><?php echo $row['m_dosupplier'] ; ?></td>
    </tr>
</table>

<table width="100%" style="font-size:12px;border-collapse: collapse" >
	<tr height="20px" style="font-weight:bold">
            
        <td style="border:1px solid #000;" align="center">No.</td>
        <td style="border:1px solid #000;" align="center">Product ID</td>
        <td style="border:1px solid #000;" align="center">Naming</td>
        <td style="border:1px solid #000;" align="center">Group</td>
        <td style="border:1px solid #000;" align="center">Item</td>
        <td style="border:1px solid #000;" align="center">Qty</td>
        <td style="border:1px solid #000;" align="center">Berat</td>
        <td style="border:1px solid #000;" align="center">Butir</td>
        <td style="border:1px solid #000;" align="center">Carat</td>
        <td style="border:1px solid #000;" align="center">Harga Beli ($)</td>
        <td style="border:1px solid #000;" align="center">Harga Jual (RP)</td>
        <td style="border:1px solid #000;" align="center">Keterangan</td>
	</tr>
	<?php
		$no = 1;
	    $tqty = 0 ;
		$tgross = 0 ;
		$tbutir = 0 ;
		$tcarat = 0 ;
		$thargam = 0 ;
		$thargabeli = 0 ;
		$thargar = 0 ;
		$thargajual = 0 ;
		$tsql2 = "	select 	a.*, b.m_item, b.m_netweight, b.m_butir, b.m_carat , c.m_nama as co_namabarang, b.m_keterangan
					from 	t_ttb2 a, t_stockdata b, msbarang c 
					where 	a.m_cabang = '".$kdstore."' and 
							a.m_nomor = '".$nomor."' and 
							a.m_kodebarang = b.m_kodebarang and 
							a.m_productid = b.m_productid and
							a.m_kodebarang = c.m_kode " ;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
                $stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
                $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
                
                $tqty = $tqty + $row2['m_qty'] ;
                $tgross = $tgross + $row2['m_grossweight'] ;
                $tbutir = $tbutir + $row2['m_butir'] ;
                $tcarat = $tcarat + $row2['m_carat'] ;
                $thargam = $thargam + $row2['m_hargam'] ;
                $thargajual = $thargajual + $row2['m_hargajual'] ;
			
		?>
        <tr height="20px"  style="border:1px solid #000;">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row2['m_productid']; ?></td>
            <td align="center"><?php echo $row2['m_rubberid']; ?></td>
            <td align="center"><?php echo $row2['co_namabarang']; ?></td>
            <td align="center"><?php echo $rowitem['m_nama']; ?></td>
            <td align="center"><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_butir'], 0, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_carat'], 3, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_hargam'], 2, '.', ','); ?></td>
            <td align="center"><?php echo number_format($row2['m_hargajual'], 0, '.', ','); ?></td>
            <td align="center"><?php echo $row2['m_keterangan']; ?></td>
        </tr>
         <?php
			$tsqldet = "select * from t_stockdetail where m_kodebarang = '".$row2['m_kodebarang']."' and m_productid = '".$row2['m_productid']."'";
			$stmtdet = sqlsrv_query( $con_dbnew, $tsqldet);
			?>
			<tr style="border:1px solid #000;">
            	<td colspan="2"></td>
            	<td colspan="10">
                	<table width="257">
                    	<tr>
                        	<td width="40" align="center">Color</td>
                        	<td width="33" align="center">Butir</td>
                        	<td width="59" align="center">Carat</td>
						</tr>
                        <?php
						while( $rowdet = sqlsrv_fetch_array( $stmtdet, SQLSRV_FETCH_ASSOC))
						{
							
							?>
                            <tr>
                                <td align="center"><?php echo $rowdet['m_colour']; ?></td>
                                <td align="center"><?php echo number_format($rowdet['m_butir'], 0, '.', ','); ?></td>
                                <td align="center"><?php echo number_format($rowdet['m_carat'], 3, '.', ','); ?></td>
                            </tr>
                            <?php
						}
						?>
                    </table>
                </td>
            </tr>
            <?php
		
		$no = $no + 1 ;
	}
	
    ?>
    <tr height="20px" style="border:1px solid #000;font-weight:bold">
        <td colspan="5"></td>
        <td align="center"><?php echo number_format($tqty, 0, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tgross, 2, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tbutir, 0, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($tcarat, 3, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($thargam, 2, '.', ',') ;?></td>
        <td align="center"><?php echo number_format($thargajual, 0, '.', ',') ;?></td>
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