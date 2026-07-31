<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$prog = $_GET['prog'];
	$kode = $_GET['kode'];
	$prm = $_GET['prm'];

	$tsql = "delete from msmenu where m_program = '".$prog."' and m_kode = '".$kode."'" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);


?>
