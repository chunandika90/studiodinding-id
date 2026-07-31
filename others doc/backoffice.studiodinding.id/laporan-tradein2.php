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
	$stqlty = $_GET['ql'];

	if (($_SESSION['store'] <> '00') && ($_SESSION['store'] <> 'M0') && $_SESSION['store'] <> $kdcabang) {$kdcabang = 'XX' ;}
	if ($_SESSION['store'] == 'M0'){ $kdgroup = 'M0000001';}
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	$tsqlx = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal2,103) as co_tglasal, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
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
	if ( $stqlty != 'ALL' ){$tsqlx = $tsqlx . " and b.m_kelas = '".$stqlty."'";}
	
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

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="16">
            	<div>
                	<div class="pull-left">
                    	<h4>List Barang Trade-In, Store - <?php echo $kdcabang ; ?></h4>
                    </div>
                    <div class="pull-right">
					<?php
                    if (substr($xparam[3],3,1) == 'Y')
                    {
                        ?>
                        <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>') "/> 
                    	<?php
					}
					?>
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th width="25">No</th>
            <th width="50">St</th>
            <th width="50">Tanggal</th>
            <th width="50">Nomor</th>
            <th width="200">Customer</th>
            <th width="100">No.PLU</th>
            <th width="100">Group</th>
            <th width="150">Item</th>
            <th width="50">Qty</th>            
            <th width="130"><div align="right">Harga</div></th>            
            <th width="50"><div align="right">(+/-)%</div></th>
            <th width="50" style="color:#F00">St</th>
            <th width="50" style="color:#F00">Tanggal</th>
            <th width="50" style="color:#F00">Nomor</th>
            <th width="130" style="color:#F00"><div align="right">Harga Asal</div></th>            
            <th width="50"><div align="right">Net</div></th>
            <th width="50"><div align="right">Butir</div></th>
            <th width="50"><div align="right">Carat</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            while( $rowx = sqlsrv_fetch_array( $stmtx, SQLSRV_FETCH_ASSOC))
            {
				$i++ ;
				$depr = (( $rowx['m_harga2'] - $rowx['m_harga'] ) / $rowx['m_harga2'] ) * 100 ;
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $rowx['m_cabang']; ?></td>
                	<td><?php echo $rowx['co_tgl']; ?></td>
                	<td><?php echo $rowx['m_nomor']; ?></td>
                	<td><?php echo $rowx['m_nama']; ?></td>
                	<td onClick="view_modal('<?php echo $rowx['m_kodebarang']; ?>','<?php echo $rowx['m_productid']; ?>')"  style="cursor:pointer"><?php echo $rowx['m_productid']; ?></td>
                	<td><?php echo $rowx['namabarang']; ?></td>
                	<td><?php echo $rowx['namaitem']; ?></td>
                	<td><?php echo $rowx['m_qty']; ?></td>
                	<td><div align="right"><?php echo number_format($rowx['m_harga'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($depr, 2, '.', ','); ?></div></td>
                	<td style="color:#F00"><?php echo $rowx['m_cabang2']; ?></td>
                	<td style="color:#F00"><?php echo $rowx['co_tglasal']; ?></td>
                	<td style="color:#F00"><?php echo $rowx['m_nomor2']; ?></td>
                	<td style="color:#F00"><div align="right"><?php echo number_format($rowx['m_harga2'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($rowx['m_netweight'], 2, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($rowx['m_butir'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($rowx['m_carat'], 3, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>

<?php 

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
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
	if ( $stqlty != 'ALL' ){$tsql = $tsql . " and f.m_kelas = '".$stqlty."'";}
	
	if ( $kdby == 'm_cabang' ){ $tsql = $tsql." and d.m_cabang = '".$vkode."'";}
	else if ( $kdby == 'm_customer' ){ $tsql = $tsql." and z.m_kodecust = '".$vkode."'";}
	else if ( $kdby == 'm_sales' ){ $tsql = $tsql." and z.m_kodesales = '".$vkode."'";}
	else if ( $kdby == 'm_group' ){ $tsql = $tsql." and d.m_kodebarang = '".$vkode."'";}
	else if ( $kdby == 'm_level' ){ $tsql = $tsql." and f.m_klasifikasi = '".$vkode."'";}
	else if ( $kdby == 'm_kategori' ){ $tsql = $tsql." and f.m_kategori = '".$vkode."'";}
	else if ( $kdby == 'm_item' ){ $tsql = $tsql." and f.m_item = '".$vkode."'";}
	
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal asc, z.m_nomor asc, a.m_kodebarang asc, a.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//echo $tsql ;


//	echo $tsqlx ;
?>
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="16">
            	<div>
                	<div class="pull-left">
                    	<h4>List Penjualan Trade-In</h4>
                    </div>
                    <div class="pull-right">
					<?php
                    if (substr($xparam[3],3,1) == 'Y')
                    {
                        ?>
                       <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1d('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1d('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>','<?php echo $stqlty; ?>') "/> 
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
            <th width="50"><div align="center">Qty</div></th>
            <th width="100"><div align="right">Disc.Reguler</div></th>
            <th width="100"><div align="right">Disc.VIP</div></th>
            <th width="100"><div align="right">Disc.Promo</div></th>
            <th width="100"><div align="right">Pembulatan</div></th>
            <th width="100"><div align="right">Total Disc.</div></th>
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

				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4']
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['m_cabang']; ?></td>
                	<td><?php echo $row['co_tgl']; ?></td>
                	<td><?php echo $row['m_nomor']; ?></td>
                	<td><?php echo $row['m_nama']; ?></td>
                	<td><?php echo $rowsales['m_nama']; ?></td>
                	<td onClick="view_modal('<?php echo $row['m_kodebarang']; ?>','<?php echo $row['m_productid']; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                	<td><?php echo $row['namabarang']; ?></td>
                	<td><?php echo $row['namaitem']; ?></td>
                	<td><div align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount2'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount3'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_discount4'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></div></td>
                	<td style="font-weight:bold"><div align="right"><?php echo number_format($row['m_harga'] - $totdisc, 0, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
