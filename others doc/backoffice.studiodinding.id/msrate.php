<?php
	session_start();
	$type = base64_decode($_GET['kd']);
	$periode = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	if ( $type == '' ) {$type = 'USD' ;}
	if ( $periode =='' ){date("Y-m");}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Rate Kurs</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;

		$dumb = explode('-',$periode);

		$tsql = "select m_kode, m_nama from msmaster where m_status = 'A' and m_type = 'KURS' order by m_nama asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		
		$tsql2 = "	select *, convert(varchar(30),m_tanggal,121) as co_tgl
					from msrate 
					where m_kode = '".$type."' and year(m_tanggal) = ".$dumb[0]."  and month(m_tanggal) = ".$dumb[1]."  
					order by m_tanggal desc" ;
					//echo $tsql2;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
		
		
		//echo $tsql2 ;
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">List KURS</span>
            <select name="kdtype" id="kdtype" class="input-medium" onChange="oc_kurs()">
                <?php
	            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
				{
					?>
					<option value="<?php echo $row['m_kode']; ?>" <?php if ($row['m_kode'] == $type) { ?> selected <?php }  ?>  ><?php echo $row['m_kode']; ?></option>
                    <?php
				}
                ?>
            </select>
        </div>
        <div class="span3 input-prepend">
        	<span class="add-on">Periode</span>
            <span id="listperiode" >
            <select name="periode" id="periode" class="input-medium">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from msrate order by co_periode desc" ;
				echo $tsqlbulan;
				$stmtbulan = sqlsrv_query( $con_dbnew, $tsqlbulan);
                while( $rowbulan = sqlsrv_fetch_array( $stmtbulan, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbulan['co_periode']; ?>" <?php if($rowbulan['co_periode'] == $periode){ ?> selected <?php } ?> ><?php echo $rowbulan['co_periode']; ?></option>
                    <?php
                }
                ?>
            </select>
            </span>
            <input type="button" class="btn" id="searchbtn" value="Display" onClick="oc_type('<?php echo $prm ; ?>','<?php echo $type ; ?>','')" />
			<?php
            if (substr($xparam[3],0,1) == 'Y')
            {
                ?>
	            <input type="button" class="btn" id="addbtn" value="Insert" onClick="edit_modal('<?php echo $prm ; ?>','','')" />
                <?php
			}
			?>
        </div>
    </div>

    <div class="container pull-left" style="width: 30%; padding: 0 20px;">
		<div class="span6" style="overflow:auto;overflow-x:hidden;height:400px">
            <span id="listuser">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="2"><h4>RATE KURS - <?php echo $type ; ?></h4></th>
                    </tr>
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="10%"><div class="pull-right">Beli</div></th>
                        <th width="10%"><div class="pull-right">Jual</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <tr>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $type ; ?>','<?php echo $row2['co_tgl']; ?>')" style="cursor:pointer"><?php echo $row2['co_tgl']; ?></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_beli'], 2, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_jual'], 2, '.', ','); ?></div></td>
                            </tr>
                            <?php
                        }
                        ?>
                </tbody>
            </table>
            </span>
        </div>
    </div>

    <!-- Modal -->
    <div id="dview_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="view_modal">
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

    <div id="dedit_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="edit_modal">
        </span>
    </div>         

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">

		function oc_kurs()
		{
			var data={kd:$('#kdtype').val()};
			var fungsi=function(respon){
					$("#listperiode").html(respon);
				};
			$.get('msrate-periode.php',data,fungsi);
		}

		function oc_type(vparam)
		{
			var vkode = document.getElementById('kdtype').value ;
			var vperiod = document.getElementById('periode').value ;
			window.open('msrate.php?kd='+base64_encode(vkode)+'&pr='+base64_encode(vperiod)+'&prm='+base64_encode(vparam),'_self');			
		}
		
		function view_modal(vparam,vtype,vkode)
		{
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#view_modal").html(respon);
				};
			$.get('msrate-view.php',data,fungsi);
			
			$('#dview_modal').modal();
		}
		
		function edit_modal(vparam,vtype,vkode)
		{
			var vtype = document.getElementById('kdtype').value ;
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					$("#edit_modal").html(respon);
				};
			$.get('msrate-edit.php',data,fungsi);
			
			$('#dedit_modal').modal();
		}

		function hapus_modal(vparam,vtype,vkode)
		{
			var vtype = document.getElementById('kdtype').value ;
			var data={ty:vtype,kd:vkode,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msrate-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>