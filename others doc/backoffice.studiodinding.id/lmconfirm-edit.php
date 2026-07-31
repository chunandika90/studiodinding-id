<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	$kdcab = base64_decode($_GET['cb']);
	$nomor = base64_decode($_GET['nm']);
	$prm = base64_decode($_GET['prm']);
	$kdstore = base64_decode($_GET['st']);
	$periode  = base64_decode($_GET['pr']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>LM.Transfer</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    </head>

    <body>
    <?php
        include "mssql-dbnew.php" ;
		
		$tsql = "select a.*, convert(varchar(10),a.m_tanggal,103) as co_tgl from t_transfer a where a.m_cabang = '".$kdcab."' and a.m_nomor = '".$nomor."' " ;
		$stmt = sqlsrv_query( $con_dbnew, $tsql);
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ;
		
		$tgl = $row['co_tgl'] ;
		$nama = $row['m_nama'] ;
		$lokasi = $row['m_lokasi'] ;
		$lokasi2 = $row['m_lokasi2'] ;
		$ket = $row['m_keterangan'] ;
		$status = $row['m_status'] ;
		$kurir = $row['m_kurir'] ;
		$tjaws = $row['m_outid'] ;
		$tkodebarang = $row['m_kodebarang'] ;
		
    ?>
	<form class="form-horizontal" method="post" action="lmconfirm-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 50%; padding: 0 10px;">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo $kdcab.' '.$nomor ; ?></h4></th>
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
                        	<input type="hidden" id="m_outid" name="m_outid" value="<?php echo $tjaws; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="75">Tanggal</td>
                        <td width="150"><input class="input-medium" data-format="dd/MM/yyyy" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td colspan="3"><input class="input-xlarge" type="text" id="m_nama" name="m_nama" value="<?php echo $nama; ?>" readonly /></td>
                    </tr>
                    <tr>
                        <td>From</td>
                        <td><input type="hidden" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" />
                            <select name="s_lokasi" id="s_lokasi" class="input-large" disabled>
                                <?php
                                $tsqllok = "select m_kode, m_nama from msmaster where m_type = 'LOKASI' and left(m_kode,2) = '".$kdcab."' order by m_nama asc" ;
                                $stmtlok= sqlsrv_query( $con_dbnew, $tsqllok);
                                while( $rowlok = sqlsrv_fetch_array( $stmtlok, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowlok['m_kode']; ?>" <?php if ($rowlok['m_kode'] == $lokasi){ ?> selected="selected" <?php }   ?> ><?php echo $rowlok['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>To</td>
                        <td><input type="hidden" id="m_lokasi2" name="m_lokasi2" value="<?php echo $lokasi2; ?>" />
                            <select name="m_lokasi2" id="m_lokasi2" class="input-large" disabled>
                                <?php
								if($_SESSION['store'] <> '00')
								{
	                                $tsqllok2 = "select m_kode, m_nama from msmaster where m_type = 'LOKASI' and m_kode = '".$lokasi2."' order by m_nama asc" ;
								}
								else
								{
	                                $tsqllok2 = "select m_kode, m_nama from msmaster where m_type = 'LOKASI' and m_kode <> '".$lokasi."' order by m_nama asc" ;
								}
                                $stmtlok2 = sqlsrv_query( $con_dbnew, $tsqllok2);
                                while( $rowlok2 = sqlsrv_fetch_array( $stmtlok2, SQLSRV_FETCH_ASSOC))
                                {
                                    ?>
                                    <option value="<?php echo $rowlok2['m_kode']; ?>" <?php if ($rowlok2['m_kode'] == $lokasi2){ ?> selected="selected" <?php }   ?> ><?php echo $rowlok2['m_nama']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>Kurir</td>
                        <td><input class="input-xlarge" type="text" id="m_kurir" name="m_kurir" value="<?php echo $kurir; ?>" readonly /></td>
                        
                         <td>Kategori Barang</td>
                         <td>
                                <?php
								$tsqlbrg = "select m_kode, m_nama from msbarang where m_kode = '".$tkodebarang."' order by m_nama asc" ;
                                $stmtbrg = sqlsrv_query( $con_dbnew, $tsqlbrg);
								$rowbrg = sqlsrv_fetch_array( $stmtbrg, SQLSRV_FETCH_ASSOC) ;
                                ?>
                                <input type="hidden" id="m_kodebarang" name="m_kodebarang" value="<?php echo $tkodebarang; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nmbarang" name="m_nmbarang" value="<?php echo $rowbrg['m_nama']; ?>" readonly />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    	<div class="container pull-left row-fluid" style="width: 70%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>LM</th>
                        <th>Product ID</th>
                        <th>Qty</th>
                        <th>Berat/pcs</th>
                        <th>T.Berat</th>
                        <th><div align="center">Confirm</div></th>
                        <th>Keterangan</th>                        
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
                        $tberat = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, b.m_item, b.m_netweight, c.m_nama as co_namaitem
										from 	t_transfer2 a, t_stockdata b, msmaster c 
										where 	a.m_cabang = '".$kdcab."' and 
												a.m_nomor = '".$nomor."' and 
												a.m_kodebarang = b.m_kodebarang and 
												a.m_productid = b.m_productid and
												c.m_type = 'ITEM' and 
												b.m_item = c.m_kode " ;
							$stmt2 = sqlsrv_query( $con_dbnew, $tsql2);
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))
							{	
								$i = $i + 1 ;
								$tqty = $tqty + $row2['m_qty'] ;
								$tberat = $tberat + $row2['m_netweight'] ;
								?>
								<tr>
									<td><?php echo number_format($i, 0, '.', ','); ?></td>
									<td><?php echo $row2['co_namaitem']; ?></td>
									<td>
										<input type="hidden" id="m_kodebarang<?php echo $i; ?>" name="m_kodebarang<?php echo $i; ?>" value="<?php echo $row2['m_kodebarang']; ?>" />
                                    	<input class="input-small" type="text" id="m_productid<?php echo $i; ?>" name="m_productid<?php echo $i; ?>" value="<?php echo $row2['m_productid']; ?>" readonly/>
									</td>
									<td><?php echo number_format($row2['m_qty'], 0, '.', ','); ?></td>
									<td><?php echo number_format($row2['m_netweight'], 2, '.', ','); ?></td>
									<td><?php echo number_format($row2['m_qty'] * $row2['m_netweight'], 2, '.', ','); ?></td>
									<td>
                                    	<div align="center"><input type="checkbox" id="m_confirm<?php echo $i; ?>" name="m_confirm<?php echo $i; ?>" checked /></div>
                                    </td>
									<td><input class="input-xlarge" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="<?php echo $row2['m_keterangan']; ?>" /></td>
								</tr>
								<?php
							}
						}
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8">
                        <div>
                            <div class="pull-left" >
                                <input type="submit" class="btn btn-primary" id="bt_save" value="Confirm" />
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
		$(function() {
		$('#datetimepicker1').datetimepicker({
			language: 'en',
			pickTime: false
		});
		});
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("lmconfirm.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;

			document.getElementById('jumrow').value = jumrow;
			
			return true ;
		}


	</script>

    </body>
</html>