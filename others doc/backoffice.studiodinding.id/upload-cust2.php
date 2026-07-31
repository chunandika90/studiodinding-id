<?php
	session_start();
	set_time_limit(0);
	date_default_timezone_set('Asia/Bangkok');
	include "mssql-dbnew.php";
	
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
					$kode = $sheetData[$i]["A"] ;
					$nama = $sheetData[$i]["B"] ;
					$alamat = $sheetData[$i]["C"] ;
					$telepon = $sheetData[$i]["D"] ;
					$agama = $sheetData[$i]["E"] ;
					$email = $sheetData[$i]["F"] ;
					$tgllahir = $sheetData[$i]["G"] ;
					
					if ($agama == 'ISLAM')
					{
						$agama = '01';
					}
					else if ($agama == 'KRISTEN')
					{
						$agama = '02';
					}
					else if ($agama == 'KATOLIK')
					{
						$agama = '03';
					}
					else if ($agama == 'BUDHA')
					{
						$agama = '04';
					}
					else if ($agama == 'HINDU')
					{
						$agama = '05';
					}
					else 
					{
						$agama = '06';
					}
					
					$abc = explode('/',$tgllahir);
					
					if ( count($abc) < 3)
					{
						$tanggal1 = '';
					}
					else
					{
						$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
					}
					
					$tsqlA = " 	insert into dbmondial.dbo.mscustomer (m_kode, m_nama, m_alamat, m_telepon, m_agama, m_email, m_tgllahir) 
								values 
								('".$kode."','".$nama."','".$alamat."','".$telepon."', '".$agama."', '".$email."','".$tanggal1."')";
//echo $tsqlA  . "<br>";
					$stmtA = sqlsrv_query($con_dbnew, $tsqlA);
					
					
					
					$i++ ;
				}
			
			}

		}
		
	}
	ob_flush();
	flush();
	
	
	
?>