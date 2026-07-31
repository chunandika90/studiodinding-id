<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = $_GET['kdcabang'];
	$kdshape = $_GET['kdshape'];
	$kdsize = $_GET['kdsize'];
	$dimensi = $_GET['dimensi'];
	$kdby = $_GET['by'];
	
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = 'ALL';}
	if ($kdcabang2 ==''){$kdcabang2 = 'ALL';}
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}
	if ($tukang ==''){$tukang = 'ALL';}

	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdby ==''){$kdby = 'm_cabang';}

	include "mssql-dbnew.php" ;
	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
	
	$tsql = "exec dbo.sp_transfersb_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdcabang2."','".$kdshape."','".$kdsize."','".$dimensi."','".$tukang."','".$kdby."' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
?>

    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr data-level="header" class="header">
                <th width="20%"><div align="center">Report Setting Stone - <?php echo $kdcabang ; ?></div></th>
                <th width="10%"><div align="right">Butir</div></th>
                <th width="10%"><div align="right">Carat</div></th>
            </tr>
        </thead>
        <tbody>
        <?php
			
		$tbutir = 0;
		$tcarat = 0;
		
        $i = 0 ;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
        {	
            $i = $i + 1 ;

			$tbutir = $tbutir + $row['vf_butir'];
			$tcarat = $tcarat + $row['vf_carat'];

            ?>
            <tr height="25px">
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_nama']; ?></div></td>
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
            <th><div align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></div></th>
            <th><div align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
        </tr>
        </tfoot>
    </table>
