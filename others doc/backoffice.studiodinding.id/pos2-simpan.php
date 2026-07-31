<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	
	$tuser = $_SESSION['loginid'];
	
	$kdstore = $_POST['kdstore'];
	$periode  = $_POST['periode'];

	$treturn = '                              ';
	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$talamat = $_POST['m_alamat'];
	$tkota = $_POST['m_kota'];
	$ttelepon1 = $_POST['m_telepon1'];
	$ttelepon2 = $_POST['m_telepon2'];
	$tket = $_POST['m_keterangan'];
	$tsales = $_POST['m_kodesales'];
	$tstatus = $_POST['m_status'];
	$tlokasi = $_POST['m_lokasi'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	$tsqllok = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi."' ";
	$stmtlok= sqlsrv_query( $con_dbnew, $tsqllok);
	$rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowlok['m_kode2']) ;
	$tlok = $dumb[0];
	$idlok = $dumb[1];
	$nmlok = $rowlok['m_nama'];

	// Cek data Customer Dulu
	if ($tkodecust == '')
	{
		$tsqlnocust = "select max(right(m_kode,6)) as nomormax from mscustomer where left(m_kode,2) = '".$tcabang."' and substring(m_kode,4,2) = '".substr($abc[2],-2)."'";
		$stmtnocust= sqlsrv_query( $con_dbnew, $tsqlnocust);
		$rownocust = sqlsrv_fetch_array( $stmtnocust, SQLSRV_FETCH_ASSOC);
		$nomax = $rownocust['nomormax'];
		if ($nomax == ''){$nomax = '000000' ;}
		$nomax = $nomax + 1 ;

		$tkodecust = $tcabang.'-'.substr($abc[2],-2).substr('000000'.$nomax,-6) ;
		$tsqlcust = "insert into mscustomer ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status, m_tmplahir, m_tgllahir, m_agama, m_cabang, m_kodesales, m_member, m_tglmember )
					values ( '".$tkodecust."', '".$tkodecust."', '".$tnama."', '".$talamat."', '".$tkota."', '".$ttelepon1."', '".$ttelepon2."', '', '', '', '00', '', '1900/01/01', '', '".$tcabang."', '".$tsales."', '', '1900/01/01' )" ;
		$stmtcust = sqlsrv_query( $con_dbnew, $tsqlcust);
	}
	else
	{
		$tsqlcust = "	update 	mscustomer 
						set		m_nama = '".$tnama."', m_alamat = '".$talamat."', m_kota = '".$tkota."', m_telepon1 = '".$ttelepon1."', m_telepon2 = '".$ttelepon2."'
						where 	m_kode = '".$tkodecust."'";
		$stmtcust = sqlsrv_query( $con_dbnew, $tsqlcust);
	}
//	echo $tsqlcust.'<br/>'  ;
	


	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_pos where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'SJ'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;

				
		//Create nomor JAWS
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.SalesOrder where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '0000';}
		$nojaws = $nojaws + 1 ;
		$tjawspos = 'SO/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
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
		
		$tsqljaws = "insert into palace_db.dbo.SalesOrder values ('".$tlok."','".$idlok."','1','".$nmcust."','1','JR TOP','".$tjawspos."','0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0','0', '0','".$tgl."','0','".$tgl."','".$tket."','".$tuser."','".$tgl."','0')" ;
		
		$tsql = "insert into t_pos (m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status, m_kodesales, m_kodesales2, m_type,m_jaws) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."','".$tsales."','".$tsales2."','J','".$tjawspos."')" ;
		
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		echo $tsqljaws;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_pos set m_keterangan = '".$tket."', m_kodesales = '".$tsales."', m_kodesales2 = '".$tsales2."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

