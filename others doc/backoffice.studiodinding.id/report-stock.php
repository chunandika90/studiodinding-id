<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['cb']);
	$kdgroup = base64_decode($_GET['gr']);
	$kdkatg = base64_decode($_GET['kt']);
	$kditem = base64_decode($_GET['it']);
	$kdstock = base64_decode($_GET['kdst']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdgroup ==''){$kdgroup = 'ALL';}
	if ($kdkatg ==''){$kdkatg = 'ALL';}
	if ($kditem ==''){$kditem = 'ALL';}
	if ($kdstock ==''){$kdstock = 'ALL';}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Stock</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
	    <link href="css/tabelizer.min.css" media="all" rel="stylesheet" type="text/css" />    
        
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
        include "menu-pos2.php" ;
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" class="input-large"  <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>>
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
        </div>

        <div class="span3 input-prepend">
        	<span class="add-on">St.Stock</span>
            <select name="kdstock" id="kdstock" class="input-large">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlst = " select distinct a.m_status, b.m_nama from t_stockdata a, msmaster b where a.m_status = b.m_kode and b.m_type = 'STINV' order by a.m_status asc " ;
				$stmtst = sqlsrv_query( $con_dbnew, $tsqlst );
                while( $rowst = sqlsrv_fetch_array( $stmtst, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowst['m_status']; ?>" <?php if($kdstock == $rowst['m_status']){?> selected <?php } ?>><?php echo $rowst['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>
    </div>

    <div class="container pull-left" style="width: 80%; padding: 0 20px;">
        <span id="listdata">
        
        <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        	<thead>
                <tr data-level="header" class="header">
                    <th width="20%"><div align="center">Report STOCK</div></th>
                    <th width="5%"><div align="right">Stock</div></th>
                    <th width="5%"><div align="right">In Transit</div></th>
                    <th width="10%"><div align="right">Total</div></th>
                    <th width="5%"><div align="right">Gross</div></th>
                    <th width="5%"><div align="right">Net</div></th>
                    <th width="5%"><div align="right">Butir</div></th>
                    <th width="5%"><div align="right">Carat</div></th>
                    <th width="5%"><div align="center">
                        <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/> 
                        <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdgroup; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>','<?php echo $kdstock; ?>') "/>  
						</div>                        
                    </th>
                </tr>
            </thead>
            <tbody>
			<?php
            $tsql = "select * from dbo.f_reportstock('".$kdcabang."', '".$kdgroup."', '".$kdkatg."', '".$kditem."', '".$kdstock."')" ;
            $stmt = sqlsrv_query( $con_dbnew, $tsql);

            $i = 0 ;
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
                    <td class="data"><div align="right"><?php echo number_format($row['vf_otw'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_gross'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_net'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_butir'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_carat'], 3, '.', ','); ?></div></td>                    
                    <td>
                    	<div align="center">
                        <img src="images/view_icon.gif" style="cursor:pointer" id="viewdetail" onClick="oc_detail('<?php echo $row['vf_idcab']; ?>','<?php echo $row['vf_idgroup']; ?>','<?php echo $row['vf_idkatg']; ?>','<?php echo $row['vf_iditem']; ?>','<?php echo $kdstock; ?>')"/>
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
		});

		function oc_report(vparam)
		{
			var data=$('#kdcabang').val() ;
			var kdgroup =$('#kdgroup').val() ;
			var kdkatg =$('#kdkatg').val() ;
			var kditem =$('#kditem').val() ;
			var kdstock =$('#kdstock').val() ;
			window.open("report-stock.php?cb="+base64_encode(data)+'&gr='+base64_encode(kdgroup)+'&kt='+base64_encode(kdkatg)+'&it='+base64_encode(kditem)+'&prm='+base64_encode(vparam)+'&kdst='+base64_encode(kdstock),'_self');
		}

		function oc_detail(kdcab,kdgroup,kdkatg, kditem, kdstock )
		{
			var data={cb:kdcab,gr:kdgroup,kt:kdkatg,it:kditem,kdst:kdstock};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-stock2.php',data,fungsi);
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

		function cetak1b (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem, kdstock )
		{	
			window.open('report-stockp.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function exel1b (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem, kdstock )
		{	
			window.open('report-stockx.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function cetak1c (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem, kdstock )
		{	
			window.open('report-stock2p.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
		
		function exel1c (tgl1,tgl2,kdcab,kdgroup,kdkatg,kditem, kdstock )
		{	
			window.open('report-stock2x.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&it='+kditem+'&kdst='+kdstock,'_blank');
		}
	</script>

    </body>
</html>