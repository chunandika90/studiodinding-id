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
	
	
	$kode = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$harga = $_POST['m_harga'];
	
	/*
	$tgl = $_POST['m_tanggal'];

	
		$tgl = date("Y/m/d") ;
		$jam = date("H:i:s") ;
		
		$tgl =$tgl.' '.$jam;
	*/

	if ($tnew <> 'T')
	{
		$tsql = "insert into master_part (m_kode, m_nama, m_harga) values ('".$kode."','".$nama."',".$harga.")" ;
	}
	else
	{
		
		$tsql = "
			update 	master_part 
			set 	m_kode = '".$kode."', m_nama = '".$nama."', m_harga = ".$harga."
			where	m_kode = '".$kode."' ";
	}
	$stmt = $con_dbnew->query($tsql);
	
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n";
		 die( print_r( query(), true));
	}

	
	header("Location: master_part.php?prm=".base64_encode($prm));
?>