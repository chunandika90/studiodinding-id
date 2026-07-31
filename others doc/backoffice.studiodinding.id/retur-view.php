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
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_retur a where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."'" ;
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
            <td colspan="3"><?php echo '( '.$row['m_kodesupl'].' ) '.$row['m_nama']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Dok.ID</td>
            <td colspan="3"><?php echo $row['m_dokumen']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Qty</th>
            <th>Group</th>
            <th>Item</th>
            <th>Net</th>
            <th>Butir</th>
            <th>Carat</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tqty = 0 ;
            $tnet = 0 ;
            $tbutir = 0 ;
            $tcarat = 0 ;
            $tsql2 = "	select 	a.*, b.m_item, b.m_netweight, b.m_butir, b.m_carat , c.m_nama as co_namabarang
                        from 	t_retur2 a, t_stockdata b, msbarang c 
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
                                a.m_kodebarang = b.m_kodebarang and 
                                a.m_productid = b.m_productid and
                                a.m_kodebarang = c.m_kode " ;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
            while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
            {
                $tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
                $stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
                $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
                
                $tqty = $tqty + $row2['m_qty'] ;
                $tnet = $tnet + $row2['m_netweight'] ;
                $tbutir = $tbutir + $row2['m_butir'] ;
                $tcarat = $tcarat + $row2['m_carat'] ;
                ?>
                <tr <?php if($row2['m_status']=='T'){ ?> style="color:#F00" <?php } ?>>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo $row2['co_namabarang']; ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><?php echo number_format($row2['m_netweight'], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_butir'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_carat'], 3, '.', ','); ?></td>
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
            <th><?php echo number_format($tqty, 0, '.', ',') ; ?></th>
            <th colspan="2"></th>
            <th><?php echo number_format($tnet, 2, '.', ',') ; ?></th>
            <th><?php echo number_format($tbutir, 0, '.', ',') ; ?></th>
            <th><?php echo number_format($tcarat, 3, '.', ',') ; ?></th>
			<?php
            if ($row['m_confirm'] != '')
            {
                ?>
                 <th></th>
               <?php
            }
            ?>
        </tr>
        <tr>
            <th colspan="7">
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
	                        <button class="btn btn-danger" onclick="hapus_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal Retur</button>
                            <?php
						}
						?>
                    </div>  
                </div>
            </th>
        </tr>
    </tfoot>
</table>        

