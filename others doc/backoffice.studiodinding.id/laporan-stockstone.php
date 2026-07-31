<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	$kdcabang = $_SESSION['store'];
	$kdgroup = 'ALL';
	$kditem = 'ALL';
	$kdplu = '';

	$kdby = 'm_group';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report STOCK</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	    <link href="css/tabelizer.min.css" media="all" rel="stylesheet" type="text/css" />    
        
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php" ;
		
		$abc = explode('/',$tgl1);
		$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
		$abc = explode('/',$tgl2);
		$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
	<table width="60%">
    	<tr>
        	<td width="25%">
                <div class="input-prepend">
                    <span class="add-on">Cabang </span>
                    <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_cabang()">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlcabang = "select a.m_kode, a.m_nama from mscabang a order by a.m_kode asc" ;
                        $stmtcabang = sqlsrv_query( $con_dbnew, $tsqlcabang);
                        while( $rowcabang = sqlsrv_fetch_array( $stmtcabang, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowcabang['m_kode']; ?>" <?php if($kdcabang == $rowcabang['m_kode']){?> selected <?php } ?>><?php echo $rowcabang['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
        	<td width="25%">
                <div class="input-prepend">
                    <span class="add-on">Shape</span>
                    <select name="kdshape" id="kdshape" class="input-medium">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlshape = "select distinct m_shape from t_stockstone  " ;
                        $stmtshape = sqlsrv_query( $con_dbnew, $tsqlshape );
                        while( $rowshape = sqlsrv_fetch_array( $stmtshape, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowitem['m_shape']; ?>" <?php if($kdshape == $rowshape['m_shape']){?> selected <?php } ?>><?php echo $rowshape['m_shape']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
        	<td width="25%">
                <div class="input-prepend">
                    <span class="add-on">Size</span>
                    <select name="kdsupplier" id="kdsupplier" class="input-medium">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlsize = "select distinct m_size from t_stockstone  " ;
                        $stmtsize = sqlsrv_query( $con_dbnew, $tsqlsize );
                        while( $rowsize = sqlsrv_fetch_array( $stmtsize, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowsize['m_size']; ?>"><?php echo $rowsize['m_size']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Dimensi</span>
                    <input class="input-medium" type="text" id="dimensi" name="dimensi" value=""/>
                </div>
            </td>
        </tr>
    	<tr>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Group By</span>
                    <select name="reportby" id="reportby" class="input-medium">
                        <option value="01" selected>Shape</option>
                    </select>            
                </div>
            </td>
            <td colspan="2">
                <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
                <button type="button" class="btn btn-danger btn-sm" id="search-record" onClick="f_export()">
                    Export Excel 
                </button>
                
            </td>
            <td>
            </td>
        </tr>
    </table>    
    </div>

    <div class="container pull-left" style="width: 40%; padding: 0 20px;">
        <span id="listdata">

        </span>
    </div>

    <div class="container pull-left" style="width: 40%; padding: 0 20px;">
        <span id="listplu">

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

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script src="js/jquery.tabelizer.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});

			$('#datetimepicker2').datetimepicker({
				language: 'en',
				pickTime: false
			});
			
		});

		function oc_cabang()
		{
			document.getElementById('kdsales').value = '' ;
			oc_sales();
		}
		
		function oc_sales()
		{
			var data={cb:$('#kdcabang').val()};
			var fungsi=function(respon){
					$("#listjr").html(respon);
				};
			$.get('laporan-listsales.php',data,fungsi);
		}

		function oc_report(vparam)
		{
			var kdcabang= $('#kdcabang').val() ;
			var kdshape = $('#kdshape').val() ;
			var kdsize = $('#kdsize').val() ;
			var dimensi = $('#dimensi').val() ;
			
			var reportby = $('#reportby').val() ;
			
			var data={kdcabang:kdcabang,kdshape:kdshape,kdsize:kdsize,dimensi:dimensi,by:reportby,prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#listplu").html('');
				};
			$.get('laporan-stockstone1.php',data,fungsi);
		}

		function oc_detail(detby, kd)
		{
				
			
			var kdcabang= $('#kdcabang').val() ;
			var kdshape = $('#kdshape').val() ;
			var kdsize = $('#kdsize').val() ;
			var dimensi = $('#dimensi').val() ;
			
			var kdby = '99';
			
			
			var data={kdcabang:kdcabang,kdshape:kdshape,kdsize:kdsize,dimensi:dimensi,kdby:kdby,kd:kd,detby:detby};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('laporan-stockstone2.php',data,fungsi);
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
		function f_export()
		{
			var tgl1 = $('#tanggal1').val() ;
			var tgl2 = $('#tanggal2').val() ;
			
			var kdcabang= $('#kdcabang').val() ;
			var kdshape = $('#kdshape').val() ;
			var kdsize = $('#kdsize').val() ;
			var dimensi = $('#dimensi').val() ;
			
			var kdby = '99';
			
			window.open('stock-export1a.php?tgl1='+tgl1+'&tgl2='+tgl2+'&kdcabang='+kdcabang+'&kdshape='+kdshape+'&kdsize='+kdsize+'&dimensi='+dimensi+'&kdby='+kdby,'_blank');
		}
		
	</script>

    </body>
</html>