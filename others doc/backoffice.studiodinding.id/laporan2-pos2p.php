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
	$kddist = $_GET['dst'];
	$kdseg = $_GET['sg'];
	$kdplu = $_GET['pl'];
	$kdsize = str_replace(",","",$_GET['sz']);	
	$kdsize2 = str_replace(",","",$_GET['sz2']);	
	$kdcert = $_GET['crt'];

	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$vkode = $_GET['vkode'];
	$vnama = $_GET['vnama'];	
	$stqlty = $_GET['ql'];
	$kdbasic = $_GET['bsc'];

	if (($_SESSION['store'] <> '00') && ($_SESSION['store'] <> 'M0') && $_SESSION['store'] <> $kdcabang) {$kdcabang = 'XX' ;}
	if ($_SESSION['store'] == 'M0'){ $kdgroup = 'M0000001';}
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}
	if ($kdbasic ==''){$kdbasic = 'ALL';}
	if ($kdcert ==''){$kdcert = 'ALL';}
	if ($kdsize == ''){$kdsize = 0 ;}
	if ($kdsize2 == ''){$kdsize2 = 0 ;}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}
	
	// cek ukuran batu dulu
	$cekbatu = 'T';
	if (( $kdsize > 0 ) && ( $kdsize2 > 0 ) && ( $kdsize <= $kdsize2 )){ $cekbatu = 'Y';}

	$kdbrand = 'TP';
	$tcekperiod = " select max(m_periode) as coperiod from t_basicinv where m_brand = '".$kdbrand."' ";
	$stmtperiod = sqlsrv_query( $con_dbnew, $tcekperiod);
	$rowperiod = sqlsrv_fetch_array( $stmtperiod, SQLSRV_FETCH_ASSOC );

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),z.m_tanggal,108) as co_jam, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodecust, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem, b.m_tukarb
				from 	t_pos z, t_pos2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_tanggal >= '".$tanggal1."' and 
						z.m_tanggal <= '".$tanggal2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode ";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsql = $tsql . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kddist != 'ALL' ){$tsql = $tsql . " and b.m_distribusi = '".$kddist."'";}
	if ( $kdseg != 'ALL' ){$tsql = $tsql . " and b.m_segmen= '".$kdseg."'";}
	if ( $kdplu != 'ALL' ){$tsql = $tsql . " and a.m_productid = '".$kdplu."'";}
	if ( $stqlty != 'ALL' ){$tsql = $tsql . " and b.m_kelas = '".$stqlty."'";}
	if ( $kdbasic == 'm_basic' )
	{	
		$tsql = $tsql . " and b.m_kodekaret in ( select m_rubberid from t_basicinv where m_brand = '".$kdbrand."' and m_periode = '".$rowperiod['coperiod']."' ) ";	
	}
	elseif ( $kdbasic == 'm_nbasic' )
	{	
		$tsql = $tsql . " and b.m_kodekaret not in ( select m_rubberid from t_basicinv where m_brand = '".$kdbrand."' and m_periode = '".$rowperiod['coperiod']."' ) ";	
	}
	if ( $cekbatu == 'Y' )
	{	
		$tsql = $tsql . " and a.m_productid in ( select distinct m_productid 
												from t_stockdetail 
												where m_butir > 0 and m_carat > 0 and m_carat/m_butir >= ".$kdsize." and m_carat/m_butir <= ".$kdsize2." ) ";	
	}
	if ( $cekcert == 'm_cert' )
	{	
		$tsql = $tsql . " and a.m_productid in ( select distinct m_productid 
												from t_stockdetail 
												where m_sertifikat is not null and m_sertifikat <> '' ) ";	
	}
	else if ( $cekcert == 'm_ncert' )
	{	
		$tsql = $tsql . " and a.m_productid not in ( select distinct m_productid 
												from t_stockdetail 
												where m_sertifikat is not null and m_sertifikat <> '' ) ";	
	}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsql = $tsql." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsql = $tsql." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and b.m_item = '".$vkode."'";}
	else if ( $kdby == 'm_distribusi' ){ $tsql = $tsql." and b.m_distribusi = '".$vkode."'";}
	else if ( $kdby == 'm_segmen' ){ $tsql = $tsql." and b.m_segmen = '".$vkode."'";}
	else if ( $kdby == 'm_tanggal' ){ $tsql = $tsql." and CONVERT(varchar(8),z.m_tanggal,112) = '".$vkode."'";}
	else if ( $kdby == 'm_jam' ){ $tsql = $tsql." and left(CONVERT(varchar(8),z.m_tanggal,108),2) = '".$vkode."'";}
	
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
            	<th colspan="22" align="left"><h2>LAPORAN PENJUALAN ( <?php echo $kdcabang ; ?> ) </h2></th>
            </tr>
        	<tr>
            	<th colspan="22" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th colspan="22"></th>
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
                <th width="100" style="border:1px solid #000">Warna</th>
                <th width="100" style="border:1px solid #000">Qty</th>
                <th width="100" style="border:1px solid #000">Tukaran Beli</th>
                <th width="100" style="border:1px solid #000">Disc.Reguler</th>
                <th width="100" style="border:1px solid #000">Disc.VIP</th>
                <th width="100" style="border:1px solid #000">Disc.Promo</th>
                <th width="100" style="border:1px solid #000">Pembulatan</th>
                <th width="100" style="border:1px solid #000">Total Disc.</th>
                <th width="100" style="border:1px solid #000">Net.Sales</th>
                <th width="100" style="border:1px solid #000"> Net Weight </th>
                <th width="100" style="border:1px solid #000"> Gross Weight </th>
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
			$tnet = 0 ;
			$tgross = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4'];
				
				$tsqlsales = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
                $stmtsales = sqlsrv_query( $con_dbnew, $tsqlsales);
                $rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC);
				
				
				$i++ ;
				$tqty1 = $tqty1 + $row['m_qty'] ;
				$tdisc1 = $tdisc1 + $row['m_discount'] ;
				$tdisc2 = $tdisc2 + $row['m_discount2'] ;
				$tdisc3 = $tdisc3 + $row['m_discount3'] ;
				$tdisc4 = $tdisc4 + $row['m_discount4'] ;
				$ttotdisc = $ttotdisc + $totdisc;
				$netsales = $netsales + ($row['m_harga'] - $totdisc) ;
				$tnet = $tnet + $row['m_netweight'] ;
				$tgross = $tgross + $row['m_grossweight'] ;
				
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
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_warna']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['m_tukarb'], 3, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount2'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount3'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_discount4'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_harga'] - $totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center" colspan="11"></td>
                <td style="border:1px solid #000" align="center"><?php echo number_format($tqty1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="center"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc2, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc3, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc4, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($ttotdisc, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($netsales, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnet, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tgross, 2, '.', ','); ?></td>
            </tr>
        </tfoot>

	</table>
