<?php
	session_start();
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master Project</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
	
		$tsql2 = "	select *
					from master_project 
					order by m_kode asc" ;
	
		
		//echo $tsq12;
		$stmt2 = $con_dbnew->query($tsql2);
		
		
    ?>

    <div class="container-fluid" padding: 0 10px;">
		<div style="overflow:auto; overflow-x:auto; height:800px; width:80%;">
            <span id="listuser">
			<?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
				<button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">Tambah Project</button>
				<?php
			}
			?></th>
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4>Master Project</h4></th>
                    </tr>
                    
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="15%">Nama Project</th>
                        <th width="10%">Tanggal Project</th>
                        <th width="10%">Supervisor Project </br> Nomor Telepon </th>
                        <th width="10%">Nama Client</th>
                        <th width="10%">Lokasi</th>
                        <th width="15%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0;
                        while($row2 = $stmt2->fetch_assoc())
                        {
							$i++;
                            ?>
                            <tr>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $i; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['m_kode']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['nama_project']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['tanggal_mulai_project']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['supervisor_project'] ."</br> Tlp : ".$row2['m_telepon_spv'] ; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['nama_client']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['m_lokasi']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['m_alamat']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                </tbody>
            </table>
            </span>
        </div>
    </div>

    <!-- Modal -->
    <div id="dview_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="view_modal">
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

    <div id="dedit_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content" id="edit_modal">
		  <!-- dynamic content loaded via JS -->
		</div>
	  </div>
	</div>       



    <div id="dialog-listshape">
        <span id="datashape">
        </span>
    </div>
    
    <style>
	#listuser table {
		width: 100% !important;
		table-layout: auto; /* optional supaya kolom tetap proporsional */
	}
	</style>

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">



		$(function() {
		$( "#dialog-listshape" ).dialog({
			autoOpen: false,
			height:900,
			width:600,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		

		function oc_type(vparam)
		{
			
			var vkode = document.getElementById('kdtype').value ;
			
			var vperiod = document.getElementById('periode').value ;
			window.open('master_project.php?kd='+base64_encode(vkode)+'&pr='+base64_encode(vperiod)+'&prm='+base64_encode(vparam),'_self');			
		}
		
		function view_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#view_modal").html(respon);
				};
			$.get('master_project-view.php',data,fungsi);
			
			$('#dview_modal').modal();
		}
		
		function edit_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#edit_modal").html(respon);
				};
			$.get('master_project-edit.php',data,fungsi);
			
			$('#dedit_modal').modal();
		}

		function hapus_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('master_project-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>