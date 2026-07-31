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
	$soid =  base64_decode($_GET['so']);
	
	$tsql = "update t_opname set m_status = 'B' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		
	echo $tsql ;

	$tmenu = 'M40000';
	$tuser = $_SESSION['loginid'];
	$tketlog = 'BATAL';
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);
	header("Location: opname.php?st=".$_GET['cb']."&pr=".$_GET['pr']."&so=".$_GET['so']);

?>
