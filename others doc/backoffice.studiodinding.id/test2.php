<?php
	session_start();
	$type = base64_decode($_GET['kd']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Navigation Bar Responsive</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body>
    <?php
        include "mainmenu.php" ;
        include "mssql-dbnew.php" ;
		
		$tsql = "select * from msmaster where m_status = 'A' and m_type = 'TYPE' order by m_kode asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		
		if ( $type == '' ) { $type = 'TYPE' ;}
		$tsql2 = "select * from msmaster where m_status = 'A' and m_type = '".$type."' order by m_kode asc " ;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
		
    ?>
	<div class="span11">
        <div class="span4">
            <select name="kdmaster" id="kdmaster" class="input-medium" onChange="fkodemaster()">
				<?php
	            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
				{
					?>
					<option value="<?php echo $row['m_kode']; ?>" <?php if ($row['m_kode'] == $type) { ?> selected <?php }  ?>  ><?php echo $row['m_nama']; ?></option>
                    <?php
				}
				?>
            </select>
        </div>
    </div>

	<div class="span4 offset1">
        <table class="table table-bordered table-striped table-hover table-condensed">
            <thead>
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
                            <td><?php echo $row2['m_kode']; ?></td>
                            <td><?php echo $row2['m_nama']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
            </tbody>
        </table>
    </div>

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function fkodemaster()
		{
			var vkode = document.getElementById('kdmaster').value ;
			window.open('test2.php?kd='+base64_encode(vkode),'_self');			
		}
	</script>

    </body>
</html>