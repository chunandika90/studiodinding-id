<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdprogram = base64_decode($_GET['cb']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	if ($kdprogram == ''){$kdprogram = '01';}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master MENU</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body onLoad="oc_menu('<?php echo $prm; ?>')">
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select a.* from msmenu a where a.m_program = '".$kdprogram."' order by m_kode asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);

		$tsqlprog = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'PROGRAM' order by a.m_kode asc" ;
		$stmtprog = sqlsrv_query( $con_dbnew, $tsqlprog );

    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span2 input-prepend">
        	<span class="add-on">Program </span>
            <select name="kdprog" id="kdprog" class="input-large" onChange="oc_menu('<?php echo $prm; ?>')">
                <?php
                while( $rowprog = sqlsrv_fetch_array( $stmtprog, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowprog['m_kode']; ?>"><?php echo $rowprog['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="container pull-left" style="width: 70%; padding: 0 20px;">
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
		function oc_menu(vparam)
		{
			var data={cb:$('#kdprog').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('msmenu-list.php',data,fungsi);
		}
		
		function view_modal(vparam, vprog, vkode)
		{
			var data={kode:vkode, prog:vprog,prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('msmenu-view.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function edit_modal(vparam, vprog, vkode)
		{
			var data={kode:vkode, prog:vprog,prm:vparam};
			var fungsi=function(respon){
					$("#editdata").html(respon);
				};
			$.get('msmenu-edit.php',data,fungsi);
			
			$('#edit_modal').modal();
		}

		function hapus_modal(vparam, vprog, vkode)
		{
			var data={kode:vkode, prog:vprog,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msmenu-hapus.php',data,fungsi);			
		}

	</script>

    </body>
</html>