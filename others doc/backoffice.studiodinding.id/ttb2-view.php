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
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl,b.m_nama as namasupplier,  d.m_nama as designer,
			c.m_nama as namabarang, a.m_status , e.m_jenisbarang
			from t_ttb a , mssupplier b , msbarang c, msdesigner d, t_ttb2 e
			where   a.m_supplier = b.m_kode and
					a.m_kodebarang = c.m_kode and
					a.m_designer = d.m_kode and
					a.m_nomor = e.m_nomor and
					a.m_cabang = '".$kdstore."' and 
					a.m_nomor = '".$nomor."'  ";
					//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
?>
<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Nomor</td>
            <td width="40%"><b><?php echo $row['m_cabang'].'-'.$row['m_nomor']; ?></b></td>
            <td width="10%">Tanggal</td>
            <td width="30%"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td colspan="3"><?php echo '( '.$row['m_supplier'].' ) '.$row['namasupplier']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>NO SJ SUPPLIER</td>
            <td colspan="3"><?php echo $row['m_dosupplier']; ?></td>
        </tr>
        <tr>
            <td>Jenis Barang</td>
            <td colspan="3"><?php echo $row['namabarang']; ?></td>
        </tr>
        <tr>
            <td>Designer</td>
            <td colspan="3"><?php echo $row['designer']; ?></td>
        </tr>
        <tr>
            <td>Jenis Barang</td>
            <td colspan="3"><?php echo $row['m_jenisbarang']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Kode Barang</th>
            <th>Kode Supplier</th>
            <th>Item</th>
            <th>Konstruksi</th>
            <th>Segmen</th>
            <th>Qty</th>
            <th>Berat</th>
            <th>Butir</th>
            <th>Carat</th>
            <th>T.Ongkos</th>
            <th>Harga M</th>
            <th>Harga R</th>
            <th>Harga Jual</th>
            <th>Harga Barcode</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tqty = 0 ;
            $tgross = 0 ;
            $tbutir = 0 ;
            $tcarat = 0 ;
            $thargam = 0 ;
            $thargabeli = 0 ;
            $thargar = 0 ;
            $thargajual = 0 ;
            $tsql2 = "	select 	a.*, b.m_item, b.m_netweight, b.m_totbutir, b.m_totcarat , c.m_nama as co_namabarang, d.m_nama m_segmen
                        from 	t_ttb2 a, t_stockdata b, msbarang c , mssegmen_in d
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
                                a.m_kodebarang = b.m_kodebarang and 
                                a.m_productid = b.m_productid and
                                a.m_kodebarang = c.m_kode and
								a.m_segmen = d.m_kode" ;
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
				<td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
				<td><?php echo $row2['m_rubberid']; ?></td>
				<td><?php echo $row2['m_kodesupplier']; ?></td>
				<td><?php echo $rowitem['m_nama']; ?></td>
				<td><?php echo $row2['m_konstruksi']; ?></td>
				<td><?php echo $row2['m_segmen']; ?></td>
				<td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_totbutir'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_totcarat'], 3, '.', ','); ?></td>
				<td><?php echo number_format(($row2['m_orangkam']+$row2['m_opbm']+$row2['m_olainm']), 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargam'], 2, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargar'], 2, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargajual'], 0, '.', ','); ?></td>
				<td><?php echo number_format($row2['m_hargabarcode'], 0, '.', ','); ?></td>
			</tr>
			
    </tbody>
</table>
    <br />
<table class="table table-bordered table-condensed">
	<tbody>
    	<tr style="font-weight:bold">
        	<td> Total Emas M</td>
			<td><?php echo $row2['m_emasm']; ?></td>
        	<td> Total Emas R</td>
			<td><?php echo $row2['m_emasr']; ?></td>
        </tr>
    	<tr>
        	<td> Total Ongkos Rangka M</td>
			<td><?php echo $row2['m_orangkam']; ?></td>
        	<td> Total Ongkos Rangka R</td>
			<td><?php echo $row2['m_orangkar']; ?></td>
        </tr>
    	<tr>
        	<td> Total Ongkos Poles M</td>
			<td><?php echo $row2['m_opolesm']; ?></td>
        	<td> Total Ongkos Poles R</td>
			<td><?php echo $row2['m_opolesr']; ?></td>
        </tr>
    	<tr>
        	<td> Total Ongkos Lain M</td>
			<td><?php echo $row2['m_olainm']; ?></td>
        	<td> Total Ongkos Lain R</td>
			<td><?php echo $row2['m_olainr']; ?></td>
        </tr>
    	<tr>
        	<td> Total Ongkos PB M</td>
			<td><?php echo $row2['m_opbm']; ?></td>
        	<td> Total Ongkos PB R</td>
			<td><?php echo $row2['m_opbr']; ?></td>
        </tr>
    	<tr style="font-weight:bold">
        	<td> Total Ongkos M</td>
			<td><?php echo $row2['m_orangkam'] + $row2['m_olainm']+ $row2['m_opbm'] + $row2['m_opolesm']; ?></td>
        	<td> Total Ongkos R</td>
			<td><?php echo $row2['m_orangkar'] + $row2['m_olainr']+ $row2['m_opbr'] + $row2['m_opolesr']; ?></td>
        </tr>
    	<tr style="font-weight:bold">
        	<td> Total M Batu</td>
			<td><?php echo $row2['m_stonem']; ?></td>
        	<td> Total R Batu</td>
			<td><?php echo $row2['m_stoner']; ?></td>
        </tr>
    	<tr style="font-weight:bold;font-size:14px">
        	<td> Total M </td>
			<td><?php echo $row2['m_hargam']; ?></td>
        	<td> Total R </td>
			<td><?php echo $row2['m_hargar']; ?></td>
        </tr>
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
            <th>Product ID</th>
            <th>Shape</th>
            <th>Ukuran</th>
            <th>Butir</th>
            <th>Carat</th>
            <th>Harga M</th>
            <th>Harga R</th>
            <th>Harga Pb M</th>
            <th>Harga Pb R</th>
        </tr>
    </thead>    
	<tbody>
    	<?php
            $tsql3 = "	select 	a.*, b.m_ukuran
                        from 	t_ttb3 a, msstone b
                        where 	a.m_nomor = '".$nomor."' and a.m_shape = b.m_shape and a.m_size = b.m_size " ;
            //echo $tsql3;
            $stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
            while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
            {
                
                ?>
                <tr>
                    <td><?php echo $row3['m_productid']; ?></td>
                    <td><?php echo $row3['m_shape']; ?></td>
                    <td><?php echo $row3['m_ukuran']; ?></td>
                    <td><?php echo number_format($row3['m_butir'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_carat'], 3, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_hargam'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_hargar'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_opbm'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_opbr'], 2, '.', ','); ?></td>
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
                  <?php  }
                    ?>
                </div>
            </th>
        </tr>
    </tfoot>
</table>        

