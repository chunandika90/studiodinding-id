<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tnew = $_POST['m_new'];
	$prm = $_POST['param'];
	
	$shape = $_POST['m_shape'];
	$size = $_POST['m_size'];
	$ukuran = $_POST['m_ukuran'];
	$hargam = str_replace(",","",$_POST['m_hargam']);	
	$hargar = str_replace(",","",$_POST['m_hargar']);	
	$opbm = str_replace(",","",$_POST['m_opbm']);	
	$opbr = str_replace(",","",$_POST['m_opbr']);	
	$min = str_replace(",","",$_POST['m_min']);	
	$max = str_replace(",","",$_POST['m_max']);	
	$tgl = $_POST['m_tanggal'];

	
		$tgl = date("Y/m/d") ;
		$jam = date("H:i:s") ;
		
		$tgl =$tgl.' '.$jam;
	

	if ($tnew <> 'T')
	{
		$tsql = "insert into msstone (m_shape, m_size, m_ukuran, m_tanggal, m_hargam, m_hargar,m_opbm,m_opbr,m_min,m_max) values ('".$shape."','".$size."','".$ukuran."', '".$tgl."', ".$hargam.", ".$hargar.", ".$opbm.", ".$opbr.", ".$min.", ".$max.")" ;
	}
	else
	{
		
		$tsql = "
			update 	msstone 
			set 	m_hargam = ".$hargam.", m_hargar = ".$hargar.", m_tanggal = '".$tgl."', m_hargam = ".$hargam.", m_hargar = ".$hargar.", m_opbm = ".$opbm.", m_opbr = ".$opbr.", m_min = ".$min.", m_max = ".$max."
			where	m_size = '".$size."' and m_shape = '".$shape."' ";
	}
	echo $tsql ;
	$stmt = sqlsrv_query($con_dbnew, $tsql);
	
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( sqlsrv_errors(), true));
	}

	
	header("Location: msstone.php?kd=".base64_encode($ttype)."&prm=".base64_encode($prm));
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>