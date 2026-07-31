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

	$tcabang = $_POST['m_cabang'];
	
	
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodesupl = $_POST['m_kode'];
	$nama = $_POST['m_nama'];
	$tlokasi = $_POST['m_lokasi']."-0";
	$tket = $_POST['m_keterangan'];
	$dosupplier = $_POST['m_dosupplier'];
	$dosupplier = $_POST['m_dosupplier'];
	$tkdbrg = $_POST['m_kodebarang'];
	$designer = $_POST['m_designer'];
	$ttype = $_POST['m_type'];
	$jumrow = $_POST['jumrow'];
	$status = 'A';
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		// Cek dulu, suppliernya baru atau lama !!!
		if ($tkodesupl == '')
		{
			$tsqlnosupl = "select max(right(m_kode,6)) as nomormax from mssupplier where left(m_kode,1) = left('".$nama."',1) ";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000000' ;}
			$nomax = $nomax + 1 ;
		
			$tkodesupl = substr($nama,0,1).'0'.substr('000000'.$nomax,-6) ;
			$tsqlsupl = "insert into mssupplier ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status )
						values ( '".$tkodesupl."', 'LOKAL', '".$nama."', '', '', '', '', '', '', '', '00' )" ;
			$stmtsupl = sqlsrv_query( $con_dbnew, $tsqlsupl);

		}
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax 
					  from t_ttb 
					  where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'TTB'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_ttb (m_cabang, m_nomor,m_tanggal, m_supplier, m_keterangan, m_dosupplier, m_status ,m_type, m_kodebarang, m_designer) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodesupl."','".$tket."','".$dosupplier."','".$status."','".$ttype."','".$tkdbrg."','".$designer."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_ttb set m_keterangan = '".$tket."', m_supplier = '".$tkodesupl."', m_dosupplier = '".$dosupplier."', m_type = '".$ttype."' 
								  m_kodebarang = '".$tkdbrg."', m_designer = '".$designer."'
				where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	//echo $tsql;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';


