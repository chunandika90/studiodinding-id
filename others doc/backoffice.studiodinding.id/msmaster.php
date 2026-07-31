<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$menu = base64_decode($_GET['m']);
	$akses = base64_decode($_GET['a']);
	$type = base64_decode($_GET['kd']);

	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master USER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select * from msmaster where m_status = 'A' and m_type = 'TYPE' order by m_nama asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		
		if ( $type == '' ) { $type = 'TYPE' ;}
		$tsql2 = "select * from msmaster where m_status = 'A' and m_type = '".$type."' order by m_nama asc " ;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span2 input-prepend">
        	<span class="add-on">List KODE </span>
            <select name="kdtype" id="kdtype" class="input-medium" onChange="oc_type('<?php echo $prm; ?>')">
                <?php
	            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
				{
					?>
					<option value="<?php echo $row['m_kode']; ?>" <?php if ($row['m_kode'] == $type) { ?> selected <?php }  ?>  ><?php echo $row['m_nama']; ?></option>
                    <?php
				}
                ?>
            </select>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
                <input type="button" class="btn" id="addbtn" value="Insert" onClick="editmaster_modal('<?php echo $prm; ?>','<?php echo $type ; ?>','')" />
				<?php
			}
			?>
        </div>
    </div>

    <div class="container" style="width: auto; padding: 0 20px;">
    	<div class="span6" style="overflow:auto;overflow-x:hidden;height:400px">        
            <span id="listuser">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><h4>KODE - <?php echo $type ; ?></h4></th>
                    </tr>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <tr>
                                <td onClick="viewmaster_modal('<?php echo $prm; ?>','<?php echo $type ; ?>','<?php echo $row2['m_kode']; ?>')" style="cursor:pointer"><?php echo $row2['m_kode']; ?></td>
                                <td><?php echo $row2['m_nama']; ?></td>
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
    <div id="dviewmaster_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="viewmaster_modal">
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

    <div id="deditmaster_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="editmaster_modal">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function oc_type(vparam)
		{
			var vkode = document.getElementById('kdtype').value ;
			window.open('msmaster.php?kd='+base64_encode(vkode)+'&prm='+base64_encode(vparam),'_self');			
		}
		
		function viewmaster_modal(vparam,vtype,vkode)
		{
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#viewmaster_modal").html(respon);
				};
			$.get('msmaster-view.php',data,fungsi);
			
			$('#dviewmaster_modal').modal();
		}
		
		function editmaster_modal(vparam,vtype,vkode)
		{
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#editmaster_modal").html(respon);
				};
			$.get('msmaster-edit.php',data,fungsi);
			
			$('#deditmaster_modal').modal();
		}

		function hapusmaster_modal(vparam,vtype,vkode)
		{
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msmaster-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>