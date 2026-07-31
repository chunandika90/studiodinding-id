<?php
 	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
 	include "mssql-dbnew.php";
	
	$pkode = $_GET['vkode'];
	$prm = $_GET['prm'];
	
	$tsql = "select a.* from msbarang a where a.m_kode = '".$pkode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<form class="form-horizontal" method="post" action="msbarang-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $pkode ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="kode">Kode</label>
            <div class="controls">
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $pkode; ?>" />
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input class="input-small" type="text" id="m_kode" name="m_kode" value="<?php echo $row['m_kode']; ?>" <?php if($pkode!=''){ ?> readonly="readonly" <?php } ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nama">Nama Barang</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" required />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Satuan">Satuan</label>
            <div class="controls">
            <select name="m_satuan" id="m_satuan" class="input-medium">
                <option value="" >ALL</option>
                <?php
                $tsqlsatuan = "select m_kode, m_nama from msmaster where m_type = 'SATUAN'" ;
                $stmtsatuan = sqlsrv_query( $con_dbnew, $tsqlsatuan);
                while( $rowsatuan = sqlsrv_fetch_array( $stmtsatuan, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowsatuan['m_kode']; ?>" <?php if ($rowsatuan['m_kode'] == $row['m_satuan']){ ?> selected="selected" <?php }   ?> ><?php echo $rowsatuan['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Kategori">Kategori</label>
            <div class="controls">
            <select name="m_kategori" id="m_kategori" class="input-large">
                <option value="" >ALL</option>
                <?php
                $tsqlkatg = "select m_kode, m_nama from msmaster where m_type = 'KATEGORI'" ;
                $stmtkatg = sqlsrv_query( $con_dbnew, $tsqlkatg);
                while( $rowkatg = sqlsrv_fetch_array( $stmtkatg, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowkatg['m_kode']; ?>" <?php if ($rowkatg['m_kode'] == $row['m_kategori']){ ?> selected="selected" <?php }   ?> ><?php echo $rowkatg['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="Desc">Desc</label>
            <div class="controls">
            <textarea class="input-xlarge" name="m_desc" id="m_desc" cols="150" rows="3" ><?php echo $row['m_desc']; ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="bt_savedata" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