//Detail
	for ($i = 1; $i <= $jumrow; $i++) 
	{	
		$tnoplu = $_POST['m_productid'.$i];	
		$trubberid = $_POST['m_rubberid'.$i];	
		$tkodesupplier = $_POST['m_kodesupplier'.$i];	
		$titem = $_POST['m_item'.$i];	
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$tgross = str_replace(",","",$_POST['m_grossweight'.$i]);	
		$thargar = str_replace(",","",$_POST['m_hargar'.$i]);	
		
		
		$tbutir = str_replace(",","",$_POST['m_butira'.$i]);	
		$tcarat = str_replace(",","",$_POST['m_carata'.$i]);	
		$tbutir2 = str_replace(",","",$_POST['m_butirb'.$i]);	
		$tcarat2 = str_replace(",","",$_POST['m_caratb'.$i]);	
		$tbutir3 = str_replace(",","",$_POST['m_butirc'.$i]);	
		$tcarat3 = str_replace(",","",$_POST['m_caratc'.$i]);	
		$tbutir4 = str_replace(",","",$_POST['m_butird'.$i]);	
		$tcarat4 = str_replace(",","",$_POST['m_caratd'.$i]);	
		$tbutir5 = str_replace(",","",$_POST['m_butire'.$i]);	
		$tcarat5 = str_replace(",","",$_POST['m_carate'.$i]);	
		$tbutir6 = str_replace(",","",$_POST['m_butirf'.$i]);	
		$tcarat6 = str_replace(",","",$_POST['m_caratf'.$i]);	
		$tbutir7 = str_replace(",","",$_POST['m_butirg'.$i]);	
		$tcarat7 = str_replace(",","",$_POST['m_caratg'.$i]);	
		$tbutir8 = str_replace(",","",$_POST['m_butirh'.$i]);	
		$tcarat8 = str_replace(",","",$_POST['m_carath'.$i]);	
		$tbutir9 = str_replace(",","",$_POST['m_butiri'.$i]);	
		$tcarat9 = str_replace(",","",$_POST['m_carati'.$i]);	
		$tbutir10 = str_replace(",","",$_POST['m_butirj'.$i]);	
		$tcarat10 = str_replace(",","",$_POST['m_caratj'.$i]);	
		$tbutir11 = str_replace(",","",$_POST['m_butirk'.$i]);	
		$tcarat11 = str_replace(",","",$_POST['m_caratk'.$i]);	
		$tbutir12 = str_replace(",","",$_POST['m_butirl'.$i]);	
		$tcarat12 = str_replace(",","",$_POST['m_caratl'.$i]);	
		
		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		//echo $tbutir3 ."<br>";
		// $tcarat3 ."<br>";
		
		if ($tbutir == ''){$tbutir = 0;}
		if ($tcarat == ''){$tcarat = 0;}
		if ($tbutir2 == ''){$tbutir2 = 0;}
		if ($tcarat2 == ''){$tcarat2 = 0;}
		if ($tbutir3 == ''){$tbutir3 = 0;}
		if ($tcarat3 == ''){$tcarat3 = 0;}
		if ($tbutir4 == ''){$tbutir4 = 0;}
		if ($tcarat4 == ''){$tcarat4 = 0;}
		if ($tbutir5 == ''){$tbutir5 = 0;}
		if ($tcarat5 == ''){$tcarat5 = 0;}
		if ($tbutir6 == ''){$tbutir6 = 0;}
		if ($tcarat6 == ''){$tcarat6 = 0;}
		if ($tbutir7 == ''){$tbutir7 = 0;}
		if ($tcarat7 == ''){$tcarat7 = 0;}
		if ($tbutir8 == ''){$tbutir8 = 0;}
		if ($tcarat8 == ''){$tcarat8 = 0;}
		if ($tbutir9 == ''){$tbutir9 = 0;}
		if ($tcarat9 == ''){$tcarat9 = 0;}
		if ($tbutir10 == ''){$tbutir10 = 0;}
		if ($tcarat10 == ''){$tcarat10 = 0;}
		if ($tbutir11 == ''){$tbutir11 = 0;}
		if ($tcarat11 == ''){$tcarat11 = 0;}
		if ($tbutir12 == ''){$tbutir12 = 0;}
		if ($tcarat12 == ''){$tcarat12 = 0;}
		
		$totbutir = $tbutir + $tbutir2 + $tbutir3 + $tbutir4 + $tbutir5 + $tbutir6 + $tbutir7 + $tbutir8 + $tbutir9 + $tbutir10 + $tbutir11 + $tbutir12 ;
		$totcarat = $tcarat + $tcarat2 + $tcarat3 + $tcarat4 + $tcarat5 + $tcarat6 + $tcarat7 + $tcarat8 + $tcarat9 + $tcarat10 + $tcarat11 + $tcarat12 ;
		
		
		$sql_ceksegmen = "select * from mssegmen where m_hargamin <= ".$thargar." and m_hargamax >= ".$thargar." ";
		$stmt_ceksegmen  = sqlsrv_query( $con_dbnew, $sql_ceksegmen);
		$row_ceksegmen = sqlsrv_fetch_array( $stmt_ceksegmen, SQLSRV_FETCH_ASSOC);
		
		$kdkatg = $row_ceksegmen ['m_kode'];
		$markup = $row_ceksegmen ['m_markup'];
		
		//ambil rate USD
		$sql_cekrate = "select TOP 1 m_beli from msrate where m_kode ='USD K' order by m_tanggal desc";
		$stmt_cekrate  = sqlsrv_query( $con_dbnew, $sql_cekrate);
		$row_cekrate = sqlsrv_fetch_array( $stmt_cekrate, SQLSRV_FETCH_ASSOC);
		
		$trate = $row_cekrate ['m_beli'];
		//echo $new . "<br>";
		if ($ttype = 'K')
		{
			$thargam = ($thargar/$trate) - ( $thargar / $trate * 0.05) ;
			$thargajual = ((($thargar / $trate)-(5/100*($thargar / $trate))) * 2) * 1.15 ;
		}
		
		if ($titem != '')
		{
			
		
			if ( $hapus != 'on' )
			{
				if ( $new == 'Y' )
				{
		
					// Kalau baru, cek stock dulu masih ada ngk !!! 
					$sql_cekstock = "select m_qty from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
					$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
					
					if ($tqty >= $row_cekstock['m_qty'])
					{
						//generate nomor PLU
						$tsqlnoplu = "select max(right(m_productid,4)) as nomormax from t_stockinv where substring(m_productid,3,2) = substring('".$abc[2]."',3,2)";
						$stmtnoplu= sqlsrv_query( $con_dbnew, $tsqlnoplu);
						$rownoplu = sqlsrv_fetch_array( $stmtnoplu, SQLSRV_FETCH_ASSOC);
						$noplumax = $rownoplu['nomormax'];
						if ($noplumax == ''){$noplumax = '0000' ;}
						$noplumax = $noplumax + 1 ;
						
						if ($tkdbrg == 'DJ'){ $np = 'J';} else { $np = 'P';}
						$tnoplu = 'W'.$np.substr($abc[2],-2).$abc[1].substr('0000'.$noplumax,-4) ;
						
						//insert ke table stockinv
						$sql_insertstock = "insert into t_stockinv (m_cabang, m_kodebarang, m_productid,m_qty, m_otw) 
											values('".$tcabang."','".$tkdbrg."','".$tnoplu."',".$tqty.",0)" ;
						$stmt_insertstock  = sqlsrv_query( $con_dbnew, $sql_insertstock);
						
						//insert ke table stockdata
						
						
						//generate nomor kode karet
						
						if ($trubberid =='')
						{	
							$tsqlrub = "select max(substring(m_rubberid,4,4)) as nomormax from t_stockdata 
										where substring(m_rubberid,1,1)= '".$kdkatg."' and substring(m_rubberid,2,1)= '".$titem."' and 
											  substring(m_rubberid,3,1)= '".$tkodesupl."'";
							$stmtrub= sqlsrv_query( $con_dbnew, $tsqlrub);
							$rowrub = sqlsrv_fetch_array( $stmtrub, SQLSRV_FETCH_ASSOC);
							$norubmax = $rowrub['nomormax'];
							if ($norubmax == ''){$norubmax = '0000' ;}
							$norubmax = $norubmax + 1 ;
							
							$trubberid = $kdkatg.$titem.$tkodesupl.substr('0000'.$norubmax,-4).'-001' ;
						}
						else
						{
							$tsqlrub = "select max(substring(m_rubberid,9,3)) as nomormax from t_stockdata 
										where m_rubberid = '".$trubberid."'";
							$stmtrub= sqlsrv_query( $con_dbnew, $tsqlrub);
							$rowrub = sqlsrv_fetch_array( $stmtrub, SQLSRV_FETCH_ASSOC);
							$norubmax = $rowrub['nomormax'];
							if ($norubmax == ''){$norubmax = '000' ;}
							$norubmax = $norubmax + 1 ;
							
							echo $tsqlrub ."<br>";
							
							$trubberid = substr($trubberid,0,7)."-".substr('000'.$norubmax,-3) ;
						}
						
						$sql_insert_stock2 = "insert into t_stockdata (m_cabang, m_productid, m_kodebarang, m_item, m_rubberid, m_kodesupplier, 
																	   m_segmen, m_qty, m_grossweight, m_netweight, m_rate, m_hargam, m_hargar, m_hargajual,
																	   m_butir1, m_carat1, m_butir2, m_carat2, m_butir3, m_carat3, m_butir4, m_carat4,
																	   m_butir5, m_carat5, m_butir6, m_carat6, m_butir7, m_carat7, m_butir8, m_carat8,
																	   m_butir9, m_carat9, m_butir10, m_carat10, m_butir11, m_carat11, m_butir12, m_carat12,
																	   m_totbutir, m_totcarat, m_supplier, m_designer, m_hargabarcode)  
											 values('".$tcabang."','".$tnoplu."','".$tkdbrg."','".$titem."','".$trubberid."','".$tkodesupplier."',
													'".$kdkatg."', ".$tqty.",".$tgross.",".$tgross.",".$trate.",".$thargam.",".$thargar.",".$thargajual.",
													".$tbutir.", ".$tcarat.",".$tbutir2.",".$tcarat2.",".$tbutir3.",".$tcarat3.",".$tbutir4.",".$tcarat4.",
													".$tbutir5.", ".$tcarat5.",".$tbutir6.",".$tcarat6.",".$tbutir7.",".$tcarat7.",".$tbutir8.",".$tcarat8.",
													".$tbutir9.", ".$tcarat9.",".$tbutir10.",".$tcarat10.",".$tcarat11.",".$tcarat11.",".$tbutir12.",".$tcarat12.", ".$totbutir.", ".$totcarat.", '".$tkodesupl."','".$designer."',".$thargajual.")" ;
						//echo $sql_insert_stock2 . "<br>";
						$stmt_insert_stock2  = sqlsrv_query( $con_dbnew, $sql_insert_stock2);
						
						
						//insert ke table ttb2
						$sql_insert_ttb2 = "insert into t_ttb2 (m_cabang, m_nomor, m_productid, m_kodebarang, m_item, m_rubberid, m_kodesupplier, 
																	   m_segmen, m_qty, m_grossweight, m_netweight, m_rate, m_hargam, m_hargar, m_hargajual,
																	   m_butir1, m_carat1, m_butir2, m_carat2, m_butir3, m_carat3, m_butir4, m_carat4,
																	   m_butir5, m_carat5, m_butir6, m_carat6, m_butir7, m_carat7, m_butir8, m_carat8,
																	   m_butir9, m_carat9, m_butir10, m_carat10, m_butir11, m_carat11, m_butir12, m_carat12,
																	   m_totbutir, m_totcarat, m_supplier, m_designer, m_hargabarcode
																	   )  
											 values('".$tcabang."','".$tnomor."','".$tnoplu."','".$tkdbrg."','".$titem."','".$trubberid."','".$tkodesupplier."',
													'".$kdkatg."', ".$tqty.",".$tgross.",".$tgross.",".$trate.",".$thargam.",".$thargar.",".$thargajual.",
													".$tbutir.", ".$tcarat.",".$tbutir2.",".$tcarat2.",".$tbutir3.",".$tcarat3.",".$tbutir4.",".$tcarat4.",
													".$tbutir5.", ".$tcarat5.",".$tbutir6.",".$tcarat6.",".$tbutir7.",".$tcarat7.",".$tbutir8.",".$tcarat8.",
													".$tbutir9.", ".$tcarat9.",".$tbutir10.",".$tcarat10.",".$tcarat11.",".$tcarat11.",".$tcarat12.",".$tcarat12.", ".$totbutir.", ".$totcarat.", '".$tkodesupl."','".$designer."',".$thargajual.")" ;
						//echo $sql_insert_ttb2 . "<br><br>";
						$stmt_insert_ttb2  = sqlsrv_query( $con_dbnew, $sql_insert_ttb2);
						
					}
				}
				else
				{
					//Update data stock 
					$sql_updatestock = "update t_stockdata set m_item = '".$titem."', m_hargam = ".$thargam.", m_hargar = ".$thargar.", 
										m_hargajual = ".$thargajual.", m_grossweight = ".$tgross.", m_netweight = ".$tgross.", 
										m_butir1 = ".$tbutir.", m_carat1 = ".$tcarat.", m_butir2 = ".$tbutir2.", m_carat2 = ".$tcarat2.", 
										m_butir3 = ".$tbutir3.", m_carat3 = ".$tcarat3.", m_butir4 = ".$tbutir4.", m_carat4 = ".$tcarat4.", 
										m_butir5 = ".$tbutir5.", m_carat5 = ".$tcarat5.", 
										m_butir6 = ".$tbutir6.", m_carat6 = ".$tcarat6.", m_butir7 = ".$tbutir7.", m_carat7 = ".$tcarat7.", 
										m_butir8 = ".$tbutir8.", m_carat8 = ".$tcarat8.", 
										m_butir9 = ".$tbutir9.", m_carat9 = ".$tcarat9.", m_butir10 = ".$tbutir10.", m_carat10 = ".$tcarat10." , 
										m_butir11 = ".$tbutir11.", m_carat11 = ".$tcarat11.", m_butir12 = ".$tbutir12.", m_carat12 = ".$tcarat12."  ,
										m_totbutir = ".$tbutir." + ".$tbutir2." + ".$tbutir3." + ".$tbutir4." + ".$tbutir5." + ".$tbutir6." + ".$tbutir7." + ".$tbutir8." + ".$tbutir9." + ".$tbutir10." + ".$tbutir11." + ".$tbutir12.",
										m_totcarat = ".$tcarat." + ".$tcarat2." + ".$tcarat3." + ".$tcarat4." + ".$tcarat5." + ".$tcarat6." + ".$tcarat7." + ".$tcarat8." + ".$tcarat9." + ".$tcarat10." + ".$tcarat11." + ".$tcarat12.",
										m_supplier = '".$tkodesupl."', m_designer = '".$designer."'
										where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
	
					//Update data TTB jika dihapus
					$sql_updatettb   = "update t_ttb2 set m_item = '".$titem."', m_hargam = ".$thargam.", m_hargar = ".$thargar.", 
										m_hargajual = ".$thargajual.", m_grossweight = ".$tgross.", m_netweight = ".$tgross." ,
										m_butir1 = ".$tbutir.", m_carat1 = ".$tcarat.", m_butir2 = ".$tbutir2.", m_carat2 = ".$tcarat2.", 
										m_butir3 = ".$tbutir3.", m_carat3 = ".$tcarat3.", m_butir4 = ".$tbutir4.", m_carat4 = ".$tcarat4.", 
										m_butir5 = ".$tbutir5.", m_carat5 = ".$tcarat5.", 
										m_butir6 = ".$tbutir6.", m_carat6 = ".$tcarat6.", m_butir7 = ".$tbutir7.", m_carat7 = ".$tcarat7.", 
										m_butir8 = ".$tbutir8.", m_carat8 = ".$tcarat8.", 
										m_butir9 = ".$tbutir9.", m_carat9 = ".$tcarat9.", m_butir10 = ".$tbutir10.", m_carat10 = ".$tcarat10."  ,
										m_butir11 = ".$tbutir11.", m_carat11 = ".$tcarat11.", m_butir12 = ".$tbutir12.", m_carat12 = ".$tcarat12."   ,
										m_totbutir = ".$tbutir." + ".$tbutir2." + ".$tbutir3." + ".$tbutir4." + ".$tbutir5." + ".$tbutir6." + ".$tbutir7." + ".$tbutir8." + ".$tbutir9." + ".$tbutir10." + ".$tbutir11." + ".$tbutir12.",
										m_totcarat = ".$tcarat." + ".$tcarat2." + ".$tcarat3." + ".$tcarat4." + ".$tcarat5." + ".$tcarat6." + ".$tcarat7." + ".$tcarat8." + ".$tcarat9." + ".$tcarat10." + ".$tcarat11." + ".$tcarat12.",
										m_supplier = '".$tkodesupl."', m_designer = '".$designer."'
										where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
					
					echo $sql_updatestock . "<br>";
					echo $sql_updatettb . "<br><br>";
				
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					$stmt_updatettb = sqlsrv_query( $con_dbnew, $sql_updatettb);
				}
			}
			else
			{
					//delete data stockinv
					$sql_delstock = "delete from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
	
					//delete data stockdata
					$sql_delstock2 = "delete from t_stockdata where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
	
					//delete data ttb2
					$sql_delttb2 = "delete from t_ttb2 where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
	
					/*
					echo $sql_delstock . "<br>";
					echo $sql_delstock2 . "<br>";
					echo $sql_delttb2 . "<br>";
				*/
					$stmt_delstock  = sqlsrv_query( $con_dbnew, $sql_delstock);
					$stmt_delstock2 = sqlsrv_query( $con_dbnew, $sql_delstock2);
					$stmt_delttb2 = sqlsrv_query( $con_dbnew, $sql_delttb2);
					
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
	header("Location: ttb.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>