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
	$tsqlcek = "select ifnull(m_total_rp,0) cototal from t_po where m_nomor = '".$nomor."'";
	$stmtcek = $con_dbnew->query($tsqlcek);
	$rowcek = $stmtcek->fetch_assoc();

//	echo $tsqlcek ;
	// Cek dulu sisa yg belum dibayar
	$tsqlcek2 = "select sum(ifnull(m_jumlah,0)) as cobayar from t_po3 where  m_nomor = '".$nomor."'";
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
<form class="form-horizontal" method="post" action="po-simpan2.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo 'Pembayaran '. $nomor ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="carabayar">Cara Bayar</label>
            <div class="controls">
            <input type="hidden" id="m_no" name="m_no" value="<?php echo $no; ?>" />
            <input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <select name="m_carabayar" id="m_carabayar" class="input-medium">
                <?php
                $tsqlcara = "select id, nama from master_pembayaran order by id asc" ;
				$stmtcara = $con_dbnew->query($tsqlcara);
				while($rowcara = $stmtcara->fetch_assoc())
                {
                    ?>
                    <option value="<?php echo $rowcara['id']; ?>" ><?php echo $rowcara['nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
		<div class="control-group">
			<label class="control-label" for="m_tanggal">Tanggal</label>
			<div class="controls">
				<input class="input-large" type="text" id="m_tanggal" name="m_tanggal" autocomplete="off" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="m_tanggal">Type</label>
			<div class="controls">
				
				<select name="m_type" id="m_type" class="input-medium">
					<option value="HITAM" >HITAM</option>
					<option value="PUTIH" >PUTIH</option>
				</select>
			</div>
		</div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Keterangan</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_keterangan" name="m_keterangan" value=""   />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Nomor Doc</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_nodoc" name="m_nodoc" value=""   />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Jumlah Bayar</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_jumlah" name="m_jumlah" value="<?php echo number_format($sisa, 0, '.', ',') ; ?>" onChange="recalc_payment()" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
