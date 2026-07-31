<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
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
		
		$tsql = "select a.*, 
				 case when m_group = '01' THEN 'Procurement ' 
					  when m_group = '02' THEN 'Finance'
					  when m_group = '03' THEN 'Supervisor Project'
					  when m_group = '04' THEN 'Admin'
					  when m_group = '00' THEN 'Direktur'
					  else '' end m_group
					  
				 from msuser a where  a.m_status = 'A'  " ;
		$tsql = $tsql." order by a.m_group asc " ;
		$stmt = $con_dbnew->query($tsql);
		
		//echo $tsql ."<br>"; 


    ?>
    

    <div class="container" style="overflow:auto;overflow-x:hidden;height:500px;width:auto">
        <span id="listuser">
        <table class="table table-bordered table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th colspan="1"><h4>USER - LIST</h4></th>
                    <th colspan="2">
                        <div class="container input-append pull-right" style="width: 60%; padding: 0 10px;">
                            <input type="text" class="input-medium search-query" id="inputText" placeholder="Search Text" value="" onChange="oc_search()" />
                            <select name="searchby" id="searchby" class="input-small">
                                <option value="login" >login</option>
                                <option value="nama" >nama</option>
                            </select>
                            <button class="btn" onClick="oc_search('<?php echo $prm; ?>')">Search</button>
                            <button class="btn" onClick="edituser_modal('<?php echo $prm; ?>','')">New User</button>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>Login-ID</th>
                    <th>Nama</th>
                    <th>Group</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while( $row = $stmt->fetch_assoc())
                    {
                        ?>
                        <tr>
                            <td onClick="viewuser_modal('<?php echo $prm; ?>','<?php echo $row['m_login']; ?>')" style="cursor:pointer"><?php echo $row['m_login']; ?></td>
                            <td><?php echo $row['m_nama']; ?></td>
							<td><?php echo $row['m_group']; ?></td>
							
                        </tr>
                        <?php
                    }
                    ?>
            </tbody>

        </table>
        </span>
    </div>

    <!-- Modal -->
    <div id="viewuser_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="viewuser">
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

    <div id="edituser_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="edituser">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function oc_cabang(vparam)
		{
			oc_user(vparam);
		}
		

		function oc_search(vparam)
		{
			oc_user(vparam);
		}

		
		function oc_user(vparam)
		{
			var data={cb:$('#kdcabang').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listuser").html(respon);
				};
			$.get('msuser-list.php',data,fungsi);
		}
		
		function viewuser_modal(vparam,loginid)
		{
			var data={logid:loginid,prm:vparam};
			var fungsi=function(respon){
					$("#viewuser").html(respon);
				};
			$.get('msuser-view.php',data,fungsi);
			
			$('#viewuser_modal').modal();
		}
		
		function edituser_modal(vparam,loginid)
		{
			var data={logid:loginid,prm:vparam};
			var fungsi=function(respon){
					$("#edituser").html(respon);
				};
			$.get('msuser-edit.php',data,fungsi);
			
			$('#edituser_modal').modal();
		}

		function hapususer_modal(vparam,loginid)
		{
			var data={logid:loginid,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msuser-hapus.php',data,fungsi);
			
//			$('#edituser_modal').modal();
		}

		function aksesuser(vparam,loginid)
		{
			window.open("msuser-akses.php?lg="+base64_encode(loginid)+"&prm="+base64_encode(vparam),'_self');
		}


		
	</script>

    </body>
</html>