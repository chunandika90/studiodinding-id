<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	$rubberid = $_GET['rubberid'];

	$tsql = "	select 	a.m_nomor, a.m_tanggal, convert(varchar(10),a.m_tanggal,103) as co_tgl
				from 	t_ttb a, t_ttb2 b
				where 	a.m_cabang = b.m_cabang and 
						a.m_nomor = b.m_nomor and 
						b.m_rubberid = '".$rubberid."' 
						
			" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);


	
?>
<input type="text" id="cek_rubberid" name="cek_rubberid" value="<?php echo $row['m_nomor']; ?>" />
<input type="text" id="cek_tanggal" name="cek_tanggal" value="<?php echo $row['co_tgl']; ?>" />


