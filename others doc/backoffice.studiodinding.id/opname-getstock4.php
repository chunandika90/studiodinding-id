<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdcab = $_POST['m_cabang'];
	$tgl = $_POST['m_tanggal'];
	$nama = $_POST['m_nama'];
	$prm = $_POST['param'];
	$keterangan = $_POST['m_keterangan'];

	$abc = explode('/',substr($tgl, 0, 10));
	$tanggal = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.substr($tgl, -8);
	
	$tsqlnomor = "select max(right(m_nomor,3)) as nomormax from t_stockopname0 where year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
	$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
	$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
	$nomax = $rownomor['nomormax'];
	if ($nomax == ''){$nomax = '000' ;}
	$nomax = $nomax + 1 ;
	$nomor = $abc[2].$abc[1].substr('000'.$nomax,-3) ;

	$tsql = "	insert into t_stockopname0 ( m_cabang, m_nomor, m_tanggal, m_nama, m_keterangan, m_status )
				values ( '".$kdcab."', '".$nomor."', '".$tanggal."', '".$nama."', '".$keterangan."', 'A' )" ;
	$stmt = sqlsrv_query($con_dbnew, $tsql);

	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		// hapus dulu date stockopname
		$tsqlhapus = "delete from t_stockopname where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'";
		$stmt = sqlsrv_query($con_dbnew, $tsql);

		$tsqlinsert = "	insert into t_stockopname 
						select '".$kdcab."','".$nomor."', m_kodebarang, m_lokasi, m_productid, m_qty, m_status, '' from t_stockinv where m_cabang = '".$kdcab."' and m_qty > 0 ";
		$stmtinsert = sqlsrv_query($con_dbnew, $tsqlinsert);

		header("Location: opname-getstock.php?st=".base64_encode($_POST['kdstore'])."&pr=".base64_encode($_POST['periode'])."&prm=".base64_encode($_POST['param']));
	}
	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbnew);
?>