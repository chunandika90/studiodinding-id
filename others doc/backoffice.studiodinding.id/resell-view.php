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
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_canceldate,103) as co_tglbatal, b.m_alamat, b.m_kota, b.m_telepon1 from t_resell a, mscustomer b where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' and a.m_kodecust = b.m_kode " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);

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
            <td>Customer</td>
            <td colspan="3"><?php echo '( '.$row['m_kodecust'].' ) '.$row['m_nama']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>JR-1</td>
            <td colspan="3"><?php echo $row['m_kodesales'].' - '.$rowjr['m_nama']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="10%">Product ID</th>
            <th width="5%">Qty</th>
            <th width="12%">Group</th>
            <th width="13%">Item</th>
            <th width="10%"><div align="right">Buyback</div></th>
            <th width="5%" style="color:#F00">Store</th>
            <th width="10%" style="color:#F00">No.Faktur</th>
            <th width="17%" style="color:#F00">Tanggal</th>
            <th width="10%" style="color:#F00"><div align="right">Total</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tqty = 0 ;
			$ttot = 0 ;
			$tbuy = 0 ;
            $tsqlbuy = "select 	a.*, convert(varchar(10),a.m_tanggal2,103) as co_tgl, convert(varchar(8),m_tanggal2,108) as co_jam, b.m_item, c.m_nama as co_namabarang
                        from 	t_resell2 a, t_stockdata b, msbarang c 
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
                                a.m_kodebarang = b.m_kodebarang and 
                                a.m_productid = b.m_productid and
                                a.m_kodebarang = c.m_kode " ;
            $stmtbuy = sqlsrv_query( $con_dbnew, $tsqlbuy);
            while( $rowbuy = sqlsrv_fetch_array( $stmtbuy, SQLSRV_FETCH_ASSOC))
            {
                $tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$rowbuy['m_item']."'";
                $stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
                $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
				
				$i = $i + 1 ;
                $tqty = $tqty + $rowbuy['m_qty'] ;
                $ttot = $ttot + ( $rowbuy['m_qty'] * $rowbuy['m_harga'] ) ;
                $tbuy = $tbuy + ( $rowbuy['m_qty'] * $rowbuy['m_harga2'] ) ;
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $rowbuy['m_kodebarang']; ?>','<?php echo $rowbuy['m_productid']; ?>')" style="cursor:pointer"><?php echo $rowbuy['m_productid']; ?></td>
                    <td><?php echo number_format($rowbuy['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo $rowbuy['co_namabarang']; ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><div align="right"><?php echo number_format($rowbuy['m_harga'], 0, '.', ','); ?></div></td>
                    <td style="color:#F00"><?php echo $rowbuy['m_cabang2']; ?></td>
                    <td style="color:#F00"><?php echo $rowbuy['m_nomor2']; ?></td>
                    <td style="color:#F00"><?php echo $rowbuy['co_tgl'].' '.$rowbuy['co_jam']; ?></td>
                    <td style="color:#F00"><div align="right"><?php echo number_format($rowbuy['m_harga2'], 0, '.', ','); ?></div></td>
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
            <th><div align="right"><?php echo number_format($ttot, 0, '.', ',') ; ?></div></th>
            <th colspan="3"></th>
            <th style="color:#F00"><div align="right"><?php echo number_format($tbuy, 0, '.', ',') ; ?></div></th>
        </tr>
        <tr>
            <th colspan="9">
			<div>            
                <div class="pull-left" >
                <?php
				if(( $totbayar <= 0 ) && ( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Edit</button>
                    <?php
				}
				if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-warning" onclick="print_resell('<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Print RESELL</button>
                    <?php
				}
				?>
                </div>
                <?php
				if(( $totbayar <= 0 ) && ( substr($xparam[3],2,1) == 'Y' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-danger" onclick="batal_resell('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal RESELL</button>
                    </div>  
                    <?php
				}
				?>
			</div>
            </th>
        </tr>        
    </tfoot>
</table>        
