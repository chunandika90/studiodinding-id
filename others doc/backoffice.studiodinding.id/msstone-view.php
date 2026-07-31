<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$shape = $_GET['vshape'];
	$size = $_GET['vsize'];
	$prm = $_GET['prm'];
	$xparam = explode('/',$prm);
	
	
	$tsql = "select *, convert(varchar(30),m_tanggal,121) as co_tgl 
			 from msstone where m_size = '".$size."' and m_shape = '".$shape."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	//echo $tsql;


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
            <input class="input-medium" type="text" id="m_tanggal" value="<?php echo $row['co_tgl']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Shape</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_shape" value="<?php echo $row['m_shape']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Size</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_size" value="<?php echo $row['m_size']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="tanggal">Ukuran</label>
            <div class="controls">
            <input class="input-medium" type="text" id="m_ukuran" value="<?php echo $row['m_ukuran']; ?>" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Beli</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_beli" value="<?php echo number_format($row['m_hargam'], 2, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Jual</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_jual" value="<?php echo number_format($row['m_hargar'], 2, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">PB M</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_opbm" value="<?php echo number_format($row['m_opbm'], 0, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">PB R</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_opbr" value="<?php echo number_format($row['m_opbr'], 0, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Carat MIN</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_min" value="<?php echo number_format($row['m_min'], 4, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Carat Max</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_max" value="<?php echo number_format($row['m_max'], 4, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
    </form>				
</div>
<div class="modal-footer">
	<?php
    if(( substr($xparam[3],1,1) == 'Y' ))
    {
        ?>
    	<button class="btn btn-primary" data-dismiss="modal" onclick="edit_modal('<?php echo $prm ; ?>','<?php echo $shape ; ?>','<?php echo $size ; ?>')">Edit</button>
    	<?php
	}
	if(( substr($xparam[3],2,1) == 'Y' ))
	{
		?>
	    <button class="btn btn-danger" data-dismiss="modal" onclick="hapus_modal('<?php echo $prm ; ?>','<?php echo $shape ; ?>','<?php echo $size ; ?>')">Hapus</button>
        <?php
	}
	?>
    <button class="btn" data-dismiss="modal">Close</button>
</div>
