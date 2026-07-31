<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbcmk.php";
	$kode = base64_decode($_GET['kd']);
	$kdstore = base64_decode($_GET['st']);
	$kdsales  = base64_decode($_GET['sl']);
	$prm = base64_decode($_GET['prm']);
	
	$cekdulu = "select count(*) as jumrow from t_pos where m_kodecust = '".$kode."'" ;
	$stmtcek = sqlsrv_query( $con_dbcmk, $cekdulu);
	$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);
	if (($rowcek['jumrow'] == '') or ($rowcek['jumrow'] == 0))
	{
		$tsql = "delete from mscustomer where m_kode= '".$kode."' " ;
		$stmt = sqlsrv_query( $con_dbcmk, $tsql);
		if( $stmt === false )
		{
			 echo "Error in executing statement 3.\n";
			 die( print_r( sqlsrv_errors(), true));
		}
	}
	header("Location: mscustomer.php?st=".base64_encode($_POST['kdstore'])."&sl=".base64_encode($_POST['kdsales'])."&prm=".base64_encode($prm));

	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbcmk);

?>
