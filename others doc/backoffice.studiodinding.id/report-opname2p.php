<?php
	session_start();
	include "mssql-dbnew.php" ;

	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdstock = $_GET['kdst'];
	$kdby = $_GET['kdby'];
	$periode  = $_GET['pr'];
	$soid = $_GET['so'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	$stat = $_GET['strep'];
	$vkode = $_GET['vkode'];
	$judul = '' ;
	
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}
	
	if ($stat == '3')
	{
		$judul = 'SO ada, Stock tidak ada';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode and 
							a.m_productid not in ( select m_productid from t_stockopname x, t_stockopname0 y where x.m_cabang = y.m_cabang and x.m_nomor = y.m_nomor and y.m_nomor = '".$soid."' and y.m_status = 'A' ) ";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}
	elseif ($stat == '4')
	{
		$judul = 'SO tidak ada, Stock ada';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg
					from 	t_stockopname a, t_stockopname0 b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_nomor = '".$soid."' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode and 
							a.m_productid not in ( select m_productid from t_opname2 x, t_opname y where x.m_cabang = y.m_cabang and x.m_nomor = y.m_nomor and y.m_soid  = '".$soid."' and y.m_status = 'A' ) ";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
		
	}
	elseif ($stat == '5')
	{
		$judul = 'Todak ada gambar';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_nopic = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}

	elseif ($stat == '6')
	{
		$judul = 'Beda gambar';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_bedapic = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}

	}
	elseif ($stat == '7')
	{
		$judul = 'Beda bandrol';
		$tsql = "	select	a.m_kodebarang, a.m_productid, c.m_rubberid, c.m_item, d.m_nama as namaitem, e.m_nama as namabrg,a.m_keterangan
					from 	t_opname2 a, t_opname b, t_stockdata c, msmaster d, msbarang e
					where 	a.m_cabang = b.m_cabang and 
							a.m_nomor = b.m_nomor and 
							b.m_status = 'A' and 
							b.m_soid = '".$soid."' and 
							a.m_bedabandrol = 'Y' and 
							a.m_kodebarang = c.m_kodebarang and 
							a.m_productid = c.m_productid and 
							d.m_type = 'ITEM' and 
							c.m_item = d.m_kode and 
							a.m_kodebarang = e.m_kode";
		if ($kdgroup != 'ALL'){$tsql = $tsql." and a.m_kodebarang = '".$kdgroup."'" ;}
		if ($kdkatg != 'ALL'){$tsql = $tsql." and c.m_kategori = '".$kdkatg."'" ;}
		if ($kditem != 'ALL'){$tsql = $tsql." and c.m_item = '".$kditem."'" ;}
		if ($kdstock != 'ALL'){$tsql = $tsql." and c.m_status = '".$kdstock."'" ;}
	}
	
	if ($kdby == 'm_cabang')
		{$tsql = $tsql." and a.m_cabang = '".$vkode."'" ;}
	else if ($kdby == 'm_group')
		{$tsql = $tsql." and a.m_kodebarang = '".$vkode."'" ;}
	else if ($kdby == 'm_kategori')
		{$tsql = $tsql." and c.m_kategori = '".$vkode."'" ;}
	else if ($kdby == 'm_item')
		{$tsql = $tsql." and c.m_status = '".$vkode."'" ;}
	
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

	<table width="80%" style="border-collapse:collapse;font-family:Arial, Helvetica, sans-serif;font-size:12px">
    	<thead>
        	<tr>
            	<th colspan="12" align="left"><h2>Report StockOpname <?php echo $kdcabang;  ?></h2></th>
            </tr>	   
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="80" style="border:1px solid #000">No</th>
                <th width="80" style="border:1px solid #000">No.PLU</th>
            	<th width="80" style="border:1px solid #000">Kode Karet</th>
            	<th width="80" style="border:1px solid #000">Group</th>
            	<th width="80" style="border:1px solid #000">Item</th>
            	<th width="80" style="border:1px solid #000">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
        	$i = 0 ;
			$tqty = 0;
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				
           		$i = $i + 1 ;
				$tqty = $tqty + $row['m_qty'] ;
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000" align="center"><?php echo $i; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_productid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_rubberid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namabrg']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_keterangan']; ?></td>

                </tr>
            <?php
            }
            ?>
        </tbody>
	</table>

