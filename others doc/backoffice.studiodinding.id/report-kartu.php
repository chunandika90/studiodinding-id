<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>INFO PRODUCT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body>
    <?php
		$prm = base64_decode($_GET['prm']);
		$xparam = explode('/',$prm);
		
        include "mssql-dbnew.php" ;
        include "menu-pos2.php" ;
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">

        <div class="container input-append" style="width: auto; padding: 0 10px;">
            <input type="text" class="input-large search-query" id="inputText" placeholder="No.PLU / Kode Barang" value="" onChange="oc_search()"/>
            <button class="btn" onClick="oc_search()">Search</button>
        </div>
        
    </div>

    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="container pull-left" style="width:auto; padding: 0 20px;">
            <span id="listdata">
            </span>
        </div>
	</div>
    
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function oc_search()
		{
			var data={plu:$('#inputText').val()};

			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('report-kartu2.php',data,fungsi);
		}

	</script>

    </body>
</html>