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
        <title>Inv.Retur</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		
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
		$lokasi = $kdcab.'-0' ;
    ?>
	<form class="form-horizontal" method="post" action="retur-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Inv.Retur ( '.$kdcab.' '.$nomor.' )' ; ?></h4></th>
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
                        <td width="75">Tanggal</td>
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
                        <td colspan="3"><input class="input-large" type="text" id="m_dokumen" name="m_dokumen" value="<?php echo $doc; ?>" onChange="cekdoc()" required /></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product ID</th>
                        <th><div align="center">Qty</div></th>
                        <th>Harga</th>
                        <th>Group</th>
                        <th>Item</th>
                        <th>Net</th>
                        <th>Butir</th>
                        <th>Carat</th>
                        <th>Status</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
                        $tharga = 0 ;
                        $tnet = 0 ;
                        $tbutir = 0 ;
                        $tcarat = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_harga, b.m_item, b.m_netweight, b.m_butir, b.m_carat, c.m_nama as co_namabarang, b.m_status, d.m_nama as ststock
										from 	t_retur2 a, t_stockdata b, msbarang c, msmaster d
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												a.m_kodebarang = c.m_kode and 
												d.m_type = 'STINV' and 
												b.m_status = d.m_kode " ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row2['m_item']."'";
								$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
								$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
								
								$tqty = $tqty + $row2['m_qty'] ;
								$tharga = $tharga + $row2['m_harga'] ;
								$tnet = $tnet + $row2['m_netweight'] ;
								$tbutir = $tbutir + $row2['m_butir'] ;
								$tcarat = $tcarat + $row2['m_carat'] ;
								?>
								<tr>
									<td><?php echo number_format($i, 0, '.', ','); ?></td>
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="<?php echo $row2['m_kodebarang']; ?>" />
                                    	<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" onChange="oc_cekplu('<?php echo $i; ?>')" readonly/>
									</td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly /></div></td>
									<td><?php echo number_format($row2['m_harga'], 0, '.', ','); ?></td>
									<td><?php echo $row2['co_namabarang']; ?></td>
									<td><?php echo $rowitem['m_nama']; ?></td>
									<td><?php echo number_format($row2['m_netweight'], 2, '.', ','); ?></td>
									<td><?php echo number_format($row2['m_butir'], 0, '.', ','); ?></td>
									<td><?php echo number_format($row2['m_carat'], 3, '.', ','); ?></td>
									<td><?php echo $row2['ststock']; ?></td>
									<td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
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
                            	<td><?php echo $i; ?></td>
								<td>
                                	<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="" />
                                    <input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" onChange="oc_cekplu('<?php echo $i; ?>')" />
								</td>
								<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="1" style="text-align:center" readonly /></div></td>
								<td><input class="input-small" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="0" readonly/></td>
								<td><input class="input-medium" type="text" id="m_group<?php echo $i; ?>" name="m_group<?php echo $i; ?>" value="" readonly/></td>
								<td><input class="input-medium" type="text" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" readonly/></td>
								<td><input class="input-mini" type="text" id="m_net<?php echo $i; ?>" name="m_net<?php echo $i; ?>" value="0" readonly/></td>
								<td><input class="input-mini" type="text" id="m_butir<?php echo $i; ?>" name="m_butir<?php echo $i; ?>" value="0" readonly/></td>
								<td><input class="input-mini" type="text" id="m_carat<?php echo $i; ?>" name="m_carat<?php echo $i; ?>" value="0" readonly/></td>
								<td><input class="input-medium" type="text" id="m_status<?php echo $i; ?>" name="m_status<?php echo $i; ?>" value="" readonly/></td>
								<td>
									<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
									<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
								</td>
							</tr>
							<?php
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="11">
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
    
    <div id="tempdata" class="hide">
        <span id="dataplu">
            <input type="text" id="cek_noplu" name="cek_noplu" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_net" name="cek_net" value="" />
            <input type="text" id="cek_butir" name="cek_butir" value="" />
            <input type="text" id="cek_carat" name="cek_carat" value="" />
            <input type="text" id="cek_status" name="cek_status" value="" />
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
			window.open("retur.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datacust").html(respon);
				};
			$.get('retur-ceksupplier.php',data,fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}

		function selectcust(vkode,vnama)
		{
			document.getElementById('m_kodesupl').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listcust" ).dialog( "close" );
		}

		function oc_cekplu(rowke)
		{
			
			var data={kdcab:$('#m_cabang').val(), kdlok:$('#m_lokasi').val(), noplu:$('#m_productid'+rowke).val()};
			var fungsi=function(respon){
					$("#dataplu").html(respon);
					
					var vharga =  Number($('#cek_harga').val().replace(/,/g,""));
					var vnet =  Number($('#cek_net').val().replace(/,/g,""));
					var vbutir = Number($('#cek_butir').val().replace(/,/g,""));
					var vcarat = Number($('#cek_carat').val().replace(/,/g,""));
					var vstat = $('#cek_status').val();
					
					document.getElementById('m_kodebarang'+rowke).value = $('#cek_kodebarang').val();
					document.getElementById('m_productid'+rowke).value = $('#cek_noplu').val();
					document.getElementById('m_group'+rowke).value = $('#cek_group').val();
					document.getElementById('m_item'+rowke).value = $('#cek_item').val();
					document.getElementById('m_harga'+rowke).value = formatangka(vharga.toFixed(0).toString()) ;
					document.getElementById('m_net'+rowke).value = formatangka(vnet.toFixed(2).toString()) ;
					document.getElementById('m_butir'+rowke).value = formatangka(vbutir.toFixed().toString()) ;
					document.getElementById('m_carat'+rowke).value = formatangka(vcarat.toFixed(3).toString()) ;
					document.getElementById('m_status'+rowke).value = $('#cek_status').val();
				};
			$.get('retur-cekplu.php',data,fungsi);
		}

		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 1;
		  var row = tbl.insertRow(lastRow - 1);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td>'+iteration+'</td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input type="hidden" id="m_kodebarang'+iteration+'" name="m_kodebarang'+iteration+'" value="" /><input class="input-small" type="text" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" onChange="oc_cekplu('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="1" style="text-align:center" readonly /></div>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="0" readonly/></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_group'+iteration+'" name="m_group'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_net'+iteration+'" name="m_net'+iteration+'" value="0" readonly/></td>';
		  
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_butir'+iteration+'" name="m_butir'+iteration+'" value="0" readonly/></td>';
		  
		  var cellno = row.insertCell(8);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_carat'+iteration+'" name="m_carat'+iteration+'" value="0" readonly/></td>';
		  
		  var cellno = row.insertCell(9);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_status'+iteration+'" name="m_status'+iteration+'" value="" readonly/></td>';
		  
		  var cellno = row.insertCell(10);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_productid'+iteration).focus();
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			document.getElementById('jumrow').value = jumrow;
			
			return true ;
		}


	</script>

    </body>
</html>