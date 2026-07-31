<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$type = $_GET['ty'];
	$kode = $_GET['kd'];
	$prm = $_GET['prm'];
	
	$tsql2 = "select m_nama from msmaster where m_type = 'KURS' and m_kode = '".$type."'" ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);

	if ($kode == '')
	{
		$beli = 0 ;
		$jual = 0 ;
		$tgl = '' ;
	}
	else
	{
		
		$tsql = "select * from msrate where m_kode = '".$type."' and m_tanggal = '".$kode."'" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
	
		$beli = $row['m_beli'] ;
		$jual = $row['m_jual'] ;	
	}
?>

<form class="form-horizontal" method="post" action="msrate-simpan.php">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3 id="myModalLabel"><?php echo $row2['m_nama'] ; ?></h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="tanggal">Tanggal</label>
            <div class="controls">
            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
            <input type="hidden" id="m_new" name="m_new" value="<?php echo $kode; ?>" />
            <input type="hidden" id="m_kode" name="m_kode" value="<?php echo $type; ?>" />
            <input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $kode; ?>" readonly="readonly" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursbeli">Beli</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_beli" name="m_beli" value="<?php echo number_format($beli, 2, '.', ','); ?>" style="text-align:right" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="kursjual">Jual</label>
            <div class="controls">
            <input class="input-small" type="text" id="m_jual" name="m_jual" value="<?php echo number_format($jual, 2, '.', ','); ?>" style="text-align:right" />
            </div>
        </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
      <button class="btn" data-dismiss="modal">Close</button>
    </div>
</form>				
