<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$type = $_GET['ty'];
	$kode = $_GET['kd'];
	$prm =  $_GET['prm'];

	$tsql = "select * from msmaster where m_type = '".$type."' and m_kode = '".$kode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsql2 = "select m_nama from msmaster where m_type = 'TYPE' and m_kode = '".$type."'" ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);

?>

<form class="form-horizontal" method="post" action="msmaster-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $row2['m_nama'] ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="username">Kode</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_type" name="m_type" value="<?php echo $type; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $kode; ?>" />
            <input class="input-small" type="text" id="m_kode" name="m_kode" value="<?php echo $kode; ?>" <?php if($kode != ''){ ?> readonly="readonly" <?php } else { ?> required <?php } ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="namacabang">Desc</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>"  />
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_savemaster" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
