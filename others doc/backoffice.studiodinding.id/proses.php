<?php
// menggunakan class phpExcelReader
include "plugins/excel_reader2.php";
include "mssql-trading.php";

// koneksi ke mysql


// membaca file excel yang diupload
$data = new Spreadsheet_Excel_Reader($_FILES['file1']['tmp_name']);

// membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index=0);

echo $baris;

// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;

// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
for ($i=2; $i<=$baris; $i++)
{
  // membaca data nim (kolom ke-1)
 // $kode = $data->val($i, 1);
  // membaca data nama (kolom ke-2)
  $kode = $data->val($i, 1);
  $nama = $data->val($i, 2);
  $alamat = $data->val($i, 3);
  $telepon = $data->val($i, 4);
  $agama = $data->val($i, 5);
  $email = $data->val($i, 6);
  $tgllahir = $data->val($i, 7);
  
	if ( $kode <> '' && $nama <> '')
	{
		
	$tsqlA = " 	insert into dbmaster.dbo.mscustomer (m_kode, m_nama, m_alamat, m_telepon, m_agama, m_email, m_tgllahir) 
				values ('".$kode."','".$nama."','".$alamat."','".$telepon."', '".$agama."', '".$email."','".$tgllahir."')";
	echo $tsqlA . "<br>";
	//$stmt = sqlsrv_query($conn, $tsqlA);
		if( $stmt === false)
		{
			 echo "Error in query preparation/execution.\n";
			 die( print_r( sqlsrv_errors(), true));
		}
	}
	// jika proses insert data sukses, maka counter $sukses bertambah
	// jika gagal, maka counter $gagal yang bertambah
	if ($stmt) $sukses++;
	else $gagal++;

}

// tampilan status sukses dan gagal
echo "<h3>Proses import data selesai.</h3>";
echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
echo "Jumlah data yang gagal diimport : ".$gagal."</p>";

?>