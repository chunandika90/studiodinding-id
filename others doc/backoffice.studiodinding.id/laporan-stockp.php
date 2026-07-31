<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kdklas = $_GET['ks'];
	$kdkatg = $_GET['kt'];
	$kditem = $_GET['it'];
	$kddist = $_GET['dst'];
	$kdseg = $_GET['sg'];
	$kdplu = $_GET['pl'];
	$stqlty = $_GET['ql'];

	$kdby = $_GET['by'];
	$ststock = $_GET['st'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($ststock ==''){$ststock = 'X';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}

	if ($kdby ==''){$kdby = 'm_group';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	$tsql = "select * from dbo.f_laporanstock('".$kdcabang."', '".$kdgroup."', '".$kdklas."', '".$kdkatg."', '".$kditem."', '".$kdplu."', '".$kdby."', '".$ststock."', '".$stqlty."', '".$kddist."', '".$kdseg."') order by vf_kode asc" ;
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
            	<th colspan="15" align="left"><h2>LAPORAN Stock ( <?php echo $kdcabang ; ?> ) </h2></th>
            </tr>
        	<tr>
            	<th colspan="15" align="left">Periode : <?php echo $tgl1.' s/d '.$tgl2; ?></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th colspan="15"></th>
            </tr>
        	<tr height="25" style="border:1px solid #000;background-color:#CCC">
            	<th width="200" style="border:1px solid #000">Report Stock</th>
            	<th width="50"  style="border:1px solid #000">Qty</th>
                <th width="100" style="border:1px solid #000">In Transf.</th>
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
				
				$tqty1 = $tqty1 + $row['vf_qty'] ;
				$totw = $totw + $row['vf_otw'] ;
				$ttotal = $ttotal + $row['vf_total'] ;
				$thargam = $thargam + $row['vf_hargam'] ;
				$thargar = $thargar + $row['vf_hargar'] ;
				$tnet = $tnet + $row['vf_net'] ;
				$tgross = $tgross + $row['vf_gross'] ;
				$tbutir = $tbutir + $row['vf_butir'] ;
				$tcarat = $tcarat + $row['vf_carat'] ;
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['vf_nama']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_otw'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_hargam'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_hargar'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_net'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_gross'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
        	<tr height="25" style="border:1px solid #000;font-weight:bold">
                <td style="border:1px solid #000" align="center">Total</td>
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
