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

	$tsql = "update t_titipan set m_kembalinote = '".$cancelnote."' , m_kembalidate = getdate()
			 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' ";
	$stmt = sqlsrv_query( $con_dbnew, $tsql, $params);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		
	
	$tsql2 = "	select 	a.*
				from 	t_titipan2 a 
				where 	a.m_cabang = '".$tcabang."' and 
						a.m_nomor = '".$tnomor."' " ;
	$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
	
	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
	{
		
		
		$tsql3 = "update t_titipan2 set m_status = 'Y' 
				 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and 
				 	   m_kodebarang = '".$row2['m_kodebarang']."' and m_productid = '".$row2['m_productid']."' ";
		//echo $tsql3;
		$stmt3 = sqlsrv_query( $con_dbnew, $tsql, $params);
		
	}

	sqlsrv_close($con_dbnew);
	header("Location: titipan.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>