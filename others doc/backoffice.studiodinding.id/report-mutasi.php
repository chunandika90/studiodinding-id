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
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	$tgl1 = base64_decode($_GET['tg1']);
	$tgl2 = base64_decode($_GET['tg2']);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Mutasi Stock</title>
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
        </div>
        
        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>  class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlcabang = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'STORE' order by a.m_kode asc" ;
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
        	<span class="add-on">Kategori </span>
            <select name="kdkatg" id="kdkatg" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlkatg = "select distinct b.m_kategori, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'CATEGORY' and b.m_kategori = c.m_kode order by b.m_kategori asc" ;
				$stmtkatg = sqlsrv_query( $con_dbnew, $tsqlkatg );
                while( $rowkatg = sqlsrv_fetch_array( $stmtkatg, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowkatg['m_kategori']; ?>" <?php if($kdkatg == $rowkatg['m_kategori']){?> selected <?php } ?>><?php echo $rowkatg['m_nama']; ?></option>
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
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>
    </div>

    <div class="container pull-left" style="width: 100%; padding: 0 20px;">
        <span id="listdata">
        
        <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        	<thead>
                <tr data-level="header" class="header">
                    <th width="20%"><div align="center">Report Mutasi Stock</div></th>
                    <th width="10%" colspan="2"><div align="center">Stock Awal</div></th>
                    <th width="10%" colspan="2"><div align="center">Penerimaan</div></th>
                    <th width="10%" colspan="2"><div align="center">Penjualan</div></th>
                    <th width="10%" colspan="2"><div align="center">Tradein</div></th>
                    <th width="10%" colspan="2"><div align="center">Resell</div></th>
                    <th width="10%" colspan="2"><div align="center">Retur</div></th>
                    <th width="10%" colspan="2"><div align="center">Stock Akhir</div></th>
                </tr>
            </thead>
            <tbody>
			<?php
            $tsql = "select * from dbo.f_reportmutasistock('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."')" ;
            $stmt = sqlsrv_query( $con_dbnew, $tsql);
            $i = 0 ;

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
            {	
                $i = $i + 1 ;
				$otw1 = '' ;
				$otw7 = '' ;
				$qty = $row['vf_qty1'] + $row['vf_qty2'] - $row['vf_qty3'] + $row['vf_qty4'] + $row['vf_qty5'] - $row['vf_qty6'] ;
				$net = $row['vf_net1'] + $row['vf_net2'] - $row['vf_net3'] + $row['vf_net4'] + $row['vf_net5'] - $row['vf_net6'] + $row['vf_net7'] ;
				$butir = $row['vf_butir1'] + $row['vf_butir2'] - $row['vf_butir3'] + $row['vf_butir4'] + $row['vf_butir5'] - $row['vf_butir6'] + $row['vf_butir7'] ;
				$carat = $row['vf_carat1'] + $row['vf_carat2'] - $row['vf_carat3'] + $row['vf_carat4'] + $row['vf_carat5'] - $row['vf_carat6'] + $row['vf_carat7'];
				if($row['vf_otw1'] > 0)	{ $otw1 = '( '.number_format($row['vf_otw1'], 0, '.', ',').' )';}
				if($row['vf_otw7'] > 0)	{ $otw7 = '( '.number_format($row['vf_otw7'], 0, '.', ',').' )';}

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
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty1'], 0, '.', ',').$otw1; ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net1'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir1'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat1'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty2'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net2'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir2'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat2'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty3'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net3'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir3'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat3'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty4'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net4'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir4'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat4'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty5'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net5'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir5'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat5'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($row['vf_qty6'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($row['vf_net6'], 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($row['vf_butir6'], 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($row['vf_carat6'], 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
                    <td class="data">
                    	<div><div class="pull-left">Qty</div><div class="pull-right"><?php echo number_format($qty, 0, '.', ',').$otw7; ?></div></div><br/>
                    	<div><div class="pull-left">Weight</div><div class="pull-right"><?php echo number_format($net, 2, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Butir</div><div class="pull-right"><?php echo number_format($butir, 0, '.', ','); ?></div></div><br/>
                    	<div><div class="pull-left">Carat</div><div class="pull-right"><?php echo number_format($carat, 3, '.', ','); ?></div></div><br/>
                    </td>
                    <td width="1%"></td>
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
			var kdkatg =$('#kdkatg').val() ;
			var kditem =$('#kditem').val() ;
			var tgl1 =$('#tanggal1').val() ;
			var tgl2 =$('#tanggal2').val() ;
			window.open("report-mutasi.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(data)+'&gr='+base64_encode(kdgroup)+'&kt='+base64_encode(kdkatg)+'&it='+base64_encode(kditem)+'&prm='+base64_encode(vparam),'_self');
		}

		function oc_detail(tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem)
		{
			var data={tg1:tgl1,tg2:tgl2,cb:kdcab,gr:kdgroup,kt:kdkatg,it:kditem};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-mutasi2.php',data,fungsi);
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

	</script>

    </body>
</html>