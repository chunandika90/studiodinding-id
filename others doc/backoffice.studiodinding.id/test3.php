<?php
	session_start();
	$type = base64_decode($_GET['kd']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Navigation Bar Responsive</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>
	<body>
    <?php
        include "mainmenu.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select a.*, b.m_nama as namacabang from msuser a, mscabang b where a.m_status = 'A' and a.m_cabang = b.m_kode " ;
		if ($kdcabang != ''){ $tsql = $tsql." and a.m_cabang = '".$kdcabang."' "; }
		$tsql = $tsql." order by a.m_cabang asc, a.m_dept asc, a.m_divisi asc, a.m_divisi2 asc, a.m_login asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);

		$tsqlcabang = "select distinct a.m_cabang, b.m_nama from msuser a, mscabang b where a.m_cabang = b.m_kode order by a.m_cabang asc" ;
		$stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);

		$tsqlsbu = "select distinct a.m_dept, b.m_nama from msuser a, msdept b where a.m_dept = b.m_kode order by a.m_dept asc" ;
		$stmtsbu = sqlsrv_query( $con_dbnew, $tsqlsbu);
    ?>



	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    </body>
</html>