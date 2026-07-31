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
        <title>Sisa Batu</title>
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
			$lokasi2 = 'PUSAT-SK';
			$namalokasi2 = 'PUSAT-SK';
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam, 
					 a.m_cabang, c.m_nama as m_namalokasi, a.m_cabang2, d.m_nama as m_namalokasi2,
					 e.m_nama as m_namatukang
					 from t_transfersb a
					 left join mstukang e on a.m_tukang = e.m_kode
					 left join mslokasi c on a.m_cabang = c.m_kode
					 left join mslokasi d on a.m_cabang2 = d.m_kode 
					 where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' " ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$spk = $row['m_spk'] ;
			$ket = $row['m_keterangan'] ;
			$tukang = $row['m_tukang'] ;
			$namatukang = $row['m_namatukang'] ;
			$kota = $row['m_kota'] ;
			$status = $row['m_status'] ;
			$lokasi = $row['m_cabang'] ;
			$lokasi2 = $row['m_cabang2'] ;
			$namalokasi = $row['m_namalokasi'] ;
			$namalokasi2 = $row['m_namalokasi2'] ;
			
		}
		
		$tsqlrate = " select top 1 * from msrate where m_type = 'USD' and m_tanggal <= getdate() 
					  order by m_tanggal desc ";
		$stmtrate = sqlsrv_query( $con_dbnew, $tsqlrate);
		$rowrate = sqlsrv_fetch_array( $stmtrate, SQLSRV_FETCH_ASSOC) ;
		
		$rate = $rowrate['m_beli'];
					
    ?>
	<form class="form-horizontal" method="post" action="transfersb-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 70%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Setting Stone ('.$nomor.' )' ; ?></h4></th>
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
                        <td>Tukang</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_tukang" name="m_tukang" value="<?php echo $tukang; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_namatukang" name="m_namatukang" value="<?php echo $namatukang; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listukang()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>No. SPK</td>
                        <td colspan="3"><input class="input-medium" type="text" id="m_spk" name="m_spk" value="<?php echo $spk; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container pull-left row-fluid" style="width: 30%; padding: 0 10px;">
            <table id="table_batu" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Stone Shape</th>
                        <th>Stone Size</th>
                        <th>Dimensi</th>
                        <th>Dimensi 2</th>
                        <th>Dimensi 3</th>
                        <th>GIA</th>
                        <th>Butir</th>
                        <th>Carat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$b = 0 ;
						$tcarat = 0;
						$tbutir = 0;
						if ($nomor != '')
						{
							$tsql3 = "	select 	a.*, d.m_ukuran
										from 	t_transfersb2 a, msstone d
										where 	a.m_nomor = '".$nomor."' and
												a.m_size = d.m_size and a.m_shape = d.m_shape " ;
												
							
							//echo $tsql3;
							$stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
							while( $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC))
							{	
							
								$tcarat = $tcarat + $row3['m_carat'];
								$tbutir = $tbutir + $row3['m_butir'];
							
								$b = $b + 1 ;
								?>
								<tr>
									<td><input class="input-small" type="text" id="m_shape<?php echo $b; ?>" name="m_shape<?php echo $b; ?>" value="<?php echo $row3['m_shape']; ?>" style="text-align:center;cursor:pointer" readonly onClick="listshape(<?php echo $b; ?>)" /></td>
									<td><input class="input-small" type="hidden" id="m_size<?php echo $b; ?>" name="m_size<?php echo $b; ?>" value="<?php echo $row3['m_size']; ?>" /><input class="input-small" type="text" id="m_ukuran<?php echo $b; ?>" name="m_ukuran<?php echo $b; ?>" value="<?php echo $row3['m_ukuran']; ?>"style="text-align:center;cursor:pointer" readonly onClick="listshape(<?php echo $b; ?>)"	/></td>
									<td><input class="input-small" type="text" id="m_dimensi<?php echo $b; ?>" name="m_dimensi<?php echo $b; ?>" value="<?php echo $row3['m_dimensi']; ?>" style="text-align:right" /></td>
									<td><input class="input-small" type="text" id="m_dimensib<?php echo $b; ?>" name="m_dimensib<?php echo $b; ?>" value="<?php echo $row3['m_dimensi2']; ?>" style="text-align:right" /></td>
									<td><input class="input-small" type="text" id="m_dimensic<?php echo $b; ?>" name="m_dimensic<?php echo $b; ?>" value="<?php echo $row3['m_dimensi3']; ?>" style="text-align:right" /></td>
									<td><input class="input-small" type="text" id="m_gia<?php echo $b; ?>" name="m_gia<?php echo $b; ?>" value="<?php echo $row3['m_gia']; ?>"  /></td>
									<td><input class="input-small" type="text" id="m_butir<?php echo $b; ?>" name="m_butir<?php echo $b; ?>" value="<?php echo $row3['m_butir']; ?>" style="text-align:right" />
									<td><input class="input-small" type="text" id="m_carat<?php echo $b; ?>" name="m_carat<?php echo $b; ?>" value="<?php echo $row3['m_carat']; ?>" style="text-align:right" />
                                    </td>
									<td>
										<input type="hidden" id="m_no<?php echo $b; ?>" name="m_no<?php echo $b; ?>" value="<?php echo $row3['m_no']; ?>" />
										<input type="hidden" id="m_new<?php echo $b; ?>" name="m_new<?php echo $b; ?>" value="T" />
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
                        <th colspan="6"></th>
                        <th><div id="sp-totbutir" align="right"><?php echo number_format($tbutir, 0, '.', ','); ?></div></th>
                        <th><div id="sp-totcarat" align="right"><?php echo number_format($tcarat, 3, '.', ','); ?></div></th>
                        <th></th>
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
        <span id="dataplu">
            <input type="text" id="cek_kodebarang" name="cek_kodebarang" value="" />
            <input type="text" id="cek_noplu" name="cek_noplu" value="" />
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
    <div id="dialog-listlokasi">
        <span id="datalokasi">
        </span>
    </div>
    <div id="dialog-listlokasi2">
        <span id="datalokasi2">
        </span>
    </div>

    <div id="dialog-listitem">
        <span id="dataitem">
        </span>
    </div>
    
    <div id="dialog-listukang">
        <span id="datatukang">
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
		$( "#dialog-listukang" ).dialog({
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
		
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("ttb.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
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
		
		
		function listlokasi()
		{
			var data={tx:$('#m_namalokasi').val()};

			var fungsi=function(respon){
					$("#datalokasi").html(respon);
				};
			$.get('transfersb-ceklokasi.php',data,fungsi);
			
			$( "#dialog-listlokasi" ).dialog( "open" );
		}
		
		function selectlokasi(vkode,vnama)
		{
			document.getElementById('m_lokasi').value = vkode ;
			document.getElementById('m_namalokasi').value = vnama ;

			$( "#dialog-listlokasi" ).dialog( "close" );
		}
		
		function listukang()
		{
			var data={tx:$('#m_namatukang').val()};

			var fungsi=function(respon){
					$("#datatukang").html(respon);
				};
			$.get('transfersb-cektukang.php',data,fungsi);
			
			$( "#dialog-listukang" ).dialog( "open" );
		}
		
		function selectukang(vkode,vnama)
		{
			document.getElementById('m_tukang').value = vkode ;
			document.getElementById('m_namatukang').value = vnama;

			$( "#dialog-listukang" ).dialog( "close" );
		}
		
		
		function listlokasi2()
		{
			var data={tx:$('#m_namalokasi2').val()};

			var fungsi=function(respon){
					$("#datalokasi2").html(respon);
				};
			$.get('transfersb-ceklokasi2.php',data,fungsi);
			
			$( "#dialog-listlokasi2" ).dialog( "open" );
		}
		
		function selectlokasi2(vkode,vnama)
		{
			document.getElementById('m_lokasi2').value = vkode ;
			document.getElementById('m_namalokasi2').value = vnama ;

			$( "#dialog-listlokasi2" ).dialog( "close" );
		}
		
		
		function selectshape(rowke,shape,size,ukuran,hargam,hargar,opbm,opbr)
		{
			document.getElementById('m_shape'+rowke).value = shape ;
			document.getElementById('m_size'+rowke).value = size ;
			document.getElementById('m_ukuran'+rowke).value = ukuran ;
			
			$( "#dialog-listshape" ).dialog( "close" );
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
		  	var jumrow2 = lastRow2 - 3;
			var hasil = 'Y';
			
			
			
			
			document.getElementById('jumrow2').value = jumrow2;
			
		}
		
		
		function recalc2()
		{
			
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
				
			}
			
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
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_dimensi'+iteration2+'" name="m_dimensi'+iteration2+'" value="" style="text-align:right" /></div></td>';
		  
		  var cellno = row2.insertCell(3);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_dimensib'+iteration2+'" name="m_dimensib'+iteration2+'" value="" style="text-align:right" /></div></td>';
		  
		  var cellno = row2.insertCell(4);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_dimensic'+iteration2+'" name="m_dimensic'+iteration2+'" value="" style="text-align:right" /></div></td>';
		  
		  var cellno = row2.insertCell(5);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_gia'+iteration2+'" name="m_gia'+iteration2+'" value=""/></div></td>';
		  
		  var cellno = row2.insertCell(6);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_butir'+iteration2+'" name="m_butir'+iteration2+'" value="0" style="text-align:right" onChange="recalc2()" /></div></td>';
		  
		  var cellno = row2.insertCell(7);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_carat'+iteration2+'" name="m_carat'+iteration2+'" value="0" style="text-align:right" onChange="recalc2()" /></div></td>';
		  
		  var cellno = row2.insertCell(8);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration2+'" name="m_new'+iteration2+'" value="Y" /><input type="hidden" id="m_no'+iteration2+'" name="m_no'+iteration2+'" value="'+iteration2+'" /><div align="center"><input type="checkbox" id="m_hapus'+iteration2+'" name="m_hapus'+iteration2+'" /></div></td>';

		  
		}
	</script>

    </body>
</html>