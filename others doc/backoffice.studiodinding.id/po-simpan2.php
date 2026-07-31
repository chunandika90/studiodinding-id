<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$prm = $_POST['param'];
	$m_nomor = $_POST['m_nomor'];
	$m_type = $_POST['m_type'];	
	$m_carabayar = $_POST['m_carabayar'];	
	$m_tanggal = $_POST['m_tanggal'];
	$m_keterangan = $_POST['m_keterangan'];
	$m_nodoc = $_POST['m_nodoc'];
	
	// ubah dd/mm/yyyy -> yyyy-mm-dd
	if (!empty($m_tanggal)) {
		$tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
		$m_tanggal = $tgl->format('Y-m-d');
	} else {
		$m_tanggal = null; // kalau kosong
	}
	
	$m_jumlah = str_replace(",","",$_POST['m_jumlah']);	
	
	
	
	
	$tsql = "insert into t_po3 (m_nomor, m_tanggal, m_type, m_carabayar, m_keterangan, m_nodoc, m_jumlah) 
			values('".$m_nomor."','".$m_tanggal."','".$m_type."', '".$m_carabayar."','".$m_keterangan."','".$m_nodoc."',".$m_jumlah.")" ;
			
			//echo $tsql;
	$stmt = $con_dbnew->query($tsql);
	
	
	// update total pembayarn di header
	$tsqlcek = "
				UPDATE t_po a
				JOIN (
					SELECT m_nomor AS m_nomor, SUM(m_jumlah) AS m_jumlah
					FROM t_po3
					WHERE m_nomor = '".$m_nomor."'
					GROUP BY m_nomor
				) b ON a.m_nomor = b.m_nomor
				SET a.m_bayar = b.m_jumlah
						
				";
	$stmtcek = $con_dbnew->query($tsqlcek);
	
	

	$con_dbnew->close();
	header("Location: t_po.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));

?>