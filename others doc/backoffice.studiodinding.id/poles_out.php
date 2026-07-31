<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	$kdcabang = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	
	
	
	$nomor = base64_decode($_GET['nm']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ($kdcabang == '')
	{
		$kdcabang = $_SESSION['cabang'];
		$periode = date("Y-m");
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Pengeluaran Poles</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body onLoad="oc_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>')">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">

        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_barang_out where m_status = 'A'" ;
				if ($kdcabang != ''){ $tsql = $tsql." and m_cabang = '".$kdcabang."' "; }
				$tsqlbulan = $tsqlbulan." order by co_periode desc" ;
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
            <input type="text" class="input-large search-query" id="inputText" placeholder="Search Text" value="" />
            <select name="searchby" id="searchby" class="input-medium">
                <option value="nomor" >Nomor</option>
                <option value="nama" >Nama</option>
            </select>
            <button class="btn" onClick="oc_pos('<?php echo $prm; ?>','')">Search</button>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
            	<button class="btn" onClick="edit_data('<?php echo $prm; ?>','<?php echo $kdcabang; ?>','')">
            	New Keluar Poles
       	  </button>
				<?php
			}
			?>
        </div>
        
    </div>

    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="container pull-left" style="width:30%; padding: 0 20px;">
            <span id="listdata">
            </span>
        </div>
        <div class="container pull-right" style="width:65%; padding: 0 10px;">
            <span id="detaildata">
            </span>
        </div>
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

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function oc_pos(vparam,vnomor)
		{
			var kdcab = $('#kdcabang').val();
			var data={cb:kdcab,pr:$('#periode').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};

			var fungsi=function(respon){
					$("#listdata").html(respon);
					if (vnomor == '')
					{
						$("#detaildata").html('');
					}
					else
					{
						oc_detail(vparam,kdcab,vnomor);
					}
				};
			$.get('poles_out-list.php',data,fungsi);
		}

		function oc_detail(vparam,kdcab,nomor)
		{
			var data={cb:kdcab,nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('poles_out-view.php',data,fungsi);
		}

		
		function hapus_data(vparam,kdcab,nomor)
		{
			var pr = $('#periode').val() ;
			window.open("poles_out-hapus.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&pr='+base64_encode(pr)+'&prm='+base64_encode(vparam),'_self');
		}

		function edit_data(vparam,kdcab,nomor)
		{
			var pr = $('#periode').val() ;
			window.open("poles_out-edit.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&pr='+base64_encode(pr)+'&prm='+base64_encode(vparam),'_self');
		}

		function print_all(kdcab,nomor)
		{
			window.open("poles_out-print.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor),'_blank');
		}
		
		
		
		
	</script>

    </body>
</html>