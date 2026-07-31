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
			c.m_nama as namabarang, a.m_status
			from t_ttb a , mssupplier b , msbarang c, msdesigner d
			where   a.m_supplier = b.m_kode and
					a.m_kodebarang = c.m_kode and
					a.m_designer = d.m_kode and
					a.m_cabang = '".$kdstore."' and 
					a.m_nomor = '".$nomor."'  ";
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	//echo $tsql;
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
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>Group</th>
            <th>Product ID</th>
            <th>Kode Barang</th>
            <th>Kode Supplier</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Berat</th>
            <th>Tot. Butir</th>
            <th>Tot. Carat</th>
            <th>Harga R</th>
            <th>Harga Jual</th>
            <th>Harga Barcode</th>
            <th>Edit Spec</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tqty = 0 ;
            $tgross = 0 ;
            $tbutir = 0 ;
            $tcarat = 0 ;
            $thargar = 0 ;
            $thargam = 0 ;
            $thargar = 0 ;
            $thargajual = 0 ;
            $tsql2 = "	select 	a.*, c.m_nama as co_namabarang
                        from 	t_ttb2 a, msbarang c 
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
                                a.m_kodebarang = c.m_kode " ;
			//echo $tsql2;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
            while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
            {
                $tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
                $stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
                $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
                
                $tqty = $tqty + $row2['m_qty'] ;
                $tgross = $tgross + $row2['m_grossweight'] ;
                $tbutir = $tbutir + $row2['m_totbutir'] ;
                $tcarat = $tcarat + $row2['m_totcarat'] ;
                $thargar = $thargar + $row2['m_hargar'] ;
                $thargam = $thargam + $row2['m_hargam'] ;
                $thargajual = $thargajual + $row2['m_hargajual'] ;
                ?>
                <tr <?php if($row2['m_status']=='T'){ ?> style="color:#F00" <?php } ?>>
                    <td><?php echo $row2['co_namabarang']; ?></td>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo $row2['m_rubberid']; ?></td>
                    <td><?php echo $row2['m_kodesupplier']; ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_totbutir'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_totcarat'], 3, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_hargar'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_hargajual'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_hargabarcode'], 0, '.', ','); ?></td>
                    <td><button class="btn btn-primary" onclick="edit_spec('<?php echo $prm; ?>','<?php echo $row['m_nomor']; ?>','<?php echo $row2['m_productid']; ?>')">Edit</button></td>
					<?php
                    if ($row['m_confirm'] != '')
                    {
                        ?>
                         <th><?php echo $row2['m_keterangan']; ?></th>
                       <?php
                    }
                    ?>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th colspan="4"></th>
            <th><?php echo number_format($tqty, 0, '.', ',') ; ?></th>
            <th><?php echo number_format($tgross, 2, '.', ',') ; ?></th>
            <th><?php echo number_format($tbutir, 0, '.', ',') ; ?></th>
            <th><?php echo number_format($tcarat, 3, '.', ',') ; ?></th>
            <th><?php echo number_format($thargar, 0, '.', ',') ; ?></th>
            <th><?php echo number_format($thargajual, 0, '.', ',') ; ?></th>
			<th></th>
        </tr>
        <tr>
            <th colspan="13">
                <div>
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
                </div>
            </th>
        </tr>
    </tfoot>
</table>        

