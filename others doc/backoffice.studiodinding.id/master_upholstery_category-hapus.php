<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$vkode = $_GET['vkode'];

	$tsql = "delete from master_upholstery_category where m_kode = '".$vkode."'   " ;
	$stmt = $con_dbnew->query($tsql);
	

?>
