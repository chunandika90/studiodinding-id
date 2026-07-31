<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}

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
						( b.m_akses = 'Y' or a.m_status = '2' )
				order by a.m_urutan asc, a.m_kode asc" ;
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
                        <a class="brand" href="main.php"><img src="images/logopalace.png" width="30" /></a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">
                            <ul class="nav">
                            	<?php
								$fheader = 'Y';
								$topmenu = '' ;
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
								{
									$kode = $row['m_kode'];
									$akses = $row['coakses'];
									$nama = $row['m_nama'];
									$link = $row['m_object'];
									if ( substr($kode,-5)=='00000' )
									{
										if ($fheader != 'Y'){echo '</ul></li>';}
										$fheader = 'T' ;
										$topmenu = substr($kode,0,1) ;
										
										echo '<li class="dropdown"><a href="'.$link.'" class="dropdown-toggle" data-toggle="dropdown">'.$nama.'<b class="caret"></b></a>';
										echo '<ul class="dropdown-menu">';
									}
									else
									{
										if (substr($kode,0,1) == $topmenu)
										{
											if ($row['m_status']=='2')
											{
												echo '<li class="nav-header">'.$nama.'</li>';
											}
											else
											{
												echo '<li><a href="'.$link.'?m='.base64_encode($kode).'&a='.base64_encode($akses).'">'.$nama.'</a></li>';
											}
										}
									}
								}
								if ($fheader != 'Y')
								{ echo '</ul></li>' ;}
								?>
                            </ul>
                            <ul class="nav pull-right">
                                <li class="divider-vertical"></li>
                                <li><a href="logout.php">Sign-Out</a></li>
                            </ul>
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->
    	</div>        
		
	<script src="js/jquery-1.9.1.min.js"></script>
    <!-- Bootstrap javascript -->
    <script src="js/bootstrap.min.js"></script>
