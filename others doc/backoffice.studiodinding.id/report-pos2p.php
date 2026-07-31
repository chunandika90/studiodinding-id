<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];

	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_pos z, t_pos2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_tanggal >= '".$tgl1."' and 
						z.m_tanggal <= '".$tgl2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode 
			";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal desc, z.m_nomor desc, a.m_kodebarang, b.m_kategori, b.m_item asc, b.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>


<style type="text/css" media="print, screen">
thead
{
	display:table-header-group;	
}
tbody
{
	display:table-row-group;	
}
</style>

	<table width="100%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    	<thead>
        	<tr>
            	<th colspan="12" align="left"><h2>LAPORAN Penjualan <?php echo $kdcabang;  ?></h2></th>
            </tr>
        	<tr>
            	<th colspan="12" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>       
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="80" style="border:1px solid #000">ST</th>
            	<th width="80" style="border:1px solid #000">Tanggal</th>
            	<th width="80" style="border:1px solid #000">Nomor</th>
            	<th width="30" style="border:1px solid #000">Customer</th>
            	<th width="80" style="border:1px solid #000">Sales</th>
                <th width="80" style="border:1px solid #000">No.PLU</th>
            	<th width="80" style="border:1px solid #000">Group</th>
            	<th width="80" style="border:1px solid #000">Item</th>
            	<th width="80" style="border:1px solid #000">Qty</th>
            	<th width="80" style="border:1px solid #000" >Harga</th>
            	<th width="80" style="border:1px solid #000">Tot.Disc</th>
            	<th width="80" style="border:1px solid #000">Net.Sales</th>
            	<th width="80" style="border:1px solid #000">Net</th>
            	<th width="80" style="border:1px solid #000">Butir</th>
            	<th width="80" style="border:1px solid #000">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$tqty = 0;
			$tharga = 0;
			$tdisc = 0;
			$tnet= 0;
			$tnetw= 0;
			$tbutir= 0;
			$tcarat= 0;
			
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4'];
				
				$tqty = $tqty + $row['m_qty'] ;
				$tharga = $tharga + $row['m_harga'] ;
				$tdisc = $tdisc + $totdisc ;
				$tnet = $tharga - $tdisc ;
				$tnetw = $tnetw + $row['m_netweight'] ;
				$tbutir = $tbutir + $row['m_butir'] ;
				$tcarat = $tcarat + $row['m_carat'] ;
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000" align="center"><?php echo $row['m_cabang']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['co_tgl']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_nomor']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_nama']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_kodesales']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_productid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namabarang']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_qty']; ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_harga'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_harga']-$totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></td>

                </tr>
            <?php
            }
            ?>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" colspan="8"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tqty, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tharga, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnet, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnetw, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
            </tr>
        </tbody>
	</table>

