<?php
ob_start();
	session_start();
	set_time_limit(0) ;
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$periode  = $_POST['periode'];
	$prm = $_POST['param'];

	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodeproject = $_POST['m_kode_project'];
	$tnamaproject = $_POST['m_nama_project'];
	$tketerangan = $_POST['m_keterangan'];
	$m_surveyor = $_POST['m_surveyor'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	
	
	//echo $tanggal ."<br>"; 
	
	$abc = explode('-', substr($tanggal, 0, 10)); // potong 10 char pertama
	if (count($abc) == 3 && is_numeric($abc[0]) && is_numeric($abc[1]) && is_numeric($abc[2])) {
		// YYYY-MM-DD
		$tahun  = $abc[0];
		$bulan  = $abc[1];
		$hari   = $abc[2];
		$tgl    = "$tahun/$bulan/$hari " . date("H:i:s");
	} else {
		// fallback pakai hari ini
		$tahun = date("Y");
		$bulan = date("m");
		$hari  = date("d");
		$tgl   = "$tahun/$bulan/$hari " . date("H:i:s");
	}
	
	
	// Kalau baru, create nomor POS 
	
	//echo $tnomor ."<br>";
	
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_survey where year(m_tanggal) = ".$tahun." and month(m_tanggal) = ".$bulan;
		
		//echo $tsqlnomor ."<br>";
		$stmtnomor = $con_dbnew->query($tsqlnomor);
		$rownomor = $stmtnomor->fetch_assoc();
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'CK'.$tahun.$bulan.substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_survey (m_nomor, m_tanggal, m_kode_project, m_nama_project, m_keterangan, m_status, m_surveyor) 
				values('".$tnomor."','".$tgl."','".$tkodeproject."','".$tnamaproject."','".$tketerangan."','".$tstatus."','".$m_surveyor."')" ;
		
		//$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_survey set m_keterangan = '".$tketerangan."', m_surveyor = '".$m_surveyor."'
				 where m_nomor = '".$tnomor."'";
	}
	//echo $tsql."<br>";
	$stmt = $con_dbnew->query($tsql);
	
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n".$tsql;
		 die( print_r( sqlsrv_errors(), true));
	}
	
	

	$tmenu = 'R10000';

	ob_end_flush();
	$con_dbnew->close();	
	header("Location: t_survey.php?pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>