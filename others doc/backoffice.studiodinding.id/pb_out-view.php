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

	
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tglhapus,103) as co_tglhapus, b.m_nama, 
			c.m_nama as m_namalokasi, d.m_nama as m_namalokasi2
			from t_barang_out a
			left join mssupplier b on a.m_supplier = b.m_kode
			left join mslokasi c on a.m_lokasi = c.m_kode
			left join mslokasi d on a.m_lokasi2 = d.m_kode  
			where a.m_nomor = '".$nomor."'  " ;
	//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

	
?><table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Nomor</td>
            <td width="40%"><b><?php echo $row['m_lokasi'].'-'.$row['m_nomor']; ?></b></td>
            <td width="10%">Tanggal</td>
            <td width="30%"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td colspan="3"><?php echo '( '.$row['m_supplier'].' ) '.$row['m_nama']; ?></td>
        </tr>
        <tr>
            <td>From Lokasi</td>
            <td colspan="3"><?php echo '( '.$row['m_lokasi'].' ) '.$row['m_namalokasi']; ?></td>
        </tr>
        <tr>
            <td>To Lokasi</td>
            <td colspan="3"><?php echo '( '.$row['m_lokasi2'].' ) '.$row['m_namalokasi2']; ?></td>
        </tr>
        <tr>
            <td>Nomor SPK</td>
            <td colspan="3"><?php echo $row['m_spk']; ?></td>
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
            <th width="2%">No</th>
            <th width="10%">Type</th>
            <th width="10%">Kode Barang</th>
            <th width="10%">Tukang</th>
            <th width="10%"><div align="right">Total Pcs</div></th>
            <th width="10%"><div align="right">Total Berat</div></th>
            <th width="10%"><div align="center">Keterangan</div></th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tqty = 0 ;
			$tgross = 0;
            $tsql2 = "	select 	a.*, d.m_nama as m_namabarang
                        from 	t_barang_out2 a
						left join mstype b on a.m_type = b.m_type
						left join mslokasi c on a.m_tukang = c.m_nama
						left join msmaster d on d.m_type = 'MATERIAL' and a.m_kodebarang = d.m_kode
                        where 	a.m_lokasi = '".$row['m_lokasi']."' and 
                                a.m_nomor = '".$nomor."' " ;
			//echo $tsql2;
            $stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
            while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
            {
				
				$i = $i + 1 ;
                $tqty = $tqty + $row2['m_qty'] ;
                $tgross = $tgross + $row2['m_grossweight'] ;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row2['m_type']; ?></td>
                    <td><?php echo $row2['m_namabarang']; ?></td>
                    <td><?php echo $row2['m_tukang']; ?></td>
                    <td><div align="right"><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></div></td>
                    <td><div align="right"><?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?></div></td>
                    <td><?php echo $row2['m_keterangan']; ?></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4"></th>
            <th><div align="right"><?php echo number_format($tqty, 0, '.', ',') ; ?></div></th>
            <th><div align="right"><?php echo number_format($tgross,2, '.', ',') ; ?></div></th>
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
				if(( $totbayar <= 0 ) && ( substr($xparam[3],1,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $row['m_lokasi']; ?>','<?php echo $nomor; ?>')">Edit</button>
                    <?php
				}
				if (( $totbayar >= $totjual) && ( substr($xparam[3],3,1) == 'Y' ))
				{
					?>
                    <button class="btn btn-warning" onclick="print_all('<?php echo $row['m_lokasi']; ?>','<?php echo $nomor; ?>')">Print All</button>
                    <?php
				}
				?>
                </div>
                <?php
				if(( $totbayar <= 0 ) && ( substr($xparam[3],2,1) == 'Y' ))
				{
					?>
                    <div class="pull-right" >
                        <button class="btn btn-danger" onclick="hapus_data('<?php echo $prm; ?>','<?php echo $row['m_lokasi']; ?>','<?php echo $nomor; ?>')">Batal Faktur</button>
                    </div>  
                    <?php
				}
				?>
				<?php
			}
			else
			{
				echo "Cancel by : ".$row['m_hapus'].' ( '.$row['co_tglhapus'].' ) ';
			}
			?>
			</div>
            </th>
        </tr>
    </tfoot>
</table>        

