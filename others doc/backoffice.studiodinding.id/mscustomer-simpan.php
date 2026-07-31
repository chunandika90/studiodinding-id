<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$treturn = '                              ';

	$tgllahir = $_POST['m_tgllahir'];
	$tglmember = $_POST['m_tglmember'];
	$prm = $_POST['param'];
	
	$stedit =  $_POST['stedit'];
	$kode = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$alamat = $_POST['m_alamat'];
	$kota = $_POST['m_kota'];
	$telepon1 = $_POST['m_telepon1'];
	$telepon2 = $_POST['m_telepon2'];
	$fax = $_POST['m_fax'];			
	$email = $_POST['m_email'];
	$status = $_POST['m_status'];
	$tmplahir = $_POST['m_tmplahir'];
	$agama = $_POST['m_agama'];
	$cabang = $_POST['m_cabang'];
	$kodesales = $_POST['m_kodesales'];
	$member = $_POST['m_member'];
	$pinbb = $_POST['m_pinbb'];
	$typemember = $_POST['m_typemember'];
	$tgl = date("d/m/Y") ;
	$tnew = $_POST['m_new'];
	$tgllhr = '1900/01/01';
	$tglmbr = '1900/01/01';
	
	if ($tgllahir != '')
	{
		$abc = explode('/',substr($tgllahir, 0, 10));
		$tgllhr = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
	}
	
	if ($tnew == '')
	{
		$tsqlnomor = "select max(right(m_kode,6)) as nomormax from dbcmkinventory.dbo.mscustomer where left(m_kode,2) = '".$cabang."' and substring(m_kode,4,2) = '".substr($tgl,-2)."'";
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '000000' ;}
		$nomax = $nomax + 1 ;
		
		$kode = $cabang.'-'.substr($tgl,-2).substr('000000'.$nomax,-6) ;
		$tsql = "	insert into dbcmkinventory.dbo.mscustomer ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status, m_tmplahir, m_tgllahir, m_agama, m_cabang, m_kodesales, m_member, m_tglmember, m_pinbb, m_typemember )
		 			values ( '".$kode."', '".$kode."', '".$nama."', '".$alamat."', '".$kota."', '".$telepon1."', '".$telepon2."', '".$fax."', '".$email."', '', '".$status."', '".$tmplahir."', '".$tgllhr."', '".$agama."', '".$cabang."', '".$kodesales."', '".$member."', '".$tglmbr."', '".$pinbb."', '".$typemember."' )" ;

		$tsql2 = "	insert into mscustomer ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status, m_tmplahir, m_tgllahir, m_agama, m_cabang, m_kodesales)
		 			values ( '".$kode."', '".$kode."', '".$nama."', '".$alamat."', '".$kota."', '".$telepon1."', '".$telepon2."', '".$fax."', '".$email."', '', '".$status."', '".$tmplahir."', '".$tgllhr."', '".$agama."', '".$cabang."', '".$kodesales."' )" ;
	}
	else
	{
		$tsql2 = "	update mscustomer 
					set m_group = '".$kode."',
						m_nama = '".$nama."',
						m_alamat = '".$alamat."',
						m_kota = '".$kota."',
						m_telepon1 = '".$telepon1."',
						m_telepon2 = '".$telepon2."',
						m_fax = '".$fax."',
						m_email = '".$email."',
						m_npwp = '',
						m_status = '".$status."',
						m_tmplahir = '".$tmplahir."',
						m_tgllahir = '".$tgllhr."',
						m_agama = '".$agama."',
						m_cabang = '".$cabang."',
						m_kodesales = '".$kodesales."'
					where m_kode = '".$kode."'";

	}
	$stmt2 = sqlsrv_query($con_dbnew, $tsql2);
	
	if( $stmt2 === false )
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
	sqlsrv_close( $con_dbnew);
?>