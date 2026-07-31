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

	
	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, c.m_nama as nama_supplier, c.nama_rekening, c.nomor_rekening, c.bank_rekening,c.alamat, c.contact_person,
			 case when m_status = 'A' then 'Complete' else 'Batal' end m_status
			 from t_pembayaran a, master_supplier c
			 where a.m_kode_supplier = c.m_kode and 	 
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

//echo $tsql. "<br>";
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
            <td>Supplier</td>
            <td colspan="3"><?php echo '( '.$row['nama_supplier'].' ) '; ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3"><?php echo $row['alamat']; ?></td>
        </tr>
        <tr>
            <td>Nama Rekening</td>
            <td><?php echo $row['nama_rekening']; ?></td>
            <td>Rekening</td>
            <td><?php echo $row['bank_rekening'] ." ( ". $row['nomor_rekening'] ." )"; ?></td>
        </tr>
        <tr>
            <td>Contact Person</td>
            <td colspan="3"><?php echo $row['contact_person']; ?></td>
        </tr>
        <tr>
            <td>Type Invoice</td>
            <td colspan="3"><?php echo $row['m_type']; ?></td>
        </tr>
        <tr>
            <td>Cara Bayar</td>
            <td colspan="3"><?php echo $row['m_carabayar']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td colspan="3"><?php echo $row['m_status']; ?></td>
        </tr>
        
    </tbody>
</table>

<h4 style="font-weight:700; margin-top:20px;">Informasi Detail Pembayaran</h4>
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr align="center">
            <th width="3%">No</th>
            <th width="5%">Nomor PO</th>
            <th width="5%">Project</th>
            <th width="5%">Tanggal PO</th>
            <th width="10%">Keterangan</th>
            <th width="5%">Nilai PO</th>
            <th width="5%">Pembayaran</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tjumlah = 0 ;
            $tjumlahpo = 0 ;
            $tsql2 = "	select 	ifnull(b.m_nomor,a.m_nomor_po) m_nomor_po,
								ifnull(b.m_tanggal,a.m_tanggal_po) m_tanggal_po,
								ifnull(b.m_nama_project,a.m_project) m_project, a.m_jumlah_po, a.m_jumlah, a.m_keterangan
								
                        from 	t_pembayaran2 a
						left join t_po b on a.m_nomor_po = b.m_nomor
                        where 	a.m_nomor = '".$nomor."'   " ;
								
			//echo $tsql2."<br>";
			$stmt2 = $con_dbnew->query($tsql2);
			while($row2 = $stmt2->fetch_assoc())
            {
				
				
				$i = $i + 1 ;
                $tjumlah = $tjumlah + $row2['m_jumlah'] ;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row2['m_nomor_po']; ?></td>
                    <td><?php echo $row2['m_project']; ?></td>
                    <td><?php echo $row2['m_tanggal_po']; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row2['m_keterangan']??'')); ?></td>
                    <td><div align="right"><?php echo number_format($row2['m_jumlah_po']??0, 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_jumlah']??0, 0, '.', ','); ?></div></td>
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
            <th></th>
            <th><div align="right"><?php echo number_format($tjumlah, 0, '.', ',') ; ?></div></th>
        </tr>
        <tr>
            <th colspan="7">
			<div>            
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <div class="pull-left" >
                <?php
				if(( $row['m_status'] <> 'Complete' ) && ( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Edit</button>
                    <?php
				}
				if (( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-warning" onclick="print_all('<?php echo $nomor; ?>')">Print All</button>
                    <?php
				}
				?>
                </div>
                <?php
				if(( $row['m_status'] <> 'Complete' ) && ( substr($xparam[3],2,1) == 'Y' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Batal Faktur</button>
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
