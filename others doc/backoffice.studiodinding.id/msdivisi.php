<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	
	$kddept = base64_decode($_GET['dp']);
	$divisi = base64_decode($_GET['div']);
	echo $divisi;
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ($divisi == '')
	{
		$kddept = $_SESSION['dept'];
		$periode = date("Y-m");
	}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Point of Sales</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body onLoad="oc_msdivisi('<?php echo $prm; ?>','<?php echo $kddept; ?>','<?php echo $divisi; ?>')">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
		<div class="span3 input-prepend">
        	<span class="add-on">Dept</span>
            <select name="kddept" id="kddept" class="input-large" onChange="oc_msdivisi('<?php echo $prm; ?>','','')" >
            <option value="" selected >-------------</option>
                <?php
				$tsqldept = "select m_kode, m_nama from msdept  order by m_kode asc" ;
				$stmtdept = sqlsrv_query( $con_dbnew, $tsqldept);
                while( $rowdept = sqlsrv_fetch_array( $stmtdept, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowdept['m_kode']; ?>" <?php if($rowdept['m_kode'] == $kddept){ ?> <?php } ?> ><?php echo $rowdept['m_nama']; ?></option>
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
            <button class="btn" onClick="oc_msdivisi('<?php echo $prm; ?>','','')">Search</button>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
	            <button class="btn" onClick="edit_data('<?php echo $prm; ?>','','')">New</button>
                <?php
			}
			?>

        </div>
        
    </div>

    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="container pull-left" style="width:35%; padding: 0 20px">
            <span id="listdata" style="overflow:scroll">
            </span>
        </div>
        <div class="container pull-right" style="width:60%; padding: 0 10px;">
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
		function oc_msdivisi(vparam,vdept,vnomor)
		{
			var data={dp:$('#kddept').val(),by:$('#searchby').val(),tx:$('#inputText').val(),prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					if (vnomor == '')
					{
						$("#detaildata").html('');
					}
					else
					{
						kddept =vdept;
						kode = vnomor;
						oc_detail(vparam, kddept ,kode);
					}
				};
			$.get('msdivisi-list.php',data,fungsi);
		}

		function oc_detail(vparam, kddept,kode)
		{
			var data={dept:kddept,kd:kode,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('msdivisi-view.php',data,fungsi);
		}

		function view_modal(kdbrg,productid)
		{
			var data={kdbrg:kdbrg, productid:productid};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('product-info.php',data,fungsi);
			
			$('#view_modal').modal();
		}

		function edit_data(vparam,dept,kode)
		{
			if (dept == '')
			{
				var dept = $('#kddept').val() ;
			}
			
			window.open("msdivisi-edit.php?dp="+base64_encode(dept)+'&kd='+base64_encode(kode)+'&prm='+base64_encode(vparam),'_self');
		}

		function batal_msdivisi(vparam,dept,kode)
		{
			window.open("msdivisi-batal.php?dp="+base64_encode(dept)+'&kd='+base64_encode(kode)+'&prm='+base64_encode(vparam),'_self');
		}
		

	</script>

    </body>
</html>