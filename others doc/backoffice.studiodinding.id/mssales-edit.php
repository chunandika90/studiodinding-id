<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kode = $_GET['kode'];
	$prm = $_GET['prm'];

	$tsql = "select a.* from mssales a where a.m_kode = '".$kode."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>

<form class="form-horizontal" method="post" action="mssales-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $kode ; ?></h3>
    </div>
    <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="UserId">Sales ID</label>
                <div class="controls">
                <input type="hidden" id="m_new" name="m_new" value="<?php echo $kode;?>" />
                <input type="hidden" id="param" name="param" value="<?php echo $prm ;?>" />
                <input class="input-small" type="text" id="m_kode" name="m_kode" value="<?php echo $kode ?>" <?php if($kode!=''){ ?> readonly="readonly" <?php }   ?> />
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="Salesname">Sales Name</label>
                <div class="controls">
                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $row['m_nama']; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="namacabang">Store</label>
                <div class="controls">
                <select name="m_cabang" id="m_cabang" class="input-large">
                    <option value="" >ALL</option>
                    <?php
                    $tsqlcabang = "select m_kode, m_nama from msmaster where m_type = 'STORE' order by m_nama asc" ;
                    $stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);
                    while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                    {
                        ?>
                        <option value="<?php echo $rowcabang['m_kode']; ?>" <?php if ($rowcabang['m_kode'] == $row['m_cabang']){ ?> selected="selected" <?php }   ?> ><?php echo $rowcabang['m_nama']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="loginid">Login-ID</label>
                <div class="controls">
                <input class="input-medium" type="text" id="m_login" name="m_login" value="<?php echo $row['m_login']; ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">Aktif / Non AKtif</label>
                <div class="controls">
                <select name="m_aktif" id="m_aktif" class="input-medium">
                    <option value="1" <?php if ($row['m_aktif'] == '1'){ ?> selected="selected" <?php }   ?>>Aktif</option>
                    <option value="0" <?php if ($row['m_aktif'] == '0'){ ?> selected="selected" <?php }   ?>>Non-Aktif</option>
                </select>
                </div>
            </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_savedata" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>				
