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
        <title>Material Request</title>
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
			$nama = '' ;
			$kdcust = '' ;
			$alamat = '' ;
			$lokasi = '' ;
			$namaclient = '' ;
			$supervisor = '' ;
			$nama_supervisor = '' ;
			$tgl_kirim = '' ;
			$ket = '' ;
			$status = 'A' ;
			
			if (!empty($tgl_kirim)) {
				$tgl_kirim = date("d/m/Y", strtotime($tgl_kirim));
			}
		}
		else
		{
			$tsql = "select a.m_kode_project, a.m_nama, a.m_status, a.m_supervisor, a.m_keterangan, 
					 DATE(a.m_tanggal) AS co_tgl, 
					 TIME(a.m_tanggal) AS co_jam, 
					 b.m_alamat, b.m_lokasi, b.nama_client m_namaclient, b.supervisor_project m_supervisor,
					 case when a.m_jumlah = a.m_po then 'Complete' else 'Not Complete' end status_bayar, a.m_tanggal_kirim
					 from t_penawaran a, master_project b  
					 where a.m_nomor = '".$nomor."' and a.m_kode_project = b.m_kode  " ;
			$stmt = $con_dbnew->query($tsql);
			$row = $stmt->fetch_assoc();
			if ($row) 
			{
				$tgl = $row['co_tgl'] ;
				$jam = $row['co_jam'] ;
				$nama = $row['m_nama'] ;
				$kdcust = $row['m_kode_project'] ;
				$alamat = $row['m_alamat'] ;
				$lokasi = $row['m_lokasi'] ;
				$namaclient = $row['m_namaclient'] ;
				$supervisor = $row['m_supervisor'] ;
				$ket = $row['m_keterangan'] ;
				$status = $row['m_status'] ;
				$tgl_kirim = $row['m_tanggal_kirim'] ;
			} else {
				// Data tidak ditemukan
				$tgl = date("d/m/Y") ;
				$jam = date("H:i:s") ;
				$nama = '' ;
				$kdcust = '' ;
				$alamat = '' ;
				$lokasi = '' ;
				$namaclient = '' ;
				$supervisor = '' ;
				$nama_supervisor = '' ;
				$ket = '' ;
				$status = 'A' ;
				$tgl_kirim = '' ;
				
				if (!empty($tgl_kirim)) {
					$tgl_kirim = date("d/m/Y", strtotime($tgl_kirim));
				}
			}
		}
		
    ?>
	<form class="form-horizontal" method="post" action="t_penawaran-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid table-responsive" style="padding: 0 10px;">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th colspan="4"><h4>Material Request</h4></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-label="Nomor">Nomor</td>
						<td data-label="Input Nomor">
							<input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
							<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
							<input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
							<input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
							<input type="hidden" id="jumrow" name="jumrow" value="0" />
							<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
						</td>
						<td data-label="Tanggal">Tanggal</td>
						<td data-label="Input Tanggal">
							<input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly />
						</td>
					</tr>
					<tr>
						<td data-label="Kode Project">Kode Project</td>
						<td data-label="Input Kode Project">
							<input class="input-medium" type="text" id="m_kodecust" name="m_kodecust" value="<?php echo $kdcust; ?>" onclick="listcust()" style="text-align:center;cursor:pointer" readonly />
						</td>
						<td data-label="Nama Project">Nama Project</td>
						<td data-label="Input Nama Project">
							<div class="input-append responsive-append">
								<input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" onchange="listcust()" required />
							</div>
						</td>
					</tr>
					<tr>
						<td data-label="Alamat">Alamat</td>
						<td colspan="3" data-label="Input Alamat">
							<textarea class="input-xxlarge" name="m_alamat" id="m_alamat" rows="2" readonly><?php echo $alamat; ?></textarea>
						</td>
					</tr>
					<tr>
						<td data-label="Lokasi">Lokasi</td>
						<td data-label="Input Lokasi">
							<input class="input-medium" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" readonly/>
						</td>
						<td data-label="Nama Client">Nama Client</td>
						<td data-label="Input Nama Client">
							<input class="input-medium" type="text" id="m_namaclient" name="m_namaclient" value="<?php echo $namaclient; ?>" readonly/>
						</td>
					</tr>
					<tr>
						<td data-label="Supervisor">Supervisor</td>
						<td colspan="3" data-label="Input Supervisor">
							<input class="input-large" type="text" id="m_supervisor" name="m_supervisor" value="<?php echo $supervisor; ?>" readonly/>
						</td>
					</tr>
					<tr>
						<td data-label="Keterangan">Keterangan</td>
						<td colspan="3" data-label="Input Keterangan">
							<input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" />
						</td>
					</tr>
					<tr>
						<td data-label="Keterangan">Tanggal Kirim</td>
						<td colspan="3" data-label="Input Tanggal">
							<div class="input-group date" id="datepicker_m_tgl_kirim">
								<input type="text" name="m_tgl_kirim" id="m_tgl_kirim" 
									   class="input-medium form-control" 
									   value="<?php echo $tgl_kirim; ?>" />
								<span class="input-group-addon"><i class="icon-calendar"></i></span>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

    	<div class="container pull-left row-fluid table-responsive" style="padding:0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed" style="width:100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><div align="center">Material</div></th>
                        <th><div align="center">Keterangan</div></th>
                        <th><div align="center">Unit</div></th>
                        <th><div align="center">Qty</div></th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$ttot = 0 ;
						$ttotal = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, c.m_nama as co_namabarang
                        from 	t_penawaran2 a, master_item c 
                        where 	a.m_nomor = '".$nomor."' and 
                                a.m_item = c.m_kode   " ;
							//echo $tsql2;
							$stmt2 = $con_dbnew->query($tsql2);
                            while( $row2 = $stmt2->fetch_assoc())
							{	
								$i = $i + 1 ;
								
								$tqty = $tqty + $row2['m_qty'] ;
								?>
								<tr>
									<td><?php echo $i; ?> </td>
									<td><input class="input-large" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="<?php echo $row2['co_namabarang']; ?>" style="text-align:center;cursor:pointer"  onchange="listitem(<?php echo $i; ?>)" />
										<input class="input-small" type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="<?php echo $row2['m_item']; ?>"/>
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>"/>
									</td>
									<td><div align="center"><input class="input-large" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="<?php echo $row2['m_keterangan']; ?>" style="text-align:center"/></div></td>
									<td><div align="center"><input class="input-medium" type="text" id="m_unit<?php echo $i; ?>" name="m_unit<?php echo $i; ?>" value="<?php echo $row2['m_unit']; ?>" style="text-align:center;cursor:pointer"/></div></td>
									<td><div align="center"><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 3, '.', ','); ?>" style="text-align:center" onChange="recalc()" /></div></td>
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
									<td><?php echo $i; ?> </td>
                                    <td><input class="input-large" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value=""  onchange="listitem('<?php echo $i; ?>')" style="text-align:left" onchange="listitem(<?php echo $i; ?>)" />
										<input class="input-small" type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value=""/>
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $i; ?>"/>
									</td>	
                                    <td><div align="center"><input class="input-large" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
                                    <td><div align="center"><input class="input-medium" type="text" id="m_unit<?php echo $i; ?>" name="m_unit<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="0" style="text-align:center" onchange="recalc()" /></div></td>
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
						<th>
							<div id="sp-totqty" align="center"><?php echo number_format($tqty, 0, '.', ','); ?></div>
						</th>
						<th></th>
					</tr>
					<tr>
						<th colspan="6">
							<div class="tfoot-buttons">
								<input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data()" />
								<div>
									<input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
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
    
    <div id="dialog-listitem">
        <span id="dataitem">
        </span>
    </div>
	

    <div id="dialog-listmaterial">
        <span id="datamaterial">
        </span>
    </div>
    
	<style>
		/* ======================= */
		/* Style Dialog List Lo    */
		/* ======================= */
		#dialog-listcust, 
		#dialog-listitem {
			font-family: Arial, sans-serif;
			font-size: 13px;
		}

		/* Styling tabel di dalam masing-masing dialog */
		#dialog-listcust table,
		#dialog-listitem table {
			border-collapse: collapse;
			width: 100%;
		}
		#dialog-listcust th, 
		#dialog-listcust td,
		#dialog-listitem th, 
		#dialog-listitem td {
			border: 1px solid #ddd;
			padding: 8px;
		}
		#dialog-listcust th,
		#dialog-listitem th {
			background: #f4f4f4;
			font-weight: bold;
			text-align: left;
		}
		#dialog-listcust tr:nth-child(even),
		#dialog-listitem tr:nth-child(even) {
			background-color: #f9f9f9;
		}
		#dialog-listcust tr:hover,
		#dialog-listitem tr:hover {
			background-color: #f1f1f1;
		}

		/* Scroll khusus konten dialog */
		.ui-dialog .ui-dialog-content {
			overflow: hidden !important;
			padding: 0 !important;
		}
		.dialog-content-scroll {
			max-height: 60vh;
			overflow-y: auto;
		}

		/* ======================= */
		/* Style Tabel Material Request */
		/* ======================= */
		.table-responsive {
			width: 100%;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch; /* smooth scroll iOS */
		}
		
		/* Biar input di Project gak ngelebar di HP */
		.responsive-append {
			display: flex;
			flex-wrap: wrap;
			gap: 5px; /* jarak antar input */
		}

		.responsive-append input,
		.responsive-append .add-on {
			flex: 1 1 auto; /* bisa mengecil dan memenuhi ruang */
			min-width: 100px; /* biar gak terlalu kecil */
		}
		
		.tfoot-buttons {
			display: flex;
			justify-content: space-between; /* tombol Add Row kiri, Save/Cancel kanan */
			align-items: center;
			flex-wrap: wrap; /* biar tombol tidak keluar layar */
			gap: 5px;
		}

		.tfoot-buttons div {
			display: flex;
			gap: 5px; /* jarak antar Save & Cancel */
		}

		/* Responsive khusus mobile */
		@media (max-width: 768px) {
			.table-condensed th,
			.table-condensed td {
				font-size: 12px;       /* lebih kecil di HP */
				padding: 4px 6px;      /* padding lebih kecil */
				white-space: nowrap;   /* jangan wrap teks */
			}

			 .table-condensed input,
			.table-condensed textarea {
				width: 100% !important;
				box-sizing: border-box;
			}
			.input-append {
				flex-direction: column !important;
			}
			.input-append input, .input-append span {
				margin-bottom: 5px;
				width: 100% !important;
			}
			 #table_data input.input-large {
				width: 100px; /* atau bisa pakai 90% */
			}
			#table_data input.input-medium {
				width: 70px;
			}
			#table_data input.input-mini {
				width: 50px;
			}
			 .tfoot-buttons {
				flex-wrap: wrap; /* tombol tetap tidak keluar layar */
			}
			.tfoot-buttons input.btn {
				flex: 1 1 auto; /* tombol bisa mengecil tapi tetap sejajar */
				min-width: 80px; /* batas minimal */
			}
			 #datepicker_m_tgl_kirim,
			#datepicker_m_tgl_kirim .input-group {
				width: 100% !important;
			}

			#datepicker_m_tgl_kirim input {
				width: 100% !important;
				box-sizing: border-box;
			}
		}
		
		.bootstrap-datetimepicker-widget {
			z-index: 99999 !important; /* lebih tinggi */
			top: auto !important;
			left: auto !important;
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

			// dialog list item
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

				$("#dialog-listitem").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listitem").dialog("option", "height", $(window).height() * 0.8);
			});
		});
		
		$(function(){
			 $('#datepicker_m_tgl_kirim').datetimepicker({
					format: 'dd/MM/yyyy',
					minView: 2,
					todayHighlight: true
				}).on('changeDate', function(ev){
					$(this).datetimepicker('hide'); // paksa hide saat pilih tanggal
				});

				// biar muncul saat input diklik
				$('#m_tgl_kirim').on('focus', function(){
					$('#datepicker_m_tgl_kirim').datetimepicker('show');
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
			$.get('t_penawaran-cekproject.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
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
		
		
		function selectshape(rowke,shape,size,ukuran,hargam,hargar,opbm,opbr)
		{
			document.getElementById('m_shape'+rowke).value = shape ;
			document.getElementById('m_size'+rowke).value = size ;
			document.getElementById('m_ukuran'+rowke).value = ukuran ;
			
			$( "#dialog-listshape" ).dialog( "close" );
		}
		
		function selectcust(vkode,vnama,valamat,vlokasi,vclient,vsupervisor)
		{
			document.getElementById('m_kodecust').value = vkode ;
			document.getElementById('m_nama').value = vnama ;
			document.getElementById('m_alamat').value = valamat ;
			document.getElementById('m_lokasi').value = vlokasi ;
			document.getElementById('m_namaclient').value = vclient ;
			document.getElementById('m_supervisor').value = vsupervisor ;

			$( "#dialog-listcust" ).dialog( "close" );
		}

		function selectplu(vkode)
		{
			$( "#dialog-listdoc" ).dialog( "close" );
		}

		
		function listitem(rowke)
		{
			var data={rk:rowke, tx:$('#m_nmitem'+rowke).val()};
			
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
							document.getElementById('m_disc'+rowke).value = '0' ;
							document.getElementById('m_discount'+rowke).value = '0';
							document.getElementById('m_discount4'+rowke).value = '0';
							alert('Dobel Input Plu yang Sama!!!');
						}
					}
					
					
					else
					{
						document.getElementById('m_productid'+rowke).value = newplu;						
						document.getElementById('m_group'+rowke).value = '';
						document.getElementById('m_item'+rowke).value = '';
						document.getElementById('m_harga'+rowke).value = '0';
						document.getElementById('m_disc'+rowke).value = '0' ;
						document.getElementById('m_discount'+rowke).value = '0';
						document.getElementById('m_discount4'+rowke).value = '0';
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
		  	var jumrow = lastRow - 1;
			
			var supervisor = document.getElementById('m_supervisor').value ;

			
			document.getElementById('jumrow').value = jumrow;
			
			if (supervisor == '') 
			{
				alert('Supervisor belum di isi !!!');
				return false ;
			}
			else
			{
				return true ;
			}
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tqty = 0 ;
			/*
			var tharga = 0 ;
			var tdisc1 = 0 ;
			var tdisc2 = 0 ;
			var tdisc3 = 0 ;
			var tdisc4 = 0 ;
			var ttotal = 0 ;
			*/
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				
				/*
				var harga = Number(document.getElementById('m_harga' + i).value.replace(/,/g,""));
				var disc  = Number(document.getElementById('m_disc' + i).value.replace(/,/g,""));
				var disc1 = Number(document.getElementById('m_discount' + i).value.replace(/,/g,""));
				var disc2  = Number(document.getElementById('m_disc2' + i).value.replace(/,/g,""));
				var disc22 = Number(document.getElementById('m_discount2' + i).value.replace(/,/g,""));
				var disc3  = Number(document.getElementById('m_disc3' + i).value.replace(/,/g,""));
				var disc33 = Number(document.getElementById('m_discount3' + i).value.replace(/,/g,""));
				var disc4 = Number(document.getElementById('m_discount4' + i).value.replace(/,/g,""));
				var jumlah = qty * harga ;
				
				//disc1 = jumlah * disc / 100 ;
				
				var disc1 = Math.round(jumlah * disc / 100) ;
				var jumlah1 = Math.round(jumlah - disc1) ;
				
				var disc22 = Math.round(jumlah1 * disc2 /100);
				var jumlah2 = Math.round(jumlah1 - disc22);
				
				
				var disc33 = Math.round(jumlah2 * disc3 /100);
				var jumlah3 = Math.round(jumlah2 - disc33);
				
				

				if (( disc4 > 5000 ) || ( disc4 < -5000 ))
				{
					disc4 = Math.round(jumlah3 - Math.floor(jumlah3/1000) * 1000) ;
				}
				
				total = Math.round(jumlah3 - disc4) ;
				
				*/

				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed(0).toString()) ;

/*			  
				document.getElementById('m_harga' + i).value = formatangka(harga.toFixed(0).toString()) ;
				document.getElementById('m_disc' + i).value = formatangka(disc.toFixed(2).toString()) ;
				document.getElementById('m_discount' + i).value = formatangka(disc1.toFixed(2).toString()) ;
				document.getElementById('m_disc2' + i).value = formatangka(disc2.toFixed(2).toString()) ;
				document.getElementById('m_discount2' + i).value = formatangka(disc22.toFixed(2).toString()) ;
				document.getElementById('m_disc3' + i).value = formatangka(disc3.toFixed(2).toString()) ;
				document.getElementById('m_discount3' + i).value = formatangka(disc33.toFixed(2).toString()) ;
				document.getElementById('m_discount4' + i).value = formatangka(disc4.toFixed(2).toString()) ;
				document.getElementById('m_total' + i).value = formatangka(total.toFixed(2).toString()) ;
				*/
				
				tqty = tqty + qty;
				/*
				tharga = tharga + harga;
				tdisc1 = tdisc1 + disc1;
				tdisc2 = tdisc2 + disc22;
				tdisc3 = tdisc3 + disc33;
				tdisc4 = tdisc4 + disc4;
				ttotal = ttotal + total;
				*/
			  $("#sp-totqty").html(formatangka((tqty).toFixed(0).toString()));
			  
			  /*
			  $("#sp-totharga").html(formatangka((tharga).toFixed(0).toString()));
			  $("#sp-totdisc1").html(formatangka((tdisc1).toFixed(2).toString()));
			  $("#sp-totdisc2").html(formatangka((tdisc2).toFixed(2).toString()));
			  $("#sp-totdisc3").html(formatangka((tdisc3).toFixed(2).toString()));
			  $("#sp-totdisc4").html(formatangka((tdisc4).toFixed(2).toString()));
			  $("#sp-tottotal").html(formatangka((ttotal).toFixed(2).toString()));
			  */
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
		  cellno.innerHTML='<td>'+iteration+'</td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-large" type="text" id="m_nmitem'+iteration+'" name="m_nmitem'+iteration+'" value=""  onchange="listitem('+iteration+')" /><input class="input-small" type="hidden" id="m_item'+iteration+'" name="m_item'+iteration+'" value=""/><input class="input-small" type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="'+iteration+'"/></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value="" style="text-align:center"  /></div></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input class="input-medium" type="text" id="m_unit'+iteration+'" name="m_unit'+iteration+'" value="" style="text-align:center"  /></div></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_model'+iteration).focus();
		}
	</script>

    </body>
</html>