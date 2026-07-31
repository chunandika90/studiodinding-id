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
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}

	if ($kdby ==''){$kdby = 'm_group';}

	include "mssql-dbnew.php" ;

?>

    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed" width="40%">
        <thead>
            <tr data-level="header" class="header">
                <th width="10%"><div align="center">Cabang</div></th>
                <th width="10%"><div align="center">Shape</div></th>
                <th width="10%"><div align="center">Size</div></th>
                <th width="10%"><div align="center">Dimensi</div></th>
                <th width="10%"> <div align="right">Butir</div></th>
                <th width="10%"> <div align="right">Carat</div></th>
            </tr>
        </thead>
        <tbody>
        <?php
		$tsql = "exec dbo.sp_stockstone_all '".$kdcabang."','".$kdshape."','".$kdsize."','".$dimensi."','".$kdby."' ";
		//echo $tsql;
        $stmt = sqlsrv_query( $con_dbnew, $tsql);
		
        $i = 0 ;
		$tcarat = 0;
		
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
        {	
            $i = $i + 1 ;
			
			
			$tqty = $tqty + $row['vf_qty'];
			$tcarat = $tcarat + $row['vf_carat'];
            ?>
            <tr height="25px">
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_cabang']; ?></div></td>
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_shape']; ?></div></td>
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_size']; ?></div></td>
                <td><div align="left" style="cursor:pointer" onclick="oc_detail('<?php echo $kdby ; ?>','<?php echo $row['vf_kode']; ?>')"><?php echo $row['vf_dimensi']; ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                <td class="data"><div align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4"></th>
                <th><div align="right"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>  
                <th><div align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>  
            </tr>
        </tfoot>
    </table>
