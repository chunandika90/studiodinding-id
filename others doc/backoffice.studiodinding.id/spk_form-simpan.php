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
	$tanggal_jt = $_POST['m_tanggal_jatuh_tempo'];	
	$nama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	
	$ttype = $_POST['m_type'];
	$jumrow = $_POST['jumrow'];
	$designer = $_POST['m_designer'];
	$tukang = $_POST['m_tukang'];
	$ttype = $_POST['m_type'];
	$status_order = $_POST['m_status_order'];

	$konstruksi = $_POST['m_konstruksi'];
	$jenisbarang = $_POST['m_jenisbarang'];
	$segmen = $_POST['m_segmen'];
	$status = 'A';
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	$abc = explode('/',substr($tanggal_jt, 0, 10));
	$tgljt = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	

	$trubberid = $_POST['m_rubberid'];	
	$titem = $_POST['m_item'];	
	$twarna = $_POST['m_warna'];	
	$tringsize = $_POST['m_ringsize'];	
	$tqty = str_replace(",","",$_POST['m_qty']);	
	$tgross = str_replace(",","",$_POST['m_grossweight']);	
	
	// Kalau baru, create nomor POS 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax 
					  from t_spk 
					  where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = substr($abc[2],-2).$abc[1].$titem.substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_spk (m_cabang, m_nomor,m_tanggal, m_designer, m_keterangan, m_status, m_type ,m_user, m_tanggal_jatuh_tempo, m_status_order, m_tukang) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$designer."','".$tket."','A','".$ttype."','".$_SESSION['loginid']."','".$tgljt."','".$status_order."','".$tukang."')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_spk set m_keterangan = '".$tket."', m_designer = '".$designer."', m_type = '".$ttype."', m_tukang = '".$tukang."', m_status_order = '".$status_order."', m_tanggal_jatuh_tempo = '".$tgljt."'
				where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	echo $tsql . "<br>";
	
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
//	echo $return.'<br/>'.$tcabang.'<br/>'.$tnomor.'<br/>'.$tdoc.'<br/>';

		
		
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
						
						$trubberid = $segmen.$titem.'A'.substr('0000'.$norubmax,-4).'-001' ;
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
					
					
					
					
					//insert ke table calculator2
					$sql_insert_spk2 = "insert into t_spk2 (m_cabang, m_nomor, m_rubberid,m_segmen, m_item, m_qty, m_grossweight, m_netweight, m_rate, m_orangkam, m_orangkar, m_olainm, m_olainr, m_opolesm, m_opolesr, m_konstruksi, m_warna, m_ringsize)  
										 values('".$tcabang."','".$tnomor."','".$trubberid."','','".$titem."', ".$tqty.",".$tgross.",".$tgross.",".$trate.",".$orangkam.",".$orangkar.",0,0,".$opolesm.",".$opolesr.",'".$konstruksi."','".$warna."','".$tringsize."')" ;

					$stmt_insert_spk2  = sqlsrv_query( $con_dbnew, $sql_insert_spk2);
					
					
					echo $sql_insert_spk2 . "<br><br>";
					
				}
				else
				{
					//Update data calculator jika dihapus
					$sql_updatecalculator   = "update t_spk2 set m_item = '".$titem."', m_grossweight = ".$tgross.", 
												m_netweight = ".$tgross." , m_warna = '".$twarna."',
												m_orangkam = ".$orangkam.",m_orangkar = ".$orangkar.",
												m_opolesm = ".$opolesm.",m_opolesr = ".$opolesr.", m_konstruksi = '".$konstruksi."', m_ringsize = '".$tringsize."'
												where  m_nomor = '".$tnomor."'  ";
					
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					$stmt_updatecalculator = sqlsrv_query( $con_dbnew, $sql_updatecalculator);
					
					echo $sql_updatecalculator . "<br><br>";
				}
			}
			else
			{
					//delete data calculator2
					$sql_delcalculator2 = "delete from t_spk2 where m_nomor = '".$tnomor."'   ";
	
					$stmt_delcalculator2 = sqlsrv_query( $con_dbnew, $sql_delcalculator2);
					
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
		
		echo $shape."-".$size."-".$butir."-".$carat."-".($carat / $butir )."-".$hargam."=".$Mbatu ."<br><br>";
		echo $shape."-".$size."-".$butir."-".$carat."-".($carat / $butir )."-".$hargar."=".$Rbatu ."<br><br>";

		
		$thargam = $thargam + $Mbatu;
		$thargar = $thargar + $Rbatu;
		
		$tbutir = $tbutir + $butir;
		$tcarat = $tcarat + $carat;
		
		$topbm = $topbm + $Pbm;
		$topbr = $topbr + $Pbr;
		
		if ($shape != '')
		{
		//insert ke table calculator3
			$sql_insertstock4 = "
			if not exists(select m_nomor from t_spk3 where m_nomor = '".$tnomor."' and m_no = '".$b."' and m_shape = '".$shape."' and m_size = '".$size."' )
			begin
				insert into t_spk3 (m_nomor, m_no, m_shape, m_size, m_butir, m_carat, m_hargam, m_hargar, m_opbm, m_opbr)  
				values('".$tnomor."','".$b."','".$shape."','".$size."',".$butir.",".$carat.",".$Mbatu.",".$Rbatu.",".$Pbm.",".$Pbr.")
			end 					
				
			else
			begin
			
			update t_spk3 set m_shape = '".$shape."', m_size = '".$size."', m_butir = ".$butir." ,m_carat = ".$carat." , 
						  m_hargam = ".$Mbatu.", m_hargar = ".$Rbatu.", m_opbm = ".$Pbm.", m_opbr = ".$Pbr."
						  where m_nomor = '".$tnomor."' and m_no = ".$b." 
			
			end
								
								" ;
			$stmt_insertstock4  = sqlsrv_query( $con_dbnew, $sql_insertstock4);
			if( $stmt_insertstock4 === false )
			{
				 echo "Error in executing statement 2.\n";
				 die( print_r( sqlsrv_errors(), true));
			}
			
			
		}
		
		
	}
	//TOTAL ONGKOS
	$Mongkos = ($orangkam + $topbm  + $opolesm);
	$Rongkos = ($orangkar + $topbr  + $opolesr);

	
	//TOTAL HARGAM BATU
	$TotalMbatu = $thargam;
	$TotalRbatu = $thargar;
	
	$totalhargaM = ($TotalMbatu + $Mongkos + $Memas) ;
	$totalhargaR = ($TotalRbatu + $Rongkos + $Remas) ;
	
	
	
	//cek markup
	$sql_ceksegmen = "select m_markup, m_kode from mssegmen_in 
					  where m_kode = '".$segmen."'  order by m_markup asc ";
	$stmt_ceksegmen  = sqlsrv_query( $con_dbnew, $sql_ceksegmen);
	$row_ceksegmen = sqlsrv_fetch_array( $stmt_ceksegmen, SQLSRV_FETCH_ASSOC);
	
	echo $sql_ceksegmen ."<br><br>";
	
	$markup = $row_ceksegmen ['m_markup'];
	
	
	
	
	
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

	$sql_update = "update t_spk2 set m_hargam = ".$totalhargaM.", m_hargar = ".$totalhargaR." ,m_hargajual= ".$totalhargajual." ,
				   m_hargabarcode= ".$totalhargabarcode.", m_opbm = ".$topbm.", m_opbr = ".$topbr.",
				   m_rubberid = '".$trubberid."', m_segmen ='".$segmen."', m_totbutir = ".$tbutir.", m_totcarat = ".$tcarat.",
				   m_markup = ".$markup.", m_emasm = ".$Memas.", m_emasr = ".$Remas.", m_stonem = ".$TotalMbatu.", m_stoner = ".$TotalRbatu."
				   where m_nomor = '".$tnomor."' " ;
	$stmt_update  = sqlsrv_query( $con_dbnew, $sql_update);
	

	
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
	header("Location: spk_form.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>