<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tprog = $_POST['m_program'];
	$tkode = $_POST['m_kode'];
	$tnama = $_POST['m_nama'];
	$tobject = $_POST['m_object'];
	$tstatus = $_POST['m_status'];
	$tsubmenu = $_POST['m_submenu'];
	$turutan = $_POST['m_urutan'];
	$prm = $_POST['param'];
	$tnew = $_POST['m_new'];
	
	if ($tnew == '')
	{
		$tsql = "insert into msmenu (m_program, m_kode, m_nama, m_object, m_status, m_submenu, m_urutan) values ('".$tprog."','".$tkode."', '".$tnama."', '".$tobject."', '".$tstatus."', '".$tsubmenu."', ".$turutan.")" ;
	}
	else
	{
		$tsql = "
			update 	msmenu 
			set 	m_nama = '".$tnama."', 
					m_object = '".$tobject."', 
					m_status = '".$tstatus."', 
					m_submenu = '".$tsubmenu."',
					m_urutan = '".$turutan."'
			where	m_program = '".$tprog."' and 
					m_kode = '".$tkode."'
			";
	}
	echo $tsql ;
	$stmt = sqlsrv_query($con_dbnew, $tsql);

	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: msmenu.php?prm=".base64_encode($prm));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>