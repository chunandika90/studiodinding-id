<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$periode = $_GET['pr'];
	$pacing = $_GET['pc'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($periode ==''){date("Y-m");}
	if ($pacing ==''){$pacing = 'ALL';}
	$abc = explode('-',$periode);

	$tsql = "	select 	a.*, f.m_nama as namasales, convert(varchar(10),z.m_tanggal,103) as co_tgl, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_pos z, t_pos2 a, t_stockdata b, msbarang c, msmaster e, mssales f
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and
						a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						a.m_kodebarang = c.m_kode and
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode and 
						z.m_kodesales = f.m_kode and 
						year(z.m_tanggal) = ".$abc[0]." and month(z.m_tanggal) = ".$abc[1] ;
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and z.m_kodesales = '".$kdgroup."'";}
	if ( $pacing != 'ALL' )
	{
		if ($pacing == '1')
		{
			$tgl1 = $abc[0].'/'.$abc[1].'/01 00:00:00';
			$tgl2 = $abc[0].'/'.$abc[1].'/07 23:59:59';
			$tsql = $tsql . " and z.m_tanggal >= '".$tgl1."' and z.m_tanggal <= '".$tgl2."'";
		}
		else if ($pacing == '2')
		{
			$tgl1 = $abc[0].'/'.$abc[1].'/08 00:00:00';
			$tgl2 = $abc[0].'/'.$abc[1].'/15 23:59:59';
			$tsql = $tsql . " and z.m_tanggal >= '".$tgl1."' and z.m_tanggal <= '".$tgl2."'";
		}
		else if ($pacing == '3')
		{
			$tgl1 = $abc[0].'/'.$abc[1].'/16 00:00:00';
			$tgl2 = $abc[0].'/'.$abc[1].'/22 23:59:59';
			$tsql = $tsql . " and z.m_tanggal >= '".$tgl1."' and z.m_tanggal <= '".$tgl2."'";
		}
		else if ($pacing == '4')
		{
			$tgl1 = $abc[0].'/'.$abc[1].'/23 00:00:00';
			$tgl2 = $abc[0].'/'.$abc[1].'/31 23:59:59';
			$tsql = $tsql . " and z.m_tanggal >= '".$tgl1."' and year(z.m_tanggal) = ".$abc[0]." and month(z.m_tanggal) = ".$abc[1];
		}
	}
	
	$tsql = $tsql." order by z.m_cabang asc, a.m_kodebarang asc, z.m_tanggal asc, z.m_nomor asc, b.m_kategori asc, b.m_item asc, b.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="9">
            	<div class="container">
                	<div class="pull-left">
                    	<h4>Report Penjualan</h4>
                    </div>
                    <div class="pull-right">
					<?php
                    if (substr($xparam[3],3,1) == 'Y')
                    {
                        ?>
                        <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/>  
                        <?php
					}
					?>
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th width="20">St</th>
            <th width="50">Tanggal</th>
            <th width="50">Nomor</th>
            <th width="150">Customer</th>
            <th width="120">Sales</th>
            <th width="75">No.PLU</th>
            <th width="75">Group</th>
            <th width="100">Item</th>
            <th width="100"><div align="right">Jumlah</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$totdisc = $row['m_discount'] + $row['m_discount2'] + $row['m_discount3'] + $row['m_discount4']
                ?>
				<tr>
                	<td><?php echo $row['m_cabang']; ?></td>
                	<td><?php echo $row['co_tgl']; ?></td>
                	<td><?php echo $row['m_nomor']; ?></td>
                	<td><?php echo $row['m_nama']; ?></td>
                	<td><?php echo $row['namasales']; ?></td>
                	<td  onClick="view_modal('<?php echo $row['m_kodebarang']; ?>','<?php echo $row['m_productid']; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                	<td><?php echo $row['namabarang']; ?></td>
                	<td><?php echo $row['namaitem']; ?></td>
                	<td><div align="right"><?php echo number_format($row['m_harga'] - $totdisc, 0, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
