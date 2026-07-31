<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$shape = $_GET['vshape'];
	$size = $_GET['vsize'];
	$prm = $_GET['prm'];
	
	//echo $kode;
	
	if ($shape == '')
	{
		$hargam = 0 ;
		$hargar = 0 ;
		$tgl = '' ;
		$tnew = 'Y';
	}
	else
	{
		
		$tsql = "select *, convert(varchar(30),m_tanggal,121) as co_tgl from msstone where m_size = '".$size."' and m_shape = '".$shape."'" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		//echo $tsql;
	
		$hargam = $row['m_hargam'] ;
		$hargar = $row['m_hargar'] ;	
		$shape = $row['m_shape'] ;
		$size = $row['m_size'] ;
		$tnew = 'T';
	}
?>

<form class="form-horizontal" method="post" action="msstone-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $row2['m_nama'] ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="tanggal">Tanggal</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $tnew; ?>" />
            <input type="hidden" id="m_kode" name="m_kode" value="<?php echo $type; ?>" />
            <input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $row['co_tgl']; ?>" readonly="readonly" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Shape</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_shape" name="m_shape" value="<?php echo $row['m_shape']; ?>" required />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Size</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_size" name="m_size" value="<?php echo $row['m_size']; ?>" required />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Ukuran</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_ukuran" name="m_ukuran" value="<?php echo $row['m_ukuran']; ?>" required />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Harga M</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_hargam" name="m_hargam" value="<?php echo number_format($hargam, 2, '.', ','); ?>" style="text-align:right" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Harga R</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_hargar" name="m_hargar" value="<?php echo number_format($hargar, 2, '.', ','); ?>" style="text-align:right" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">PB M</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_opbm"  name="m_opbm"  value="<?php echo number_format($row['m_opbm'], 0, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">PB R</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_opbr"  name="m_opbr"  value="<?php echo number_format($row['m_opbr'], 0, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Carat MIN</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_min" name="m_min"  value="<?php echo number_format($row['m_min'], 4, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Carat Max</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_max" name="m_max"  value="<?php echo number_format($row['m_max'], 4, '.', ','); ?>" style="text-align:right" disabled />
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
