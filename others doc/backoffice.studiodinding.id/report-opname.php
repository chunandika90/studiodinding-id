<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	$kdcabang = base64_decode($_GET['cb']);
	$periode  = base64_decode($_GET['pr']);
	$soid = base64_decode($_GET['so']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);

	if ($kdcabang == '')
	{
		$kdcabang = $_SESSION['store'];
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

    <body onLoad="oc_opname()">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
		<div class="span3 input-prepend">
        	<span class="add-on">Cabang</span>
            <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_store()" <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>>
                <?php
				$tsqlstore = "select m_kode, m_nama from msmaster where m_type = 'STORE' order by m_kode asc" ;
				$stmtstore = sqlsrv_query( $con_dbnew, $tsqlstore);
                while( $rowstore = sqlsrv_fetch_array( $stmtstore, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowstore['m_kode']; ?>" <?php if($rowstore['m_kode'] == $kdcabang){ ?> selected <?php } ?> ><?php echo $rowstore['m_nama']; ?></option>
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
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_stockopname0 where m_status = 'A' and m_cabang = '".$kdcabang."' order by co_periode desc" ;
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
				$tsqlsoid = "select distinct m_cabang, m_nomor from t_stockopname0 where m_cabang = '".$kdcabang."' and year(m_tanggal) = ".$dumb[0]." and month(m_tanggal) = ".$dumb[1]." order by m_nomor desc" ;
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
        	<span class="add-on">Rekap By</span>
            <select name="kdby" id="kdby" class="input-large">
				<option value="m_cabang" >CABANG</option>
				<option value="m_group" >PRODUCT</option>
				<option value="m_kategori" >KATEGORI</option>
				<option value="m_item" >ITEM</option>
            </select>
        </div>
        
	</div>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Group </span>
            <select name="kdgroup" id="kdgroup" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlgroup = "select distinct a.m_kodebarang, b.m_nama from t_stockinv a, msbarang b where a.m_kodebarang= b.m_kode order by a.m_kodebarang asc" ;
				$stmtgroup = sqlsrv_query( $con_dbnew, $tsqlgroup );
                while( $rowgroup = sqlsrv_fetch_array( $stmtgroup, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowgroup['m_kodebarang']; ?>" <?php if($kdgroup == $rowgroup['m_kodebarang']){?> selected <?php } ?>><?php echo $rowgroup['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">Kategori </span>
            <select name="kdkatg" id="kdkatg" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlkatg = "select distinct b.m_kategori, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'CATEGORY' and b.m_kategori = c.m_kode order by b.m_kategori asc" ;
				$stmtkatg = sqlsrv_query( $con_dbnew, $tsqlkatg );
                while( $rowkatg = sqlsrv_fetch_array( $stmtkatg, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowkatg['m_kategori']; ?>" <?php if($kdkatg == $rowkatg['m_kategori']){?> selected <?php } ?>><?php echo $rowkatg['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">Item </span>
            <select name="kditem" id="kditem" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlitem = "select distinct b.m_item, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'ITEM' and b.m_item = c.m_kode order by b.m_item asc" ;
				$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem );
                while( $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowitem['m_item']; ?>" <?php if($kditem == $rowitem['m_item']){?> selected <?php } ?>><?php echo $rowitem['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">St.Stock</span>
            <select name="kdstock" id="kdstock" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlst = "select m_kode, m_nama from msmaster where m_type = 'STINV' order by m_kode asc" ;
				$stmtst = sqlsrv_query( $con_dbnew, $tsqlst );
                while( $rowst = sqlsrv_fetch_array( $stmtst, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowst['m_kode']; ?>" <?php if($kdstock == $rowst['m_kode']){?> selected <?php } ?>><?php echo $rowst['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>

    </div>

    <div class="container pull-left" style="width: auto; padding: 0 20px;">
        <span id="listdata">
            <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
            </table>
            <table id="table2" class="controller table table-bordered table-striped table-hover table-condensed">
            </table>
        </span>
    </div>
    
    <div class="container pull-left" style="width: auto; padding: 0 20px;">
        <span id="listplu">
		</span>
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
			var data={kdcab:$('#kdcabang').val()};
			var fungsi=function(respon){
					$("#listprd").html(respon);
					oc_periode();
				};
			$.get('opname-listprd.php',data,fungsi);
		}

		function oc_periode()
		{
			document.getElementById('soid').value = '' ;
			var data={kdcab:$('#kdcabang').val(), periode:$('#periode').val()};
			var fungsi=function(respon){
					$("#listsoid").html(respon);
				};
			$.get('opname-listsoid.php',data,fungsi);
		}

		function oc_report(vparam)
		{
			var data={cb:$('#kdcabang').val(),pr:$('#periode').val(),so:$('#soid').val(),prm:vparam,gr:$('#kdgroup').val(),kt:$('#kdkatg').val(),it:$('#kditem').val(),kdst:$('#kdstock').val(),kdby:$('#kdby').val()};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#listplu").html('');
				};
			$.get('report-opname1.php',data,fungsi);
		}
		

		function oc_detail(vparam,vstat,vkode)
		{
			var data={cb:$('#kdcabang').val(),pr:$('#periode').val(),so:$('#soid').val(),prm:vparam,gr:$('#kdgroup').val(),kt:$('#kdkatg').val(),it:$('#kditem').val(),kdst:$('#kdstock').val(),kdby:$('#kdby').val(),strep:vstat,vkode:vkode};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-opname2.php',data,fungsi);
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

		function print_data(kdcab,nomor)
		{
			window.open("opname-print.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor),'_blank');
		}
		
		function cetak1b (kdcab,kdgroup,kdkatg,kditem,kdstock,kdby,periode,soid,stat,vkode)
		{	
			window.open('report-opname2p.php?cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock+'&by='+kdby+'&pr='+periode+'&so='+soid+'&strep='+stat+'&vkode='+vkode,'_blank');
		}
		
		function exel1b (kdcab,kdgroup,kdkatg,kditem,kdstock,kdby,periode,soid,stat,vkode)
		{
			window.open('report-opname2x.php?cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock+'&by='+kdby+'&pr='+periode+'&so='+soid+'&strep='+stat+'&vkode='+vkode,'_blank');
		}
		
	</script>

    </body>
</html>