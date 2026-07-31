<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';

	$periode  = $_POST['periode'];
	$nomor = $_POST['m_nomor']; 
	$tanggal = $_POST['m_tanggal'];
	$keterangan = $_POST['m_keterangan'];
	$totaljual = str_replace(",","",$_POST['m_totaljual']);

	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");


	// Kalau baru, create nomor POS 
	if ($nomor == '')
	{
		$tketlog = 'ADD';
		$nomor = $abc[2].$abc[1].$abc[0] ;
		
		$tsql = "insert into mshargalm (m_nomor, m_tanggal, m_keterangan, m_totaljual) 
				values('".$nomor."','".$tgl."','".$keterangan."',".$totaljual.")" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update mshargalm set m_keterangan = '".$keterangan."', m_totaljual = ".$totaljual." where m_nomor = '".$nomor."'";	
	}

	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = $_POST['m_kode'.$i];
		$tbeli = str_replace(",","",$_POST['m_beli'.$i]);	
		$tjual = str_replace(",","",$_POST['m_jual'.$i]);	
		$tjual2 = str_replace(",","",$_POST['m_jual2'.$i]);	
		$tmodal = str_replace(",","",$_POST['m_modal'.$i]);	
		$tbeli2= str_replace(",","",$_POST['m_beli2'.$i]);	
		$tjualb = str_replace(",","",$_POST['m_jualb'.$i]);	

		$new = $_POST['m_new'.$i];
		
		if ( $new == 'Y' )
		{
			$sql_insert = "insert into mshargalm2
							(m_nomor, m_kode, m_beli, m_jual, m_jual2, m_modal, m_beli2, m_jualb, m_buffer)
							 values('".$nomor."','".$tkdbrg."',".$tbeli.",".$tjual.",".$tjual2.",".$tmodal.",".$tbeli2.",".$tjualb.", 80)";

			$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);				
		}
		else
		{
			$sql_updatepos = "	update mshargalm2 set m_beli = ".$tbeli.", m_jual = ".$tjual.", m_jual2 = ".$tjual2.", m_modal = ".$tmodal.", m_beli2 = ".$tbeli2.", m_jualb = ".$tjualb." where m_nomor = '".$nomor."' and m_kode = '".$tkdbrg."'";

			$stmt_updatepos = sqlsrv_query( $con_dbnew, $sql_updatepos);
		}
	}

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
	header("Location: mshargalm.php?pr=".base64_encode($_POST['periode'])."&prm=".base64_encode($prm));

?>