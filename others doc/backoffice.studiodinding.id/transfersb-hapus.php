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

	echo $tcabang . "<br>";
	echo $tnomor . "<br>";
	
	$tsql = " select b.m_kodebarang, b.m_productid , b.m_qty
			  from t_transfersb a, t_transfersb2 b
			  where a.m_nomor = b.m_nomor and 
			  		a.m_cabang = b.m_cabang and
					a.m_nomor = '".$tnomor."' and
					a.m_cabang = '".$tcabang."'
					
				";
				echo $tsql . "<br>";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	
	$productid = $row ['m_productid'];
	$kodebarang = $row ['m_kodebarang'];
	$qty = $row ['m_qty'];
	
	//MINUSIN STOCK
	$tsqlstock = " update t_stockinv set m_qty = m_qty - '".$qty."' where m_productid = '".$productid."' and m_kodebarang = '".$kodebarang."' 
		";
		echo $tsqlstock . "<br>";
	$stmtstock = sqlsrv_query( $con_dbnew, $tsqlstock);
	//BATALIN TTB
	$tsqlttb = " update t_transfersb set m_status = 'B' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' 
		";
		echo $tsqlttb . "<br>";
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
	header("Location: transfersb.php?st=".$_GET['cb']."&pr=".$_GET['pr']."&prm=".$_GET['prm']);

?>
