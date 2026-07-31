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

	$tsql = "select a.* from msmenu a where a.m_program = '".$prog."' and a.m_kode = '".$kode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $prog.' - '.$kode ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="saleskode">Kode</label>
            <div class="controls">
            <input type="hidden" id="m_program" value="<?php echo $row['m_program']; ?>" disabled />
            <input class="input-small" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="username">Nama</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_nama" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="namacabang">Link</label>
            <div class="controls">
            <input class="input-large" type="text" id="namacabang" value="<?php echo $row['m_object']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="loginid">Status</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_status" value="<?php echo $row['m_status']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="loginid">Ada Submenu</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_submenu" value="<?php echo $row['m_submenu']; ?>" disabled />
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
  <button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $prog ; ?>','<?php echo $kode ; ?>')">Edit</button>
  <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $prog ; ?>','<?php echo $kode ; ?>')">Hapus</button>
  <button class="btn" data-dismiss="modal">Close</button>
</div>
