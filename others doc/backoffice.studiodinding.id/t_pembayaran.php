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
        <title>Invoice (Pembayaran)</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium">
                <?php
				// Fallback default periode: bulan sekarang (format YYYY-MM)
				$periodeDefault = date("Y-m");

				// Query MySQL: ambil distinct periode dari data yang ada (format YYYY-MM)
				$tsqlbulan = "SELECT DISTINCT DATE_FORMAT(m_tanggal, '%Y-%m') AS co_periode
							  FROM t_pembayaran
							  WHERE m_status = 'A'
							  ORDER BY co_periode DESC";

				$stmtbulan = $con_dbnew->query($tsqlbulan);

				if ($stmtbulan && $stmtbulan->num_rows > 0) {
					while ($rowbulan = $stmtbulan->fetch_assoc()) {
						$selected = ($rowbulan['co_periode'] == $periodeDefault) ? "selected" : "";
						echo "<option value='{$rowbulan['co_periode']}' {$selected}>{$rowbulan['co_periode']}</option>";
					}
				} else {
					// Jika tidak ada data, tampilkan default bulan ini
					echo "<option value='{$periodeDefault}' selected>{$periodeDefault}</option>";
				}
				?>
            </select>
        </div>
        <div class="input-append" style="width: auto; padding: 0 10px;">
            <input type="text" class="input-large search-query" id="inputText" placeholder="Search Text" value="" />
            <select name="searchby" id="searchby" class="input-medium">
                <option value="nomor" >Search</option>
            </select>
            <button class="btn" onClick="oc_pos('<?php echo $prm; ?>','')">Search</button>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
            	<button class="btn" onClick="edit_data('<?php echo $prm; ?>','','')">
            	New PO
       	  </button>
				<?php
			}
			?>
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
			var data={pr:$('#periode').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};

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
			$.get('t_pembayaran-list.php',data,fungsi);
		}

		function oc_detail(vparam,nomor)
		{
			var data={nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('t_pembayaran-view.php',data,fungsi);
		}

		function add_inv(vparam, nomor) {

			var data = { nm: nomor, prm: vparam };

			$.get('t_pembayaran-invoice.php', data, function(respon){

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
			$.get('t_pembayaran-hapus.php',data,fungsi);
			
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
			
			
			window.open("t_pembayaran-edit.php?nm="+btoa(nomor)+'&pr='+btoa(pr)+'&prm='+btoa(vparam),'_self');
		}

		function print_all(nomor)
		{
			window.open("t_pembayaran-printall.php?nm="+btoa(nomor),'_blank');
		}

		function print_data(kdcab,nomor,kdbrg,productid)
		{
			window.open("t_pembayaran-print.php?nm="+btoa(nomor)+'&kdbrg='+btoa(kdbrg)+'&productid='+btoa(productid),'_blank');
		}
		
		
		function recalc_payment()
		{
			
		
			var jumlah = Number(document.getElementById('m_jumlah' ).value.replace(/,/g,""));
			
			document.getElementById('m_jumlah').value = formatangka(jumlah.toFixed(0).toString()) ;
		
			
			
		}
		
		function batal_pos(vparam,nomor)
		{
			var data={nm:nomor,pr:$('#periode').val(),prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
					oc_detail(vparam,nomor);
				};
			$.get('t_pembayaran-batal.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		function print_cert(nomor,kdbrg,productid)
		{
			
			window.open("t_pembayaran-sertifikat.php?nm="+btoa(nomor)+'&kdbrg='+btoa(kdbrg)+'&productid='+btoa(productid),'_blank');
		}
		
			window.onload = function() {
	  oc_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>');
	};
		
	</script>
	
    </body>
</html>