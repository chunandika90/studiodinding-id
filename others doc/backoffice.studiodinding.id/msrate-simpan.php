<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$ttype = $_POST['m_kode'];
	$tnew = $_POST['m_new'];
	$tkode = $_POST['m_tanggal'];
	$prm = $_POST['param'];
	$tbeli = str_replace(",","",$_POST['m_beli']);	
	$tjual = str_replace(",","",$_POST['m_jual']);	

	if ($tkode == '')
	{
		$tgl = date("Y/m/d") ;
		$jam = date("H:i:s") ;
		
		$tgl =$tgl.' '.$jam;
	}

	if ($tnew == '')
	{
		$tsql = "insert into msrate (m_kode, m_tanggal, m_beli, m_jual) values ('".$ttype."', '".$tgl."', ".$tbeli.", ".$tjual.")" ;
	}
	else
	{
		
		$tsql = "
			update 	msrate 
			set 	m_beli = ".$tbeli.", m_jual = ".$tjual."
			where	m_kode = '".$ttype."' and m_tanggal = '".$tkode."'";
	}
	echo $tsql ;
	$stmt = sqlsrv_query($con_dbnew, $tsql);
	
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}

	
	header("Location: msrate.php?kd=".base64_encode($ttype)."&prm=".base64_encode($prm));
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>