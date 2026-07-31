<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['cb'];
	$kdgroup = $_GET['gr'];
	$kditem = $_GET['it'];
	$kdplu = $_GET['kdplu'];
	$rubberid = $_GET['rubberid'];
	$kodesupplier = $_GET['kodesupplier'];
	$kdsupplier = $_GET['kdsupplier'];
	$kdby = $_GET['by'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdplu ==''){$kdplu = 'ALL';}
	if ($rubberid ==''){$rubberid = 'ALL';}
	if ($kodesupplier ==''){$kodesupplier = 'ALL';}
	if ($kdsupplier ==''){$kdsupplier = 'ALL';}
	if ($kddesigner ==''){$kddesigner = 'ALL';}

	if ($kdby ==''){$kdby = 'm_group';}

	include "mssql-dbnew.php" ;

?>

    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr data-level="header" class="header">
                <th width="20%"><div align="center">Report STOCK</div></th>
                <th width="5%"><div align="right">Qty</div></th>
                <th width="10%"><div align="right">Gross-W</div></th>
                <th width="10%"><div align="right">Butir</div></th>
                <th width="10%"><div align="right">Carat</div></th>
                <th width="10%"><div align="right">Harga M</div></th>
                <th width="10%"><div align="right">Harga R</div></th>
                <th width="10%"><div align="right">Harga Jual</div></th>
                <th width="10%"><div align="right">Harga Barcode</div></th>
            </tr>
        </thead>
        <tbody>
        <?php
		$tsql = "exec dbo.sp_stock_all '".$kdcabang."','".$kdgroup."','".$kditem."','".$kdplu."','".$rubberid."','".$kodesupplier."','".$kdsupplier."','".$kddesigner."','".$kdby."' ";
		echo $tsql;
        $stmt = sqlsrv_query( $con_dbnew, $tsql);
		
        $i = 0 ;
		$tqty = 0;
		$tgross = 0;
		$tbutir = 0;
		$tcarat = 0;
		$thargam = 0;
		$thargar = 0;
		$thargajual = 0;
		$thargabarcode = 0;
		
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
        {	
            $i = $i + 1 ;
			
			
			$tqty = $tqty + $row['vf_qty'];
			$tgross = $tgross + $row['vf_grossweight'];
			$tbutir = $tbutir + $row['vf_totbutir'];
			$tcarat = $tcarat + $row['vf_totcarat'];
			$thargam = $thargam + $row['vf_hargam'];
			$thargar = $thargar + $row['vf_hargar'];
			$thargajual = $thargajual + $row['vf_hargajual'];
			$thargabarcode = $thargabarcode + $row['vf_hargabarcode'];
            ?>
            <tr height="25px">
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_nama']; ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_grossweight'], 2, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_totbutir'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_totcarat'], 3, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_hargam'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_hargar'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_hargajual'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_hargabarcode'], 0, '.', ','); ?></div></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th><div align="right"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>
                <th><div align="right"><?php echo number_format($tgross, 2, '.', ','); ?></div></th>
                <th><div align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></div></th>
                <th><div align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
                <th><div align="right"><?php echo number_format($thargam, 0, '.', ','); ?></div></th>  
                <th><div align="right"><?php echo number_format($thargar, 0, '.', ','); ?></div></th>  
                <th><div align="right"><?php echo number_format($thargajual, 0, '.', ','); ?></div></th>    
                <th><div align="right"><?php echo number_format($thargabarcode, 0, '.', ','); ?></div></th>     
            </tr>
        </tfoot>
    </table>
