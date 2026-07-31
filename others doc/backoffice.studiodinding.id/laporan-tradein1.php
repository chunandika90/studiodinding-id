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
	$kdplu = $_GET['pl'];
	$kdby = $_GET['by'];
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$stqlty = $_GET['ql'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdklas ==''){$kdklas = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($stqlty ==''){$stqlty = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
?>

<?php
    if (substr($xparam[3],3,1) == 'Y')
    {
        ?>
        <button class="btn" onClick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $stqlty; ?>')"><img src="images/printer.gif"/> </button>
        <button class="btn" onClick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $stqlty; ?>')"><img src="images/excel.gif"/> </button>
        <?php
    }
?>
    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th width="20%"><div align="center">Report Trade-In, Store - <?php echo $kdcabang ; ?></div></th>
                <th width="5%"><div align="right">Qty</div></th>
                <th width="10%"><div align="right">Buyback</div></th>
                <th width="10%"><div align="right">Harga Awal</div></th>
                <th width="5%"><div align="right">% Depr</div></th>
                <th width="5%"><div align="right">Gross</div></th>
                <th width="5%"><div align="right">Net</div></th>
                <th width="5%"><div align="right">Butir</div></th>
                <th width="5%"><div align="right">Carat</div></th>
                <th width="3%"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $tsql = "select * from dbo.f_laporantradein('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdklas."', '".$kdkatg."', '".$kditem."', '".$kdplu."', '".$kdby."', '".$stqlty."')" ;

        $stmt = sqlsrv_query( $con_dbnew, $tsql);

        $i = 0 ;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
        {	
            $i = $i + 1 ;
            ?>
            <tr height="25px">
                <td><div align="left" style="cursor:pointer" onClick="oc_detail('<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdklas; ?>', '<?php echo $kdkatg; ?>', '<?php echo $kditem; ?>', '<?php echo $kdplu; ?>', '<?php echo $kdby; ?>', '<?php echo $tgl1; ?>', '<?php echo $tgl2; ?>', '<?php echo $row['vf_kode']; ?>', '<?php echo $row['vf_nama']; ?>', '<?php echo $stqlty; ?>')"><?php echo $row['vf_nama']; ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_asal'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($depr, 2, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_gross'], 2, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_net'], 2, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>                    
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
