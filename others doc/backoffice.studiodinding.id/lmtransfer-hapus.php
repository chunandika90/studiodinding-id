<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tcabang = base64_decode($_GET['cb']);
	$tnomor = base64_decode($_GET['nm']);
	$kdstore = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);

	$tsql = "{call sp_transfer_batal(?,?,?)}";
	$params = array(
					array($treturn, SQLSRV_PARAM_OUT),
					array($tcabang, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN)
					);
	$stmt = sqlsrv_query( $con_dbnew, $tsql, $params);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		


	$tmenu = 'M10000';
	$tuser = $_SESSION['loginid'];
	$tketlog = 'DELETE';
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);
	header("Location: lmtransfer.php?st=".$_GET['cb']."&pr=".$_GET['pr']."&prm=".$_GET['prm']);

?>
