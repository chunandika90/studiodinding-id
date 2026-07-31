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
	$kdsize = str_replace(",","",$_GET['sz']);	
	$kdsize2 = str_replace(",","",$_GET['sz2']);	
	$kdcert = $_GET['crt'];

	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$stqlty = $_GET['ql'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	$kdbasic = $_GET['bsc'];

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kddist ==''){$kddist = 'ALL';}
	if ($kdseg ==''){$kdseg = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}
	if ($kdbasic ==''){$kdbasic = 'ALL';}
	if ($kdcert ==''){$kdcert = 'ALL';}
	if ($kdsize == ''){$kdsize = 0 ;}
	if ($kdsize2 == ''){$kdsize2 = 0 ;}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	$tsql = "select * from dbo.f_laporanpos('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdklas."', '".$kdkatg."', '".$kditem."', '".$kdplu."', '".$kdby."', '".$stqlty."', '".$kddist."', '".$kdseg."', '".$kdbasic."', ".$kdsize.", ".$kdsize2.", '".$kdcert."') order by vf_kode asc" ;
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
            	<th colspan="15" align="left"><h2>LAPORAN PENJUALAN ( <?php echo $kdcabang ; ?> ) </h2></th>
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
                <th width="100" style="border:1px solid #000">Disc.Reguler</th>
                <th width="100" style="border:1px solid #000">Disc.VIP</th>
                <th width="100" style="border:1px solid #000">Disc.Promo</th>
                <th width="100" style="border:1px solid #000">Pembulatan</th>
                <th width="100" style="border:1px solid #000">Total Disc</th>
                <th width="100" style="border:1px solid #000">Disc %</th>
                <th width="100" style="border:1px solid #000">Net Sales</th>
                <th width="100" style="border:1px solid #000">Net-W</th>
                <th width="100" style="border:1px solid #000">Gross-W</th>
                <th width="100" style="border:1px solid #000">Butir</th>
                <th width="100" style="border:1px solid #000">Carat</th>
            </tr>
        </thead>
        <tbody>
            <?php
			$tqty1 = 0 ;
			$tdisc1 = 0 ;
			$tdisc2 = 0 ;
			$tdisc3 = 0 ;
			$tdisc4 = 0 ;
			$ttotdisc = 0 ;
			$tpcdisc = 0 ;
			$tnetsales = 0 ;
			$tberat = 0 ;
			$tberatg = 0 ;
			$tbutir = 0 ;
			$tcarat = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$totdisc = ($row['vf_disc1']+$row['vf_disc2']+$row['vf_disc3']+$row['vf_disc4']);
				$pctdisc = ( $totdisc / $row['vf_jumlah'] ) * 100 ;
				$netsales = $row['vf_jumlah'] - $totdisc ;
				
				$tqty1 = $tqty1 + $row['vf_qty'] ;
				$tdisc1 = $tdisc1 + $row['vf_disc1'] ;
				$tdisc2 = $tdisc2 + $row['vf_disc2'] ;
				$tdisc3 = $tdisc3 + $row['vf_disc3'] ;
				$tdisc4 = $tdisc4 + $row['vf_disc4'] ;
				$ttotdisc = $ttotdisc + $totdisc;
				$tpcdisc = $tpcdisc + $pctdisc ;
				$tnetsales = $tnetsales + $netsales ;
				$tberat = $tberat + $row['vf_berat'] ;
				$tberatg = $tberatg + $row['vf_beratg'] ;
				$tbutir = $tbutir + $row['vf_butir'] ;
				$tcarat = $tcarat + $row['vf_carat'] ;
				
                ?>
                <tr height="25">
                    <td style="border-left:1px solid #000;border-right:1px solid #000"><?php echo $row['vf_nama']; ?></td>
                    <td style="border-right:1px solid #000" align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_disc1'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_disc2'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_disc3'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_disc4'], 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($pctdisc, 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($netsales, 0, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_berat'], 2, '.', ','); ?></td>
                    <td style="border-right:1px solid #000" align="right"><?php echo number_format($row['vf_beratg'], 2, '.', ','); ?></td>
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
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc1, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc2, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc3, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tdisc4, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($ttotdisc, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tpcdisc, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tnetsales, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tberat, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tberatg, 2, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></td>
                <td style="border:1px solid #000" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></td>
            </tr>
        </tfoot>

	</table>
