<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
   include "mssql-dbnew.php" ;
	//$kdcabang = $_GET['cb'];
	$nomor = isset($_GET['nm']) ? $_GET['nm'] : "";
	$prm   = isset($_GET['prm']) ? $_GET['prm'] : "";
	$xparam = explode('/',$prm);

	
	
	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl , b.supervisor_project as nama_supervisor, b.nama_project as nama_project, 
			 b.m_lokasi, b.m_alamat, b.nama_client, b.supervisor_project,
			 case when a.m_jumlah_qty = a.m_terima_qty then 'Delivered' else 'Not Delivered' end m_terima, c.m_nama as nama_supplier, ifnull(a.m_bayar,0) m_bayar, ifnull(a.m_total_rp,0) m_total_rp,
			 case when a.m_type is null or a.m_type = '' then 'General' else a.m_type end m_type,
			 case when a.m_status = 'B'then 'Batal' else 'Aktif' end m_status ,
			 case when a.m_approved_by is not null then 'Sudah Disetujui' else 'Belum Disetujui' end m_status_approved, d.m_nama m_payment_term
			 from t_po a
			 left join master_term_pembayaran d on a.m_payment_term = d.m_kode, master_project b, master_supplier c
			 where a.m_kode_project = b.m_kode and a.m_kode_supplier = c.m_kode and 	 
			 a.m_nomor = '".$nomor."' " ;
	//echo $tsql."<br>";
	$stmt = $con_dbnew->query($tsql);
	if ($stmt && $row = $stmt->fetch_assoc()) {
		// ada data, aman dipakai
	} else {
		echo "<div style='color:red'>Data tidak ditemukan untuk nomor: ".$nomor."</div>";
		$row = [
			'm_cabang' => '',
			'm_nomor' => '',
			'co_tgl' => '',
			'm_kodecust' => '',
			'm_nama' => '',
			'm_alamat' => '',
			'm_lokasi' => '',
			'm_nama_client' => '',
			'm_nama_supervisor' => '',
			'm_nama_supplier' => '',
			'm_keterangan' => '',
			'm_status' => '',
			'm_diskon_persen' => 0,
			'm_diskon_jumlah' => 0,
			'm_ppn_persen' => 0,
			'm_ppn_jumlah' => 0,
			'm_total_rp' => 0,
			'm_bayar' => 0
		];
	}

