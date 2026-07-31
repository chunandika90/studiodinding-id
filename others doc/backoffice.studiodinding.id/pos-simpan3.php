<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$periode  = $_POST['periode'];
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$prm = $_POST['param'];
	
	$cancelnote = $_POST['m_cancelnote'];	
	$cancelby = $_SESSION['loginid'];

	$tsql = "{call sp_pos_batal(?,?,?,?,?)}";
	$params = array(
					array($treturn, SQLSRV_PARAM_OUT),
					array($tcabang, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($cancelby, SQLSRV_PARAM_IN),
					array($cancelnote, SQLSRV_PARAM_IN)
					);
	$stmt = sqlsrv_query( $con_dbnew, $tsql, $params);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		

	$tketlog = 'BATAL';
	$tmenu = 'R10000';
	$tuser = $_SESSION['loginid'];
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);
	header("Location: pos.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>