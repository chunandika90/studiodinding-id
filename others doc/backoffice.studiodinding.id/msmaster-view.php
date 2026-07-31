<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$type = $_GET['ty'];
	$kode = $_GET['kd'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "select * from msmaster where m_type = '".$type."' and m_kode = '".$kode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsql2 = "select m_nama from msmaster where m_type = 'TYPE' and m_kode = '".$type."'" ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $row2['m_nama'] ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="username">Kode</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="namacabang">Desc</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_nama" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if (substr($xparam[3],1,1) == 'Y')
    {
        ?>
	    <button class="btn btn-primary" data-dismiss="modal" onclick="editmaster_modal('<?php echo $prm ; ?>','<?php echo $type ; ?>','<?php echo $row['m_kode'] ; ?>')">Edit</button>
		<?php
	}
    if (substr($xparam[3],2,1) == 'Y')
    {
		?>
    	<button class="btn btn-danger" data-dismiss="modal" onclick="hapusmaster_modal('<?php echo $prm ; ?>','<?php echo $type ; ?>','<?php echo $row['m_kode'] ; ?>')">Hapus</button>
		<?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
