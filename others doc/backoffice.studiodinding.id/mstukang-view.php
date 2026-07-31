<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$pkode = $_GET['vkode'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "select a.* from mstukang a where a.m_kode = '".$pkode."' " ;
	//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $pkode ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="kodeSupplier">Kode Tukang</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="namaSupplier">Tukang</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_nama" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="alamat">Lokasi</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_lokasi" value="<?php echo $row['m_lokasi']; ?>" disabled />
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
<?php
if (substr($xparam[3],1,1) == 'Y')
{
	?>
    <button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $pkode ; ?>')">Edit</button>
	<?php
}
if (substr($xparam[3],2,1) == 'Y')
{
	?>
    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $pkode ; ?>')">Hapus</button>
    <?php
}
?>
<button class="btn" data-dismiss="modal">Close</button>
</div>
