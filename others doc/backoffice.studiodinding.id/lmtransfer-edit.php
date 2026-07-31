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
        <title>LM.Transfer</title>
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
			$nama = $_SESSION['nama'] ;
			$lokasi = $kdcab.'-0' ;
			$lokasi2 = substr($kdcab,0,1).'0-0' ;
			$ket = '' ;
			$status = 'A';
			$kurir = '' ;
			$groupbrg = 'M0000001' ;

		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_transfer a where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' " ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$nama = $row['m_nama'] ;
			$lokasi = $row['m_lokasi'] ;
			$lokasi2 = $row['m_lokasi2'] ;
			$ket = $row['m_keterangan'] ;
			$status = $row['m_status'] ;
			$kurir = $row['m_kurir'] ;
			$groupbrg = $row['m_kodebarang'] ;
		}
		
    ?>
	<form class="form-horizontal" method="post" action="lmtransfer-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Inv.Transfer ( '.$kdcab.' '.$nomor.' )' ; ?></h4></th>
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
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="75">Tanggal</td>
                        <td width="150">
                            <div id="datetimepicker1" class="input-append date">
                            	<input class="input-medium" data-format="dd/MM/yyyy" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl; ?>" <?php if($nomor != ''){ ?> readonly <?php  } ?> />
                                <?php
								if (($nomor == ''))
								{
									?>
                                    <span class="add-on">
                                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                                    </span>
                                    <?php
								}
								?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required /></td>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td><input type="hidden" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" />
                            <select name="s_lokasi" id="s_lokasi" class="input-large" onChange="oc_lokasi()" <?php if($nomor != ''){ ?> disabled <?php  } ?>>
                                <?php
                                $tsqllok = "select m_kode, m_nama from msmaster where m_type = 'LOKASI' and left(m_kode,2) = '".$kdcab."' order by m_nama asc" ;
                                $stmtlok= sqlsrv_query( $con_dbnew, $tsqllok);
                                while( $rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowlok['m_kode']; ?>" <?php if ($rowlok['m_kode'] == $lokasi){ ?> selected="selected" <?php }   ?> ><?php echo $rowlok['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>To</td>
                        <td><input type="hidden" id="m_lokasi2" name="m_lokasi2" value="<?php echo $lokasi2; ?>" />
                            <select name="m_lokasi2" id="m_lokasi2" class="input-large" onChange="oc_lokasi2()" <?php if($nomor != ''){ ?> disabled <?php  } ?>>
                                <?php
								$tsqllok2 = "select m_kode, m_nama from msmaster where m_type = 'LOKASI' and m_kode <> '".$lokasi."' order by m_nama asc" ;
                                $stmtlok2 = sqlsrv_query( $con_dbnew, $tsqllok2);
                                while( $rowlok2 = sqlsrv_fetch_array( $stmtlok2, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowlok2['m_kode']; ?>" <?php if ($rowlok2['m_kode'] == $lokasi2){ ?> selected="selected" <?php }   ?> ><?php echo $rowlok2['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Kurir</td>
                        <td><input class="input-xlarge" type="text" id="m_kurir" name="m_kurir" value="<?php echo $kurir; ?>" required /></td>
                        <td>Group Product</td>
                        <td><input type="hidden" id="m_kodebarang" name="m_kodebarang" value="<?php echo $groupbrg; ?>" />
                            <select name="x_kodebarang" id="x_kodebarang" class="input-large" disabled >
                                <?php
								$tsqlbrg = "select m_kode, m_nama from msbarang order by m_nama asc" ;
                                $stmtbrg = sqlsrv_query( $con_dbnew, $tsqlbrg);
                                while( $rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowbrg['m_kode']; ?>" <?php if ($rowbrg['m_kode'] == $groupbrg){ ?> selected="selected" <?php }   ?> ><?php echo $rowbrg['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Item</th>
                        <th>Product ID</th>
                        <th><div align="center">Qty</div></th>
                        <th>Berat/pcs</th>
                        <th>Tot.Berat(gr)</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
                        $tharga = 0 ;
                        $tberat = 0 ;

						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_harga, b.m_item, c.m_nama as co_namabarang, b.m_status, d.m_nama as co_namaitem, d.m_kode2
										from 	t_transfer2 a, t_stockdata b, msbarang c, msmaster d
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												a.m_kodebarang = c.m_kode and 
												d.m_type = 'ITEM' and 
												b.m_item = d.m_kode " ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$dumb = explode('-',$row2['m_kode2']);
								$tqty = $tqty + $row2['m_qty'] ;
								$tberat = $tberat + $dumb[1] ;
								$tharga = $tharga + $row2['m_harga'] ;
								?>
								<tr>
									<td><?php echo number_format($i, 0, '.', ','); ?></td>
									<td>
                                    	<input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="<?php echo $row2['co_namaitem']; ?>" readonly />
                                    	<input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="<?php echo $row2['m_item']; ?>" />
                                    </td>
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="M0000001" />
										<input class="input-medium" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" readonly />
									</td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo $row2['m_qty']; ?>" style="text-align:center" readonly /></div></td>
									<td><input class="input-mini" type="text" id="m_berat<?php echo $i; ?>" name="m_berat<?php echo $i; ?>" value="<?php echo $dumb[1]; ?>" style="text-align:center" readonly /></td>
									<td><input class="input-mini" type="text" id="m_tberat<?php echo $i; ?>" name="m_tberat<?php echo $i; ?>" value="<?php echo $row2['m_qty']*$dumb[1]; ?>" style="text-align:center" readonly /></td>
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
                                    <input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="" readonly />
                                    <input type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" />
                                    <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlm(<?php echo $i; ?>)"></i></span>
                                </td>
                                <td>
                                    <input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="M0000001" />
                                    <input class="input-medium" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" readonly />
                                    <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="oc_ceklm(<?php echo $i; ?>)"></i></span>
                                </td>
                                <td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc()"/></div></td>
                                <td><input class="input-mini" type="text" id="m_berat<?php echo $i; ?>" name="m_berat<?php echo $i; ?>" value="0" style="text-align:center" /></td>
                                <td><input class="input-mini" type="text" id="m_tberat<?php echo $i; ?>" name="m_tberat<?php echo $i; ?>" value="0" style="text-align:center" readonly /></td>
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

    <div id="dialog-listlm">
        <span id="datalm">
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
				pickTime: false });
			
			$( "#dialog-listlm" ).dialog({
				autoOpen: false,
				height:600,
				width:400,
				modal: true,
				buttons: {
							"Close": function() {
							$( this ).dialog( "close" );}
						} });
		});

		function cancel_data(vparam,kdstore,periode)
		{
			window.open("lmtransfer.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function oc_lokasi()
		{
			document.getElementById('m_lokasi').value = $('#s_lokasi').val();
		}

		function oc_lokasi2()
		{
			document.getElementById('m_lokasi2').value = $('#s_lokasi2').val();
		}

		function listlm(rowke)
		{
			var data={rk:rowke};

			var fungsi=function(respon){
					$("#datalm").html(respon);
				};
			$.get('pos-ceklm.php',data,fungsi);
			
			$( "#dialog-listlm" ).dialog( "open" );
		}

		function selectlm(rowke,kodelm,namalm,berat,hbeli,hjual)
		{
			var vharga = Number(hjual.replace(/,/g,""));
			document.getElementById('m_item'+rowke).value = kodelm ;
			document.getElementById('m_nmitem'+rowke).value = namalm ;
			document.getElementById('m_berat'+rowke).value = berat ;
			
			if (berat < 10)
			{
				document.getElementById('m_productid'+rowke).value = kodelm ;
				document.getElementById('m_productid'+rowke).readOnly = true;
				document.getElementById('m_qty'+rowke).readOnly = false ;
			}
			else
			{
				document.getElementById('m_productid'+rowke).value = '' ;
				document.getElementById('m_qty'+rowke).value = '1';
				document.getElementById('m_productid'+rowke).readOnly = false;
				document.getElementById('m_qty'+rowke).readOnly = true;
			}

			$( "#dialog-listlm" ).dialog( "close" );
			recalc();
		}

		function oc_ceklm(rowke)
		{
			var data={rk:rowke,kdcab:$('#m_cabang').val(), kdlok:$('#m_lokasi').val(), vitem:$('#m_item'+rowke).val()};

			var fungsi=function(respon){
					$("#datalm").html(respon);
				};
			$.get('lm-cekplu.php',data,fungsi);
			
			$( "#dialog-listlm" ).dialog( "open" );
		}

		function selectplu(rowke,kodeplu)
		{
			document.getElementById('m_productid'+rowke).value = kodeplu ;
			$( "#dialog-listlm" ).dialog( "close" );
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
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_nmitem'+iteration+'" name="m_nmitem'+iteration+'" value="" readonly /><input type="hidden" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" /><span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listlm('+iteration+')"></i></span></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input type="hidden" id="m_kodebarang'+iteration+'" name="m_kodebarang'+iteration+'" value="M0000001" /><input class="input-medium" type="text" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" readonly /><span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="oc_ceklm('+iteration+')"></i></span></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="0" style="text-align:center"  onChange="recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_berat'+iteration+'" name="m_berat'+iteration+'" value="0" style="text-align:center" /></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_tberat'+iteration+'" name="m_tberat'+iteration+'" value="0" style="text-align:center" /></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';

		  document.getElementById('m_productid'+iteration).focus();
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 3;
			var tqty = 0 ;
			var tberat = 0 ;
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var kdbrg = document.getElementById('m_kodebarang' + i).value ;
				var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				var berat = Number(document.getElementById('m_berat' + i).value.replace(/,/g,""));

				tqty = tqty + qty ;
				tberat = tberat + berat  ;
				
				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed().toString()) ;
				document.getElementById('m_berat' + i).value = formatangka(berat.toFixed(2).toString()) ;
				document.getElementById('m_tberat' + i).value = formatangka((qty*berat).toFixed().toString()) ;
			}
			return true ;
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