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
	
	
	$tsql = "select * from msrate where m_kode = '".$type."' and m_tanggal = '".$kode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

	$tsql2 = "select m_nama from msmaster where m_type = 'KURS' and m_kode = '".$kode."'" ;
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
            <label class="control-label" for="tanggal">Tanggal</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_tanggal" value="<?php echo $kode; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Beli</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_beli" value="<?php echo number_format($row['m_beli'], 2, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Jual</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_jual" value="<?php echo number_format($row['m_jual'], 2, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if(( substr($xparam[3],1,1) == 'Y' ))
    {
        ?>
    	<button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $type ; ?>','<?php echo $kode ; ?>')">Edit</button>
    	<?php
	}
	if(( substr($xparam[3],2,1) == 'Y' ))
	{
		?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $type ; ?>','<?php echo $kode ; ?>')">Hapus</button>
        <?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
