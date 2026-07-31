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
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_canceldate,103) as co_tglbatal, b.m_alamat, b.m_kota, b.m_telepon1 from t_pos a, mscustomer b where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' and a.m_kodecust = b.m_kode " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	// Cek total penjualan
	$tsqljual = "select isnull(sum(( m_qty * m_harga ) - m_discount - m_discount2 - m_discount3 - m_discount4),0) as cojual from t_pos2 where m_cabang = '".$kdstore."' and m_nomor = '".$nomor."'";
	$stmtjual = sqlsrv_query( $con_dbnew, $tsqljual);
	$rowjual = sqlsrv_fetch_array( $stmtjual, SQLSRV_FETCH_ASSOC) ;
	$totjual = $rowjual['cojual'];
	if ( $totjual == '' ){$totjual = 0 ;}
	
	// Cek total bayat
	$tsqlbyr = "select isnull(sum(m_jumlah),0) as cobayar from t_pos3 where m_cabang = '".$kdstore."' and m_nomor = '".$nomor."'";
	$stmtbyr = sqlsrv_query( $con_dbnew, $tsqlbyr);
	$rowbyr = sqlsrv_fetch_array( $stmtbyr, SQLSRV_FETCH_ASSOC) ;
	$totbayar = $rowbyr['cobayar'];
	if ( $totbayar == '' ){$totbayar = 0 ;}
	
	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);
	
	$tsqljr2 = "select m_nama from mssales where m_kode = '".$row['m_kodesales2']."'";
	$stmtjr2 = sqlsrv_query( $con_dbnew, $tsqljr2);
	$rowjr2 = sqlsrv_fetch_array( $stmtj2r, SQLSRV_FETCH_ASSOC);
	
