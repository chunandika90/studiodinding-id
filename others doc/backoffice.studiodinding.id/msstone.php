<?php
	session_start();
	$type = base64_decode($_GET['kd']);
	$periode = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ( $type == '' ) {$type = 'ALL' ;}
	if ( $periode =='' ){ $periode = date("Y-m");}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master Stone</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;

		$dumb = explode('-',$periode);
		

		$tsql = "select distinct m_size from msstone  order by m_shape, m_size asc " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		
		
		if ($type == 'ALL')
		{
			$tsql2 = "	select *, convert(varchar(30),m_tanggal,121) as co_tgl
						from msstone  
						order by m_shape, m_size asc" ;
			
		}
		else
		{
			$tsql2 = "	select *, convert(varchar(30),m_tanggal,121) as co_tgl
						from msstone 
						where m_size = '".$type."'  
						order by m_shape, m_size asc" ;
		}
		
		//echo $tsq12;
		$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
		
		
		
		$tsqlnosupl = "select max(right(m_kode,5)) as nomormax from dbapotik.dbo.msobat where left(m_kode,1) = 'O' ";
		
		//echo $tsqlnosupl ."<br>";
		$stmtnosupl= sqlsrv_query( $con_dbnew, $tsqlnosupl);
		$rownosupl = sqlsrv_fetch_array( $stmtnosupl, SQLSRV_FETCH_ASSOC);
		$nomax = $rownosupl['nomormax'];
		if ($nomax == ''){$nomax = '00000' ;}
		$nomax = $nomax + 1 ;
		
		//echo $nomax ."<br>";
		
		$m_kode = 'O'.substr('00000'.$nomax,-5) ;
		
		//echo $m_kode."<br>";
		
		
    ?>

    <div class="container pull-left" style="width: 100%; padding: 0 20px;">
		<div class="span10" style="overflow:auto;overflow-x:hidden;height:500px">
            <span id="listuser">
            <table class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4>Master Stone - <?php echo $type ; ?></h4></th>
                        <th colspan="2">
                        <div class="pull-right">
							<?php
                            if (substr($xparam[3],0,1) == 'Y')
                            {
                                ?>
                                <button class="btn" onClick="edit_modal('<?php echo $prm ; ?>','')">Tambah Stone</button>
                                <?php
                            }
                            ?>
                        </div></th>
                    </tr>
                    
                    <tr>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Shape</th>
                        <th width="15%">Size</th>
                        <th width="15%">Ukuran</th>
                        <th width="10%"><div class="pull-right">Beli</div></th>
                        <th width="10%"><div class="pull-right">Jual</div></th>
                        <th width="10%"><div class="pull-right">PB M</div></th>
                        <th width="10%"><div class="pull-right">PB R</div></th>
                        <th width="10%"><div class="pull-right">Carat Min</div></th>
                        <th width="10%"><div class="pull-right">Carat Max</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <tr>
                                <td onClick="view_modal('<?php echo $prm ; ?>','<?php echo $row2['m_shape'] ; ?>','<?php echo $row2['m_size'] ; ?>')" style="cursor:pointer"><?php echo $row2['co_tgl']; ?></td>
                                <td><?php echo $row2['m_shape']; ?></td>
                                <td><?php echo $row2['m_size']; ?></td>
                                <td><?php echo $row2['m_ukuran']; ?></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_hargam'], 2, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_hargar'], 2, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_opbm'], 0, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_opbr'], 0, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_min'], 4, '.', ','); ?></div></td>
                                <td><div class="pull-right"><?php echo number_format($row2['m_max'], 4, '.', ','); ?></div></td>
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

    <div id="dedit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <span id="edit_modal">
        </span>
    </div>         



    <div id="dialog-listshape">
        <span id="datashape">
        </span>
    </div>
    

	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">



		$(function() {
		$( "#dialog-listshape" ).dialog({
			autoOpen: false,
			height:600,
			width:400,
			modal: true,
			buttons: {
				"Close": function() {
						$( this ).dialog( "close" );
						}
					}
			});
			
		});
		
		function oc_kurs()
		{
			var data={kd:$('#kdtype').val()};
			var fungsi=function(respon){
					$("#listperiode").html(respon);
				};
			$.get('msstone-periode.php',data,fungsi);
		}

		function oc_type(vparam)
		{
			
			var vkode = document.getElementById('kdtype').value ;
			
			var vperiod = document.getElementById('periode').value ;
			window.open('msstone.php?kd='+base64_encode(vkode)+'&pr='+base64_encode(vperiod)+'&prm='+base64_encode(vparam),'_self');			
		}
		
		function view_modal(vparam,vshape,vsize)
		{
			var data={vshape:vshape,vsize:vsize,prm:vparam};
			var fungsi=function(respon){
					$("#view_modal").html(respon);
				};
			$.get('msstone-view.php',data,fungsi);
			
			$('#dview_modal').modal();
		}
		
		function edit_modal(vparam,vshape,vsize)
		{
			var data={vshape:vshape,vsize:vsize,prm:vparam};
			var fungsi=function(respon){
					$("#edit_modal").html(respon);
				};
			$.get('msstone-edit.php',data,fungsi);
			
			$('#dedit_modal').modal();
		}

		function hapus_modal(vparam,vshape,vsize)
		{
			var data={vshape:vshape,vsize:vsize,prm:vparam};
			var fungsi=function(respon){
					location.reload();
				};
			$.get('msstone-hapus.php',data,fungsi);
		}

	</script>

    </body>
</html>