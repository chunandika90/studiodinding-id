<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$loginid = isset($_GET['logid']) ? $_GET['logid'] : '';
	$prm = isset($_GET['prm']) ? $_GET['prm'] : '';
	$xparam = explode('/',$prm);

	// default kosong (untuk add baru)
	$row = [
		'm_nama'     => '',
		'm_password' => '',
		'm_group'    => '',
		'm_status'   => 'A'
	];

	if ($loginid != '') {
		$tsql = "select a.*, 
				 case when m_group = '01' THEN 'Procurement ' 
					  when m_group = '02' THEN 'Finance'
					  when m_group = '03' THEN 'Supervisor Project'
					  when m_group = '04' THEN 'Admin'
					  when m_group = '00' THEN 'Direktur'
					  else '' end m_group 
				 from msuser a 
				 where a.m_status = 'A' and a.m_login = '".$loginid."' ";
		$stmt = $con_dbnew->query($tsql);
		$tmpRow = $stmt->fetch_assoc();
		if ($tmpRow) {
			$row = $tmpRow;
		}
	}

	// flag add / edit
	$isNew = ($loginid == '' || !$row);
?>

<form class="form-horizontal" method="post" action="msuser-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo ($isNew ? 'Tambah User Baru' : $loginid); ?></h3>
    </div>
    <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="UserId">User ID</label>
                <div class="controls">
                <input type="hidden" id="m_new" name="m_new" value="<?php echo $loginid ?>" />
                <input type="hidden" id="param" name="param" value="<?php echo $prm ?>" />
                <input class="input-xlarge" type="text" id="m_login" name="m_login" 
                       value="<?php echo $loginid ?>" 
                       <?php if(!$isNew){ ?> readonly="readonly" <?php } ?> />
                </div>
            </div>    
            <div class="control-group">
                <label class="control-label" for="username">User Name</label>
                <div class="controls">
                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" 
                       value="<?php echo htmlspecialchars($row['m_nama']); ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="userpasw">Password</label>
                <div class="controls">
                <input class="input-xlarge" type="password" id="m_password" name="m_password" 
                       value="<?php echo ($isNew ? '' : htmlspecialchars($row['m_password'])); ?>" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="group">Group</label>
                <div class="controls">
                <select name="m_group" id="m_group" class="input-medium">
                    <option value="01" <?php if ($row['m_group'] == '01'){ ?> selected="selected" <?php } ?>>Procurement</option>
                    <option value="02" <?php if ($row['m_group'] == '02'){ ?> selected="selected" <?php } ?>>Finance</option>
                    <option value="03" <?php if ($row['m_group'] == '03'){ ?> selected="selected" <?php } ?>>Supervisor Project</option>
                    <option value="04" <?php if ($row['m_group'] == '04'){ ?> selected="selected" <?php } ?>>Admin</option>
                    <option value="00" <?php if ($row['m_group'] == '00'){ ?> selected="selected" <?php } ?>>Direktur</option>
                </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">Aktif / Non AKtif</label>
                <div class="controls">
                <select name="m_status" id="m_status" class="input-medium">
                    <option value="A" <?php if ($row['m_status'] == 'A'){ ?> selected="selected" <?php } ?>>Aktif</option>
                    <option value="N" <?php if ($row['m_status'] == 'N'){ ?> selected="selected" <?php } ?>>Non-Aktif</option>
                </select>
                </div>
            </div>
    </div>
    <div class="modal-footer">
    	<input type="submit" class="btn btn-primary" id="bt_saveuser" value="Save" />
        <button class="btn" data-dismiss="modal">Cancel</button>
    </div>
</form>
