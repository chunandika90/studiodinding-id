<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['st']);
	$periode = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	if ($periode == ''){ $periode = date("Y-m");}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master CUSTOMER</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
    </head>

    <body onLoad="oc_so()">
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;

    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_so('<?php echo $prm; ?>','')">
                <?php
				$tsqlcabang = "select m_kode, m_nama from msmaster where m_type = 'STORE' order by m_kode asc" ;
				$stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);
                while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcabang['m_kode']; ?>" <?php if ($rowcabang['m_kode'] == $kdcabang){ ?> selected="selected" <?php } ?> ><?php echo $rowcabang['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        
        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium" onChange="oc_so('<?php echo $prm; ?>')">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_stockopname0 order by co_periode desc" ;
				$stmtbulan = sqlsrv_query( $con_dbnew, $tsqlbulan);
                while( $rowbulan = sqlsrv_fetch_array( $stmtbulan, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbulan['co_periode']; ?>" <?php if($rowbulan['co_periode'] == $periode){ ?> selected <?php } ?> ><?php echo $rowbulan['co_periode']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="container input-append" style="width: auto; padding: 0 10px;">
            <button class="btn" onClick="getstock('<?php echo $prm; ?>')">New SO</button>
        </div>
        
    </div>

    <div class="container pull-left" style="width: 90%; padding: 0 20px;">
        <span id="listdata">
        </span>
    </div>

    <div id="dialog-confirm">
        <span id="editdata">
        </span>
    </div>

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$( "#dialog-confirm" ).dialog({
				autoOpen: false,
				resizable: false,
				height:300,
				width:500,
				modal: true	
				});
		});
	
		function oc_so(vparam)
		{
			var data={cb:$('#kdcabang').val(),pr:$('#periode').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
				};
			$.get('opname-getstock2.php',data,fungsi);
		}

		function getstock(vparam)
		{
			var data={cb:$('#kdcabang').val(),pr:$('#periode').val(),prm:vparam};
			var fungsi=function(respon){
					$("#editdata").html(respon);
				};
			$.get('opname-getstock3.php',data,fungsi);

			$( "#dialog-confirm" ).dialog( "open" );
		}

	</script>

    </body>
</html>