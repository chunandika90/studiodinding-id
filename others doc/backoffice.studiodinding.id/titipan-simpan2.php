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

	$tsql = "update t_titipan set m_cancelnote = '".$cancelnote."' , m_cancelby = '".$cancelby."', m_canceldate = getdate(), m_status = 'B'
			 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' ";
	
	echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql, $params);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		

	sqlsrv_close($con_dbnew);
	header("Location: titipan.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>