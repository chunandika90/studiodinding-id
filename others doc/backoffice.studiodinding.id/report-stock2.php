<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$tgl = date('Y-m-d 23:59:59');
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdstock = $_GET['kdst'];
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}

	$tsql = "	select 	a.*, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, d.m_nama as namakatg, e.m_nama as namaitem,b.m_rubberid
				from 	t_stockinv a, t_stockdata b, msbarang c, msmaster d, msmaster e
				where 	a.m_kodebarang = b.m_kodebarang and
						a.m_productid = b.m_productid and
						( a.m_qty > 0 or a.m_otw > 0 ) and 
						a.m_kodebarang = c.m_kode and
						d.m_type = 'CATEGORY' and
						b.m_kategori = d.m_kode and 
						e.m_type = 'ITEM' and
						b.m_item = e.m_kode 
			";
	if ( $kdcabang != 'ALL' ){$tsql = $tsql . " and a.m_cabang = '".$kdcabang."'";}
	if ( $kdgroup != 'ALL' ){$tsql = $tsql . " and a.m_kodebarang = '".$kdgroup."'";}
	if ( $kdkatg != 'ALL' ){$tsql = $tsql . " and b.m_kategori = '".$kdkatg."'";}
	if ( $kditem != 'ALL' ){$tsql = $tsql . " and b.m_item = '".$kditem."'";}
	if ( $kdstock != 'ALL' ){$tsql = $tsql . " and b.m_status = '".$kdstock."'";}
	
	$tsql = $tsql." order by a.m_cabang asc, a.m_kodebarang, b.m_kategori, b.m_item asc, a.m_productid asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	$tkursLD = "select * from msrate where m_kode = 'LD' and m_tanggal = ( select max(m_tanggal) from msrate where m_kode = 'LD' and  m_tanggal <= '".$tgl."' )";
	$stmtLD= sqlsrv_query( $con_dbnew, $tkursLD);
	$rowLD = sqlsrv_fetch_array( $stmtLD, SQLSRV_FETCH_ASSOC);
	
//	echo $tsql ;
	
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="12">
            	<div class="container">
                	<div class="pull-left">
                    	<h4>Report Stock</h4>
                    </div>
                    <div class="pull-right">
                        <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/>  
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th width="50">Cabang</th>
            <th width="100">No.PLU</th>
            <th width="100">Kode Karet</th>
            <th width="100">Group</th>
            <th width="150">Kategori</th>
            <th width="150">Item</th>
            <th width="50"><div align="center">Qty</div></th>
            <th width="50"><div align="center">Trst</div></th>
            <th width="150"><div align="right">Harga</div></th>
            <th width="150"><div align="right">Gross</div></th>
            <th width="150"><div align="right">Net</div></th>
            <th width="150"><div align="right">Butir</div></th>
            <th width="150"><div align="right">Carat</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$harga = $row['m_harga'] ;
				
				if ($row['m_kodebarang'] == 'P0000004')
				{
					// Untuk kadar 99.99
					if ($row['m_kadar'] >= 0.900)
					{
						$tgp = $rowLD['m_jual'] * 1.2 ;
					}
					else if ($row['m_item'] == 'H') // CHAIN
					{
						if (strtoupper($row['m_warna']) == 'KNG')
						{
							$tgp = $rowLD['m_jual'] * 0.86 ;
						}
						else
						{
							$tgp = $rowLD['m_jual'] * 0.87 ;
						}
					}
					else // NON CHAIN
					{
						if (strtoupper($row['m_warna']) == 'KNG')
						{
							$tgp = $rowLD['m_jual'] * 0.90 ;
						}
						else
						{
							$tgp = $rowLD['m_jual'] * 0.91 ;
						}
					}
					$tgp = ceil($tgp / 1000) * 1000 ;
					$harga = $row['m_grossweight'] * $tgp ;
					$harga = ceil($harga / 1000) * 1000 ;
				}

				
                ?>
				<tr>
                	<td><?php echo $row['m_cabang']; ?></td>
                	<td onClick="view_modal('<?php echo $row['m_kodebarang']; ?>','<?php echo $row['m_productid']; ?>')" style="cursor:pointer"><?php echo $row['m_productid']; ?></td>
                	<td><?php echo $row['m_rubberid']; ?></td>
                	<td><?php echo $row['namabarang']; ?></td>
                	<td><?php echo $row['namakatg']; ?></td>
                	<td><?php echo $row['namaitem']; ?></td>
                	<td><div align="center"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['m_otw'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($harga, 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
