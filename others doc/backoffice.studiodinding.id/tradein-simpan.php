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
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	$tsales = $_POST['m_kodesales'];
	$tsales2 = $_POST['m_kodesales2'];
	$tstatus = $_POST['m_status'];
	$tlokasi = $_POST['m_lokasi'];
	$jumrow = $_POST['jumrow'];
	$jumrow2 = $_POST['jumrow2'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.substr($tanggal, -9);
	
	$tsqllok = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi."' ";
	$stmtlok= sqlsrv_query( $con_dbnew, $tsqllok);
	$rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowlok['m_kode2']) ;
	$tlok = $dumb[0];
	$idlok = $dumb[1];
	$nmlok = $rowlok['m_nama'];

	// Kalau baru, create nomor POS 
	echo $tnomor.'<br/>' ; 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		//cari nama/kode Customer
		$tsqlcj = "select ID,Nama,KodePos from palace_db.dbo.datacustomer where KodePos = '".$tkodecust."' ";
		$stmtcj= sqlsrv_query( $con_dbnew, $tsqlcj);
		$rowcj = sqlsrv_fetch_array( $stmtcj, SQLSRV_FETCH_ASSOC);
		
		$idcust = $rowcj ['ID'];
		$nmcust = $rowcj ['Nama'];
		
		//cari nama/kode Sales
		$tsqlsj = "select ID,Nama,KodePos  from palace_db.dbo.datasales where KodePos = '".$tsales."' ";
		$stmtsj= sqlsrv_query( $con_dbnew, $tsqlsj);
		$rowsj = sqlsrv_fetch_array( $stmtsj, SQLSRV_FETCH_ASSOC);
		
		$idsales = $rowsj ['ID'];
		$nmsales = $rowsj ['Nama'];
		
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_pos where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'SJ'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		//Create nomor POS JAWS
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.SalesOrder where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '0000';}
		$nojaws = $nojaws + 1 ;
		$tjawspos = 'SO/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//Create nomor RESELL JAWS
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.Resell where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '0000';}
		$nojaws = $nojaws + 1 ;
		$tjawsresell = 'RO/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//Create nomor RESELL JAWS
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.Resell where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '0000';}
		$nojaws = $nojaws + 1 ;
		$tjawsresell = 'RO/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;

		$tsql = "insert into t_pos (m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status, m_kodesales, m_kodesales2, m_type) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."','".$tsales."','".$tsales2."','T')" ;
				
		$tsqljawspos = "insert into palace_db.dbo.SalesOrder values ('".$tlok."','".$idlok."','".$idcust."','".$nmcust."','".$idsales."','".$nmsales."','".$tjawspos."','0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0','0', '0','".$tgl."','0','".$tgl."','".$tket."','".$tuser."','".$tgl."','0')" ;
		
		$tsqljawsresell = "insert into palace_db.dbo.Resell values ('".$idcust."','".$nmcust."','".$tlok."','".$idlok."','0', '".$tgl."', '".$tjawsresell."', '0', '".$tgl."', '".$tket."', '".$tgl."', 'AE-".$tlokasi."','0' )" ;

	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_pos set m_keterangan = '".$tket."', m_kodesales = '".$tsales."', m_kodesales2 = '".$tsales2."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	echo $tsql.'<br/>' ; 
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	// Simpat data stock penjualan
	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = $_POST['m_kodebarang'.$i];
		$tnoplu = $_POST['m_productid'.$i];
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$tharga = str_replace(",","",$_POST['m_harga'.$i]);	
		$tdisc = str_replace(",","",$_POST['m_disc'.$i]);	
		$tdiscount = str_replace(",","",$_POST['m_discount'.$i]);	
		$tdiscount2 = str_replace(",","",$_POST['m_discount2'.$i]);	
		$tdiscount3 = str_replace(",","",$_POST['m_discount3'.$i]);	
		$tdiscount4 = str_replace(",","",$_POST['m_discount4'.$i]);	
		
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
					$sql_insert = "insert into t_pos2
									(m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_harga, m_disc, m_discount, m_discount2, m_discount3, m_discount4, m_kurs, m_rate, m_keterangan, m_status)
									 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",".$tdisc.",".$tdiscount.",".$tdiscount2.",".$tdiscount3.",".$tdiscount4.",'IDR',1,'','0')";
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
					$sql_hapus = "delete from t_pos2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
				else
				{
					$sql_updatepos = "	update t_pos2 set m_disc = ".$tdisc.", m_discount = ".$tdiscount.", m_discount2 = ".$tdiscount2.", m_discount3 = ".$tdiscount3.", m_discount4 = ".$tdiscount4." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatepos = sqlsrv_query( $con_dbnew, $sql_updatepos);
					
				}
			}
		}
	}

	// Simpan data buyback
	$totbuyback = 0 ;
	for ($i = 1; $i <= $jumrow2; $i++) 
	{
		$tkdbrg = $_POST['b_kodebarang'.$i];
		$tnoplu = $_POST['b_productid'.$i];
		$tqty = str_replace(",","",$_POST['b_qty'.$i]);	
		$tharga = str_replace(",","",$_POST['b_harga'.$i]);	
		$tcabang2 = $_POST['b_cabang2'.$i];
		$tnomor2 = $_POST['b_nomor2'.$i];
		$tharga2 = str_replace(",","",$_POST['b_harga2'.$i]);	
		$ttgl = $_POST['b_tanggal2'.$i];

		$abc = explode('/',substr($ttgl, 0, 10));
		$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.substr($ttgl, -9);


		$new = $_POST['b_new'.$i];
		$hapus = $_POST['b_hapus'.$i];
		if ($tnoplu != '')
		{
			if ( $new == 'Y' )
			{
				$totbuyback = $totbuyback + $tharga ;
				// Kalau baru, cek stock dulu masih ada ngk !!! 
				$sql_cekstock = "select isnull(count(*),0) as co_jumrow from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
				$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
				$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
				$cek = $row_cekstock['co_jumrow'];
				if ($cek == ''){$cek = 0 ;}
				
				if ($cek > 0)
				{
					//Update stock
					$sql_updatestock = "update t_stockinv set m_qty = m_qty + ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
				}
				else
				{
					//Insert stock
					$sql_insertstock = "insert into t_stockinv
									(m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status, m_otw)
									 values('".$tcabang."','".$tkdbrg."','".$tlokasi."','".$tnoplu."',".$tqty.",".$tharga.",'2', 0)";
					$stmt_insertstock  = sqlsrv_query( $con_dbnew, $sql_insertstock);				
				}
				
				//Insert table tradein2
				$sql_insert = "insert into t_tradein2
								(m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_harga, m_kurs, m_rate, m_keterangan, m_cabang2, m_nomor2, m_tanggal2, m_harga2, m_hargaasp, m_status)
								 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",'IDR', 1, '','".$tcabang2."','".$tnomor2."','".$tgl."',".$tharga2.",0,'0')";
				$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);		
				
				// Update status stock
				$sql_status = "update t_stockdata set m_status = '2' where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
				$stmt_status = sqlsrv_query( $con_dbnew, $sql_status);

			}
			else
			{
				if ($hapus == 'on')
				{
					//Update stock
					$sql_updatestock = "update t_stockinv set m_qty = m_qty - ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
	
					//Hapus data transfer
					$sql_hapus = "delete from t_tradein2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				

					// Update status stock
					$sql_status = "update t_stockdata set m_status = '0' where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_status = sqlsrv_query( $con_dbnew, $sql_status);
				}
				else
				{
					$totbuyback = $totbuyback + $tharga ;
					$sql_updatetr = "	update t_tradein2 set m_harga = ".$tharga." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatetr = sqlsrv_query( $con_dbnew, $sql_updatetr);
				}
			}
		}
	}

	$tmenu = 'R20000';
	$tuser = $_SESSION['loginid'];
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	// Simpan pembayaran exchange
	// Cek dulu jumlah pembayaran exchange udah ada belum
	$cekexch = "select isnull(sum(m_jumlah),0) as cojumlah from t_pos3 where m_cabang = '' and m_nomor = '' and m_carabayar = ''";
	$stmtexch = sqlsrv_query( $con_dbnew, $cekexch);
	$rowexch = sqlsrv_fetch_array( $stmtexch, SQLSRV_FETCH_ASSOC);
	$totexch = $rowexch['cojumlah'];
	if ($totexch == '') {$totexch = 0;}
	
	if ($totbuyback - $totexch > 0)
	{
		$bayarexch = $totbuyback - $totexch ;
		$tsqlpay = "insert into t_pos3 (m_cabang, m_nomor, m_tanggal, m_carabayar, m_edc, m_bank, m_nokartu, m_nmkartu, m_jumlah, m_mdr, m_jnkartu, m_cclkartu) values('".$tcabang."','".$tnomor."', getdate(), '05','','','','',".$bayarexch.", 0,'','')" ;
		$stmtpay = sqlsrv_query( $con_dbnew, $tsqlpay);
		echo $tsqlpay.'<br/>';
	
		$tketlog = 'PAYMEN';
		$tmenu = 'R20000';
		$tuser = $_SESSION['loginid'];
		$tsqllog = "{call sp_loguser(?,?,?,?)}";
		$paramlog = array(
						array($tmenu, SQLSRV_PARAM_IN),
						array($tuser, SQLSRV_PARAM_IN),
						array($tnomor, SQLSRV_PARAM_IN),
						array($tketlog, SQLSRV_PARAM_IN)
						);
		$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);
	}
	
	sqlsrv_close($con_dbnew);
	header("Location: tradein.php?st=".base64_encode($_POST['kdstore'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>