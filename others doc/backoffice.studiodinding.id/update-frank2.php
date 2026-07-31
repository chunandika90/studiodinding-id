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
				
				while ( $i <= $countrow )
				{					
					//$productid = $sheetData[$i]["B"] ;
					
					//echo $productid . "<br>";
					/*
					
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
					*/
					$tsqlA = " 	insert into dbburungfrank.dbo.t_stockinv (m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status) 
								values ('".$cabang."','".$kodebarang."','".$lokasi."','".$productid."', ".$qty.", ".$harga.",'".$status."')";
					//$stmtA = sqlsrv_query($conn, $tsqlA);
					
					
					
					$i++ ;
				}
			
			}

		}
		
	}
	ob_flush();
	flush();
	
	
	
?>