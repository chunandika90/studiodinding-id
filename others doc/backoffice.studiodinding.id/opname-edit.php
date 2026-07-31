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
	$periode = base64_decode($_GET['pr']);
	$soid = base64_decode($_GET['so']);
	$prm = base64_decode($_GET['prm']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Stock Opname</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
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
			$nama = $_SESSION['nama'] ;
			$ket = '' ;
			$status = 'A';
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(10),a.m_tanggal,108) as co_jam from t_opname a where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' " ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$ket = $row['m_keterangan'] ;
			$status = $row['m_status'] ;
		}
    ?>
	<form class="form-horizontal" method="post" action="opname-simpan.php"  onsubmit="return validasi()">
		<div class="container pull-left" >
            <div class="pull-left row-fluid" style="width: 50%; padding: 0 10px;">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th colspan="4"><h4><?php echo $kdcab.' '.$nomor ; ?></h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="100">Nomor</td>
                            <td width="150">
                                <input type="hidden" id="kdstore" name="kdstore" value="<?php echo $_GET['st']; ?>" />
                                <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                                <input type="hidden" id="soid" name="soid" value="<?php echo $_GET['so']; ?>" />
                                <input type="hidden" id="param" name="param" value="<?php echo $_GET['prm']; ?>" />
                                
                                <input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                                <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                                <input type="hidden" id="jumrow" name="jumrow" value="0" />
                                <input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                            </td>
                            <td width="75">Tanggal</td>
                            <td width="150">
                                <div id="datetimepicker1" class="input-append date">
                                    <input class="input-medium" data-format="dd/MM/yyyy" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required /></td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                        </tr>
                        <tr>
                            <td>SO.ID</td>
                            <td colspan="3"><input class="input-medium" type="text" id="m_soid" name="m_soid" value="<?php echo $soid; ?>" required readonly/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pull-right row-fluid" style="width: 30%; padding: 0 10px;">
				<span id="viewimage">
                    <img id="gambar" class="" name="gambar" src="" width="250" height="250">
				</span>
            </div>
        </div>
    	<div class="container pull-left row-fluid" style="width: auto; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="15">
                        <div>
                            <div class="pull-left" >
                                <input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data()" />
                            </div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                                <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $periode; ?>','<?php echo $soid; ?>')" />
                            </div>
                        </div>
                        </th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Product ID</th>
                        <th><div align="center">Qty</div></th>
                        <th><div align="center">No-Pic</div></th>
                        <th><div align="center">Beda-Pic</div></th>
                        <th><div align="center">Beda-Tag</div></th>
                        <th>Lokasi</th>
                        <th>Group</th>
                        <th>Item</th>
                        <th>Net</th>
                        <th>Butir</th>
                        <th>Carat</th>
                        <th>Harga</th>
                        <th>Ket.</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
                        $tnet = 0 ;
                        $tbutir = 0 ;
                        $tcarat = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*
										from 	t_opname2 a
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."'" ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$tsql3 = "	select 	b.m_item, b.m_netweight, b.m_butir, b.m_carat, b.m_harga, c.m_nama as co_namabarang
											from 	t_stockdata b, msbarang c 
											where 	b.m_kodebarang = '".$row2['m_kodebarang']."' and 
													b.m_productid = '".$row2['m_productid']."' and
													b.m_kodebarang = c.m_kode " ;
								$stmt3 = sqlsrv_query( $con_dbnew, $tsql3);
								$row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
								
								$i = $i + 1 ;
								$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$row3['m_item']."'";
								$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
								$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);
								
								$tqty = $tqty + $row2['m_qty'] ;
								$tnet = $tnet + $row3['m_netweight'] ;
								$tbutir = $tbutir + $row3['m_butir'] ;
								$tcarat = $tcarat + $row3['m_carat'] ;
								?>
								<tr>
									<td><?php echo $i ;?></td>
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="<?php echo $row2['m_kodebarang']; ?>" />
                                    	<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" readonly/>
									</td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly /></div></td>
									<td><div align="center"><input type="checkbox" id="m_nopic<?php echo $i; ?>" name="m_nopic<?php echo $i; ?>" <?php if($row2['m_nopic']=='Y'){ ?> checked <?php } ?>/></div></td>
									<td><div align="center"><input type="checkbox" id="m_bedapic<?php echo $i; ?>" name="m_bedapic<?php echo $i; ?>" <?php if($row2['m_bedapic']=='Y'){ ?> checked <?php } ?>/></div></td>
									<td><div align="center"><input type="checkbox" id="m_bedabandrol<?php echo $i; ?>" name="m_bedabandrol<?php echo $i; ?>" <?php if($row2['m_bedabandrol']=='Y'){ ?> checked <?php } ?>/></div></td>
									<td><input class="input-mini" type="text" id="m_lokasi<?php echo $i; ?>" name="m_lokasi<?php echo $i; ?>" value="<?php echo $row2['m_lokasi']; ?>" style="text-align:center" readonly /></td>
									<td><?php echo $row3['co_namabarang']; ?></td>
									<td><?php echo $rowitem['m_nama']; ?></td>
									<td><?php echo number_format($row3['m_netweight'], 2, '.', ','); ?></td>
									<td><?php echo number_format($row3['m_butir'], 0, '.', ','); ?></td>
									<td><?php echo number_format($row3['m_carat'], 3, '.', ','); ?></td>
									<td><div align="right"><?php echo number_format($row3['m_harga'], 0, '.', ','); ?></div></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="<?php echo $row2['m_keterangan']; ?>"/></td>
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
									<td><?php echo $i ;?></td>
                                    <td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="" />
										<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="" onChange="oc_cekplu('<?php echo $i; ?>')" onkeypress="return disableEnterKey(event,this.id,'<?php echo $i; ?>')" />
									</td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="1" style="text-align:center" readonly /></div></td>
									<td><div align="center"><input type="checkbox" id="m_nopic<?php echo $i; ?>" name="m_nopic<?php echo $i; ?>" /></div></td>
									<td><div align="center"><input type="checkbox" id="m_bedapic<?php echo $i; ?>" name="m_bedapic<?php echo $i; ?>" /></div></td>
									<td><div align="center"><input type="checkbox" id="m_bedabandrol<?php echo $i; ?>" name="m_bedabandrol<?php echo $i; ?>" /></div></td>
									<td><input class="input-mini" type="text" id="m_lokasi<?php echo $i; ?>" name="m_lokasi<?php echo $i; ?>" value="" style="text-align:center" readonly /></td>
									<td><input class="input-small" type="text" id="m_group<?php echo $i; ?>" name="m_group<?php echo $i; ?>" value="" readonly/></td>
									<td><input class="input-small" type="text" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="" readonly/></td>
									<td><input class="input-mini" type="text" id="m_net<?php echo $i; ?>" name="m_net<?php echo $i; ?>" value="0" readonly/></td>
									<td><input class="input-mini" type="text" id="m_butir<?php echo $i; ?>" name="m_butir<?php echo $i; ?>" value="0" readonly/></td>
									<td><input class="input-mini" type="text" id="m_carat<?php echo $i; ?>" name="m_carat<?php echo $i; ?>" value="0" readonly/></td>
									<td><input class="input-small" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="0" style="text-align:right" readonly/></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value=""/></td>
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
            </table>        
		</div>
    </form>

    <div id="tempdata">
        <span id="dataplu" class="hide">
			<input type="text" id="cek_kodebarang" name="cek_kodebarang" value="" />
            <input type="text" id="cek_noplu" name="cek_noplu" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_net" name="cek_net" value="" />
            <input type="text" id="cek_butir" name="cek_butir" value="" />
            <input type="text" id="cek_carat" name="cek_carat" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_lokasi" name="cek_lokasi" value="" />
            <input type="text" id="cek_karet" name="cek_karet" value="" />
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
		});
  	
		function cancel_data(vparam,kdstore,periode,soid)
		{
			window.open("opname.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&so='+base64_encode(soid)+'&prm='+base64_encode(vparam),'_self');
		}op

		function oc_cekplu(rowke)
		{
			var data={kdcab:$('#m_cabang').val(), so:$('#m_soid').val(), noplu:$('#m_productid'+rowke).val()};

			var fungsi=function(respon){
					$("#dataplu").html(respon);
					// Cek double dulu 
					var cekdouble = 'T';
					var newplu = $('#cek_noplu').val();
					var kdbrg = $('#cek_kodebarang').val();
					var tbl = document.getElementById('table_data');
					var lastRow = tbl.rows.length;
					var jumrow = lastRow - 2 ;
					if (newplu != '')
					{
						for(var i=1; i <= jumrow; i++) 
						{
							var cekplu = $('#m_productid'+i).val();
							if ((newplu == cekplu) && (i != rowke ))
							{
								cekdouble = 'Y';
							}
						}
						if ((cekdouble == 'T') && (kdbrg != 'DOUBLE'))
						{
							var vnet =  Number($('#cek_net').val().replace(/,/g,""));
							var vbutir = Number($('#cek_butir').val().replace(/,/g,""));
							var vcarat = Number($('#cek_carat').val().replace(/,/g,""));
							var vharga = Number($('#cek_harga').val().replace(/,/g,""));
							document.getElementById('m_kodebarang'+rowke).value = $('#cek_kodebarang').val();
							document.getElementById('m_productid'+rowke).value = $('#cek_noplu').val();
							document.getElementById('m_group'+rowke).value = $('#cek_group').val();
							document.getElementById('m_item'+rowke).value = $('#cek_item').val();
							document.getElementById('m_lokasi'+rowke).value = $('#cek_lokasi').val();
							document.getElementById('m_net'+rowke).value = formatangka(vnet.toFixed(2).toString()) ;
							document.getElementById('m_butir'+rowke).value = formatangka(vbutir.toFixed().toString()) ;
							document.getElementById('m_carat'+rowke).value = formatangka(vcarat.toFixed(3).toString()) ;
							document.getElementById('m_harga'+rowke).value = formatangka(vharga.toFixed().toString()) ;
							tampilgambar($('#cek_kodebarang').val(),$('#cek_karet').val());
							//add_data();
						}
						else
						{
							alert('NO.PLU DOUBLE , SUDAH DIINPUT !!! ');
							
							document.getElementById('m_kodebarang'+rowke).value = '';
							document.getElementById('m_productid'+rowke).value = '';
							document.getElementById('m_group'+rowke).value = '';
							document.getElementById('m_item'+rowke).value = '';
							document.getElementById('m_lokasi'+rowke).value = '';
							document.getElementById('m_net'+rowke).value = '0' ;
							document.getElementById('m_butir'+rowke).value = '0' ;
							document.getElementById('m_carat'+rowke).value = '0.000' ;
							document.getElementById('m_harga'+rowke).value = '0' ;
						}
					}
				};
			$.get('opname-cekplu.php',data,fungsi);
		}
		
		function tampilgambar(vkd, vkr)
		{
			var data={kd:vkd, kr:vkr};
			var fungsi=function(respon){
					$("#viewimage").html(respon);
				};
			$.get('opname-image.php',data,fungsi);
		}
		
		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 1;
		  var row = tbl.insertRow(2);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td>'+iteration+'</td>';

		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input type="hidden" id="m_kodebarang'+iteration+'" name="m_kodebarang'+iteration+'" value="" /><input class="input-small" type="text" id="m_productid'+iteration+'" name="m_productid'+iteration+'" value="" onChange="oc_cekplu('+iteration+')" onkeypress="return disableEnterKey(event,this.id)" /></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="1" style="text-align:center" readonly /></div></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input type="checkbox" id="m_nopic'+iteration+'" name="m_nopic'+iteration+'" /></div></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="center"><input type="checkbox" id="m_bedapic'+iteration+'" name="m_bedapic'+iteration+'" /></div></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><div align="center"><input type="checkbox" id="m_bedabandrol'+iteration+'" name="m_bedabandrol'+iteration+'" /></div></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_lokasi'+iteration+'" name="m_lokasi'+iteration+'" value="" style="text-align:center" readonly /></td>';
		
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_group'+iteration+'" name="m_group'+iteration+'" value="" readonly/></td>';

		  var cellno = row.insertCell(8);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_item'+iteration+'" name="m_item'+iteration+'" value="" readonly/></td>';

		  var cellno = row.insertCell(9);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_net'+iteration+'" name="m_net'+iteration+'" value="0" readonly/></td>';

		  var cellno = row.insertCell(10);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_butir'+iteration+'" name="m_butir'+iteration+'" value="0" readonly/></td>';

		  var cellno = row.insertCell(11);
		  cellno.innerHTML='<td><input class="input-mini" type="text" id="m_carat'+iteration+'" name="m_carat'+iteration+'" value="0" readonly/></td>';

		  var cellno = row.insertCell(12);
		  cellno.innerHTML='<td><input class="input-small" type="text" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="0" style="text-align:right" readonly/></td>';

		  var cellno = row.insertCell(13);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value=""/></td>';

		  var cellno = row.insertCell(14);
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

		function disableEnterKey(e,elid,rowke)
		{
			var key;
			if(window.event)
			key = window.event.keyCode; //IE
			else
			key = e.which; //firefox

			if(key == 13)
			{
				var pluid = document.getElementById(elid).value ;
				if ( pluid != '' )	{ add_data(); }

				return false;
			}
		}

	</script>

    </body>
</html>