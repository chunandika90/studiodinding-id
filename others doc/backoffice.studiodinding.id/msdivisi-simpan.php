<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

   include "mssql-dbnew.php" ;
	
	$treturn = '                              ';
	
	$jumrow = $_POST['jumrow'];
	$taed = $_POST['aed'];
	$loginid = $_POST['loginid'];
	
	// Simpan Headernya dulu
	$kode = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$dept = $_POST ['kddept'];
	$head = $_POST ['m_head'];
	$prm = $_POST['param'];
	$aed = 'E';	
	if ($kode=='')	{ $aed = 'A'; $kode = '          '; }
	
	$tsql_callSP = "{call sp_savemsdivisi(?,?,?,?,?,?)}";
	$params = array(
					array($treturn, SQLSRV_PARAM_OUT),
					array($aed, SQLSRV_PARAM_IN),
					array($kode, SQLSRV_PARAM_INOUT),
					array($nama, SQLSRV_PARAM_IN),	
					array($dept, SQLSRV_PARAM_IN),	
					array($head, SQLSRV_PARAM_IN)
					);

	$stmt = sqlsrv_query( $con_dbnew, $tsql_callSP, $params);
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}	
	$next_result = sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);


	// Simpan detail
	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$treturn = '                              ';
		if (($_POST['del'.$i]<>'Y') || ($_POST['newrec'.$i]<>'Y'))
		{
			$kdsub = $_POST['m_kode'.$i];
			$nmsub = $_POST['m_nama'.$i];
			$kddiv = $kode;

			if ($_POST['del'.$i] == 'Y')
			{ $taed = 'D' ;}
			else
			{ $taed = 'A' ;}
			
			
			
		
			$tsql_callSP2 = "{call sp_savemsdivisi2(?,?,?,?,?)}";
			$params2 = array(
							array($treturn, SQLSRV_PARAM_OUT),
							array($taed, SQLSRV_PARAM_IN),
							array($kdsub, SQLSRV_PARAM_INOUT),
							array($nmsub, SQLSRV_PARAM_IN),
							array($kddiv, SQLSRV_PARAM_IN)
							);
		
			$stmt2 = sqlsrv_query( $connm, $tsql_callSP2, $params2);
		/*	if( $stmt2 === false )
			{
				 echo "Error in executing statement 3.\n";
				 die( print_r( sqlsrv_errors(), true));
			}
		*/			
			sqlsrv_next_result($stmt2);
			sqlsrv_free_stmt( $stmt2);	
		}		
	
	}

	sqlsrv_close( $connm);
	header("Location: msdivisi.php?dp=".base64_encode($dept)."&prm=".base64_encode($prm)."&div=".base64_encode($kode));
?>
