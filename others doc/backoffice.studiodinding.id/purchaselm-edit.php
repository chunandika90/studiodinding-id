<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcab = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
	$kdstore = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>INV Purchase ( LM )</title>
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
			$harga1 = 0 ;
			$ongkos1 = 0 ;
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam from t_purchase a where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."'" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$kdsupl = $row['m_kodesupl'] ;
			$ket = $row['m_keterangan'] ;
			$doc = $row['m_dokumen'] ;
			$status = $row['m_status'] ;
			$harga1 = $row['m_harga1'] ;
			$ongkos1 = $row['m_ongkos1'] ;
		}
		$lokasi = $kdcab.'-0' ;
		
    ?>
	<form class="form-horizontal" method="post" action="purchaselm-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Inv.Purchase ( '.$kdcab.' '.$nomor.' )' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="150">
                        	<input type="hidden" id="kdstore" name="kdstore" value="<?php echo $_GET['st']; ?>" />
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                        	<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                        	<input type="hidden" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="100">Tanggal</td>
                        <td width="150"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kodesupl" name="m_kodesupl" value="<?php echo $kdsupl; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listcust()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Documen ID</td>
                        <td colspan="3"><input class="input-large" type="text" id="m_dokumen" name="m_dokumen" value="<?php echo $doc; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Harga / gram</td>
                        <td><input class="input-large" type="text" id="m_harga" name="m_harga" value="<?php echo number_format($harga1, 0, '.', ','); ?>" onChange="recalc()"/></td>
                        <td>Tambahan Ongkos</td>
                        <td><input class="input-large" type="text" id="m_ongkos" name="m_ongkos" value="<?php echo number_format($ongkos1, 0, '.', ','); ?>" onChange="recalc()" /></td>
                    </tr>
                    <tr>
                    	<td colspan="4">
                            <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                            <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $periode; ?>')" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 90%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Item LM</th>
                        <th>Product ID</th>
                        <th><div align="center">Qty</div></th>
                        <th>T.Berat</th>
                        <th>Harga M/Gr</th>
                        <th>Ongkos /pcs</th>
                        <th>Total</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$tberat = 0 ;
						$ttotal = 0 ;

						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_item, c.m_nama as co_namabarang, c.m_kode2
										from 	t_purchase2 a, t_stockdata b, msmaster c 
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												c.m_type = 'ITEM' and 
												b.m_item = c.m_kode " ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$dumb = explode('-',$row2['m_kode2']);
								$berat = $dumb[1];
								
								$tqty = $tqty + $row2['m_qty']; 
								$tberat = $tberat + ($row2['m_qty'] * $berat) ;
								$ttotal = $ttotal + ($row2['m_qty'] * $berat * $row2['m_harga']) + ($row2['m_qty'] * $row2['m_ongkos']) ;

								?>
								<tr>
                                	<td>
                                        <input class="input-medium" type="text" id="x_item<?php echo $i; ?>" name="x_item<?php echo $i; ?>" value="<?php echo $row2['co_namabarang']; ?>" readonly >
                                        <input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="<?php echo $row2['m_item']; ?>" >
                                        <input type="hidden" id="m_berat<?php echo $i; ?>" name="m_berat<?php echo $i; ?>" value="<?php echo $berat; ?>" >
                                    </td>
									<td>
                                        <input class="input-medium" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" readonly >
									</td>
									<td>
                                    	<div align="center">
                                        	<input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly />
										</div>
									</td>
									<td>
                                    	<div align="center">
                                        	<input class="input-mini" type="text" id="m_totberat<?php echo $i; ?>" name="m_totberat<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'] * $berat, 2, '.', ','); ?>" style="text-align:center" readonly />
										</div>
									</td>
									<td>
                                        <input class="input-medium" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="<?php echo number_format($row2['m_harga'], 0, '.', ','); ?>" style="text-align:right" readonly />
									</td>
									<td>
                                        <input class="input-medium" type="text" id="m_ongkos<?php echo $i; ?>" name="m_ongkos<?php echo $i; ?>" value="<?php echo number_format($row2['m_ongkos'], 0, '.', ','); ?>" style="text-align:right" readonly />
									</td>
									<td>
                                        <input class="input-medium" type="text" id="m_total<?php echo $i; ?>" name="m_total<?php echo $i; ?>" value="<?php echo number_format($row2['m_ongkos'], 0, '.', ','); ?>" style="text-align:right" readonly />
									</td>
									<td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" onChange="recalc()" /></div>
                                    </td>
								</tr>
								<?php
							}
						}

						$addrow = 1 ;
						while( $addrow <= 3 )
						{
							$addrow = $addrow + 1 ;
							$i = $i + 1 ;
							?>
							<tr>
								<td>
									<input class="input-medium" type="text" id="x_item<?php echo $i; ?>" name="x_item<?php echo $i; ?>" value="<?php echo $row2['co_namabarang']; ?>" onClick="oc_item(<?php echo $i; ?>)" style="cursor:pointer" >
									<input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" >
									<input type="hidden" id="m_berat<?php echo $i; ?>" name="m_berat<?php echo $i; ?>" value="0" >
								</td>
								<td>
									<input class="input-medium" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" >
								</td>
								<td>
									<div align="center">
										<input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc()" />
									</div>
								</td>
								<td>
									<div align="center">
										<input class="input-mini" type="text" id="m_totberat<?php echo $i; ?>" name="m_totberat<?php echo $i; ?>" value="0" style="text-align:center" readonly />
									</div>
								</td>
								<td>
									<input class="input-medium" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="0" style="text-align:right" readonly />
								</td>
								<td>
									<input class="input-medium" type="text" id="m_ongkos<?php echo $i; ?>" name="m_ongkos<?php echo $i; ?>" value="0" style="text-align:right" readonly />
								</td>
								<td>
									<input class="input-medium" type="text" id="m_total<?php echo $i; ?>" name="m_total<?php echo $i; ?>" value="0" style="text-align:right" readonly />
								</td>
								<td>
									<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
									<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" onChange="recalc()" /></div>
								</td>
							</tr>
							<?php
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2"></td>
                        <th>
                            <div align="center">
                                <input class="input-mini" type="text" id="m_gtotqty" name="m_gtotqty" value="<?php echo number_format($tqty, 0, '.', ',') ; ?>" style="text-align:center;font-weight:bold" readonly/>
                            </div>
                        </th>
                        <th>
                            <div align="center">
                                <input class="input-mini" type="text" id="m_gtotw" name="m_gtotw" value="<?php echo number_format($tberat, 2, '.', ',') ; ?>" style="text-align:center;font-weight:bold" readonly/>
                            </div>
                        </th>
                        <th colspan="2"></th>
                        <th>
                            <input class="input-medium" type="text" id="m_gtotal" name="m_gtotal" value="<?php echo number_format($ttotal, 0, '.', ',') ; ?>" style="text-align:right;font-weight:bold" readonly />
                        </th>
                        <th>
                        </th>
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
		</div>
    </form>

    <div id="dialog-listcust">
        <span id="datacust">
        </span>
    </div>

    <div id="dialog-listitem">
        <span id="dataitem">
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
			$( "#dialog-listcust" ).dialog({
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
			window.open("purchaselm.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datacust").html(respon);
				};
			$.get('purchase-ceksupplier.php',data,fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}

		function selectcust(vkode,vnama)
		{
			document.getElementById('m_kodesupl').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listcust" ).dialog( "close" );
		}


		function oc_item(rowke)
		{
			var data={rk:rowke};
			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('purchaselm-cekitem.php',data,fungsi);
			
			$( "#dialog-listitem" ).dialog( "open" );
		}

		function selectitem(rowke,vkode,vnama,vberat,vongkos)
		{
			var vw = Number(vberat.replace(/,/g,""));
			var vo = Number(vongkos.replace(/,/g,""));
			var hargam = Number(document.getElementById('m_harga').value.replace(/,/g,""));
			document.getElementById('m_item'+rowke).value = vkode ;
			document.getElementById('x_item'+rowke).value = vnama ;
			document.getElementById('m_berat'+rowke).value = vberat ;
			document.getElementById('m_harga'+rowke).value = formatangka(hargam.toFixed().toString()) ;
			document.getElementById('m_totberat'+rowke).value = formatangka(vw.toFixed(2).toString()) ;
			document.getElementById('m_ongkos'+rowke).value = formatangka(vo.toFixed().toString()) ;
			document.getElementById('m_qty'+rowke).value = '1' ;
			if ( vw < 10 )
			{
				document.getElementById('m_productid'+rowke).readOnly = true ;
				document.getElementById('m_qty'+rowke).readOnly = false ;
			}
			else
			{
				document.getElementById('m_productid'+rowke).value = '' ;
				document.getElementById('m_productid'+rowke).readOnly = false ;
				document.getElementById('m_qty'+rowke).readOnly = true ;
			}
			$( "#dialog-listitem" ).dialog( "close" );
			recalc();

		}

		function recalc()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
			// if there's no header row in the table, then iteration = lastRow + 1
		  	var jumrow = lastRow - 3;
			var tberat = 0 ;
			var tqty = 0 ;
			var ttotal = 0 ;
			var hargam = Number(document.getElementById('m_harga').value.replace(/,/g,""));
			var tambahan = Number(document.getElementById('m_ongkos').value.replace(/,/g,""));
			var plusm = 0 ;

			document.getElementById('m_harga').value = formatangka(hargam.toFixed().toString()) ;
			document.getElementById('m_ongkos').value = formatangka(tambahan.toFixed().toString()) ;
			
			if ( tambahan > 0 )
			{
				for(var i=1; i <= jumrow; i++) 
				{					
					var berat = Number(document.getElementById('m_berat' + i).value.replace(/,/g,""));
					var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
					if ( document.getElementById('m_hapus' + i).checked != true ) { tberat = tberat + ( berat * qty ) ;}
				}
				if ( tberat > 0 ){ plusm = tambahan / tberat } ;
			}	
			
			tberat = 0 ;		
			for(var i=1; i <= jumrow; i++) 
			{
				if ( document.getElementById('m_hapus' + i).checked != true )
				{
					var berat = Number(document.getElementById('m_berat' + i).value.replace(/,/g,""));
					var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
					var ongkos = Number(document.getElementById('m_ongkos' + i).value.replace(/,/g,""));
					var total = ( qty * berat * (hargam + plusm) ) + ongkos ;
					var vitem = document.getElementById('m_item' + i).value ;				
					if (vitem != '' )
					{
						document.getElementById('m_qty' + i).value = formatangka(qty.toFixed().toString()) ;
						document.getElementById('m_totberat' + i).value = formatangka((qty * berat).toFixed(2).toString()) ;
						document.getElementById('m_harga' + i).value = formatangka((hargam + plusm).toFixed().toString()) ;
						document.getElementById('m_ongkos' + i).value = formatangka(ongkos.toFixed().toString()) ;
						document.getElementById('m_total' + i).value = formatangka(total.toFixed().toString()) ;
						tqty = tqty + qty ;
						tberat = tberat + ( qty * berat );
						ttotal = ttotal + total ;
					}
					else
					{
						document.getElementById('m_qty' + i).value = 0 ;
						document.getElementById('m_totberat' + i).value = 0 ;
						document.getElementById('m_harga' + i).value = 0 ;
						document.getElementById('m_ongkos' + i).value = 0 ;
						document.getElementById('m_total' + i).value = 0 ;
					}
				}
			}
			document.getElementById('m_gtotqty').value = formatangka(tqty.toFixed().toString()) ;
			document.getElementById('m_gtotw').value = formatangka(tberat.toFixed(2).toString()) ;
			document.getElementById('m_gtotal').value = formatangka(ttotal.toFixed().toString()) ;
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 3;
			var cekplu = 'Y';
			document.getElementById('jumrow').value = jumrow;
			for(var i=1; i <= jumrow; i++) 
			{
				if ( document.getElementById('m_hapus' + i).checked != true )
				{
					var berat = Number(document.getElementById('m_berat' + i).value.replace(/,/g,""));
					var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
					var vitem = document.getElementById('m_item' + i).value ;
					var noplu = document.getElementById('m_productid' + i).value ;
					
					if ((berat >= 10) && (noplu == ''))
					{
						cekplu = 'T';
					}
				}
			}
			if ( cekplu == 'T' )
			{ 
				alert('Untuk LM mulai 10gr ke atas harus ada nomor sertikat !!!');
				return false ;
			}
			else
			{
				return true ;
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
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="x_item'+iteration+'" name="x_item'+iteration+'" value="" onClick="oc_item('+iteration+')" style="cursor:pointer" ><input type="hidden" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" ><input type="hidden" id="m_berat'+iteration+'" name="m_berat'+iteration+'" value="0" ></td>';

		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" ></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="0" style="text-align:center" /></div></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_totberat'+iteration+'" name="m_totberat'+iteration+'" value="0" style="text-align:center" readonly /></div></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="0" style="text-align:right" readonly /></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_ongkos'+iteration+'" name="m_ongkos'+iteration+'" value="0" style="text-align:right" readonly /></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_total'+iteration+'" name="m_total'+iteration+'" value="0" style="text-align:right" readonly /></td>';
		  
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_productid'+iteration).focus();
		}

	</script>

    </body>
</html>