<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcab = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
	$kdstore = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>RESELL</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		include "menu-pos2.php";
		if ($nomor == '')
		{
			$tgl = date("d/m/Y") ;
			$jam = date("H:i:s") ;
			$nama = '' ;
			$kdcust = '';
			$kdsales = '';
			$alamat = '';
			$kota = '';
			$telepon = '';
			$ket = '' ;
			$status = 'A';
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),m_tanggal,108) as co_jam, b.m_alamat, b.m_kota, b.m_telepon1 from t_resell a, mscustomer b where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' and a.m_kodecust = b.m_kode " ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
			
			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$nama = $row['m_nama'] ;
			$kdcust = $row['m_kodecust'] ;
			$kdsales = $row['m_kodesales'] ;
			$alamat = $row['m_alamat'] ;
			$kota = $row['m_kota'] ;
			$telepon = $row['m_telepon1'] ;
			$ket = $row['m_keterangan'] ;
			$status = $row['m_status'] ;
		}
		$lokasi = $kdcab.'-0' ;
    ?>
	<form class="form-horizontal" method="post" action="resell-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'RESELL ( '.$kdcab.' '.$nomor.' )' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="150">
                        	<input type="hidden" id="kdstore" name="kdstore" value="<?php echo $_GET['st']; ?>" />
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
				            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                        	<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                        	<input type="hidden" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                            <input type="hidden" id="jumrow2" name="jumrow2" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="75">Tanggal</td>
                        <td width="150"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kodecust" name="m_kodecust" value="<?php echo $kdcust; ?>" required readonly />
                                <input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" required />
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listcust()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    <tr>
                        <td>JR-1</td>
                        <td colspan="3">
                            <select name="m_kodesales" id="m_kodesales" class="input-large">
                                <option value="" >-</option>
                                <?php
                                $tsqljr = "select m_kode, m_nama from mssales where m_cabang = '".$kdcab."' and m_aktif = 1 order by m_nama asc" ;
                                $stmtjr = sqlsrv_query( $con_dbnew, $tsqljr);
                                while( $rowjr = sqlsrv_fetch_array( $stmtjr, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowjr['m_kode']; ?>" <?php if ($rowjr['m_kode'] == $row['m_kodesales']){ ?> selected="selected" <?php }   ?> ><?php echo $rowjr['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 90%; padding: 0 10px;">
            <table id="table_buyback" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th colspan="10"><div align="center">Buyback Item</div></th>
                    </tr>
                    <tr>
                        <th>Product ID</th>
                        <th>Group</th>
                        <th>Item</th>
                        <th><div align="center">Qty</div></th>
                        <th>BuyBack</th>                        
                        <th>Cabang</th>
                        <th>No.Faktur</th>
                        <th>Tanggal</th>
                        <th>Harga</th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$ttot = 0 ;
						$tbuy = 0 ;
						
						if ($nomor != '')
						{
							$tsqlbuy = "select 	a.*, convert(varchar(10),a.m_tanggal2,103) as co_tgl, convert(varchar(8),m_tanggal2,108) as co_jam, b.m_item, c.m_nama as co_namabarang
										from 	t_resell2 a, t_stockdata b, msbarang c 
										where 	a.m_cabang = '".$kdstore."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												a.m_kodebarang = c.m_kode " ;
							$stmtbuy = sqlsrv_query( $con_dbnew, $tsqlbuy);
							while( $rowbuy = sqlsrv_fetch_array( $stmtbuy, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$tsqlitem = "select m_kode, m_nama from msmaster where m_type = 'ITEM' and m_kode = '".$rowbuy['m_item']."'";
								$stmtitem = sqlsrv_query( $con_dbnew, $tsqlitem);
								$rowitem = sqlsrv_fetch_array( $stmtitem, SQLSRV_FETCH_ASSOC);

								$tqty = $tqty + $rowbuy['m_qty'] ;
								$ttot = $ttot + ( $rowbuy['m_qty'] * $rowbuy['m_harga'] ) ;
								$tbuy = $tbuy + ( $rowbuy['m_qty'] * $rowbuy['m_harga2'] ) ;
								?>
								<tr>
									<td>
										<input type="hidden" id="b_kodebarang<?php echo $i; ?>" name="b_kodebarang<?php echo $i; ?>" value="<?php echo $rowbuy['m_kodebarang']; ?>" />
                                    	<input class="input-small" type="text" id="b_productid<?php echo $i; ?>" name="b_productid<?php echo $i; ?>" value="<?php echo $rowbuy['m_productid']; ?>" readonly/>
									</td>
									<td><?php echo $rowbuy['co_namabarang']; ?></td>
									<td><?php echo $rowitem['m_nama']; ?></td>
									<td><div align="center"><input class="input-mini" type="text" id="b_qty<?php echo $i; ?>" name="b_qty<?php echo $i; ?>" value="<?php echo number_format($rowbuy['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly /></div></td>
									<td><input class="input-small" type="text" id="b_harga<?php echo $i; ?>" name="b_harga<?php echo $i; ?>" value="<?php echo number_format($rowbuy['m_harga'], 0, '.', ','); ?>" style="text-align:right" /></td>
									<td><input class="input-mini" type="text" id="b_cabang2<?php echo $i; ?>" name="b_cabang2<?php echo $i; ?>" value="<?php echo $rowbuy['m_cabang2']; ?>" style="color:#F00" readonly/></td>
									<td><input class="input-small" type="text" id="b_nomor2<?php echo $i; ?>" name="b_nomor2<?php echo $i; ?>" value="<?php echo $rowbuy['m_nomor2']; ?>" style="color:#F00" readonly/></td>
									<td><input class="input-medium" type="text" id="b_tanggal2<?php echo $i; ?>" name="b_tanggal2<?php echo $i; ?>" value="<?php echo $rowbuy['co_tgl'].' '.$rowbuy['co_jam']; ?>" style="color:#F00" readonly/></td>
									<td><input class="input-small" type="text" id="b_harga2<?php echo $i; ?>" name="b_harga2<?php echo $i; ?>" value="<?php echo number_format($rowbuy['m_harga2'], 0, '.', ','); ?>" style="text-align:right;color:#F00" readonly /></td>
									<td>
                                    	<input type="hidden" id="b_new<?php echo $i; ?>" name="b_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="b_hapus<?php echo $i; ?>" name="b_hapus<?php echo $i; ?>" /></div>
                                    </td>
								</tr>
								<?php
							}
						}
						
						$addrow = 1 ;
						while( $addrow <= 3 )
						{
							$addrow = $addrow + 1 ;
							$i = $i + 1 ;
							?>
							<tr>
								<td>
									<input type="hidden" id="b_kodebarang<?php echo $i; ?>" name="b_kodebarang<?php echo $i; ?>" value="" />
									<input class="input-small" type="text" id="b_productid<?php echo $i; ?>" name="b_productid<?php echo $i; ?>" value="" onClick="oc_cekasal('<?php echo $i; ?>')" style="cursor:pointer" readonly />
								</td>
								<td><input class="input-medium" type="text" id="b_group<?php echo $i; ?>" name="b_group<?php echo $i; ?>" value="" readonly/></td>
								<td><input class="input-medium" type="text" id="b_item<?php echo $i; ?>" name="b_item<?php echo $i; ?>" value="" readonly/></td>
								<td><div align="center"><input class="input-mini" type="text" id="b_qty<?php echo $i; ?>" name="b_qty<?php echo $i; ?>" value="1" style="text-align:center" readonly /></div></td>
								<td><input class="input-small" type="text" id="b_harga<?php echo $i; ?>" name="b_harga<?php echo $i; ?>" value="0" style="text-align:right" /></td>
								<td><input class="input-mini" type="text" id="b_cabang2<?php echo $i; ?>" name="b_cabang2<?php echo $i; ?>" value="" style="color:#F00" readonly/></td>
								<td><input class="input-small" type="text" id="b_nomor2<?php echo $i; ?>" name="b_nomor2<?php echo $i; ?>" value="" style="color:#F00" readonly/></td>
								<td><input class="input-medium" type="text" id="b_tanggal2<?php echo $i; ?>" name="b_tanggal2<?php echo $i; ?>" value=""  style="color:#F00" readonly/></td>
								<td><input class="input-small" type="text" id="b_harga2<?php echo $i; ?>" name="b_harga2<?php echo $i; ?>" value="0" style="text-align:right;color:#F00" readonly /></td>
								<td>
									<input type="hidden" id="b_new<?php echo $i; ?>" name="b_new<?php echo $i; ?>" value="Y" />
									<div align="center"><input type="checkbox" id="b_hapus<?php echo $i; ?>" name="b_hapus<?php echo $i; ?>" /></div>
								</td>
							</tr>
							<?php
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="12">
                        <div>
                            <div class="pull-right" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Save" />
                                <input type="button" class="btn btn-warning" id="bt_cancel" value="Cancel" onclick="cancel_data('<?php echo $prm; ?>','<?php echo $kdstore; ?>','<?php echo $periode; ?>')" />
                            </div>
                        </div>
                        </th>
                    </tr>
                </tfoot>
            </table>        
		</div>
    </form>

    <div id="dialog-listcust">
        <span id="datacust">
        </span>
    </div>
    
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			$('#datetimepicker1').datetimepicker({
				language: 'en',
				pickTime: false
			});
			
		$(function() {
			$( "#dialog-listcust" ).dialog({
				autoOpen: false,
				height:500,
				width:1100,
				modal: true,
				buttons: {
					"Close": function() {
						$( this ).dialog( "close" );
						}
			}
			});
		});
			
		});
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("resell.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					$("#datacust").html(respon);
				};
			$.get('pos-cekcustomer.php',data,fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}

		function selectcust(vkode,vnama,valamat,vkota,vtelepon)
		{
			document.getElementById('m_kodecust').value = vkode ;
			document.getElementById('m_nama').value = vnama ;

			$( "#dialog-listcust" ).dialog( "close" );
		}

		function oc_cekasal(rowke)
		{
			var data={tx:$('#m_kodecust').val(),rowke:rowke};
			var vkodecust = $('#m_kodecust').val() ;
			if ( vkodecust == '' )
			{
				alert('Harap input Customernya dahulu !!!');
			}
			else
			{
				var fungsi=function(respon){
						$("#datacust").html(respon);
					};
				$.get('tradein-cekasal.php',data,fungsi);
				
				$( "#dialog-listcust" ).dialog( "open" );
			}
		}

		function selectplu(rowke,vkdbrg,vgroup,vitem,vnoplu,vcab,vno,vtgl,vharga)
		{
			var vharga =  Number(vharga.replace(/,/g,""));
			
			var cekdouble = 'T';
			var tbl = document.getElementById('table_buyback');
			var lastRow = tbl.rows.length;
			var jumrow = lastRow - 2;

			for(var i=1; i <= jumrow; i++) 
			{
				var cekplu = $('#b_productid'+i).val();
				if ((vnoplu == cekplu) && (i != rowke ))
				{
					cekdouble = 'Y';
				}
			}
			if (cekdouble == 'T')
			{
				document.getElementById('b_kodebarang'+rowke).value = vkdbrg;
				document.getElementById('b_productid'+rowke).value = vnoplu;
				document.getElementById('b_group'+rowke).value = vgroup;
				document.getElementById('b_item'+rowke).value = vitem;
				document.getElementById('b_cabang2'+rowke).value = vcab;
				document.getElementById('b_nomor2'+rowke).value = vno;
				document.getElementById('b_tanggal2'+rowke).value = vtgl;
				document.getElementById('b_harga2'+rowke).value = formatangka(vharga.toFixed().toString()) ;
			}
			else
			{
				document.getElementById('b_kodebarang'+rowke).value = '';
				document.getElementById('b_productid'+rowke).value = '';
				document.getElementById('b_group'+rowke).value = '';
				document.getElementById('b_item'+rowke).value = '';
				document.getElementById('b_cabang2'+rowke).value = '';
				document.getElementById('b_nomor2'+rowke).value = '';
				document.getElementById('b_tanggal2'+rowke).value = '';
				document.getElementById('b_harga2'+rowke).value = 0 ;
			}

			$( "#dialog-listcust" ).dialog( "close" );
		}

		function validasi()
		{
			var tbl2 = document.getElementById('table_buyback');
			var lastRow2 = tbl2.rows.length;
		  	var jumrow2 = lastRow2 - 2;

			document.getElementById('jumrow').value = 0;
			document.getElementById('jumrow2').value = jumrow2;
			
			return true ;
		}

	</script>

    </body>
</html>