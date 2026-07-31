<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kode = $_GET['kode'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "select a.*, b.m_nama as namacabang from mssales a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode and a.m_kode = '".$kode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	echo $prm ;
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $kode ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="saleskode">Kode Sales</label>
            <div class="controls">
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
            <label class="control-label" for="namacabang">Cabang</label>
            <div class="controls">
            <input class="input-large" type="text" id="namacabang" value="<?php echo $row['namacabang']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="loginid">Login-ID</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_login" value="<?php echo $row['m_login']; ?>" disabled />
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
	<?php
    if (substr($xparam[3],1,1) == 'Y')
    {
        ?>
	    <button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Edit</button>
    	<?php
	}
    if (substr($xparam[3],2,1) == 'Y')
    {
        ?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $kode ; ?>')">Hapus</button>
      	<?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
