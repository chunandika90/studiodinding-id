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
		
	$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam, 
			 a.m_cabang, c.m_nama as m_namalokasi, a.m_cabang2, d.m_nama as m_namalokasi2,
			 e.m_nama as m_namatukang
			 from t_transferpb a
			 left join mstukang e on a.m_tukang = e.m_kode
			 left join mslokasi c on a.m_cabang = c.m_kode
			 left join mslokasi d on a.m_cabang2 = d.m_kode 
			 where a.m_cabang = '".$kdstore."' and a.m_nomor = '".$nomor."' " ;
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
            <td>Lokasi From</td>
            <td colspan="3"><?php echo $row['m_namalokasi']; ?></td>
        </tr>
        <tr>
            <td>To Lokasi</td>
            <td colspan="3"><?php echo $row['m_namalokasi2']; ?></td>
        </tr>
        <tr>
            <td>Nomor SPK</td>
            <td colspan="3"><?php echo $row['m_spk']; ?></td>
        </tr>
        <tr>
            <td>Nama Tukang</td>
            <td colspan="3"><?php echo $row['m_namatukang']; ?></td>
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
            <th>Shape</th>
            <th>Ukuran</th>
            <th>Dimensi</th>
            <th>Dimensi 2</th>
            <th>Dimensi 3</th>
            <th>GIA</th>
            <th>Butir</th>
            <th>Carat</th>
        </tr>
    </thead>    
	<tbody>
    	<?php
            $tsql3 = "	select 	a.*
                        from 	t_transferpb2 a
                        where 	a.m_nomor = '".$nomor."'  " ;
           // echo $tsql3;
            $stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
            while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
            {
                $tsqlcek = "	select 	*
								from 	msstone a
								where 	a.m_shape = '".$row3['m_shape']."' and a.m_size = '".$row3['m_size']."'  " ;
				$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
				$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC) ;
				
				$ukuran =$rowcek ['m_ukuran'];


                ?>
                <tr>
                    <td><?php echo $row3['m_shape']; ?></td>
                    <td><?php echo $ukuran; ?></td>
                    <td><?php echo $row3['m_dimensi']; ?></td>
                    <td><?php echo $row3['m_dimensi2']; ?></td>
                    <td><?php echo $row3['m_dimensi3']; ?></td>
                    <td><?php echo $row3['m_gia']; ?></td>
                    <td><?php echo number_format($row3['m_butir'], 0, '.', ','); ?></td>
                    <td><?php echo number_format($row3['m_carat'], 3, '.', ','); ?></td>
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

