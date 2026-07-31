<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['kdcabang'];
	$kdshape = $_GET['kdshape'];
	$kdsize = $_GET['kdsize'];
	$dimensi = $_GET['dimensi'];
	
	$kdby = $_GET['kdby'];
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];
	
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}

	
	if ( $detby == '01' ){ $kdgroup = $kd ;  }
	
	
	$tsql = "exec dbo.sp_stockstone_all '".$kdcabang."','".$kdshape."','".$kdsize."','".$dimensi."','".$kdby."' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	//echo $tsql;
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="16">
            	<div>
                	<div class="pull-left">
                    	<h4>Report Stock</h4>
                    </div>
                    <div class="pull-right">
					<?php
                    if (substr($xparam[3],3,1) == 'Y')
                    {
                        ?>
                        <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/>  
                    	<?php
					}
					?>
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Cabang</th>
            <th>Shape</th>
            <th>Size</th>
            <th>Ukuran</th>
            <th>Dimensi</th>
            <th>Dimensi2</th>
            <th>Dimensi3</th>
            <th><div align="center">Qty</div></th>
            <th><div align="center">Carat</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
			$tqty = 0;
			$tcarat = 0;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
				$i++ ;
				$tqty = $tqty + $row['vf_qty'];
				$tcarat = $tcarat + $row['vf_carat'];
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['vf_cabang']; ?></td>
                	<td><?php echo $row['vf_shape']; ?></td>
                	<td><?php echo $row['vf_size']; ?></td>
                	<td><?php echo $row['vf_ukuran']; ?></td>
                	<td><?php echo $row['vf_dimensi']; ?></td>
                	<td><?php echo $row['vf_dimensi2']; ?></td>
                	<td><?php echo $row['vf_dimensi3']; ?></td>
                	<td><div align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>
                <?php
            }
            ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="8"></th>
        <th><div align="center"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
    </tr>
    </tfoot>
</table>
