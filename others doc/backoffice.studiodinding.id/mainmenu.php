<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
<?php
  	include "mssql-dbnew.php";
	include "tabel-tgp.php";

	$kdprog = $_SESSION['program'];
	$login = $_SESSION['loginid'];

	$tgl = date('Y-m-d 23:59:59');
	if ($kdprog == ''){$kdprog = '01';}

	$tsql = "	select 	a.*, b.m_add+b.m_edit+b.m_delete+b.m_print as coakses
				from 	msmenu a, msakses b 
				where 	a.m_program = '".$kdprog."' and 
						a.m_program = b.m_program and 
						a.m_kode = b.m_kode and 
						b.m_login = '".$login."' and 
						right(a.m_kode,5) = '00000' and 
						( b.m_akses = 'Y' or a.m_status = '2' )
				order by a.m_kode asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);

	?>
        <div class="row" style="margin-top:10px;">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <!-- Menampilkan tombol trigger -->
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a><!-- Akhir dari tombol triger -->
                        <!-- Komponen navbar -->
                        <a class="brand" href="menu1.php"><img src="images/logopalace.png" width="30" /></a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">
                            
                            	<?php
								$fheader = 'Y';
								$topmenu = '' ;
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
								{
									$class = '';
									$kode = $row['m_kode'];
									$akses = $row['coakses'];
									$nama = $row['m_nama'];
									$link = 'menu2.php?m='.base64_encode($kode).'&p='.base64_encode($kdprog).'&l='.base64_encode($login).'&nm='.base64_encode($nama);
									if ($row['m_submenu'] == '1')
									{ $class = 'class="btn btn-success input-small'; }
									else if ($row['m_submenu'] == '0')
									{ $class = 'class="btn btn-info input-medium'; }
									
									echo '<ul class="nav"><li class="divider-vertical"></li><a href="'.$link.'" '.$class.'" >'.$nama.'</a></ul>';
								}
								?>
                            
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li><a href="logout.php">Sign-Out</a></li>
                            </ul>
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
    	</div>        
    </body>
</html>		
	<script src="js/jquery-1.9.1.min.js"></script>
    <!-- Bootstrap javascript -->
    <script src="js/bootstrap.min.js"></script>
