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
	$tkodesupl = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$tlokasi = $_POST['m_lokasi'];
	$tket = $_POST['m_keterangan'];
	$tkdbrg = $_POST['m_kodebarang'];
	$ttype = $_POST['m_type'];
	$jumrow = $_POST['jumrow'];
	$jumrow2 = $_POST['jumrow2'];
	$status = 'A';
	
	$trate = $_POST['m_rate'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	
	echo $jumrow2."<br>";
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		// Cek dulu, suppliernya baru atau lama !!!
		if ($tkodesupl == '')
		{
			$tsqlnosupl = "select max(right(m_kode,6)) as nomormax from mssupplier where left(m_kode,1) = left('".$nama."',1) ";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000000' ;}
			$nomax = $nomax + 1 ;
		
			$tkodesupl = substr($nama,0,1).'0'.substr('000000'.$nomax,-6) ;
			$tsqlsupl = "insert into mssupplier ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status )
						values ( '".$tkodesupl."', 'LOKAL', '".$nama."', '', '', '', '', '', '', '', '00' )" ;
			$stmtsupl = sqlsrv_query( $con_dbnew, $tsqlsupl);

		}
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax 
					  from t_ttb_stone 
					  where m_cabang = '".$tlokasi."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'TS'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		if ($trate == '')
		{
			$trate = 0;
		}
		$tsql = "insert into t_ttb_stone (m_cabang, m_nomor,m_tanggal, m_supplier, m_keterangan, m_dosupplier, m_status, m_rate) 
				values('".$tlokasi."','".$tnomor."','".$tgl."','".$tkodesupl."','".$tket."','".$dosupplier."','".$status."',".$trate.")" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_ttb_stone set m_keterangan = '".$tket."', m_supplier = '".$tkodesupl."', m_rate = ".$trate."
				where m_cabang = '".$tlokasi."' and m_nomor = '".$tnomor."'";
	}
	//echo $tsql;
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
		$jumlah = str_replace(",","",$_POST['m_jumlah'.$b]);	
		$total = str_replace(",","",$_POST['m_total'.$b]);	
		
		
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
					$sql_ttb   = "insert into t_ttb_stone2 (m_nomor, m_no, m_shape, m_size, m_dimensi, m_dimensi2, m_dimensi3, m_gia, m_butir,m_carat, m_jumlah, m_total, m_cabang)  
								 values('".$tnomor."','".$no."','".$shape."','".$size."','".$dimensi."','".$dimensi2."','".$dimensi3."','".$gia."',".$butir.",".$carat.",".$jumlah.",".$total.",'".$tlokasi."')" ;
					
				}
				else
				{
					$sql_ttb =  "update t_ttb_stone2 set m_carat = ".$carat.", m_butir = ".$butir."  ,m_jumlah = ".$jumlah." , m_total = ".$total." ,m_dimensi = '".$dimensi."',
								 m_dimensi2 = '".$dimensi2."', m_dimensi3 = '".$dimensi2."'
								 where m_cabang = '".$tcabang."' and m_shape = '".$shape."' and m_size = '".$size."' and m_no = '".$no."' ";
				}
			}
			else
			{
					$sql_ttb = "delete t_ttb_stone2  where m_nomor = '".$tnomor."'and m_shape = '".$shape."' and m_size = '".$size."' and m_no = '".$no."' ";
					
			}
		}
		echo $sql_ttb."<br>";
			$stmt_ttb  = sqlsrv_query( $con_dbnew, $sql_ttb);
			$stmt_stock  = sqlsrv_query( $con_dbnew, $sql_stock);
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
	header("Location: ttb_stone.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>