?>
<h4 style="font-weight:700; margin-top:20px;">Informasi PO</h4>
<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Nomor</td>
            <td width="40%"><b><?php echo $row['m_nomor']; ?></b></td>
            <td width="10%">Tanggal</td>
            <td width="30%"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>Nama Projek</td>
            <td colspan="3"><?php echo '( '.$row['nama_project'].' ) '; ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3"><?php echo $row['m_alamat']; ?></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td><?php echo $row['m_lokasi']; ?></td>
            <td>Client</td>
            <td><?php echo $row['nama_client']; ?></td>
        </tr>
        <tr>
            <td>Supervisor</td>
            <td colspan="3"><?php echo $row['supervisor_project']; ?></td>
        </tr>
        <tr>
            <td>Vendor</td>
            <td colspan="3"><?php echo $row['m_nama_supplier']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Status Penerimaan</td>
            <td colspan="3"><?php echo $row['m_terima']; ?></td>
        </tr>
        <tr>
            <td>Total Harga</td>
            <td Colspan="2"></td>
            <td><div align="right"><?php echo number_format($row['m_jumlah_rp'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Total Diskon %</td>
            <td Colspan="2" ><div align="right"><?php echo number_format($row['m_diskon_persen'] ?? 0, 2, '.', ',').' %'; ?></div></td>
            <td><div align="right"><?php echo number_format($row['m_diskon_jumlah'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Total Diskon RP</td>
            <td Colspan="3" ><div align="right"><?php echo number_format($row['m_diskon2_jumlah'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Total PPN</td>
            <td Colspan="2" ><div align="right"><?php echo number_format($row['m_ppn_persen'] ?? 0, 2, '.', ',').' %'; ?></div></td>
            <td><div align="right"><?php echo number_format($row['m_ppn_jumlah'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Total PO</td>
            <td Colspan="2"></td>
            <td><div align="right"><?php echo number_format($row['m_total_rp'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Total Bayar</td>
            <td Colspan="2"></td>
            <td><div align="right"><?php echo number_format($row['m_bayar'] ?? 0, 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Sisa Tagihan</td>
            <td Colspan="2"></td>
            <td><div align="right"><?php echo number_format(($row['m_total_rp'] ?? 0)-($row['m_bayar'] ?? 0), 0, '.', ','); ?></div></td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td colspan="3"><?php echo $row['m_type']; ?></td>
        </tr>
        <tr>
            <td>Status Approval</td>
            <td colspan="3"><?php echo $row['m_status_approved']." ( " . $row['m_approved_note'] ." )"." - "." ( " . $row['m_approved_by'] ." )"; ?></td>
        </tr>
        <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
            <td>Status Transaksi</td>
            <td colspan="3"><?php echo $row['m_status'] ." ( " . $row['m_cancel_note'] ." )"; ?></td>
        </tr>
        <tr>
            <td>Term Pembayaran</td>
            <td colspan="3"><?php echo $row['m_payment_term']; ?></td>
        </tr>
        <tr>
            <td>Delivery Date</td>
            <td colspan="3"><?php echo $row['m_tanggal_kirim']; ?></td>
        </tr>
        
    </tbody>
</table>

<h4 style="font-weight:700; margin-top:20px;">Informasi Detail PO</h4>
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr align="center">
            <th width="3%">No</th>
            <th width="15%">Nomor MR</th>
            <th width="15%">Material</th>
            <th width="13%">Keterangan</th>
            <th width="5%">Satuan</th>
            <th width="5%">Qty</th>
            <th width="10%">Harga</th>
            <th width="10%">Diskon RP</th>
            <th width="10%">Jumlah</th>
            <th width="10%">Total Akhir</th>
            <th width="10%" class="hide-mobile">Qty </br> Terima</th>
            <th width="10%" class="hide-mobile">Status </br> Terima</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tqty = 0 ;
            $tqtyterima = 0 ;
            $takhir = 0 ;
            $tsql2 = "	select 	a.*, c.m_nama as co_namabarang, xx.status_bayar, xx.total_terima
                        from 	master_item c ,t_po2 a
						left join 
						(
							select x.m_nomor, x.m_item, x.status_bayar, sum(x.m_qty) total_terima
							from
							(
								select z.m_nomor, y.m_item,
									   CASE when z.m_partial <> 'OK' or z.m_partial is null then case 
												 WHEN z.m_jumlah = z.m_po THEN 'Complete' 
												 ELSE 'Not Complete' END 
											else 'Complete Partial' end status_bayar,  y.m_qty
								from t_penawaran z, t_penawaran_receive y
								where z.m_nomor = y.m_nomor 
							)x
							group by x.m_nomor, x.m_item, x.status_bayar
						)xx on a.m_item = xx.m_item and a.m_nomor_request = xx.m_nomor
                        where 	a.m_nomor = '".$nomor."' and 
                                a.m_item = c.m_kode  " ;
								
			//echo $tsql2."<br>";
			$stmt2 = $con_dbnew->query($tsql2);
			while($row2 = $stmt2->fetch_assoc())
            {
				
				$jumlah = $row2['m_harga']- $row2['m_diskon_rp'];
				$total_akhir = $jumlah * $row2['m_qty'];
				
				$i = $i + 1 ;
                $tqty = $tqty + $row2['m_qty'] ;
                $tqtyterima = $tqtyterima + $row2['total_terima'] ;
                $takhir = $takhir + ($total_akhir) ;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row2['m_nomor_request']; ?></td>
                    <td><?php echo $row2['co_namabarang']; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row2['m_keterangan'])); ?></td>
                    <td><div align="center"><?php echo $row2['m_unit']; ?></div></td>
                    <td><div align="right"><?php echo rtrim(rtrim(number_format($row2['m_qty'], 3, '.', ','),'0'),'.'); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_diskon_rp'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($jumlah, 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($total_akhir, 0, '.', ','); ?></div></td>
                    <td class="hide-mobile"><div align="right"><?php echo number_format($row2['total_terima']??0, 0, '.', ','); ?></div></td>
                    <td class="hide-mobile"><div align="center"><?php echo $row2['status_bayar']; ?></div></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><div align="right"><?php echo rtrim(rtrim(number_format($tqty, 3, '.', ','),'0'),'.') ; ?></div></th>
            <th></th>
            <th></th>
            <th></th>
            <th><div align="right"><?php echo number_format($takhir, 0, '.', ',') ; ?></div></th>
            <th class="hide-mobile"><div align="right"><?php echo number_format($tqtyterima, 0, '.', ',') ; ?></div></th>
            <th class="hide-mobile"></th>
        </tr>
        <tr>
            <th colspan="14">
			<div>            
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <?php
				if(( $row['m_status'] <> 'Complete' ) && ( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
					<div class="pull-left" >
						<button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Edit</button>
					</div>
                    <?php
				}
				if (( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
					<div class="pull-left" >
						<button class="btn btn-warning" onclick="print_all('<?php echo $nomor; ?>')">Print All</button>
					
					</div>
                    <?php
				}
				if(( $_SESSION['group'] == '00' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-success" onclick="approve_po('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Approve PO</button>
                    </div>  
                    <?php
				}
				?>
                </div>
				
                <?php
				if(( $row['m_status'] <> 'Complete' ) && ( substr($xparam[3],2,1) == 'Y' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Batal PO</button>
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

<h4 style="font-weight:700; margin-top:20px;">Informasi Pembayaran PO</h4>
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="2%">No</th>
            <th width="8%">Tanggal</th>
            <th width="8%">Type</th>
            <th width="8%">Cara Bayar</th>
            <th width="6%">Keterangan</th>
            <th width="6%">Nomor Dok</th>
            <th width="6%"><div align="right">Jumlah</div></th>
            <th width="6%"><div align="right">Print Voucher</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i= 0 ;
            $tjumlah= 0 ;
			$tlain = 0 ;
            $tsql3 = "	select 	a.*, b.nama cara_pembayaran
                        from 	t_po3 a, master_pembayaran b
                        where 	a.m_nomor = '".$nomor."' and 
								b.id = a.m_carabayar" ;
			$stmt3 = $con_dbnew->query($tsql3);
			while($row3 = $stmt3->fetch_assoc())
            {
				$i= $i + 1 ;
				$ketkartu = '';
				
				
                $tjumlah = $tjumlah + $row3['m_jumlah'] ;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row3['m_tanggal']; ?></td>
                    <td><?php echo $row3['m_type']; ?></td>
                    <td><?php echo $row3['cara_pembayaran']; ?></td>
                    <td><?php echo $row3['m_keterangan']; ?></td>
                    <td><?php echo $row3['m_nodoc']; ?></td>
                    <td><div align="right"><?php echo number_format($row3['m_jumlah'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><button class="btn btn-warning" onclick="print_voucher('<?php echo $nomor; ?>')">Print</button></div></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan= "6"></th>
            <th><div align="right"><?php echo number_format($tjumlah, 0, '.', ',') ; ?></div></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="9" style="color:#F00">
            <div>
			
			<?php
			if(($row['m_status'] != 'B') && ( substr($xparam[3],1,1) == 'Y'))
			{
				/*
				echo "TOTAL BAYAR = " .$row['m_bayar'];
				echo "TOTAL Jumlah Rp = " .$row['m_total_rp'];
				*/
				
				if ( $row['m_bayar'] >= $row['m_total_rp'] )
				{
					
					
					echo "PENJUALAN INI SUDAH DI BAYARKAN LUNAS"	;
				}
				else
				{				
				?>
                      <button class="btn btn-primary" onclick="add_inv('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Entry Pembayaran</button>
                <?php
				}
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