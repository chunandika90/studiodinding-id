<?php
	session_start();
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	include "mssql-trading.php";
	
	if(isset($_POST['submit1']))
	{
		/** Include path **/
		set_include_path(get_include_path() . PATH_SEPARATOR . 'plugins/excel2007/');
		
		/** PHPExcel_IOFactory */
		include 'plugins/excel2007/PHPExcel/IOFactory.php';


		//  $inputFileType = 'Excel5';
		//	$inputFileType = 'Excel2007';
		//	$inputFileType = 'Excel2003XML';
		//	$inputFileType = 'OOCalc';
		//	$inputFileType = 'Gnumeric';
		$cabang = $_POST['cabang'];

		if ($_FILES['file1']["name"] <> '')
		{
			$filestock = $_FILES['file1']['tmp_name'] ;
			$namefile = $_FILES['file1']['name'];
			$dumb = explode('.',$namefile);
			$ext = $dumb[count($dumb)-1] ;
			$jumarr = count($dumb);

			echo $filestock.'<br/>' ;

			$filetujuan1 = 'plugins/'.$namefile;			
			echo $filetujuan1.'<br/>' ;
			if(move_uploaded_file($_FILES['file1']['tmp_name'],$filetujuan1))
			{
				if ($ext == 'xls')
				{
					$inputFileType = 'Excel5';
				}
				elseif ($ext == 'xlsx')
				{
					$inputFileType = 'Excel2007';
				}			
				
				$inputFileName = $filetujuan1;
				echo $inputFileType.'<br/>' ;
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				$objPHPExcel = $objReader->load($inputFileName);
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$countrow = count($sheetData);
				
				echo $countrow;
				
				$tsqlx = " delete from dbburungfrank.dbo.t_stockinv where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				
				$tsqlx = " delete from dbburungfrank.dbo.t_stockdata a, dbburungfrank.dbo.t_stockinv b where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				
				$tsqlx = " delete from dbburungfrank.dbo.t_stockdetail where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				$i = 2 ;
				/*
				while ( $i <= $countrow )
				{					
					$cabang = $sheetData[$i]["A"] ;
					$kodebarang = 'J0000001';
					$lokasi = $sheetData[$i]["A"].'-0';
					$productid = $sheetData[$i]["C"];
					echo $productid;
					$qty = 1;
					$harga = $sheetData[$i]["BA"];
					$status = '0';

					$belian = $sheetData[$i]["F"];
					$hpp = 0;
					$configid = $sheetData[$i]["D"];
					$rubberid = $sheetData[$i]["L"];
					//$item =  $sheetData[$i]["N"];
					//$klasifikasi = 'DJ1';					
					$segmen = '';
					$grossweight = $sheetData[$i]["P"];
					$netweight = $sheetData[$i]["O"];
					$butir = $sheetData[$i]["Q"]+$sheetData[$i]["W"]+$sheetData[$i]["AC"]+$sheetData[$i]["AI"]+$sheetData[$i]["AO"]   ;
					$carat = $sheetData[$i]["R"]+$sheetData[$i]["X"]+$sheetData[$i]["AD"]+$sheetData[$i]["AJ"]+$sheetData[$i]["AP"]   ;
					$warna = '';
					$margin = 0;
					$selisih = 0;
					//$distribusi = '';
					$harga = $sheetData[$i]["BA"] * 10;
					$hargam = $sheetData[$i]["BB"];
					$hargar = $sheetData[$i]["BC"];
					$kelas = '01';
					$kadar = 75.5;
					$tukarb = 84.5;
					$tukarj = 0;
					$framematerial = 'EMS';
					$framefinish = 'DOFF';
					$framecolor = 'P';
					$konstruksi = 'D';
					$designkategori = 'BB';
					$designconcept = 'N';
					$designproses = 'M';
					$umur = 'Adult';
					$gender = 'Unisex';
					$naming = '';
					$grafir = '';
					$frame = 0;
					$no = 0;
					$ro = 0;
					$repeat = 0;
					$setting = 0;
					$chrome = 0;
					$ongkoscogs = 0;
					$ongkos2 = 0;
					$image = '';
					$dumbx = $sheetData[$i]["M"];
					
					// Cek dulu informasi yang bisa didapat !!!
					// Kode Barang
					if ( strpos(' '.$belian,'GOLD') ) { $kodebarang = 'P0000003' ;}

					// Cek Item
					$itemfrank = substr($configid,2,1);
					if ($itemfrank == 'A'){$item = 'Q';}
					else if ($itemfrank == 'B'){$item = 'G';}
					else if ($itemfrank == 'C'){$item = 'H';}
					else if ($itemfrank == 'E'){$item = 'A';}
					else if ($itemfrank == 'G'){$item = 'E';}
					else if ($itemfrank == 'H'){$item = 'N';}
					else if ($itemfrank == 'I'){$item = 'F';}
					else if ($itemfrank == 'J'){$item = 'J';}
					else if ($itemfrank == 'L'){$item = 'W';}
					else if ($itemfrank == 'M'){$item = 'C';}
					else if ($itemfrank == 'N'){$item = 'K';}
					else if ($itemfrank == 'P'){$item = 'L';}
					else if ($itemfrank == 'R'){$item = 'B';}
					else if ($itemfrank == 'S'){$item = 'S';}
					else if ($itemfrank == 'U'){$item = 'I';}
					else if ($itemfrank == 'W'){$item = 'M';}
					else if ($itemfrank == 'Z'){$item = 'Z';}
					
					// Cek Distribusi
					if ( strpos(' '.$dumbx,'FANCY') ) { $distribusi = 'FAN' ;}
					if ( strpos(' '.$dumbx,'SIMPLE') ) { $distribusi = 'SIM' ;}
					if ( strpos(' '.$dumbx,'SOLITER') ) { $distribusi = 'SOL' ;}
					if ( strpos(' '.$dumbx,'TENNIS') ) { $distribusi = 'TEN' ;}
					if ( strpos(' '.$dumbx,'TRILOGI') ) { $distribusi = 'TRI' ;}					
					
					// Cek Kategori  
					if ( strpos(' '.$dumbx,'FASHION') ) { $kategori = 'FAS' ;}
					if ( strpos(' '.$dumbx,'FUN') ) { $kategori = 'FUN' ;}
					if ( strpos(' '.$dumbx,'PG MOUNTING') ) { $kategori = 'PG' ;}
					if ( strpos(' '.$dumbx,'SETTING/LEPASAN') ) { $kategori = 'PG' ;}
					if ( strpos(' '.$dumbx,'SI') ) { $kategori = 'SI' ;}
					if ( strpos(' '.$dumbx,'SNI') ) { $kategori = 'SNI' ;}
					if ( strpos(' '.$dumbx,'WEDDING RING') ) { $kategori = 'WDR' ;}
					if ( strpos(' '.$dumbx,'WEDDING RING PG') ) { $kategori = 'WDR' ;}
					
					// Cek Klasifikasi
					if ( strpos(' '.$dumbx,'MOUNTING') ) { $klasifikasi = 'MTG' ;}
					if ( strpos(' '.$dumbx,'SEMI') ) { $klasifikasi = 'SMG' ;}
					if ($kodebarang == 'P0000003') { $klasifikasi = 'PG1' ;}

					// Cek Segmen
					if ($kategori == 'SI')
					{
						if (($harga > 60000000 ) && ($harga <= 200000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 200000000 )
						{ $segmen = 'BG';}
					}
					else if ($kategori == 'SNI')
					{
						if ($harga <= 20000000 ) 
						{ $segmen = 'BN';}
						else if (($harga > 20000000 ) && ($harga <= 60000000 )) 
						{ $segmen = 'LV';}
						else if (($harga > 60000000 ) && ($harga <= 200000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 200000000 ) 
						{ $segmen = 'BG';}
					}
					else if ($kategori == 'WDR')
					{
						if ($harga <= 10000000 ) 
						{ $segmen = 'BN';}
						else if (($harga > 10000000 ) && ($harga <= 30000000 )) 
						{ $segmen = 'LV';}
						else if (($harga > 30000000 ) && ($harga <= 100000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 100000000 ) 
						{ $segmen = 'BG';}
					}
					else if ($kategori == 'FAS')
					{
						if ($harga <= 10000000 ) 
						{ $segmen = 'BN';}
						else if (($harga > 10000000 ) && ($harga <= 30000000 )) 
						{ $segmen = 'LV';}
						else if (($harga > 30000000 ) && ($harga <= 100000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 100000000 ) 
						{ $segmen = 'BG';}
					}
					else if ($kategori == 'FUN')
					{
						if ($harga <= 3000000 ) 
						{ $segmen = 'BN';}
						else if (($harga > 3000000 ) && ($harga <= 10000000 )) 
						{ $segmen = 'LV';}
						else if (($harga > 10000000 ) && ($harga <= 30000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 30000000 ) 
						{ $segmen = 'BG';}
					}
					else if ($kategori == 'PG')
					{
						if ($harga <= 3000000 ) 
						{ $segmen = 'BN';}
						else if (($harga > 3000000 ) && ($harga <= 10000000 )) 
						{ $segmen = 'LV';}
						else if (($harga > 10000000 ) && ($harga <= 30000000 )) 
						{ $segmen = 'FQ';}
						else if ($harga > 30000000 ) 
						{ $segmen = 'BG';}
					}

					$tsqlA = " 	insert into dbburungfrank.dbo.t_stockinv (m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status) 
								values ('".$cabang."','".$kodebarang."','".$lokasi."','".$productid."', ".$qty.", ".$harga.",'".$status."')";
					//$stmtA = sqlsrv_query($conn, $tsqlA);
					
					$tsqlB = " 	insert into dbburungfrank.dbo.t_stockdata (m_kodebarang, m_productid, m_hpp, m_configid, m_rubberid, m_item, m_klasifikasi
									, m_kategori, m_segmen, m_grossweight, m_netweight, m_butir, m_carat, m_warna, m_margin, m_selisih, m_distribusi
									, m_harga, m_hargam, m_hargar, m_kelas, m_kadar, m_tukarb, m_tukarj, m_framematerial, m_framefinish
									, m_framecolor, m_konstruksi, m_designkategori, m_designconcept, m_designproses, m_umur, m_gender, m_naming
									, m_grafir, m_frame, m_no, m_ro, m_repeat, m_setting, m_chrome, m_ongkoscogs, m_ongkos2, m_image) 
								values ('".$kodebarang."','".$productid."','".$hpp."','".$configid."','".$rubberid."','".$item."','".$klasifikasi."',
									'".$kategori."','".$segmen."','".$grossweight."','".$netweight."','".$butir."','".$carat."','".$warna."','".$margin."',
									'".$selisih."','".$distribusi."','".$harga."','".$hargam."','".$hargar."','".$kelas."','".$kadar."','".$tukarb."',
									'".$tukarj."','".$framematerial."','".$framefinish."','".$framecolor."','".$konstruksi."','".$designkategori."',
									'".$designconcept."','".$designproses."','".$umur."','".$gender."','".$naming."','".$grafir."','".$frame."','".$no."',
									'".$ro."','".$repeat."','".$setting."','".$chrome."','".$ongkoscogs."','".$ongkos2."','".$image."')";
					//$stmtB = sqlsrv_query($conn, $tsqlB);
					
					$i++ ;
				}
			*/
			}

		}
		
	}
	ob_flush();
	flush();
	
	
	
?>