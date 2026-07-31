<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$ttype = $_GET['ty'];
	$tkode = $_GET['kd'];

	$tsql = "delete from msrate where m_kode = '".$ttype."' and m_tanggal = '".$tkode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);


?>
