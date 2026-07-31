<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['cb']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master SALES</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body onLoad="oc_sales('<?php echo $prm ; ?>')">
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select a.*, b.m_nama as namacabang from mssales a, msmaster b where b.m_type = 'STORE' and a.m_aktif = 1 and a.m_cabang = b.m_kode " ;
		if ($kdcabang != ''){ $tsql = $tsql." and a.m_cabang = '".$kdcabang."' "; }
		$tsql = $tsql." order by a.m_cabang asc, a.m_nama asc" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);

		$tsqlcabang = "select distinct a.m_cabang, b.m_nama from mssales a, msmaster b where b.m_type = 'STORE' and a.m_cabang = b.m_kode order by a.m_cabang asc" ;
		$stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);

    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span2 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_sales('<?php echo $prm ; ?>')">
				<option value="" >ALL</option>
                <?php
                while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcabang['m_cabang']; ?>" <?php if($rowcabang['m_cabang'] == $kdcabang){ ?> selected <?php } ?>><?php echo $rowcabang['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="container pull-left" style="width: 50%; padding: 0 20px;">
        <span id="listdata">
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

    <div id="edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="editdata">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function oc_sales(vparam)
		{
			var data={cb:$('#kdcabang').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('mssales-list.php',data,fungsi);
		}
		
		function view_modal(vparam,vkode)
		{
			var data={kode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('mssales-view.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function edit_modal(vparam,vkode)
		{
			var data={kode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#editdata").html(respon);
				};
			$.get('mssales-edit.php',data,fungsi);
			
			$('#edit_modal').modal();
		}

		function hapus_modal(vparam,vkode)
		{
			var data={kode:vkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('mssales-hapus.php',data,fungsi);			
		}

	</script>

    </body>
</html>