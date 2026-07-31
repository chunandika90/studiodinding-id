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

	if ($tnew <> 'T') { 
    // === INSERT BARU ===
    $sqlMax = "SELECT MAX(m_kode) as last_kode FROM master_item WHERE m_kode LIKE 'M%'";
    $resMax = $con_dbnew->query($sqlMax);
    $rowMax = $resMax->fetch_assoc();

    if ($rowMax && $rowMax['last_kode'] != null) {
        $lastKode = $rowMax['last_kode'];   // contoh: M0012
        $num = intval(substr($lastKode, 1)); // ambil angka -> 12
        $newNum = $num + 1;                  // increment -> 13
        $newKode = "M" . str_pad($newNum, 4, "0", STR_PAD_LEFT); // jadi M0013
    } else {
        $newKode = "M0001"; // default kalau belum ada data
    }

    $tsql = "INSERT INTO master_item (m_kode, m_nama) VALUES ('".$newKode."', '".$nama."')" ;
	} else { 
		// === UPDATE ===
		$tsql = "
			UPDATE  master_item 
			SET     m_nama = '".$nama."'
			WHERE   m_kode = '".$kode."' ";
	}

	$stmt = $con_dbnew->query($tsql);
	if( $stmt === false ) {
		echo "Error in executing statement.\n";
		die( print_r($con_dbnew->errorInfo(), true));
	}

	header("Location: master_item.php?prm=".base64_encode($prm));
?>
