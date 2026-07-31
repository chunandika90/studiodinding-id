<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcabang = base64_decode($_GET['cb']);
	$kdcara = base64_decode($_GET['cr']);
	$kdedc = base64_decode($_GET['ed']);
	$kdbank = base64_decode($_GET['bn']);
	$kdkartu = base64_decode($_GET['jn']);
	$kdcicil = base64_decode($_GET['cl']);
	$prm = base64_decode($_GET['prm']);
	$xparam = explode('/',$prm);

	$tgl1 = base64_decode($_GET['tg1']);
	$tgl2 = base64_decode($_GET['tg2']);
	
	if ($kdcabang ==''){$kdcabang = $_SESSION['store'];}
	if ($kdcara ==''){$kdcara = 'ALL';}
	if ($kdedc ==''){$kdedc = 'ALL';}
	if ($kdbank ==''){$kdbank = 'ALL';}
	if ($kdkartu ==''){$kdkartu = 'ALL';}
	if ($kdcicil ==''){$kdcicil = 'ALL';}
	
	if ($tgl1 ==''){$tgl1 = date("01/m/Y");}
	if ($tgl2 ==''){$tgl2 = date("d/m/Y");}
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Pembayaran</title>
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
        <div class="span4 input-prepend">
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
            <select name="kdcabang" id="kdcabang" class="input-large" <?php if($_SESSION['store'] <> '00'){ ?> disabled <?php } ?>>
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
            <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
        </div>
	</div>
    <div class="container" style="width: auto; padding: 0 20px;">
        <div class="span3 input-prepend">
        	<span class="add-on">Cara Bayar</span>
            <select name="kdcara" id="kdcara" class="input-medium">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlcara = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'CARABAYAR' order by a.m_kode asc" ;
				$stmtcara = sqlsrv_query( $con_dbnew, $tsqlcara );
                while( $rowcara = sqlsrv_fetch_array( $stmtcara, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcara['m_kode']; ?>" <?php if($kdcara == $rowcara['m_kode']){?> selected <?php } ?>><?php echo $rowcara['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span2 input-prepend">
        	<span class="add-on">EDC</span>
            <select name="kdedc" id="kdedc" class="input-small">
				<option value="ALL" >ALL</option>
                <?php
				$tsqledc = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'BANK' order by a.m_kode asc" ;
				$stmtedc = sqlsrv_query( $con_dbnew, $tsqledc );
                while( $rowedc = sqlsrv_fetch_array( $stmtedc, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowedc['m_kode']; ?>" <?php if($kdedc == $rowedc['m_kode']){?> selected <?php } ?>><?php echo $rowedc['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span2 input-prepend">
        	<span class="add-on">Bank</span>
            <select name="kdbank" id="kdbank" class="input-small">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlbank = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'BANK' order by a.m_kode asc" ;
				$stmtbank = sqlsrv_query( $con_dbnew, $tsqlbank );
                while( $rowbank = sqlsrv_fetch_array( $stmtbank, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowbank['m_kode']; ?>" <?php if($kdbank == $rowbank['m_kode']){?> selected <?php } ?>><?php echo $rowbank['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="span2 input-prepend">
        	<span class="add-on">Jns.Kartu</span>
            <select name="kdkartu" id="kdkartu" class="input-small">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlkartu = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'JENISKARTU' order by a.m_kode asc" ;
				$stmtkartu = sqlsrv_query( $con_dbnew, $tsqlkartu );
                while( $rowkartu = sqlsrv_fetch_array( $stmtkartu, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowkartu['m_kode']; ?>" <?php if($kdkartu == $rowkartu['m_kode']){?> selected <?php } ?>><?php echo $rowkartu['m_nama']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
 
        <div class="span3 input-prepend">
        	<span class="add-on">Jns.Cicilan</span>
            <select name="kdcicil" id="kdcicil" class="input-medium">
				<option value="ALL" >ALL</option>
                <?php
				$tsqlcicil = "select a.m_kode, a.m_nama from msmaster a where a.m_type = 'CICILKARTU' order by a.m_kode asc" ;
				$stmtcicil = sqlsrv_query( $con_dbnew, $tsqlcicil );
                while( $rowcicil = sqlsrv_fetch_array( $stmtcicil, SQLSRV_FETCH_ASSOC))
                {
                    ?>
                    <option value="<?php echo $rowcicil['m_kode']; ?>" <?php if($kdcicil == $rowcicil['m_kode']){?> selected <?php } ?>><?php echo $rowcicil['m_nama']; ?></option>
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
                <img src="images/printer.gif" style="cursor:pointer" id="cetakreport1c" onclick="cetak1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdcara; ?>','<?php echo $kdedc; ?>','<?php echo $kdbank; ?>','<?php echo $kdkartu; ?>','<?php echo $kdcicil; ?>') "/> 
                <img src="images/excel.gif" style="cursor:pointer" id="excelreport1c" onclick="exel1b('<?php echo $tgl1; ?>','<?php echo $tgl2; ?>','<?php echo $kdcabang; ?>','<?php echo $kdcara; ?>','<?php echo $kdedc; ?>','<?php echo $kdbank; ?>','<?php echo $kdkartu; ?>','<?php echo $kdcicil; ?>') "/>  
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
                    <th width="20%"><div align="center">Report Pembayaran</div></th>
                    <th width="10%"><div align="right">Total</div></th>
                    <th width="10%"><div align="right">MDR</div></th>
                    <th width="3%"></th>
                </tr>
            </thead>
            <tbody>
			<?php
            $tsql = "select * from dbo.f_reportpembayaran('".$tanggal1."', '".$tanggal2."', '".$kdcabang."', '".$kdcara."', '".$kdedc."', '".$kdbank."', '".$kdkartu."', '".$kdcicil."')" ;
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
                    <td class="data"><div align="right"><?php echo number_format($row['vf_total'], 0, '.', ','); ?></div></td>
                    <td class="data"><div align="right"><?php echo number_format($row['vf_mdr'], 0, '.', ','); ?></div></td>
                    <td>
                    	<div align="center">
                        <img src="images/view_icon.gif" style="cursor:pointer" id="viewdetail" onClick="oc_detail('<?php echo $prm; ?>','<?php echo $tanggal1; ?>','<?php echo $tanggal2; ?>','<?php echo $row['vf_idcab']; ?>','<?php echo $row['vf_idcara']; ?>','<?php echo $row['vf_idedc']; ?>','<?php echo $row['vf_idbank']; ?>','<?php echo $row['vf_idkartu']; ?>','<?php echo $row['vf_idcicil']; ?>')"/>
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
			var kdcara =$('#kdcara').val() ;
			var kdedc =$('#kdedc').val() ;
			var kdbank =$('#kdbank').val() ;
			var kdkartu =$('#kdkartu').val() ;
			var kdcicil =$('#kdcicil').val() ;
			var tgl1 =$('#tanggal1').val() ;
			var tgl2 =$('#tanggal2').val() ;
			window.open("report-pembayaran.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(data)+'&cr='+base64_encode(kdcara)+'&ed='+base64_encode(kdedc)+'&bn='+base64_encode(kdbank)+'&jn='+base64_encode(kdkartu)+'&cl='+base64_encode(kdcicil)+'&prm='+base64_encode(vparam),'_self');
		}

		function oc_detail(vparam,tgl1,tgl2,kdcab,kdcara,kdedc,kdbank,kdkartu,kdcicil)
		{
			var data={tg1:tgl1,tg2:tgl2,cb:kdcab,cr:kdcara,ed:kdedc,bn:kdbank,jn:kdkartu,cl:kdcicil,prm:vparam};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('report-pembayaran2.php',data,fungsi);
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

		function cetak1b(tgl1,tgl2,kdcab,kdcara,kdedc,kdbank,kdkartu,kdcicil)
		{	
			window.open("report-pembayaranp.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(kdcab)+'&cr='+base64_encode(kdcara)+'&ed='+base64_encode(kdedc)+'&bn='+base64_encode(kdbank)+'&jn='+base64_encode(kdkartu)+'&cl='+base64_encode(kdcicil),'_blank');
		}
		
		function exel1b(tgl1,tgl2,kdcab,kdcara,kdedc,kdbank,kdkartu,kdcicil)
		{
			window.open("report-pembayaranx.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(kdcab)+'&cr='+base64_encode(kdcara)+'&ed='+base64_encode(kdedc)+'&bn='+base64_encode(kdbank)+'&jn='+base64_encode(kdkartu)+'&cl='+base64_encode(kdcicil),'_blank');
		}
		
		function cetak1c(tgl1,tgl2,kdcab,kdcara,kdedc,kdbank,kdkartu,kdcicil)
		{	
			window.open("report-pembayaran2p.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(data)+'&cr='+base64_encode(kdcara)+'&ed='+base64_encode(kdedc)+'&bn='+base64_encode(kdbank)+'&jn='+base64_encode(kdkartu)+'&cl='+base64_encode(kdcicil),'_blank');
		}
		
		function exel1c(tgl1,tgl2,kdcab,kdcara,kdedc,kdbank,kdkartu,kdcicil)
		{	
			window.open("report-pembayaran2x.php?tg1="+base64_encode(tgl1)+"&tg2="+base64_encode(tgl2)+"&cb="+base64_encode(data)+'&cr='+base64_encode(kdcara)+'&ed='+base64_encode(kdedc)+'&bn='+base64_encode(kdbank)+'&jn='+base64_encode(kdkartu)+'&cl='+base64_encode(kdcicil),'_blank');
		}
		
	</script>

    </body>
</html>