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
	$soid = $_POST['soid'];
	$prm = $_POST['param'];

	$treturn = '                              ';
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tnama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	$tsoid = $_POST['m_soid'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.substr($tanggal, -8);

	// Kalau baru, create nomor Transfer 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_opname where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'OP'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;

		$tsql = "insert into t_opname (m_cabang, m_nomor, m_tanggal, m_nama, m_keterangan, m_status, m_soid) values('".$tcabang."','".$tnomor."','".$tgl."','".$tnama."','".$tket."','".$tstatus."','".$tsoid."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_opname set m_nama = '".$tnama."', m_keterangan = '".$tket."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = $_POST['m_kodebarang'.$i];
		$tnoplu = $_POST['m_productid'.$i];
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$tlokasi = $_POST['m_lokasi'.$i];
		$tket = $_POST['m_keterangan'.$i];
		$nopic = 'T';
		$bedapic = 'T';
		$bedatag = 'T';
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];

		if ($_POST['m_nopic'.$i]=='on'){$nopic = 'Y';}
		if ($_POST['m_bedapic'.$i]=='on'){$bedapic = 'Y';}
		if ($_POST['m_bedabandrol'.$i]=='on'){$bedatag = 'Y';}
		
		if ($tnoplu != '')
		{
			if ( $new == 'Y' )
			{
				//Insert table transfer
				$sql_insert = "insert into t_opname2 (m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_keterangan, m_nopic, m_bedapic, m_bedabandrol, m_status)
								values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."', ".$tqty.",'".$tket."','".$nopic."','".$bedapic."','".$bedatag."','0')";
				$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);				
			}
			else
			{
				if ($hapus == 'on')
				{
					//Hapus data transfer
					$sql_hapus = "delete from t_opname2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
				else
				{
					$sql_update = "	update 	t_opname2 
									set 	m_keterangan = '".$tket."', 
											m_nopic = '".$nopic."', 
											m_bedapic = '".$bedapic."', 
											m_bedabandrol = '".$bedatag."' 
									where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_update  = sqlsrv_query( $con_dbnew, $sql_update);				
				}
			}
		}
	}

	$tmenu = 'M40000';
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
	header("Location: opname.php?st=".base64_encode($_POST['m_cabang'])."&pr=".$_POST['periode']."&so=".$_POST['soid']."&prm=".$prm);

?>