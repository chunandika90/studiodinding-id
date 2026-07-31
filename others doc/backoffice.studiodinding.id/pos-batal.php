<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$kdcab = $_GET['cb'];
	$nomor = $_GET['nm'];
	$periode = $_GET['pr'];
	$prm = $_GET['prm'];
	
	// Cek dulu sisa yg belum dibayar
	$tsqlcek = "select isnull(sum((m_qty * m_harga) - m_discount - m_discount2 - m_discount3 - m_discount4),0) as cototal from t_pos2 where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'";
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
	$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);

	// Cek dulu sisa yg belum dibayar
	$tsqlcek2 = "select isnull(sum(m_jumlah),0) as cobayar from t_pos3 where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'";
	$stmtcek2 = sqlsrv_query( $con_dbnew, $tsqlcek2);
	$rowcek2 = sqlsrv_fetch_array( $stmtcek2, SQLSRV_FETCH_ASSOC);
	
	$sisa = $rowcek['cototal'] - $rowcek2['cobayar'];
	if ($sisa < 0){$sisa = 0 ;}
?>

<form class="form-horizontal" method="post" action="pos-simpan3.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $kdcab.'-'.$nomor ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="carabayar">Alasan Batal</label>
            <div class="controls">
            <input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
            <input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" />
            <input type="hidden" id="periode" name="periode" value="<?php echo $periode; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input class="input-large" type="text" id="m_cancelnote" name="m_cancelnote" value="" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Batal Faktur" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
