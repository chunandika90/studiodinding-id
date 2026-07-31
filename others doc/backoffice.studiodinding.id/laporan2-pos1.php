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
	
?>

	<?php
    if (substr($xparam[3],3,1) == 'Y')
    {
        ?>
        <button class="btn" onClick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kddist; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $stqlty; ?>','<?php echo $kdseg; ?>','<?php echo $kdbasic; ?>','<?php echo $kdsize; ?>', '<?php echo $kdsize2; ?>', '<?php echo $kdcert; ?>')"><img src="images/printer.gif"/> </button>
        <button class="btn" onClick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kddist; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $stqlty; ?>','<?php echo $kdseg; ?>','<?php echo $kdbasic; ?>','<?php echo $kdsize; ?>', '<?php echo $kdsize2; ?>', '<?php echo $kdcert; ?>')"><img src="images/excel.gif"/></button>
        <?php
    }
    ?>
    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr data-level="header" class="header">
                <th width="20%"><div align="center">Report Penjualan, Store - <?php echo $kdcabang ; ?></div></th>
                <th width="5%"><div align="right">Qty</div></th>
                <th width="8%"><div align="right">Disc.Reguler</div></th>
                <th width="8%"><div align="right">Disc.VIP</div></th>
                <th width="8%"><div align="right">Disc.Promo</div></th>
                <th width="8%"><div align="right">Pembulatan</div></th>
                <th width="10%"><div align="right">Total Disc</div></th>
                <th width="5%"><div align="right">Disc %</div></th>
                <th width="10%"><div align="right">Net Sales</div></th>
                <th width="10%"><div align="right">Net-W</div></th>
                <th width="10%"><div align="right">Gross-W</div></th>
                <th width="10%"><div align="right">Butir</div></th>
                <th width="10%"><div align="right">Carat</div></th>
            </tr>
        </thead>
        <tbody>
        <?php

		$ttot0 = 0 ;
		$ttot1 = 0 ;
		$ttot2 = 0 ;
		$ttot3 = 0 ;
		$ttot4 = 0 ;
		$ttot5 = 0 ;
		$ttot6 = 0 ;
		$ttot7 = 0 ;
		$ttot8 = 0 ;
		$ttot9 = 0 ;
		$ttot10 = 0 ;

        $i = 0 ;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
        {	
            $i = $i + 1 ;
            $totdisc = ($row['vf_disc1']+$row['vf_disc2']+$row['vf_disc3']+$row['vf_disc4']);
            $pctdisc = ( $totdisc / $row['vf_jumlah'] ) * 100 ;
            $netsales = $row['vf_jumlah'] - $totdisc ;

			$ttot0 = $ttot0 + $row['vf_jumlah'];
			$ttot1 = $ttot1 + $row['vf_qty'];
			$ttot2 = $ttot2 + $row['vf_disc1'];
			$ttot3 = $ttot3 + $row['vf_disc2'];
			$ttot4 = $ttot4 + $row['vf_disc3'];
			$ttot5 = $ttot5 + $row['vf_disc4'];
			$ttot6 = $ttot6 + $netsales;
			$ttot7 = $ttot7 + $row['vf_berat'];
			$ttot8 = $ttot8 + $row['vf_beratg'];
			$ttot9 = $ttot9 + $row['vf_butir'];
			$ttot10 = $ttot10 + $row['vf_carat'];

            ?>
            <tr height="25px">
                <td><div align="left" style="cursor:pointer" onClick="oc_detail('<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>', '<?php echo $kdkatg; ?>', '<?php echo $kditem; ?>', '<?php echo $kddist; ?>', '<?php echo $kdseg; ?>', '<?php echo $kdplu; ?>', '<?php echo $kdby; ?>', '<?php echo $tgl1; ?>', '<?php echo $tgl2; ?>', '<?php echo $row['vf_kode']; ?>', '<?php echo $row['vf_nama']; ?>', '<?php echo $stqlty; ?>', '<?php echo $kdbasic; ?>', '<?php echo $kdsize; ?>', '<?php echo $kdsize2; ?>', '<?php echo $kdcert; ?>')"><?php echo $row['vf_nama']; ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_disc1'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_disc2'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_disc3'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_disc4'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($totdisc, 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($pctdisc, 2, '.', ',').' %'; ?></div></td>
                <td><div align="right"><?php echo number_format($netsales, 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_berat'], 2, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_beratg'], 2, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></div></td>
                <td><div align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
        	<th></th>
            <th><div align="right"><?php echo number_format($ttot1, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot2, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot3, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot4, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot5, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot2+$ttot3+$ttot4+$ttot5, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format((($ttot2+$ttot3+$ttot4+$ttot5)/$ttot0)*100, 2, '.', ',').' %'; ?></div></th>
            <th><div align="right"><?php echo number_format($ttot6, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot7, 2, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot8, 2, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot9, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($ttot10, 3, '.', ','); ?></div></th>            
        </tr>
        </tfoot>
    </table>
