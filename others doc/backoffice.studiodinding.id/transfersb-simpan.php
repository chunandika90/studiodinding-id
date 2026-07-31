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
	$tlokasi = $_POST['m_lokasi'];
	$tlokasi2 = $_POST['m_lokasi2'];
	$tanggal = $_POST['m_tanggal'];	
	$tspk = $_POST['m_spk'];
	$ttukang = $_POST['m_tukang'];
	$tket = $_POST['m_keterangan'];
	$jumrow = $_POST['jumrow'];
	$jumrow2 = $_POST['jumrow2'];
	$status = 'A';
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	
	echo $jumrow2."<br>";
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax 
					  from t_transfersb 
					  where m_cabang = '".$tlokasi."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'SB'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_transfersb (m_cabang, m_nomor,m_tanggal, m_spk, m_tukang, m_keterangan, m_status, m_cabang2) 
				values('".$tlokasi."','".$tnomor."','".$tgl."','".$tspk."','".$ttukang."','".$tket."','".$status."','".$tlokasi2."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_transfersb set m_keterangan = '".$tket."', m_spk = '".$tspk."', m_tukang = '".$ttukang."'
				where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	
	
	for ($b = 1; $b <= $jumrow2; $b++) 
	{
		
		$shape = $_POST['m_shape'.$b];	
		$size = $_POST['m_size'.$b];	
		$dimensi = $_POST['m_dimensi'.$b];	
		$dimensi2 = $_POST['m_dimensib'.$b];	
		$dimensi3 = $_POST['m_dimensic'.$b];	
		$gia = $_POST['m_gia'.$b];	
		
		$butir = str_replace(",","",$_POST['m_butir'.$b]);	
		$carat = str_replace(",","",$_POST['m_carat'.$b]);	
		
		
		$no = $_POST['m_no'.$b];
		$new = $_POST['m_new'.$b];
		$hapus = $_POST['m_hapus'.$b];
		
		
		if ($size != '')
		{
			if ( $hapus != 'on' )
			{
				if ( $new == 'Y' )
				{
				//insert ke table ttb3
					$sql_ttb   = "insert into t_transfersb2 (m_nomor, m_no, m_shape, m_size, m_dimensi, m_dimensi2, m_dimensi3, m_gia, m_butir, m_carat, m_cabang, m_cabang2)  
								 values('".$tnomor."','".$no."','".$shape."','".$size."','".$dimensi."','".$dimensi2."','".$dimensi3."','".$gia."',".$butir.",".$carat.",'".$tlokasi."','".$tlokasi2."')" ;
					
				}
				else
				{
					$sql_ttb =  "update t_transfersb2 set m_carat = ".$carat." ,m_butir = ".$butir." ,m_dimensi = '".$dimensi."',
								 m_dimensi2 = '".$dimensi2."', m_dimensi3 = '".$dimensi3."', m_gia = '".$gia."'
								 where m_cabang = '".$tcabang."' and m_shape = '".$shape."' and m_size = '".$size."' and m_no = '".$no."' ";
				}
			}
			else
			{
					$sql_ttb = "delete t_transfersb2  where m_nomor = '".$tnomor."'and m_shape = '".$shape."' and m_size = '".$size."' and m_no = '".$no."' ";
					
			}
		}
			echo $sql_ttb."<br>";
			$stmt_ttb  = sqlsrv_query( $con_dbnew, $sql_ttb);
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
	header("Location: transfersb.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>