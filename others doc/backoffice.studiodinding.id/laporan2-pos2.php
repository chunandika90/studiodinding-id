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

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),z.m_tanggal,108) as co_jam, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodecust, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
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

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="19">
            	<div>
                	<div class="pull-left">
                    	<h4>Report Penjualan, Store - <?php echo $kdcabang ; ?></h4>
                    </div>
                    <div class="pull-right">
					<?php
                    if (substr($xparam[3],3,1) == 'Y')
                    {
                        ?>
                       <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kddist; ?>','<?php echo $kdseg; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>','<?php echo $kdbasic; ?>','<?php echo $kdsize; ?>', '<?php echo $kdsize2; ?>', '<?php echo $kdcert; ?>')">
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kddist; ?>','<?php echo $kdseg; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>','<?php echo $kdbasic; ?>','<?php echo $kdsize; ?>', '<?php echo $kdsize2; ?>', '<?php echo $kdcert; ?>')">  
                    	<?php
					}
					?>
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th width="25">No</th>
            <th width="25">St</th>
            <th width="50">Tanggal</th>
            <th width="50">Nomor</th>
            <th width="200">Customer</th>
            <th width="150">Sales</th>
            <th width="100">No.PLU</th>
            <th width="100">Group</th>
            <th width="150">Item</th>
            <th width="50">Net.W</th>
            <th width="50">Gross.W</th>
            <th width="50"><div align="center">Qty</div></th>
            <th width="100"><div align="right">Disc.Reguler</div></th>
            <th width="100"><div align="right">Disc.VIP</div></th>
            <th width="100"><div align="right">Disc.Promo</div></th>
            <th width="100"><div align="right">Pembulatan</div></th>
            <th width="100"><div align="right">Total Disc.</div></th>
            <th width="20"><div align="center">% Disc.</div></th>
            <th width="130"><div align="right">Net.Sales</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$i++ ;
                $tsqlsales = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
                $stmtsales = sqlsrv_query( $con_dbnew, $tsqlsales);
                $rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC);
				
				$pctdisc = 0 ;
				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4'];
				if (( $row['m_qty'] * $row['m_harga'] ) > 0 ) { $pctdisc = ($totdisc / ( $row['m_qty'] * $row['m_harga'] )) * 100; }
				
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['m_cabang']; ?></td>
                	<td align="center"><?php echo $row['co_tgl'].' '.$row['co_jam']; ?></td>
                	<td><?php echo $row['m_nomor']; ?></td>
                	<td onClick="view_cust('<?php echo $row['m_kodecust']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
                	<td><?php echo $rowsales['m_nama']; ?></td>
                	<td  onClick="view_modal('<?php echo $row['m_kodebarang']; ?>','<?php echo $row['m_productid']; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                	<td><?php echo $row['namabarang']; ?></td>
                	<td><?php echo $row['namaitem']; ?></td>
                	<td><div align="center"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount2'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount3'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount4'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($pctdisc, 2, '.', ',').' %'; ?></div></td>
                	<td style="font-weight:bold"><div align="right"><?php echo number_format($row['m_harga'] - $totdisc, 0, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
