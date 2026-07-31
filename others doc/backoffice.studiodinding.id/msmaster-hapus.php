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

	$tsql = "update msmaster set m_status = 'D' where m_type = '".$type."' and m_kode = '".$kode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

?>
