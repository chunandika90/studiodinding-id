<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcab = base64_decode($_GET['cb']);
	
	$nomor = base64_decode($_GET['nm']);
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>INV Receive</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		include "menu-pos2.php";
		
		if ($nomor == '')
		{
			$tgl = date("d/m/Y") ;
			$jam = date("H:i:s") ;
			$nama = '' ;
			$kdsupl = '';
			$ket = '' ;
			$doc = '' ;
			$status = 'A';
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam , b.m_segmen
			from t_ttb a, t_ttb2 b where a.m_nomor = b.m_nomor and a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."'" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$kdsupl = $row['m_supplier'] ;
			$lokasi = $row['m_cabang'];
			
			$ket = $row['m_keterangan'] ;
			$doc = $row['m_dokumen'] ;
			$status = $row['m_status'] ;	
			
			
			$tsqlsup = "select * from mssupplier where m_kode = '".$kdsupl."'" ;
			//echo $tsqlsup;
			$stmtsup = sqlsrv_query( $con_dbnew, $tsqlsup);
			$rowsup = sqlsrv_fetch_array( $stmtsup, SQLSRV_FETCH_ASSOC) ;
			
			$tsqllok = "select * from mscabang where m_kode = '".$lokasi."'" ;
			//echo $tsqlsup;
			$stmtlok = sqlsrv_query( $con_dbnew, $tsqllok);
			$rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC) ;
			
		}
		
		$tsqlrate = " select top 1 * from msrate where m_type = 'USD' and m_tanggal <= getdate() 
					  order by m_tanggal desc ";
		$stmtrate = sqlsrv_query( $con_dbnew, $tsqlrate);
		$rowrate = sqlsrv_fetch_array( $stmtrate, SQLSRV_FETCH_ASSOC) ;
		
		$rate = $rowrate['m_beli'];
					
    ?>
	<form class="form-horizontal" method="post" action="ttb2_new-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 80%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Penerimaan Barang ('.$nomor.' )' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="150">
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                            
                        	<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="m_type" name="m_type" value="I" />
                            <input type="hidden" id="m_kodebarang" name="m_kodebarang" value="DJ" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow2" name="jumrow2" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="50">Tanggal</td>
                        <td width="350"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $kdsupl; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $rowsup['m_nama']; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupplier()"></i></span>
                            </div>
                        </td>
                    </tr>
                     <tr>
                        <td>Lokasi</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_namalokasi" name="m_namalokasi" value="<?php  echo $rowlok['m_nama'];  ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlokasi()"></i></span>
                            </div>
                        </td>
                    </tr>
                   
                   
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    </tr>
                    <tr>
                        <td>Designer</td>
                            <td>
                                <select name="m_designer" id="m_designer" class="input-large" >
                                    <?php
                                    $tsqld = "select m_kode, m_nama from msdesigner order by m_kode asc" ;
                                    $stmtd = sqlsrv_query( $con_dbnew, $tsqld);
                                    while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        ?> <option value="<?php echo $rowd['m_kode']; ?>" <?php if ($rowd['m_kode'] == $row['m_designer']) { ?> selected="selected" <?php } ?> ><?php echo $rowd['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                
                        </td>
                   </tr>
                   <tr>
                        <td>Konstruksi</td>
                            <td>
                                <select name="m_konstruksi" id="m_konstruksi" class="input-large" >
                                    <?php
                                    $tsqld = "select distinct m_konstruksi from msopoles order by m_konstruksi asc" ;
                                    $stmtd = sqlsrv_query( $con_dbnew, $tsqld);
                                    while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowd['m_konstruksi']; ?>" <?php if ($rowd['m_konstruksi'] == $row['m_konstruksi']) { ?> selected="selected" <?php } ?> ><?php echo $rowd['m_konstruksi']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                        </td>
                   </tr>
                   <tr>
                        <td>Segmen</td>
                            <td>
                                <select name="m_segmen" id="m_segmen" class="input-large" >
                                    <?php
                                    $tsqld = "select m_kode, m_nama from mssegmen_in order by m_kode asc" ;
                                    $stmtd = sqlsrv_query( $con_dbnew, $tsqld);
                                    while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowd['m_kode']; ?>" <?php if ($rowd['m_kode'] == $row['m_segmen']) { ?> selected="selected" <?php } ?> ><?php echo $rowd['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                
                        </td>
                   </tr>
                   
                   <tr>
                        <td>Jenis Barang</td>
                            <td>
                                <select name="m_jenisbarang" id="m_jenisbarang" class="input-large" >
                                    <?php
                                    $tsqld = "select m_kode, m_nama from msmaster where m_type = 'JENISBARANG' order by m_kode asc" ;
                                    $stmtd = sqlsrv_query( $con_dbnew, $tsqld);
                                    while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowd['m_kode']; ?>" <?php if ($rowd['m_kode'] == $row['m_jenisbarang']) { ?> selected="selected" <?php } ?> ><?php echo $rowd['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                
                        </td>
                   </tr>
                   
                   <tr>
                        <td>Tukang</td>
                            <td>
                                <select name="m_tukang" id="m_tukang" class="input-large" >
                                    <?php
                                    $tsqld = "select m_kode, m_nama from mstukang order by m_nama asc" ;
                                    $stmtd = sqlsrv_query( $con_dbnew, $tsqld);
                                    while( $rowd = sqlsrv_fetch_array( $stmtd, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowd['m_kode']; ?>" <?php if ($rowd['m_kode'] == $row['m_tukang']) { ?> selected="selected" <?php } ?> ><?php echo $rowd['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                        </td>
                   </tr>
                    <tr>
                        <td>Total Carat Barang</td>
                        <td colspan="3"><input class="input-small" type="text" id="m_totalcarat" name="m_totalcarat" value="0" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

    	
    	<div class="container pull-left row-fluid" style="width: 80%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th height="37">Nama Barang</th>
                      	<th>Item</th>
                        <th><div align="center">Qty</div></th>
                        <th>Berat</th>
                        <th>Ongkos Lain-Lain (M)</th>
                        <th>Ongkos Lain-Lain (R)</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$ttot = 0 ;
						$totberat = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_item, c.m_nama as co_namaitem, c.m_kode2
										from 	t_ttb2 a, t_stockdata b, msmaster c 
										where 	a.m_cabang = '".$lokasi."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												c.m_type = 'ITEM' and 
												b.m_item = c.m_kode " ;
							//echo $tsql2;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							$row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ;
							
							$dumb = explode('-',$row2['m_kode2']) ;
							$tqty = $tqty + $row2['m_qty'] ;
							$tgross = $tgross + $row2['m_grossweight'] ;
							$thargar = $thargar + $row2['m_hargar'] ;
							$totalongkos = $row2['m_orangka'] + $row2['m_opb'] + $row2['m_opoles'] + $row2['m_ofinishing'] ;
							
							$total = ( $row2['m_qty'] * $row2['m_harga'] ) ;
							?>
							<tr>
								<td><input class="input-medium" type="text" id="m_rubberid" name="m_rubberid" value="<?php echo $row2['m_rubberid']; ?>" style="text-align:left" readonly /><input  type="hidden" id="m_productid" name="m_productid" value="<?php echo $row2['m_productid']; ?>" style="text-align:left" readonly /></td>
								<td>
									<input class="input-medium" type="text" id="m_nmitem" name="m_nmitem" value="<?php echo $row2['co_namaitem']; ?>" readonly />
									<input type="hidden" id="m_item" name="m_item" value="<?php echo $row2['m_item']; ?>" />
								</td>
								<td><div align="center"><input class="input-mini" type="text" id="m_qty" name="m_qty" value="<?php echo $row2['m_qty']; ?>" style="text-align:center" readonly /></div></td>
								<td><input class="input-mini" type="text" id="m_grossweight" name="m_grossweight" value="<?php echo  number_format ($row2['m_grossweight'], 2, '.', ','); ?>" style="text-align:center" /></td>
								<td><input class="input-small" type="text" id="m_olainm" name="m_olainm" value="<?php echo number_format ($row2['m_olainm'], 0, '.', ','); ?>" style="text-align:right"   onChange="recalc()"/></td>
								<td><input class="input-small" type="text" id="m_olainr" name="m_olainr" value="<?php echo number_format ($row2['m_olainr'], 0, '.', ','); ?>" style="text-align:right"   onChange="recalc()"/></td>
								<td>
									<input type="hidden" id="m_new" name="m_new" value="T" />
									<div align="center"><input type="checkbox" id="m_hapus" name="m_hapus" /></div>
								</td>
							</tr>
							<?php
							
						}
						else
						{
							?>
							<tr>
								<td><input class="input-medium" type="text" id="m_rubberid" name="m_rubberid" value="" style="text-align:left"  onChange="oc_cekplu('')"/> <input type="hidden" id="m_productid" name="m_productid" value="" /></td>
								<td>
									<input class="input-medium" type="text" id="m_nmitem" name="m_nmitem" value="" readonly onClick="listitem()" style="cursor:pointer"/>
									<input type="hidden" id="m_item" name="m_item" value="" />
									<span class="add-on">
								</td>
								<td><div align="center"><input class="input-mini" type="text" id="m_qty" name="m_qty" value="1" style="text-align:center" onChange="recalc()" readonly/></div></td>
								<td><input class="input-mini" type="text" id="m_grossweight" name="m_grossweight" value="0" style="text-align:center"   onChange="recalc()"/></td>
								<td><input class="input-small" type="text" id="m_olainm" name="m_olainm" value="0" style="text-align:right" onChange="recalc()"/></td>
								<td><input class="input-small" type="text" id="m_olainr" name="m_olainr" value="0" style="text-align:right" onChange="recalc()"/></td>
								<td>
									<input type="hidden" id="m_new" name="m_new" value="Y" />
									<div align="center"><input type="checkbox" id="m_hapus" name="m_hapus" /></div>
								</td>
							</tr>
							<?php
						
						}
                    ?>
                </tbody>
            </table>        
		</div>
        
        <div class="container pull-left row-fluid" style="width: 30%; padding: 0 10px;">
            <table id="table_batu" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Stone Shape</th>
                        <th>Stone Size</th>
                        <th>Butir</th>
                        <th>Carat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$b = 0 ;
						$tbutir = 0;
						$tcarat = 0;
						if ($nomor != '')
						{
							$tsql3 = "	select 	a.*, b.m_item, c.m_nama as co_namaitem, c.m_kode2, d.m_ukuran, d.m_hargam hargam, 
										d.m_hargar hargar , d.m_opbm as pbm, d.m_opbr as pbr
										from 	t_ttb3 a, t_stockdata b, msmaster c , msstone d
										where 	a.m_nomor = '".$nomor."' and 
												a.m_productid = b.m_productid and
												c.m_type = 'ITEM' and a.m_size = d.m_size and
												b.m_item = c.m_kode and a.m_shape = d.m_shape " ;
												
							
							//echo $tsql3;
							$stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
							while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
							{	
							
								$tbutir = $tbutir + $row3['m_butir'];
								$tcarat = $tcarat + $row3['m_carat'];
							
								$b = $b + 1 ;
								?>
								<tr>
									<td><input class="input-small" type="text" id="m_shape<?php echo $b; ?>" name="m_shape<?php echo $b; ?>" value="<?php echo $row3['m_shape']; ?>" style="text-align:center;cursor:pointer" readonly onClick="listshape(<?php echo $b; ?>)" /></td>
									<td><input class="input-small" type="hidden" id="m_size<?php echo $b; ?>" name="m_size<?php echo $b; ?>" value="<?php echo $row3['m_size']; ?>" /><input class="input-small" type="text" id="m_ukuran<?php echo $b; ?>" name="m_ukuran<?php echo $b; ?>" value="<?php echo $row3['m_ukuran']; ?>"style="text-align:center;cursor:pointer" readonly onClick="listshape(<?php echo $b; ?>)"	/></td>
									<td><input class="input-small" type="text" id="m_butir<?php echo $b; ?>" name="m_butir<?php echo $b; ?>" value="<?php echo $row3['m_butir']; ?>" style="text-align:right" onChange="recalc()"/></td>
									<td><input class="input-small" type="text" id="m_carat<?php echo $b; ?>" name="m_carat<?php echo $b; ?>" value="<?php echo $row3['m_carat']; ?>" style="text-align:right" onchange="oc_cekstone(<?php echo $b; ?>)"/>
                                    
                                    <input class="input-small" type="hidden" id="m_hargam<?php echo $b; ?>" name="m_hargam<?php echo $b; ?>" value="<?php echo $row3['hargam']; ?>" style="text-align:right"  />
                                    <input class="input-small" type="hidden" id="m_hargar<?php echo $b; ?>" name="m_hargar<?php echo $b; ?>" value="<?php echo $row3['hargar']; ?>" style="text-align:right"  />
                                    <input class="input-small" type="hidden" id="m_opbm<?php echo $b; ?>" name="m_opbm<?php echo $b; ?>" value="<?php echo $row3['pbm']; ?>" style="text-align:right"  />
                                    <input class="input-small" type="hidden" id="m_opbr<?php echo $b; ?>" name="m_opbr<?php echo $b; ?>" value="<?php echo $row3['pbr']; ?>" style="text-align:right"  />
                                    </td>
									<td>
										<input type="HIDDEN" id="m_new<?php echo $b; ?>" name="m_new<?php echo $b; ?>" value="T" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $b; ?>" name="m_hapus<?php echo $b; ?>" /></div>
									</td>
								</tr>
								<?php
							}
						}
						else
						{
							$addrow2 = 1 ;
							while( $addrow2 <= 3 )
							{
								$addrow2 = $addrow2 + 1 ;
								$b = $b + 1 ;
								?>
								<tr>
									<td><input class="input-small" type="text" id="m_shape<?php echo $b; ?>" name="m_shape<?php echo $b; ?>" value="" style="text-align:right;cursor:pointer"  readonly onClick="listshape(<?php echo $b; ?>)"/></td>
									<td><input class="input-small" type="hidden" id="m_size<?php echo $b; ?>" name="m_size<?php echo $b; ?>" value="" />
                                    	<input class="input-small" type="text" id="m_ukuran<?php echo $b; ?>" name="m_ukuran<?php echo $b; ?>" value="" style="text-align:right"  readonly /></td>
									<td><input class="input-small" type="text" id="m_butir<?php echo $b; ?>" name="m_butir<?php echo $b; ?>" value="0" style="text-align:right" onChange="recalc()"/></td>
									<td><input class="input-small" type="text" id="m_carat<?php echo $b; ?>" name="m_carat<?php echo $b; ?>" value="0" style="text-align:right" onchange="oc_cekstone(<?php echo $b; ?>)"/>
                                    	<input class="input-small" type="hidden" id="m_hargam<?php echo $b; ?>" name="m_hargam<?php echo $b; ?>" value="" />
                                    	<input class="input-small" type="hidden" id="m_hargar<?php echo $b; ?>" name="m_hargar<?php echo $b; ?>" value="" />
                                    	<input class="input-small" type="hidden" id="m_opbm<?php echo $b; ?>" name="m_opbm<?php echo $b; ?>" value="" />
                                    	<input class="input-small" type="hidden" id="m_opbr<?php echo $b; ?>" name="m_opbr<?php echo $b; ?>" value="" />
                                    	</td>
									<td>
										<input type="hidden" id="a_new<?php echo $b; ?>" name="a_new<?php echo $b; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $b; ?>" name="m_hapus<?php echo $b; ?>" /></div>
									</td>
								</tr>
								<?php
							}
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2"></th>
                        <th><div id="sp-totbutir" align="right"><input class="input-small" type="text" value="<?php echo number_format($tbutir, 0, '.', ','); ?>" style="text-align:right" /></div></th>
                        <th><div id="sp-totcarat" align="right"><input class="input-small" type="text" value="<?php echo number_format($tcarat, 3, '.', ','); ?>" style="text-align:right" /></div></th>
                        <th></th>
						<input class="input-small" type="hidden" id="tbutir" name="tbutir" value="<?php echo number_format($tbutir, 0, '.', ','); ?>" style="text-align:right"  />
						<input class="input-small" type="hidden" id="tcarat" name="tcarat" value="<?php echo number_format($tcarat, 3, '.', ','); ?>" style="text-align:right" />
                    </tr>
                    <tr>
                        <th colspan="10">
                        <div>
                            <div class="pull-left" >
                                <input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data()" />
                            </div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                                <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $periode; ?>')" />
                            </div>
                        </div>
                        </th>
                    </tr>
                </tfoot>
            </table>        
		</div>
    </form>

    <div id="tempdata" class="hide">
        <span id="datastone">
            <input type="text" id="cek_status" name="cek_status" value="" />
            <input type="text" id="cek_tsql" name="cek_tsql" value="" />
        </span>
    </div>         

    <div id="tempdata" class="hide">
        <span id="datarubber">
            <input type="text" id="cek_rubberid" name="cek_rubberid" value="" />
            <input type="text" id="cek_tanggal" name="cek_tanggal" value="" />
        </span>
    </div>         

    <div id="dialog-listsupplier">
        <span id="datasupplier">
        </span>
    </div>
    <div id="dialog-listlokasi">
        <span id="datalokasi">
        </span>
    </div>

    <div id="dialog-listitem">
        <span id="dataitem">
        </span>
    </div>
    

    <div id="dialog-listshape">
        <span id="datashape">
        </span>
    </div>
    
    

    <div id="dialog-listsize">
        <span id="datasize">
        </span>
    </div>
    
    
    <div id="dialog-listcolour">
        <span id="datacolour">
        </span>
    </div>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});
			
		$(function() {
			$( "#dialog-listsupplier" ).dialog({
				autoOpen: false,
				height:500,
				width:1100,
				modal: true,
				buttons: {
					"Close": function() {
							$( this ).dialog( "close" );
							}
						}
				});
			});
			
		$(function() {
		$( "#dialog-listlokasi" ).dialog({
			autoOpen: false,
			height:500,
			width:1100,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		$(function() {
		$( "#dialog-listitem" ).dialog({
			autoOpen: false,
			height:600,
			width:400,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		$(function() {
		$( "#dialog-listshape" ).dialog({
			autoOpen: false,
			height:600,
			width:400,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		$(function() {
		$( "#dialog-listsize" ).dialog({
			autoOpen: false,
			height:600,
			width:400,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		$(function() {
		$( "#dialog-listcolour" ).dialog({
			autoOpen: false,
			height:600,
			width:400,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
			
		$(function() {
			$( "#dialog-listdoc" ).dialog({
				autoOpen: false,
				height:500,
				width:500,
				modal: true,
				buttons: {
					"Close": function() {
							$( this ).dialog( "close" );
							}
						}
				});
			});
			
		});
		
		function oc_cekstone(rowke)
		{
			var data={shape:$('#m_shape'+rowke).val(),size:$('#m_size'+rowke).val(),butir:$('#m_butir'+rowke).val(),carat:$('#m_carat'+rowke).val()};
			
			var fungsi=function(respon)
			{
					$("#datastone").html(respon);
					
					
					
					var status = $('#cek_status').val();
					var tsql = $('#cek_tsql').val();
					
					
					if (status == 0)
					{
						
						alert('CARAT Per butir tidak sesuai parcel');
						//alert(tsql);
						document.getElementById('m_butir'+rowke).focus();
						return false;
						
						
						
					}
					else
					{
						recalc();
					}
					
					
			};
			$.get('ttb2_new-cekstone.php',data,fungsi);
		}
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("ttb.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function listsupplier()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datasupplier").html(respon);
				};
			$.get('ttb-ceksupplier.php',data,fungsi);
			
			$( "#dialog-listsupplier" ).dialog( "open" );
		}
		
		function listlokasi()
		{
			var data={tx:$('#m_namalokasi').val()};

			var fungsi=function(respon){
					$("#datalokasi").html(respon);
				};
			$.get('ttb-ceklokasi.php',data,fungsi);
			
			$( "#dialog-listlokasi" ).dialog( "open" );
		}

		function selectsupplier(vkode,vnama)
		{
			document.getElementById('m_kode').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listsupplier" ).dialog( "close" );
		}
		
		function selectlokasi(vkode,vnama)
		{
			document.getElementById('m_lokasi').value = vkode ;
			document.getElementById('m_namalokasi').value = vnama ;

			$( "#dialog-listlokasi" ).dialog( "close" );
		}

		function cekdoc()
		{
			var data={tx:$('#m_dokumen').val()};

			var fungsi=function(respon){
					$("#dataplu").html(respon);
					document.getElementById('m_dokumen').value = $('#cek_dok').val() ;
				};
			$.get('ttb-cekdoc.php',data,fungsi);
			
//			$( "#dialog-listdoc" ).dialog( "open" );
		}

		function selectplu(vkode)
		{
			$( "#dialog-listdoc" ).dialog( "close" );
		}

		
		function listitem()
		{
			var rowke = 1;
			var data={rk:rowke};

			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('ttb2-cekitem.php',data,fungsi);
			
			$( "#dialog-listitem" ).dialog( "open" );
		}
		
		
		function listshape(rowke)
		{
			var data={rk:rowke};
		
			var fungsi=function(respon){
					$("#datashape").html(respon);
				};
			$.get('ttb-cekshape.php',data,fungsi);
			
			$( "#dialog-listshape" ).dialog( "open" );
		}
		
		
		function listcolour(rowke)
		{
			var data={rk:rowke};

			var fungsi=function(respon){
					$("#datacolour").html(respon);
				};
			$.get('ttb2-cekcolour.php',data,fungsi);
			
			$( "#dialog-listcolour" ).dialog( "open" );
		}

		function selectitem(kodeitem,namaitem)
		{
			document.getElementById('m_item').value = kodeitem ;
			document.getElementById('m_nmitem').value = namaitem ;
			$( "#dialog-listitem" ).dialog( "close" );
		}
		
		function selectshape(rowke,shape,size,ukuran,hargam,hargar,opbm,opbr)
		{
			document.getElementById('m_shape'+rowke).value = shape ;
			document.getElementById('m_size'+rowke).value = size ;
			document.getElementById('m_ukuran'+rowke).value = ukuran ;
			document.getElementById('m_hargam'+rowke).value = hargam ;
			document.getElementById('m_hargar'+rowke).value = hargar ;
			document.getElementById('m_opbm'+rowke).value = opbm ;
			document.getElementById('m_opbr'+rowke).value = opbr ;
			
			$( "#dialog-listshape" ).dialog( "close" );
		}
		
		function oc_cekplu()
		{
			var data={rubberid:$('#m_rubberid').val()};
			
			
			var fungsi=function(respon){
					$("#datarubber").html(respon);
					// cek dulu double ngk !!!
					var cekdouble = 'T';
					var newplu = $('#cek_rubberid').val();
					var tanggal = $('#cek_tanggal').val();
					
					if (newplu != '')
					{
						alert ("Kode Barang inisudah pernah di input pada nomor "+newplu +" pada tanggal "+tanggal);
					}
				};
			$.get('ttb2_new-cekplu.php',data,fungsi);
		}

		
		
		function selectcolour(rowke,kodecolour)
		{
			document.getElementById('m_colour'+rowke).value = kodecolour ;
			$( "#dialog-listcolour" ).dialog( "close" );
		}

		function validasi()
		{
			var tbl2 = document.getElementById('table_batu');
			var lastRow2 = tbl2.rows.length;
		  	var jumrow2 = lastRow2 - 1;
			var hasil = 'Y';
			
			var totalcarat = Number(document.getElementById('m_totalcarat').value.replace(/,/g,""));
			var tcarat = Number(document.getElementById('tcarat').value.replace(/,/g,""));
			
			
			if(totalcarat != tcarat)
			{
				alert('Total Carat Header tidak sama dengan Detail');
				return false;
			}
			
			document.getElementById('jumrow2').value = jumrow2;
			
			
		}
		
		
		function recalc()
		{
			
			var total_ongkos  = 0;
			var qty = 0;
			var grossweight = 0;
			var olainm = 0;
			var olainr = 0;
			var totalcarat = 0;
			
			
			
			var qty = Number(document.getElementById('m_qty').value.replace(/,/g,""));
			var grossweight = Number(document.getElementById('m_grossweight').value.replace(/,/g,""));
			var olainm = Number(document.getElementById('m_olainm').value.replace(/,/g,""));
			var olainr = Number(document.getElementById('m_olainr').value.replace(/,/g,""));
			var totalcarat = Number(document.getElementById('m_totalcarat').value.replace(/,/g,""));
			
			document.getElementById('m_qty').value = formatangka(qty.toFixed().toString()) ;
			document.getElementById('m_grossweight').value = formatangka(grossweight.toFixed(2).toString()) ;
			document.getElementById('m_olainm').value = formatangka(olainm.toFixed().toString()) ;
			document.getElementById('m_olainr').value = formatangka(olainr.toFixed().toString()) ;
			
			document.getElementById('m_totalcarat').value = formatangka(totalcarat.toFixed(3).toString()) ;
		
			
			var tbl = document.getElementById('table_batu');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tbutir = 0 ;
			var tcarat = 0 ;
			
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var butir = Number(document.getElementById('m_butir' + i).value.replace(/,/g,""));
				var carat = Number(document.getElementById('m_carat' + i).value.replace(/,/g,""));
				
				
				document.getElementById('m_butir' + i).value = formatangka(butir.toFixed(0).toString()) ;
				document.getElementById('m_carat' + i).value = formatangka(carat.toFixed(3).toString()) ;
				
				
				
				tbutir = tbutir + butir;
				tcarat = tcarat + carat;
				
			    $("#sp-totbutir").html(formatangka((tbutir).toFixed(0).toString()));
			    $("#sp-totcarat").html(formatangka((tcarat).toFixed(3).toString()));
			  
			  
				document.getElementById('tbutir').value = formatangka(tbutir.toFixed(0).toString()) ;
				document.getElementById('tcarat').value = formatangka(tcarat.toFixed(3).toString()) ;
				
			}
			
			
			
			return true ;
		}
		
		
		
		

		function add_data()
		{
		  var tbl2 = document.getElementById('table_batu');
		  var lastRow2 = tbl2.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration2 = lastRow2 - 2;
		  var row2 = tbl2.insertRow(lastRow2 - 2);
		
		  
		  var cellno = row2.insertCell(0);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_shape'+iteration2+'" name="m_shape'+iteration2+'" value="" style="text-align:right;cursor:pointer" readonly onClick="listshape('+iteration2+')"/></div></td>';
		  
		  var cellno = row2.insertCell(1);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="hidden" id="m_size'+iteration2+'" name="m_size'+iteration2+'" value="" /><input class="input-small" type="text" id="m_ukuran'+iteration2+'" name="m_ukuran'+iteration2+'" value="" style="text-align:right;cursor:pointer" readonly onClick="listsize('+iteration2+')"/></div></td>';
		  
		  var cellno = row2.insertCell(2);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_butir'+iteration2+'" name="m_butir'+iteration2+'" value="0" style="text-align:right" onChange="recalc()" /></div></td>';
		  
		  var cellno = row2.insertCell(3);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_carat'+iteration2+'" name="m_carat'+iteration2+'" value="0" style="text-align:right" onChange="recalc()" /><input class="input-small" type="hidden" id="m_hargam'+iteration2+'" name="m_hargam'+iteration2+'" value="" /><input class="input-small" type="hidden" id="m_hargar'+iteration2+'" name="m_hargar'+iteration2+'" value="" /><input class="input-small" type="hidden" id="m_opbm'+iteration2+'" name="m_opbm'+iteration2+'" value="" /><input class="input-small" type="hidden" id="m_opbr'+iteration2+'" name="m_opbr'+iteration2+'" value="" /></div></td>';
		  
		  
		  var cellno = row2.insertCell(4);
		  cellno.innerHTML='<td><input type="hidden" id="a_new'+iteration2+'" name="a_new'+iteration2+'" value="Y" /><div align="center"><input type="checkbox" id="a_hapus'+iteration2+'" name="a_hapus'+iteration2+'" /></div></td>';

		  
		  document.getElementById('m_colour'+iteration2).focus();
		}
	</script>

    </body>
</html>