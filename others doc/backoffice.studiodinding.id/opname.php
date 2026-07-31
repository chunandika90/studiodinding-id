<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	$kdstore = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	$soid = base64_decode($_GET['so']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);

	if ($kdstore == '')
	{
		$kdstore = $_SESSION['store'];
		$periode = date("Y-m");
		$soid = date("Ym001");
	}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>STOCK OPNAME</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body onLoad="oc_opname('<?php echo $prm; ?>')">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
		<div class="span3 input-prepend">
        	<span class="add-on">Store</span>
            <select name="kdstore" id="kdstore" class="input-large" onChange="oc_store()" <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>>
                <?php
				$tsqlstore = "select m_kode, m_nama from msmaster where m_type = 'STORE' order by m_kode asc" ;
				$stmtstore = sqlsrv_query( $con_dbnew, $tsqlstore);
                while( $rowstore = sqlsrv_fetch_array( $stmtstore, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowstore['m_kode']; ?>" <?php if($rowstore['m_kode'] == $kdstore){ ?> selected <?php } ?> ><?php echo $rowstore['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <span id="listprd" >            
            <select name="periode" id="periode" class="input-medium" onChange="oc_periode()">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_stockopname0 where m_status = 'A' and m_cabang = '".$kdstore."' order by co_periode desc" ;
				$stmtbulan = sqlsrv_query( $con_dbnew, $tsqlbulan);
                while( $rowbulan = sqlsrv_fetch_array( $stmtbulan, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbulan['co_periode']; ?>" <?php if($rowbulan['co_periode'] == $periode){ ?> selected <?php } ?> ><?php echo $rowbulan['co_periode']; ?></option>
                    <?php
                }
                ?>
            </select>
            </span>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">SO-ID</span>
            <span id="listsoid" >
            <select name="soid" id="soid" class="input-medium">
                <?php
				$dumb = explode('-',$periode);
				$tsqlsoid = "select distinct m_cabang, m_nomor from t_stockopname0 where m_cabang = '".$kdstore."' and year(m_tanggal) = ".$dumb[0]." and month(m_tanggal) = ".$dumb[1]." order by m_nomor desc" ;
				$stmtsoid = sqlsrv_query( $con_dbnew, $tsqlsoid);
                while( $rowsoid = sqlsrv_fetch_array( $stmtsoid, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowsoid['m_nomor']; ?>" <?php if($rowsoid['m_nomor'] == $soid){ ?> selected <?php } ?> ><?php echo $rowsoid['m_nomor']; ?></option>
                    <?php
                }
                ?>
            </select>
            </span>
        </div>

        <div class="span3 input-prepend">
            <input type="text" class="input-small search-query" id="inputText" placeholder="Search Text" value="" />
            <select name="searchby" id="searchby" class="input-small">
                <option value="noplu" >No.PLU</option>
                <option value="nama" >Nama</option>
            </select>
            <button class="btn" onClick="oc_opname('<?php echo $prm; ?>')">Retrieve</button>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
                <button class="btn" onClick="edit_data('<?php echo $prm; ?>','','')">New Entry SO</button>
                <?php
			}
			?>
        </div>
    </div>

    <div class="container pull-left" style="width: 100%; padding: 0 20px;">
        <div class="container pull-left" style="width:33%; padding: 0 20px;">
            <span id="listdata">
            </span>
        </div>
        <div class="container pull-right" style="width:60%; padding: 0 10px;">
            <span id="detaildata">
            </span>
        </div>
	</div>
    
    <!-- Modal -->
    <div id="view_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="viewdata">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button class="btn" data-dismiss="modal">Close</button>
            </div>
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
	
		function oc_store()
		{
			document.getElementById('periode').value = '' ;
			document.getElementById('soid').value = '' ;
			var data={kdcab:$('#kdstore').val()};
			var fungsi=function(respon){
					$("#listprd").html(respon);
					oc_periode();
				};
			$.get('opname-listprd.php',data,fungsi);
		}

		function oc_periode()
		{
			document.getElementById('soid').value = '' ;
			var data={kdcab:$('#kdstore').val(), periode:$('#periode').val()};
			var fungsi=function(respon){
					$("#listsoid").html(respon);
				};
			$.get('opname-listsoid.php',data,fungsi);
		}

		function oc_opname(vparam)
		{
			var data={cb:$('#kdstore').val(),pr:$('#periode').val(),so:$('#soid').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#detaildata").html('');
				};
			$.get('opname-list.php',data,fungsi);
		}

		function oc_detail(vparam,kdcab,nomor)
		{
			var data={cb:kdcab,nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('opname-view.php',data,fungsi);
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

		function edit_data(vparam,kdcab,nomor)
		{
			var st = $('#kdstore').val() ;
			var pr = $('#periode').val() ;
			var so = $('#soid').val() ;
			if (kdcab == '') { kdcab = st ; }
			window.open("opname-edit.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&st='+base64_encode(st)+'&pr='+base64_encode(pr)+'&so='+base64_encode(so)+'&prm='+base64_encode(vparam),'_self');
		}

		function print_data(kdcab,nomor)
		{
			window.open("opname-print.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor),'_blank');
		}

		function hapus_data(vparam,kdcab,nomor)
		{
			var st = $('#kdstore').val() ;
			var pr = $('#periode').val() ;
			var so = $('#soid').val() ;
			window.open("opname-hapus.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&st='+base64_encode(st)+'&pr='+base64_encode(pr)+'&so='+base64_encode(so)+'&prm='+base64_encode(vparam),'_self');
		}

	</script>

    </body>
</html>