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
	$jumrow = $_POST['jumrow'];
	
	
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tukang = $_POST['m_tukang'];
	$tket = $_POST['m_keterangan'];
	$tstatus = $_POST['m_status'];
	$prm = $_POST['param'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_tukang where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'TK'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_tukang (m_cabang, m_nomor, m_tanggal, m_tukang, m_keterangan, m_status) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tukang."','".$tket."','".$tstatus."')" ;
		
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_tukang 
				 set m_tukang = '".$tukang."',
				 	 m_keterangan = '".$tket."' 
				 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';



//Detail
	for ($i = 1; $i <= $jumrow; $i++) 
	{	
		$tno= $_POST['m_no'.$i];
		$tkdbahan = $_POST['m_kodebahan'.$i];
		$tketerangan2 = $_POST['m_keterangan'.$i];
		$tberatkirim = str_replace(",","",$_POST['m_berat_kirim'.$i]);	
		$tberatterima = str_replace(",","",$_POST['m_berat_terima'.$i]);	
		
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		if ($tkdbahan != '')
		{
			if ( $new == 'Y' )
			{
				//Insert table pos2
				$sql_insert = "insert into t_tukang2
								(m_cabang, m_nomor,m_no, m_kodebahan, m_berat_kirim, m_keterangan, m_berat_terima)
								 values('".$tcabang."','".$tnomor."','".$tno."','".$tkdbahan."',".$tberatkirim.",'".$tkdbahan."',".$tberatterima.")";
				
				echo $sql_insert ."<br>";
				$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);		
					
			}
			else
			{
				
				if ($hapus == 'on')
				{
					//Hapus data transfer
					$sql_hapus = "delete from t_tukang2 
								  where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_no = '".$tno."' and m_kodebahan = '".$tkdbahan."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
				else
				{
					$sql_updatepos = "	update t_tukang2 
										set m_keterangan = '".$tketerangan."',
											m_berat_kirim = ".$tberatkirim.",
											m_berat_terima = ".$tberatterima."				
										where m_cabang = '".$tcabang."' and m_kodebahan = '".$tkdbahan."' and 
										m_nomor = '".$tnomor."'  and m_no = '".$tno."' ";
									//	echo $sql_updatepos;
					$stmt_updatepos = sqlsrv_query( $con_dbnew, $sql_updatepos);
					
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
	header("Location: tukang.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>