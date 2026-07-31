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
	
	// Cek dulu sisa yg belum dibayar
	$tsqlcek = "select ifnull(m_jumlah,0) cototal from t_penawaran where m_nomor = '".$nomor."'";
	$stmtcek = $con_dbnew->query($tsqlcek);
	$rowcek = $stmtcek->fetch_assoc();

//	echo $tsqlcek ;
	// Cek dulu sisa yg belum dibayar
	$tsqlcek2 = "select sum(ifnull(m_qty,0)) as cobayar from t_penawaran_receive where  m_nomor = '".$nomor."'";
	$stmtcek2 = $con_dbnew->query($tsqlcek2);
	$rowcek2 = $stmtcek2->fetch_assoc();
	
	$sisa = $rowcek['cototal'] - $rowcek2['cobayar'];
	if ($sisa < 0){$sisa = 0 ;}
?>
<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function(){
    flatpickr("#m_tanggal", {
      dateFormat: "d/m/Y",
      defaultDate: "today"
    });
  });
</script>
<form class="form-horizontal" method="post" action="t_penawaran-simpan3.php" enctype="multipart/form-data">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo 'Parsiap Complete '. $nomor ; ?></h3>
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
            <label class="control-label" for="jumlah">Alasan</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_partial_alasan" name="m_partial_alasan" value=""   />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
