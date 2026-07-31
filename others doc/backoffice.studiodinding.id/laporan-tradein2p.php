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
	$vnama = $_GET['vnama'];	

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
	
	$tsqlx = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal2,103) as co_tglasal, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem,b.m_warna,b.m_tukarb
				from 	t_pos z, t_tradein2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						z.m_type = 'T' and
						z.m_tanggal >= '".$tanggal1."' and 
						z.m_tanggal <= '".$tanggal2."' and 
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode ";
	if ( $kdcabang != 'ALL' ){$tsqlx = $tsqlx . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsqlx = $tsqlx . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsqlx = $tsqlx . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsqlx = $tsqlx . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsqlx = $tsqlx . " and b.m_item = '".$kditem."'";}
	if ( $kdplu != 'ALL' ){$tsqlx = $tsqlx . " and a.m_productid = '".$kdplu."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsqlx = $tsqlx." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsqlx = $tsqlx." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsqlx = $tsqlx." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsqlx = $tsqlx." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsqlx = $tsqlx." and b.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsqlx = $tsqlx." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsqlx = $tsqlx." and b.m_item = '".$vkode."'";}
	
	$tsqlx = $tsqlx." order by z.m_cabang asc, z.m_tanggal asc, z.m_nomor asc, a.m_kodebarang asc, a.m_productid asc" ;	
	$stmtx = sqlsrv_query( $con_dbnew, $tsqlx);
	
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
            	<th colspan="20" align="left"><h2>List Barang Trade IN( <?php echo $kdcabang ; ?> ) </h2></th>
            </tr>
        	<tr>
            	<th colspan="20" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th colspan="20"></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="10" style="border:1px solid #000">No</th>
            	<th width="50"  style="border:1px solid #000">St</th>
                <th width="100" style="border:1px solid #000">Tanggal</th>
                <th width="100" style="border:1px solid #000">Nomor</th>
                <th width="100" style="border:1px solid #000">Customer</th>
                <th width="100" style="border:1px solid #000">No.PLU</th>
                <th width="100" style="border:1px solid #000">Group</th>
                <th width="100" style="border:1px solid #000">Item</th>
                <th width="100" style="border:1px solid #000">Warna</th>
                <th width="100" style="border:1px solid #000">Qty</th>
                <th width="100" style="border:1px solid #000">Tukaran Beli</th>
                <th width="100" style="border:1px solid #000">Harga</th>
                <th width="100" style="border:1px solid #000">(+/-)%</th>
                <th width="100" style="border:1px solid #000">St</th>
                <th width="100" style="border:1px solid #000">Tanggal</th>
                <th width="100" style="border:1px solid #000">Nomor</th>
                <th width="100" style="border:1px solid #000">Harga Asal</th>
                <th width="100" style="border:1px solid #000">Net</th>
                <th width="100" style="border:1px solid #000">Butir</th>
                <th width="100" style="border:1px solid #000">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
                
            $i = 0 ;
			$tqty1 = 0 ;
			$tharga = 0 ;
			$tdepr = 0 ;
			$tharga2 = 0 ;
			$tnet = 0 ;
			$tbutir = 0 ;
			$tcarat = 0 ;
			$thargam = 0 ;
			$thargar = 0 ;

            while( $rowx = sqlsrv_fetch_array( $stmtx, SQLSRV_FETCH_ASSOC))
            {
				
				$depr = (( $rowx['m_harga2'] - $rowx['m_harga'] ) / $rowx['m_harga2'] ) * 100 ;
				
				$i++ ;
				$tqty1 = $tqty1 + $rowx['m_qty'] ;
				$tharga = $tharga + $rowx['m_harga'] ;
				$tdepr = $tdepr + $depr;
				$tharga2 = $tharga2 + $rowx['m_harga2'] ;
				$tnet = $tnet + $rowx['m_netweight'] ;
				$tbutir = $tbutir + $rowx['m_butir'] ;
				$tcarat = $tcarat + $rowx['m_carat'] ;
				$thargam = $thargam + $rowx['m_hargam'] ;
				$thargar = $thargar + $rowx['m_hargar'] ;
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $i; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_cabang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['co_tgl']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_nomor']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_nama']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_productid']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['namabarang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['namaitem']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_warna']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($rowx['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($rowx['m_tukarb'], 3, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($rowx['m_harga'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($depr, 2, '.', ','); ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_cabang2']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['co_tglasal']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $rowx['m_nomor2']; ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($rowx['m_harga2'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($rowx['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($rowx['m_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($rowx['m_carat'], 3, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center" colspan="9"></td>
                <td style="border:1px solid #000" align="center"><?php echo number_format($tqty1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="center"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tharga, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdepr, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="center" colspan="4"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tharga2, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnet, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
            </tr>
        </tfoot>

	</table>
