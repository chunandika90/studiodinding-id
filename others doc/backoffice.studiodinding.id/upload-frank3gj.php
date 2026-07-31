<?php
	session_start();
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
        include "mssql-dbnew.php" ;
	
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
				$sheets = $objReader->listWorksheetNames($inputFileName);
				
				$tsqlx = " delete from t_stockinv where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				
				$tsqlx = " delete from t_stockdata a, t_stockinv b where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				
				$tsqlx = " delete from t_stockdetail where m_cabang = '".$cabang."' ";
				//$stmtx = sqlsrv_query($conn, $tsqlx);
				
				$sukses = 0;
				$a = 0;
				foreach($sheets as $sheet)
				{
					$sheetData = $objPHPExcel->setActiveSheetIndexByName($sheet)->toArray(null,true,true,true);
					$countrow = count($sheetData);
					$i = 2 ;
					$dummy = '' ;
					$sukses2 = 0;
					while ( $i <= $countrow )
					{
						$productid = $sheetData[$i]["B"] ;
						if ($productid == '')
						{
							$productid = $dummy ;
							
						}
						else
						{
							$dummy = $productid ;
						//	echo $productid.', Parcel : '.$sheetData[$i]["B"]. "<br>";
						}
						
						
						$cabang = $sheetData[$i]["A"];
						$kodebarang = 'P0000004';
						$lokasi = 'F0-0';
						$qty = 1;
						$status = '0';
	
						//$belian = $sheetData[$i]["S"];
						$hpp = 0;
						$configid = $sheetData[$i]["D"];
						$rubberid = $sheetData[$i]["C"];
						$item = $sheetData[$i]["E"];
						//$item = substr($rubberid,1,1);
						$kategori =  $sheetData[$i]["G"];
						$klasifikasi =  $sheetData[$i]["F"];	
						$distribusi =  $sheetData[$i]["H"];		
						$segmen = '';
						$grossweight = $sheetData[$i]["I"];
						$netweight = $sheetData[$i]["J"];
						//$totbutir = $sheetData[$i]["J"]  ;
						//$totcarat = $sheetData[$i]["K"]   ;
						
						
						
						/*
						if ( $item == 'BANGLE') { $item2 = 'E';}
						else if ( $item == 'BRACELET') { $item2 = 'G';}
						else if ( $item == 'BUCKLE') { $item2 = 'I';}
						else if ( $item == 'EARRINGS') { $item2 = 'A';}
						else if ( $item == 'FRANK FIRE') { $item2 = 'P';}
						else if ( $item == 'LADIES RING') { $item2 = 'W';}
						else if ( $item == 'MENS BRACELET') { $item2 = 'N';}
						else if ( $item == 'MENS JEW') { $item2 = 'C';}
						else if ( $item == 'MENS PENDANT') { $item2 = 'F';}
						else if ( $item == 'NECKLACE') { $item2 = 'K';}
						else if ( $item == 'OTHER') { $item2 = 'Z';}
						else if ( $item == 'PENDANT') { $item2 = 'L';}
						else if ( $item == 'WEDDING RING') { $item2 = 'M';}
						*/
						
						
						/*
						if ($kategori == 'SETTING')
						{
							$item = 'Z';
						}
						
						if ($item == '1')
						{
							$item = 'P';
						}
						
						if (substr($configid,1,1) == 'P')
						{
							$item = 'L';
						}
						*/
						
						echo $cabang . "/" . $productid . "/" . $rubberid . "/" . $configid . "/" . $item . "/" . $klasifikasi . "/" . $kategori. "/" . $distribusi  . "<br>";
						
						
						$warna = '';
						$margin = 0;
						$selisih = 0;
						//$distribusi = '';
						$harga = $sheetData[$i]["K"] * 10;
						$hargam = $sheetData[$i]["L"] * 10;
						$hargar = $sheetData[$i]["M"] * 10;
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
						
						if ($totbutir == '')
						{
							$totbutir = 0;
						}
						
						if ($totcarat == '')
						{
							$totcarat = 0;
						}
						if ($grossweight == '')
						{
							$grossweight = 0;
						}
						if ($netweight == '')
						{
							$netweight = 0;
						}
						
						
						if ($productid <> '')
						{
							$tsqlA = " 	insert into dbburungfrank.dbo.t_stockinvgj2 (m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status) 
										values ('".$cabang."','".$kodebarang."','".$lokasi."','".$productid."', ".$qty.", ".$harga.",'".$status."')";
							$stmtA = sqlsrv_query($con_dbnew, $tsqlA);
					
							$tsqlB = " 	insert into dbburungfrank.dbo.t_stockdatagj2 (m_kodebarang, m_productid, m_hpp, m_configid, m_rubberid, m_item, m_klasifikasi
									, m_kategori, m_segmen, m_grossweight, m_netweight, m_butir, m_carat, m_warna, m_margin, m_selisih, m_distribusi
									, m_harga, m_hargam, m_hargar, m_kelas, m_kadar, m_tukarb, m_tukarj, m_framematerial, m_framefinish
									, m_framecolor, m_konstruksi, m_designkategori, m_designconcept, m_designproses, m_umur, m_gender, m_naming
									, m_grafir, m_frame, m_no, m_ro, m_repeat, m_setting, m_chrome, m_ongkoscogs, m_ongkos2, m_image,m_Belian) 
								values ('".$kodebarang."','".$productid."','".$hpp."','".$configid."','".$rubberid."','".$item."','".$klasifikasi."',
									'".$kategori."','".$segmen."','".$grossweight."','".$netweight."','".$totbutir."','".$totcarat."','".$warna."','".$margin."',
									'".$selisih."','".$distribusi."','".$harga."','".$hargam."','".$hargar."','".$kelas."','".$kadar."','".$tukarb."',
									'".$tukarj."','".$framematerial."','".$framefinish."','".$framecolor."','".$konstruksi."','".$designkategori."',
									'".$designconcept."','".$designproses."','".$umur."','".$gender."','".$naming."','".$grafir."','".$frame."','".$no."',
									'".$ro."','".$repeat."','".$setting."','".$chrome."','".$ongkoscogs."','".$ongkos2."','".$image."','".$belian."')";
							$stmtB = sqlsrv_query($con_dbnew, $tsqlB);
							
							if( $stmtA === false )
							{
								echo $sheet ."/". $i ."/". $tsqlA."<br>";
								 echo "Error in executing statement 3.\n";
								 die( print_r( sqlsrv_errors(), true));
							}
							
							if( $stmtB === false )
							{
								echo $sheet ."/". $i ."/". $tsqlB."<br>";
								 echo "Error in executing statement 4.\n";
								 die( print_r( sqlsrv_errors(), true));
							}
								
							$sukses2++;
						}
						$i++ ;
					}
					$sukses = $sukses + $sukses2;
					echo "<br>";
					
        		}
				echo $sukses . "<br>";
			}

		}
	}
	ob_flush();
	flush();
	
	
	
?>