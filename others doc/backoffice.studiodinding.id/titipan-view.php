<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
   include "mssql-dbnew.php" ;
	$kdcabang = $_GET['cb'];
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_canceldate,103) as co_tglbatal, b.m_alamat, b.m_kota, b.m_telepon1, b.m_telepon2, m_kembalinote, convert(varchar(10),a.m_kembalidate,103) as tgl_kembali  from t_titipan a, mscustomer b where a.m_cabang = '".$kdcabang."' and a.m_nomor = '".$nomor."' and a.m_kodecust = b.m_kode " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;


	
	$tsqljr = "select m_nama from mssales where m_kode = '".$row['m_kodesales']."'";
	$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
	$rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC);
	
	
?><table class="table table-bordered table-condensed">
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
            <td>Alamat</td>
            <td colspan="3"><?php echo $row['m_alamat']; ?></td>
        </tr>
        <tr>
            <td>Kota</td>
            <td><?php echo $row['m_kota']; ?></td>
            <td>Telepon</td>
            <td><?php echo $row['m_telepon1'].' - '.$row['m_telepon2']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="10%">Product ID</th>
            <th width="15%">Kode Barang</th>
            <th width="5%">Qty</th>
            <th width="13%">Item</th>
            <th width="12%"><div align="right">Harga</div></th>
            <th width="8%"><div align="center">Prn</div></th>
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
            $tsql2 = "	select 	a.*, b.m_item, c.m_nama as co_namabarang, b.m_rubberid
                        from 	t_titipan2 a, t_stockdata b, msbarang c 
                        where 	a.m_cabang = '".$kdcabang."' and 
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
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')" style="cursor:pointer"><?php echo $row2['m_productid']; ?></td>
                    <td><?php echo $row2['m_rubberid']; ?></td>
                    <td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
                    <td><?php echo $rowitem['m_nama']; ?></td>
                    <td><div align="right"><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></div></td>
                    <td><div align="center">
                    	<?php
                        if (( $totbayar >= $totjual ) && ( substr($xparam[3],3,1) == 'Y' ))
                        {
                            ?>
	                    	<img src="images/printer.gif" width="15" style="cursor:pointer" id="cetak<?php echo $i; ?>" onclick="print_data('<?php echo $kdcabang; ?>','<?php echo $nomor; ?>','<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')"/>
	                    	<img src="images/certificate.gif" width="15" style="cursor:pointer" id="cert<?php echo $i; ?>" onclick="print_cert('<?php echo $kdcabang; ?>','<?php echo $nomor; ?>','<?php echo $row2['m_kodebarang']; ?>','<?php echo $row2['m_productid']; ?>')"/></div>
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
            <th></th>
            <th><?php echo number_format($tqty, 0, '.', ',') ; ?></th>
            <th></th>
            <th><div align="right"><?php echo number_format($ttot, 0, '.', ',') ; ?></div></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="11">
			<div>            
            <?php
			
			
			if ($row['m_kembalinote']== '')
			{
				if($row['m_status'] != 'B')
				{
					?>
					<div class="pull-left" >
					<?php
					if(( $totbayar <= 0 ) && ( substr($xparam[3],1,1) == 'Y' ))
					{
						?>
						<button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $kdcabang; ?>','<?php echo $nomor; ?>')">Edit</button>
						<?php
					}
					if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
					{
						?>
						<button class="btn btn-warning" onclick="print_data('<?php echo $kdcabang; ?>','<?php echo $nomor; ?>')">Print All</button>
						<?php
						
					}
					
					if(( $totbayar <= 0 ) && ( substr($xparam[3],1,1) == 'Y' ))
					{
						?>
						<button class="btn btn-primary" onclick="kembali_pos('<?php echo $prm; ?>','<?php echo $kdcabang; ?>','<?php echo $nomor; ?>')">Pengembalian</button>
						<?php
					}
					?>
					</div>
					<?php
					if(( $totbayar <= 0 ) && ( substr($xparam[3],2,1) == 'Y' ))
					{
						?>
						<div class="pull-right" >
							<button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $kdcabang; ?>','<?php echo $nomor; ?>')">Batal Faktur</button>
						</div>  
						<?php
					}
					?>
					<?php
				}
			}
			else
			{
				echo 'DOKUMEN INI SUDAH DI KEMBALIKAN PADA TANGGAL '.$row['tgl_kembali']."<br>".
					 'dengan alasan  :'.$row['m_kembalinote'] ;
			}
			?>
			</div>
            </th>
        </tr>
    </tfoot>
</table>        
