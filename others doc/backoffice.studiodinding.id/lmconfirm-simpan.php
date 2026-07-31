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
	$tlokasi = $_POST['m_lokasi'];
	$tlokasi2 = $_POST['m_lokasi2'];
	$tkodebarang = $_POST['m_kodebarang'];
	$jumrow = $_POST['jumrow'];
	$confirmby = $_SESSION['nama'];
	$tket = 'Confirm';
	
	$tanggal = date("d/m/Y");
	$abc = explode('/',substr($tanggal, 0, 10));
	$tgl = $abc[2].'/'.$abc[1].'/'.$abc[0].' '.date("H:i:s");
	
	$tsqlasal = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi."' ";
	$stmtasal= sqlsrv_query( $con_dbnew, $tsqlasal);
	$rowasal = sqlsrv_fetch_array( $stmtasal, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowasal['m_kode2']) ;
	$tasal = $dumb[0];
	$idasal = $dumb[1];
	$nmasal = $rowasal['m_nama'];
	
	$tsqltujuan = "select m_kode2,m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$tlokasi2."' ";
	$stmttujuan= sqlsrv_query( $con_dbnew, $tsqltujuan);
	$rowtujuan = sqlsrv_fetch_array( $stmttujuan, SQLSRV_FETCH_ASSOC);
	$dumb = explode('-',$rowtujuan['m_kode2']) ;
	$ttujuan = $dumb[0];
	$idtujuan = $dumb[1];
	$nmtujuan = $rowtujuan['m_nama'];
	
