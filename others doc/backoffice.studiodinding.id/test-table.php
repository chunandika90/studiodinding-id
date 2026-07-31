<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link href="css/tabelizer.min.css" media="all" rel="stylesheet" type="text/css" />    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <title>Untitled Document</title>

</head>

<body>
    <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">    
        <tr data-level="header" class="header">
        	<td>Group</td>
            <td><div align="right">Qty</div></td>
            <td><div align="right">Total</div></td>
		</tr>
	<?php
		include "mssql-dbnew.php";
		$tsql = "select * from dbo.f_reportstock('ALL')" ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$i = 0 ;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
		{	
			$i = $i + 1 ;
			?>
            <tr data-level="<?php echo $row['vf_level']; ?>" id="rowke<?php echo $i; ?>">
            	<td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                <td class="data"><div align="right"><?php echo $row['vf_qty']; ?></div></td>
                <td class="data"><div align="right"><?php echo $row['vf_total']; ?></div></td>
            </tr>            
            <?php
		}
    ?>
    </table>

	<script src="js/jquery-1.10.2.js"></script>
    <script src="js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="js/jquery.tabelizer.js"></script>
        
    <script>
    $(document).ready(function(){
        var table1 = $('#table1').tabelize({
            /*onRowClick : function(){
                alert('test');
            }*/
            fullRowClickable : true,
            onReady : function(){
                console.log('ready');
            },
            onBeforeRowClick :  function(){
                console.log('onBeforeRowClick');
            },
            onAfterRowClick :  function(){
                console.log('onAfterRowClick');
            },
        });
    });
    </script>

</body>
</html>