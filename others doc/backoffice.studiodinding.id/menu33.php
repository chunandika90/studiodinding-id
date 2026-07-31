<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

  	include "mssql-dbnew.php";
	include "tabel-tgp.php";

	$parm = base64_decode($_GET['prm']);
	$dumb = explode('/',$parm);

	$kdprog = $dumb[0];
	$menuid = $dumb[1];
	$nm = $dumb[2];
	$akses = $dumb[3];
	
	$menuid = substr($menuid,0,2).'0000';
	$login = $_SESSION['loginid'];

	$tgl = date('Y-m-d 23:59:59');
	$mid = substr($menuid,0,2);

	$backmenu = $kdprog.'/'.substr($menuid,0,1).'00000/'.$nama.'/'.$akses;

	$tsql = "	select 	a.*, b.m_add+b.m_edit+b.m_delete+b.m_print as coakses
				from 	msmenu a, msakses b 
				where 	a.m_program = '".$kdprog."' and 
						a.m_program = b.m_program and 
						a.m_kode = b.m_kode and 
						b.m_login = '".$login."' and 
						left(a.m_kode,2) = '".$mid."' and 
						a.m_kode <> '".$menuid."' and 
						( b.m_akses = 'Y' )
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
                        <a class="brand" href="<?php echo 'menu2.php?prm='.base64_encode($backmenu); ?>"><img src="images/logopalace.png" width="30" /></a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">                            
                            	<?php
								$fheader = 'Y';
								$topmenu = '' ;
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
								{
									$kode = $row['m_kode'];
									$akses = $row['coakses'];
									$nama = $row['m_nama'];
									$param = $kdprog.'/'.$kode.'/'.$nama.'/'.$akses;
									
									if ($row['m_status'] == '1')
										{ $link = $row['m_object'].'?prm='.base64_encode($param); }
									else if ($row['m_submenu'] == '1')
										{ $link = 'menu4.php?prm='.base64_encode($param); }
									
									if ($row['m_submenu'] == '1')
										{ $class = 'class="btn btn-warning input-medium'; }
									else if ($row['m_submenu'] == '0')
										{ $class = 'class="btn btn-success input-medium'; }
									
									echo '<ul class="nav"><li class="divider-vertical"></li><a href="'.$link.'" '.$class.'" >'.$nama.'</a></ul>';
									
								}
								?>
                            
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li><a href="logout.php"><span class="add-on"><i class="icon-off"></i></span></a></li>
                            </ul>
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
    	</div>        

	<script src="js/jquery-1.9.1.min.js"></script>
    <!-- Bootstrap javascript -->
    <script src="js/bootstrap.min.js"></script>
