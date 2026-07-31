R<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';
	$tkode = $_POST['m_kode'];
	$tnama = $_POST['m_nama'];
	$talamat = $_POST['m_alamat'];
	$ttelepon1 = $_POST['m_telepon1'];
	$prm = $_POST['param'];

	$tnew = $_POST['m_new'];
	
	if ($tnew == '')
	{
		
		if ($tkode == '')
		{
			$tsqlnosupl = "select max(right(m_kode,6)) as nomormax from mssupplier where left(m_kode,1) = left('".$tnama."',1) ";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000000' ;}
			$nomax = $nomax + 1 ;
		
			$tkodesupl = substr($tnama,0,1).'0'.substr('000000'.$nomax,-6) ;
		
			$tsql = "insert into mssupplier (m_kode, m_nama, m_alamat, m_telepon1) values ('".$tkodesupl."', '".$tnama."', '".$talamat."', '".$ttelepon1."')" ;
		
		}
	}
	else
	{
		$tsql = "
			update 	mssupplier 
			set 	m_nama = '".$tnama."', 
					m_alamat1 = '".$talamat."', 
					m_telepon1 = '".$ttelepon1."'
			where	m_kode = '".$tkode."'
			";
	}
	
	//echo $tsql;
	$stmt = sqlsrv_query($con_dbnew, $tsql);
//	echo $tsql ;
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: mssupplier.php?prm=".base64_encode($prm)."&cb=".base64_encode($tkode));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>