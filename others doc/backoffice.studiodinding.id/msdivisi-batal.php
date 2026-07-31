<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$kddept = base64_decode($_GET['dp']);
	$kode = base64_decode($_GET['kd']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	// Cek dulu sisa yg belum dibayar
	$tsqlcek = "delete from msdivisi where m_dept = '".$kddept."' and m_kode = '".$kode."'";
	echo $tsqlcek;
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);

	// Cek dulu sisa yg belum dibayar
	$tsqlcek2 = "delete from msdivisi2 where m_divisi ='".$kode."'";
	$stmtcek2 = sqlsrv_query( $con_dbnew, $tsqlcek2);
	$rowcek2 = sqlsrv_fetch_array( $stmtcek2, SQLSRV_FETCH_ASSOC);
	
	header("Location: msdivisi.php?dp=".base64_encode($dept)."&prm=".base64_encode($prm));
	
?>

