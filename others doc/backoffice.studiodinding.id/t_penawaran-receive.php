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
<form class="form-horizontal" method="post" action="t_penawaran-simpan2.php" enctype="multipart/form-data">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo 'Penerimaan Barang '. $nomor ; ?></h3>
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
            <label class="control-label" for="carabayar">Kode Material</label>
            <div class="controls">
            <select name="m_item" id="m_item" class="input-large">
                <?php
                $tsqlcara = "select a.m_item, b.m_nama from t_penawaran2 a , master_item b where a.m_item = b.m_kode and a.m_nomor = '".$nomor."' and m_po <> 0 and m_po is not null  order by m_kode asc" ;
				$stmtcara = $con_dbnew->query($tsqlcara);
				while($rowcara = $stmtcara->fetch_assoc())
                {
                    ?>
                    <option value="<?php echo $rowcara['m_item']; ?>" ><?php echo $rowcara['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Penerima</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_penerima" name="m_penerima" value=""   />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Keterangan</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_keterangan" name="m_keterangan" value=""   />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="jumlah">Qty</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_jumlah" name="m_jumlah" value="<?php echo number_format($sisa, 0, '.', ',') ; ?>" onChange="recalc_payment()" />
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
