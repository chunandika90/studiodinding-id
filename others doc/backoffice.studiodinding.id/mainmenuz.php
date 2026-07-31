<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
        
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
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Master <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">General</li>
                                        <li><a href="msmaster.php">Master Kode</a></li>
                                        <li><a href="mscabang.php">Cabang</a></li>
                                        <li><a href="mssales.php">Sales</a></li>
                                        <li><a href="mscustomer.php">Customer</a></li>
                                        <li><a href="msrate.php">Rate Kurs</a></li>
                                        <li><a href="mskomisi.php">Komisi</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Barang</li>
                                        <li><a href="msbarang.php">Kode Barang</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Security</li>
                                        <li><a href="msuser.php">User</a></li>
                                        <li><a href="msmenu.php">Menu Program</a></li>
                                    </ul>
                                </li>	
                                
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Stock <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Stock</li>
                                        <li><a href="#">Inv.Receive</a></li>
                                        <li><a href="invtransfer.php">Inv.Transfer</a></li>
                                        <li><a href="invconfirm.php">Inv.Confirm</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Stock Opname</li>
                                        <li><a href="opname-getstock.php">Get STOCK</a></li>
                                        <li><a href="opname.php">Entry SO</a></li>
                                        <li><a href="#">Report Stock Opname</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Report</li>
                                        <li><a href="report-stock.php">Report STOCK</a></li>
                                        <li><a href="report-in.php">Report Penerimaan</a></li>
                                        <li><a href="report-out.php">Report Pengeluaran</a></li>
                                        <li><a href="report-mutasi.php">Report Mutasi</a></li>
                                        <li><a href="report-kartu.php">Info Product</a></li>
                                        <li><a href="cari-stock.php">Search Stock</a></li>
                                    </ul>
                                </li>

                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Penjualan <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Penjualan</li>
                                        <li><a href="pos.php">P O S</a></li>
                                        <li><a href="tradein.php">Trade-In</a></li>
                                        <li><a href="resell.php">Re-Sell</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Repot</li>
                                        <li><a href="report-omset.php">Report OMSET</a></li>
                                        <li><a href="report-pos.php">Report Penjualan</a></li>
                                        <li><a href="report-tradein.php">Report Tradein</a></li>
                                        <li><a href="report-resell.php">Report Resell</a></li>
                                    </ul>
                                </li>

                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Finance <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Kas/Bank</li>
                                        <li><a href="financekas.php">Entry Kas/Bank</a></li>
                                        <li><a href="financereport1.php">Laporan Harian</a></li>
                                    </ul>
                                </li>


                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Report <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-header">Merchandise</li>
                                        <li><a href="salespos.php">Report Transfer</a></li>
                                        <li class="divider"></li>
                                        <li class="nav-header">Penjualan</li>
                                        <li><a href="#">Report Penjualan</a></li>
                                        <li><a href="#">Report Tradein</a></li>
                                        <li><a href="#">Report Resell</a></li>
                                        <li><a href="#">Report Omset</a></li>
                                    </ul>
                                </li>

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