<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php" ;
	$kdstore = $_GET['cb'];
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl,  d.m_nama as designer, convert(varchar(10),a.m_tanggal_jatuh_tempo,103) as co_tgl_jt, convert(varchar(10),a.m_tanggal_approval,103) as co_tgl_approv
			from t_spk a , msdesigner d
	where   a.m_designer = d.m_kode and
			a.m_nomor = '".$nomor."'  ";
	
	$day = date('D');
	
	echo $day;
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
?>
<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Nomor</td>
            <td width="40%"><b><?php echo $row['m_nomor']; ?></b></td>
            <td width="10%">Tanggal</td>
            <td width="30%"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td width="15%">Keterangan</td>
            <td width="40%"><b><?php echo $row['m_keterangan']; ?></b></td>
            <td width="10%">Tanggal Jatuh Tempo</td>
            <td width="30%"><?php echo $row['co_tgl_jt']; ?></td>
        </tr>
        <tr>
            <td>Status Order</td>
            <td colspan="3"><?php echo $row['m_status_order']; ?></td>
        </tr>
        <tr>
            <td>Designer</td>
            <td><?php echo $row['designer']; ?></td>
            <td width="10%">Tukang Rakit</td>
            <td width="30%"><?php echo $row['m_tukang']; ?></td>
        </tr>
        <tr>
            <td>Type SPK</td>
            <td colspan="3"><?php echo $row['m_type']; ?></td>
        </tr>
        <tr>
            <td>Designer</td>
            <td colspan="3"><?php echo $row['designer']; ?></td>
        </tr>
        <tr>
            <td width="15%">Approval</td>
            <td width="40%"><b><?php echo $row['m_approval']; ?></b></td>
            <td width="10%">Tanggal Jatuh Tempo</td>
            <td width="30%"><?php echo $row['co_tgl_approv']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>Kode Karet</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Berat</th>
            <th>Butir</th>
            <th>Carat</th>
            <th>Harga Jual</th>
            <th>Harga Barcode</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tqty = 0 ;
            $tbutir = 0 ;
            $tcarat = 0 ;
            $thargam = 0 ;
            $thargabeli = 0 ;
            $thargar = 0 ;
            $thargajual = 0 ;
            $tsql2 = "	select 	a.*, a.m_item, a.m_totbutir, a.m_totcarat 
                        from 	t_spk2 a
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' " ;
			//echo $tsql2;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
			$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ;
           
			$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
			$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
			$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
			
			$tqty = $tqty + $row2['m_qty'] ;
			$tgross = $tgross + $row2['m_grossweight'] ;
			$tongkos = $tongkos + $row2['m_totbutir'] ;
			$tbutir = $tbutir + $row2['m_totbutir'] ;
			$tcarat = $tcarat + $row2['m_totcarat'] ;
			$thargam = $thargam + $row2['m_hargam'] ;
			$thargajual = $thargajual + $row2['m_hargajual'] ;
			?>
			<tr <?php if($row2['m_status']=='T'){ ?> style="color:#F00" <?php } ?>>
				<td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_rubberid']; ?></td>
				<td><?php echo $rowitem['m_nama']; ?></td>
				<td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_totbutir'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_totcarat'], 3, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargajual'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargabarcode'], 0, '.', ','); ?></td>
			</tr>
			
    </tbody>
</table>
    <br />
<table class="table table-bordered table-condensed">
	<tbody>
    	<tr style="font-weight:bold;font-size:16px">
        	<td> Harga Jual </td>
			<td><?php echo $row2['m_hargajual'] ; ?></td>
        	<td> Harga Barcode </td>
			<td><?php echo $row2['m_hargabarcode'] ; ?></td>
        </tr>
    </tbody>
</table>
    <br />
<table class="table table-bordered table-striped table-hover table-condensed">
	<thead>
        <tr>
            <th>Shape</th>
            <th>Ukuran</th>
            <th>Butir</th>
            <th>Carat</th>
        </tr>
    </thead>    
	<tbody>
    	<?php
            $tsql3 = "	select 	a.*, b.m_ukuran
                        from 	t_spk3 a, msstone b
                        where 	a.m_nomor = '".$nomor."' and a.m_shape = b.m_shape and a.m_size = b.m_size " ;
            //echo $tsql3;
            $stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
            while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
            {
                
                ?>
                <tr>
                    <td><?php echo $row3['m_shape']; ?></td>
                    <td><?php echo $row3['m_ukuran']; ?></td>
                    <td><?php echo number_format($row3['m_butir'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_carat'], 3, '.', ','); ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="13">
                <div>
                <?php
				if($row['m_status'] != 'B')
				{
					?>
                    <div class="pull-left" >
						<?php
                        if(( substr($xparam[3],1,1) == 'Y' ))
                        {
                            ?>
                        	<button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Edit</button>
                        	<?php
						}
                        if(( substr($xparam[3],3,1) == 'Y' ))
						{
							?>
                        	<button class="btn btn-warning" onclick="print_data('<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Print</button>
                        	<?php
						}
                        if(( substr($xparam[3],3,1) == 'Y' ))
						{
							?>
                        	<button class="btn btn-secondary" onclick="approve_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Approve</button>
                        	<?php
						}
						?>
                    </div>
                    <div class="pull-right" >
						<?php
                        if(( substr($xparam[3],2,1) == 'Y' ))
                        {
                            ?>
	                        <button class="btn btn-danger" onclick="hapus_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal Receive</button>
                            <?php
						}
						?>
                    </div>  
                  <?php  
				}
                    ?>
                </div>
            </th>
        </tr>
    </tfoot>
</table>        

