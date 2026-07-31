<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kditem = $_GET['it'];
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	$tsql = "select * from dbo.f_laporanttb('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kditem."', '".$kdplu."', '".$kdby."') order by vf_kode asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	if( $stmt === false)
	{
		 echo "Error in query preparation/execution.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	$tsql0 = " select * from msmaster where m_type= 'STORE' and m_kode = '".$kdcabang."' " ;
	$stmt0 = sqlsrv_query($conn, $tsql0);
	$row0 = sqlsrv_fetch_array( $stmt0, SQLSRV_FETCH_ASSOC);
	
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
            	<th colspan="15" align="left"><h2>LAPORAN PENERIMAAN BARANG ( <?php echo $row0['m_nama'] ; ?> ) </h2></th>
            </tr>
        	<tr>
            	<th colspan="15" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th colspan="15"></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="200" style="border:1px solid #000">Report Penjualan</th>
            	<th width="50"  style="border:1px solid #000">Qty</th>
                <th width="100" style="border:1px solid #000">Gross-W</th>
                <th width="100" style="border:1px solid #000">Butir</th>
                <th width="100" style="border:1px solid #000">Carat</th>
                <th width="100" style="border:1px solid #000">Harga Supplier</th>
                <th width="100" style="border:1px solid #000">Harga </th>
            </tr>
        </thead>
        <tbody>
            <?php
			$tqty1 = 0 ;
			$tberatg = 0 ;
			$tbutir = 0 ;
			$tcarat = 0 ;
			$thargasup = 0 ;
			$tharga = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$tqty1 		= $tqty1 + $row['vf_qty'] ;
				$tberatg 	= $tberatg + $row['vf_beratg'] ;
				$tbutir 	= $tbutir + $row['vf_butir'] ;
				$tcarat 	= $tcarat + $row['vf_carat'] ;
				$thargasup	= $thargasup + $row['vf_hargasup'];
				$tharga 	= $tharga + $row['vf_harga'];
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['vf_nama']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_beratg'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_hargasup'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_harga'], 0, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center">Total</td>
                <td style="border:1px solid #000" align="center"><?php echo number_format($tqty1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tberatg, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($thargasup, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tharga, 0, '.', ','); ?></td>
            </tr>
        </tfoot>

	</table>
