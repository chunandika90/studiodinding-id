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
	
?>

<form class="form-horizontal" method="post" action="titipan-simpan2.php">
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
