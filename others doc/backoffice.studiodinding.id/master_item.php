<?php
	session_start();
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master Item</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
	
		$tsql2 = "	select *
					from master_item 
					order by m_kode asc" ;
	
		
		//echo $tsq12;
		$stmt2 = $con_dbnew->query($tsql2);
		
		
    ?>

    <div class="container pull-left" style="width: 100%; padding: 0 20px;">
		<div class="span10" style="overflow:auto;overflow-x:hidden;height:800px">
            <span id="listuser">
				<input type="text" id="searchBox" class="form-control" placeholder="Search by Kode or Nama..." style="width: 70%; margin-bottom: 15px;" >
				<button class="btn btn-primary" onClick="searchItems()">Search</button>
			<?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
				<button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">Tambah Item</button>
				<?php
			}
			?></th>
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4>Master Item</h4></th>
                    </tr>
                    
                    <tr>
                        <th width="10%">No</th>
                        <th width="45%">Kode</th>
                        <th width="45%">Nama</th>
                    </tr>
                </thead>
                <tbody  id="tableBody">
                    <?php
						$i = 0;
                        while($row2 = $stmt2->fetch_assoc())
                        {
							$i++;
                            ?>
                            <tr>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $i; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['m_kode']; ?></td>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_kode'] ; ?>')" style="cursor:pointer"><?php echo $row2['m_nama']; ?></td>
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


    <style>
	#listuser table {
		width: 100% !important;
		table-layout: auto; /* optional supaya kolom tetap proporsional */
	}
	</style>

    <div id="dialog-listshape">
        <span id="datashape">
        </span>
    </div>
    

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">



		$(function() {
		$( "#dialog-listshape" ).dialog({
			autoOpen: false,
			height:800,
			width:300,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		// Search Function - Match partial text (LIKE SQL behavior)
		function searchItems() {
			var searchQuery = $('#searchBox').val().toLowerCase(); // Get the search query and convert to lowercase

			var tableBody = $('#tableBody'); // Get the tbody element
			var tableRows = tableBody.find('tr'); // Get all table rows inside tbody
			if (tableRows.length === 0) {
				alert('No rows found in the table body!');
				return; // Exit the function if no rows are found
			}

			// Loop through each row
			tableRows.each(function() {
				var kode = $(this).find('td:eq(1)').text().toLowerCase(); // Get 'Kode' column
				var nama = $(this).find('td:eq(2)').text().toLowerCase(); // Get 'Nama' column

				// Check if the search query is contained anywhere in 'Kode' or 'Nama' (partial match)
				if (kode.indexOf(searchQuery) > -1 || nama.indexOf(searchQuery) > -1) {
					$(this).show(); // Show row if it matches
				} else {
					$(this).hide(); // Hide row if it doesn't match
				}
			});
		}

		// Optional: Automatically search on input (Live Search)
		$(document).ready(function() {
			$('#searchBox').on('input', function() {
				searchItems(); // Call the search function as the user types
			});
		});

		function oc_type(vparam)
		{
			
			var vkode = document.getElementById('kdtype').value ;
			
			var vperiod = document.getElementById('periode').value ;
			window.open('master_item.php?kd='+base64_encode(vkode)+'&pr='+base64_encode(vperiod)+'&prm='+base64_encode(vparam),'_self');			
		}
		
		function view_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#view_modal").html(respon);
				};
			$.get('master_item-view.php',data,fungsi);
			
			$('#dview_modal').modal();
		}
		
		function edit_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#edit_modal").html(respon);
				};
			$.get('master_item-edit.php',data,fungsi);
			
			$('#dedit_modal').modal();
		}

		function hapus_modal(vparam,vkode)
		{
			var data={vkode:vkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('master_item-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>