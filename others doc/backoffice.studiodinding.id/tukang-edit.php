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
        <title>Kirim Bahan Ke Tukang</title>
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
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam
					 from t_tukang a, mstukang b where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' and a.m_tukang = b.m_kode" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$tukang = $row['m_kode'] ;
			$kota = $row['m_kota'] ;
			$status = $row['m_status'] ;
		}
		$lokasi = $kdcab.'-0' ;
		
    ?>
	<form class="form-horizontal" method="post" action="tukang-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 100%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Kirim Bahan ke Tukang('.$kdcab.' )' ; ?></h4></th>
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
                        <td>Nama Tukang</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_tukang" name="m_tukang" value="<?php echo $tukang; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupplier()"></i></span>
                            </div>
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
                        <th>Nama Bahan</th>
                        <th>Berat Kirim</th>
                        <th>Keterangan</th>
                        <th>Berat Terima</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tberat_kirim = 0 ;
						$tberat_terima = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_nama as m_namabahan
										from 	t_tukang2 a, msmaster b
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebahan = b.m_kode and 
												b.m_type = 'MATERIAL'  " ;
							//echo $tsql2;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								
								$tberat_kirim = $tberat_kirim + $row2['m_berat_kirim'] ;
								$tberat_terima = $tberat_terima + $row2['m_berat_terima'] ;
								?>
								<tr>
									<td><input type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>" />
										<input type="hidden" id="m_kodebahan<?php echo $i; ?>" name="m_kodebahan<?php echo $i; ?>" value="<?php echo $row2['m_kodebahan']; ?>" />
                                    <td> <input class="input-large" type="text" id="m_namabahan<?php echo $i; ?>" name="m_namabahan<?php echo $i; ?>" value="<?php echo $row2['m_namabahan']; ?>" onClick="listitem('<?php echo $i ; ?>')" style="cursor:pointer" readonly/></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_berat_kirim<?php echo $i; ?>" name="m_berat_kirim<?php echo $i; ?>" value="<?php echo number_format($row2['m_berat_kirim'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></div></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="<?php echo $row2['m_keterangan']; ?>" style="text-align:left"/></td>
									<td><input class="input-mini" type="text" id="m_berat_terima<?php echo $i; ?>" name="m_berat_terima<?php echo $i; ?>" value="<?php echo number_format($row2['m_berat_terima'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc('<?php echo $i ; ?>')" /></td>
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
									<input type="hidden" id="m_kodebahan<?php echo $i; ?>" name="m_kodebahan<?php echo $i; ?>" value="" />
									<input type="text" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $i; ?>" />
									<input class="input-large" type="text" id="m_namabahan<?php echo $i; ?>" name="m_namabahan<?php echo $i; ?>" value="" readonly onClick="listitem('<?php echo $i ; ?>')" style="cursor:pointer"/>
									</td>
									<td><input class="input-medium" type="text" id="m_berat_kirim<?php echo $i; ?>" name="m_berat_kirim<?php echo $i; ?>" value="0" style="text-align:center" onChange="recalc('<?php echo $i ; ?>')" /></td>
									<td><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="" style="text-align:left" /></td>
									<td><input class="input-medium" type="text" id="m_berat_terima<?php echo $i; ?>" name="m_berat_terima<?php echo $i; ?>" value="0" style="text-align:center"  readonly/></td>
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
                        <th colspan="1"></th>
                        <th><div id="sp-totberatkirim" align="center"><?php echo number_format($tberat_kirim, 0, '.', ','); ?></div></th>
                        <th colspan="1"></th>
                        <th><div id="sp-totberatterima" align="center"><?php echo number_format($tberat_terima, 0, '.', ','); ?></div></th>
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
    
    <div id="dialog-dataitem">
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
			$( "#dialog-dataitem" ).dialog({
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
			$.get('tukang-cektukang.php',data,fungsi);
			
			$( "#dialog-listsupplier" ).dialog( "open" );
		}

		
		function selectsupplier(vkode,vnama)
		{
			document.getElementById('m_tukang').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listsupplier" ).dialog( "close" );
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
			$.get('tukang-cekbahan.php',data,fungsi);
			
			$( "#dialog-dataitem" ).dialog( "open" );
		}

		function selectitem(rowke,kodeitem,namaitem)
		{
			
			document.getElementById('m_kodebahan'+rowke).value = kodeitem ;
			
			document.getElementById('m_namabahan'+rowke).value = namaitem ;
			
			
			$( "#dialog-dataitem" ).dialog( "close" );
		}

		
		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			

			
			document.getElementById('jumrow').value = jumrow;
			
			return true;
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tberatkirim = 0 ;
			var tberatterima = 0 ;
			
			
			
			for(var i=1; i <= jumrow; i++) 
			{	
				
		  	    var beratkirim = Number(document.getElementById('m_berat_kirim'+i).value.replace(/,/g,""));
		  	    var beratterima = Number(document.getElementById('m_berat_terima'+i).value.replace(/,/g,""));
			  
				document.getElementById('m_berat_kirim' + i).value = formatangka(beratkirim.toFixed(2).toString()) ;
				document.getElementById('m_berat_terima' + i).value = formatangka(beratterima.toFixed(2).toString()) ;
				
				tberatkirim = tberatkirim + beratkirim;
				tberatterima = tberatterima + beratterima;
				
			  $("#sp-totberatkirim").html(formatangka((tberatkirim).toFixed(2).toString()));
			  $("#sp-totberatterima").html(formatangka((tberatterima).toFixed(2).toString()));
				
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
		  cellno.innerHTML='<td><input type="text" id="m_no'+iteration+'" name="m_no'+iteration+'" value="'+iteration+'" /><input type="hidden" id="m_kodebahan'+iteration+'" name="m_kodebahan'+iteration+'" value="" /><input class="input-large" type="text" id="m_namabahan'+iteration+'" name="m_namabahan'+iteration+'" value="" onclick="listitem('+iteration+')"readonly style = "cursor:pointer" /></td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_berat_kirim'+iteration+'" name="m_berat_kirim'+iteration+'" value="0" style="text-align:center" onChange="recalc('+iteration+')" /></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value="" style="text-align:left"  /></td>';
		  
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_berat_terima'+iteration+'" name="m_berat_terima'+iteration+'" value="0" style="text-align:center"  readonly/></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		}
	</script>

    </body>
</html>