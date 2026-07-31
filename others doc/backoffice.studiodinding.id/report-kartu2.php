<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
  	include "mssql-dbnew.php";
	
	$tgl = date('Y-m-d 23:59:59');
	$noplu = $_GET['plu'];
	
	if (substr($noplu,0,2) == 'WJ')
	{
		$tsqlstock = "select a.m_cabang, a.m_qty , a.m_productid, c.m_rubberid
					  from t_stockinv a ,t_stockdata c 
					  where (a.m_productid like '%".$noplu."%') and 
					  a.m_productid = c.m_productid
					  
					  " ;
	}
	else
	{
		$tsqlstock = "select a.m_cabang, a.m_qty, a.m_productid, c.m_rubberid
					  from t_stockinv a , t_stockdata c 
					  where (c.m_rubberid like '%".$noplu."%') and 
					  a.m_productid = c.m_productid
					  
					  " ;
	}
	$stmtstock = sqlsrv_query( $con_dbnew, $tsqlstock);
	$rowstock = sqlsrv_fetch_array( $stmtstock, SQLSRV_FETCH_ASSOC) ;
	
	$abc = explode('-',$rowstock['m_rubberid']);
	
	$rubberid = $abc[0].'-001';
	
	$tsql ="select a.*, b.m_nama as designer_nama, c.m_nama as segmen_nama, d.m_nama as item_nama
			from t_stockdata a 
			left join msdesigner b on a.m_designer = b.m_kode 
			left join mssegmen_in c on a.m_segmen = c.m_kode
			left join msmaster d on d.m_type = 'ITEM' and a.m_item = d.m_kode
			
			where a.m_rubberid = '".$rubberid."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	//echo $tsql;

	if ($row['m_productid'] == '')
	{
		echo '<b>'.$noplu.' TIDAK TERDAFTAR !!!'.'</b>';
	}
	else
	{
		

		// ambil kode karet untuk nama file image
		$dumb = explode('-',$row['m_rubberid']);
		$kdbrg = $row['m_kodebarang'];
		
		// Cek Harga
		$harga = $row['m_hargajual'] ;
		
		$folder = 'W:\file_temp';
		$file = $rubberid.".jpg";
		
		?>
        <div class="container pull-left" style="width: auto; padding: 0 20px;">
            <table>
                <tr>
      				<td width="10%">
                        <div class="container span4">
							<img src="getfile.php?folder=<?php echo $folder ; ?>&file=<?php echo $file ; ?>" width="300" height="300">
                        </div>
                    </td>
                    <td valign="top" width="auto">
                        <table class="table table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th colspan="4"><h4>DATA PRODUCT - <?php echo $rubberid ; ?></h4></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="25%" style="font-weight:bold">Product Item</td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['item_nama']; ?></td>
                                    <td width="25%" style="font-weight:bold">Product Segmen</td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['segmen_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td width="25%" style="font-weight:bold">Designer</td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['designer_nama']; ?></td>
                                    <td width="25%" style="font-weight:bold">Tukang </td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['m_tukang']; ?></td>
                                </tr>
                                
                                <tr>
                                    <td width="25%" style="font-weight:bold">Gross Weight</td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['m_grossweight']; ?></td>
                                    <td width="25%" style="font-weight:bold">Nettweight</td>
                                    <td width="25%" style="font-weight:bold"><?php echo $row['m_nettweight']; ?></td>
                                </tr>
                                
                                <tr>
                                    <td width="25%" style="font-weight:bold">Total Butir</td>
                                    <td style="font-weight:bold"><?php echo number_format($row['m_totbutir'], 0, '.', ','); ?></td>
                                    <td width="25%" style="font-weight:bold">Total Carat </td>
                                    <td style="font-weight:bold"><?php echo number_format($row['m_totcarat'], 3, '.', ','); ?></td>
                                </tr>
                                
                                <tr>
                                    <td width="25%" colspan ="2" style="font-weight:bold">Harga +-</td>
                                    <td style="font-weight:bold" colspan="2"><?php echo number_format($row['m_hargabarcode'], 0, '.', ','); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top" width="auto">
                    <table class="table table-bordered table-condensed" >
                            <thead>
                                <tr>
                                    <th colspan="4"><h4>DATA Stone </h4></th>
                                </tr>
                                <tr>
                                    <td width="25%">Parcel</td>
                                    <td width="25%">Total Butir</td>
                                    <td width="25%">Total Carat</td>
                                    <td width="25%">Dimensi</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tsqlh = "select * from t_stockdetail a, t_stockdata b 
										  where a.m_productid = b.m_productid and b.m_rubberid = '".$rubberid."' " ;
                                $stmth = sqlsrv_query( $con_dbnew, $tsqlh);
                                $stock = 0 ;
                                while( $rowh = sqlsrv_fetch_array( $stmth, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $rowh['m_shape'].$rowh['m_size']; ?></td>
                                        <td><div align="center"><?php echo number_format($rowh['m_butir'], 0, '.', ','); ?></div></td>
                                        <td><div align="center"><?php echo number_format($rowh['m_carat'], 3, '.', ','); ?></div></td>
                                        <td><?php echo $rowh['m_dimensi']; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table class="table table-bordered table-striped table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th colspan="7"><h4>HISTORY PRODUCT</h4></th>
                                </tr>
                                <tr>
                                    <th width="100">Keterangan</th>
                                    <th width="50">Cabang</th>
                                    <th width="150"><div align="center">Tanggal</div></th>
                                    <th width="100">Nomor Dokumen</th>
                                    <th width="100">Product ID</th>
                                    <th width="100">Kode Barang</th>
                                    <th width="250">Nama</th>
                                    <th width="50"><div align="center">In</div></th>
                                    <th width="50"><div align="center">Out</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tsqlh = "select *, convert(varchar(10),vf_tanggal,103) as co_tgl, convert(varchar(10),vf_tanggal,108) as co_jam, convert(varchar(10),vf_tanggal,120) as co_tgl2 from f_reportkartu('".$abc[0]."') order by co_tgl2 asc, vf_urutan asc, vf_nomor asc" ;
                                $stmth = sqlsrv_query( $con_dbnew, $tsqlh);
								
								//echo $tsqlh;
                                $stock = 0 ;
                                while( $rowh = sqlsrv_fetch_array( $stmth, SQLSRV_FETCH_ASSOC))
                                {
                                    $stock = $stock + $rowh['vf_in'] - $rowh['vf_out'];
                                    ?>
                                    <tr>
                                        <td><?php echo $rowh['vf_keterangan']; ?></td>
                                        <td><div align="center"><?php echo $rowh['vf_cabang']; ?></div></td>
                                        <td><?php echo $rowh['co_tgl'].' '.$rowh['co_jam']; ?></td>
                                        <td><?php echo $rowh['vf_nomor']; ?></td>
                                        <td><?php echo $rowh['vf_productid']; ?></td>
                                        <td><?php echo $rowh['vf_rubberid']; ?></td>
                                        <td><?php echo $rowh['vf_nama']; ?></td>
                                        <td><div align="center"><?php echo number_format($rowh['vf_in'], 0, '.', ','); ?></div></td>
                                        <td><div align="center"><?php echo number_format($rowh['vf_out'], 0, '.', ','); ?></div></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
            </div>
        </div>
                <?php
	}
?>



