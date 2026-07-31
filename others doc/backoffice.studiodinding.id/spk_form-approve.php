<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tcabang = base64_decode($_GET['cb']);
	$tnomor = base64_decode($_GET['nm']);
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);


	//BATALIN SPK
	$tsqlttb = " update t_spk set m_approval = '".$_SESSION['loginid']."', m_tanggal_approval = getdate() where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' 
				";
				
	echo $tsqlttb;
	$stmtttb = sqlsrv_query( $con_dbnew, $tsqlttb);
			
	
	
	$tmenu = 'M10000';
	$tuser = $_SESSION['loginid'];
	$tketlog = 'DELETE';
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);
	header("Location: spk_form.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>
