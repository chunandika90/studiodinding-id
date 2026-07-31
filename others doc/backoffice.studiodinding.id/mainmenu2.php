<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
	<?php
  	include "mssql-dbnew.php";
	
	$kdprog = $_GET['cb'];

	$tsql = "select a.* from msmenu a where a.m_program = '01' order by a.m_kode asc" ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	
	
	?>
    <div class="container" >
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
                        <a class="brand" href="#"><img src="images/cmklogo.png" width="30" /></a>
                        <div class="nav-collapse collapse navbar-responsive-collapse">
                            <ul class="nav">
                            	<?php
								$fheader = 'Y';
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
								{
									$kode = $row['m_kode'];
									$nama = $row['m_nama'];
									$link = $row['m_object'];
									if ( substr($kode,-5)=='00000' )
									{
										if ($fheader != 'Y'){echo '</ul></li>';}
										$fheader = 'T' ;
										echo '<li class="dropdown"><a href="'.$link.'" class="dropdown-toggle" data-toggle="dropdown">'.$nama.'<b class="caret"></b></a>';
										echo '<ul class="dropdown-menu">';
									}
									else
									{
										if ($row['m_status']=='2')
										{echo '<li class="nav-header">'.$nama.'</li>';}
										else
										{echo '<li><a href="'.$link.'">'.$nama.'</a></li>';}
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
    </div>
		
	<script src="js/jquery-1.9.1.min.js"></script>
    <!-- Bootstrap javascript -->
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>