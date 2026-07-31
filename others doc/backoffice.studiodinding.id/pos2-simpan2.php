<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$prm = $_POST['param'];
	$periode  = $_POST['periode'];
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$cara = $_POST['m_carabayar'];	
	$edc = $_POST['m_edc'];
	$bank = $_POST['m_bank'];
	$nokartu = $_POST['m_nokartu'];
	$nmkartu = $_POST['m_nmkartu'];
	$jnkartu = $_POST['m_jnkartu'];
	$cclkartu = $_POST['m_cclkartu'];
	
	$jumlah = str_replace(",","",$_POST['m_jumlah']);	
	$mdr = str_replace(",","",$_POST['m_mdr']);	
	
	//Select Nomor Sales Order Jaws
	$tsql_jaws = "select m_jaws from t_pos where m_nomor = '".$tnomor."' ";
	$stmt_jaws= sqlsrv_query( $con_dbnew, $tsql_jaws);
	$rowjaws = sqlsrv_fetch_array( $stmt_jaws, SQLSRV_FETCH_ASSOC);
	
	$nojaws = $rowjaws ['m_jaws'];
	
	
	
	$tsql = "insert into t_pos3 (m_cabang, m_nomor, m_tanggal, m_carabayar, m_edc, m_bank, m_nokartu, m_nmkartu, m_jumlah, m_mdr, m_jnkartu, m_cclkartu) 
			values('".$tcabang."','".$tnomor."', getdate(), '".$cara."','".$edc."','".$bank."','".$nokartu."','".$nmkartu."',".$jumlah.",".$mdr.",'".$jnkartu."','".$cclkartu."')" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	//Insert Ke table SalesReceiptDetail
	
	
	$tketlog = 'PAYMEN';
	$tmenu = 'R10000';
	$tuser = $_SESSION['loginid'];
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);
	header("Location: pos.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>