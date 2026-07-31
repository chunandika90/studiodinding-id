<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['st']);
	$kdsales = base64_decode($_GET['sl']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	if ($kdcabang == '') { $kdcabang = $_SESSION['store'];	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master CUSTOMER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbcmk.php" ;
		
		$tsql = "select a.*, b.m_nama as namacabang from mscustomer a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode " ;
		if ($kdcabang != ''){ $tsql = $tsql." and a.m_cabang = '".$kdcabang."' "; }
		$tsql = $tsql." order by a.m_cabang asc, a.m_nama asc" ;
		$stmt = sqlsrv_query( $con_dbcmk, $tsql);
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
      

        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
			<span id="listcabang">
            <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_cabang()">
				<option value="" >ALL</option>
                <?php
				$tsqlcabang = "select distinct a.m_cabang, b.m_nama from mscustomer a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode order by a.m_cabang asc" ;
				$stmtcabang = sqlsrv_query( $con_dbcmk, $tsqlcabang);
                while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcabang['m_cabang']; ?>" <?php if ($rowcabang['m_cabang'] == $kdcabang){ ?> selected="selected" <?php } ?> ><?php echo $rowcabang['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </span>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">Sales</span>
			<span id="listjr">
            <select name="kdsales" id="kdsales" class="input-large">
				<option value="" >ALL</option>
                <?php
				$tsqlsales = "select distinct a.m_kodesales, b.m_nama from mscustomer a, mssales b where a.m_kodesales = b.m_kode order by a.m_kodesales asc" ;
				$stmtsales = sqlsrv_query( $con_dbcmk, $tsqlsales);
                while( $rowsales = sqlsrv_fetch_array( $stmtsales, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowsales['m_kodesales']; ?>" <?php if ($rowsales['m_kodesales'] == $kdsales){ ?> selected="selected" <?php } ?>><?php echo $rowsales['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            </span>
        </div>
        
        <div class="span5 input-append">
            <input type="text" class="input-medium search-query" id="inputText" placeholder="Search Text" value="<?php echo $sctx ; ?>" onChange="oc_customer()" />
            <select name="searchby" id="searchby" class="input-small">
                <option value="nama" <?php if($scby == 'nama'){ ?> selected="selected" <?php } ?> >Nama</option>
                <option value="kode" <?php if($scby == 'kode'){ ?> selected="selected" <?php } ?> >Kode Cust.</option>
                <option value="member" <?php if($scby == 'member'){ ?> selected="selected" <?php } ?> >No.Member</option>
                <option value="alamat" <?php if($scby == 'alamat'){ ?> selected="selected" <?php } ?> >Alamat</option>
                <option value="kota" <?php if($scby == 'kota'){ ?> selected="selected" <?php } ?> >Kota</option>
                <option value="telepon" <?php if($scby == 'telepon'){ ?> selected="selected" <?php } ?> >Telepon</option>
                <option value="pinbb" <?php if($scby == 'pinbb'){ ?> selected="selected" <?php } ?> >Pin-BB</option>
            </select>
            <button class="btn" onClick="oc_customer('<?php echo $prm ; ?>')">Search</button>
			<?php
            if (substr($xparam[3],0,1) == 'Y')
            {
                ?>
	            <button class="btn" onClick="addcustomer('<?php echo $prm ; ?>')">New Customer</button>
                <?php
			}
			?>
        </div>
        
    </div>

    <div class="container pull-left" style="width: 90%; padding: 0 20px;">
        <span id="listdata">
        </span>
    </div>

    <!-- Modal -->
    <div id="view_modal">
		<input type="hidden" id="vkode" name="vkode" value="" />
		<input type="hidden" id="param" name="param" value="<?php echo  $prm; ?>" />
        <span id="viewdata">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$( "#view_modal" ).dialog({
				autoOpen: false,
				resizable: false,
				width:700,
				modal: true,
				position: "center top",
				buttons: {
					"Edit": function() {
						var cb = $('#kdcabang').val() ;
						var sl = $('#kdsales').val() ;
						var kd = $('#vkode').val() ;
						var prm = $('#param').val() ;
						alert (prm);
						window.open("mscustomer-edit.php?cb="+base64_encode(cb)+'&sl='+base64_encode(sl)+'&kd='+base64_encode(kd)+'&prm='+base64_encode(prm)+'&st='+base64_encode('cust'),'_self');
	
						$( this ).dialog( "close" );
						},
					"Delete": function() {
						var cb = $('#kdcabang').val() ;
						var sl = $('#kdsales').val() ;
						var kd = $('#vkode').val() ;
						var prm = $('#param').val() ;
						window.open("mscustomer-hapus.php?cb="+base64_encode(cb)+'&sl='+base64_encode(sl)+'&kd='+base64_encode(kd)+'&prm='+base64_encode(prm),'_self');
						
						$( this ).dialog( "close" );
						},
					"CLOSE": function() {
						$( this ).dialog( "close" );
						}
					
					}
				});

		});

		function btmembership(kdcust)
		{
			var cb = $('#kdcabang').val() ;
			var sl = $('#kdsales').val() ;
			var prm = $('#param').val() ;
			
			window.open("mscustomer-edit.php?cb="+base64_encode(cb)+'&sl='+base64_encode(sl)+'&kd='+base64_encode(kdcust)+'&prm='+base64_encode(prm)+'&st='+base64_encode('member'),'_self');
		}
	
		function btredeem(kdcust)
		{
			var cb = $('#kdcabang').val() ;
			var sl = $('#kdsales').val() ;
			var prm = $('#param').val() ;
			
			window.open("mscustomer-redeem.php?cb="+base64_encode(cb)+'&sl='+base64_encode(sl)+'&kd='+base64_encode(kdcust)+'&prm='+base64_encode(prm)+'&st='+base64_encode('member'),'_self');
		}
	
		function oc_brand()
		{
			document.getElementById('kdcabang').value = '' ;
			document.getElementById('kdsales').value = '' ;
			list_cabang();
		}
		
		function list_cabang()
		{
			var data={br:$('#kdbrand').val()};
			var fungsi=function(respon){
					$("#listcabang").html(respon);
				};
			$.get('report-listcabang.php',data,fungsi);
		}

		function oc_cabang()
		{
			document.getElementById('kdsales').value = '' ;
			list_sales();
		}
		
		function list_sales()
		{
			var data={cb:$('#kdcabang').val()};
			var fungsi=function(respon){
					$("#listjr").html(respon);
				};
			$.get('report-listsales.php',data,fungsi);
		}

		function oc_customer(vparam)
		{
			var data={cb:$('#kdcabang').val(),sl:$('#kdsales').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('mscustomer-list.php',data,fungsi);
		}

		function openconfirm(vparam,vkode)
		{
			document.getElementById('vkode').value = vkode ;
			$( "#dialog-confirm" ).dialog( "open" );
		}

		function addcustomer(vparam)
		{
			var cb = $('#kdcabang').val() ;
			var sl = $('#kdsales').val() ;
			var kd = '' ;
			window.open("mscustomer-edit.php?cb="+base64_encode(cb)+'&sl='+base64_encode(sl)+'&kd='+base64_encode(kd)+'&prm='+base64_encode(vparam)+'&st='+base64_encode('cust'),'_self');
		}

		function view_modal(kdcust)
		{
			document.getElementById('vkode').value = kdcust ;
			var data={kc:kdcust};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('customer-info.php',data,fungsi);
			
			$( "#view_modal" ).dialog( "open" );
			
		}

	</script>

    </body>
</html>