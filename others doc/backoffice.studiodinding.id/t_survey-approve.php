<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$nomor = $_GET['nm'];
	$prm = $_GET['prm'];
	
?>

<form class="form-horizontal" method="post" action="t_survey-simpan5.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo 'Approve PO = '. $nomor ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="carabayar">Keterangan Approve</label>
            <div class="controls">
            <input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input class="input-large" type="text" id="m_cancel_note" name="m_cancel_note" value="" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveu" value="Approve PO" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
