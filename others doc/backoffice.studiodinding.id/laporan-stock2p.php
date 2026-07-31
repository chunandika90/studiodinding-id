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
	$kdby = $_GET['by'];
	$ststock = $_GET['st'];
	$stqlty = $_GET['ql'];

	$vkode = $_GET['vkode'];
	$vnama = $_GET['vnama'];

	if (($_SESSION['store'] <> '00') && ($_SESSION['store'] <> 'M0') && $_SESSION['store'] <> $kdcabang) {$kdcabang = 'XX' ;}
	if ($_SESSION['store'] == 'M0'){ $kdgroup = 'M0000001';}
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($ststock ==''){$ststock = 'X';}
	if ($kdby ==''){$kdby = 'm_cabang';}
	if ($stqlty ==''){$stqlty = 'ALL';}


	$tsql = "	select 	a.*, dbo.f_harga(a.m_kodebarang,a.m_productid) as coharga,  b.m_klasifikasi,  b.m_kategori,  b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_stockinv a, t_stockdata b, msbarang c, msmaster e
				where 	a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode and 
						( a.m_qty <> 0 or a.m_otw <> 0 ) ";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdklas != 'ALL' ){$tsql = $tsql . " and b.m_klasifikasi = '".$kdklas."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kddist != 'ALL' ){$tsql = $tsql . " and b.m_distribusi = '".$kddist."'";}
	if ( $kdseg != 'ALL' ){$tsql = $tsql . " and b.m_segmen = '".$kdseg."'";}
	if ( $kdplu != 'ALL' ){$tsql = $tsql . " and a.m_productid = '".$kdplu."'";}
	if ( $ststock != 'X' ){$tsql = $tsql . " and b.m_status = '".$ststock."'";}
	if ( $stqlty != 'ALL' ){$tsql = $tsql . " and b.m_kelas = '".$stqlty."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and a.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and a.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsql = $tsql." and b.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and b.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and b.m_item = '".$vkode."'";}
	else if ( $kdby == 'm_distribusi' ){ $tsql = $tsql." and b.m_distribusi = '".$vkode."'";}
	else if ( $kdby == 'm_segmen' ){ $tsql = $tsql." and b.m_segmen = '".$vkode."'";}
	
	$tsql = $tsql." order by a.m_cabang asc, a.m_kodebarang asc, a.m_productid asc" ;
	
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
                <th width="100" style="border:1px solid #000">No.PLU</th>
                <th width="100" style="border:1px solid #000">Kode Karet</th>
                <th width="100" style="border:1px solid #000">Group</th>
                <th width="100" style="border:1px solid #000">Klasifikasi</th>
                <th width="100" style="border:1px solid #000">Kategori</th>
                <th width="100" style="border:1px solid #000">Item</th>
                <th width="100" style="border:1px solid #000">Qty</th>
                <th width="100" style="border:1px solid #000">In Trans</th>
                <th width="100" style="border:1px solid #000">Jumlah</th>
                <th width="100" style="border:1px solid #000">M</th>
                <th width="100" style="border:1px solid #000">R</th>
                <th width="100" style="border:1px solid #000">Net-W</th>
                <th width="100" style="border:1px solid #000">Gross-W</th>
                <th width="100" style="border:1px solid #000">Butir</th>
                <th width="100" style="border:1px solid #000">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$i = 0 ;
			$tqty1 = 0 ;
			$totw = 0 ;
			$ttotal = 0 ;
			$thargam = 0 ;
			$thargar = 0 ;
			$tnet = 0;
			$tgross = 0;
			$tbutir = 0 ;
			$tcarat = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$i++ ;
				$tqty1 = $tqty1 + $row['m_qty'] ;
				$totw = $totw + $row['m_otw'] ;
				$ttotal = $ttotal + $row['coharga'] ;
				$thargam = $thargam + $row['m_hargam'] ;
				$thargar = $thargar + $row['m_hargar'] ;
				$tnet = $tnet + $row['m_netweight'] ;
				$tgross = $tgross + $row['m_grossweight'] ;
				$tbutir = $tbutir + $row['m_butir'] ;
				$tcarat = $tcarat + $row['m_carat'] ;
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $i; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_cabang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_productid']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_rubberid']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['namabarang']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_klasifikasi']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['m_kategori']; ?></td>
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['m_otw'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['coharga'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_hargam'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_hargar'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center" colspan="8"></td>
                <td style="border:1px solid #000" align="center"><?php echo number_format($tqty1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($totw, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($ttotal, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($thargam, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($thargar, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnet, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tgross, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>

            </tr>
        </tfoot>

	</table>
