<?php
	session_start();
	$periode = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	if ( $periode =='' ){date("Y-m");}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>HARGA LM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;

		$dumb = explode('-',$periode);

		$tsql2 = "	select *, convert(varchar(10),m_tanggal,103) as co_tgl, convert(varchar(10),m_tanggal,108) as co_jam 
					from mshargalm 
					where year(m_tanggal) = ".$dumb[0]." and month(m_tanggal) = ".$dumb[1]." 
					order by m_tanggal desc" ;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from mshargalm order by co_periode desc" ;
				$stmtbulan = sqlsrv_query( $con_dbnew, $tsqlbulan);
                while( $rowbulan = sqlsrv_fetch_array( $stmtbulan, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbulan['co_periode']; ?>" <?php if($rowbulan['co_periode'] == $periode){ ?> selected <?php } ?> ><?php echo $rowbulan['co_periode']; ?></option>
                    <?php
                }
                ?>
            </select>
            <input type="button" class="btn" id="searchbtn" value="Display" onClick="oc_type('<?php echo $prm ; ?>')" />
            <input type="button" class="btn" id="addbtn" value="Insert" onClick="edit_data('<?php echo $prm ; ?>','')" />
        </div>
    </div>

    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="container pull-left" style="width:25%; padding: 0 20px;">
            <span id="listdata">
            </span>
        </div>
        <div class="container pull-right" style="width:65%; padding: 0 10px;">
            <span id="detaildata">
            </span>
        </div>
	</div>

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">

		function oc_type(vparam)
		{
			var vperiod = document.getElementById('periode').value ;
			var data={pr:vperiod,prm:vparam};

			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#detaildata").html('');
				};
			$.get('mshargalm-list.php',data,fungsi);
		}

		function oc_detail(vparam, vnomor)
		{
			var data={nm:vnomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('mshargalm-view.php',data,fungsi);
		}

		function edit_data(vparam,nomor)
		{
			var vperiod = document.getElementById('periode').value ;
			window.open("mshargalm-edit.php?nm="+base64_encode(nomor)+'&prm='+base64_encode(vparam)+'&pr='+base64_encode(vperiod),'_self');
		}

		function batal_data(vparam,nomor)
		{
			var data={nm:nomor,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('mshargalm-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>