<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
  	include "mssql-dbnew.php";
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	$no = $_GET['no'];
	
	
	//echo 'Baris = '.$no ."</br>"; 
	
	
	
	
	if ($no == '')
	{
		$m_keterangan = '';
	}
	else
	{
		$tsqlcek2 = "select * from t_survey2 where  m_nomor = '".$nomor."' and m_no = '".$no."' ";
		
		//echo $tsqlcek2;
		$stmtcek2 = $con_dbnew->query($tsqlcek2);
		$rowcek2 = $stmtcek2->fetch_assoc();
	}
	
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    $('#m_tanggal').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayHighlight: true
    });
  });
</script>
<form class="form-horizontal" method="post" action="t_survey-simpan2.php" enctype="multipart/form-data">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo 'Checklist Serah Terima Proyek '. $nomor ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
			<label class="control-label" for="m_tanggal">Tanggal</label>
            <div class="controls">
				<input type="hidden" id="m_no" name="m_no" value="<?php echo $no; ?>" />
				<input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" />
				<input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
				<input class="input-large" type="text" id="m_tanggal" name="m_tanggal" autocomplete="off" />
            </div>
        </div>
		<div class="control-group"> 
			<label class="control-label" for="jumlah">Lantai</label> 
			<div class="controls"> 
				<input class="input-large" type="text" id="m_lantai" name="m_lantai" value="" /> 
			</div> 
		</div>
		<div class="control-group"> 
			<label class="control-label" for="jumlah">Ruangan</label> 
			<div class="controls"> 
				<input class="input-large" type="text" id="m_ruangan" name="m_ruangan" value="" /> 
			</div> 
		</div>
        <div class="control-group">
            <label class="control-label" for="carabayar">Status Temuan</label>
            <div class="controls">
            <select class="input-xlarge" id="m_status_temuan" name="m_status_temuan" style="color:black; background:white;">
				<option value="" <?php echo (($rowcek2['m_status_temuan'] ?? '' )== '') ? 'selected' : ''; ?>>-- Pilih Status Temuan --</option>
				<option value="sudah_ok" <?php echo (($rowcek2['m_status_temuan']?? '' )== 'sudah_ok') ? 'selected' : ''; ?>>Sudah Ok</option>
				<option value="perlu_perbaikan" <?php echo (($rowcek2['m_status_temuan'] ?? '')== 'perlu_perbaikan') ? 'selected' : ''; ?>>Perlu Perbaikan</option>
				<option value="perlu_finishing" <?php echo (($rowcek2['m_status_temuan'] ?? '')== 'perlu_finishing') ? 'selected' : ''; ?>>Perlu Finishing</option>
				<option value="perlu_dibersihkan" <?php echo (($rowcek2['m_status_temuan']?? '') == 'perlu_dibersihkan') ? 'selected' : ''; ?>>Perlu Dibersihkan</option>
				<option value="butuh_verifikasi" <?php echo (($rowcek2['m_status_temuan']?? '') == 'butuh_verifikasi') ? 'selected' : ''; ?>>Butuh Verifikasi</option>
			</select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="carabayar">Tipe Temuan</label>
            <div class="controls">
            <select class="input-xlarge" id="m_tipe_temuan" name="m_tipe_temuan" style="color:black; background:white;">
				<option value="" <?php echo (($rowcek2['m_tipe_temuan'] ?? '' )== '') ? 'selected' : ''; ?>>-- Pilih Tipe Temuan --</option>
				<option value="struktural" <?php echo (($rowcek2['m_tipe_temuan']?? '' )== 'struktural') ? 'selected' : ''; ?>>Struktural</option>
				<option value="finishing" <?php echo (($rowcek2['m_tipe_temuan'] ?? '')== 'finishing') ? 'selected' : ''; ?>>Finishing</option>
				<option value="plumbing" <?php echo (($rowcek2['m_tipe_temuan'] ?? '')== 'plumbing') ? 'selected' : ''; ?>>Plumbing</option>
				<option value="listrik" <?php echo (($rowcek2['m_tipe_temuan']?? '') == 'listrik') ? 'selected' : ''; ?>>Listrik</option>
				<option value="pintu" <?php echo (($rowcek2['m_tipe_temuan']?? '') == 'pintu') ? 'selected' : ''; ?>>Pintu</option>
				<option value="jendela" <?php echo (($rowcek2['m_tipe_temuan']?? '') == 'jendela') ? 'selected' : ''; ?>>Jendela</option>
				<option value="cat" <?php echo (($rowcek2['m_tipe_temuan']?? '') == 'cat') ? 'selected' : ''; ?>>Cat</option>
			</select>
            </div>
        </div>
		
        <div class="control-group">
            <label class="control-label" for="carabayar">Prioritas</label>
            <div class="controls">
            <select class="input-xlarge" id="m_prioritas" name="m_prioritas" style="color:black; background:white;">
				<option value="" <?php echo (($rowcek2['m_prioritas'] ?? '' )== '') ? 'selected' : ''; ?>>-- Pilih Type --</option>
				<option value="tinggi" <?php echo (($rowcek2['m_prioritas']?? '' )== 'sudah_ok') ? 'selected' : ''; ?>>Tinggi</option>
				<option value="sedang" <?php echo (($rowcek2['m_prioritas'] ?? '')== 'perlu_perbaikan') ? 'selected' : ''; ?>>Sedang</option>
				<option value="rendah" <?php echo (($rowcek2['m_prioritas'] ?? '')== 'perlu_finishing') ? 'selected' : ''; ?>>Rendah</option>
			</select>
            </div>
        </div>
        <div class="control-group">
			<label class="control-label" for="m_keterangan">Keterangan</label>
			<div class="controls">
				<textarea class="input-xlarge" id="m_keterangan" name="m_keterangan" rows="3"><?php echo htmlspecialchars($m_keterangan ?? ''); ?></textarea>
			</div>
		</div>
		<!-- Attachment Files -->
        <div class="control-group">
            <label class="control-label" for="attachment1">Attachment 1</label>
            <div class="controls">
                <input class="input-large" type="file" id="m_foto" name="m_foto" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="attachment2">Attachment 2</label>
            <div class="controls">
                <input class="input-large" type="file" id="m_foto2" name="m_foto2" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="attachment3">Attachment 3</label>
            <div class="controls">
                <input class="input-large" type="file" id="m_foto3" name="m_foto3" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
