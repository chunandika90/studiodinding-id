<?php
ob_start();
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
	$tkodesupplier = $_POST['m_kode_supplier'];
	$tnamasupplier = $_POST['m_nama_supplier'];
	$m_carabayar = $_POST['m_carabayar'];
	$m_type = $_POST['m_type'];
	$tketerangan = $_POST['m_keterangan'];
	$type = $_POST['m_type'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	
	
	//echo $tanggal ."<br>"; 
	
	$abc = explode('-', substr($tanggal, 0, 10)); // potong 10 char pertama
	if (count($abc) == 3 && is_numeric($abc[0]) && is_numeric($abc[1]) && is_numeric($abc[2])) {
		// YYYY-MM-DD
		$tahun  = $abc[0];
		$bulan  = $abc[1];
		$hari   = $abc[2];
		$tgl    = "$tahun/$bulan/$hari " . date("H:i:s");
	} else {
		// fallback pakai hari ini
		$tahun = date("Y");
		$bulan = date("m");
		$hari  = date("d");
		$tgl   = "$tahun/$bulan/$hari " . date("H:i:s");
	}
	
	
	// Kalau baru, create nomor POS 
	
	//echo $tnomor ."<br>";
	
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_pembayaran where year(m_tanggal) = ".$tahun." and month(m_tanggal) = ".$bulan;
		
		//echo $tsqlnomor ."<br>";
		$stmtnomor = $con_dbnew->query($tsqlnomor);
		$rownomor = $stmtnomor->fetch_assoc();
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'SPB'.$tahun.$bulan.substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_pembayaran (m_nomor, m_tanggal, m_kode_supplier, m_nama_supplier, m_keterangan, m_status, m_carabayar, m_type) 
				values('".$tnomor."','".$tgl."','".$tkodesupplier."','".$tnamasupplier."','".$tketerangan."','".$tstatus."','".$m_carabayar."','".$m_type."')" ;
		
		//$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_pembayaran set m_keterangan = '".$tketerangan."', m_type = '".$m_type."', m_carabayar = '".$m_carabayar."'
				 where m_nomor = '".$tnomor."'";
	}
	echo $jumrow."<br>";
	$stmt = $con_dbnew->query($tsql);
	
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n".$tsql;
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';

	//Detail
		for ($i = 1; $i <= $jumrow; $i++) 
		{	
			if (!isset($_POST['m_project'.$i])) { 
				continue; // skip baris yang tidak ada
			}

			$m_project       		= $_POST['m_project'.$i];
			$m_nomor_po       		= $_POST['m_nomor_po'.$i];
			$m_tanggal_po 	  		= $_POST['m_tanggal_po'.$i] ?? '';
			$m_keterangan 	  		= $_POST['m_keterangan'.$i] ?? '';
			$m_no        			= $_POST['m_no'.$i] ?? $i;
			$m_jumlah_po         	= floatval(str_replace(",", "", $_POST['m_jumlah_po'.$i] ?? 0));
			$m_jumlah       		= floatval(str_replace(",", "", $_POST['m_jumlah'.$i] ?? 0));
			
			//echo $m_project ." - ". $m_nomor_po ." - ". $m_tanggal_po." - ". $m_jumlah  ."<br>"  ;
			
			$new        = $_POST['m_new'.$i] ?? '';
			$hapus      = $_POST['m_hapus'.$i] ?? '';
			
			// lanjut insert/update
			
			
			
			if ($m_no == ''){ $m_no = $i;}
			
			if ($m_project != '')
			{
				if ( $new == 'Y' )
				{
					//Insert table pos2
					$sql_insert = "insert into t_pembayaran2
									(m_nomor, m_nomor_po, m_project, m_tanggal_po, m_keterangan, m_no, m_jumlah_po, m_jumlah)
									 values('".$tnomor."','".$m_nomor_po."','".$m_project."','".$m_tanggal_po."','".$m_keterangan."',".$m_no.", ".$m_jumlah_po.", ".$m_jumlah.")";
					
					//echo $sql_insert ."</br>";
					$stmt_insert = $con_dbnew->query($sql_insert);
					
				}
				else
				{
					if ($hapus == 'on')
					{
		
						//Hapus data transfer
						$sql_hapus = "delete from t_pembayaran2 where m_nomor = '".$tnomor."' and m_item = '".$item."' and m_no = '".$tno."' ";
						$stmt_hapus = $con_dbnew->query($sql_hapus);				
					}
					else
					{
						$sql_updatepos = "	update t_pembayaran2 set m_jumlah_po = ".$m_jumlah_po.", m_jumlah = ".$m_jumlah.", m_nomor_po = '".$m_nomor_po."', 
															 m_project = '".$m_project."', m_tanggal_po = '".$m_tanggal_po."' 
											where m_nomor = '".$tnomor."' and m_no =  '".$tno."'  ";
						
						//echo $sql_updatepos ."<br>";
						$stmt_updatepos = $con_dbnew->query($sql_updatepos);
						
					}
				}
				
				/*
				// update total penjualan di header
				$tsqlcekdetail = "
							UPDATE t_po a
							JOIN (
								SELECT m_nomor_request AS m_nomor, m_item, SUM(m_qty) AS cototal
								FROM t_pembayaran2
								WHERE m_nomor = '".$tnomor."' and m_item = '".$item."'
								GROUP BY m_nomor_request, m_item
							) b ON a.m_nomor = b.m_nomor and a.m_item = b.m_item
							SET a.m_po = b.cototal
									
							";
				$stmtdetail = $con_dbnew->query($tsqlcekdetail);
				*/
			}
		}
	}
	
	// update total penjualan di header
	$tsqlcek = "
				UPDATE t_po a
				JOIN (
					SELECT m_nomor_po AS m_nomor, SUM(m_jumlah) AS cototal
					FROM t_pembayaran2
					GROUP BY m_nomor_po
				) b ON a.m_nomor = b.m_nomor
				SET a.m_bayar = b.cototal
						
				";
	echo $tsqlcek;
	$stmtcek = $con_dbnew->query($tsqlcek);
	

	$tmenu = 'R10000';

	ob_end_flush();
	$con_dbnew->close();	
	header("Location: t_pembayaran.php?pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>