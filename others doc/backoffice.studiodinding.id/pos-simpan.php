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
	$tkodecust = $_POST['m_kodecust'];
	$tnama = $_POST['m_nama'];
	$talamat = $_POST['m_alamat'];
	$tkota = $_POST['m_kota'];
	$ttelepon1 = $_POST['m_telepon1'];
	$ttelepon2 = $_POST['m_telepon2'];
	$tket = $_POST['m_keterangan'];
	$tsales = $_POST['m_kodesales'];
	$tstatus = $_POST['m_status'];
	$rate = $_POST['m_rate'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
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
	
	// Kalau baru, create nomor POS 
	
	echo $tnomor ."<br>";
	
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_pos where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		
		echo $tsqlnomor ."<br>";
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'SJ'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;
		
		$tsql = "insert into t_pos (m_cabang, m_nomor, m_tanggal, m_kodecust, m_nama, m_keterangan, m_status, m_kodesales, m_kodesales2, m_type, m_rate) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodecust."','".$tnama."','".$tket."','".$tstatus."','".$tsales."','".$tsales2."','J', ".$rate.")" ;
		
		//$stmt = sqlsrv_query( $con_dbnew, $tsql);
		//echo $tsql;
		//$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_pos set m_keterangan = '".$tket."', m_kodesales = '".$tsales."', m_kodesales2 = '".$tsales2."', m_rate = ".$rate." where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
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
			$tkdbrg = $_POST['m_kodebarang'.$i];
			$tnoplu = $_POST['m_productid'.$i];
			$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
			$tharga = str_replace(",","",$_POST['m_harga'.$i]);	
			$tdisc = str_replace(",","",$_POST['m_disc'.$i]);	
			$tdiscount = str_replace(",","",$_POST['m_discount'.$i]);		
			$tdisc2 = str_replace(",","",$_POST['m_disc2'.$i]);	
			$tdiscount2 = str_replace(",","",$_POST['m_discount2'.$i]);	
			$tdisc3 = str_replace(",","",$_POST['m_disc3'.$i]);	
			$tdiscount3 = str_replace(",","",$_POST['m_discount3'.$i]);	
			$tdiscount4 = str_replace(",","",$_POST['m_discount4'.$i]);	
			
			$new = $_POST['m_new'.$i];
			$hapus = $_POST['m_hapus'.$i];
			
			
			if ($tnoplu != '')
			{
				if ( $new == 'Y' )
				{
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
						
						//Insert table pos2
						$sql_insert = "insert into t_pos2
										(m_cabang, m_nomor, m_kodebarang, m_productid, m_qty, m_harga, m_disc, m_discount
										, m_disc2, m_discount2, m_disc3, m_discount3, m_discount4, m_kurs, m_rate, m_keterangan, m_status)
										 values('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."',".$tqty.",".$tharga.",".$tdisc.", ".$tdiscount.",".$tdisc2.", ".$tdiscount2.",".$tdisc3.", ".$tdiscount3.", ".$tdiscount4.",'IDR',1,'','0')";
						
						echo $sql_insert;
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
						$sql_updatestock = "update t_stockinv set m_qty = m_qty + ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
						$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
		
						//Hapus data transfer
						$sql_hapus = "delete from t_pos2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' ";
						$stmt_hapus = sqlsrv_query( $con_dbnew, $sql_hapus);				
					}
					else
					{
						$sql_updatepos = "	update t_pos2 set m_disc = ".$tdisc.", m_discount = ".$tdiscount.",
											m_disc2 = ".$tdisc2.", m_discount2 = ".$tdiscount2.",
											m_disc3 = ".$tdisc3.", m_discount3 = ".$tdiscount3.", m_discount4 = ".$tdiscount4." 
											where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and 
											m_productid = '".$tnoplu."' and m_nomor = '".$tnomor."'";
						
						//echo $sql_updatepos ."<br>";
						$stmt_updatepos = sqlsrv_query( $con_dbnew, $sql_updatepos);
						
					}
				}
			}
		}
	}
	
	// update total penjualan di header
	$tsqlcek = "
				update t_pos set m_jumlah = b.cototal
				from t_pos a, (
						select m_nomor,  isnull(sum((m_qty * m_harga) - m_discount - m_discount2 - m_discount3 - m_discount4),0) as cototal 
						from t_pos2 
						where m_cabang = '".$kdcab."' and m_nomor = '".$nomor."'
						group by m_nomor) b
				where a.m_nomor = b.m_nomor
						
				";
	$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
	

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
	header("Location: pos.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>