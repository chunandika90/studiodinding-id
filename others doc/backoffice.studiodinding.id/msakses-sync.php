<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$userid = base64_decode($_GET['lg']);
	$prm = base64_decode($_GET['prm']);

	$tsqlsync = "	insert into msakses ( m_login, m_program, m_kode, m_akses, m_add, m_edit, m_delete, m_print )
						select 	'".$userid."', '01', m_kode, 'T','T','T','T','T' 
						from 	msmenu
						where 	m_kode not in ( select m_kode from msakses where m_login = '".$userid."'  )	";
	$stmtsync = $con_dbnew->query($tsqlsync);
	if( $stmtsync === false)
	{
		 echo "Error in query preparation/execution.\n";
	}
	else
	{
		header("Location: msuser-akses.php?lg=".$_GET['lg']."&prm=".$_GET['prm']);
	}
?>