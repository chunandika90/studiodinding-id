<?php
	session_start();
	set_time_limit(0) ;
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdstore = $_POST['kdstore'];
	$periode  = $_POST['periode'];
	$prm = $_POST['param'];

	$tcabang = $_POST['m_cabang'];
	$tnomor = $_POST['m_nomor'];
	$tanggal = $_POST['m_tanggal'];	
	$tkodesupl = $_POST['m_kodesupl'];
	$tnama = $_POST['m_nama'];
	$tket = $_POST['m_keterangan'];
	$tdoc = $_POST['m_dokumen'];
	$tstatus = $_POST['m_status'];
	$tlokasi = $_POST['m_lokasi'];
	$tharga1 = str_replace(",","",$_POST['m_harga']);
	$tongkos1 = str_replace(",","",$_POST['m_ongkos']);
	$jumrow = $_POST['jumrow'];
	
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");

	// Kalau baru, create nomor Purchase 
	if ($tnomor == '')
	{
		$tketlog = 'ADD';
		
		// Cek dulu, suppliernya baru atau lama !!!
		if ($tkodesupl == '')
		{
			$tsqlnosupl = "select max(right(m_nomor,6)) as nomormax from mssupplier where left(m_kode,2) = '".$tcabang."' and substring(m_kode,4,2) = '".substr($abc[2],-2)."'";
			$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
			$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
			$nomax = $rownosupl['nomormax'];
			if ($nomax == ''){$nomax = '000000' ;}
			$nomax = $nomax + 1 ;
		
			$tkodesupl = $tcabang.'-'.substr($abc[2],-2).substr('000000'.$nomax,-6) ;
			$tsqlsupl = "insert into mssupplier ( m_kode, m_group, m_nama, m_alamat, m_kota, m_telepon1, m_telepon2, m_fax, m_email, m_npwp, m_status )
						values ( '".$kode."', '".$tkodesupl."', '".$nama."', '".$alamat."', '".$kota."', '".$telepon1."', '', '', '', '', '00' )" ;
			$stmtsupl = sqlsrv_query( $con_dbnew, $tsqlsupl);

		}
		
		$tsqlnomor = "select max(right(m_nomor,4)) as nomormax from t_purchase where m_cabang = '".$tcabang."' and year(m_tanggal) = ".$abc[2]." and month(m_tanggal) = ".$abc[1];
		$stmtnomor= sqlsrv_query( $con_dbnew, $tsqlnomor);
		$rownomor = sqlsrv_fetch_array( $stmtnomor, SQLSRV_FETCH_ASSOC);
		$nomax = $rownomor['nomormax'];
		if ($nomax == ''){$nomax = '0000' ;}
		$nomax = $nomax + 1 ;
		
		$tnomor = 'PC'.substr($abc[2],-2).$abc[1].substr('0000'.$nomax,-4) ;

		$tsql = "insert into t_purchase (m_cabang, m_nomor, m_tanggal, m_kodesupl, m_nama, m_keterangan, m_status, m_dokumen, m_harga1, m_ongkos1, t_type) 
				values('".$tcabang."','".$tnomor."','".$tgl."','".$tkodesupl."','".$tnama."','".$tket."','".$tstatus."','".$tdoc."',".$tharga1.",".$tongkos1.", 'M')" ;
	}
	else
	{
		$tketlog = 'EDIT';
		$tsql = "update t_purchase set m_keterangan = '".$tket."', m_dokumen = '".$tdoc."', m_harga1 = ".$tharga1.", m_ongkos1 = ".$tongkos1." where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	}
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = 'M0000001';
		$tnoplu = $_POST['m_productid'.$i];
		$berat =str_replace(",","",$_POST['m_berat'.$i]);	
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$tharga = str_replace(",","",$_POST['m_harga'.$i]);	
		$tongkos = str_replace(",","",$_POST['m_ongkos'.$i]);
		$item = $_POST['m_item'.$i];

		$new = $_POST['m_new'.$i];
		$hapus = $_POST['m_hapus'.$i];
		
		if ($berat < 10){$tnoplu = $item ;}
		if ($item != '' )
		{
			if ( $new == 'Y' )
			{
				if ($tqty > 0)
				{
					$sql_cekstock = "select isnull(count(*),0) as jumrow from t_stockinv where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_cekstock  = sqlsrv_query( $con_dbnew, $sql_cekstock);
					$row_cekstock = sqlsrv_fetch_array( $stmt_cekstock, SQLSRV_FETCH_ASSOC);
					if ($row_cekstock['jumrow'] > 0)
					{
						$sql_updatestock = "update t_stockinv set m_qty = m_qty + ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";						
					}
					else
					{
						$sql_updatestock = "insert into t_stockinv values ('".$tcabang."','".$tkdbrg."','".$tlokasi."','".$tnoplu."',".$tqty.",0,'0',0)";
					}
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					
					$sql_cekdata = "select isnull(count(*),0) as jumrow from t_stockdata where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_cekdata  = sqlsrv_query( $con_dbnew, $sql_cekdata);
					$row_cekdata = sqlsrv_fetch_array( $stmt_cekdata, SQLSRV_FETCH_ASSOC);
					if ($row_cekdata['jumrow'] > 0)
					{
						$sql_updatedata = "update t_stockdata set m_item = '".$item."', m_hargam = ".$tharga.", m_hargar = ".$tharga.", m_netweight = ".$berat.", m_grossweight = ".$berat." where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";						
					}
					else
					{
						$sql_updatedata = "insert into t_stockdata (m_kodebarang, m_productid, m_item, m_klasifikasi, m_kategori, m_segmen, m_warna, m_distribusi, m_harga, m_hargam, m_hargar, m_kadar, m_framematerial, m_netweight)
											values ('".$tkdbrg."','".$tnoplu."','".$item."','PGL','LM','LM','KNG','LM',0,".$tharga.",".$tharga.",0.99,'EMS')";
					}
					$stmt_updatedata  = sqlsrv_query( $con_dbnew, $sql_updatedata);

					
					$sql_cekdet = "select isnull(count(*),0) as jumrow from t_purchase2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_cekdet  = sqlsrv_query( $con_dbnew, $sql_cekdet);
					$row_cekdet = sqlsrv_fetch_array( $stmt_cekdet, SQLSRV_FETCH_ASSOC);
					if ($row_cekdet['jumrow'] > 0)
					{
						$sql_updatedet = "update t_purchase2 set m_harga = ".$tharga.", m_ongkos= ".$tongkos." where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and  m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";						
					}
					else
					{
						$sql_updatedet = "insert into t_purchase2 values ('".$tcabang."','".$tnomor."','".$tkdbrg."','".$tnoplu."','".$tlokasi."',".$tqty.",".$tharga.",'IDR',1, '','Y',".$tongkos.")";
					}
					$stmt_updatedet  = sqlsrv_query( $con_dbnew, $sql_updatedet);
				}
			}
			else
			{
				if ($hapus == 'on')
				{
					$sql_updatestock = "update t_stockinv set m_qty = m_qty - ".$tqty." where m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
					$stmt_updatestock  = sqlsrv_query( $con_dbnew, $sql_updatestock);
					
					$sql_updatedet = "delete from t_purchase2 where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and  m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";
					$stmt_updatedet  = sqlsrv_query( $con_dbnew, $sql_updatedet);
				}
				else
				{
					$sql_updatedata = "update t_stockdata set m_item = '".$item."', m_hargam = ".$tharga.", m_hargar = ".$tharga." where m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";						
					$stmt_updatedata  = sqlsrv_query( $con_dbnew, $sql_updatedata);

					$sql_updatedet = "update t_purchase2 set m_harga = ".$tharga.", m_ongkos= ".$tongkos." where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and  m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."'";						
					$stmt_updatedet  = sqlsrv_query( $con_dbnew, $sql_updatedet);
				}
			}
		}
	}
	sqlsrv_close($con_dbnew);
	header("Location: purchaselm.php?st=".base64_encode($_POST['m_cabang'])."&pr=".base64_encode($_POST['periode'])."&nm=".base64_encode($tnomor)."&prm=".base64_encode($prm));

?>