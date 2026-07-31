<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$ttype = $_POST['m_type'];
	$tnew = $_POST['m_new'];
	$tkode = $_POST['m_kode'];
	$tnama = $_POST['m_nama'];
	$prm = $_POST['param'];

	if ($tnew == '')
	{
		$tsql = "insert into msmaster (m_type, m_kode, m_nama, m_status) values ('".$ttype."', '".$tkode."', '".$tnama."', 'A')" ;
	}
	else
	{
		$tsql = "
			update 	msmaster 
			set 	m_nama = '".$tnama."'
			where	m_type = '".$ttype."' and m_kode = '".$tkode."'
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
		header("Location: msmaster.php?kd=".base64_encode($ttype)."&prm=".base64_encode($prm));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>