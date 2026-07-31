<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

  	include "mssql-dbnew.php";
	
	$lokasi = $_GET['lok'];
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kdstock = $_GET['kdst'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$prm = $_GET['prm'];
	
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['cabang'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}

	$tsql = "	select 	a.*, convert(varchar(10),z.m_tanggal,103) as co_tgl, z.m_cabang, z.m_nomor, z.m_nama, b.m_productid, b.m_grossweight, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as namabarang, e.m_nama as namaitem
				from 	t_transfer z, t_transfer2 a, t_stockdata b, msbarang c, msmaster e
				where 	z.m_cabang = a.m_cabang and
						z.m_nomor = a.m_nomor and 
						z.m_status = 'A' and 
						a.m_status = 'Y' and
						z.m_lokasi2 = '".$lokasi."' and
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
	if ( $kdstock != 'ALL' ){$tsql = $tsql . " and  b.m_status = '".$kdstock."'";}
	
	$tsql = $tsql." order by z.m_cabang asc, z.m_tanggal desc, z.m_nomor desc, a.m_kodebarang, b.m_kategori, b.m_item asc, b.m_productid asc" ;
//	echo $tsql;
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
            	<th colspan="12" align="left"><h2>Report Penerimaan <?php echo $kdcabang;  ?></h2></th>
            </tr>	   
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="80" style="border:1px solid #000">From</th>
                <th width="80" style="border:1px solid #000">Tanggal</th>
            	<th width="80" style="border:1px solid #000">Nomor</th>
            	<th width="80" style="border:1px solid #000">Nama</th>
            	<th width="80" style="border:1px solid #000">No.PLU</th>
            	<th width="80" style="border:1px solid #000">Group</th>
            	<th width="80" style="border:1px solid #000">Item</th>
            	<th width="80" style="border:1px solid #000">Qty</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Net</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Butir</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$tqty = 0;
			$tnetw= 0;
			$tbutir= 0;
			$tcarat= 0;
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				
				$tqty = $tqty + $row['m_qty'] ;
				$tnetw = $tnetw + $row['m_netweight'] ;
				$tbutir = $tbutir + $row['m_butir'] ;
				$tcarat = $tcarat + $row['m_carat'] ;
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000" align="center"><?php echo $row['m_lokasi']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['co_tgl']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_nomor']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_nama']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_productid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namabarang']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></td>

                </tr>
            <?php
            }
            ?>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" colspan="7"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tqty, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnetw, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
            </tr>
        </tbody>
	</table>

