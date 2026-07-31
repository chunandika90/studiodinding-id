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
	$kdshape = 'ALL';
	$kdplu = '';

	$tgl1 = date("01/m/Y");
	$tgl2 = date("d/m/Y");
	$kdby = 'm_item';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Setting Batu</title>
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
	<table>
    	<tr>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Tanggal</span>
                    <div id="datetimepicker1" class="input-append date">
                        <input class="input-small" data-format="dd/MM/yyyy" type="text" id="tanggal1" name="tanggal1" value="<?php echo $tgl1 ; ?>"/>
                        <span class="add-on">
                            <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                        </span>
                    </div>
                     <div id="datetimepicker2" class="input-append date">
                        <input class="input-small" data-format="dd/MM/yyyy" type="text" id="tanggal2" name="tanggal2" value="<?php echo $tgl2 ; ?>"/>
                        <span class="add-on">
                            <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                        </span>
                    </div>
                </div>
            </td>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Cabang </span>
                    <select name="kdcabang" id="kdcabang" class="input-medium" onChange="oc_cabang()">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlcabang = "select a.m_kode, a.m_nama from mslokasi a where a.m_kode = 'PUSAT-SK' order by a.m_kode asc" ;
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
        	<td>
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
        	<td >
                <div class="input-prepend">
                    <span class="add-on">Size</span>
                    <select name="kdsize" id="kdsize" class="input-medium">
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
        </tr>
       <tr> 
       
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Dimensi</span>
                    <input class="input-medium" type="text" id="dimensi" name="dimensi" value=""/>
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Group By</span>
                    <select name="reportby" id="reportby" class="input-medium">
                        <option value="01" selected>Lokasi TO</option>
                        <option value="02" >Tukang</option>
                        <option value="03" >Shape</option>
                        <option value="04" >Size</option>
                        <option value="05" >Dimensi</option>
                    </select>            
                </div>
            </td>
            <td >
            	
                <div class="btn-group">
                	<button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-danger btn-sm" id="search-record" onClick="f_export()">
                        Export Excel 
                    </button>
                </div>
            </td>
        </tr>
    </table>    
    </div>

    <div class="container pull-left" style="width: 60%; padding: 0 20px;">
        <span id="listdata">

        </span>
    </div>

    <div class="container pull-left" style="width: auto; padding: 0 20px;">
        <span id="listplu">

		</span>
    </div>
    
    <!-- Modal -->
    <div id="view_modal">
        <span id="viewdata">
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
			
			$( "#view_modal" ).dialog({
				autoOpen: false,
				resizable: false,
				width:700,
				modal: true,
				position: "center top",
				buttons: {
				"CLOSE": function() {
					$( this ).dialog( "close" );
				}
				}
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
			
			var tgl1 = $('#tanggal1').val() ;
			var tgl2 = $('#tanggal2').val() ;
			var reportby = $('#reportby').val() ;
			
			var data={tg1:tgl1,tg2:tgl2,kdcabang:kdcabang,kdshape:kdshape,kdsize:kdsize,dimensi:dimensi,by:reportby,prm:vparam};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#listplu").html('');
				};
			$.get('laporan-transferpb1.php',data,fungsi);
		}

		function oc_detail(detby, kd)
		{
			var tgl1 = $('#tanggal1').val() ;
			var tgl2 = $('#tanggal2').val() ;
			
			var kdcabang= $('#kdcabang').val() ;
			var kdshape = $('#kdshape').val() ;
			var kdsize = $('#kdsize').val() ;
			var dimensi = $('#dimensi').val() ;
			
			
			var kdby = '99';
			
			
			var data={kdcabang:kdcabang,kdshape:kdshape,kdsize:kdsize,dimensi:dimensi,kdby:kdby,tg1:tgl1,tg2:tgl2,kd:kd,detby:detby};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('laporan-transferpb2.php',data,fungsi);
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
			
			window.open('transferpb-export1a.php?tgl1='+tgl1+'&tgl2='+tgl2+'&kdcabang='+kdcabang+'&kdshape='+kdshape+'&kdsize='+kdsize+'&dimensi='+dimensi+'&kdby='+kdby,'_blank');
		}
		
		
		function f_export2()
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
			var kdtype = $('#kdtype').val() ;
			
			var kdby = '99';
			
			window.open('ttb-export1b.php?tgl1='+tgl1+'&tgl2='+tgl2+'&kdcabang='+kdcabang+'&kdgroup='+kdgroup+'&kditem='+kditem+'&kdplu='+kdplu+'&rubberid='+rubberid+'&kodesupplier='+kodesupplier+'&kdsupplier='+kdsupplier+'&kdby='+kdby+'&kdtype='+kdtype,'_blank');
		}

		
		function view_modal(kdbrg,productid)
		{
			var data={kdbrg:kdbrg, productid:productid};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('product-info.php',data,fungsi);
			
			$( "#view_modal" ).dialog( "open" );
		}

		function view_cust(kdcust)
		{
			var data={kc:kdcust};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('customer-info.php',data,fungsi);
			
			$( "#view_modal" ).dialog( "open" );
		}

		function cetak1b (tgl1,tgl2,kdcab,kdgroup,kditem,kdplu,reportby)
		{	
			window.open('laporan-transferpbp.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&it='+kditem+'&by='+reportby,'_blank');
		}
		
		function exel1b (tgl1,tgl2,kdcab,kdgroup,kditem,kdplu,reportby)
		{
			window.open('laporan-transferpbx.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&it='+kditem+'&by='+reportby,'_blank');
		}
		
		function cetak1c (tgl1,tgl2,kdcab,kdgroup,kditem,kdplu,reportby,vkode,vnama)
		{	
			window.open('laporan-transferpb2p.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&it='+kditem+'&pl='+kdplu+'&by='+reportby+'&vkode='+vkode+'&vnama='+vnama);
		}
		
		function exel1c (tgl1,tgl2,kdcab,kdgroup,kditem,kdplu,reportby,vkode,vnama)
		{	
			window.open('laporan-transferpb2x.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&it='+kditem+'&pl='+kdplu+'&by='+reportby+'&vkode='+vkode+'&vnama='+vnama);
		}
		
	</script>

    </body>
</html>