/*	
	if ($tkodebarang == 'J0000001')
	{
		//Cari ID kirim Nomor Jaws
		$tjawsout = $_POST['m_outid'];
		$tsqlout = "Select ID from palace_db.dbo.StockOutgoingDJ where Nomor = '".$tjawsout."' ";
		$stmtout= sqlsrv_query( $con_dbnew, $tsqlout);
		$rowout = sqlsrv_fetch_array( $stmtout, SQLSRV_FETCH_ASSOC);
		$idout = $rowout ['ID'];
		
		//Create Nomor Incoming jaws
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.StockIncomingDJ where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '000000';}
		$nojaws = $nojaws + 1 ;
		$tjawsin = 'TRM/DJ/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//inster table incoming jaws
		$tsqljaws = "insert into palace_db.dbo.StockInComingDJ values ('".$idout."','".$tjawsin."','".$tgl."','".$tket."',2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."')" ;
		
	}
	
	else if ($tkodebrg == 'L0000001')
	{
		//Cari ID kirim Nomor Jaws
		$tjawsout = $_POST['m_outid'];
		$tsqlout = "Select ID from palace_db.dbo.StockOutgoingLD where Nomor = '".$tjawsout."' ";
		$stmtout= sqlsrv_query( $con_dbnew, $tsqlout);
		$rowout = sqlsrv_fetch_array( $stmtout, SQLSRV_FETCH_ASSOC);
		$idout = $rowout ['ID'];
		
		//Create Nomor Incoming jaws
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.StockIncomingLD where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '000000';}
		$nojaws = $nojaws + 1 ;
		$tjawsin = 'TRM/LD/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//inster table incoming jaws
		$tsqljaws = "insert into palace_db.dbo.StockInComingLD values ('".$idout."','".$tjawsin."','".$tgl."','".$tket."',2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."')" ;
	}
	else
	{
		//Cari ID kirim Nomor Jaws
		$tjawsout = $_POST['m_outid'];
		$tsqlout = "Select ID from palace_db.dbo.StockOutgoingPG where Nomor = '".$tjawsout."' ";
		
		$stmtout= sqlsrv_query( $con_dbnew, $tsqlout);
		$rowout = sqlsrv_fetch_array( $stmtout, SQLSRV_FETCH_ASSOC);
		$idout = $rowout ['ID'];
		
		//Create Nomor Incoming jaws
		$tsqlnomorjaws = "select max(right(nomor,4)) as nomormax from palace_db.dbo.StockIncomingPG where year(tgl) = ".$abc[2]." and month(tgl) = ".$abc[1];
		$stmtnomorjaws= sqlsrv_query( $con_dbnew, $tsqlnomorjaws);
		$rownomorjaws = sqlsrv_fetch_array( $stmtnomorjaws, SQLSRV_FETCH_ASSOC);
		$nojaws = $rownomorjaws['nomormax'];
		if ($nojaws == ''){$nojaws = '000000';}
		$nojaws = $nojaws + 1 ;
		$tjawsin = 'TRM/PG/'.substr($abc[2],-2).'/'.$abc[1].'/'.substr('0000'.$nojaws,-4) ;
		
		//inster table incoming jaws
		$tsqljaws = "insert into palace_db.dbo.StockInComingPG values ('".$idout."','".$tjawsin."','".$tgl."','".$tket."',2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."')" ;
	}
		$stmtjaws = sqlsrv_query( $con_dbnew, $tsqljaws);
*/
	$tsql = "update t_transfer set m_confirm = '".$confirmby."', m_tglconfirm = '".$tgl."', m_inid = '".$tjawsin."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."'";
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	if( $stmt === false )
	{
		 echo "Error in executing statement 7.\n";
		 die( print_r( sqlsrv_errors(), true));
	}		

	for ($i = 1; $i <= $jumrow; $i++) 
	{
		$tkdbrg = $_POST['m_kodebarang'.$i];		
		$tnoplu = $_POST['m_productid'.$i];
		
		$tqty = str_replace(",","",$_POST['m_qty'.$i]);	
		$confirm = $_POST['m_confirm'.$i];
		$tket = $_POST['m_keterangan'.$i];
		
		if ($confirm == 'on') { $tstat = 'Y'; } else {$tstat = 'T';}
		
		// Cek dulu status kembali .... 
		$tsqlcek = "	select 	a.m_qty, b.m_harga 
						from 	t_transfer2 a, t_stockinv b 
						where 	a.m_cabang = '".$tcabang."' and 
								a.m_nomor = '".$tnomor."' and 
								a.m_kodebarang = '".$tkdbrg."' and 
								a.m_productid = '".$tnoplu."' and 
								a.m_cabang = b.m_cabang and 
								a.m_kodebarang = b.m_kodebarang and 
								a.m_lokasi = b.m_lokasi and 
								a.m_productid = b.m_productid" ;
		$stmtcek = sqlsrv_query( $con_dbnew, $tsqlcek);
		$rowcek = sqlsrv_fetch_array( $stmtcek, SQLSRV_FETCH_ASSOC);
		
		if ( $tstat == 'Y' )
		{

			$tsql2 = "update t_transfer2 set m_keterangan = '".$tket."', m_status = '".$tstat."' where m_cabang = '".$tcabang."' and m_nomor = '".$tnomor."' and m_productid = '".$tnoplu."'";
			$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
			if( $stmt2 === false )
			{
				 echo "Error in executing statement 6.\n";
				 die( print_r( sqlsrv_errors(), true));
			}		
			
			// cek dulu ditable stockinv sudah ada belum noplunya, kalo belum ada di insert kalo udah ada di update aja
			$tsqlcekplu = "select m_productid from t_stockinv where m_cabang = '".substr($tlokasi2,0,2)."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi2."'";
			$stmtcekplu = sqlsrv_query( $con_dbnew, $tsqlcekplu);
			$rowcekplu = sqlsrv_fetch_array( $stmtcekplu, SQLSRV_FETCH_ASSOC);
			if( $stmtcekplu === false )
			{
				 echo "Error in executing statement 1.\n";
				 die( print_r( sqlsrv_errors(), true));
			}		

			if ($rowcekplu['m_productid'] == '')
			{
				$tsqladd = "insert into t_stockinv values ('".substr($tlokasi2,0,2)."','".$tkdbrg."','".$tlokasi2."','".$tnoplu."',".$rowcek['m_qty'].",".$rowcek['m_harga'].", 0, 0)";
				$stmtadd = sqlsrv_query( $con_dbnew, $tsqladd);		
				if( $stmtadd === false )
				{
					 echo "Error in executing statement 2.\n";
					 die( print_r( sqlsrv_errors(), true));
				}		
			}
			else
			{
				$tsqlupd = "update 	t_stockinv 
							set		m_qty = dbo.f_hitstock('".substr($tlokasi2,0,2)."','".$tkdbrg."','".$tnoplu."') 
							where 	m_cabang = '".substr($tlokasi2,0,2)."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi2."'";
				$stmtupd = sqlsrv_query( $con_dbnew, $tsqlupd);
				if( $stmtupd === false )
				{
					 echo "Error in executing statement 3.\n";
					 die( print_r( sqlsrv_errors(), true));
				}				
			}
			$tsqlupd3 = "update 	t_stockinv 
						set		m_otw = dbo.f_hitotw('".$tcabang."','".$tkdbrg."','".$tnoplu."') 
						where 	m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
			$stmtupd3 = sqlsrv_query( $con_dbnew, $tsqlupd3);
			if( $stmtupd3 === false )
			{
				 echo "Error in executing statement 4.\n";
				 die( print_r( sqlsrv_errors(), true));
			}		
			
		}
		else
		{
			$tsqlupd2 = "update t_stockinv 
						set		m_otw = dbo.f_hitotw('".$tcabang."','".$tkdbrg."','".$tnoplu."') 
								m_qty = dbo.f_hitstock('".$tcabang."','".$tkdbrg."','".$tnoplu."') 
						where 	m_cabang = '".$tcabang."' and m_kodebarang = '".$tkdbrg."' and m_productid = '".$tnoplu."' and m_lokasi = '".$tlokasi."'";
			$stmtupd2 = sqlsrv_query( $con_dbnew, $tsqlupd2);
			if( $stmtupd2 === false )
			{
				 echo "Error in executing statement 5.\n";
				 die( print_r( sqlsrv_errors(), true));
			}		
		}
		
		/*
		if 	($tkodebarang == 'J0000001')
		{
			
			//Insert table StockInComingDJ_Product
				$sql_jaws = "insert into palace_db.dbo.StockInComingDJ_Product 
								select a.ID,b.ID,c.SubStorage,1,2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockInComingDJ a, palace_db.dbo.StockProductDJ B , palace_db.dbo.StockOutGoingDJ_Product c
								where  a.Nomor = '".$tjawsin."' and
									   b.Nomor = '".$tnoplu."' and
									   b.ID = c.IDproduct and
									   c.IDform = '".$idout."'
								";
				
				$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);			
				
			//Insert table StockLedgerDJ
				$sql_jaws2 = "insert into palace_db.dbo.StockLedgerDJ 
								select b.ID,'".$ttujuan."','".$idtujuan."','".$nmtujuan."',1,4,'".$tjaws2."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockProductDJ B 
								where b.Nomor = '".$tnoplu."'
								";
				$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);
			
			//Update Stock Actual DJ
				$sql_jaws3 = " update palace_db.dbo.StockActualDJ set TipeLokasi = '".$ttujuan."',NamaLokasi = '".$nmtujuan."',IDlokasi = '".$idtujuan."'
								
								where Nomor = '".$tnoplu."'
								";
				$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);
		}
		else if ($tkodebarang == 'L0000001')
		{
			//Insert table StockInComingLD_Product
				$sql_jaws = "insert into palace_db.dbo.StockInComingLD_Product 
								select a.ID,b.ID,c.SubStorage,1,2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockInComingLD a, palace_db.dbo.StockProductLD B , palace_db.dbo.StockOutGoingLD_Product c
								where  a.Nomor = '".$tjawsin."' and
									   b.Nomor = '".$tnoplu."' and
									   b.ID = c.IDproduct and
									   c.IDform = '".$idout."'
								";
				
				$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);			
				
			//Insert table StockLedgerLD
				$sql_jaws2 = "insert into palace_db.dbo.StockLedgerLD
								select b.ID,'".$ttujuan."','".$idtujuan."','".$nmtujuan."',1,4,'".$tjaws2."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockProductLD B 
								where b.Nomor = '".$tnoplu."'
								";
				$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);
			
			//Update Stock Actual LD
				$sql_jaws3 = " update palace_db.dbo.StockActualLD set TipeLokasi = '".$ttujuan."',NamaLokasi = '".$nmtujuan."',IDlokasi = '".$idtujuan."'
								
								where Nomor = '".$tnoplu."'
								";
				$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);
		}
		else 
		{
			//Insert table StockInComingPG_Product
				$sql_jaws = "insert into palace_db.dbo.StockInComingPG_Product 
								select a.ID,b.ID,c.SubStorage,1,2,'".$confirmby."','".$tgl."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockInComingPG a, palace_db.dbo.StockProductPG B , palace_db.dbo.StockOutGoingPG_Product c
								where  a.Nomor = '".$tjawsin."' and
									   b.Nomor = '".$tnoplu."' and
									   b.ID = c.IDproduct and
									   c.IDform = '".$idout."'
								";
				$stmt_jaws  = sqlsrv_query( $con_dbnew, $sql_jaws);			
				
			//Insert table StockLedgerPG
				$sql_jaws2 = "insert into palace_db.dbo.StockLedgerPG
								select b.ID,'".$ttujuan."','".$idtujuan."','".$nmtujuan."',1,4,'".$tjaws2."','".$confirmby."','".$tgl."'
								from palace_db.dbo.StockProductPG B 
								where b.Nomor = '".$tnoplu."'
								";
				$stmt_jaws2  = sqlsrv_query( $con_dbnew, $sql_jaws2);
			
			//Update Stock Actual PG
				$sql_jaws3 = " update palace_db.dbo.StockActualPG set TipeLokasi = '".$ttujuan."',NamaLokasi = '".$nmtujuan."',IDlokasi = '".$idtujuan."'
							   where Nomor = '".$tnoplu."' ";
				$stmt_jaws3  = sqlsrv_query( $con_dbnew, $sql_jaws3);
		}
		*/
		
	}

	$tmenu = 'M20000';
	$tuser = $_SESSION['loginid'];
	$tketlog = 'CONFIRM';
	$tsqllog = "{call sp_loguser(?,?,?,?)}";
	$paramlog = array(
					array($tmenu, SQLSRV_PARAM_IN),
					array($tuser, SQLSRV_PARAM_IN),
					array($tnomor, SQLSRV_PARAM_IN),
					array($tketlog, SQLSRV_PARAM_IN)
					);
	$stmtlog = sqlsrv_query( $con_dbnew, $tsqllog, $paramlog);

	sqlsrv_close($con_dbnew);

	header("Location: lmconfirm.php?st=".base64_encode($tcabang)."&pr=".base64_encode($_POST['periode'])."&prm=".base64_encode($_POST['param']));

?>