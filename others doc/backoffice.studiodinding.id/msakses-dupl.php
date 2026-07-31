<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$userid = base64_decode($_GET['lg']);
	$prog = base64_decode($_GET['pg']);
	$idfrom = base64_decode($_GET['fr']);
	$prm = base64_decode($_GET['prm']);

	$tsqldel = "delete from msakses where m_program = '".$prog."' and m_login = '".$userid."' "; 
	$stmtdel = sqlsrv_query($con_dbnew, $tsqldel);

	$tsqlsync = "	insert into msakses ( m_login, m_program, m_kode, m_akses, m_add, m_edit, m_delete, m_print )
						select 	'".$userid."', '".$prog."', m_kode, m_akses, m_add, m_edit, m_delete, m_print
						from 	msakses
						where 	m_program = '".$prog."' and m_login = '".$idfrom."'";
	$stmtsync = sqlsrv_query($con_dbnew, $tsqlsync);

	if( $stmtsync === false)
	{
		 echo "Error in query preparation/execution.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: msuser-akses.php?lg=".$_GET['lg']."&pg=".$_GET['pg']."&prm=".$_GET['prm']);
	}
	sqlsrv_next_result($stmtsync);
	sqlsrv_free_stmt( $stmtsync);	
	sqlsrv_close($conn);
?>