<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$loginid = $_GET['logid'];

	$tsql = "update msuser set m_status = 'D' where m_login = '".$loginid."' " ;
	$stmt = $con_dbnew->query($tsql);

?>
