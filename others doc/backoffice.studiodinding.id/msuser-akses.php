<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$userid = base64_decode($_GET['lg']);	
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
		
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Akses USER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body onLoad="oc_akses('<?php echo $prm; ?>','<?php echo $userid; ?>')">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php" ;
		
		$tsql = "select a.* from msakses a where a.m_login = '".$userid."' and a.m_program = '".$kdprog."' order by m_kode asc " ;
		$stmt = $con_dbnew->query($tsql);

		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        
        <div class="span3 input-prepend">
		<?php
        if (substr($xparam[3],1,1) == 'Y')
        {
            ?>
        	<span class="add-on">Duplicate From</span>
            <input type="text" class="input-large" id="iddup" name="iddup" placeholder="Duplicate Akses from" value="" />
			<input type="button" class="btn" id="bt_dupl" value="Duplicate" onClick="oc_duplicate('<?php echo $prm; ?>','<?php echo $kdprog; ?>','<?php echo $userid; ?>')" />
            <?php
		}
		?>
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
		function oc_akses(vparam,login)
		{
			var data={cb:$('#kdprog').val(),lg:login, prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('msakses-list.php',data,fungsi);
		}
		
		function oc_duplicate(vparam, vkode)
		{
			var idfrom = $('#iddup').val();

			if (idfrom != '')
			{
				window.open("msakses-dupl.php?lg="+base64_encode(vkode)+"&fr="+base64_encode(idfrom)+"&prm="+base64_encode(vparam),'_self');
			}
		}
		
		function sync_modal(vparam, vkode)
		{
			window.open("msakses-sync.php?lg="+base64_encode(vkode)+"&prm="+base64_encode(vparam),'_self');
		}
		
		function edit_modal(vparam, vkode)
		{
			var data={kode:vkode, prog:vprog, prm:vparam};
			var fungsi=function(respon){
					$("#editdata").html(respon);
				};
			$.get('msakses-edit.php',data,fungsi);
			
			$('#edit_modal').modal();
		}

		function hapus_modal(vparam, vkode)
		{
			var data={kode:vkode, prog:vprog, prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msakses-hapus.php',data,fungsi);			
		}

		function cekall(vcek)
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			var oldcek = document.getElementById(vcek).value ;
			var newcek = true;
			var stat = '0';
			if (oldcek == 'Y'){ newcek = false; oldcek = 'T'} else {oldcek = 'Y' ;}
			
			for(var i=1; i <= jumrow; i++) 
			{	
				stat =  document.getElementById('m_status' + i).value ;
				if (stat != '2'){ document.getElementById(vcek + i).checked = newcek ; }
			}
			document.getElementById(vcek).value = oldcek ;
			return true ;
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			document.getElementById('jumrow').value = jumrow;
			
			return true ;
		}


	</script>

    </body>
</html>