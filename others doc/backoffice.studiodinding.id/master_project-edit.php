<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$vkode = $_GET['vkode'];
	$prm = $_GET['prm'];
	
	//echo $kode;
	
	if ($vkode == '')
	{
		$vkode = '' ;
		$nama_project = '' ;
		$tanggal_mulai_project = '' ;
		$supervisor_project = '' ;
		$m_telepon_spv = '' ;
		$nama_client = '' ;
		$m_lokasi = '' ;
		$m_alamat = '' ;
		$tnew = 'Y';
	}
	else
	{
		
		$tsql = "select * from master_project where m_kode = '".$vkode."'   " ;
		$stmt = $con_dbnew->query($tsql);
		//echo $tsql;
	
		$tnew = 'T';
		
		if ($stmt && $row = $stmt->fetch_assoc()) {
		$tnew = 'T';
		$vkode = $row['m_kode'];
		$nama_project = $row['nama_project'];
		$nama_client = $row['nama_client'];
		$tanggal_mulai_project = $row['tanggal_mulai_project'];
		if (!empty($tanggal_mulai_project)) {
			// Original: 20/10/2023
			$tanggal_mulai_project = DateTime::createFromFormat('d/m/Y', $tanggal_mulai_project)
				->format('Y-m-d');  // Convert → 2023-10-20
		}
		$supervisor_project = $row['supervisor_project'];
		$m_telepon_spv = $row['m_telepon_spv'];
		$m_lokasi = $row['m_lokasi'];
		$m_alamat = $row['m_alamat'];
		} else {
			// fallback jika query gagal atau data tidak ditemukan
			$tnew = 'Y';
			$vkode = '';
			$nama_project = '';
			$tanggal_mulai_project = '';
			$supervisor_project = '';
			$m_telepon_spv = '';
			$nama_client = '';
			$m_lokasi = '';
			$m_alamat = '';	
		}
	}
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
$(function() {
    $("#tanggal_mulai_project").datepicker({
        dateFormat: "yy-mm-dd",   // Format like 2025-09-14
        changeMonth: true,
        changeYear: true
    });
});
</script>
<form class="form-horizontal" method="post" action="master_project-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $nama_project ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="tanggal">Kode</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $tnew; ?>" />
            <input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $vkode; ?>" readonly/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nama Project</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nama_project" name="nama_project" value="<?php echo $nama_project; ?>" required />
            </div>
        </div>
		<div class="control-group">
			<label class="control-label" for="m_tanggal">Tanggal Mulai Project</label>
			<div class="controls">
				<input class="input-xlarge" type="date" id="tanggal_mulai_project" name="tanggal_mulai_project" 
					   value="<?php echo $tanggal_mulai_project; ?>" />
			</div>
		</div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Supervisor Project</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="supervisor_project" name="supervisor_project" value="<?php echo $supervisor_project; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nomor </br>Telepon SPV</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_telepon_spv" name="m_telepon_spv" value="<?php echo $m_telepon_spv; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nama Client</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nama_client" name="nama_client" value="<?php echo $nama_client; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Lokasi</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $m_lokasi; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Alamat</label>
            <div class="controls">
            <textarea class="input-xlarge" id="m_alamat" name="m_alamat" rows="4" cols="50"><?php echo $m_alamat; ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
