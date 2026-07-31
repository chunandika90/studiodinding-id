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
			 case when a.m_status = 'B'then 'Batal' else 'Aktif' end m_status ,
			 case when a.m_approved_by is not null then 'Sudah Disetujui' else 'Belum Disetujui' end m_status_approved 
			 from t_survey a, master_project b
			 where a.m_kode_project = b.m_kode and 	 
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
			'm_keterangan' => '',
			'm_status' => '',
		];
	}

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<h4 style="font-weight:700; margin-top:20px;">Informasi Header Serah Terima</h4>
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
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Status Approval</td>
            <td colspan="3"><?php echo $row['m_status_approved']." ( " . $row['m_approved_note'] ." )"." - "." ( " . $row['m_approved_by'] ." )"; ?></td>
        </tr>
        <tr <?php if($row['m_status']== 'B'){ ?> style="color:#F00" <?php } ?> >
            <td>Status Transaksi</td>
            <td colspan="3"><?php echo $row['m_status'] ." ( " . $row['m_cancel_note']  ." )"; ?></td>
        </tr>
        
    </tbody>
	<tfoot>
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
						<button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Edit Header</button>
					</div>
                    <?php
				}
			}
			?>
			</div>
            </th>
        </tr>
    </tfoot>
</table>
<!-- Tempat untuk preview image -->
<div id="imagePreview" style="text-align:center; margin:10px 0;">
    <img id="previewImage" src="" style="max-width:500px; width:100%; display:none; border:1px solid #ccc; padding:5px;">
</div>
<h4 style="font-weight:700; margin-top:20px;">Informasi Detail Serah Terima (Checklist)</h4>
<table class="table table-bordered table-striped table-hover table-condensed">

	<button class="btn btn-success" onclick="add_checklist('<?php echo $prm; ?>','<?php echo $nomor; ?>','')">Add Checklist</button>
    <thead>
        <tr align="center">
            <th width="3%">No</th>
            <th width="15%">Lantai</th>
            <th width="15%">Ruangan</th>
            <th width="15%">Status Temuan</th>
            <th width="15%" class="hide-mobile">Prioritas</th>
            <th width="15%" class="hide-mobile">Tipe Temuan</th>
            <th width="13%">Keterangan</th>
            <th width="5%">Foto 1</th>
            <th width="5%">Foto 2</th>
            <th width="5%">Foto 3</th>
            <th width="5%"  class="hide-mobile">Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php
			$i = 0 ;
            $tsql3 = "	select 	a.*
                        from 	t_survey2 a
                        where 	a.m_nomor = '".$nomor."'   " ;
								
			//echo $tsql2."<br>";
			$stmt3 = $con_dbnew->query($tsql3);
			while($row3 = $stmt3->fetch_assoc())
            {
				
				
				$i = $i + 1 ;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row3['m_lantai']; ?></td>
                    <td><?php echo $row3['m_ruangan']; ?></td>
                    <td><?php echo $row3['m_status_temuan']; ?></td>
                    <td class="hide-mobile"><?php echo $row3['m_prioritas']; ?></td>
                    <td class="hide-mobile"><?php echo $row3['m_tipe_temuan']; ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row3['m_keterangan'])); ?></td>
					<?php
					$fotoColumns = ['m_foto','m_foto2','m_foto3'];
					foreach($fotoColumns as $col) {
						$foto = $row3[$col];
						if(!empty($foto)){
							echo '<td><img src="'.$foto.'" class="img-fluid" style="max-width:40px; cursor:pointer;" onclick="showImage(\''.$foto.'\')"></td>';
						} else {
							echo '<td>-</td>';
						}
					}
					?>
                    <td class="hide-mobile"><button class="btn btn-success" onclick="add_checklist('<?php echo $prm; ?>','<?php echo $nomor; ?>','<?php echo $row3['m_no']; ?>')">Edit Checklist</button></td>
                </tr>
                <?php
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="12">
			<div>            
            <?php
			if($row['m_status'] != 'B')
			{
				?>
                <?php
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
                        <button class="btn btn-success" onclick="approve_po('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Approve Survey</button>
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
                        <button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Batal Survey</button>
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

<!-- Overlay sederhana -->
<div id="imgOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
     background:rgba(0,0,0,0.8); text-align:center; z-index:9999; cursor:pointer;">
    <img id="overlayImg" src="" style="max-width:90%; max-height:90%; margin-top:5%;">
</div>

<script>
function showImage(src){
    const overlay = document.getElementById('imgOverlay');
    const img = document.getElementById('overlayImg');
    img.src = src;
    overlay.style.display = 'block';
}

// Tutup overlay kalau diklik
document.getElementById('imgOverlay').addEventListener('click', function(){
    this.style.display = 'none';
});
</script>