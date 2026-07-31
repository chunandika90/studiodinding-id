<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
    include "mssql-dbnew.php" ;
	date_default_timezone_set('Asia/Bangkok');
	$kdbrg = $_GET['kdbrg'];
	$productid = $_GET['productid'];
	$tgl = date('Y-m-d 23:59:59');
		
	$tsql = "select a.* from t_stockdata a where a.m_kodebarang = '".$kdbrg."' and a.m_productid = '".$productid."' " ;
	$stmt = sqlsrv_query( $con_dbnew, $tsql);
	$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
	
	$tsqlitem = "select m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row['m_item']."' " ;
	$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
	$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC) ;
	
	$tsqlklasifikasi = "select m_nama from msmaster where m_type = 'KLASIFIKASI' and m_kode = '".$row['m_klasifikasi']."' " ;
	$stmtklasifikasi= sqlsrv_query( $con_dbnew, $tsqlklasifikasi);
	$rowklasifikasi= sqlsrv_fetch_array( $stmtklasifikasi, SQLSRV_FETCH_ASSOC) ;
	
	$tsqlcategory = "select m_nama from msmaster where m_type = 'CATEGORY' and m_kode = '".$row['m_kategori']."' " ;
	$stmtcategory = sqlsrv_query( $con_dbnew, $tsqlcategory);
	$rowcategory = sqlsrv_fetch_array( $stmtcategory, SQLSRV_FETCH_ASSOC) ;
	
	$tsqlsegmen = "select m_nama from msmaster where m_type = 'SEGMEN' and m_kode = '".$row['m_segmen']."' " ;
	$stmtsegmen = sqlsrv_query( $con_dbnew, $tsqlsegmen);
	$rowsegmen = sqlsrv_fetch_array( $stmtsegmen, SQLSRV_FETCH_ASSOC) ;

	$tsqldist = "select m_nama from msmaster where m_type = 'DISTRIBUSI' and m_kode = '".$row['m_distribusi']."' " ;
	$stmtdist = sqlsrv_query( $con_dbnew, $tsqldist);
	$rowdist = sqlsrv_fetch_array( $stmtdist, SQLSRV_FETCH_ASSOC) ;
	
	$tsqlcolor = "select m_nama from msmaster where m_type = 'COLORPG' and m_kode = '".$row['m_warna']."' " ;
	$stmtcolor = sqlsrv_query( $con_dbnew, $tsqlcolor);
	$rowcolor= sqlsrv_fetch_array( $stmtcolor, SQLSRV_FETCH_ASSOC) ;

	$tsqlmaterial = "select m_nama from msmaster where m_type = 'MATERIAL' and m_kode = '".$row['m_framematerial']."' " ;
	$stmtmaterial = sqlsrv_query( $con_dbnew, $tsqlmaterial);
	$rowmaterial= sqlsrv_fetch_array( $stmtmaterial, SQLSRV_FETCH_ASSOC) ;
	
	// ambil kode karet untuk nama file image
	$dumb = explode('-',$row['m_rubberid']);
	$harga = $row['m_harga'] ;
	
	if ($row['m_kodebarang'] == 'P0000004')
	{
		// cek dulu kurs TGP nya 
		$tkursLD = "select * from msrate where m_kode = 'LD' and m_tanggal = ( select max(m_tanggal) from msrate where m_kode = 'LD' and  m_tanggal <= '".$tgl."' )";
		$stmtLD= sqlsrv_query( $con_dbnew, $tkursLD);
		$rowLD = sqlsrv_fetch_array( $stmtLD, SQLSRV_FETCH_ASSOC);

		// Untuk kadar 99.99
		if ($row['m_kadar'] >= 99.00)
		{
			$tgp = $rowLD['m_jual'] * 1.2 ;
		}
		else if ($row['m_item'] == 'H') // CHAIN
		{
			if (strtoupper($row['m_warna']) == 'KNG')
			{
				$tgp = $rowLD['m_jual'] * 0.86 ;
			}
			else
			{
				$tgp = $rowLD['m_jual'] * 0.87 ;
			}
		}
		else // NON CHAIN
		{
			if (strtoupper($row['m_warna']) == 'KNG')
			{
				$tgp = $rowLD['m_jual'] * 0.90 ;
			}
			else
			{
				$tgp = $rowLD['m_jual'] * 0.91 ;
			}
		}
		$tgp = ceil($tgp / 1000) * 1000 ;
		$harga = $row['m_grossweight'] * $tgp ;
		$harga = ceil($harga / 1000) * 1000 ;
	}
	
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3 id="myModalLabel"><?php echo $productid ; ?></h3>
</div>

