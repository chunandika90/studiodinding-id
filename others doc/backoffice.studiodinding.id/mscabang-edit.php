<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$pkode = $_GET['vkode'];
	$prm = $_GET['prm'];

	$tsql = "select a.* from mslokasi a where a.m_kode = '".$pkode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<form class="form-horizontal" method="post" action="mscabang-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $pkode ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="kodecabang">Kode Lokasi</label>
            <div class="controls">
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $pkode; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm ; ?>" />
            <input class="input-small" type="text" id="m_kode" name="m_kode" value="<?php echo $row['m_kode']; ?>" <?php if($pkode!=''){ ?> readonly="readonly" <?php }   ?> placeholder="XX (2DIGIT)"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="namacabang">Lokasi</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" required />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="bt_savedata" value="Save" />
        <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
