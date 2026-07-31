<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kode = $_GET['vkode'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	//echo $prm ."<br>";
	
	$tsql = "select *
			 from master_project where  m_kode = '".$kode."' " ;
	$stmt = $con_dbnew->query($tsql);
	$row = $stmt->fetch_assoc()
	//echo $tsql;


?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $row['nama_project'] ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="tanggal">Kode</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Nama Project</label>
            <div class="controls">
            <input class="input-large" type="text" id="nama_project" value="<?php echo $row['nama_project']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Tanggal Mulai Project</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="tanggal_mulai_project" value="<?php echo $row['tanggal_mulai_project']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Supervisor Project</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="supervisor_project" value="<?php echo $row['supervisor_project']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Nomor </br>Telepon SPV</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_telepon_spv" value="<?php echo $row['m_telepon_spv']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Nama Client</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nama_client" value="<?php echo $row['nama_client']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Lokasi</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_lokasi" value="<?php echo $row['m_lokasi']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
			<label class="control-label" for="tanggal">Alamat</label>
			<div class="controls">
				<textarea class="input-xlarge" id="m_alamat" name="m_alamat" rows="4" cols="50" disabled><?php echo $row['m_alamat']; ?></textarea>
			</div>
		</div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if(( substr($xparam[3],1,1) == 'Y' ))
    {
        ?>
    	<button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Edit</button>
    	<?php
	}
	if(( substr($xparam[3],2,1) == 'Y' ))
	{
		?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Hapus</button>
        <?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
