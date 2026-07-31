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
	$tlokasi = $_POST['m_lokasi'];
	$prm = $_POST['param'];
	$tnew = $_POST['m_new'];

	echo $tkode ."<br>";
	echo $tnew ."<br>";
	
	
	if ($tnew == '')
	{
		
		if ($tkode == '')
		{
			$tsqlnosupl = "select max(right(m_kode,3)) as nomormax from mstukang where left(m_kode,1) = left('".$tnama."',1) ";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000' ;}
			$nomax = $nomax + 1 ;
		
			$tkode = 'T'.substr('000'.$nomax,-3) ;
		
			$tsql = "insert into mstukang (m_kode, m_nama, m_lokasi) values ('".$tkode."', '".$tnama."', '".$tlokasi."')" ;
		
		}
	}
	else
	{
		$tsql = "
			update 	mstukang 
			set 	m_nama = '".$tnama."', 
					m_lokasi = '".$tlokasi."'
			where	m_kode = '".$tnew."'
			";
	}
	
	//echo $tsql;
	$stmt = sqlsrv_query($con_dbnew, $tsql);
	//echo $tsql ;
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: mstukang.php?prm=".base64_encode($prm)."&cb=".base64_encode($tkode));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>