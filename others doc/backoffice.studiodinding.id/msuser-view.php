<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$loginid = $_GET['logid'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);

	$tsql = "select a.*, 
				 case when m_group = '01' THEN 'Procurement ' 
					  when m_group = '02' THEN 'Finance'
					  when m_group = '03' THEN 'Supervisor Project'
					  when m_group = '04' THEN 'Admin'
					  when m_group = '00' THEN 'Direktur'
					  else '' end m_group from msuser a where  a.m_status = 'A' and a.m_login = '".$loginid."' " ;
	$stmt = $con_dbnew->query($tsql);
	$row = $stmt->fetch_assoc()


?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $loginid ; ?></h3>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="username">User Name</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="username" value="<?php echo $row['m_nama']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="email">E-Mail</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="email" value="<?php echo $row['m_email']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="email">Group</label>
            <div class="controls">
            <input class="input-xlarge" type="text" id="m_group" value="<?php echo $row['m_group']; ?>" disabled />
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
<button class="btn btn-info" data-dismiss="modal" onclick="aksesuser('<?php echo $prm ; ?>','<?php echo $loginid ; ?>')">Hak Akses</button>
<button class="btn btn-primary" data-dismiss="modal" onclick="edituser_modal('<?php echo $prm ; ?>','<?php echo $loginid ; ?>')">Edit</button>

<button class="btn btn-danger" data-dismiss="modal" onclick="hapususer_modal('<?php echo $prm ; ?>','<?php echo $loginid ; ?>')">Hapus</button>

<button class="btn" data-dismiss="modal">Close</button>
</div>
