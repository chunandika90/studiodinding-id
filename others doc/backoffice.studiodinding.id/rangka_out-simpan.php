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
	
	
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$supplier = $_POST['m_supplier'];
	$tket = $_POST['m_ket'];
	$tspk = $_POST['m_spk'];
	$tstatus = $_POST['m_status'];
	$prm = $_POST['param'];
	
	
	$tlokasi = $_POST['m_lokasi'];
	$tlokasi2 = $_POST['m_lokasi2'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'-'.$abc[1].'-'.$abc[0].' '.date("H:i:s");
	
	
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_barang_out where  year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'BO'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_barang_out (m_lokasi, m_nomor, m_tanggal, m_supplier, m_keterangan, m_status, m_lokasi2, m_spk) 
				values('".$tlokasi."','".$tnomor."','".$tgl."','".$supplier."','".$tket."','".$tstatus."','".$tlokasi2."','".$tspk."')" ;
		
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_barang_out 
				 set m_supplier = '".$supplier."',
				 	 m_keterangan = '".$tket."' ,
				 	 m_spk	 = '".$tspk."' 
				 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';


//Detail
	for ($i = 1; $i <= $jumrow; $i++) 
	{	
		$tno= $_POST['m_no'.$i];
		$ttype = $_POST['m_type'.$i];
		$tkodebarang = $_POST['m_kodebarang'.$i];
		$ttukang = $_POST['m_tukang'.$i];
		$ttqy = str_replace(",","",$_POST['m_qty'.$i]);	
		$tgrossweight = str_replace(",","",$_POST['m_grossweight'.$i]);	
		$tketerangan = $_POST['m_keterangan'.$i];
		
		
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		if ($tkodebarang != '')
		{
			if  ($hapus != 'on')
			{
				if ( $new == 'Y' )
				{
					//Insert table pos2
					$tsqlbarang = "insert into t_barang_out2
									(m_lokasi, m_nomor,m_no, m_type, m_tukang, m_kodebarang, m_qty, m_grossweight, m_keterangan, m_lokasi2)
									 values('".$tlokasi."','".$tnomor."','".$tno."','".$ttype."','".$ttukang."','".$tkodebarang."',".$ttqy.",".$tgrossweight.",'".$tketerangan."','".$tlokasi2."')";
				}
				else
				{
					$tsqlbarang = "	update t_barang_out2 
									set m_type = '".$ttype."',
										m_tukang = '".$ttukang."',
										m_kodebarang = '".$tkodebarang."'	,
										m_qty = ".$ttqy."	,
										m_grossweight = ".$tgrossweight."	,
										m_keterangan = '".$tketerangan."'	,
										m_lokasi2 = '".$tlokasi2."'				
									where m_lokasi = '".$tlokasi."' and 
									m_nomor = '".$tnomor."'  and m_no = '".$tno."' ";
				}
				
				// Kalau baru, cek stock dulu masih ada ngk !!! 
				$sql_cekstock = "select count(*) coqty from t_stockbarang where m_lokasi = '".$tlokasi2."' and m_kodebarang = '".$tkodebarang."' ";
				$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
				$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
				
				
				if ($row_cekstock['coqty'] == 0)
				{	
					//insert ke table stockinv
					$tsqlstock = "insert into t_stockbarang (m_lokasi, m_kodebarang,m_qty, m_grossweight) 
										values('".$tlokasi2."','".$tkodebarang."',".$ttqy.",".$tgrossweight.")" ;
					
				}
				$stmt_insertstock  = sqlsrv_query( $con_dbnew, $tsqlstock);
				if( $stmt_insertstock == false )
				{
					 echo "Error in executing statement 1.\n";
					 die( print_r( sqlsrv_errors(), true));
				}	
			
				
				
			}
			else
			{
				$tsqlbarang = "delete from t_barang_out2 
							  where m_lokasi = '".$tlokasi."' and m_nomor = '".$tnomor."' and m_no = '".$tno."' ";			
			}
			$stmt_barang_out  = sqlsrv_query( $con_dbnew, $tsqlbarang);		
		}
		
		
		$tsqlhitung = "update t_stockbarang set m_qty = dbo.f_hitbarang('".$tlokasi."','".$tkodebarang."'), 
											   m_grossweight = dbo.f_hitbarang2('".$tlokasi."','".$tkodebarang."')
					   where m_lokasi = '".$tlokasi."' and m_kodebarang = '".$tkodebarang."' ";	
		$stmt_hitung  = sqlsrv_query( $con_dbnew, $tsqlhitung);
		if( $stmt_hitung == false )
		{
			 echo "Error in executing statement 2.\n";
			 die( print_r( sqlsrv_errors(), true));
		}	
		$tsqlhitung2 = "update t_stockbarang set m_qty = dbo.f_hitbarang('".$tlokasi2."','".$tkodebarang."'), 
											   m_grossweight = dbo.f_hitbarang2('".$tlokasi2."','".$tkodebarang."')
					   where m_lokasi = '".$tlokasi2."' and m_kodebarang = '".$tkodebarang."' ";	
		$stmt_hitung  = sqlsrv_query( $con_dbnew, $tsqlhitung2);
		if( $stmt_hitung == false )
		{
			 echo "Error in executing statement 2.\n";
			 die( print_r( sqlsrv_errors(), true));
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
	header("Location: rangka_out.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>