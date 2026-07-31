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
					//echo $countrow ."<br><br>" ;	
					$i = 2 ;
					$dummy = '' ;
					$sukses2 = 0;
					while ( $i <= $countrow )
					{
						$productid = $sheetData[$i]["A"] ;
						if ($productid == '')
						{
							$productid = $dummy ;
							
						}
						else
						{
							$dummy = $productid ;
						//	echo $productid.', Parcel : '.$sheetData[$i]["B"]. "<br>";
						}
						
						
						$configid = $sheetData[$i]["C"];
						$rubberid = $sheetData[$i]["B"];
						$item = substr($rubberid,1,1);
						
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
						
						
						//echo $productid . "/" . $rubberid . "/" . $item . "/" . $klasifikasi . "/" . $kategori . "/" . $distribusi . "<br>";
						
						
						if ($productid <> '')
						{
							$tsqlA = " 	update dbburungfrank.dbo.t_stockdata2 set m_item = '".$item."' where m_productid = '".$productid."'  
							";
							//echo $tsqlA . "<br>";
							$stmtA = sqlsrv_query($con_dbnew, $tsqlA);

							
							if( $stmtA === false )
							{
								echo $sheet . $i . $tsqlA."<br>";
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