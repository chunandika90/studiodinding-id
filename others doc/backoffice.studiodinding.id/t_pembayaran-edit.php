<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	//$nomor = base64_decode(strtr($_GET['nm'], '-_,', '+/='));
	$raw_nm = isset($_GET['nm']) ? $_GET['nm'] : '';
	$decode_nm = base64_decode(strtr($raw_nm, '-_,', '+/='), true); // strict mode: true

	if ($decode_nm === false) {
		$nomor = '';
	} else {
		$nomor = $decode_nm;
	}
	
	
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Invoice (Pembayaran)</title>
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
			// Data tidak ditemukan
			$tgl = date("d/m/Y") ;
			$jam = date("H:i:s") ;
			$namaproject = '' ;
			$kdproject = '' ;
			$lokasi = '' ;
			$namaclient = '' ;
			$supervisor = '' ;
			$nama_supervisor = '' ;
			$kdsupplier = '';
			$namasupplier = '';
			$alamatsupplier = '';
			$telepon = '';
			$picsupplier = '';
			$ket = '' ;
			$type = '' ;
			$jumlah_rp = 0 ;
			$ppn = 0 ;
			$jumlah_ppn = 0 ;
			$diskon = 0 ;
			$jumlah_diskon = 0 ;
			$total_rp = 0;
			$status = 'A' ;
		}
		else
		{
			$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl,DATE_FORMAT(a.m_tanggal, '%H:%i:%s') AS co_jam , 
					 b.supervisor_project as m_nama_supervisor, b.nama_project as nama_project, 
					 b.m_lokasi, b.m_alamat, b.nama_client m_namaclient, b.supervisor_project m_nama_supervisor, c.alamat m_alamat_supplier, 
					 c.contact_person  m_picsupplier,c.nomor_telepon, c.m_nama as m_nama_supplier,
					 ifnull(a.m_jumlah,0) m_jumlah, a.m_carabayar
					 from t_pembayaran a, master_project b, master_supplier c
					 where a.m_kode_project = b.m_kode and a.m_kode_supplier = c.m_kode and 	
					 a.m_nomor = '".$nomor."' 
					 " ;
			$stmt = $con_dbnew->query($tsql);
			$row = $stmt->fetch_assoc();
			if ($row) 
			{
				$tgl = $row['co_tgl'] ;
				$jam = $row['co_jam'] ;
				$namaproject = $row['m_nama_project'] ;
				$kdproject = $row['m_kode_project'] ;
				$lokasi = $row['m_lokasi'] ;
				$namaclient = $row['m_namaclient'] ;
				$supervisor = $row['m_nama_supervisor'] ;
				$kdsupplier = $row['m_kode_supplier'] ;
				$namasupplier = $row['m_nama_supplier'] ;
				$alamatsupplier = $row['m_alamat_supplier'] ;
				$telepon = $row['nomor_telepon'] ;
				$picsupplier = $row['m_picsupplier'] ;
				$m_carabayar = $row['m_carabayar'] ;
				$ket = $row['m_keterangan'] ;
				$type = $row['m_type'] ;
				$status = $row['m_status'] ;
				$m_jumlah = $row['m_jumlah'] ;
			} else {
				// Data tidak ditemukan
				$tgl = date("d/m/Y") ;
				$jam = date("H:i:s") ;
				$namaproject = '' ;
				$kdproject = '' ;
				$lokasi = '' ;
				$namaclient = '' ;
				$supervisor = '' ;
				$nama_supervisor = '' ;
				$kdsupplier = '';
				$namasupplier = '';
				$alamatsupplier = '';
				$telepon = '';
				$picsupplier = '';
				$ket = '' ;
				$type = '' ;
				$status = 'A' ;
				$jumlah_rp = 0 ;
				$ppn = 0 ;
				$jumlah_ppn = 0 ;
				$diskon = 0 ;
				$jumlah_diskon = 0 ;
				$total_rp = 0;
			}
		}
		
    ?>
	<form class="form-horizontal" method="post" action="t_pembayaran-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 100%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Invoice (Pembayaran)' ; ?></h4></th>
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
                        <td>Vendor / Supplier</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kode_supplier" name="m_kode_supplier" value="<?php echo $kdsupplier; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama_supplier" name="m_nama_supplier" value="<?php echo $namasupplier; ?>" required  onchange="listsupp()"/>
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupp()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td colspan="3">
				            <textarea class="input-xxlarge" name="m_alamat_supplier" id="m_alamat_supplier" cols="200" rows="2" readonly><?php echo $alamatsupplier; ?> </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><input class="input-medium" type="text" id="m_telepon" name="m_telepon" value="<?php echo $telepon; ?>" readonly/></td>
                        <td>Attention</td>
                        <td><input class="input-medium" type="text" id="m_picsupplier" name="m_picsupplier" value="<?php echo $picsupplier; ?>" readonly/></td>
                    </tr>
					<tr>
						<td>Tipe </br> Hitam / Putih </td>
						<td colspan="3">
							<select class="input-xlarge" id="m_type" name="m_type">
								<option value="" <?php echo ($type == '') ? 'selected' : ''; ?>>-- Pilih Type --</option>
								<option value="Hitam" <?php echo ($type == 'Hitam') ? 'selected' : ''; ?>>Hitam</option>
								<option value="Putih" <?php echo ($type == 'Putih') ? 'selected' : ''; ?>>Putih</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Carabayar</td>
						<td colspan="3">
							<select class="input-xlarge" id="m_carabayar" name="m_carabayar">
								<option value="" <?php echo ($type == '') ? 'selected' : ''; ?>>-- Pilih Cara Bayar --</option>
								<option value="1" <?php echo ($type == 'Cash') ? 'selected' : ''; ?>>Cash</option>
								<option value="2" <?php echo ($type == 'Transfer') ? 'selected' : ''; ?>>Transfer</option>
								<option value="3" <?php echo ($type == 'Giro') ? 'selected' : ''; ?>>Giro</option>
							</select>
						</td>
					</tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 60%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th onclick="listrequest('')" style="text-align:center; text-decoration:underline; font-weight:bold; cursor:pointer;">Project</th>
                        <th onclick="listrequest('')" style="text-align:center; text-decoration:underline; font-weight:bold; cursor:pointer;">Nomor PO</th>
                        <th onclick="listrequest('')" style="text-align:center; text-decoration:underline; font-weight:bold; cursor:pointer;">Tanggal PO </br> YYYY-MM-DD</th>
                        <th>Keterangan</th>
                        <th><div align="center">Nilai PO</div></th>
                        <th><div align="center">Nilai Bayar</div></th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tjumlah_po = 0 ;
						$tbayar = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, c.m_tanggal m_tanggal_po, a.m_nomor_po, c.
										from 	t_pembayaran2 a, t_po c 
										where 	a.m_nomor = '".$nomor."' and 
												a.m_nopo = c.m_nomor   " ;
							//echo $tsql2;
							$stmt2 = $con_dbnew->query($tsql2);
                            while( $row2 = $stmt2->fetch_assoc())
							{	
								$i = $i + 1 ;
								
								$tjumlah_po = $tjumlah_po + $row2['m_jumlah_po'] ;
								$tbayar = $tbayar + $row2['m_jumlah'] ;
								?>
								<tr>
									<td><?php echo $i; ?> </td>
									<td><input class="input-large" type="text" id="m_project<?php echo $i; ?>" name="m_project<?php echo $i; ?>" value="<?php echo $row2['m_project']; ?>" style="text-align:center;cursor:pointer"  READONLY />
									<td><input class="input-large" type="text" id="m_nomor_po<?php echo $i; ?>" name="m_nomor_po<?php echo $i; ?>" value="<?php echo $row2['m_nomor_po']; ?>" style="text-align:center;cursor:pointer"  READONLY />
									<td><input class="input-large" type="text" id="m_tanggal_po<?php echo $i; ?>" name="m_tanggal_po<?php echo $i; ?>" value="<?php echo $row2['m_tanggal_po']; ?>" style="text-align:center;cursor:pointer"  READONLY />
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>"/>
									</td>
									<td><div align="center"><textarea class="input-large" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" style="width:300px; height:120px; resize:vertical;" /><?php echo htmlspecialchars($row2['m_keterangan']); ?></textarea></div></td>
									<td><div align="center"><div align="center"><input class="input-mini" type="text" id="m_jumlah_po<?php echo $i; ?>" name="m_jumlah_po<?php echo $i; ?>" value="<?php echo number_format($row2['m_jumlah_po'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc()" /></div></td>
									<td><div align="center"><div align="center"><input class="input-mini" type="text" id="m_jumlah<?php echo $i; ?>" name="m_jumlah<?php echo $i; ?>" value="<?php echo number_format($row2['m_jumlah'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc()" /></div></td>
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
							$addrow = 0 ;
							/*
							while( $addrow <= 1 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr> 
									<td><?php echo $i; ?> </td>
                                    <td><input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value=""  onchange="listitem('<?php echo $i; ?>')" style="text-align:center" onchange="listitem(<?php echo $i; ?>)" />
										<input class="input-small" type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value=""/>
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $i; ?>"/>
										<input class="input-small" type="hidden" id="m_nomor_request<?php echo $i; ?>" name="m_nomor_request<?php echo $i; ?>" value="<?php echo $i; ?>"/>
									</td>	
                                    <td><div align="center"><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_unit<?php echo $i; ?>" name="m_unit<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_total<?php echo $i; ?>" name="m_total<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td>
										<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
									</td>
								</tr>
								<?php
							}
							*/
						}
                    ?>
                </tbody>
                <tfoot>           
                    <tr>
                        <th colspan="5"></th>
                        <th><div id="sp-totjumlahpo" align="center"><?php echo number_format($tjumlah_po, 0, '.', ','); ?></div></th>
                        <th><div id="sp-totjumlah" align="center"><?php echo number_format($tbayar, 0, '.', ','); ?></div></th>
                        <th></th>	
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
            <input type="text" id="cek_noplu" name="cek_noplu" value="1" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_karet" name="cek_karet" value="0" />
        </span>
    </div>         
    
    <div id="dialog-listcust" title="Daftar Project">
    <div id="datacust"></div>
	</div>
	
    <div id="dialog-listproject" title="Daftar Project">
    <div id="dataproject"></div>
	</div>
	
    <div id="dialog-listsupp" title="Daftar Supplier">
    <div id="datasupp"></div>
	</div>
	
	<div id="dialog-listrequest" title="Daftar Request">
	  <div id="datarequest" class="dialog-content-scroll"></div>
	</div>
	
	<div id="dialog-listitem" title="Daftar Request">
	  <div id="dataitem" class="dialog-content-scroll"></div>
	</div>
	
    
	<style>
    //* Umum, untuk semua dialog list */
	#dialog-listcust, 
	#dialog-listitem,
	#dialog-listproject,
	#dialog-listsupp,
	#dialog-listrequest {
		font-family: Arial, sans-serif;
		font-size: 13px;
	}

	/* Styling tabel di dalam masing-masing dialog */
	#dialog-listcust table,
	#dialog-listitem table,
	#dialog-listproject table,
	#dialog-listsupp table,
	#dialog-listrequest table  {
		border-collapse: collapse;
		width: 100%;
	}

	#dialog-listcust th, 
	#dialog-listcust td,
	#dialog-listitem th, 
	#dialog-listitem td,
	#dialog-listproject th, 
	#dialog-listproject td,
	#dialog-listsupp th, 
	#dialog-listsupp td,
	#dialog-listrequest th, 
	#dialog-listrequest td {
		border: 1px solid #ddd;
		padding: 8px;
	}

	#dialog-listcust th,
	#dialog-listitem th,
	#dialog-listproject th,
	#dialog-listsupp th,
	#dialog-listrequest th {
		background: #f4f4f4;
		font-weight: bold;
		text-align: left;
	}

	#dialog-listcust tr:nth-child(even),
	#dialog-listitem tr:nth-child(even),
	#dialog-listproject tr:nth-child(even),
	#dialog-listsupp tr:nth-child(even),
	#dialog-listrequest tr:nth-child(even) {
		background-color: #f9f9f9;
	}

	#dialog-listcust tr:hover,
	#dialog-listitem tr:hover,
	#dialog-listproject tr:hover,
	#dialog-listsupp tr:hover,
	#dialog-listrequest tr:hover {
		background-color: #f1f1f1;
	}

	/* Scroll khusus konten dialog */
	.ui-dialog .ui-dialog-content {
		overflow: hidden !important;
		padding: 0 !important;
	}

	.dialog-content-scroll {
		max-height: 90vh;
		overflow-y: auto;
	}

