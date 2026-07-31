<?php
	session_start();
	set_time_limit(0) ;
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdstore = $_POST['kdstore'];
	$periode  = $_POST['periode'];
	$prm = $_POST['param'];

	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodesupl = $_POST['m_kodesupl'];
	$tnama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	$tdoc = $_POST['m_dokumen'];
	$tstatus = $_POST['m_status'];
	$tlokasi = $_POST['m_lokasi'];
	$jumrow = $_POST['jumrow'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");

	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		// Cek dulu, suppliernya baru atau lama !!!
		if ($tkodesupl == '')
		{
			$tsqlnosupl = "select max(right(m_nomor,6)) as nomormax from mssupplier where left(m_kode,2) = '".$tcabang."' and substring(m_kode,4,2) = '".substr($abc[2],-2)."'";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000000' ;}
			$nomax = $nomax + 1 ;
		
			$tkodesupl = $tcabang.'-'.substr($abc[2],-2).substr('000000'.$nomax,-6) ;
			$tsqlsupl = "insert into mssupplier ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status )
						values ( '".$kode."', '".$tkodesupl."', '".$nama."', '".$alamat."', '".$kota."', '".$telepon1."', '', '', '', '', '00' )" ;
			$stmtsupl = sqlsrv_query( $con_dbnew, $tsqlsupl);

		}
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_retur where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'RT'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;

		$tsql = "insert into t_retur (m_cabang, m_nomor, m_tanggal, m_kodesupl, m_nama, m_keterangan, m_status, m_dokumen) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodesupl."','".$tnama."','".$tket."','".$tstatus."','".$tdoc."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_retur set m_keterangan = '".$tket."', m_doc = '".$tdoc."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

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
				// Kalau baru, cek stock dulu masih ada ngk !!! 
				$sql_cekstock = "select m_qty from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
				$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
				$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
				if ($tqty >= $row_cekstock['m_qty'])
				{
					//Update stock
					$sql_updatestock = "update t_stockinv set m_qty = m_qty - ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					
					//Insert table pos2
					$sql_insert = "insert into t_retur2
									(m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_harga, m_kurs, m_rate, m_keterangan, m_status)
									 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",'IDR',1,'','0')";
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
					//Update stock
					$sql_updatestock = "update t_stockinv set m_qty = m_qty + ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
	
					//Hapus data transfer
					$sql_hapus = "delete from t_retur2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
			}
		}
	}

	// Simpan Log
	$tmenu = 'R10000';
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
	header("Location: ttb.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>