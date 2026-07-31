<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdstore = $_POST['kdstore'];
	$periode  = $_POST['periode'];
	$prm = $_POST['prm'];
	$treturn = '                              ';
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	$tsales = $_POST['m_kodesales'];
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
	
	// Kalau baru, create nomor RESELL 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_resell where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'RS'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		//Create nomor JAWS
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.Resell where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '0000';}
		$nojaws = $nojaws + 1 ;
		$tjawsresell = 'RO/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//cari nama/kode Customer
		$tsqlcj = "select ID,Nama,KodePos from palace_db.dbo.datacustomer where KodePos = '".$tkodecust."' ";
		$stmtcj= sqlsrv_query( $con_dbnew, $tsqlcj);
		$rowcj = sqlsrv_fetch_array( $stmtcj, SQLSRV_FETCH_ASSOC);
		
		$idcust = $rowcj ['ID'];
		$nmcust = $rowcj ['Nama'];

		$tsqljaws = "insert into palace_db.dbo.Resell values ('".$idcust."','".$nmcust."','".$tlok."','".$idlok."','0', '".$tgl."', '".$tjawsresell."', '0', '".$tgl."', '".$tket."', '".$tgl."', 'AE-".$tlokasi."','0' )" ;
		
		$tsql = "insert into t_resell (m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status, m_kodesales,m_jaws) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."','".$tsales."','".$tjawsresell."')" ;
		
		
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_resell set m_keterangan = '".$tket."', m_kodesales = '".$tsales."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}	
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);

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
					$sql_updatestock = "update t_stockinv set m_qty = m_qty + ".$tqty." , m_status = 3 where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					
					if ($tkdbrg == 'J0000001')
					{
						//Update table StockProductDJ
						$sql_jaws = "	update palace_db.dbo.StockProductDJ 
										set StatusPenjualan = '1'
										where  Nomor = '".$tnoplu."'
										";
						
						
						//Update table StockProductDJ
						$sql_jaws2 = "	update palace_db.dbo.StockActualDJ 
										set SubStorage = '11'
										where  Nomor = '".$tnoplu."'
										";
									
						
						//Insert table StockLedgerDJ
						$sql_jaws3 = "insert into palace_db.dbo.StockLedgerDJ 
										select b.ID,'".$tlok."','".$idlok."','".$nmlok."',1,11,'".$tjawsresell."','".$tuser."','".$tgl."'
										from palace_db.dbo.StockProductDJ B 
										where b.Nomor = '".$tnoplu."'
										";
						
						//Insert table ResellDJ
							
							//Create nomor JAWS
							$tsqlinv = "select max(right(Invoice,4)) as nomormax from palace_db.dbo.ResellDJ ";
							$stmtinv = sqlsrv_query( $con_dbnew, $tsqlinv);
							$rowinv = sqlsrv_fetch_array( $stmtinv, SQLSRV_FETCH_ASSOC);
							$noinv = $rowinv ['nomormax'];
							if ($noinv  == ''){$noinv  = '0000';}
							$noinv  = $noinv  + 1 ;
							$kodeinv = 'D';
							$Qc = 'QC';
							$tjawsinv = 'INV/'.$kodeinv.'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$noinv,-4) ;
							
							$sql_jaws4 = " insert into palace_db.dbo.ResellDJ (IDForm, IDSalesOrder, IDProduct, Nomor, TglResell, HargaAcuan, HargaRupiah, NilaiTradein, OperatorTgl, OperatorNama)
										   select a.ID,e.ID,b.ID, '".$tjawsinv."', '".$tgl."','".$tharga2."','".$tharga2."','".$tharga."','".$tgl."', 'AE'
										   from palace_db.dbo.Resell a, palace_db.dbo.StockProductDJ b , t_stockdata c, t_pos d, palace_db.dbo.SalesOrder e
										   where 	a.Nomor = '".$tjawsresell."' and
										   			d.m_nomor = '".$tnomor2."' and
													d.m_jaws = e.Nomor and
												    b.Nomor = '".$tnoplu."' and
												    c.m_productid = '".$tnoplu."'
											";
					}
					else
					{
						//Update table StockProductPG
						$sql_jaws = "	update palace_db.dbo.StockProductPG
										set StatusPenjualan = '1'
										where  Nomor = '".$tnoplu."'
										";
						
						//Update table StockActualPG
						$sql_jaws2 = "	update palace_db.dbo.StockActualPG
										set SubStorage = '11'
										where  Nomor = '".$tnoplu."'
										";
						//Insert table StockLedgerPG
						$sql_jaws3 = "insert into palace_db.dbo.StockLedgerPG 
										select b.ID,'".$tlok."','".$idlok."','".$nmlok."',1,11,'".$tjawsresell."','".$tuser."','".$tgl."'
										from palace_db.dbo.StockProductDJ B 
										where b.Nomor = '".$tnoplu."'
										";
						//Insert table ResellPG
							
							//Create nomor JAWS
							$tsqlinv = "select max(right(Invoice,4)) as nomormax from palace_db.dbo.SalesOrderPG" ;
							$stmtinv = sqlsrv_query( $con_dbnew, $tsqlinv);
							$rowinv = sqlsrv_fetch_array( $stmtinv, SQLSRV_FETCH_ASSOC);
							$noinv = $rowinv ['nomormax'];
							if ($noinv  == ''){$noinv  = '0000';}
							$noinv  = $noinv  + 1 ;
							$kodeinv = 'P';
							$tjawsinv = 'INV/'.$kodeinv.'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$noinv,-4) ;
							
							$sql_jaws4 = " insert into palace_db.dbo.ResellPG (IDForm, IDSalesOrder, IDProduct, Nomor, TglResell, HargaAcuan, HargaRupiah, NilaiTradein, OperatorTgl, OperatorNama)
										   select a.ID,e.ID,b.ID, '".$tjawsinv."', '".$tgl."','".$tharga2."','".$tharga2."','".$tharga."','".$tgl."', 'AE'
										   from palace_db.dbo.Resell a, palace_db.dbo.StockProductPG b , t_stockdata c, t_pos d, palace_db.dbo.SalesOrder e
										   where 	a.Nomor = '".$tjawsresell."' and
										   			d.m_nomor = '".$tnomor2."' and
													d.m_jaws = e.Nomor and
												    b.Nomor = '".$tnoplu."' and
												    c.m_productid = '".$tnoplu."'
											";
					}	
					
				$stmt_jaws   = sqlsrv_query( $con_dbnew, $sql_jaws);
				$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);
				$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);		
				$stmt_jaws4  = sqlsrv_query( $con_dbnew, $sql_jaws4);	
				
				}
				else
				{
					//Insert stock
					$sql_insertstock = "insert into t_stockinv
									(m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status, m_otw)
									 values('".$tcabang."','".$tkdbrg."','".$tlokasi."','".$tnoplu."',".$tqty.",".$tharga.",'3', 0)";
					$stmt_insertstock  = sqlsrv_query( $con_dbnew, $sql_insertstock);				
				}
								
				// Update status stock
				$sql_status = "update t_stockdata set m_status = '3' where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
				$stmt_status = sqlsrv_query( $con_dbnew, $sql_status);

				//Insert table resell2
				$sql_insert = "insert into t_resell2
								(m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_harga, m_kurs, m_rate, m_keterangan, m_cabang2, m_nomor2, m_tanggal2, m_harga2, m_hargaasp, m_status)
								 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",'IDR', 1, '','".$tcabang2."','".$tnomor2."','".$tgl."',".$tharga2.",0,'0')";
				$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);
				
				//update lagi table Resell 
				$sql_status2 = "update palace_db.dbo.Resell set TotalHarga = '".$tharga."' where Nomor = '".$tjawsresell."' ";
				$stmt_status2 = sqlsrv_query( $con_dbnew, $sql_status2);		
			}
			else
			{
				if ($hapus == 'on')
				{
					//Update stock
					$sql_updatestock = "update t_stockinv set m_qty = m_qty - ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
	
								
					// Update status stock
					$sql_status = "update t_stockdata set m_status = '0' where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_status = sqlsrv_query( $con_dbnew, $sql_status);
					
					//Hapus data transfer
					$sql_hapus = "delete from t_resell2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
					$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
				}
				else
				{
					$totbuyback = $totbuyback + $tharga ;
					$sql_updatetr = "	update t_resell2 set m_harga = ".$tharga." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatetr = sqlsrv_query( $con_dbnew, $sql_updatetr);
				}
			}
		}
	}

	$tmenu = 'R30000';
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
	
	header("Location: resell.php?st=".base64_encode($_POST['kdstore'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>