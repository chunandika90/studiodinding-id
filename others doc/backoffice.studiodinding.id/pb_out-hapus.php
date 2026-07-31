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

	
	$tsql = " select b.m_kodebarang, b.m_productid , b.m_qty
			  from t_barang_in a, t_barang_in2 b
			  where a.m_nomor = b.m_nomor and 
			  		a.m_cabang = b.m_cabang and
					a.m_nomor = '".$tnomor."' and
					a.m_lokasi = '".$tcabang."'
					
				";
				echo $tsql . "<br>";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	
	$tlokasi2 = $row ['m_lokasi2'];
	$kodebarang = $row ['m_kodebarang'];
	$qty = $row ['m_qty'];
	
		//MINUSIN STOCK
		$tsqlstock = " update t_stockbarang set m_qty = dbo.f_hitbarang('".$tlokasi2."','".$tkodebarang."'), m_grossweight = dbo.f_hitbarang2('".$tlokasi2."','".$tkodebarang."') where m_lokasi = '".$tlokasi2."' and m_kodebarang = '".$kodebarang."' 
			";
		//echo $tsqlstock . "<br>";
		$stmtstock = sqlsrv_query( $con_dbnew, $tsqlstock);
		//BATALIN TTB
		$tsqlttb = " update t_barang_in set m_status = 'B', m_tglhapus = getdate(), m_hapus = '".$_SESSION['loginid']."' 
					 where m_lokasi = '".$tcabang."' and m_nomor = '".$tnomor."' 
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
	header("Location: barang_in.php?st=".$_GET['cb']."&pr=".$_GET['pr']."&prm=".$_GET['prm']);

?>
