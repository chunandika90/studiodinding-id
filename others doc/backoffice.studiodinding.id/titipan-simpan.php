<?php
	session_start();
	set_time_limit(0) ;
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$periode  = $_POST['periode'];
	$prm = $_POST['param'];

	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$talamat = $_POST['m_alamat'];
	$tkota = $_POST['m_kota'];
	$ttelepon1 = $_POST['m_telepon1'];
	$ttelepon2 = $_POST['m_telepon2'];
	$tket = $_POST['m_keterangan'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	// Cek data Customer Dulu
	if ($tkodecust == '')
	{
		$tsqlnocust = "select max(right(m_kode,6)) as nomormax from mscustomer where left(m_kode,2) = '".$tcabang."' and substring(m_kode,4,2) = '".substr($abc[2],-2)."'";
		$stmtnocust= sqlsrv_query( $con_dbnew, $tsqlnocust);
		$rownocust = sqlsrv_fetch_array( $stmtnocust, SQLSRV_FETCH_ASSOC);
		$nomax = $rownocust['nomormax'];
		if ($nomax == ''){$nomax = '000000' ;}
		$nomax = $nomax + 1 ;

		$tkodecust = $tcabang.'-'.substr($abc[2],-2).substr('000000'.$nomax,-6) ;
		$tsqlcust = "insert into mscustomer ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status, m_tmplahir, m_tgllahir, m_agama, m_cabang, m_kodesales, m_member, m_tglmember )
					values ( '".$tkodecust."', '".$tkodecust."', '".$tnama."', '".$talamat."', '".$tkota."', '".$ttelepon1."', '".$ttelepon2."', '', '', '', '00', '', '1900/01/01', '', '".$tcabang."', '".$tsales."', '', '1900/01/01' )" ;
		
		$stmtcust = sqlsrv_query( $con_dbnew, $tsqlcust);
	}
	else
	{
		$tsqlcust = "	update 	mscustomer 
						set		m_nama = '".$tnama."', m_alamat = '".$talamat."', m_kota = '".$tkota."', m_telepon1 = '".$ttelepon1."', m_telepon2 = '".$ttelepon2."'
						where 	m_kode = '".$tkodecust."'";
		
		$stmtcust = sqlsrv_query( $con_dbnew, $tsqlcust);
	}
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_titipan where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'TP'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_titipan (m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."')" ;
		
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_titipan set m_keterangan = '".$tket."' 
				 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';

//Detail
	for ($i = 1; $i <= $jumrow; $i++) 
	{	
		$tkdbrg = $_POST['m_kodebarang'.$i];
		$tnoplu = $_POST['m_productid'.$i];
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$tharga = str_replace(",","",$_POST['m_harga'.$i]);	
		
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		
		if ($tnoplu != '')
		{
			if ( $new == 'Y' )
			{
				
				//Insert table pos2
				$sql_insert = "insert into t_titipan2
								(m_cabang, m_nomor, m_kodebarang, m_productid, m_qty, m_harga, m_status)
								 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."',".$tqty.",".$tharga.",'T')";
				
				echo $sql_insert;
				$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);		
				
				
			}
			else
			{
				if ($hapus == 'on')
				{
					//Hapus data transfer
					$sql_hapus = "delete from t_titipan2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
				else
				{
					/*
					$sql_updatepos = "	update t_titipan2 set m_disc = ".$tdisc.", m_discount = ".$tdiscount.",
										m_disc2 = ".$tdisc2.", m_discount2 = ".$tdiscount2.",
										m_disc3 = ".$tdisc3.", m_discount3 = ".$tdiscount3.", m_discount4 = ".$tdiscount4." 
										where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and 
										m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatepos = sqlsrv_query( $con_dbnew, $sql_updatepos);
					*/
				}
			}
		}
	}

	$tmenu = 'R10000';
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);


	sqlsrv_close($con_dbnew);
	//header("Location: titipan.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>