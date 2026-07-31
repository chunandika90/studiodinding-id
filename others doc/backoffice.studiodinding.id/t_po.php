<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	$periode  = base64_decode($_GET['pr'] ?? '');
	$nomor    = base64_decode($_GET['nm'] ?? '');
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	
	
	$periode = date("Y-m");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>PO(Supplier)</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
		<style>
		/* ============================= */
		/* Tabel scrollable di HP        */
		/* ============================= */
		.table-responsive {
		  width: 100%;
		  overflow-x: auto;
		  -webkit-overflow-scrolling: touch; /* smooth scroll di iOS */
		}
		.filter-group input.search-query {
			flex: 1;
			min-width: 150px;
			max-width: 400px; /* Batasi lebar di desktop */
		}


		/* ============================= */
		/* Layout responsive untuk layar kecil */
		/* ============================= */
		@media (max-width: 768px) {
		  /* Float kiri/kanan jadi stack */
		  .pull-left, .pull-right {
			float: none !important;
			width: 100% !important;
			padding: 0 !important;
			margin-bottom: 5px;
		  }

		  /* Input group jadi full-width */
		  .input-prepend, .input-append {
			display: block;
			width: 100% !important;
			margin-bottom: 10px;
		  }

		  /* Input & select full-width */
		  #inputText, #searchby, #periode {
			width: 100% !important;
			margin-bottom: 5px;
		  }

		  /* Button full-width & jarak bawah */
		  .btn {
			width: 100% !important;
			margin-bottom: 5px;
		  }

		  /* Font tabel lebih kecil, nowrap supaya muat */
		  table.table th,
		  table.table td {
			font-size: 12px;
			white-space: nowrap;
		  }

		  /* Scroll tabel smooth di iOS */
		  .table-responsive {
			-webkit-overflow-scrolling: touch;
		  }
		  .filter-group {
				display: flex;
				flex-direction: column;
				gap: 5px; /* jarak antar elemen */
			}
			.filter-group input,
			.filter-group select,
			.filter-group button {
				width: 100% !important;
				box-sizing: border-box;
			}
			
			.filter-bar {
				flex-direction: column;
				align-items: flex-start;
			  }
			  .filter-bar .input-prepend,
			  .filter-bar .input-group {
				width: 100%;
			  }
			  .filter-bar select,
			  .filter-bar input,
			  .filter-bar button {
				width: auto;
			  }
			
			table th.hide-mobile,
			table td.hide-mobile {
				display: none !important;
			}
			
			#listdata, #detaildata {
				width: 100% !important;
				padding: 0 !important;
				margin-bottom: 10px;
			}
			table th.supplier, table td.supplier {
				white-space: normal !important;
				word-wrap: break-word;
				word-break: break-word;
			}
		}

		/* ============================= */
		/* Foto thumbnail responsive     */
		/* ============================= */
		.table img {
		  max-width: 100%;
		  height: auto;
		}

		/* Modal image responsive */
		#modalImage {
		  width: 100%;
		  max-width: 500px;
		  height: auto;
		}
		</style>
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width:auto; padding:0 20px;">
	  <div class="filter-bar" style="
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 10px;
	  ">
		<!-- Periode -->
		<div class="input-prepend" style="display:flex; align-items:center;">
		  <span class="add-on">Periode</span>
		  <select name="periode" id="periode" class="input-medium" style="margin-left:5px;">
			<?php
			$periodeDefault = date("Y-m");
			$tsqlbulan = "SELECT DISTINCT DATE_FORMAT(m_tanggal, '%Y-%m') AS co_periode
						  FROM t_po
						  WHERE m_status = 'A'
						  ORDER BY co_periode DESC";
			$stmtbulan = $con_dbnew->query($tsqlbulan);
			if ($stmtbulan && $stmtbulan->num_rows > 0) {
			  while ($rowbulan = $stmtbulan->fetch_assoc()) {
				$selected = ($rowbulan['co_periode'] == $periodeDefault) ? "selected" : "";
				echo "<option value='{$rowbulan['co_periode']}' {$selected}>{$rowbulan['co_periode']}</option>";
			  }
			} else {
			  echo "<option value='{$periodeDefault}' selected>{$periodeDefault}</option>";
			}
			?>
		  </select>
		</div>
		

		<!-- Search -->
		<div class="input-group" style="display:flex; align-items:center; flex-wrap:wrap; gap:5px;">
		  <input type="text" class="input-large search-query" id="inputText" placeholder="Search Text" value="" style="flex:1; min-width:120px;" onchange="oc_pos('<?php echo $prm; ?>','')">
		  <select class="input-medium" id="m_approve" name="m_approve" onchange="oc_pos('<?php echo $prm; ?>','')">
				<option value="">-- Pilih Type --</option>
				<option value="belum_approved" selected>Belum Approve</option>
				<option value="approved">Approved</option>
			</select>
		  <button class="btn btn-primary" onClick="oc_pos('<?php echo $prm; ?>','')">Cari</button>

		  <?php if (substr($xparam[3],0,1) == 'Y') { ?>
			<button class="btn btn-success" onClick="edit_data('<?php echo $prm; ?>','','')">Input PO</button>
		  <?php } ?>
		</div>
	  </div>
	</div>

    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="container pull-left" style="width:30%; padding: 0 20px;">
            <span id="listdata">
            </span>
        </div>
        <div class="container pull-right" style="width:65%; padding: 0 10px;">
            <span id="detaildata">
            </span>
        </div>
	</div>
    
    <!-- Modal -->
	
    <!-- Modal -->
	<div id="view_modal" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
		<div class="modal-content" id="viewdata"></div>
	  </div>
	</div>    
	

	<!-- 2. jQuery UI (opsional) -->
	<script src="js/jquery-ui.js"></script>

	<!-- 3. Bootstrap 3 JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

	<!-- 4. Custom JS -->
	<script src="js/myjs.js"></script>

	<!-- 4. Script custom kamu -->
    <script type="text/javascript">
	

		
		
		function oc_pos(vparam,vnomor)
		{
			console.log("oc_pos called"); // ini harus tampil di console
			// alert("oc_pos called with param: " + vparam + " and nomor: " + vnomor);
			var data={pr:$('#periode').val(),approve:$('#m_approve').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};

			var fungsi=function(respon){
					$("#listdata").html(respon);
					if (vnomor == '')
					{
						$("#detaildata").html('');
					}
					else
					{
						oc_detail(vparam,vnomor);
					}
				};
			$.get('t_po-list.php',data,fungsi);
		}

		function oc_detail(vparam,nomor)
		{
			var data={nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('t_po-view.php',data,fungsi);
		}

		function add_inv(vparam, nomor) {

			var data = { nm: nomor, prm: vparam };

			$.get('t_po-invoice.php', data, function(respon){

				// masukkan hasil ke modal body
				$("#viewdata").html(respon);

				// aktifkan datepicker setelah konten ajax dimuat
				$('#m_tanggal').datepicker({
					format: "dd/mm/yyyy",
					autoclose: true,
					todayHighlight: true
				});

				// tampilkan modal
				$('#view_modal').modal('show');
			})
			.fail(function(xhr){
				alert("AJAX Error: " + xhr.status + " " + xhr.statusText);
			});
		}		

		function hapus_data(vparam,nomor)
		{
			var data={nm:nomor,pr:$('#periode').val(),prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
					oc_detail(vparam,nomor);
				};
			$.get('t_po-hapus.php',data,fungsi);
			
			$('#view_modal').modal();
		}

		function view_modal(kdbrg,productid)
		{
			var data={kdbrg:kdbrg, productid:productid};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('product-info.php',data,fungsi);
			
			$('#view_modal').modal();
		}

		function edit_data(vparam,nomor)
		{
			var pr = $('#periode').val() ;
			
			
			window.open("t_po-edit.php?nm="+btoa(nomor)+'&pr='+btoa(pr)+'&prm='+btoa(vparam),'_self');
		}

		function print_all(nomor)
		{
			window.open("t_po-printall.php?nm="+btoa(nomor),'_blank');
		}

		function print_data(kdcab,nomor,kdbrg,productid)
		{
			window.open("t_po-print.php?nm="+btoa(nomor)+'&kdbrg='+btoa(kdbrg)+'&productid='+btoa(productid),'_blank');
		}
		
		
		function recalc_payment()
		{
			
		
			var jumlah = Number(document.getElementById('m_jumlah' ).value.replace(/,/g,""));
			
			document.getElementById('m_jumlah').value = formatangka(jumlah.toFixed(0).toString()) ;
		
			
			
		}
		
		function batal_pos(vparam,nomor)
		{
			var data={nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
					oc_detail(vparam,nomor);
				};
			$.get('t_po-batal.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function approve_po(vparam,nomor)
		{
			var data={nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
					oc_detail(vparam,nomor);
				};
			$.get('t_po-approve.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function print_cert(nomor,kdbrg,productid)
		{
			
			window.open("t_po-sertifikat.php?nm="+btoa(nomor)+'&kdbrg='+btoa(kdbrg)+'&productid='+btoa(productid),'_blank');
		}
		
			window.onload = function() {
	  oc_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>');
	};
		
	</script>
	
    </body>
</html>