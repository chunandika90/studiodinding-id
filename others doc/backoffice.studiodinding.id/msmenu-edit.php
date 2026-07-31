<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$prog = $_GET['prog'];
	$kode = $_GET['kode'];
	$prm = $_GET['prm'];

	$tsql = "select a.* from msmenu a where a.m_kode = '".$kode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<form class="form-horizontal" method="post" action="msmenu-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $prog.' - '.$kode ; ?></h3>
    </div>
    <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="UserId">Kode</label>
                <div class="controls">
                <input type="hidden" id="param" name="param" value="<?php echo $prm ;?>" />
                <input type="hidden" id="m_new" name="m_new" value="<?php echo $kode ;?>" />
                <input type="hidden" id="m_program" name="m_program" value="<?php echo $prog ;?>" />
                <input class="input-small" type="text" id="m_kode" name="m_kode" value="<?php echo $kode ;?>" <?php if($kode!=''){ ?> readonly="readonly" <?php }   ?> />
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="Salesname">Nama Menu</label>
                <div class="controls">
                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="Salesname">Link</label>
                <div class="controls">
                <input class="input-xlarge" type="text" id="m_object" name="m_object" value="<?php echo $row['m_object']; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">Status</label>
                <div class="controls">
                <select name="m_status" id="m_status" class="input-medium">
                    <option value="0" <?php if ($row['m_status'] == '0'){ ?> selected="selected" <?php }   ?>>Not Link</option>
                    <option value="1" <?php if ($row['m_status'] == '1'){ ?> selected="selected" <?php }   ?>>Link</option>
                    <option value="2" <?php if ($row['m_status'] == '2'){ ?> selected="selected" <?php }   ?>>Header</option>
                </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">Sub-Menu</label>
                <div class="controls">
                <select name="m_submenu" id="m_submenu" class="input-medium">
                    <option value="0" <?php if ($row['m_submenu'] == '0'){ ?> selected="selected" <?php }   ?>>Tidak Ada</option>
                    <option value="1" <?php if ($row['m_submenu'] == '1'){ ?> selected="selected" <?php }   ?>>Ada</option>
                </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">no.urut</label>
                <div class="controls">
                <input class="input-mini" type="text" id="m_urutan" name="m_urutan" value="<?php echo $row['m_urutan']; ?>" />
                </div>
            </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_savedata" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
