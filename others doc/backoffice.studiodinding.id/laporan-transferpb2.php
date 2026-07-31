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
	if ($kdcabang2 ==''){$kdcabang2 = 'ALL';}
	if ($kdshape ==''){$kdshape = 'ALL';}
	if ($kdsize ==''){$kdsize = 'ALL';}
	if ($dimensi ==''){$dimensi = 'ALL';}
	if ($tukang ==''){$tukang = 'ALL';}

	
	if ( $detby == '01' ){ $kdcabang2 = $kd ;  }
	else if ( $detby == '02' ){ $supplier = $kd ;  }
	else if ( $detby == '03' ){ $kdshape = $kd ;  }
	else if ( $detby == '04' ){ $kdsize = $kd ;  }
	else if ( $detby == '05' ){ $dimensi = $kd ;  }
	

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

		
	$tsql = "exec dbo.sp_transferpb_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdcabang2."','".$kdshape."','".$kdsize."','".$dimensi."','".$tukang."','".$kdby."' ";
	//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);


?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="19">
            	<div>
                	<div class="pull-left">
                    	<h4>Report Setting Batu </h4>
                    </div>
            	</div>
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Lokasi From</th>
            <th>To Lokasi</th>
            <th>Nomor SPK</th>
            <th>Nama Tukang</th>
            <th>Keterangan</th>
            <th>Shape</th>
            <th>Size</th>
            <th>Dimensi</th>
            <th>Dimensi 2</th>
            <th>Dimensi 3</th>
            <th>GIA</th>
            <th><div align="center">Butir</div></th>
            <th><div align="center">Carat</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
			
			$tbutir = 0;
			$tcarat = 0;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
					
				$tbutir = $tbutir + $row['vf_butir'];
				$tcarat = $tcarat + $row['vf_carat'];
				
				$i++ ;
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['vf_nomor']; ?></td>
                	<td><?php echo $row['vf_tanggal']; ?></td>
                	<td><?php echo $row['vf_lokasi']; ?></td>
                	<td><?php echo $row['vf_lokasi2']; ?></td>
                	<td><?php echo $row['vf_spk']; ?></td>
                	<td><?php echo $row['vf_tukang']; ?></td>
                	<td><?php echo $row['vf_keterangan']; ?></td>
                	<td><?php echo $row['vf_shape']; ?></td>
                	<td><?php echo $row['vf_size']; ?></td>
                	<td><?php echo $row['vf_dimensi']; ?></td>
                	<td><?php echo $row['vf_dimensi2']; ?></td>
                	<td><?php echo $row['vf_dimensi3']; ?></td>
                	<td><?php echo $row['vf_gia']; ?></td>
                	<td><div align="center"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="14"></th>
        <th><div align="center"><?php echo number_format($tbutir, 0, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tcarat, 2, '.', ','); ?></div></th>
    </tr>
    </tfoot>
</table>
