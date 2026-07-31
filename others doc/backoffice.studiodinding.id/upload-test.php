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
						$nmsupplier = $sheetData[$i]["A"] ;
								
						$tsqlsup = "select max(right(m_kode,4)) as nomormax from dbmaster.dbo.mssupplier where left(m_kode,1) = '".$substr($nmsupplier,1)."'";
						
						$kdsupplier = substr($nmsupplier,1).substr('0000'.$nojaws,-4) ;
			
						echo $nmsupplier . "<br>";
						echo $tsqlsup . "<br>";
						echo $kdsupplier . "<br>";
						
						
						if ($productid <> '')
						{
							$tsqlA = " 	insert into dbburungfrank.dbo.t_stockinv (m_cabang, m_kodebarang, m_lokasi, m_productid, m_qty, m_harga, m_status) 
										values ('".$cabang."','".$kodebarang."','".$lokasi."','".$productid."', ".$qty.", ".$harga.",'".$status."')";
							//$stmtA = sqlsrv_query($con_dbnew, $tsqlA);
					
							$tsqlB = " 	insert into dbburungfrank.dbo.t_stockdata (m_kodebarang, m_productid, m_hpp, m_configid, m_rubberid, m_item, m_klasifikasi
									, m_kategori, m_segmen, m_grossweight, m_netweight, m_butir, m_carat, m_warna, m_margin, m_selisih, m_distribusi
									, m_harga, m_hargam, m_hargar, m_kelas, m_kadar, m_tukarb, m_tukarj, m_framematerial, m_framefinish
									, m_framecolor, m_konstruksi, m_designkategori, m_designconcept, m_designproses, m_umur, m_gender, m_naming
									, m_grafir, m_frame, m_no, m_ro, m_repeat, m_setting, m_chrome, m_ongkoscogs, m_ongkos2, m_image,m_Belian) 
								values ('".$kodebarang."','".$productid."','".$hpp."','".$configid."','".$rubberid."','".$itemfrank."','".$klasifikasi."',
									'".$kategori."','".$segmen."','".$grossweight."','".$netweight."','".$totbutir."','".$totcarat."','".$warna."','".$margin."',
									'".$selisih."','".$distribusi."','".$harga."','".$hargam."','".$hargar."','".$kelas."','".$kadar."','".$tukarb."',
									'".$tukarj."','".$framematerial."','".$framefinish."','".$framecolor."','".$konstruksi."','".$designkategori."',
									'".$designconcept."','".$designproses."','".$umur."','".$gender."','".$naming."','".$grafir."','".$frame."','".$no."',
									'".$ro."','".$repeat."','".$setting."','".$chrome."','".$ongkoscogs."','".$ongkos2."','".$image."','".$belian."')";
								//$stmtB = sqlsrv_query($con_dbnew, $tsqlB);
							
							if( $stmtA === false )
							{
								echo $sheet . $i . $tsqlA."<br>";
								 echo "Error in executing statement 3.\n";
								 die( print_r( sqlsrv_errors(), true));
							}
							
							if( $stmtB === false )
							{
								echo $sheet . $i . $tsqlB."<br>";
								 echo "Error in executing statement 3.\n";
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