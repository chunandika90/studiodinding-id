<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$xparam = explode('/',$prm);
	
	$kdcabang = $_SESSION['store'];
	$kdklas = 'ALL';
	$kdgroup = 'ALL';
	$kdkatg = 'ALL';
	$kditem = 'ALL';
	$kddist = 'ALL';
	$kdseg = 'ALL';
	$kdplu = '';
	$kdsize = 0;
	$kdsize2 = 0;
	$kdcert = 'ALL';

	$tgl1 = date("01/m/Y");
	$tgl2 = date("d/m/Y");
	$kdby = 'm_group';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Report Penjualan</title>
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
        	<td valign="middle" width="25%">
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
        	<td width="25%">
                <div class="input-prepend">
                    <span class="add-on">Cabang </span>
                    <select name="kdcabang" id="kdcabang" class="input-large" onChange="oc_cabang()">
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
            <td width="25%">
                <div class="input-prepend">
                    <span class="add-on">Klasifikasi</span>
                    <select name="kdklas" id="kdklas" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqlkklas = "select distinct b.m_klasifikasi, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'KLASIFIKASI' and b.m_klasifikasi = c.m_kode order by b.m_klasifikasi asc" ;
                        $stmtklas = sqlsrv_query( $con_dbnew, $tsqlkklas );
                        while( $rowklas = sqlsrv_fetch_array( $stmtklas, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowklas['m_klasifikasi']; ?>" <?php if($kdklas == $rowklas['m_klasifikasi']){?> selected <?php } ?>><?php echo $rowklas['m_nama']; ?></option>
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
                    <span class="add-on">Quality</span>
                    <select name="stqlty" id="stqlty" class="input-large">
                        <option value="" >ALL</option>
                        <option value="01" >1st Quality</option>
                        <option value="02" >2nd Quality</option>
                    </select>
                </div>
            </td>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">No.PLU</span>
                    <input class="input-medium" type="text" id="noplu" name="noplu" value="<?php echo $kdplu ; ?>"/>
                </div>
            </td>
        </tr>
        <tr>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Dist.Stone</span>
                    <select name="kddist" id="kddist" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqldist = "select distinct b.m_distribusi, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'DISTRIBUSI' and b.m_distribusi = c.m_kode order by b.m_distribusi asc" ;
                        $stmtdist = sqlsrv_query( $con_dbnew, $tsqldist );
                        while( $rowdist = sqlsrv_fetch_array( $stmtdist, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowdist['m_distribusi']; ?>" <?php if($kddist == $rowdist['m_distribusi']){?> selected <?php } ?>><?php echo $rowdist['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Segmen</span>
                    <select name="kdseg" id="kdseg" class="input-large">
                        <option value="ALL" >ALL</option>
                        <?php
                        $tsqldist = "select distinct b.m_segmen, c.m_nama from t_stockinv a, t_stockdata b, msmaster c where a.m_kodebarang= b.m_kodebarang and a.m_productid = b.m_productid and c.m_type = 'SEGMEN' and b.m_segmen = c.m_kode order by b.m_segmen asc" ;
                        $stmtdist = sqlsrv_query( $con_dbnew, $tsqldist );
                        while( $rowdist = sqlsrv_fetch_array( $stmtdist, SQLSRV_FETCH_ASSOC))
                        {
                            ?>
                            <option value="<?php echo $rowdist['m_segmen']; ?>" <?php if($kdseg == $rowdist['m_segmen']){?> selected <?php } ?>><?php echo $rowdist['m_nama']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Barang Basic</span>
                    <select name="kdbasic" id="kdbasic" class="input-medium">
                        <option value="ALL" >ALL</option>
                        <option value="m_basic" <?php if($kdbsc == 'm_basic'){?> selected <?php } ?>>Barang Basic</option>
                        <option value="m_nbasic" <?php if($kdbsc == 'm_nbasic'){?> selected <?php } ?>>Non Basic</option>
                    </select>            
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Group By</span>
                    <select name="reportby" id="reportby" class="input-medium">
                        <option value="m_group" <?php if($kdby == 'm_group'){?> selected <?php } ?>>Group Product</option>
                        <option value="m_cabang" <?php if($kdby == 'm_cabang'){?> selected <?php } ?>>Store</option>
                        <option value="m_customer" <?php if($kdby == 'm_customer'){?> selected <?php } ?>>Customer</option>
                        <option value="m_sales" <?php if($kdby == 'm_sales'){?> selected <?php } ?>>JR</option>
                        <option value="m_level" <?php if($kdby == 'm_level'){?> selected <?php } ?>>Klasifikasi</option>
                        <option value="m_kategori" <?php if($kdby == 'm_kategori'){?> selected <?php } ?>>Kategori</option>
                        <option value="m_item" <?php if($kdby == 'm_item'){?> selected <?php } ?>>Item</option>
                        <option value="m_distribusi" <?php if($kdby == 'm_distribusi'){?> selected <?php } ?>>Dist.Stone</option>
                        <option value="m_segmen" <?php if($kdby == 'm_segmen'){?> selected <?php } ?>>Segmen</option>
                        <option value="m_tanggal" <?php if($kdby == 'm_tanggal'){?> selected <?php } ?>>Tanggal</option>
                        <option value="m_jam" <?php if($kdby == 'm_tanggal'){?> selected <?php } ?>>Jam</option>
                    </select>            
                </div>
            </td>
        </tr>
        <tr>
        	<td>
                <div class="input-prepend">
                    <span class="add-on">Stone Size</span>
                    <input type="text" class="input-mini" id="kdsize" name="kdsize" placeholder="Min Size" value="0" style="text-align:center" />
                    <span class="add-on">s/d</span>
                    <input type="text" class="input-mini" id="kdsize2" name="kdsize2" placeholder="Max Size" value="0" style="text-align:center" />
                </div>
            </td>
            <td>
                <div class="input-prepend">
                    <span class="add-on">Certificate</span>
                    <select name="kdcert" id="kdcert" class="input-medium">
                        <option value="ALL" >ALL</option>
                        <option value="m_cert" <?php if($kdcert == 'm_cert'){?> selected <?php } ?>>Ada Sertifikat</option>
                        <option value="m_ncert" <?php if($kdcert == 'm_ncert'){?> selected <?php } ?>>Tidak Ada Sertifikat</option>
                    </select>            
                </div>
            </td>
            <td colspan="2">
                <button class="btn" onClick="oc_report('<?php echo $prm; ?>')">Display</button>
                
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
			var data= $('#kdcabang').val() ;
			var kdgroup = $('#kdgroup').val() ;
			var kdklas = $('#kdklas').val() ;
			var kdkatg = $('#kdkatg').val() ;
			var kditem = $('#kditem').val() ;
			var kddist = $('#kddist').val() ;
			var kdseg = $('#kdseg').val() ;
			var kdplu = $('#kdplu').val() ;
			var kdcert = $('#kdcert').val() ;
			var kdsize = $('#kdsize').val() ;
			var kdsize2 = $('#kdsize2').val() ;
			
			var tgl1 = $('#tanggal1').val() ;
			var tgl2 = $('#tanggal2').val() ;
			var stqlty = $('#stqlty').val() ;
			var reportby = $('#reportby').val() ;
			var kdbasic = $('#kdbasic').val() ;
			
			var data={tg1:tgl1,tg2:tgl2,cb:data,gr:kdgroup,ks:kdklas,kt:kdkatg,it:kditem,dst:kddist,sg:kdseg,pl:kdplu,by:reportby,prm:vparam,ql:stqlty,bsc:kdbasic,sz:kdsize,sz2:kdsize2,crt:kdcert};
			var fungsi=function(respon){
					$("#listdata").html(respon);
					$("#listplu").html('');
				};
			$.get('laporan-pos1.php',data,fungsi);
		}

		function oc_detail(kdcab,kdgroup,kdklas,kdkatg,kditem,kddist,kdseg,kdplu,kdby,tgl1,tgl2,vkode,vnama,vstqlty,kdbasic,kdsize,kdsize2,kdcert)
		{
			var data={cb:kdcab,gr:kdgroup,ks:kdklas,kt:kdkatg,it:kditem,dst:kddist,pl:kdplu,by:kdby,tg1:tgl1,tg2:tgl2,vkode:vkode,vnama:vnama,ql:vstqlty,sg:kdseg,bsc:kdbasic,sz:kdsize,sz2:kdsize2,crt:kdcert};
			var fungsi=function(respon){
					$("#listplu").html(respon);
				};
			$.get('laporan-pos2.php',data,fungsi);
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

		function cetak1b (tgl1,tgl2,kdcab,kdgroup,kdklas,kdkatg,kditem,kddist,kdplu,reportby,stqlty,kdseg,kdbasic,kdsize,kdsize2,kdcert)
		{	
			window.open('laporan-posp.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&kl='+kdklas+'&it='+kditem+'&dst='+kddist+'&pl='+kdplu+'&by='+reportby+'&ql='+stqlty+'&sg='+kdseg+'&bsc='+kdbasic+'&sz='+kdsize+'&sz2='+kdsize2+'&crt='+kdcert,'_blank');
		}
		
		function exel1b (tgl1,tgl2,kdcab,kdgroup,kdklas,kdkatg,kditem,kddist,kdplu,reportby,stqlty,kdseg,kdbasic,kdsize,kdsize2,kdcert)
		{
			window.open('laporan-posx.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&kl='+kdklas+'&it='+kditem+'&dst='+kddist+'&pl='+kdplu+'&by='+reportby+'&ql='+stqlty+'&sg='+kdseg+'&bsc='+kdbasic+'&sz='+kdsize+'&sz2='+kdsize2+'&crt='+kdcert,'_blank');
		}
		
		function cetak1c (tgl1,tgl2,kdcab,kdgroup,kdklas,kdkatg,kditem,kddist,kdseg,kdplu,reportby,vkode,vnama,vstqlty,kdbasic,kdsize,kdsize2,kdcert)
		{	
			window.open('laporan-pos2p.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&kl='+kdklas+'&it='+kditem+'&dst='+kddist+'&sg='+kdseg+'&pl='+kdplu+'&by='+reportby+'&vkode='+vkode+'&vnama='+vnama+'&ql='+vstqlty+'&bsc='+kdbasic+'&sz='+kdsize+'&sz2='+kdsize2+'&crt='+kdcert,'_blank');
		}
		
		function exel1c (tgl1,tgl2,kdcab,kdgroup,kdklas,kdkatg,kditem,kddist,kdseg,kdplu,reportby,vkode,vnama,vstqlty,kdbasic,kdsize,kdsize2,kdcert)
		{	
			window.open('laporan-pos2x.php?tg1='+tgl1+'&tg2='+tgl2+'&cb='+kdcab+'&gr='+kdgroup+'&kt='+kdkatg+'&kl='+kdklas+'&it='+kditem+'&dst='+kddist+'&sg='+kdseg+'&pl='+kdplu+'&by='+reportby+'&vkode='+vkode+'&vnama='+vnama+'&ql='+vstqlty+'&bsc='+kdbasic+'&sz='+kdsize+'&sz2='+kdsize2+'&crt='+kdcert,'_blank');
		}
		
	</script>

    </body>
</html>