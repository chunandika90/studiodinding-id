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
	
	$tsql = "select a.* from msbarang a where a.m_kode = '".$pkode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsqlsatuan = "select m_nama from msmaster where m_type = 'SATUAN' and m_kode = '".$row['m_satuan']."'" ;
	$stmtsatuan = sqlsrv_query( $con_dbnew, $tsqlsatuan);
	$rowsatuan = sqlsrv_fetch_array( $stmtsatuan, SQLSRV_FETCH_ASSOC);

	$tsqlkatg =  "select m_nama from msmaster where m_type = 'KATEGORI' and m_kode = '".$row['m_kategori']."'" ;
	$stmtkatg = sqlsrv_query( $con_dbnew, $tsqlkatg);
	$rowkatg = sqlsrv_fetch_array( $stmtkatg, SQLSRV_FETCH_ASSOC);

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $pkode ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="kode">Kode</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_kode" value="<?php echo $row['m_kode']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nama">Nama Barang</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_nama" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Satuan">Satuan</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_abbrev" value="<?php echo $rowsatuan['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Kategori">Kategori</label>
            <div class="controls">
            <input class="input-large" type="text" id="m_alamat1" value="<?php echo $rowkatg['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Desc">Desc</label>
            <div class="controls">
            <textarea class="input-xlarge" name="m_desc" id="m_desc" cols="150" rows="3" disabled="disabled"><?php echo $row['m_desc']; ?></textarea>
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if (substr($xparam[3],1,1) == 'Y')
    {
        ?>
	    <button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm; ?>','<?php echo $pkode ; ?>')">Edit</button>
        <?php
	}
    if (substr($xparam[3],2,1) == 'Y')
    {
        ?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm; ?>','<?php echo $pkode ; ?>')">Hapus</button>
        <?php
	}
	?>        
    <button class="btn" data-dismiss="modal">Close</button>
</div>
