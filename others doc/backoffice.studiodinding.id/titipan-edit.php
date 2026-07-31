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
        <title>Titipan Customer</title>
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
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam , b.m_alamat, 
					 b.m_kota, b.m_telepon1, b.m_telepon2
					 from t_pos a, mscustomer b where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' and a.m_kodecust = b.m_kode" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$kdcust = $row['m_kodecust'] ;
			$kota = $row['m_kota'] ;
			$telepon = $row['m_telepon1'] ;
			$telepon2 = $row['m_telepon2'] ;
			$ket = $row['m_keterangan'] ;
			$doc = $row['m_dokumen'] ;
			$status = $row['m_status'] ;
		}
		$lokasi = $kdcab.'-0' ;
		
    ?>
	<form class="form-horizontal" method="post" action="titipan-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 100%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Titipan ('.$kdcab.')' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="10">
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                            
                        	<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="50">Tanggal</td>
                        <td width="350"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                     <tr>
                        <td>Nama</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kodecust" name="m_kodecust" value="<?php echo $kdcust; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listcust()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td colspan="3">
				            <textarea class="input-xxlarge" name="m_alamat" id="m_alamat" cols="200" rows="2" ><?php echo $row['m_alamat']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td><input class="input-medium" type="text" id="m_kota" name="m_kota" value="<?php echo $kota; ?>" /></td>
                        <td>Phone</td>
                        <td>
                        	<input class="input-medium" type="text" id="m_telepon1" name="m_telepon1" value="<?php echo $telepon; ?>" />
                            <input class="input-medium" type="text" id="m_telepon2" name="m_telepon2" value="<?php echo $telepon2; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Naming Product</th>
                        <th>Group</th>
                        <th>Item</th>
                        <th><div align="center">Qty</div></th>
                        <th>Harga</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$ttot = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_rubberid, b.m_item, c.m_nama as co_namabarang
										from 	t_titipan2 a, t_stockdata b, msbarang c 
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												a.m_kodebarang = c.m_kode " ;
							//echo $tsql2;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
								$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
								$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
								
								$tqty = $tqty + $row2['m_qty'] ;
								$ttot = $ttot + ( $row2['m_qty'] * $row2['m_harga'] ) ;
								?>
								<tr>
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="<?php echo $row2['m_kodebarang']; ?>" />
                                    	<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" onChange="oc_cekplu('<?php echo $i; ?>')" readonly/>
									</td>
                                    <td> <input class="input-small" type="text" id="m_rubberid<?php echo $i; ?>" name="m_rubberid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>"onChange="oc_cekplu('<?php echo $i; ?>')" readonly/></td>
									<td><?php echo $row2['co_namabarang']; ?></td>
									<td><?php echo $rowitem['m_nama']; ?></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly /></div></td>
									<td><div align="center"><input class="input-small" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="<?php echo number_format($row2['m_harga'], 0, '.', ','); ?>" style="text-align:center" readonly /></div></td>
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
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="" />
										<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" onChange="oc_cekplu('<?php echo $i; ?>')" />
									</td>
                                    <td><input class="input-small" type="text" id="m_rubberid<?php echo $i; ?>" name="m_rubberid<?php echo $i; ?>" value="" onChange="oc_cekplu('<?php echo $i; ?>')" /></td>
									<td><input class="input-small" type="text" id="m_group<?php echo $i; ?>" name="m_group<?php echo $i; ?>" value="" readonly/></td>
									<td><input class="input-small" type="text" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" readonly/></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="1" style="text-align:center" readonly /></div></td>
									<td><div align="center"><input class="input-small" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="0" style="text-align:center" readonly /></div></td>
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
                        <th><div id="sp-totharga" align="center"><?php echo number_format($tharga, 0, '.', ','); ?></div></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="15">
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
            <input type="text" id="cek_noplu" name="cek_noplu" value="1" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_karet" name="cek_karet" value="0" />
        </span>
    </div>         
    
    <div id="dialog-listcust">
        <span id="datacust">
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
			
		});
		
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("pos.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		
		
		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datacust").html(respon);
				};
			$.get('pos-cekcustomer.php',data,fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}

		function selectcust(vkode,vnama,valamat,vkota,vtelepon,vtelepon2)
		{
			document.getElementById('m_kodecust').value = vkode ;
			document.getElementById('m_nama').value = vnama ;
			document.getElementById('m_alamat').value = valamat ;
			document.getElementById('m_kota').value = vkota ;
			document.getElementById('m_telepon1').value = vtelepon ;
			document.getElementById('m_telepon2').value = vtelepon2 ;

			$( "#dialog-listcust" ).dialog( "close" );
		}

		function selectplu(vkode)
		{
			$( "#dialog-listdoc" ).dialog( "close" );
		}

		
		function listitem(rowke)
		{
			var data={rk:rowke};

			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('pos-cekitem.php',data,fungsi);
			
			$( "#dialog-listitem" ).dialog( "open" );
		}

		function selectitem(rowke,kodeitem,namaitem)
		{
			document.getElementById('m_item'+rowke).value = kodeitem ;
			document.getElementById('m_nmitem'+rowke).value = namaitem ;
			$( "#dialog-listitem" ).dialog( "close" );
		}

		function oc_cekplu(rowke)
		{
			var data={kdcab:$('#m_cabang').val(), noplu:$('#m_productid'+rowke).val(), rubberid:$('#m_rubberid'+rowke).val()};
			
			var fungsi=function(respon){
					$("#dataplu").html(respon);
					// cek dulu double ngk !!!
					var cekdouble = 'T';
					var newplu = $('#cek_noplu').val();
					
					var tbl = document.getElementById('table_data');
					var lastRow = tbl.rows.length;
					var jumrow = lastRow - 2;
					
					
					if (newplu != '' )
					{
						for(var i=1; i <= jumrow; i++) 
						{
							var cekplu = $('#m_productid'+i).val();
							if ((newplu == cekplu) && (i != rowke ))
							{
								cekdouble = 'Y';
							}
						}
						if (cekdouble == 'T' )
						{
							var vharga =  Number($('#cek_harga').val().replace(/,/g,""));
							document.getElementById('m_kodebarang'+rowke).value = $('#cek_kodebarang').val();
							document.getElementById('m_rubberid'+rowke).value = $('#cek_rubberid').val();
							document.getElementById('m_productid'+rowke).value = newplu;
							document.getElementById('m_group'+rowke).value = $('#cek_group').val();
							document.getElementById('m_item'+rowke).value = $('#cek_item').val();
							document.getElementById('m_harga'+rowke).value = formatangka(vharga.toFixed().toString()) ;
							
						}
						else
						{
							document.getElementById('m_productid'+rowke).value = '';						
							document.getElementById('m_group'+rowke).value = '';
							document.getElementById('m_item'+rowke).value = '';
							document.getElementById('m_harga'+rowke).value = '0';
							alert('Dobel Input Plu yang Sama!!!');
						}
					}
					
					
					else
					{
						document.getElementById('m_productid'+rowke).value = newplu;						
						document.getElementById('m_group'+rowke).value = '';
						document.getElementById('m_item'+rowke).value = '';
						document.getElementById('m_harga'+rowke).value = '0';
						alert('PLU tersebut Tidak terdaftar !!!');
					}
			
					recalc() ;
					
				};
			$.get('pos-cekplu.php',data,fungsi);
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			document.getElementById('jumrow').value = jumrow;
			
			return true ;
			
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			var kdcab = $('#m_cabang').val() ;
			
			var tqty = 0 ;
			var tharga = 0 ;
			
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var karet = document.getElementById('m_rubberid' + i).value ;
				var kdbrg = document.getElementById('m_kodebarang' + i).value ;
				var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				var harga = Number(document.getElementById('m_harga' + i).value.replace(/,/g,""));
				
				


			  
				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed(0).toString()) ;
				document.getElementById('m_harga' + i).value = formatangka(harga.toFixed(0).toString()) ;
				
				
				tqty = tqty + qty;
				tharga = tharga + harga;
				
			  $("#sp-totqty").html(formatangka((tqty).toFixed(0).toString()));
			  $("#sp-totharga").html(formatangka((tharga).toFixed(0).toString()));
				
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
		  cellno.innerHTML='<td><input type="hidden" id="m_kodebarang'+iteration+'" name="m_kodebarang'+iteration+'" value="" /><input class="input-small" type="text" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" onChange="oc_cekplu('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_rubberid'+iteration+'" name="m_rubberid'+iteration+'" value=""  onChange="oc_cekplu('+iteration+')"	/></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_group'+iteration+'" name="m_group'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="1" style="text-align:center" readonly /></div></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="0" style="text-align:right" readonly /></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_productid'+iteration).focus();
		}
	</script>

    </body>
</html>