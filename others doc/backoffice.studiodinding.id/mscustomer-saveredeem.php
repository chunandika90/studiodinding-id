<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbcmk.php";
	
	$treturn = '                              ';

	$prm = $_POST['param'];
	
	$stedit =  $_POST['stedit'];
	$kdcab = $_SESSION['store'];
	$kode = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$note = $_POST['m_keterangan'];
	$tpvcr = $_POST['m_typevcr'];
	$dumb = explode('-',$_POST['m_point']);
	$point = str_replace(",","",$dumb[0]);
	$jumlah = str_replace(",","",$_POST['m_jumlah']);

	$tgl = date("Y/m/d H:i:s") ;
	$ym = date("ym");


	$tsqlmem = "select max(right(m_nomor,6)) as memmax from t_redeem where substring(m_nomor,5,4) = '".$ym."'";
	$stmtmem = sqlsrv_query( $con_dbcmk, $tsqlmem);
	$rowmem = sqlsrv_fetch_array( $stmtmem, SQLSRV_FETCH_ASSOC);
	$nomem = $rowmem['memmax'];
	if ($nomem == ''){$nomem = '000000' ;}
	$nomem = $nomem + 1 ;
	
	$nomor = 'CMK-'.$ym.'-'.substr('000000'.$nomem,-6) ;

	$tsql = "	insert into t_redeem ( m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status, m_post, m_point, m_jumlah, m_typevoucher, m_issuer )
				values ('".$kdcab."', '".$nomor."', '".$tgl."', '".$kode."', '".$nama."', '".$note."', 'A', '', ".$point.", ".$jumlah.",'".$tpvcr."','".$_SESSION['loginid']."') ";
	$stmt = sqlsrv_query($con_dbcmk, $tsql);
	if( $stmt === false )
	{
		 echo "Error in executing statement 3.\n";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		header("Location: mscustomer.php?st=".base64_encode($_POST['kdstore'])."&sl=".base64_encode($_POST['kdsales'])."&prm=".base64_encode($prm));
	}

	sqlsrv_next_result($stmt);
	sqlsrv_free_stmt( $stmt);
	sqlsrv_close( $con_dbcmk);
?>