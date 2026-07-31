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
        <title>Terima Poles</title>
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
			$supplier = '';
			$namasupplier = '';
			$ket = '' ;
			$spk = '' ;
			$doc = '' ;
			$status = 'A';
			$lokasi2 = 'PUSAT-SK-R';
			$namalokasi2 = 'PUSAT-SK-R';
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam, 
					 b.m_nama as nama_supplier, a.m_lokasi, c.m_nama as m_namalokasi, a.m_lokasi2, d.m_nama as m_namalokasi2
					 from t_barang_in a
					 left join mslokasi c on a.m_lokasi = c.m_kode
					 left join mslokasi d on a.m_lokasi2 = d.m_kode
					 left join mssupplier b on a.m_supplier = b.m_kode 
					 where a.m_lokasi = '".$kdcab."' and a.m_nomor = '".$nomor."' " ;
			//echo $tsql;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$supplier = $row['m_supplier'] ;
			$namasupplier = $row['nama_supplier'] ;
			$kota = $row['m_kota'] ;
			$status = $row['m_status'] ;
			$spk = $row['m_spk'] ;
			$lokasi = $row['m_lokasi'] ;
			$lokasi2 = $row['m_lokasi2'] ;
			$namalokasi = $row['m_namalokasi'] ;
			$namalokasi2 = $row['m_namalokasi2'] ;
		}
		
    ?>
	<form class="form-horizontal" method="post" action="poles_in-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 100%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Penerimaan Poles('.$lokasi.' )' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="10">
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                            <input type="hidden" id="jumrow2" name="jumrow2" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="50">Tanggal</td>
                        <td width="350"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                     <tr>
                        <td>Nama Supplier</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_supplier" name="m_supplier" value="<?php echo $supplier; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $namasupplier; ?>" readonly />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupplier()"></i></span>
                            </div>
                        </td>
                    </tr>
                   
                     <tr>
                        <td>From Lokasi</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_namalokasi" name="m_namalokasi" value="<?php echo $namalokasi; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlokasi()"></i></span>
                            </div>
                        </td>
                    </tr>
                     <tr>
                        <td>To Lokasi</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_lokasi2" name="m_lokasi2" value="<?php echo $lokasi2; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_namalokasi2" name="m_namalokasi2" value="<?php echo $namalokasi2; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlokasi2()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Nomor SPK</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_spk" name="m_spk" value="<?php echo $spk; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_ket" name="m_ket" value="<?php echo $ket; ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Type</th>
                        <th>Kode Barang</th>
                        <th>Nama Tukang</th>
                        <th>Total Qty</th>
                        <th>Total Berat</th>
                        <th>Keterangan</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$tgrossweight = 0 ;
							
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_nama as m_namabarang
										from 	t_barang_in2 a
												left join msmaster b on b.m_type = 'MATERIAL' and a.m_kodebarang = b.m_kode
										where 	a.m_lokasi = '".$lokasi."' and 
												a.m_nomor = '".$nomor."'   " ;
							//echo $tsql2;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								
								$tqty = $tqty + $row2['m_qty'] ;
								$tgrossweight = $tgrossweight + $row2['m_grossweight'] ;
								?>
								<tr>
                				<td><?php echo $i; ?>
                                    	<input type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>" />
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="<?php echo $row2['m_kodebarang']; ?>" />
                                    </td>
                                    <td> <input class="input-large" type="text" id="m_type<?php echo $i; ?>" name="m_type<?php echo $i; ?>" value="<?php echo $row2['m_type']; ?>" onClick="listtype('<?php echo $i ; ?>')" style="cursor:pointer" readonly/></td>
                                    <td> <input class="input-large" type="text" id="m_namabarang<?php echo $i; ?>" name="m_namabarang<?php echo $i; ?>" value="<?php echo $row2['m_namabarang']; ?>" onClick="listitem('<?php echo $i ; ?>')" style="cursor:pointer" readonly/></td>
                                    <td> <input class="input-large" type="text" id="m_tukang<?php echo $i; ?>" name="m_tukang<?php echo $i; ?>" value="<?php echo $row2['m_tukang']; ?>" onClick="listtukang('<?php echo $i ; ?>')" style="cursor:pointer" readonly/></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_grossweight<?php echo $i; ?>" name="m_grossweight<?php echo $i; ?>" value="<?php echo number_format($row2['m_grossweight'], 2, '.', ','); ?>" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></div></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="<?php echo $row2['m_keterangan']; ?>" style="text-align:left"/></td>
									<td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
                                    </td>
								</tr>
								<?php
							}
						}
						else
						{
							$addrow = 1 ;
							while( $addrow <= 1 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr>
									<td><?php echo $i; ?>
									<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="" />
									<input type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $i; ?>" />
                                    <td><input class="input-large" type="text" id="m_type<?php echo $i; ?>" name="m_type<?php echo $i; ?>" value="" readonly onClick="listtype('<?php echo $i ; ?>')" style="cursor:pointer"/>
									</td>
                                    <td><input class="input-large" type="text" id="m_namabarang<?php echo $i; ?>" name="m_namabarang<?php echo $i; ?>" value="" readonly onClick="listbarang('<?php echo $i ; ?>')" style="cursor:pointer"/>
									</td>
                                    <td><input class="input-large" type="text" id="m_tukang<?php echo $i; ?>" name="m_tukang<?php echo $i; ?>" value="" readonly onClick="listtukang('<?php echo $i ; ?>')" style="cursor:pointer"/>
									</td>
									<td><input class="input-medium" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></td>
									<td><input class="input-medium" type="text" id="m_grossweight<?php echo $i; ?>" name="m_grossweight<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="" style="text-align:left" /></td>
									<td>
										<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
									</td>
								</tr>
								<?php
							}
						}
                    ?>
                </tbody>
                <tfoot>           
                    <tr>
                        <th colspan="4"></th>
                        <th><div id="sp-totqty" align="center"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>
                        <th><div id="sp-totgross" align="center"><?php echo number_format($tgrossweight, 0, '.', ','); ?></div></th>
                        <th colspan="1"></th>
                    </tr>
                    <tr>
                        <th colspan="8">
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
            
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
        <h5>Barang Asal</h5>
            <table id="table_data2" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Out</th>
                        <th>Kode Barang</th>
                        <th>Total Qty</th>
                        <th>Total Berat</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $atqty = 0 ;
						$atgrossweight = 0 ;
							
						if ($nomor != '')
						{
							$tsql3 = "	select 	a.*, b.m_nama as m_namabarang
										from 	t_barang_in3 a
												left join msmaster b on b.m_type = 'MATERIAL' and a.m_kodebarang = b.m_kode
										where 	a.m_lokasi = '".$lokasi."' and 
												a.m_nomor = '".$nomor."'   " ;
							
							$stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
							while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								
								$atqty = $atqty + $row3['m_qty'] ;
								$atgrossweight = $atgrossweight + $row3['m_grossweight'] ;
								?>
								<tr>
                				<td><?php echo $i; ?>
                                    	<input type="hidden" id="a_no<?php echo $i; ?>" name="a_no<?php echo $i; ?>" value="<?php echo $row3['m_no']; ?>" />
										<input type="hidden" id="a_kodebarang<?php echo $i; ?>" name="a_kodebarang<?php echo $i; ?>" value="<?php echo $row3['m_kodebarang']; ?>" />
                                    </td>
                                    <td> <input class="input-large" type="text" id="a_nodoc<?php echo $i; ?>" name="a_nodoc<?php echo $i; ?>" value="<?php echo $row3['m_nodoc']; ?>" /></td>
                                    <td> <input class="input-large" type="text" id="a_namabarang<?php echo $i; ?>" name="a_namabarang<?php echo $i; ?>" value="<?php echo $row3['m_namabarang']; ?>" onClick="listbarang2('<?php echo $i ; ?>')" style="cursor:pointer" readonly/></td>
									<td><div align="center"><input class="input-mini" type="text" id="a_qty<?php echo $i; ?>" name="a_qty<?php echo $i; ?>" value="<?php echo number_format($row3['m_qty'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc2('<?php echo $i ; ?>')" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="a_grossweight<?php echo $i; ?>" name="a_grossweight<?php echo $i; ?>" value="<?php echo number_format($row3['m_grossweight'], 2, '.', ','); ?>" style="text-align:center" onChange="recalc2('<?php echo $i ; ?>')" /></div></td>
									<td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
                                    </td>
								</tr>
								<?php
							}
						}
						else
						{
							$addrow = 1 ;
							while( $addrow <= 1 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr>
									<td><?php echo $i; ?>
									<input type="hidden" id="a_kodebarang<?php echo $i; ?>" name="a_kodebarang<?php echo $i; ?>" value="" />
									<input type="hidden" id="a_no<?php echo $i; ?>" name="a_no<?php echo $i; ?>" value="<?php echo $i; ?>" />
									<td><input class="input-large" type="text" id="a_nodoc<?php echo $i; ?>" name="a_nodoc<?php echo $i; ?>" value="" style="text-align:center"  /></td>
                                    <td><input class="input-large" type="text" id="a_namabarang<?php echo $i; ?>" name="a_namabarang<?php echo $i; ?>" value="" readonly onClick="listbarang2('<?php echo $i ; ?>')" style="cursor:pointer"/>
									</td>
									<td><input class="input-medium" type="text" id="a_qty<?php echo $i; ?>" name="a_qty<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc2('<?php echo $i ; ?>')" /></td>
									<td><input class="input-medium" type="text" id="a_grossweight<?php echo $i; ?>" name="a_grossweight<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc2('<?php echo $i ; ?>')" /></td>
									<td>
										<input type="hidden" id="a_new<?php echo $i; ?>" name="a_new<?php echo $i; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="a_hapus<?php echo $i; ?>" name="a_hapus<?php echo $i; ?>" /></div>
									</td>
								</tr>
								<?php
							}
						}
                    ?>
                </tbody>
                <tfoot>           
                    <tr>
                        <th colspan="3"></th>
                        <th><div id="sp-atotqty" align="center"><?php echo number_format($atqty, 0, '.', ','); ?></div></th>
                        <th><div id="sp-atotgross" align="center"><?php echo number_format($atgrossweight, 2, '.', ','); ?></div></th>
                        <th colspan="1"></th>
                    </tr>
                    <tr>
                        <th colspan="8">
                        <div>
                            <div class="pull-left" >
                                <input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data2()" />
                            </div>
                        </div>
                      	</th>
                    </tr>
                </tfoot>
            </table>       
		</div>
    </form>

    <div id="tempdata" class="hide">
        <span id="dataplu">
            <input type="text" id="cek_kodebarang" name="cek_kodebarang" value="" />
            <input type="text" id="cek_noplu" name="cek_noplu" value="1" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_karet" name="cek_karet" value="0" />
        </span>
    </div>         
    
    <div id="dialog-listsupplier">
        <span id="datasupplier">
        </span>
    </div>
    
    <div id="dialog-lokasi">
        <span id="datalokasi">
        </span>
    </div>
    
    <div id="dialog-lokasi2">
        <span id="datalokasi2">
        </span>
    </div>
    
    <div id="dialog-datatype">
        <span id="datatype">
        </span>
    </div>
    <div id="dialog-databarang">
        <span id="databarang">
        </span>
    </div>
    <div id="dialog-databarang2">
        <span id="databarang2">
        </span>
    </div>
    <div id="dialog-datatukang">
        <span id="datatukang">
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
			$( "#dialog-listlokasi2" ).dialog({
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
			$( "#dialog-datatype" ).dialog({
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
			$( "#dialog-databarang" ).dialog({
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
			$( "#dialog-databarang2" ).dialog({
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
			$( "#dialog-datatukang" ).dialog({
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
			
		});
		
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("retursupp.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		
		
		function listsupplier()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datasupplier").html(respon);
				};
			$.get('poles_in-ceksupplier.php',data,fungsi);
			
			$( "#dialog-listsupplier" ).dialog( "open" );
		}

		
		function selectsupplier(vkode,vnama)
		{
			document.getElementById('m_supplier').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listsupplier" ).dialog( "close" );
		}
		
		function listlokasi()
		{
			var data={tx:$('#m_namalokasi').val()};

			var fungsi=function(respon){
					$("#datasupplier").html(respon);
				};
			$.get('poles_in-ceklokasi.php',data,fungsi);
			
			$( "#dialog-listsupplier" ).dialog( "open" );
		}
		
		function selectlokasi(vkode,vnama)
		{
			document.getElementById('m_lokasi').value = vkode ;
			document.getElementById('m_namalokasi').value = vnama ;

			$( "#dialog-listsupplier" ).dialog( "close" );
		}
		
		
		function listlokasi2()
		{
			var data={tx:$('#m_namalokasi2').val()};

			var fungsi=function(respon){
					$("#datasupplier").html(respon);
				};
			$.get('poles_in-ceklokasi2.php',data,fungsi);
			
			$( "#dialog-listsupplier" ).dialog( "open" );
		}
		
		function selectlokasi2(vkode,vnama)
		{
			document.getElementById('m_lokasi2').value = vkode ;
			document.getElementById('m_namalokasi2').value = vnama ;

			$( "#dialog-listsupplier" ).dialog( "close" );
		}

		function selectplu(vkode)
		{
			$( "#dialog-listdoc" ).dialog( "close" );
		}

		
		function listtype(rowke)
		{
			var data={rk:rowke};
			var fungsi=function(respon){
					$("#datatype").html(respon);
				};
			$.get('poles_in-cektype.php',data,fungsi);
			
			$( "#dialog-datatype" ).dialog( "open" );
		}

		function selecttype(rowke,kodetype)
		{
			document.getElementById('m_type'+rowke).value = kodetype ;
			$( "#dialog-datatype" ).dialog( "close" );
		}
		
		function listbarang(rowke)
		{
			var data={rk:rowke};
			var fungsi=function(respon){
					$("#databarang").html(respon);
				};
			$.get('poles_in-cekbahan.php',data,fungsi);
			
			$( "#dialog-databarang" ).dialog( "open" );
		}

		function selectbarang(rowke,kodebarang,namabarang)
		{
			
			document.getElementById('m_kodebarang'+rowke).value = kodebarang ;
			document.getElementById('m_namabarang'+rowke).value = namabarang ;
			$( "#dialog-databarang" ).dialog( "close" );
		}
		
		
		function listbarang2(rowke)
		{
			var data={rk:rowke};
			var fungsi=function(respon){
					$("#databarang2").html(respon);
				};
			$.get('poles_in-cekbahan2.php',data,fungsi);
			
			$( "#dialog-databarang2" ).dialog( "open" );
		}

		function selectbarang2(rowke,kodebarang,namabarang)
		{
			
			document.getElementById('a_kodebarang'+rowke).value = kodebarang ;
			document.getElementById('a_namabarang'+rowke).value = namabarang ;
			$( "#dialog-databarang2" ).dialog( "close" );
		}
		
		function listtukang(rowke)
		{
			var data={rk:rowke};
			var fungsi=function(respon){
					$("#datatukang").html(respon);
				};
			$.get('poles_in-cektukang.php',data,fungsi);
			
			$( "#dialog-datatukang" ).dialog( "open" );
		}

		function selecttukang(rowke,kodetukang)
		{
			
			document.getElementById('m_tukang'+rowke).value = kodetukang ;
			$( "#dialog-datatukang" ).dialog( "close" );
		}

		
		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			document.getElementById('jumrow').value = jumrow;
			
			
			var tbl2 = document.getElementById('table_data2');
			var lastRow2 = tbl2.rows.length;
		  	var jumrow2 = lastRow2 - 2;
			document.getElementById('jumrow2').value = jumrow2;
			
			return true;
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tqty = 0 ;
			var tgross = 0 ;
			
			
			for(var i=1; i <= jumrow; i++) 
			{	
				
		  	    var qty = Number(document.getElementById('m_qty'+i).value.replace(/,/g,""));
		  	    var gross = Number(document.getElementById('m_grossweight'+i).value.replace(/,/g,""));
			  
				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed(0).toString()) ;
				document.getElementById('m_grossweight' + i).value = formatangka(gross.toFixed(2).toString()) ;
				
				tqty = tqty + qty;
				tgross = tgross + gross;
				
			  $("#sp-totqty").html(formatangka((tqty).toFixed(0).toString()));
			  $("#sp-totgross").html(formatangka((tgross).toFixed(2).toString()));
				
			}
			
		}
		
		function recalc2()
		{
			
			var tbl = document.getElementById('table_data2');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tqty = 0 ;
			var tgross = 0 ;
			
			
			for(var i=1; i <= jumrow; i++) 
			{	
				
		  	    var qty = Number(document.getElementById('a_qty'+i).value.replace(/,/g,""));
		  	    var gross = Number(document.getElementById('a_grossweight'+i).value.replace(/,/g,""));
			  
				document.getElementById('a_qty' + i).value = formatangka(qty.toFixed(0).toString()) ;
				document.getElementById('a_grossweight' + i).value = formatangka(gross.toFixed(2).toString()) ;
				
				tqty = tqty + qty;
				tgross = tgross + gross;
				
			  $("#sp-atotqty").html(formatangka((tqty).toFixed(0).toString()));
			  $("#sp-atotgross").html(formatangka((tgross).toFixed(2).toString()));
				
			}
			
		}

		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 2;
		  var row = tbl.insertRow(lastRow - 2);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td><input type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="'+iteration+'" /><input type="hidden" id="m_kodebarang'+iteration+'" name="m_kodebarang'+iteration+'" value="" />'+iteration+'</td>';
		  
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="m_type'+iteration+'" name="m_type'+iteration+'" value="" onclick="listtype('+iteration+')"readonly style = "cursor:pointer" /></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="m_namabarang'+iteration+'" name="m_namabarang'+iteration+'" value="" onclick="listbarang('+iteration+')"readonly style = "cursor:pointer" /></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="m_tukang'+iteration+'" name="m_tukang'+iteration+'" value="" onclick="listtukang('+iteration+')"readonly style = "cursor:pointer" /></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="0" style="text-align:center" onChange="recalc('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_grossweight'+iteration+'" name="m_grossweight'+iteration+'" value="0" style="text-align:center" onChange="recalc('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value="" style="text-align:left"  /></td>';
		  
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		}
		
		function add_data2()
		{
		  var tbl = document.getElementById('table_data2');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 2;
		  var row = tbl.insertRow(lastRow - 2);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td><input type="hidden" id="a_no'+iteration+'" name="a_no'+iteration+'" value="'+iteration+'" /><input type="hidden" id="a_kodebarang'+iteration+'" name="a_kodebarang'+iteration+'" value="" />'+iteration+'</td>';
		  
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="a_nodoc'+iteration+'" name="a_nodoc'+iteration+'" value=""  /></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="a_namabarang'+iteration+'" name="a_namabarang'+iteration+'" value="" onclick="listbarang2('+iteration+')"readonly style = "cursor:pointer" /></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="a_qty'+iteration+'" name="a_qty'+iteration+'" value="0" style="text-align:center" onChange="recalc2('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="a_grossweight'+iteration+'" name="a_grossweight'+iteration+'" value="0" style="text-align:center" onChange="recalc2('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input type="hidden" id="a_new'+iteration+'" name="a_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="a_hapus'+iteration+'" name="a_hapus'+iteration+'" /></div></td>';
		  
		}
	</script>

    </body>
</html>