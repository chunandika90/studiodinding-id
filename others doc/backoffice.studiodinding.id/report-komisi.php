<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['cb']);
	$kdsales = base64_decode($_GET['jr']);
	$periode = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdsales ==''){$kdsales = 'ALL';}
	if ($periode ==''){date("Y-m");}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Incentive</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	    <link href="css/tabelizer.min.css" media="all" rel="stylesheet" type="text/css" />    
        
    </head>

    <body>
    <?php
        include "menu-pos2.php" ;
        include "mssql-dbnew.php" ;
		
		$abc = explode('/',$tgl1);
		$tanggal1 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 00:00:00';
		$abc = explode('/',$tgl2);
		$tanggal2 = $abc[2].'/'.$abc[1].'/'.$abc[0].' 23:59:59';
		
    ?>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span12 input-prepend">
        	<span class="add-on">Periode</span>
            <select name="periode" id="periode" class="input-medium">
                <?php
				$tsqlbulan = "select distinct LEFT(convert(varchar(10),m_tanggal,120),7) as co_periode from t_stockopname0 order by co_periode desc" ;
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
        
        <div class="span3 input-prepend">
        	<span class="add-on">Cabang </span>
            <select name="kdcabang" id="kdcabang" class="input-large" <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>  onChange="oc_cabang()">
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
        	<span class="add-on">JR</span>
			<span id="listjr">
            <select name="kdsales" id="kdsales" class="input-medium">
                <option value="" >ALL</option>
				<?php
				$tsqljr = "select distinct a.m_kode, a.m_nama from mssales a where a.m_cabang = '".$kdcabang."' and m_aktif = 1 order by a.m_nama asc" ;
				$stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
                while( $rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC))
                {
                   ?>
                    <option value="<?php echo $rowjr['m_kode']; ?>" <?php if($kdsales == $rowjr['m_kode']){?> selected <?php } ?> ><?php echo $rowjr['m_nama']; ?></option>
                   <?php
                }
				?>                    
            </select>            
            </span>
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>

        <div class="span1">
        	<?php
			if (substr($xparam[3],3,1) == 'Y')
			{
				?>
                <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdsales; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/> 
                <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdsales; ?>','<?php echo $kdkatg; ?>','<?php echo $kditem; ?>') "/>  
            	<?php
			}
			?>
        </div>
    </div>

    <div class="container pull-left" style="width: 60%; padding: 0 20px;">
        <span id="listdata">
        
        <table id="table1" class="controller table table-bordered table-striped table-hover table-condensed">
        	<thead>
                <tr data-level="header" class="header">
                    <th width="25%"><div align="center">Report Incentive</div></th>
                    <th width="10%"><div align="center">Pt.Target</div></th>
                    <th width="10%"><div align="center">Pt.PG</div></th>
                    <th width="10%"><div align="center">Pt.DJ</div></th>
                    <th width="10%"><div align="center">Pt.LM</div></th>
                    <th width="10%"><div align="center">Tot.Point</div></th>
                    <th width="5%"><div align="right">Rp.</div></th>
                    <th width="3%"></th>
                </tr>
            </thead>
            <tbody>
			<?php
            $tsql = "select * from dbo.f_reportkomisi('".$periode."', '".$kdcabang."', '".$kdsales."') order by vf_idcab asc, vf_idgroup asc,  vf_level asc" ;
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
                ?>
                <tr <?php echo $tampilanfont; ?> data-level="<?php echo $row['vf_level']; ?>" id="rowke<?php echo $i; ?>" height="25px">
                    <td><div align="left"><?php echo $row['vf_nama']; ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_target'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_pg'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_dj'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_lm'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_pg']+$row['vf_dj']+$row['vf_lm'], 2, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_rp'], 0, '.', ','); ?></div></td>
                    <td>
                    	<div align="center">
                        <img src="images/view_icon.gif" style="cursor:pointer" id="viewdetail" onClick="oc_detail('<?php echo $prm; ?>','<?php echo $periode; ?>','<?php echo $row['vf_idcab']; ?>','<?php echo $row['vf_idgroup']; ?>','<?php echo $row['vf_idpacing']; ?>')"/>
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
			$.get('report-listsales.php',data,fungsi);
		}

		function oc_report(vparam)
		{
			var data=$('#kdcabang').val() ;
			var kdsales =$('#kdsales').val() ;
			var periode =$('#periode').val() ;

			window.open("report-komisi.php?pr="+base64_encode(periode)+"&cb="+base64_encode(data)+'&jr='+base64_encode(kdsales)+'&prm='+base64_encode(vparam),'_self');
		}

		function oc_detail(vparam,periode,kdcab,kdsales,kdpacing)
		{
			var data={pr:periode,cb:kdcab,gr:kdsales,pc:kdpacing,prm:vparam};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-komisi2.php',data,fungsi);
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

		function cetak1b (tgl1,tgl2,kdcab,kdsales,kdkatg,kditem)
		{	
			window.open('report-komisip.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdsales+'&kt='+kdkatg+'&it='+kditem,'_blank');
		}
		
		function exel1b (tgl1,tgl2,kdcab,kdsales,kdkatg,kditem)
		{
			window.open('report-komisix.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdsales+'&kt='+kdkatg+'&it='+kditem,'_blank');
		}
		
		function cetak1c (tgl1,tgl2,kdcab,kdsales,kdkatg,kditem)
		{	
			window.open('report-komisi2p.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdsales+'&kt='+kdkatg+'&it='+kditem,'_blank');
		}
		
		function exel1c (tgl1,tgl2,kdcab,kdsales,kdkatg,kditem)
		{	
			window.open('report-komisi2x.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdsales+'&kt='+kdkatg+'&it='+kditem,'_blank');
		}
		
	</script>

    </body>
</html>