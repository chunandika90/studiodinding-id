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
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam from t_ttb a where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."'" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$kdsupl = $row['m_kodesupl'] ;
			$ket = $row['m_keterangan'] ;
			$doc = $row['m_dokumen'] ;
			$status = $row['m_status'] ;
		}
		
		$tsqlrate = " select top 1 * from msrate where m_type = 'USD' and m_tanggal <= getdate() 
					  order by m_tanggal desc ";
		$stmtrate = sqlsrv_query( $con_dbnew, $tsqlrate);
		$rowrate = sqlsrv_fetch_array( $stmtrate, SQLSRV_FETCH_ASSOC) ;
		
		$rate = $rowrate['m_beli'];
			
    ?>
	<form class="form-horizontal" method="post" action="ttb-simpan.php"  onsubmit="return validasi()">
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
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="50">Tanggal</td>
                        <td width="350"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kode" name="m_kode" value="<?php echo $kdcust; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupplier()"></i></span>
                            </div>
                        </td>
                    </tr>
                     <tr>
                        <td>Lokasi</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $kdcust; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_namalokasi" name="m_namalokasi" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlokasi()"></i></span>
                            </div>
                        </td>
                    </tr>
                   
                   
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                     <tr>
                        <td>NO SJ SUPPLIER</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_dosupplier" name="m_dosupplier" value="<?php echo $sjsupplier; ?>" /></td>
                    </tr>
                    <tr>
                    	<td>Jenis Penerimaan</td>
                        <td>
                            <select name="m_type" id="m_type" class="input-large" >
                               <option value="K" selected>Konsinyasi</option>
                               <option value="I">Internal</option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td>Group Product</td>
                            <td>
                                <select name="m_kodebarang" id="m_kodebarang" class="input-large" >
                                    <?php
                                    $tsqlbrg = "select m_kode, m_nama from msbarang WHERE m_kode = 'DJ' order by m_kode asc" ;
                                    $stmtbrg = sqlsrv_query( $con_dbnew, $tsqlbrg);
                                    while( $rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC))
                                    {
                                        ?>
                                        <option value="<?php echo $rowbrg['m_kode']; ?>"><?php echo $rowbrg['m_nama']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                
                        </td>
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
                        <th>Kode Barang Supplier</th>
                        <th><div align="center">Qty</div></th>
                        <th>Berat</th>
                        <th>Harga Supplier</th>
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
										from 	t_pos2 a, t_stockdata b, msmaster c 
										where 	a.m_cabang = '".$kdstore."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												c.m_type = 'ITEM' and 
												b.m_item = c.m_kode " ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$dumb = explode('-',$row2['m_kode2']) ;
								$tqty = $tqty + $row2['m_qty'] ;
								$ttot = $ttot + ( $row2['m_qty'] * $row2['m_harga'] ) ;
								$totberat = $totberat + $dumb[1] ;

								$total = ( $row2['m_qty'] * $row2['m_harga'] ) ;
								?>
								<tr>
									<td><input class="input-medium" type="text" id="m_rubberid<?php echo $i; ?>" name="m_rubberid<?php echo $i; ?>" value="" style="text-align:left" readonly /><input  type="hidden" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" style="text-align:left" readonly /></td>
									<td>
                                    	<input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="<?php echo $row2['co_namaitem']; ?>" readonly />
                                    	<input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="<?php echo $row2['m_item']; ?>" />
                                    </td>
									<td><input class="input-mini" type="text" id="m_kodesupplier<?php echo $i; ?>" name="m_kodesupplier<?php echo $i; ?>" value="" style="text-align:left" readonly /></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo $row2['m_qty']; ?>" style="text-align:center" readonly /></div></td>
									<td><input class="input-mini" type="text" id="m_grossweight<?php echo $i; ?>" name="m_grossweight<?php echo $i; ?>" value="<?php echo $row2['m_grossweight']; ?>" style="text-align:center" /></td>
									<td><input class="input-small" type="text" id="m_hargar<?php echo $i; ?>" name="m_hargar<?php echo $i; ?>" value="<?php echo $row2['m_hargasup']; ?>" style="text-align:right" readonly /></td>
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
							while( $addrow <= 3 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr>
									<td><input class="input-medium" type="text" id="m_rubberid<?php echo $i; ?>" name="m_rubberid<?php echo $i; ?>" value="" style="text-align:left" /> <input type="hidden" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" /></td>
									<td>
                                    	<input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="" readonly onClick="listitem(<?php echo $i; ?>)" style="cursor:pointer"/>
                                    	<input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" />
                                		<span class="add-on">
									</td>
									<td><input class="input-medium" type="text" id="m_kodesupplier<?php echo $i; ?>" name="m_kodesupplier<?php echo $i; ?>" value="" style="text-align:left" /></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="1" style="text-align:center" onChange="recalc()" readonly/></div></td>
									<td><input class="input-mini" type="text" id="m_grossweight<?php echo $i; ?>" name="m_grossweight<?php echo $i; ?>" value="0" style="text-align:right"   onChange="recalc()"/></td>
									<td><input class="input-small" type="text" id="m_hargar<?php echo $i; ?>" name="m_hargar<?php echo $i; ?>" value="0" style="text-align:right" onChange="recalc()"/></td>
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
                        <td colspan="3"></td>
                        <td><div align="center"><input class="input-mini" type="text" id="m_tqty" name="m_tqty" value="0" style="text-align:center" readonly/></div></td>
                        <td><input class="input-mini" type="text" id="m_tgross" name="m_tgross" value="0" style="text-align:right" readonly /></td>
                        <td><input class="input-small" type="text" id="m_thargar" name="m_thargar" value="0" style="text-align:right" readonly /></td>
                        <td></td>
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

    <div id="dialog-listitem">
        <span id="dataitem">
        </span>
    </div>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script><script type="text/javascript">
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

		function listitem(rowke)
		{
			var data={rk:rowke};

			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('ttb-cekitem.php',data,fungsi);
			
			$( "#dialog-listitem" ).dialog( "open" );
		}

		function selectitem(rowke,kodeitem,namaitem)
		{
			document.getElementById('m_item'+rowke).value = kodeitem ;
			document.getElementById('m_nmitem'+rowke).value = namaitem ;
			$( "#dialog-listitem" ).dialog( "close" );
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 3;
			var hasil = 'Y';
			
			if (document.getElementById('m_nama').value == '') 
			{
				alert('Nama Supplier belum di isi !!!');
				hasil = false ;
			}
			
			document.getElementById('jumrow').value = jumrow;
			for(var i=1; i <= jumrow; i++) 
			{	
				var vitem = document.getElementById('m_item' + i).value ;
				var vqty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				var vplu = document.getElementById('m_productid' + i).value ;
				
				if (( vqty > 0 ) && (vplu == '')) { hasil = 'T' ; }
			}
			if ( hasil == 'Y') 
			{ return true ; } 
			
		}

		function recalc()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 3;
			var tqty = 0 ;
			var tgross = 0 ;
			var thargar = 0 ;
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				var grossweight = Number(document.getElementById('m_grossweight' + i).value.replace(/,/g,""));
				var hargar = Number(document.getElementById('m_hargar' + i).value.replace(/,/g,""));
				
				tqty = tqty + qty ;
				tgross = tgross + grossweight ;
				thargar = thargar + hargar  ;
				
				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed().toString()) ;
				document.getElementById('m_grossweight' + i).value = formatangka(grossweight.toFixed(2).toString()) ;
				document.getElementById('m_hargar' + i).value = formatangka(hargar.toFixed().toString()) ;
			}
			document.getElementById('m_tqty').value = formatangka(tqty.toFixed().toString()) ;
			document.getElementById('m_tgross').value = formatangka(tgross.toFixed(2).toString()) ;
			document.getElementById('m_thargar').value = formatangka(thargar.toFixed().toString()) ;
			return true ;
		}

		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 2;
		  var row = tbl.insertRow(lastRow - 2);

		  
		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td><div align="left"><input class="input-medium" type="text" id="m_rubberid'+iteration+'" name="m_rubberid'+iteration+'" value="" style="text-align:left" /><input type="hidden" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" /></td>';
			
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_nmitem'+iteration+'" name="m_nmitem'+iteration+'" value="" readonly onClick="listitem('+iteration+')" style="cursor:pointer"/><input type="hidden" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" /><span class="add-on"></span></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><div align="left"><input class="input-medium" type="text" id="m_kodesupplier'+iteration+'" name="m_kodesupplier'+iteration+'" value="" style="text-align:LEFT" /></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="1" style="text-align:center" onChange="recalc()" readonly/></div></td>';
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="left"><input class="input-mini" type="text" id="m_grossweight'+iteration+'" name="m_grossweight'+iteration+'" value="0" style="text-align:right" onChange="recalc()"/></div></td>';
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><div align="left"><input class="input-small" type="text" id="m_hargar'+iteration+'" name="m_hargar'+iteration+'" value="0" style="text-align:right" onChange="recalc()" /></div></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';

		  
		  document.getElementById('m_productid'+iteration).focus();
		}
	</script>

    </body>
</html>