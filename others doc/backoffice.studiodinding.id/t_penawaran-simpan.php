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
	$m_tanggal_kirim = $_POST['m_tgl_kirim'];	
	
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$talamat = $_POST['m_alamat'];
	$tkota = $_POST['m_lokasi'];
	$tnamaclient = $_POST['m_namaclient'];
	$tket = $_POST['m_keterangan'];
	$tsupervisor = $_POST['m_supervisor'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
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
	
	$abc2 = explode('/', substr($m_tanggal_kirim, 0, 10)); // potong 10 char pertama

		// YYYY-MM-DD
		$tahun2  = $abc2[2];
		$bulan2  = $abc2[1];
		$hari2   = $abc2[0];
		$tgl_kirim   = "$tahun2/$bulan2/$hari2 ";

	
	
	// Kalau baru, create nomor POS 
	
	
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_penawaran where year(m_tanggal) = ".$tahun." and month(m_tanggal) = ".$bulan;
		
		//echo $tsqlnomor ."<br>";
		$stmtnomor = $con_dbnew->query($tsqlnomor);
		$rownomor = $stmtnomor->fetch_assoc();
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'MR'.$tahun.$bulan.substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_penawaran (m_nomor, m_tanggal, m_kode_project, m_nama, m_keterangan, m_status, m_supervisor, m_tanggal_kirim) 
				values('".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."','".$tsupervisor."','".$m_tanggal_kirim."')" ;
		
		//$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_penawaran set m_keterangan = '".$tket."', m_kode_project = '".$tkodecust."', m_nama = '".$tnama."', m_supervisor = '".$tsupervisor."', m_tanggal_kirim  = '".$tgl_kirim."'
					where m_nomor = '".$tnomor."'";
	}
	//echo $tsql."<br>";
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
			if (!isset($_POST['m_item'.$i])) { 
				continue; // skip baris yang tidak ada
			}

			$item       = $_POST['m_item'.$i];
			$tketdetail = $_POST['m_keterangan'.$i] ?? '';
			$tunit 		= $_POST['m_unit'.$i] ?? '';
			$tno        = $_POST['m_no'.$i] ?? $i;
			$tqty       = str_replace(",","", $_POST['m_qty'.$i] ?? 0);
			$new        = $_POST['m_new'.$i] ?? '';
			$hapus      = $_POST['m_hapus'.$i] ?? '';
			
			// lanjut insert/update
			
			
			//echo $item ." - ". $new ." - ". $tno ."<br>"  ;
			
			if ($tno == ''){ $tno = $i;}
			
			if ($item != '')
			{
				if ( $new == 'Y' )
				{
					/*
					// Kalau baru, cek stock dulu masih ada ngk !!! 
					$sql_cekstock = "select m_qty from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
					$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
					if ($tqty >= $row_cekstock['m_qty'])
					{
						//Update stock
						$sql_updatestock = "update t_stockinv set m_qty = m_qty - ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
						echo $sql_updatestock;
						$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
						
							
						
						
						
					}
					else
					{
						echo $tnoplu.' Stock tidak ada '.'<br/>' ;
					}
					*/
					//Insert table pos2
					$sql_insert = "insert into t_penawaran2
									(m_nomor, m_item, m_qty, m_keterangan, m_no, m_unit)
									 values('".$tnomor."','".$item."',".$tqty.",'".$tketdetail."', '".$tno."','".$tunit."')";
					
					//echo $sql_insert ."</br>";
					$stmt_insert = $con_dbnew->query($sql_insert);
					
				}
				else
				{
					if ($hapus == 'on')
					{
		
						//Hapus data transfer
						$sql_hapus = "delete from t_penawaran2 where m_nomor = '".$tnomor."' and m_item = '".$item."' and m_no = '".$tno."' ";
						$stmt_hapus = $con_dbnew->query($sql_hapus);				
					}
					else
					{
						$sql_updatepos = "	update t_penawaran2 set m_qty = ".$tqty.", m_keterangan = '".$tketdetail."', m_unit = '".$tunit."'
											where m_item = '".$item."' and m_nomor = '".$tnomor."' and m_no =  '".$tno."'  ";
						
						//echo $sql_updatepos ."<br>";
						$stmt_updatepos = $con_dbnew->query($sql_updatepos);
						
					}
				}
			}
		}
	}
	
	// update total penjualan di header
	$tsqlcek = "
				UPDATE t_penawaran a
					JOIN (
						SELECT m_nomor, SUM(m_qty) AS cototal
						FROM t_penawaran2
						WHERE m_nomor = '".$tnomor."'
						GROUP BY m_nomor
					) b ON a.m_nomor = b.m_nomor
					SET a.m_jumlah = b.cototal;
						
				";
	$stmtcek = $con_dbnew->query($tsqlcek);
	

	$tmenu = 'R10000';

ob_end_flush();
	$con_dbnew->close();	
	header("Location: t_penawaran.php?pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>