<div class="modal-body">
		<div class="accordion" id="accordion2">
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseImage">
                        Image Product
                     </a>
                </div>
                <div id="collapseImage" class="accordion-body collapse in">
                    <div class="accordion-inner">
                        <table class="table table-bordered table-hover table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                    	<div class="thumbnail">
                                            <img src="product-image.php?kd=<?php echo $kdbrg; ?>&kr=<?php echo $dumb[0]; ?>" height="250">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                        Data Product
                    </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <table class="table table-hover table-condensed">
                            <tbody>
                                <tr style="font-weight:bold">
                                    <td width="30%">Harga</td>
                                    <td width="70%"><?php echo 'Rp. '.number_format($harga, 0, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <td>Kode Karet</td>
                                    <td><?php echo $row['m_rubberid']; ?></td>
                                </tr>
                                <tr>
                                    <td>Product Klasifikasi</td>
                                    <td><?php echo $rowklasifikasi['m_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td>Product Kategori</td>
                                    <td><?php echo $rowcategory['m_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td>Product Segmen</td>
                                    <td><?php echo $rowsegmen['m_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td>Product Item</td>
                                    <td><?php echo $rowitem['m_nama']; ?></td>
                                </tr>
                                <?php
                                    if ($row['m_kodebarang'] <> 'P0000004')
                                    {
                                        ?>
                                        <tr>
                                            <td>Dist.Stone</td>
                                            <td><?php echo $rowdist['m_nama']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tot.Butir</td>
                                            <td><?php echo number_format($row['m_butir'], 0, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tot.Carat</td>
                                            <td><?php echo number_format($row['m_carat'], 3, '.', ','); ?></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                        Data Emas
                     </a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <table class="table table-hover table-condensed">
                            <tbody>
                                <tr>
                                    <td width="30%">Warna</td>
                                    <td width="70%"><?php echo $rowcolor['m_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td>Material</td>
                                    <td><?php echo $rowmaterial['m_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td>Net Weight</td>
                                    <td><?php echo number_format($row['m_netweight'], 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <td>Tot.Gross Weight</td>
                                    <td><?php echo number_format($row['m_grossweight'], 2, '.', ','); ?></td>
                                </tr>
                                <tr>
                                    <td>Kadar Logam</td>
                                    <td><?php echo number_format($row['m_kadar'], 2, '.', ','); ?></td>
                                </tr>
                                <?php
                                    if ($row['m_kodebarang'] <> 'P0000004')
                                    {
                                        ?>
                                        <tr>
                                            <td>Kadar Tukaran Beli</td>
                                            <td><?php echo number_format($row['m_tukarb'], 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Kadar Tukaran Jual</td>
                                            <td><?php echo number_format($row['m_tukarb'], 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Margin PG</td>
                                            <td><?php echo number_format($row['m_margin'], 2, '.', ','); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Selisih Margin</td>
                                            <td><?php echo number_format($row['m_selisih'], 2, '.', ','); ?></td>
                                        </tr>
                                		<?php
									}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

			<?php
                if ($row['m_kodebarang'] <> 'P0000004')
                {
					?>
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseStone">
                                Data Stone
                             </a>
                        </div>
                        <div id="collapseStone" class="accordion-body collapse">
                            <div class="accordion-inner">
                            <table class="table table-bordered table-hover table-condensed">
                                <thead>
                                    <tr>
                                    	<th>Parcel</th>
                                        <th>Butir</th>
                                        <th>Carat/@</th>
                                        <th>Tot.Carat</th>
                                        <th>Colour</th>
                                        <th>Clarity</th>
                                        <th>Class</th>
                                        <th>Shape</th>
                                        <th>GIA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$tsql2 = "select a.* from t_stockdetail a where a.m_kodebarang = '".$kdbrg."' and a.m_productid = '".$productid."' order by a.m_carat desc " ;
									$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
									while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
									{
										$tsqlcolorst = "select m_nama from msmaster where m_type = 'COLORDJ' and m_kode = '".$row2['m_colour']."' " ;
										$stmtcolorst = sqlsrv_query( $con_dbnew, $tsqlcolorst);
										$rowcolorst = sqlsrv_fetch_array( $stmtcolorst, SQLSRV_FETCH_ASSOC) ;
										$color = $rowcolorst['m_nama'];
										
										$tsqlclarity = "select m_nama from msmaster where m_type = 'CLARITY' and m_kode = '".$row2['m_clarity']."' " ;
										$stmtclarity = sqlsrv_query( $con_dbnew, $tsqlclarity);
										$rowclarity = sqlsrv_fetch_array( $stmtclarity, SQLSRV_FETCH_ASSOC) ;
										$clarity = $rowclarity['m_nama'];

										$tsqlclass = "select m_nama from msmaster where m_type = 'CLASS' and m_kode = '".$row2['m_class']."' " ;
										$stmtclass = sqlsrv_query( $con_dbnew, $tsqlclass);
										$rowclass = sqlsrv_fetch_array( $stmtclass, SQLSRV_FETCH_ASSOC) ;

										$tsqlshape = "select m_nama from msmaster where m_type = 'SHAPE' and m_kode = '".$row2['m_shape']."' " ;
										$stmtshape = sqlsrv_query( $con_dbnew, $tsqlshape);
										$rowshape = sqlsrv_fetch_array( $stmtshape, SQLSRV_FETCH_ASSOC) ;
										
										if ($row2['m_carat'] < 0.3)
										{
											$color = 'F';
											if ($row['m_kelas'] == '02') {$clarity = 'VS';} else {$clarity = 'VVS';}
										}
										?>
                                        
                                        <tr>
                                            <td><?php echo $row2['m_parcel']; ?></td>
                                            <td><?php echo number_format($row2['m_butir'], 0, '.', ','); ?></td>
                                            <td><?php echo number_format($row2['m_carat']/$row2['m_butir'], 3, '.', ','); ?></td>
                                            <td><?php echo number_format($row2['m_carat'], 3, '.', ','); ?></td>
                                            <td><?php echo $color; ?></td>
                                            <td><?php echo $clarity; ?></td>
                                            <td><?php echo $rowclass['m_nama']; ?></td>
                                            <td><?php echo $rowshape['m_nama']; ?></td>
                                            <td><?php echo $row2['m_sertifikat']; ?></td>
                                        </tr>
										<?php
									}
                                	?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <?php
				}
			?>

	</div>
    
</div>
<div class="modal-footer">
  <button class="btn" data-dismiss="modal">Close</button>
</div>