//	echo $tsqlbyr ;
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
            <td><?php echo $row['m_kodesales'].' - '.$rowjr['m_nama']; ?></td>
            <td>JR-2</td>
            <td><?php echo $row['m_kodesales2'].' - '.$rowjr2['m_nama']; ?></td>
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
                        from 	t_tradein2 a, t_stockdata b, msbarang c 
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
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <div class="pull-left" >
                <?php
				if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-warning" onclick="print_exch('<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Print Trade-IN</button>
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


<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="10%">Product ID</th>
            <th width="5%">Qty</th>
            <th width="12%">Group</th>
            <th width="13%">Item</th>
            <th width="12%"><div align="right">Harga/@</div></th>
            <th width="10%"><div align="right">Dsc-1</div></th>
            <th width="10%"><div align="right">Dsc-2</div></th>
            <th width="10%"><div align="right">Promo</div></th>
            <th width="6%"><div align="right">+/-</div></th>
            <th width="12%"><div align="right">Total</div></th>
            <th width="5%"><div align="center">Prn</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tqty = 0 ;
			$ttot = 0 ;
            $tdisc1 = 0 ;
            $tdisc2 = 0 ;
            $tdisc3 = 0 ;
            $tdisc4 = 0 ;
            $tsql2 = "	select 	a.*, b.m_item, c.m_nama as co_namabarang
                        from 	t_pos2 a, t_stockdata b, msbarang c 
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
                
				$i = $i + 1 ;
                $tqty = $tqty + $row2['m_qty'] ;
                $ttot = $ttot + ( $row2['m_qty'] * $row2['m_harga'] ) ;
                $tdisc1 = $tdisc1 + $row2['m_discount'] ;
                $tdisc2 = $tdisc2 + $row2['m_discount2'] ;
                $tdisc3 = $tdisc3 + $row2['m_discount3'] ;
                $tdisc4 = $tdisc4 + $row2['m_discount4'] ;
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo $row2['co_namabarang']; ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><div align="right"><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_discount'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_discount2'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_discount3'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_discount4'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format(($row2['m_harga']*$row2['m_qty'])-$row2['m_discount']-$row2['m_discount2']-$row2['m_discount3']-$row2['m_discount4'], 0, '.', ','); ?></div></td>
                    <td><div align="center">
                    	<?php
                        if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
                        {
                            ?>
	                    	<img src="images/printer.gif" style="cursor:pointer" id="cetak<?php echo $i; ?>" onclick="print_data('<?php echo $kdstore; ?>','<?php echo $nomor; ?>','<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')"/></div>
	                    	<img src="images/certificate.gif" width="15" style="cursor:pointer" id="cert<?php echo $i; ?>" onclick="print_cert('<?php echo $kdstore; ?>','<?php echo $nomor; ?>','<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')"/></div>
                            <?php
                        }
                    	?>
					</td>
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
            <th><div align="right"><?php echo number_format($tdisc1, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($tdisc2, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($tdisc3, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($tdisc4, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($ttot - $tdisc1 - $tdisc2 - $tdisc3 - $tdisc4, 0, '.', ',') ; ?></div></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="11">
			<div>            
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <div class="pull-left" >
                <?php
				if(( $totbayar <= 0) && ( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Edit</button>
                    <?php
				}
				if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-warning" onclick="print_all('<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Print All</button>
                    <?php
				}
				?>
                </div>
                <?php
				if(( $totbayar <= 0 ) && ( substr($xparam[3],2,1) == 'Y' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Batal Faktur</button>
                    </div>  
                    <?php
				}
				?>
				<?php
			}
			?>
			</div>
            </th>
        </tr>
    </tfoot>
</table>        

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="8%">Cara Bayar</th>
            <th width="6%"><div align="right">Jumlah</div></th>
            <th width="6%"><div align="right">Lain-2</div></th>
            <th width="6%">EDC</th>
            <th width="6%">Bank</th>
            <th width="12%">No.Kartu</th>
            <th width="15%">Nama</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $tjumlah= 0 ;
			$tlain = 0 ;
            $tsql3 = "	select 	a.*, b.m_nama as co_cara
                        from 	t_pos3 a, msmaster b
                        where 	a.m_cabang = '".$kdstore."' and 
                                a.m_nomor = '".$nomor."' and 
								b.m_type = 'CARABAYAR' and 
								a.m_carabayar = b.m_kode " ;
            $stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
            while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
            {
				$bank = '' ;
				$edc = '' ;
				$ketkartu = '';
				if ($row3['m_edc'] != '')
				{
					$tsqledc = "select m_nama from msmaster where m_type = 'BANK' and m_kode = '".$row3['m_edc']."'";
            		$stmtedc = sqlsrv_query( $con_dbnew, $tsqledc);
					$rowedc = sqlsrv_fetch_array( $stmtedc, SQLSRV_FETCH_ASSOC);
					$edc = $rowedc['m_nama'] ;
				}

				if ($row3['m_bank'] != '')
				{
					$tsqlbank = "select m_nama from msmaster where m_type = 'BANK' and m_kode = '".$row3['m_bank']."'";
            		$stmtbank = sqlsrv_query( $con_dbnew, $tsqlbank);
					$rowbank = sqlsrv_fetch_array( $stmtbank, SQLSRV_FETCH_ASSOC);
					$bank = $rowbank['m_nama'] ;
				}
				
				if ($row3['m_jnkartu'] != '')
				{
					$ketkartu = ' ('.$row3['m_jnkartu'].') - '.$row3['m_cclkartu'];
				}
				
				
                $tjumlah = $tjumlah + $row3['m_jumlah'] ;
                $tlain = $tlain + $row3['m_mdr'] ;
                ?>
                <tr>
                    <td><?php echo $row3['co_cara']; ?></td>
                    <td><div align="right"><?php echo number_format($row3['m_jumlah'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row3['m_mdr'], 0, '.', ','); ?></div></td>
                    <td><?php echo $edc; ?></td>
                    <td><?php echo $bank; ?></td>
                    <td><?php echo $row3['m_nokartu'].$ketkartu; ?></td>
                    <td><?php echo $row3['m_nmkartu']; ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th><div align="right"><?php echo number_format($tjumlah, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($tlain, 0, '.', ',') ; ?></div></th>
            <th colspan="4"></th>
        </tr>
        <tr>
            <th colspan="7" style="color:#F00">
            <div>
                <div class="pull-left" >
			<?php
			if(($row['m_status'] != 'B') && ( substr($xparam[3],1,1) == 'Y' ))
			{
				?>
                      <button class="btn btn-primary" onclick="add_inv('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $nomor; ?>')">Entry Pembayaran</button>
                <?php
			}
			else
			{
				echo "Cancel by : ".$row['m_cancelby'].' ( '.$row['co_tglbatal'].' ),  Note : '.$row['m_cancelnote'];
			}
			?>            
                </div>
            </div>
            </th>
        </tr>
    </tfoot>
</table>        
