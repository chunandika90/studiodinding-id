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
	<table width="100%">
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
                    <span class="add-on">Group </span>
                    <select name="kdgroup" id="kdgroup" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlgroup = "select distinct a.m_kodebarang, b.m_nama from t_stockinv a, msbarang b where a.m_kodebarang= b.m_kode order by a.m_kodebarang asc" ;
                        $stmtgroup = sqlsrv_query( $con_dbnew, $tsqlgroup );
                        while( $rowgroup = sqlsrv_fetch_array( $stmtgroup, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowgroup['m_kodebarang']; ?>" <?php if($kdgroup == $rowgroup['m_kodebarang']){?> selected <?php } ?>><?php echo $rowgroup['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
            <td>
            	<div class="input-prepend">
                    <span class="add-on">Item </span>
                    <select name="kditem" id="kditem" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlitem = "select distinct b.m_item, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'ITEM' and b.m_item = c.m_kode order by b.m_item asc" ;
                        $stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem );
                        while( $rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowitem['m_item']; ?>" <?php if($kditem == $rowitem['m_item']){?> selected <?php } ?>><?php echo $rowitem['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
            
            <td>
                <div class="input-prepend">
                    <span class="add-on">Supplier </span>
                    <select name="kdsupplier" id="kdsupplier" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlsup = "select distinct b.m_supplier, c.m_nama from t_stockinv a, t_stockdata b, mssupplier c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and b.m_supplier = c.m_kode and a.m_qty >0 order by b.m_supplier asc" ;
                        $stmtsup = sqlsrv_query( $con_dbnew, $tsqlsup );
                        while( $rowsup = sqlsrv_fetch_array( $stmtsup, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowsup['m_supplier']; ?>"><?php echo $rowsup['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
        </tr>
    	<tr>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">No.PLU</span>
                    <input class="input-medium" type="text" id="noplu" name="noplu" value="<?php echo $kdplu ; ?>"/>
                </div>
            </td>
            
            <td>
            
                <div class="input-prepend">
                    <span class="add-on">Kode Barang</span>
                    <input class="input-medium" type="text" id="rubberid" name="rubberid" value="<?php echo $kdplu ; ?>"/>
                </div>
            </td>
            <td>
            
                <div class="input-prepend">
                    <span class="add-on">Kode Supplier</span>
                    <input class="input-medium" type="text" id="kodesupplier" name="kodesupplier" value="<?php echo $kdplu ; ?>"/>
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Group By</span>
                    <select name="reportby" id="reportby" class="input-medium">
                        <option value="01" >Group Product</option>
                        <option value="02">Store</option>
                        <option value="03" selected>Item</option>
                        <option value="04" >Supplier</option>
                        <option value="05" >Designer</option>
                    </select>            
                </div>
            </td>
            <td colspan="2">
                <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
                
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" id="search-record" onClick="f_export()">
                    Export Excel 
                </button>
            </td>
        </tr>
    </table>    
    </div>

    <div class="container pull-left" style="width: 95%; padding: 0 20px;">
        <span id="listdata">

        </span>
    </div>

    <div class="container pull-left" style="width: auto; padding: 0 20px;">
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
			var data= $('#kdcabang').val() ;
			var kdgroup = $('#kdgroup').val() ;
			var kditem = $('#kditem').val() ;
			var kdplu = $('#noplu').val() ;
			var rubberid = $('#rubberid').val() ;
			var kodesupplier = $('#kodesupplier').val() ;
			var kdsupplier = $('#kdsupplier').val() ;
			var reportby = $('#reportby').val() ;
			
			var data={cb:data,gr:kdgroup,it:kditem,kdplu:kdplu,rubberid:rubberid,kodesupplier:kodesupplier,kdsupplier:kdsupplier,by:reportby,prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#listplu").html('');
				};
			$.get('laporan-stock1.php',data,fungsi);
		}

		function oc_detail(detby, kd)
		{
				
			
			var kdcabang= $('#kdcabang').val() ;
			var kdgroup = $('#kdgroup').val() ;
			var kditem = $('#kditem').val() ;
			var kdplu = $('#noplu').val() ;
			var rubberid = $('#rubberid').val() ;
			var kodesupplier = $('#kodesupplier').val() ;
			var kdsupplier = $('#kdsupplier').val() ;
			
			var kdby = '99';
			
			
			var data={kdcabang:kdcabang,kdgroup:kdgroup,kditem:kditem,kdplu:kdplu,rubberid:rubberid,kodesupplier:kodesupplier,kdsupplier:kdsupplier,kdby:kdby,kd:kd,detby:detby};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('laporan-stock2.php',data,fungsi);
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
			var kdgroup = $('#kdgroup').val() ;
			var kditem = $('#kditem').val() ;
			var kdplu = $('#noplu').val() ;
			var rubberid = $('#rubberid').val() ;
			var kodesupplier = $('#kodesupplier').val() ;
			var kdsupplier = $('#kdsupplier').val() ;
			
			var kdby = '99';
			
			window.open('stock-export1a.php?tgl1='+tgl1+'&tgl2='+tgl2+'&kdcabang='+kdcabang+'&kdgroup='+kdgroup+'&kditem='+kditem+'&kdplu='+kdplu+'&rubberid='+rubberid+'&kodesupplier='+kodesupplier+'&kdsupplier='+kdsupplier+'&kdby='+kdby,'_blank');
		}
		
	</script>

    </body>
</html>