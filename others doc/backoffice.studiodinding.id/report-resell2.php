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
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal2,103) as co_tglasal, z.m_cabang, z.m_nomor, z.m_nama, z.m_kodesales, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_resell z, t_resell2 a, t_stockdata b, msbarang c, msmaster e
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

//	echo $tsql ;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="11">
            	<div class="container">
                	<div class="pull-left">
                    	<h4>Report Resell</h4>
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
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$depr = (( $row['m_harga2'] - $row['m_harga'] ) / $row['m_harga2'] ) * 100 ;
                ?>
				<tr>
                	<td><?php echo $row['m_cabang']; ?></td>
                	<td><?php echo $row['co_tgl']; ?></td>
                	<td><?php echo $row['m_nomor']; ?></td>
                	<td><?php echo $row['m_nama']; ?></td>
                	<td><?php echo $row['m_productid']; ?></td>
                	<td><?php echo $row['namabarang']; ?></td>
                	<td><?php echo $row['namaitem']; ?></td>
                	<td><?php echo $row['m_qty']; ?></td>
                	<td><div align="right"><?php echo number_format($row['m_harga'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($depr, 2, '.', ','); ?></div></td>
                	<td style="color:#F00"><?php echo $row['m_cabang2']; ?></td>
                	<td style="color:#F00"><?php echo $row['co_tglasal']; ?></td>
                	<td style="color:#F00"><?php echo $row['m_nomor2']; ?></td>
                	<td style="color:#F00"><div align="right"><?php echo number_format($row['m_harga2'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
