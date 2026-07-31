<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tuserid = $_POST['m_login'];
	$tusername = $_POST['m_nama'];
	$tuserpass = $_POST['m_password'];
	$tgroup = $_POST['m_group'];
	$tstatus = $_POST['m_status'];
	$tnew = $_POST['m_new'];
	$prm = $_POST['param'];
	
	if ($tnew == '')
	{
		$tsql = "insert into msuser (m_login, m_nama, m_password, m_group, m_status) values ('".$tuserid."', '".$tusername."', '".$tuserpass."', '".$tgroup."', 'A')" ;
	}
	else
	{
		$tsql = "
			update 	msuser 
			set 	m_nama = '".$tusername."', 
					m_password = '".$tuserpass."', 
					m_group = '".$tgroup."', 
					m_status = '".$tstatus."'
			where	m_login = '".$tuserid."'
			";
	}
	$stmt = $con_dbnew->query($tsql);	
//echo $tsql ;
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: msuser.php?prm=".base64_encode($prm));
	}
?>