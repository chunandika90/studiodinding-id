<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tkode = $_POST['m_kode'];
	$tnama = $_POST['m_nama'];
	$tsatuan = $_POST['m_satuan'];
	$tkateg = $_POST['m_kategori'];
	$tdesc = $_POST['m_desc'];
	$prm = $_POST['param'];

	$tnew = $_POST['m_new'];
	
	if ($tnew == '')
	{
		$tsql = "insert into msbarang (m_kode, m_nama, m_satuan, m_kategori, m_desc) values ('".$tkode."', '".$tnama."', '".$tsatuan."', '".$tkateg."', '".$tdesc."')" ;
	}
	else
	{
		$tsql = "
			update 	msbarang 
			set 	m_nama = '".$tnama."', 
					m_satuan = '".$tsatuan."', 
					m_kategori = '".$tkateg."', 
					m_desc = '".$tdesc."'
			where	m_kode = '".$tkode."'
			";
	}
	$stmt = sqlsrv_query($con_dbnew, $tsql);
//	echo $tsql ;
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: msbarang.php?prm=".base64_encode($prm));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>