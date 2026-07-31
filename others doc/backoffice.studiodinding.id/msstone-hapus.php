<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$shape = $_GET['vshape'];
	$size = $_GET['vsize'];

	$tsql = "delete from msstone where m_shape = '".$shape."' and m_size = '".$size."'  " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);


?>
