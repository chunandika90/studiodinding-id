<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	$kdstore = $_GET['kdlok'];
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
		
	$tsql = "	select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tglconfirm,103) as co_tglconfirm , b.m_nama as nmbrg
				from t_transfer a, msbarang b
				where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' and a.m_kodebarang = b.m_kode" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
		
?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th colspan="4"><h4><?php echo $kdstore.' - '.$nomor ; ?></h4></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="100">Nomor</td>
            <td width="150"><?php echo $row['m_nomor']; ?></td>
            <td width="75">Tanggal</td>
            <td width="150"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td colspan="3"><?php echo $row['m_nama']; ?></td>
        </tr>
        <tr>
            <td>From</td>
            <td><?php echo $row['m_lokasi']; ?></td>
            <td>To</td>
            <td><?php echo $row['m_lokasi2']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Kurir</td>
            <td><?php echo $row['m_kurir']; ?></td>
            <td>Barang Retur</td>
            <td><?php echo $row['nmbrg']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>No.</th>
            <th>LM</th>
            <th>Product ID</th>
            <th width="12%">Qty</th>
            <th width="12%">Berat/pcs</th>
            <th width="12%">T.Berat</th>
            <?php
			if ($row['m_confirm'] != '')
			{
				?>
	             <th>Keterangan</th>
               <?php
			}
			?>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tharga = 0 ;
            $tqty = 0 ;
            $tberat = 0 ;
            $tsql2 = "	select 	a.*, b.m_harga, b.m_item, c.m_nama as co_namabarang, d.m_nama as co_namaitem, d.m_kode2
                        from 	t_transfer2 a, t_stockdata b, msbarang c, msmaster d
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
                                a.m_kodebarang = b.m_kodebarang and 
                                a.m_productid = b.m_productid and
                                a.m_kodebarang = c.m_kode and 
								d.m_type = 'ITEM' and 
								d.m_kode = b.m_item " ;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
            while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
            {
				$dumb = explode('-',$row2['m_kode2']);
				$i = $i + 1 ;
                $tqty = $tqty + $row2['m_qty'] ;
                $tberat = $tberat + ($dumb[1] * $row2['m_qty']) ;
                ?>
                <tr <?php if($row2['m_status']=='T'){ ?> style="color:#F00" <?php } ?>>
                    <td><?php echo number_format($i, 0, '.', ','); ?></td>
                    <td><?php echo $row2['co_namaitem']; ?></td>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($dumb[1], 2, '.', ','); ?></td>
                    <td><?php echo number_format($row2['m_qty'] * $dumb[1], 2, '.', ','); ?></td>
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
            <th colspan="3"></th>
            <th><?php echo number_format($tqty, 0, '.', ',') ; ?></th>
            <th></th>
            <th><?php echo number_format($tberat, 2, '.', ',') ; ?></th>
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
            <th colspan="6">
                <div>
                <?php 	
                    if($row['m_confirm']!='')
                    {
                        echo 'Confirm by : '.$row['m_confirm'].',  Tanggal : '.$row['co_tglconfirm'];
                    } 
                    else
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
                            <button class="btn btn-danger" onclick="hapus_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal Transfer</button>
                            <?php
                        }
                        ?>
                        </div>  
                        <?php
                    }
                ?> 
                </div>
            </th>
			<?php
            if ($row['m_confirm'] != '')
            {
                ?>
                 <th></th>
               <?php
            }
            ?>
        </tr>
    </tfoot>
</table>        
