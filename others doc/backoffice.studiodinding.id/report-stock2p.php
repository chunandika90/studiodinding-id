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
            	<th colspan="12" align="left"><h2>Report Stock <?php echo $kdcabang;  ?></h2></th>
            </tr>	   
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="80" style="border:1px solid #000">Cabang</th>
                <th width="80" style="border:1px solid #000">No.PLU</th>
                <th width="80" style="border:1px solid #000">Kode Karet</th>
            	<th width="80" style="border:1px solid #000">Group</th>
            	<th width="80" style="border:1px solid #000">Kategori</th>
            	<th width="80" style="border:1px solid #000">Item</th>
            	<th width="80" style="border:1px solid #000">Qty</th>
            	<th width="80" style="border:1px solid #000">Trst</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Harga</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Gross</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Net</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Butir</th>
            	<th width="80" style="border:1px solid #000;text-align:right">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$tqty = 0;
			$ttrst = 0;
			$tharga = 0;
			$tgross = 0;
			$tnetw= 0;
			$tbutir= 0;
			$tcarat= 0;
			
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
				
				$tqty = $tqty + $row['m_qty'] ;
				$ttrst = $ttrst + $row['m_otw'] ;
				$tharga = $tharga + $row['m_harga'] ;
				$tgross = $tgross + $row['m_grossweight'] ;
				$tnetw = $tnetw + $row['m_netweight'] ;
				$tbutir = $tbutir + $row['m_butir'] ;
				$tcarat = $tcarat + $row['m_carat'] ;
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000" align="center"><?php echo $row['m_cabang']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_productid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['m_rubberid']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namabarang']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namakatg']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo $row['namaitem']; ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_otw'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_harga'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['m_carat'], 3, '.', ','); ?></td>

                </tr>
            <?php
            }
            ?>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" colspan="6"></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tqty, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($ttrst, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tharga, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tgross, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnetw, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
            </tr>
        </tbody>
	</table>

