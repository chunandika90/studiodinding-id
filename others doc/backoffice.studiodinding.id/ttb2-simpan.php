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
	$tkdbrg = $_POST['m_kodebarang'];
	$ttype = $_POST['m_type'];
	$jumrow = $_POST['jumrow'];
	$designer = $_POST['m_designer'];
	$konstruksi = $_POST['m_konstruksi'];
	$jenisbarang = $_POST['m_jenisbarang'];
	$tukang = $_POST['m_tukang'];
	$segmen = $_POST['m_segmen'];
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
		
		$tsql = "insert into t_ttb (m_cabang, m_nomor,m_tanggal, m_supplier, m_keterangan, m_dosupplier, m_status ,m_type, m_kodebarang, m_designer, m_tukang) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodesupl."','".$tket."','".$dosupplier."','".$status."','".$ttype."','".$tkdbrg."','".$designer."','".$tukang."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_ttb set m_keterangan = '".$tket."', 
								  m_supplier = '".$tkodesupl."', 
								  m_dosupplier = '".$dosupplier."', 
								  m_type = '".$ttype."',
								  m_kodebarang = '".$tkdbrg."',
								  m_tukang = '".$tukang."',
								  m_designer = '".$designer."'
				where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	if( $stmt === false )
	{
		 echo "Error in executing statement 1.\n" . $tsql ."<br>";
		 die( print_r( sqlsrv_errors(), true));
	}
	else
	{

			$tnoplu = $_POST['m_productid'];	
			$trubberid = $_POST['m_rubberid'];	
			$tkodesupplier = $_POST['m_kodesupplier'];	
			$titem = $_POST['m_item'];	
			$tqty = str_replace(",","",$_POST['m_qty']);	
			$tgross = str_replace(",","",$_POST['m_grossweight']);	
			
			
			$tolainm = str_replace(",","",$_POST['m_olainm']);		
			$tolainr = str_replace(",","",$_POST['m_olainr']);		
			
			$new = $_POST['m_new'];
			$hapus = $_POST['m_hapus'];
			
			
			//ambil rate USD
			$sql_cekrate = "select TOP 1 m_beli from msrate where m_kode ='USD I' order by m_tanggal desc";
			$stmt_cekrate  = sqlsrv_query( $con_dbnew, $sql_cekrate);
			$row_cekrate = sqlsrv_fetch_array( $stmt_cekrate, SQLSRV_FETCH_ASSOC);
			
			
			$trate = $row_cekrate ['m_beli'];
			
			//ambil harga emas
			$sql_cekemas = "select TOP 1 m_beli, m_jual from msrate where m_kode ='EMS' order by m_tanggal desc";
			$stmt_cekemas  = sqlsrv_query( $con_dbnew, $sql_cekemas);
			$row_cekemas = sqlsrv_fetch_array( $stmt_cekemas, SQLSRV_FETCH_ASSOC);
			
			$rate_e_m = $row_cekemas ['m_beli'];
			$rate_e_r = $row_cekemas ['m_jual'];
			
			$Memas = ($rate_e_m * $tgross) ;
			$Remas = ($rate_e_r * $tgross) ;
			
			
			//ambil ongkos rangka M dan R
			$sql_cekrangka = "select TOP 1 m_hargam, m_hargar 
							from msorangka_new where m_segmen ='".$segmen."' and m_item = '".$titem."' order by m_tanggal desc";
			$stmt_cekrangka  = sqlsrv_query( $con_dbnew, $sql_cekrangka);
			$row_cekrangka = sqlsrv_fetch_array( $stmt_cekrangka, SQLSRV_FETCH_ASSOC);
			
			$orangkam = $row_cekrangka ['m_hargam'] / $trate;
			$orangkar = $row_cekrangka ['m_hargar'] / $trate;
			
			
			//ambil ongkos poles chrome M dan R
			$sql_cekpoles = "select TOP 1 m_hargam, m_hargar 
							from msopoles where m_konstruksi ='".$konstruksi."' and m_item = '".$titem."' order by m_tanggal desc";
			
			echo $sql_cekpoles ."<br>";
			$stmt_cekpoles  = sqlsrv_query( $con_dbnew, $sql_cekpoles);
			$row_cekpoles = sqlsrv_fetch_array( $stmt_cekpoles, SQLSRV_FETCH_ASSOC);
			
			$opolesm = $row_cekpoles ['m_hargam'] / $trate;
			$opolesr = $row_cekpoles ['m_hargar'] / $trate;
			
			
			
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
							
							
							
							$sql_insert_stock2 = "insert into t_stockdata (m_cabang, m_productid, m_kodebarang, m_rubberid, m_kodesupplier, m_segmen, m_item, m_qty, m_grossweight, m_netweight, m_rate, m_supplier, m_designer, m_orangkam, m_orangkar, m_olainm, m_olainr, m_opolesm, m_opolesr, m_konstruksi, m_jenisbarang)  
												 values('".$tcabang."','".$tnoplu."','".$tkdbrg."','','".$tkodesupplier."','','".$titem."', ".$tqty.",".$tgross.",".$tgross.",".$trate.", '".$tkodesupl."','".$designer."',".$orangkam.",".$orangkar.",".$tolainm.",".$tolainr.",".$opolesm.",".$opolesr.",'".$konstruksi."','".$jenisbarang."')" ;
							
							$stmt_insert_stock2  = sqlsrv_query( $con_dbnew, $sql_insert_stock2);
							
							
							//insert ke table ttb2
							$sql_insert_ttb2 = "insert into t_ttb2 (m_cabang, m_nomor, m_productid, m_kodebarang, m_rubberid, m_kodesupplier,m_segmen, m_item, m_qty, m_grossweight, m_netweight, m_rate, m_supplier, m_designer, m_orangkam, m_orangkar, m_olainm, m_olainr, m_opolesm, m_opolesr, m_konstruksi, m_jenisbarang)  
												 values('".$tcabang."','".$tnomor."','".$tnoplu."','".$tkdbrg."','','".$tkodesupplier."','','".$titem."', ".$tqty.",".$tgross.",".$tgross.",".$trate.", '".$tkodesupl."','".$designer."',".$orangkam.",".$orangkar.",".$tolainm.",".$tolainr.",".$opolesm.",".$opolesr.",'".$konstruksi."','".$jenisbarang."')" ;

							$stmt_insert_ttb2  = sqlsrv_query( $con_dbnew, $sql_insert_ttb2);
							
							
							echo $sql_insert_stock2 . "<br>";
							echo $sql_insert_ttb2 . "<br><br>";
						}
					}
					else
					{
						//Update data stock 
						$sql_updatestock = "update t_stockdata set m_item = '".$titem."',m_kodesupplier = '".$tkodesupplier."', m_grossweight = ".$tgross.", 
											m_netweight = ".$tgross.",
											m_orangkam = ".$orangkam.",m_orangkar = ".$orangkar.",m_olainm = ".$tolainm.",m_olainr = ".$tolainr.",
											m_opolesm = ".$opolesm.",m_opolesr = ".$opolesr.", m_konstruksi = '".$konstruksi."', m_jenisbarang = '".$jenisbarang."'
											where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
							
						
						//Update data TTB jika dihapus
						$sql_updatettb   = "update t_ttb2 set m_item = '".$titem."',m_kodesupplier = '".$tkodesupplier."', m_grossweight = ".$tgross.", 
											m_netweight = ".$tgross." ,
											m_orangkam = ".$orangkam.",m_orangkar = ".$orangkar.",m_olainm = ".$tolainm.",m_olainr = ".$tolainr.",
											m_opolesm = ".$opolesm.",m_opolesr = ".$opolesr.", m_konstruksi = '".$konstruksi."', m_jenisbarang = '".$jenisbarang."'
											where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'  ";
						
						$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
						$stmt_updatettb = sqlsrv_query( $con_dbnew, $sql_updatettb);
						
						echo $sql_updatestock . "<br>";
						echo $sql_updatettb . "<br><br>";
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
		
						$stmt_delstock  = sqlsrv_query( $con_dbnew, $sql_delstock);
						$stmt_delstock2 = sqlsrv_query( $con_dbnew, $sql_delstock2);
						$stmt_delttb2 = sqlsrv_query( $con_dbnew, $sql_delttb2);
						
				}
			}
				
		
		$jumrow2 = $_POST['jumrow2'];
		
		$thargam  = 0;
		$thargar  = 0;
		$tbutir  = 0;
		$tcarat  = 0;
		$topbm  = 0;
		$topbr  = 0;
		
		echo $tnoplu ."<br>";
		for ($b = 1; $b <= $jumrow2; $b++) 
		{
			
			$shape = $_POST['m_shape'.$b];	
			$size = $_POST['m_size'.$b];	
			$anew = $_POST['a_new'.$b];	
			
			$butir = str_replace(",","",$_POST['m_butir'.$b]);	
			$carat = str_replace(",","",$_POST['m_carat'.$b]);	
			$hargam = str_replace(",","",$_POST['m_hargam'.$b]);	
			$hargar = str_replace(",","",$_POST['m_hargar'.$b]);	
			$opbm = str_replace(",","",$_POST['m_opbm'.$b]);	
			$opbr = str_replace(",","",$_POST['m_opbr'.$b]);	
			
			$Mbatu = $carat  * $hargam;
			$Rbatu = $carat  * $hargar;
			$Pbm = ($butir  * $opbm) / $trate;
			$Pbr = ($butir  * $opbr) / $trate;
			
			echo $anew."-".$size."-".$butir."-".$carat."-".($carat / $butir )."-".$hargam."=".$Mbatu ."<br><br>";
			echo $anew."-".$size."-".$butir."-".$carat."-".($carat / $butir )."-".$hargar."=".$Rbatu ."<br><br>";

			
			$thargam = $thargam + $Mbatu;
			$thargar = $thargar + $Rbatu;
			
			$tbutir = $tbutir + $butir;
			$tcarat = $tcarat + $carat;
			
			$topbm = $topbm + $Pbm;
			$topbr = $topbr + $Pbr;
			if ( $anew == 'Y' )
			{
				if ($shape != '')
				{
				//insert ke table ttb3
					$sql_insertstock4 = "insert into t_ttb3 (m_nomor, m_productid, m_no, m_shape, m_size, m_butir, m_carat, m_hargam, m_hargar, m_opbm, m_opbr)  
					values('".$tnomor."','".$tnoplu."','".$b."','".$shape."','".$size."',".$butir.",".$carat.",".$Mbatu.",".$Rbatu.",".$Pbm.",".$Pbr.")" ;
					$stmt_insertstock4  = sqlsrv_query( $con_dbnew, $sql_insertstock4);
					
					
				//insert ke table stockdetail
					$sql_insertstock5 = "insert into t_stockdetail (m_productid, m_no, m_shape, m_size, m_butir, m_carat, m_hargam, m_hargar, m_opbm, m_opbr)  
					values('".$tnoplu."','".$b."','".$shape."','".$size."',".$butir.",".$carat.",".$Mbatu.",".$Rbatu.",".$Pbm.",".$Pbr.")" ;
					$stmt_insertstock5  = sqlsrv_query( $con_dbnew, $sql_insertstock5);
					
					
					$sql_insertstock6 = "update t_ttb2 set m_butir".$b." = ".$butir." ,m_carat".$b." = ".$carat."
										 where m_productid = '".$tnoplu."' and m_nomor = '".$tnomor."' " ;
					$stmt_insertstock6  = sqlsrv_query( $con_dbnew, $sql_insertstock6);
					
					$sql_insertstock7 = "update t_stockdata set m_butir".$b." = ".$butir." ,m_carat".$b." = ".$carat."
										 where m_productid = '".$tnoplu."' " ;
					$stmt_insertstock7  = sqlsrv_query( $con_dbnew, $sql_insertstock7);
					
					
					echo $sql_insertstock4 ."<br>";
					echo $sql_insertstock5 ."<br>";
				}
			}
			else
			{
				if ($shape != '')
				{
					$detail_update = "update t_ttb3 set m_shape = '".$shape."', m_size = '".$size."', m_butir = ".$butir." ,m_carat = ".$carat." , 
									  m_hargam = ".$Mbatu.", m_hargar = ".$Rbatu.", m_opbm = ".$Pbm.", m_opbr = ".$Pbr."
									  where m_nomor = '".$tnomor."' and m_productid = '".$tnoplu."' and m_no = ".$b." ";
					$detail_update2 = "update t_stockdetail set m_shape = '".$shape."', m_size = '".$size."', m_butir = ".$butir." ,m_carat = ".$carat."  , 
									  m_hargam = ".$Mbatu.", m_hargar = ".$Rbatu.", m_opbm = ".$Pbm.", m_opbr = ".$Pbr."
									   where m_productid = '".$tnoplu."'  and m_no = ".$b." ";
					$stmt_detail  = sqlsrv_query( $con_dbnew, $detail_update);
					$stmt_detail2  = sqlsrv_query( $con_dbnew, $detail_update2);
					
					
					$detail_update3 = "update t_ttb2 set m_butir".$b." = ".$butir." ,m_carat".$b." = ".$carat."
										 where m_productid = '".$tnoplu."' and m_nomor = '".$tnomor."'   " ;
					$stmt_detail3  = sqlsrv_query( $con_dbnew, $detail_update3);
					
					$detail_update4 = "update t_stockdata set m_butir".$b." = ".$butir." ,m_carat".$b." = ".$carat."
										 where m_productid = '".$tnoplu."' " ;
					$stmt_detail4  = sqlsrv_query( $con_dbnew, $detail_update4);
					
					
				}
			}
			
		}
		//TOTAL ONGKOS
		$Mongkos = ($orangkam + $topbm  + $opolesm);
		$Rongkos = ($orangkar + $topbr  + $opolesr);

		
		//TOTAL HARGAM BATU
		$TotalMbatu = $thargam;
		$TotalRbatu = $thargar;
		
		$totalhargaM = ($TotalMbatu + $Mongkos + $Memas) + $tolain ;
		$totalhargaR = ($TotalRbatu + $Rongkos + $Remas) ;
		
		
		//cek markup
		$sql_ceksegmen = "select m_markup, m_kode from mssegmen_in 
						  where m_kode = '".$segmen."'  order by m_markup asc ";
		$stmt_ceksegmen  = sqlsrv_query( $con_dbnew, $sql_ceksegmen);
		$row_ceksegmen = sqlsrv_fetch_array( $stmt_ceksegmen, SQLSRV_FETCH_ASSOC);
		
		echo $sql_ceksegmen ."<br><br>";
		
		$markup = $row_ceksegmen ['m_markup'];
		
		
		if ( $new == 'Y' )
		{
			if ($trubberid =='')
			{	
				$tsqlrub = "select max(substring(m_rubberid,4,4)) as nomormax from t_stockdata 
							where substring(m_rubberid,1,1)= '".$segmen."' and substring(m_rubberid,2,1)= '".$titem."' and 
								  substring(m_rubberid,3,1)= '".$tkodesupl."'";
				$stmtrub= sqlsrv_query( $con_dbnew, $tsqlrub);
				$rowrub = sqlsrv_fetch_array( $stmtrub, SQLSRV_FETCH_ASSOC);
				$norubmax = $rowrub['nomormax'];
				if ($norubmax == ''){$norubmax = '0000' ;}
				$norubmax = $norubmax + 1 ;
				
				$trubberid = $segmen.$titem.$tkodesupl.substr('0000'.$norubmax,-4).'-001' ;
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
				
				
				$trubberid = substr($trubberid,0,7)."-".substr('000'.$norubmax,-3) ;
			}
		}
		
		
		$totalhargajual = ($totalhargaR * $markup) + $tolainr;
		
		$totalhargabarcode = ($totalhargajual *2) *1.15 ;


		echo "TOTAL M EMAS =". $Memas ."<br>";
		echo "TOTAL R EMAS =". $Remas ."<br>";
		
		echo "TOTAL M Ongkos Rangka M =". $orangkam ."<br>";
		echo "TOTAL R Ongkos Rangka R=". $orangkar ."<br>";
		
		echo "TOTAL M Ongkos Poles M =". $opolesm ."<br>";
		echo "TOTAL R Ongkos Poles R=". $opolesr ."<br>";
		
		echo "TOTAL M Ongkos PB =". $topbm ."<br>";
		echo "TOTAL R Ongkos PB =". $topbr ."<br>";
		
		echo "TOTAL M Ongkos =". $Mongkos ."<br>";
		echo "TOTAL R Ongkos =". $Rongkos ."<br>";
		
		echo "TOTAL M Batu =". $TotalMbatu ."<br>";
		echo "TOTAL R Batu =". $TotalRbatu ."<br>";
		
		echo "TOTAL M Ongkos lain M =". $tolainm ."<br>";
		echo "TOTAL R Ongkos lain R=". $tolainr ."<br>";
		
		echo "TOTAL M  =". $totalhargaM ."<br>";
		echo "TOTAL R  =". $totalhargaR ."<br>";
		echo "MARKUP  =". $markup ."<br>";
		
		echo "TOTAL Jual =". $totalhargajual ."<br>";
		echo "TOTAL Barcode =". $totalhargabarcode ."<br><br>";

		$sql_update = "update t_ttb2 set m_hargam = ".$totalhargaM.", m_hargar = ".$totalhargaR." ,m_hargajual= ".$totalhargajual." ,
					   m_hargabarcode= ".$totalhargabarcode.", m_opbm = ".$topbm.", m_opbr = ".$topbr.",
					   m_rubberid = '".$trubberid."', m_segmen ='".$segmen."', m_totbutir = ".$tbutir.", m_totcarat = ".$tcarat.",
					   m_markup = ".$markup.", m_emasm = ".$Memas.", m_emasr = ".$Remas.", m_stonem = ".$TotalMbatu.", m_stoner = ".$TotalRbatu."
						where m_productid = '".$tnoplu."' and m_nomor = '".$tnomor."' " ;
		$stmt_update  = sqlsrv_query( $con_dbnew, $sql_update);
		
		
		$sql_update2 = "update t_stockdata set m_hargam = ".$totalhargaM.", m_hargar = ".$totalhargaR." ,m_hargajual= ".$totalhargajual.",
						m_hargabarcode= ".$totalhargabarcode.", m_opbm = ".$topbm.", m_opbr = ".$topbr.",
						m_rubberid = '".$trubberid."', m_segmen ='".$segmen."', m_totbutir = ".$tbutir.", m_totcarat = ".$tcarat.",
						m_markup = ".$markup.", m_emasm = ".$Memas.", m_emasr = ".$Remas.", m_stonem = ".$TotalMbatu.", m_stoner = ".$TotalRbatu."
						where m_productid = '".$tnoplu."'  " ;
		$stmt_update2  = sqlsrv_query( $con_dbnew, $sql_update2);
		
	}
	
	echo $sql_update . "<br><br>";
	echo $sql_update2 . "<br><br>";
	
	
	
	
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
	header("Location: ttb2.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>