</style>
	
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			// datetimepicker
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});

			// dialog list customer
			$("#dialog-listcust").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});
			
			// dialog list project
			$("#dialog-listproject").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});
			
			// dialog list supplier
			$("#dialog-listsupp").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});

			// inisialisasi dialog list request
			$("#dialog-listrequest").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});
			
			$("#dialog-listitem").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});

			// update ukuran saat resize window
			$(window).resize(function() {
				$("#dialog-listcust").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listcust").dialog("option", "height", $(window).height() * 0.8);
				
				$("#dialog-listproject").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listproject").dialog("option", "height", $(window).height() * 0.8);
				
				$("#dialog-listsupp").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listsupp").dialog("option", "height", $(window).height() * 0.8);

				$("#dialog-listitem").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listitem").dialog("option", "height", $(window).height() * 0.8);
				
				// di bagian window.resize handler tambahkan:
				$("#dialog-listrequest").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listrequest").dialog("option", "height", $(window).height() * 0.8);
			});
		});
		
		$(function() {
		$( "#dialog-material" ).dialog({
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
		
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("pos.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		
		
		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					
				$("#datacust").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_pembayaran-cekproject.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}
		

		function listproject()
		{
			var data={tx:$('#m_nama_project').val()};

			var fungsi=function(respon){
					
				$("#dataproject").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_pembayaran-cekproject.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listproject" ).dialog( "open" );
		}
		
		
		function selectproject(vkode,vnama,valamat,vlokasi,vclient,vsupervisor)
		{
			
			document.getElementById('m_kode_project').value = vkode ;
			document.getElementById('m_nama_project').value = vnama ;
			document.getElementById('m_lokasi').value = vlokasi ;
			document.getElementById('m_namaclient').value = vclient ;
			document.getElementById('m_supervisor').value = vsupervisor ;
			
			$( "#dialog-listproject" ).dialog( "close" );
		}
		
		
		function listsupp()
		{
			var data={tx:$('#m_nama_supplier').val()};

			var fungsi=function(respon){
					
				$("#datasupp").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_pembayaran-ceksupplier.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listsupp" ).dialog( "open" );
		}
		
		
		function selectsupp(vkode,vnama,valamat,vtelepon,vpic)
		{
			
			document.getElementById('m_kode_supplier').value = vkode ;
			document.getElementById('m_nama_supplier').value = vnama ;
			//alert();
			document.getElementById('m_alamat_supplier').value = valamat ;
			document.getElementById('m_telepon').value = vtelepon ;
			document.getElementById('m_picsupplier').value = vpic ;
			
			$( "#dialog-listsupp" ).dialog( "close" );
		}
		
		function listrequest() 
		{
			var data = {
				kode_supplier: $('#m_kode_supplier').val()
			};

			var fungsi = function(respon) {
				$("#datarequest").html("<div class='result-wrapper'>" + respon + "</div>");
			};

			$.get('t_pembayaran-cekrequest.php?rnd=' + new Date().getTime(), data, fungsi);

			$("#dialog-listrequest").dialog("open");
		}
		
		function addSelectedRequests() 
		{
			var selected = $('#requestTable input[type="checkbox"]:checked');
			if (selected.length === 0) {
				alert("Silakan pilih minimal 1 request.");
				return;
			}

			selected.each(function() {
				var row = $(this).closest('tr');
				var nomorPO = row.find('td:eq(1)').text();
				var tanggalpo = row.find('td:eq(2)').text();
				var project = row.find('td:eq(4)').text();
				var nilaipo = row.find('td:eq(5)').text();
				var nilaibayar = row.find('td:eq(7)').text();

				// Tambahkan row baru dengan data request
				addRowFromRequest(nomorPO, tanggalpo, project, nilaipo, nilaibayar);
			});
			
			$("#dialog-listrequest").dialog("close");
			recalc(); // update total otomatis
		}
		
		function listitem(rowke)
		{
			var tx = $('#m_nmitem'+rowke).val();  // simpan ke variabel tx

			
			var data={rk:rowke, tx:tx};
		
			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('t_penawaran-cekitem.php',data,fungsi);
			
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
		  	var jumrow = lastRow - 1;
			
			document.getElementById('jumrow').value = jumrow;
			
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tjumlah = 0 ;
			var tjumlahpo = 0 ;
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var jumlah = Number(document.getElementById('m_jumlah'+ i).value.replace(/,/g,""));
				var jumlah_po = Number(document.getElementById('m_jumlah_po'+ i).value.replace(/,/g,""));
				
				if (jumlah > jumlah_po)
				{
					
					alert('Jumlah Bayar tidak boleh lebih besar dari sisa Pembayaran PO');
					
					jumlah = jumlah_po;
				}
				
				document.getElementById('m_jumlah' + i).value = formatangka(jumlah.toFixed(0).toString()) ;
				document.getElementById('m_jumlah_po' + i).value = formatangka(jumlah_po.toFixed(0).toString()) ;
				
				tjumlah = tjumlah + jumlah;
				tjumlahpo = tjumlahpo + jumlah_po;
				
				
			  $("#sp-totjumlah").html(formatangka((tjumlah).toFixed(0).toString()));
			  $("#sp-totjumlahpo").html(formatangka((tjumlahpo).toFixed(0).toString()));
			  

			}
			
		}
		
		function recalc_header() 
		{
			// ============================
			// AMBIL JUMLAH AWAL
			// ============================
			var jumlah_awal = Number($("#m_jumlah_rp").val().replace(/,/g,"")) || 0;

			// ============================
			// DISKON PERSEN
			// ============================
			var diskon_persen = Number($("#m_diskon_persen").val().replace(/,/g,"")) || 0;
			var diskon_jumlah = 0;

			if (diskon_persen > 0) {
				diskon_jumlah = Math.round(jumlah_awal * diskon_persen / 100);
			}

			var setelah_diskon = jumlah_awal - diskon_jumlah;
			if (setelah_diskon < 0) setelah_diskon = 0;

			// tampilkan ke kolom diskon_jumlah (readonly)
			$("#m_diskon_jumlah").val(formatangka(diskon_jumlah.toString()));

			// ============================
			// PPN
			// ============================
			var ppn_persen = Number($("#m_ppn_persen").val().replace(/,/g,"")) || 0;
			var ppn_jumlah = 0;

			if (ppn_persen > 0) {
				ppn_jumlah = Math.round(setelah_diskon * ppn_persen / 100);
			}

			// tampilkan hasil ke field
			$("#m_ppn_jumlah").val(formatangka(ppn_jumlah.toString()));

			// ============================
			// TOTAL AKHIR
			// ============================
			var total_akhir = setelah_diskon + ppn_jumlah;
			$("#m_total_rp").val(formatangka(total_akhir.toString()));
			
			
		}
		
		
		function addRowFromRequest(nomorPO, tanggalpo, project, nilaipo,nilaibayar) 
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
			var rowNum = lastRow - 2; // sebelum tfoot

			var row = tbl.insertRow(rowNum);

			// No urut
			var cell0 = row.insertCell(0);
			cell0.innerHTML = rowNum;

			// Project
			var cell1 = row.insertCell(1);
			cell1.innerHTML = 
				'<input class="input-medium" type="text" id="m_project'+rowNum+'" name="m_project'+rowNum+'" value="'+project+'" readonly />' +
				'<input type="hidden" id="m_no'+rowNum+'" name="m_no'+rowNum+'" value="'+rowNum+'" />';

			// Nomor
			var cell3 = row.insertCell(2);
			cell3.innerHTML = '<div align="center"><input class="input-medium" type="text" id="m_nomor_po'+rowNum+'" name="m_nomor_po'+rowNum+'" value="'+nomorPO+'" style="text-align:left" readonly /></div>';

			// tanggal po
			var cell3 = row.insertCell(3);
			cell3.innerHTML = '<div align="center"><input class="input-medium" type="text" id="m_tanggal_po'+rowNum+'" name="m_tanggal_po'+rowNum+'" value="'+tanggalpo+'" style="text-align:left" readonly /></div>';

			// Keterangan
			var cell2 = row.insertCell(4);
			cell2.innerHTML = '<div align="center"><textarea class="input-large"  id="m'+rowNum+'" name="m_keterangan'+rowNum+'" value=""  style="width:300px; height:120px; resize:vertical;" /></textarea></div>';

			// Nilai PO
			var cell3 = row.insertCell(5);
			cell3.innerHTML = '<div align="center"><input class="input-large" type="text" id="m_jumlah_po'+rowNum+'" name="m_jumlah_po'+rowNum+'" value="'+nilaipo+'" style="text-align:center" readonly /></div>';

			// Nilai Bayar
			var cell4 = row.insertCell(6);
			cell4.innerHTML = '<div align="center"><input class="input-large" type="text" id="m_jumlah'+rowNum+'" name="m_jumlah'+rowNum+'" value="'+nilaibayar+'" style="text-align:center" onchange="recalc()"/></div>';

			// Delete
			var cell7 = row.insertCell(7);
			cell7.innerHTML = '<input type="hidden" id="m_new'+rowNum+'" name="m_new'+rowNum+'" value="Y" />' +
							  '<input type="checkbox" id="m_hapus'+rowNum+'" name="m_hapus'+rowNum+'" />';
							 
		}



		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 2;
		  var row = tbl.insertRow(lastRow - 2);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td>'+iteration+'</td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_project'+iteration+'" name="m_project'+iteration+'" value=""   /><input class="input-small" type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="'+iteration+'"/></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_nomor_po'+iteration+'" name="m_nomor_po'+iteration+'" value=""   /></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><input class="input-medium" type="text" id="m_tanggal_po'+iteration+'" name="m_tanggal_po'+iteration+'" value=""   /></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="center"><textarea class="input-large" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value="" /></textarea></div></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_jumlah_po'+iteration+'" name="m_jumlah_po'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_jumlah'+iteration+'" name="m_jumlah'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_project'+iteration).focus();
		}
	</script>

    </body>
</html>