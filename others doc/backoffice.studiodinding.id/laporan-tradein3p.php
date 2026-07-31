<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdklas = $_GET['ks'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$vkode = $_GET['vkode'];
	$vnama = $_GET['vst'];	

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem, b.m_hargam,b.m_hargar
				from 	t_pos z, t_pos2 a, t_tradein2 d, t_stockdata b, msbarang c, msmaster e, t_stockdata f
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_cabang = d.m_cabang and
						z.m_nomor = d.m_nomor and 
						z.m_status = 'A' and
						z.m_type = 'T' and						
						z.m_tanggal >= '".$tanggal1."' and 
						z.m_tanggal <= '".$tanggal2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and						
						d.m_kodebarang = f.m_kodebarang and
						d.m_productid = f.m_productid and						
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode ";						
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and d.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and d.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsql = $tsql . " and f.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and f.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and f.m_item = '".$kditem."'";}
	if ( $kdplu != 'ALL' ){$tsql = $tsql . " and d.m_productid = '".$kdplu."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and d.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsql = $tsql." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsql = $tsql." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and d.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsql = $tsql." and f.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and f.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and f.m_item = '".$vkode."'";}
	
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal asc, z.m_nomor asc, a.m_kodebarang asc, a.m_productid asc" ;
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
            	<th colspan="20" align="left"><h2>LAPORAN RESELL ( <?php echo $kdcabang ; ?> ) </h2></th>
            </tr>
        	<tr>
            	<th colspan="20" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th colspan="20"></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="200" style="border:1px solid #000">No</th>
            	<th width="50"  style="border:1px solid #000">St</th>
                <th width="100" style="border:1px solid #000">Tanggal</th>
                <th width="100" style="border:1px solid #000">Nomor</th>
                <th width="100" style="border:1px solid #000">Customer</th>
                <th width="100" style="border:1px solid #000">Sales</th>
                <th width="100" style="border:1px solid #000">No.PLU</th>
                <th width="100" style="border:1px solid #000">Group</th>
                <th width="100" style="border:1px solid #000">Item</th>
                <th width="100" style="border:1px solid #000">Qty</th>                
                <th width="100" style="border:1px solid #000">Disc.Reguler</th>
                <th width="100" style="border:1px solid #000">Disc.VIP</th>
                <th width="100" style="border:1px solid #000">Disc.Promo</th>
                <th width="100" style="border:1px solid #000">Pembulatan</th>
                <th width="100" style="border:1px solid #000">Total Disc.</th>
                <th width="100" style="border:1px solid #000">Net.Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$i = 0 ;
			$tqty1 = 0 ;
			$tdisc1 = 0 ;
			$tdisc2 = 0 ;
			$tdisc3 = 0 ;
			$tdisc4 = 0 ;
			$ttotdisc = 0 ;
			$netsales = 0 ;
			$thargam = 0 ;
			$thargar = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$i++ ;
                $tsqlsales = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
                $stmtsales = sqlsrv_query( $con_dbnew, $tsqlsales);
                $rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC);

				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4'];

				$tqty1 = $tqty1 + $row['m_qty'] ;
				$tdisc1 = $tdisc1 + $row['m_discount'] ;
				$tdisc2 = $tdisc2 + $row['m_discount2'] ;
				$tdisc3 = $tdisc3 + $row['m_discount3'] ;
				$tdisc4 = $tdisc4 + $row['m_discount4'] ;
				$ttotdisc = $ttotdisc + $totdisc;
				$netsales = $netsales + ($row['m_harga'] - $totdisc) ;
				$thargam = $thargam + $row['m_hargam'] ;
				$thargar = $thargar + $row['m_hargar'] ;
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $i; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_cabang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['co_tgl']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_nomor']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_nama']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowsales['m_nama']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_productid']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['namabarang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount2'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount3'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount4'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_harga'] - $totdisc, 0, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center" colspan="9"></td>
                <td style="border:1px solid #000" align="center"><?php echo number_format($tqty1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc2, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc3, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc4, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($ttotdisc, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($netsales, 0, '.', ','); ?></td>
            </tr>
        </tfoot>

	</table>
