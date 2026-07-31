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
	$prm = $_POST['param'];

	$tnew = $_POST['m_new'];
	
	if ($tnew == '')
	{
		$tsql = "insert into mslokasi (m_cabang, m_kode, m_nama) values ('00','".$tkode."', '".$tnama."')" ;
	}
	else
	{
		$tsql = "
			update 	mslokasi 
			set 	m_nama = '".$tnama."'
			where	m_kode = '".$tkode."'
			";
	}
	$stmt = sqlsrv_query($con_dbnew, $tsql);
//	echo $tsql ;
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: mscabang.php?prm=".base64_encode($prm)."&cb=".base64_encode($tkode));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>