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
	$tkodeproject = $_POST['m_kode_project'];
	$tnamaproject = $_POST['m_nama_project'];
	$tkodesupplier = $_POST['m_kode_supplier'];
	$tnamasupplier = $_POST['m_nama_supplier'];
	$m_payment_term = $_POST['m_payment_term'];
	$tketerangan = $_POST['m_keterangan'];
	$type = $_POST['m_type'];
	$tstatus = $_POST['m_status'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	

	$m_jumlah_rp       = str_replace(",","", $_POST['m_jumlah_rp'] ?? 0);
	$m_diskon_persen      = str_replace(",","", $_POST['m_diskon_persen'] ?? 0);
	$m_diskon_jumlah    = str_replace(",","", $_POST['m_diskon_jumlah'] ?? 0);
	$m_diskon2_jumlah    = str_replace(",","", $_POST['m_diskon2_jumlah'] ?? 0);
	$m_ppn_persen    = str_replace(",","", $_POST['m_ppn_persen'] ?? 0);
	$m_ppn_jumlah    = str_replace(",","", $_POST['m_ppn_jumlah'] ?? 0);
	$m_total_rp    = str_replace(",","", $_POST['m_total_rp'] ?? 0);
	
	$tanggal_kirim = $_POST['m_tanggal_kirim'];

	// default null kalau kosong atau salah format
	$m_tanggal_kirim = null;

	if (!empty($tanggal_kirim)) {
		$dt = DateTime::createFromFormat('d/m/Y', $tanggal_kirim);
		if ($dt !== false) {
			$m_tanggal_kirim = $dt->format('Y-m-d');
		}
	}
	
	
	//echo $tanggal_kirim ."<br>"; 
	//echo $m_tanggal_kirim ."<br>"; 
	
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
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_po where year(m_tanggal) = ".$tahun." and month(m_tanggal) = ".$bulan;
		
		//echo $tsqlnomor ."<br>";
		$stmtnomor = $con_dbnew->query($tsqlnomor);
		$rownomor = $stmtnomor->fetch_assoc();
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'PO'.$tahun.$bulan.substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_po (m_nomor, m_tanggal, m_kode_project, m_nama_project, m_kode_supplier, m_nama_supplier, m_keterangan, 
								   m_status, m_jumlah_rp, m_diskon_persen, m_diskon_jumlah, m_diskon2_jumlah, m_ppn_persen, m_ppn_jumlah, m_total_rp, m_type, m_payment_term, m_tanggal_kirim) 
				values('".$tnomor."','".$tgl."','".$tkodeproject."','".$tnamaproject."','".$tkodesupplier."','".$tnamasupplier."','".$tketerangan."',
					   '".$tstatus."',".$m_jumlah_rp.",".$m_diskon_persen.",".$m_diskon_jumlah.",".$m_diskon2_jumlah.",".$m_ppn_persen.",".$m_ppn_jumlah.",".$m_total_rp.",'".$type."','".$m_payment_term."','".$m_tanggal_kirim."')" ;
		
		//$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_po set m_keterangan = '".$tketerangan."', m_type = '".$type."',m_kode_supplier = '".$tkodesupplier."', m_nama_supplier = '".$tnamasupplier."', m_payment_term = '".$m_payment_term."', 
								 m_tanggal_kirim = '".$m_tanggal_kirim."', m_jumlah_rp = ".$m_jumlah_rp.", m_diskon_persen = ".$m_diskon_persen.",m_kode_project = '".$tkodeproject."', m_nama_project = '".$tnamaproject."', 
								 m_diskon_jumlah = ".$m_diskon_jumlah.", m_diskon2_jumlah = ".$m_diskon2_jumlah.", m_ppn_persen = ".$m_ppn_persen.", m_ppn_jumlah = ".$m_ppn_jumlah.", m_total_rp = ".$m_total_rp."
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
			$tno        = $_POST['m_no'.$i] ?? $i;
			$tnomorreq  = $_POST['m_nomor_request'.$i] ?? $i;
			$tunit      = $_POST['m_unit'.$i];
			$tqty         = floatval(str_replace(",", "", $_POST['m_qty'.$i] ?? 0));
			$tharga       = floatval(str_replace(",", "", $_POST['m_harga'.$i] ?? 0));
			$m_diskon_rp  = floatval(str_replace(",", "", $_POST['m_diskon_rp'.$i] ?? 0));
			$m_diskon      = floatval(str_replace(",","", $_POST['m_diskon'.$i] ?? 0));
			
			
			//echo $tqty ." - ". $tharga ." - ". $m_diskon_rp ."<br>"  ;
			
			$ttotal       = ($tqty * $tharga)-$m_diskon_rp;
			$new        = $_POST['m_new'.$i] ?? '';
			$hapus      = $_POST['m_hapus'.$i] ?? '';
			
			// lanjut insert/update
			
			
			
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
					$sql_insert = "insert into t_po2
									(m_nomor, m_item, m_qty, m_keterangan, m_no, m_unit, m_harga, m_diskon, m_diskon_rp, m_total, m_nomor_request)
									 values('".$tnomor."','".$item."',".$tqty.",'".$tketdetail."', '".$tno."', '".$tunit."', ".$tharga.", ".$m_diskon.", ".$m_diskon_rp.", ".$ttotal.", '".$tnomorreq."')";
					
					//echo $sql_insert ."</br>";
					$stmt_insert = $con_dbnew->query($sql_insert);
					
				}
				else
				{
					if ($hapus == 'on')
					{
		
						//Hapus data transfer
						$sql_hapus = "delete from t_po2 where m_nomor = '".$tnomor."' and m_item = '".$item."' and m_no = '".$tno."' ";
						$stmt_hapus = $con_dbnew->query($sql_hapus);				
					}
					else
					{
						$sql_updatepos = "	update t_po2 set m_qty = ".$tqty.", m_keterangan = '".$tketdetail."', m_unit = '".$tunit."' , 
															 m_diskon = ".$m_diskon.", m_diskon_rp = ".$m_diskon_rp.",
															 m_harga = ".$tharga.", m_total = ".$ttotal."
											where m_item = '".$item."' and m_nomor = '".$tnomor."' and m_no =  '".$tno."'  ";
						
						//echo $sql_updatepos ."<br>";
						$stmt_updatepos = $con_dbnew->query($sql_updatepos);
						
					}
				}
				
				
				// update total penjualan di header
				$tsqlcekdetail = "
							UPDATE t_penawaran2 a
							JOIN (
								SELECT m_nomor_request AS m_nomor, m_item, SUM(m_qty) AS cototal
								FROM t_po2
								WHERE m_nomor = '".$tnomor."' and m_item = '".$item."'
								GROUP BY m_nomor_request, m_item
							) b ON a.m_nomor = b.m_nomor and a.m_item = b.m_item
							SET a.m_po = b.cototal
									
							";
				$stmtdetail = $con_dbnew->query($tsqlcekdetail);
			}
		}
	}
	
	// update total penjualan di header
	$tsqlcek = "
				UPDATE t_penawaran a
				JOIN (
					SELECT m_nomor_request AS m_nomor, SUM(m_qty) AS cototal
					FROM t_po2
					WHERE m_nomor = '".$tnomor."'
					GROUP BY m_nomor_request
				) b ON a.m_nomor = b.m_nomor
				SET a.m_po = b.cototal
						
				";
	$stmtcek = $con_dbnew->query($tsqlcek);
	

	$tmenu = 'R10000';

	ob_end_flush();
	$con_dbnew->close();	
	header("Location: t_po.php?pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>