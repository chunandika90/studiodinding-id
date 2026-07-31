<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['cb']);
	$kdgroup = base64_decode($_GET['gr']);
	$kdkatg = base64_decode($_GET['kt']);
	$kditem = base64_decode($_GET['it']);
	$tgl1 = base64_decode($_GET['tg1']);
	$tgl2 = base64_decode($_GET['tg2']);
	$kdstock = base64_decode($_GET['kdst']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	if ($kdstock ==''){$kdstock = 'ALL';}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Inv.Transfer</title>
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
        <div class="span12 input-prepend">
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
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>
        
        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" class="input-large"  >
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

        <div class="span3 input-prepend">
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
        <div class="span3 input-prepend">
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

        <div class="span1">
			<?php
            if (substr($xparam[3],3,1) == 'Y')
            {
                ?>
                <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/> 
                <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/>  
            	<?php
			}
			?>
        </div>
        
    </div>

    <div class="container pull-left" style="width: 95%; padding: 0 20px;">
        <span id="listdata">
        
        <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        	<thead>
                <tr data-level="header" class="header">
                    <th width="20%"><div align="center">Report Inv.Transfer</div></th>
                    <th width="5%"><div align="right">Qty</div></th>
                    <th width="10%"><div align="right">Total</div></th>
                    <th width="5%"><div align="right">Gross</div></th>
                    <th width="5%"><div align="right">Net</div></th>
                    <th width="5%"><div align="right">Butir</div></th>
                    <th width="5%"><div align="right">Carat</div></th>
                    <th width="3%"></th>
                </tr>
            </thead>
            <tbody>
			<?php
            $tsql = "select * from dbo.f_reportout('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kditem."')" ;
			echo $tsql;
            $stmt = sqlsrv_query( $con_dbnew, $tsql);
            $i = 0 ;
//			echo $tsql ;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
                $i = $i + 1 ;
				$tampilanfont = '';
				if ($row['vf_level']=='1')
				{
					$tampilanfont = 'style="font-weight:bold"';
				}
				else if ($row['vf_level']=='2')
				{
					$tampilanfont = 'style="color:#060;font-weight:bold;font-style:italic"';
				}
				else if ($row['vf_level']=='3')
				{
					$tampilanfont = 'style="color:#00F;font-style:italic"';
				}
				
                ?>
                <tr <?php echo $tampilanfont; ?> data-level="<?php echo $row['vf_level']; ?>" id="rowke<?php echo $i; ?>" height="25px">
                    <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_qty'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_gross'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_net'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>                    
                    <td>
                    	<div align="center">
                        <img src="images/view_icon.gif" style="cursor:pointer" id="viewdetail" onClick="oc_detail('<?php echo $prm; ?>','<?php echo $tanggal1; ?>','<?php echo $tanggal2; ?>','<?php echo $row['vf_lokasi2']; ?>','<?php echo $row['vf_idcab']; ?>','<?php echo $row['vf_idgroup']; ?>','<?php echo $row['vf_idkatg']; ?>','<?php echo $row['vf_iditem']; ?>','<?php echo $kdstock; ?>')"/>
                        </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

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
			var table1 = $('#table1').tabelize({
				/*onRowClick : function(){
					alert('test');
				}*/
				fullRowClickable : true,
				onReady : function(){
					console.log('ready');
				},
				onBeforeRowClick :  function(){
					console.log('onBeforeRowClick');
				},
				onAfterRowClick :  function(){
					console.log('onAfterRowClick');
				},
			});
			
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});

			$('#datetimepicker2').datetimepicker({
				language: 'en',
				pickTime: false
			});
			
		});

		function oc_report(vparam)
		{
			var data=$('#kdcabang').val() ;
			var kdgroup =$('#kdgroup').val() ;
			var kditem =$('#kditem').val() ;
			var tgl1 =$('#tanggal1').val() ;
			var tgl2 =$('#tanggal2').val() ;
			
			window.open("report-out.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(data)+'&gr='+base64_encode(kdgroup)+'&it='+base64_encode(kditem)+'&prm='+base64_encode(vparam),'_self');
		}

		function oc_detail(vparam,tgl1,tgl2,lokasi,kdcabang,kdgroup,kdkatg,kditem,kdstock)
		{
			var data={tg1:tgl1,tg2:tgl2,lok:lokasi,cb:kdcabang,gr:kdgroup,kt:kdkatg,it:kditem,kdst:kdstock,prm:vparam};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-out2.php',data,fungsi);
		}

		
		function view_modal(vkode)
		{
			var data={kode:vkode};
			var fungsi=function(respon){
					$("#viewdata").html(respon);
				};
			$.get('mssales-view.php',data,fungsi);
			
			$('#view_modal').modal();
		}
		
		function cetak1b (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem,kdstock)
		{	
			window.open('report-outp.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function exel1b (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem,kdstock)
		{	
			window.open('report-outx.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function cetak1c(tgl1,tgl2,lokasi,kdcab,kdgroup,kdkatg,kditem,kdstock)
		{	
			window.open('report-out2p.php?tg1='+tgl1+'&tg2='+tgl2+'&lok='+lokasi+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function exel1c(tgl1,tgl2,lokasi,kdcab,kdgroup,kdkatg,kditem,kdstock)
		{	
			window.open('report-out2x.php?tg1='+tgl1+'&tg2='+tgl2+'&lok='+lokasi+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
	</script>

    </body>
</html>