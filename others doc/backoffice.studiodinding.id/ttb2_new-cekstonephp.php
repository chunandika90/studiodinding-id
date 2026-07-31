<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$shape = $_GET['shape'];
	$size = $_GET['size'];
	$butir = str_replace(",","",$_GET['butir'.$i]);	
	$carat = str_replace(",","",$_GET['carat'.$i]);	
	
	$caratperbutir = $carat / $butir ;
	
	$tsqlitem = "select count(*)coqty from msstone where  m_shape = '".$shape."' and m_size = '".$size."'and m_min >= ".$caratperbutir." and m_max <= ".$caratperbutir."
	
				
				";
	$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
	$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);

	$status = $rowitem['coqty'] ;
	
	
	
	

	
?>
<input type="text" id="cek_status" name="cek_status" value="<?php echo '1'; ?>" />
<input type="text" id="cek_tsql" name="cek_tsql" value="<?php echo '2'; ?>" />