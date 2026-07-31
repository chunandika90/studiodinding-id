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
		$m_nama = '' ;
		$nama_rekening = '' ;
		$nomor_rekening = '' ;
		$bank_rekening = '' ;
		$alamat = '' ;
		$nomor_telepon = '' ;
		$contact_person = '' ;
		$tnew = 'Y';
	}
	else
	{
		
		$tsql = "select * from master_supplier where m_kode = '".$vkode."'   " ;
		$stmt = $con_dbnew->query($tsql);
		//echo $tsql;
	
		$tnew = 'T';
		
		if ($stmt && $row = $stmt->fetch_assoc()) {
		$tnew = 'T';
		$vkode = $row['m_kode'];
		$m_nama = $row['m_nama'];
		$nama_rekening = $row['nama_rekening'];
		$nomor_rekening = $row['nomor_rekening'];
		$bank_rekening = $row['bank_rekening'];
		$alamat = $row['alamat'];
		$nomor_telepon = $row['nomor_telepon'];
		$contact_person = $row['contact_person'];
		
		} else {
			// fallback jika query gagal atau data tidak ditemukan
			$tnew = 'Y';
			$vkode = '';
			$m_nama = '' ;
			$nama_rekening = '' ;
			$nomor_rekening = '' ;
			$bank_rekening = '' ;
			$alamat = '' ;
			$nomor_telepon = '' ;
			$contact_person = '' ;
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
<form class="form-horizontal" method="post" action="master_supplier-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $m_nama ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="tanggal">Kode</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $tnew; ?>" />
            <input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $vkode; ?>"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nama Supplier</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $m_nama; ?>" required />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Contact Person</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="contact_person" name="contact_person" value="<?php echo $contact_person; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nomor Telepon</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nomor_telepon" name="nomor_telepon" value="<?php echo $nomor_telepon; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Bank Rekening</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="bank_rekening" name="bank_rekening" value="<?php echo $bank_rekening; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nomor Rekening</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nomor_rekening" name="nomor_rekening" value="<?php echo $nomor_rekening; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Nama Rekening</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="nama_rekening" name="nama_rekening" value="<?php echo $nama_rekening; ?>"  />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Alamat</label>
            <div class="controls">
            <textarea class="input-xlarge" id="alamat" name="alamat" rows="4" cols="50"><?php echo $alamat; ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
