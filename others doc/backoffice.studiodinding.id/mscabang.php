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
        <title>Master Cabang</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select a.* from mslokasi a order by a.m_kode " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <span id="listuser">
        <table class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th colspan="2">
                        <div class="pull-left">
                            <h4>Lokasi</h4>
						</div>
                        <div class="pull-right">
							<?php
                            if (substr($xparam[3],0,1) == 'Y')
                            {
                                ?>
                                <button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">Tambah Lokasi</button>
                                <?php
                            }
                            ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
                    {
                        ?>
                        <tr>
                            <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row['m_kode']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
                            <td><?php echo $row['m_nama']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
            </tbody>
        </table>
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
        <span id="editspan">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function view_modal(vparam,pkode)
		{
			var data={vkode:pkode,prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('mscabang-view.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function edit_modal(vparam,pkode)
		{
			var data={vkode:pkode,prm:vparam};
			var fungsi=function(respon){
					$("#editspan").html(respon);
				};
			$.get('mscabang-edit.php',data,fungsi);
			
			$('#edit_modal').modal();
		}

		function hapus_modal(vparam,pkode)
		{
			var data={vkode:pkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('mscabang-hapus.php',data,fungsi);
			
		}

	</script>

    </body>
</html>