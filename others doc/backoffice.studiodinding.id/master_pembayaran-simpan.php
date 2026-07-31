<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tnew = $_POST['m_new'];
	$prm  = $_POST['param'];
	
	$kode = $_POST['m_kode']; // ini kalo lo ada input manual, tp nanti kita overwrite klo insert baru
	$nama = $_POST['m_nama'];

	if ($tnew <> 'T') 
	{ 

		// === INSERT BARU ===
		$sqlMax = "SELECT MAX(m_kode) as last_kode FROM master_term_pembayaran";
		$resMax = $con_dbnew->query($sqlMax);
		$rowMax = $resMax->fetch_assoc();

		if ($rowMax && $rowMax['last_kode'] != null) {
			$lastKode = $rowMax['last_kode'];   // contoh: 03
			$num = intval($lastKode);           // ambil angka -> 3
			$newNum = $num + 1;                 // increment -> 4
			$newKode = str_pad($newNum, 2, "0", STR_PAD_LEFT); // jadi 04
		} else {
			$newKode = "01"; // default kalau belum ada data
		}
		
		$tsql = "INSERT INTO master_term_pembayaran (m_kode, m_nama) VALUES ('".$newKode."','".$nama."')" ;
		
	} 
	else 
	{ 
		// === UPDATE ===
		$tsql = "
			UPDATE  master_term_pembayaran 
			SET     m_nama = '".$nama."'
			WHERE   m_kode = '".$kode."' ";
	}

	$stmt = $con_dbnew->query($tsql);
	if( $stmt === false ) {
		echo "Error in executing statement.\n";
		die( print_r($con_dbnew->errorInfo(), true));
	}

	header("Location: master_pembayaran.php?prm=".base64_encode($prm));
?>
