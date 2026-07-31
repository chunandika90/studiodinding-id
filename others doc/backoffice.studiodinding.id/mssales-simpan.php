<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tkode = $_POST['m_kode'];
	$tnama = $_POST['m_nama'];
	$tcabang = $_POST['m_cabang'];
	$tlogin = $_POST['m_login'];
	$taktif = $_POST['m_aktif'];
	$tnew = $_POST['m_new'];
	$prm = $_POST['param'];
	
	if ($tnew == '')
	{
		$tsql = "insert into mssales (m_kode, m_nama, m_cabang, m_login, m_aktif) values ('".$tkode."', '".$tnama."', '".$tcabang."', '".$tlogin."', ".$taktif.")" ;
	}
	else
	{
		$tsql = "
			update 	mssales 
			set 	m_nama = '".$tnama."', 
					m_cabang = '".$tcabang."', 
					m_login = '".$tlogin."', 
					m_aktif = '".$taktif."'
			where	m_kode = '".$tkode."'
			";
	}
	$stmt = sqlsrv_query($con_dbnew, $tsql);

	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: mssales.php?prm=".base64_encode($prm)."&cb=".base64_encode($tcabang));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>