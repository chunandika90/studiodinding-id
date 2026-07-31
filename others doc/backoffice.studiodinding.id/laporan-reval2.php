<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$kdcabang = $_GET['kdcabang'];
	$kdgroup = $_GET['kdgroup'];
	$kditem = $_GET['kditem'];
	$kdplu = $_GET['kdplu'];
	$rubberid = $_GET['rubberid'];
	$kodesupplier = $_GET['kodesupplier'];
	$kdsupplier = $_GET['kdsupplier'];
	$kdtype = $_GET['kdtype'];
	
	$kdby = $_GET['kdby'];
	$detby = $_GET['detby'];
	$kd = $_GET['kd'];
	
	$tgl1 = $_GET['tg1'];
	$tgl2 = $_GET['tg2'];
	
	
	
	if ($kdcabang == ''){$kdcabang = 'ALL' ;}
	if ($kdgroup == ''){$kdgroup = 'ALL' ;}
	if ($kditem == ''){$kditem = 'ALL' ;}
	if ($kdplu == ''){$kdplu = 'ALL' ;}
	if ($rubberid ==''){$rubberid = 'ALL';}
	if ($kodesupplier ==''){$kodesupplier = 'ALL';}
	if ($kdsupplier == ''){$kdsupplier = 'ALL' ;}
	if ($kddesigner == ''){$kddesigner = 'ALL' ;}
	if ($kdtype ==''){$kdtype = 'ALL';}
	if ($kdkonstruksi ==''){$kdkonstruksi = 'ALL';}

	
	if ( $detby == '01' ){ $kdcabang = $kd ;  }
	else if ( $detby == '02' ){ $kdgroup = $kd ;  }
	else if ( $detby == '03' ){ $kditem = $kd ;  }
	else if ( $detby == '04' ){ $kdsupplier = $kd ;  }
	else if ( $detby == '05' ){ $kddesigner = $kd ;  }
	else if ( $detby == '06' ){ $kdtype = $kd ;  }
	else if ( $detby == '07' ){ $kdkonstruksi = $kd ;  }
	

	$abc = explode('/',$tgl1);
	$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	$abc = explode('/',$tgl2);
	$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';

	
	$tsql = "exec dbo.sp_reval_all '".$tanggal1."','".$tanggal2."','".$kdcabang."','".$kdgroup."','".$kditem."','".$kdplu."','".$rubberid."','".$kodesupplier."','".$kdsupplier."','".$kddesigner."','".$kdtype."','".$kdkonstruksi."','".$kdby."' ";
	//echo $tsql;	
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
            <th>Group</th>
            <th>Nomor</th>
            <th>Supplier</th>
            <th>Tanggal</th>
            <th>Product ID</th>
            <th>Item</th>
            <th>Kode Barang</th>
            <th>Kode Supplier</th>
            <th>Designer</th>
            <th><div align="center">Qty</div></th>
            <th><div align="center">Grossweight</div></th>
            <th><div align="center">Butir</div></th>
            <th><div align="center">Carat</div></th>
            <th><div align="center">Harga M</div></th>
            <th><div align="center">Harga R </div></th>
            <th><div align="center">Harga Jual </div></th>
            <th><div align="center">Harga Barcode </div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
			
			$tqty = 0;
			$tgross = 0;
			$tbutir = 0;
			$tcarat = 0;
			$thargam = 0;
			$thargam = 0;
			$thargar = 0;
			$thargajual = 0;
			$thargabarcode = 0;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
					
				$tqty = $tqty + $row['vf_qty'];
				$tgross = $tgross + $row['vf_grossweight'];
				$tbutir = $tbutir + $row['vf_totbutir'];
				$tcarat = $tcarat + $row['vf_totcarat'];
				$thargam = $thargam + $row['vf_hargam'];
				$thargar = $thargar + $row['vf_hargar'];
				$thargajual = $thargajual + $row['vf_hargajual'];
				$thargabarcode = $thargabarcode + $row['vf_hargabarcode'];
				
				$i++ ;
                ?>
				<tr>
                	<td><?php echo $i; ?></td>
                	<td><?php echo $row['vf_cabang']; ?></td>
                	<td><?php echo $row['vf_group']; ?></td>
                	<td><?php echo $row['vf_nomor']; ?></td>
                	<td><?php echo $row['vf_supplier']; ?></td>
                	<td><?php echo $row['vf_tanggal']; ?></td>
                	<td><?php echo $row['vf_productid']; ?></td>
                	<td><?php echo $row['vf_item']; ?></td>
                	<td><?php echo $row['vf_rubberid']; ?></td>
                	<td><?php echo $row['vf_kodesupplier']; ?></td>
                	<td><?php echo $row['vf_designer']; ?></td>
                	<td><div align="center"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_grossweight'], 2, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_totbutir'], 0, '.', ','); ?></div></td>
                	<td><div align="center"><?php echo number_format($row['vf_totcarat'], 3, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['vf_hargam'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['vf_hargar'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['vf_hargajual'], 0, '.', ','); ?></div></td>
                	<td><div align="right"><?php echo number_format($row['vf_hargabarcode'], 0, '.', ','); ?></div></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="11"></th>
        <th><div align="center"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tgross, 2, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tbutir, 0, '.', ','); ?></div></th>
        <th><div align="center"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
        <th><div align="right"><?php echo number_format($thargam, 0, '.', ','); ?></div></th>  
        <th><div align="right"><?php echo number_format($thargar, 0, '.', ','); ?></div></th>  
        <th><div align="right"><?php echo number_format($thargajual, 0, '.', ','); ?></div></th>     
        <th><div align="right"><?php echo number_format($thargabarcode, 0, '.', ','); ?></div></th>     
    </tr>
    </tfoot>
</table>