//Detail
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
					//$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					
					//Insert table pos2
					$sql_insert = "insert into t_pos2
									(m_cabang, m_nomor, m_kodebarang, m_productid, m_lokasi, m_qty, m_harga, m_disc, m_discount, m_discount2, m_discount3, m_discount4, m_kurs, m_rate, m_keterangan, m_status)
									 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",".$tdisc.", ".$tdiscount.", ".$tdiscount2.", ".$tdiscount3.", ".$tdiscount4.",'IDR',1,'','0')";
					$stmt_insert  = sqlsrv_query( $con_dbnew, $sql_insert);		
					
					//UPDATE DATA STOCK DI TABLE JAWS
					
					if ($tkdbrg == 'J0000001')
					{
						//Update table StockProductDJ
						$sql_jaws = "	update palace_db.dbo.StockProductDJ 
										set StatusPenjualan = '2'
										where  Nomor = '".$tnoplu."'
										";
						
						$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);
						
						//Update table StockProductDJ
						$sql_jaws2 = "	update palace_db.dbo.StockActualDJ 
										set SubStorage = '3'
										where  Nomor = '".$tnoplu."'
										";
						$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);				
						
						//Insert table StockLedgerDJ
						$sql_jaws3 = "insert into palace_db.dbo.StockLedgerDJ 
										select b.ID,'".$tlok."','".$idlok."','".$nmlok."',-1,8,'".$tjawspos."','".$tuser."','".$tgl."'
										from palace_db.dbo.StockProductDJ B 
										where b.Nomor = '".$tnoplu."'
										";
						$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);	
						
						//Insert table SalesOrderDJ
							
							//Create nomor JAWS
							$tsqlinv = "select max(right(Invoice,4)) as nomormax from palace_db.dbo.SalesOrderDJ ";
							$stmtinv = sqlsrv_query( $con_dbnew, $tsqlinv);
							$rowinv = sqlsrv_fetch_array( $stmtinv, SQLSRV_FETCH_ASSOC);
							$noinv = $rowinv ['nomormax'];
							if ($noinv  == ''){$noinv  = '0000';}
							$noinv  = $noinv  + 1 ;
							$kodeinv = 'D';
							$tjawsinv = 'INV/'.$kodeinv.'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$noinv,-4) ;
							
							$sql_jaws3 = " insert into palace_db.dbo.SalesOrderDJ (IDform, IDproduct, Invoice, ModalRupiah, HargaRupiah, Discount, DiscountNominal, DiscountProgram, DiscountProgramNominal, DiscountGIft, DiscountRound, TotalRupiah, TotalBayar, StatusResell)

										select a.ID,b.ID,'".$tjawsinv."',c.m_hargam, '".$tharga."', '".$tdisc."','".$tdiscount."','".$tdiscount2."','".$tdiscount3."',0,'".$tdiscount4."', '".$tharga."', '".$tharga."',0
										from palace_db.dbo.SalesOrder a, palace_db.dbo.StockProductDJ b , t_stockdata c
										where 	a.Nomor = '".$tjawspos."' and
												b.Nomor = '".$tnoplu."' and
												c.m_productid = '".$tnoplu."'
											";
							$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);
							
					
						
					}
					/*
					else if ($tkdbrg == 'L0000001')
					{
						//Update table StockProductLD
						$sql_jaws = "	update palace_db.dbo.StockProductLD
										set StatusPenjualan = '2'
										where  Nomor = '".$tnoplu."'
										";
						$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);
						
						//Update table StockActualLD
						$sql_jaws2 = "	update palace_db.dbo.StockActualLD 
										set SubStorage = '3'
										where  Nomor = '".$tnoplu."'
										";
						$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);				
						
						//Insert table StockLedgerLD
						$sql_jaws3 = "insert into palace_db.dbo.StockLedgerLD
										select b.ID,'".$tlok."','".$idlok."','".$nmlok."',-1,8,'".$tjawspos."','".$tuser."','".$tgl."'
										from palace_db.dbo.StockLedgerLD B 
										where b.Nomor = '".$tnoplu."'
										";
						$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);	
						
						//Insert table SalesOrderDJ
							
							//Create nomor JAWS
							$tsqlinv = "select max(right(Invoice,4)) as nomormax from palace_db.dbo.SalesOrderDJ ";
							$stmtinv = sqlsrv_query( $con_dbnew, $tsqlinv);
							$rowinv = sqlsrv_fetch_array( $stmtinv, SQLSRV_FETCH_ASSOC);
							$noinv = $rowinv ['nomormax'];
							if ($noinv  == ''){$noinv  = '0000';}
							$noinv  = $noinv  + 1 ;
							$kodeinv = 'D';
							$tjawsinv = 'INV/'.$kodeinv.'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$noinv,-4) ;
							
							$sql_jaws3 = "insert into palace_db.dbo.SalesOrderDJ (IDform, IDproduct, Invoice, ModalRupiah, HargaRupiah, Discount, DiscountNominal, DiscountProgram, DiscountProgramNominal, DiscountGIft, DiscountRound, TotalRupiah, TotalBayar, StatusResell)
										select a.ID,b.ID,'".$tjawsinv."',c.m_hargam, '".$tharga."', '".$tdisc."','".$tdiscount."','".$tdiscount2."','".$tdiscount3."',0,'".$tdiscount4."', '".$tharga."', '".$tharga."',0
										from palace_db.dbo.SalesOrder a, palace_db.dbo.StockProductLD b , t_stockdata c
										where 	a.Nomor = '".$tjawspos."' and
												b.Nomor = '".$tnoplu."' and
												c.m_productid = '".$tnoplu."'
											";
							$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);	
					}
					*/
					else
					{
						//Update table StockProductPG
						$sql_jaws = "	update palace_db.dbo.StockProductPG
										set StatusPenjualan = '2'
										where  Nomor = '".$tnoplu."'
										";
						$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);
						
						//Update table StockActualPG
						$sql_jaws2 = "	update palace_db.dbo.StockActualPG
										set SubStorage = '3'
										where  Nomor = '".$tnoplu."'
										";
						$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);				
						
						//Insert table StockLedgerPG
						$sql_jaws3 = "insert into palace_db.dbo.StockLedgerPG 
										select b.ID,'".$tlok."','".$idlok."','".$nmlok."',-1,8,'".$tjawspos."','".$tuser."','".$tgl."'
										from palace_db.dbo.StockProductDJ B 
										where b.Nomor = '".$tnoplu."'
										";
						$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);	
						
						//Insert table SalesOrderPG
							
							//Create nomor JAWS
							$tsqlinv = "select max(right(Invoice,4)) as nomormax from palace_db.dbo.SalesOrderPG" ;
							$stmtinv = sqlsrv_query( $con_dbnew, $tsqlinv);
							$rowinv = sqlsrv_fetch_array( $stmtinv, SQLSRV_FETCH_ASSOC);
							$noinv = $rowinv ['nomormax'];
							if ($noinv  == ''){$noinv  = '0000';}
							$noinv  = $noinv  + 1 ;
							$kodeinv = 'P';
							$tjawsinv = 'INV/'.$kodeinv.'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$noinv,-4) ;
							
							$sql_jaws3 = "insert into palace_db.dbo.SalesOrderPG (IDform, IDproduct, Invoice, ModalRupiah, HargaRupiah, Discount, DiscountNominal, DiscountProgram, DiscountProgramNominal, DiscountGIft, DiscountRound, TotalRupiah, TotalBayar, StatusResell)
										select a.ID,b.ID,'".$tjawsinv."',c.m_hargam, '".$tharga."', '".$tdisc."','".$tdiscount."','".$tdiscount2."','".$tdiscount3."',0,'".$tdiscount4."', '".$tharga."', '".$tharga."',0
										from palace_db.dbo.SalesOrder a, palace_db.dbo.StockProductPG b , t_stockdata c
										where 	a.Nomor = '".$tjawspos."' and
												b.Nomor = '".$tnoplu."' and
												c.m_productid = '".$tnoplu."'
											";
							$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);	
							
					}	
						
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
	
	// Cek dulu Total Invoice untuk Input k Jaws
	$tsqlcek = "select isnull(sum((m_qty * m_harga) - m_discount - m_discount2 - m_discount3 - m_discount4),0) as cototal from t_pos2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
	$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);
	
	$total = $rowcek ['cototal'];
	//update total invoice k sales order
	$jaws_update = "	update palace_db.dbo.SalesOrder
					set HargaRupiah = '".$total."',Unpaid = '".$total."',TotalBayarBeforeDisc = '".$total."',TotalRupiahBeforeDisc = '".$total."', TotalBayar = '".$total."', TotalRupiah = '".$total."'
					where  Nomor = '".$tjawspos."'
					";

	$stmt_update  = sqlsrv_query( $con_dbnew, $jaws_update);
	
	//Insert ke Table SalesReceipt
	$tsqlreceipt = "select max(right(NomorInvoice,4)) as nomormax from palace_db.dbo.SalesReceipt where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
	$stmtreceipt= sqlsrv_query( $con_dbnew, $tsqlreceipt);
	$rowreceipt = sqlsrv_fetch_array( $stmtreceipt, SQLSRV_FETCH_ASSOC);
	$noreceipt = $rowreceipt['nomormax'];
	if ($noreceipt == ''){$noreceipt = '0000';}
	$noreceipt = $noreceipt + 1 ;
	$tjawsreceipt = 'INV/'.$tlok.'/'.substr('000'.$idlok,-3).'/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojnoreceiptaws,-4) ;
	
	$sql_jaws4 = "insert into palace_db.dbo.SalesReceipt
				  select ID,Nomor,'','".$tjawsreceipt."', 0, getdate(),'AE'
				  from palace_db.dbo.SalesOrder
				  where  Nomor = '".$tjawspos."' 		   
				 ";
	//echo $sql_jaws4;
	$stmt_jaws4  = sqlsrv_query( $con_dbnew, $sql_jaws4);
	

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
	//header("Location: pos.php?dp=".base64_encode($_POST['m_cabang']."&prm=".base64_encode($prm));

?>