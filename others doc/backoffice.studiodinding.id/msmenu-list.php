<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$kdprog = $_GET['cb'];
	$prm = $_GET['prm'];

	$tsql = "select a.* from msmenu a where a.m_program = '".$kdprog."' order by a.m_urutan asc, a.m_kode asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
    	<tr>
            <th colspan="6">
            	<div class="pull-left"><h4>MENU - LIST</h4></div>
                <div class="container input-append pull-right" style="width: auto; padding: 0 10px;">
                    <button class="btn" onClick="edit_modal('<?php echo $prm; ?>','<?php echo $kdprog; ?>','')">New Menu</button>
                </div>
            </th>
        </tr>
        <tr>
            <th>Kode</th>
            <th>Nama Menu</th>
            <th>Link</th>
            <th>Status</th>
            <th>Ada Sub</th>
            <th>sn</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {
                ?>
                <tr>
                    <td onClick="view_modal('<?php echo $prm; ?>','<?php echo $row['m_program']; ?>','<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                    <td><?php echo $row['m_nama']; ?></td>
                    <td><?php echo $row['m_object']; ?></td>
                    <td><?php echo $row['m_status']; ?></td>
                    <td><?php echo $row['m_submenu']; ?></td>
                    <td><?php echo $row['m_urutan']; ?></td>
                </tr>
                <?php
            }
            ?>
    </tbody>
</table>
