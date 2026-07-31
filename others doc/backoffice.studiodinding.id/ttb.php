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
        <title>Penerimaan Konsinyasi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">

    </head>

    <body onLoad="oc_ttb('<?php echo $prm; ?>','<?php echo $nomor; ?>')">
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php";
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
		<div class="span3 input-prepend">
        	<span class="add-on">Store</span>
            <select name="kdcabang" id="kdcabang" class="input-large" >
                <?php
				$tsqlstore = "select m_kode, m_nama from mscabang order by m_kode asc" ;
				$stmtstore = sqlsrv_query( $con_dbnew, $tsqlstore);
                while( $rowstore = sqlsrv_fetch_array( $stmtstore, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowstore['m_kode']; ?>" <?php if($rowstore['m_kode'] == $kdcabang){ ?> selected <?php } ?> ><?php echo $rowstore['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_ttb where m_status = 'A'" ;
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
            <button class="btn" onClick="oc_ttb('<?php echo $prm; ?>','')">Search</button>
            <?php
			if (substr($xparam[3],0,1) == 'Y')
			{
				?>
            	<button class="btn" onClick="edit_data('<?php echo $prm; ?>','<?php echo $kdcabang; ?>','')">New Receive</button>
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
    
    <div id="edit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="editspan">
        
        </span>
    </div>   
    
   <div id="edit_spec" class="modal hide fade"  style="wid"tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="edit_spec">
           
        </span>
    </div>          

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
	
		
		function oc_ttb(vparam,vnomor)
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
			$.get('ttb-list.php',data,fungsi);
		}

		function oc_detail(vparam,kdcab,nomor)
		{
			var data={cb:kdcab,nm:nomor,prm:vparam};
			var fungsi=function(respon){
					$("#detaildata").html(respon);
				};
			$.get('ttb-view.php',data,fungsi);
		}

		function edit_spec(param,nomor,productid)
		{
			var periode = $('#periode').val() ;
			var kdcabang = $('#kdcabang').val() ;
			
			var data={param:param, nomor:nomor, productid:productid, periode:periode, kdcabang:kdcabang};
			var fungsi=function(respon){
					$("#edit_spec").html(respon);
				};
			$.get('edit-spec-ttb.php',data,fungsi);
			
			$('#edit_spec').modal();
		}
		

		function hapus_data(vparam,kdcab,nomor)
		{
			var data={cb:kdcab,nm:nomor,pr:$('#periode').val(),prm:vparam};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
					oc_detail(kdcab,nomor);
				};
			$.get('ttb-hapus.php',data,fungsi);
			
			$('#view_modal').modal();
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

		function edit_data(vparam,kdcab,nomor)
		{
			var pr = $('#periode').val() ;
			window.open("ttb-edit.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&pr='+base64_encode(pr)+'&prm='+base64_encode(vparam),'_self');
		}

		function print_all(kdcab,nomor)
		{
			window.open("ttb-printall.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor),'_blank');
		}

		function print_data(kdcab,nomor,kdbrg,productid)
		{
			window.open("ttb-print.php?cb="+base64_encode(kdcab)+'&nm='+base64_encode(nomor)+'&kdbrg='+base64_encode(kdbrg)+'&productid='+base64_encode(productid),'_blank');
		}
		
		function addRowToTable(aed)
		{
			
				var tbl = document.getElementById('table_spec');
				var lastRow = tbl.rows.length;
				// if there's no header row in the table, then iteration = lastRow + 1
				var iteration = lastRow;
				var row = tbl.insertRow(lastRow);
				
				var cellno = row.insertCell(0);
				cellno.align="center";
				cellno.innerHTML=iteration;
				
				var cellbutir = row.insertCell(1);
				cellbutir.valign="top";
				cellbutir.innerHTML='<td><div align="left"><input class="input-mini" type="text" id="m_butir'+iteration+'" name="m_butir'+iteration+'" value="0" style="text-align:right" onChange="recalc2()"/></div></td>';
				
				var cellcarat = row.insertCell(2);
				cellcarat.valign="top";
		  		cellcarat.innerHTML='<td><div align="left"><input class="input-mini" type="text" id="m_carat'+iteration+'" name="m_carat'+iteration+'" value="0" style="text-align:right" onChange="recalc2()" /></div></td>';
				
			  var cellcarat = row.insertCell(3);
			  cellcarat.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		}
		
		function validasi()
		{
			
			var tbl = document.getElementById('table_spec');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow -1;
			
			document.getElementById('jumrow').value = jumrow;
			
			return true ;
		}
		
		
		
		function recalc2()
		{
			var tbl = document.getElementById('table_spec');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow ;
			var tbutir = 0 ;
			var tcarat = 0 ;
			
			for(var i=1; i <= jumrow; i++) 
			{	
				
				var butir = Number(document.getElementById('m_butir' + i).value.replace(/,/g,""));
				var carat = Number(document.getElementById('m_carat' + i).value.replace(/,/g,""));
				
				tbutir = tbutir + butir  ;
				tcarat = tcarat + carat  ;
				
				document.getElementById('m_butir' + i).value = formatangka(butir.toFixed().toString()) ;
				document.getElementById('m_carat' + i).value = formatangka(carat.toFixed(3).toString()) ;
			}
			return true ;
		}
		
		

	</script>

    </body>
</html>