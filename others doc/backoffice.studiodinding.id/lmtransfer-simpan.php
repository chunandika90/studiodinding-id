<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdstore = $_POST['kdstore'];
	$periode  = $_POST['periode'];
	$prm = $_POST['param'];

	$treturn = '                              ';
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tnama = $_POST['m_nama'];
	$tlokasi = $_POST['m_lokasi'];
	$tlokasi2 = $_POST['m_lokasi2'];
	$tket = $_POST['m_keterangan'];
	$tkurir = $_POST['m_kurir'];
	$tstatus = $_POST['m_status'];
	$tkodebrg = $_POST['m_kodebarang'];
	$jumrow = $_POST['jumrow'];


	$tsqlasal = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi."' ";
	$stmtasal= sqlsrv_query( $con_dbnew, $tsqlasal);
	$rowasal = sqlsrv_fetch_array( $stmtasal, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowasal['m_kode2']) ;
	$tasal = $dumb[0];
	$idasal = $dumb[1];
	$nmasal = $rowasal['m_nama'];
	
	$tsqltujuan = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi2."' ";
	$stmttujuan= sqlsrv_query( $con_dbnew, $tsqltujuan);
	$rowtujuan = sqlsrv_fetch_array( $stmttujuan, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowtujuan['m_kode2']) ;
	$ttujuan = $dumb[0];
	$idtujuan = $dumb[1];
	$nmtujuan = $rowtujuan['m_nama'];
	
	
	$abc = explode('/',substr($tanggal, 0, 10));

	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");

	// Kalau baru, create nomor Transfer 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_transfer where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ; $nojaws = '000000';}
		$nomax = $nomax + 1 ;
		$tnomor = 'TR'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_transfer (m_cabang, m_nomor, m_tanggal, m_nama, m_keterangan, m_status, m_lokasi, m_lokasi2, m_kurir,m_outid, m_kodebarang) values('".$tcabang."','".$tnomor."','".$tgl."','".$tnama."','".$tket."','".$tstatus."','".$tlokasi."','".$tlokasi2."','".$tkurir."','".$tjawsout."','".$tkodebrg."')" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_transfer set m_nama = '".$tnama."', m_keterangan = '".$tket."', m_kurir = '".$tkurir."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = $_POST['m_kodebarang'.$i];
		$tnoplu = $_POST['m_productid'.$i];
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		if ($tnoplu != '')
		{
			if ( $new == 'Y' )
			{
				// Kalau baru, cek stock dulu masih ada ngk !!! 
				$sql_cekstock = "select m_qty from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
				$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
				$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
				if ($tqty <= $row_cekstock['m_qty'])
				{
					//Insert table transfer
					$sql_insert = "insert into t_transfer2 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."','".$tlokasi2."',".$tqty.",'','T')";
					$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);	
					
				
				}
				else
				{
					echo $tnoplu.' Stock tidak ada '.'<br/>' ;
				}
			}
			else
			{
				if ($hapus == 'on')
				{
					//Hapus data transfer
					$sql_hapus = "delete from t_transfer2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
			}
			//Update stock
			$sql_updatestock = "update t_stockinv set m_qty = dbo.f_hitstock('".$tcabang."','".$tkdbrg."','".$tnoplu."'), m_otw = dbo.f_hitotw('".$tcabang."','".$tkdbrg."','".$tnoplu."') where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
			$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
			echo $sql_updatestock ;
		}

	}

	$tmenu = 'M10000';
	$tuser = $_SESSION['loginid'];


	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);


	sqlsrv_close($con_dbnew);
	//header("Location: lmtransfer.php?st=".base64_encode($tcabang)."&pr=".base64_encode($_POST['periode'])."&prm=".base64_encode($_POST['param']));

?>