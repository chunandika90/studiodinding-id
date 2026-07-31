<?php
	session_start();
	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$nomor = base64_decode($_GET['nm']);
	$prm = base64_decode($_GET['prm']);
	$periode  = base64_decode($_GET['pr']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Harga LM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/jquery-ui.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		include "tabel-tgp.php";
	
		if ($nomor == '')
		{
			$tgl = date("d/m/Y") ;
			$jam = date("H:i:s") ;
			$ket = '' ;
			$totaljual = 0;
		}
		else
		{
			$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl, convert(varchar(8),a.m_tanggal,108) as co_jam from mshargalm a where a.m_nomor = '".$nomor."'" ;
			$stmt = sqlsrv_query( $con_dbnew, $tsql);
			$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;

			$tgl = $row['co_tgl'] ;
			$jam = $row['co_jam'] ;
			$ket = $row['m_keterangan'] ;
			$totaljual = $row['m_totaljual'] ;
		}		
		$dumb = explode('/',$tgl);
		$tanggal = $dumb[2].'-'.$dumb[1].'-'.$dumb[0].' 23:59:59';
		
		$tsqlld = "select m_jual from msrate where m_kode = 'LDLM' and m_tanggal = (select max(m_tanggal) from msrate where m_kode = 'LDLM' and m_tanggal <= '".$tanggal."')" ;
		$stmtld = sqlsrv_query($con_dbnew, $tsqlld);
		$rowld  = sqlsrv_fetch_array($stmtld, SQLSRV_FETCH_ASSOC);

    ?>
	<form class="form-horizontal" method="post" action="mshargalm-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 60%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'Harga LM' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tanggal</td>
                        <td colspan="3">
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                            <input type="hidden" id="periode" name="periode" value="<?php echo $periode; ?>" />
                        	<input type="hidden" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        	<input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly />
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    <tr>
                        <td>Plafond / hari</td>
                        <td colspan="3"><input class="input-medium" type="text" id="m_totaljual" name="m_totaljual" value="<?php echo $totaljual; ?>" /></td>
                    </tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 90%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20">No</th>
                        <th>Desc</th>
                        <th style="cursor:pointer" onclick="recalcm('<?php echo $kld ; ?>')">M</th>
                        <th>Beli</th>
                        <th>Jual B</th>
                        <th>Jual R</th>
                        <th>A.Beli</th>
                        <th>A.Jual</th>
                        <th>(+/-)</th>
                    </tr>
                </thead>
                <tbody>
					<?php
                    if ( $nomor == '' )
                    {	   
						$tsqlbrg = " select m_kode, m_nama, m_kode2 from msmaster where m_type = 'ITEM' and m_nama like 'FINE GOLD%' order by m_kode asc" ;
						$stmtbrg = sqlsrv_query($con_dbnew, $tsqlbrg);
						$i = 0 ;
						while( $rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC))
						{
							$i++;
							$dumb = explode('-',$rowbrg['m_kode2']);
							// Ambil ongkosnya
							$tsqlongkos = "	select a.m_beli, a.m_jual 
											from msongkoslm2 a, msongkoslm b 
											where a.m_nomor = b.m_nomor and a.m_kode = '".$rowbrg['m_kode']."' and b.m_tanggal = (select max(x.m_tanggal) from msongkoslm x, msongkoslm2 y where x.m_nomor = y.m_nomor and y.m_kode = '".$rowbrg['m_kode']."')" ;
							$stmtongkos = sqlsrv_query($con_dbnew, $tsqlongkos);
							$rowongkos = sqlsrv_fetch_array($stmtongkos, SQLSRV_FETCH_ASSOC);
							
							$mongkos = $rowongkos['m_beli'] ;
							$vmodal = ( $rowld['m_jual'] * $dumb[1] * 1.005 ) + $mongkos ;
							$vjual = ( $rowld['m_jual'] * $dumb[1] * 1.005 ) + $rowongkos['m_jual'] ;
							?>
                            <tr>
                                <td><?php echo $i; ?>
                                	<input type="hidden" id="m_kode<?php echo $i; ?>" name="m_kode<?php echo $i; ?>" value="<?php echo $rowbrg['m_kode']; ?>" />
                                	<input type="hidden" id="m_kode2<?php echo $i; ?>" name="m_kode2<?php echo $i; ?>" value="<?php echo $dumb[1]; ?>" />
                                	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
                                </td>
                                <td><input class="input-medium" type="text" id="m_nama<?php echo $i; ?>" name="m_nama<?php echo $i; ?>" value="<?php echo $rowbrg['m_nama']; ?>" readonly /></td>
                                <td><input class="input-small" type="text" id="m_modal<?php echo $i; ?>" name="m_modal<?php echo $i; ?>" value="<?php echo number_format($vmodal, 0, '.', ','); ?>" style="text-align:right" readonly /></td>
                                <td><input class="input-small" type="text" id="m_beli<?php echo $i; ?>" name="m_beli<?php echo $i; ?>" value="0" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_jualb<?php echo $i; ?>" name="m_jualb<?php echo $i; ?>" value="<?php echo number_format($vjual, 0, '.', ','); ?>" onChange="recalc(this.id,<?php echo $i; ?>)" style="text-align:right" /></td>
                                <td><input class="input-small" type="text" id="m_jual<?php echo $i; ?>" name="m_jual<?php echo $i; ?>" value="0" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_beli2<?php echo $i; ?>" name="m_beli2<?php echo $i; ?>" value="0" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_jual2<?php echo $i; ?>" name="m_jual2<?php echo $i; ?>" value="0" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_selisih<?php echo $i; ?>" name="m_selisih<?php echo $i; ?>" value="0" style="text-align:right" readonly /></td>
                           </tr>
							<?php
						}
					}
					else
					{
						$tsqlbrg = " select a.*, b.m_nama, b.m_kode2 from mshargalm2 a,msmaster b where a.m_nomor = '".$nomor."' and a.m_kode = b.m_kode and b.m_type = 'ITEM' order by a.m_kode asc" ;
						$stmtbrg = sqlsrv_query($con_dbnew, $tsqlbrg);
						$i = 0 ;
						while( $rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC))
						{
							$dumb = explode('-',$rowbrg['m_kode2']);
							$i++;
							?>
                            <tr>
                                <td><?php echo $i; ?>
									<input type="text" id="m_kode2<?php echo $i; ?>" name="m_kode2<?php echo $i; ?>" value="<?php echo $dumb[1]; ?>" />
                                	<input type="hidden" id="m_kode<?php echo $i; ?>" name="m_kode<?php echo $i; ?>" value="<?php echo $rowbrg['m_kode']; ?>" />
                                	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
								</td>
                                <td><input class="input-medium" type="text" id="m_nama<?php echo $i; ?>" name="m_nama<?php echo $i; ?>" value="<?php echo $rowbrg['m_nama']; ?>" readonly /></td>
                                <td><input class="input-small" type="text" id="m_modal<?php echo $i; ?>" name="m_modal<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_modal'], 0, '.', ','); ?>" style="text-align:right" readonly /></td>
                                <td><input class="input-small" type="text" id="m_beli<?php echo $i; ?>" name="m_beli<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_beli'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_jualb<?php echo $i; ?>" name="m_jualb<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_jualb'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_jual<?php echo $i; ?>" name="m_jual<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_jual'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_beli2<?php echo $i; ?>" name="m_beli2<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_beli2'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_jual2<?php echo $i; ?>" name="m_jual2<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_jual2'], 0, '.', ','); ?>" style="text-align:right" onChange="recalc(this.id,<?php echo $i; ?>)" /></td>
                                <td><input class="input-small" type="text" id="m_selisih<?php echo $i; ?>" name="m_selisih<?php echo $i; ?>" value="<?php echo number_format($rowbrg['m_jual'] - $rowbrg['m_jual2'], 0, '.', ','); ?>" style="text-align:right" readonly /></td>
                           </tr>
							<?php
						}
					}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="9">
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

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		function cancel_data(vparam, kdstore,periode)
		{
			window.open("mshargalm.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			document.getElementById('jumrow').value = jumrow;
			
			return true ;
		}

		function recalc(elid,rowke)
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
			var iteration = lastRow - 1;
			var tqty = 0 ;
			var tjumlah = 0 ;
			var beliantam = 0 ;
			var belimli = 0 ;
			if (elid == 'm_beli1')
			{
				var belimli = Number(document.getElementById('m_beli1').value.replace(/,/g,""));
			}

			for(var i=1; i <= iteration; i++) 
			{	
				var beli = Number(document.getElementById('m_beli' + i).value.replace(/,/g,""));
				var jual = Number(document.getElementById('m_jual' + i).value.replace(/,/g,""));
				var jual2 = Number(document.getElementById('m_jual2' + i).value.replace(/,/g,""));
				var jualb = Number(document.getElementById('m_jualb' + i).value.replace(/,/g,""));
				var stockmin = Number(document.getElementById('m_kode2' + i).value.replace(/,/g,""));
				if ( stockmin == 1 )
				{
					beliantam = Number(document.getElementById('m_beli2' + i).value.replace(/,/g,""));
				}
				var beli2 = Number(document.getElementById('m_kode2' + i).value.replace(/,/g,"")) * beliantam;
				
				if ( elid == 'm_beli1' )
				{
					beli = Number(document.getElementById('m_kode2' + i).value.replace(/,/g,"")) * belimli;
				}

				if (( elid.substring(0,7) == 'm_jual2' ) && (elid != 'm_jual2') && (elid == 'm_jual2' + i))
				{
					jual = Number(document.getElementById('m_jual2' + i).value.replace(/,/g,""));
				}

				var selisih = jual - jual2 ;
				document.getElementById('m_beli' + i).value = formatangka(beli.toFixed().toString()) ;
				document.getElementById('m_jualb' + i).value = formatangka(jualb.toFixed().toString()) ;
				document.getElementById('m_jual' + i).value = formatangka(jual.toFixed().toString()) ;
				document.getElementById('m_jual2' + i).value = formatangka(jual2.toFixed().toString()) ;
				document.getElementById('m_beli2' + i).value = formatangka(beli2.toFixed().toString()) ;
				document.getElementById('m_selisih' + i).value = formatangka(selisih.toFixed().toString()) ;
			}
		}

	</script>

    </body>
</html>