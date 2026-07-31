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
	
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	
	
	if ($kdcabang ==''){$kdcabang = 'ALL';}
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}
	if ($supplier ==''){$supplier = 'ALL';}

	
	if ( $detby == '01' ){ $kdcabang = $kd ;  }
	else if ( $detby == '02' ){ $supplier = $kd ;  }
	else if ( $detby == '03' ){ $kdshape = $kd ;  }
	else if ( $detby == '04' ){ $kdsize = $kd ;  }
	else if ( $detby == '05' ){ $dimensi = $kd ;  }
	

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	
	$tsql = "exec dbo.sp_ttb_stone_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdshape."','".$kdsize."','".$dimensi."','".$supplier."','".$kdby."' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	

?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="19">
            	<div>
                	<div class="pull-left">
                    	<h4>Report Penerimaan </h4>
                    </div>
                    <div class="pull-right">
                       <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>')">
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1c('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kditem; ?>','<?php echo $kdplu; ?>','<?php echo $kdby; ?>','<?php echo $vkode; ?>','<?php echo $vnama; ?>')">
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Cabang</th>
            <th>Nomor</th>
            <th>Supplier</th>
            <th>RATE</th>
            <th>Tanggal</th>
            <th>Shape</th>
            <th>Size</th>
            <th>Dimensi</th>
            <th>Dimensi 2</th>
            <th>Dimensi 3</th>
            <th>GIA</th>
            <th><div align="center">Carat</div></th>
            <th><div align="center">Jumlah</div></th>
            <th><div align="center">Total</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
			
			$tcarat = 0;
			$tjumlah = 0;
			$ttotal = 0;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
					
				$tcarat = $tcarat + $row['vf_carat'];
				$tjumlah = $tjumlah + $row['vf_jumlah'];
				$ttotal = $ttotal + $row['vf_total'];
				
				$i++ ;
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['vf_lokasi']; ?></td>
                	<td><?php echo $row['vf_nomor']; ?></td>
                	<td><?php echo $row['vf_supplier']; ?></td>
                	<td><?php echo $row['vf_rate']; ?></td>
                	<td><?php echo $row['vf_tanggal']; ?></td>
                	<td><?php echo $row['vf_shape']; ?></td>
                	<td><?php echo $row['vf_size']; ?></td>
                	<td><?php echo $row['vf_dimensi']; ?></td>
                	<td><?php echo $row['vf_dimensi2']; ?></td>
                	<td><?php echo $row['vf_dimensi3']; ?></td>
                	<td><?php echo $row['vf_gia']; ?></td>
                	<td><div align="center"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_jumlah'], 2, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_total'], 2, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="12"></th>
        <th><div align="center"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tjumlah, 2, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($ttotal, 2, '.', ','); ?></div></th>
    </tr>
    </tfoot>
</table>
