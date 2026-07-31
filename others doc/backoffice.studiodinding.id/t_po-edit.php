<?php
	session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

	date_default_timezone_set('Asia/Bangkok');
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	//$nomor = base64_decode(strtr($_GET['nm'], '-_,', '+/='));
	$raw_nm = isset($_GET['nm']) ? $_GET['nm'] : '';
	$decode_nm = base64_decode(strtr($raw_nm, '-_,', '+/='), true); // strict mode: true

	if ($decode_nm === false) {
		$nomor = '';
	} else {
		$nomor = $decode_nm;
	}
	
	
	$periode  = base64_decode($_GET['pr']);
	$prm = base64_decode($_GET['prm']);
	
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>PO (Purchase Order)</title>
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
			// Data tidak ditemukan
			$tgl = date("d/m/Y") ;
			$m_tanggal_kirim = date("d/m/Y") ;
			$jam = date("H:i:s") ;
			$namaproject = '' ;
			$kdproject = '' ;
			$lokasi = '' ;
			$namaclient = '' ;
			$supervisor = '' ;
			$nama_supervisor = '' ;
			$kdsupplier = '';
			$namasupplier = '';
			$alamatsupplier = '';
			$telepon = '';
			$picsupplier = '';
			$ket = '' ;
			$type = '' ;
			$jumlah_rp = 0 ;
			$ppn = 0 ;
			$jumlah_ppn = 0 ;
			$diskon = 0 ;
			$jumlah_diskon = 0 ;
				$jumlah_diskon2 = 0 ;
			$total_rp = 0;
			$status = 'A' ;
			$m_payment_term = '' ;
		}
		else
		{
			$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, DATE_FORMAT(a.m_tanggal_kirim, '%d/%m/%Y') AS m_tanggal_kirim,DATE_FORMAT(a.m_tanggal, '%H:%i:%s') AS co_jam , 
					 b.supervisor_project as m_nama_supervisor, b.nama_project as nama_project, 
					 b.m_lokasi, b.m_alamat, b.nama_client m_namaclient, b.supervisor_project m_nama_supervisor, c.alamat m_alamat_supplier, 
					 c.contact_person  m_picsupplier,c.nomor_telepon,
					 case when a.m_jumlah_qty = a.m_terima_qty then 'Complete' else 'Not Complete' end m_status, c.m_nama as m_nama_supplier,
					 ifnull(a.m_jumlah_rp,0) jumlah_rp, ifnull(a.m_ppn_persen,0) m_ppn_persen, ifnull(a.m_ppn_jumlah,0)m_ppn_jumlah, 
					 ifnull(a.m_diskon_persen,0) m_diskon_persen , ifnull(a.m_diskon_jumlah,0) m_diskon_jumlah, ifnull(a.m_total_rp,0) m_total_rp,
					 case when a.m_type is null or a.m_type = '' then 'General' else a.m_type end m_type, ifnull(a.m_diskon2_jumlah,0) m_diskon2_jumlah
					 from t_po a, master_project b, master_supplier c
					 where a.m_kode_project = b.m_kode and a.m_kode_supplier = c.m_kode and 	
					 a.m_nomor = '".$nomor."' 
					 " ;
			$stmt = $con_dbnew->query($tsql);
			$row = $stmt->fetch_assoc();
			if ($row) 
			{
				$tgl = $row['co_tgl'] ;
				$jam = $row['co_jam'] ;
				$namaproject = $row['m_nama_project'] ;
				$kdproject = $row['m_kode_project'] ;
				$lokasi = $row['m_lokasi'] ;
				$namaclient = $row['m_namaclient'] ;
				$supervisor = $row['m_nama_supervisor'] ;
				$kdsupplier = $row['m_kode_supplier'] ;
				$namasupplier = $row['m_nama_supplier'] ;
				$alamatsupplier = $row['m_alamat_supplier'] ;
				$telepon = $row['nomor_telepon'] ;
				$picsupplier = $row['m_picsupplier'] ;
				$ket = $row['m_keterangan'] ;
				$type = $row['m_type'] ;
				$status = $row['m_status'] ;
				$jumlah_rp = $row['jumlah_rp'] ;
				$ppn = $row['m_ppn_persen'] ;
				

				$jumlah_ppn = $row['m_ppn_jumlah'] ;
				$diskon = $row['m_diskon_persen'] ;
				$jumlah_diskon = $row['m_diskon_jumlah'] ;
				$jumlah_diskon2 = $row['m_diskon2_jumlah'] ;
				$total_rp = $row['m_total_rp'] ;
				$m_payment_term = $row['m_payment_term'] ;
				$m_tanggal_kirim = $row['m_tanggal_kirim'] ;
			} else {
				// Data tidak ditemukan
				$tgl = date("d/m/Y") ;
				$jam = date("H:i:s") ;
				$namaproject = '' ;
				$kdproject = '' ;
				$lokasi = '' ;
				$namaclient = '' ;
				$supervisor = '' ;
				$nama_supervisor = '' ;
				$kdsupplier = '';
				$namasupplier = '';
				$alamatsupplier = '';
				$telepon = '';
				$picsupplier = '';
				$ket = '' ;
				$type = '' ;
				$status = 'A' ;
				$jumlah_rp = 0 ;
				$ppn = 0 ;
				$jumlah_ppn = 0 ;
				$diskon = 0 ;
				$jumlah_diskon = 0 ;
				$jumlah_diskon2 = 0 ;
				$total_rp = 0;
				$m_payment_term = '' ;
			}
		}
		
    ?>
	<form class="form-horizontal" method="post" action="t_po-simpan.php"  onsubmit="return validasi()">
    	<div class="container pull-left row-fluid" style="width: 100%; padding: 0 10px;">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4"><h4><?php echo 'PO (Purchase Order)' ; ?></h4></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="100">Nomor</td>
                        <td width="10">
                            <input type="hidden" id="periode" name="periode" value="<?php echo $_GET['pr']; ?>" />
                            
                        	<input type="hidden" id="m_cabang" name="m_cabang" value="<?php echo $kdcab; ?>" />
                            <input type="hidden" id="m_status" name="m_status" value="<?php echo $status; ?>" />
                            <input type="hidden" id="param" name="param" value="<?php echo $prm; ?>" />
                            <input type="hidden" id="jumrow" name="jumrow" value="0" />
                        	<input class="input-medium" type="text" id="m_nomor" name="m_nomor" value="<?php echo $nomor; ?>" readonly />
                        </td>
                        <td width="50">Tanggal</td>
                        <td width="350"><input class="input-medium" type="text" id="m_tanggal" name="m_tanggal" value="<?php echo $tgl.' '.$jam; ?>" readonly /></td>
                    </tr>
                     <tr>
                        <td>Project</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kode_project" name="m_kode_project" value="<?php echo $kdproject; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama_project" name="m_nama_project" value="<?php echo $namaproject; ?>" required onchange="listproject()"/>
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listproject()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td><input class="input-medium" type="text" id="m_lokasi" name="m_lokasi" value="<?php echo $lokasi; ?>" readonly/></td>
                        <td>Nama Client</td>
                        <td><input class="input-medium" type="text" id="m_namaclient" name="m_namaclient" value="<?php echo $namaclient; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>Supervisor</td>
                        <td colspan="3"><input class="input-medium" type="text" id="m_supervisor" name="m_supervisor" value="<?php echo $supervisor; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>Vendor / Supplier</td>
                        <td colspan="3">
                            <div id="divinputcust" class="input-append">
                                <input class="input-medium" type="text" id="m_kode_supplier" name="m_kode_supplier" value="<?php echo $kdsupplier; ?>" readonly />
                                <input class="input-xlarge" type="text" id="m_nama_supplier" name="m_nama_supplier" value="<?php echo $namasupplier; ?>" required  onchange="listsupp()"/>
                                <span class="add-on"><i class="icon-search" style="cursor:pointer" onClick="listsupp()"></i></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td colspan="3">
				            <textarea class="input-xxlarge" name="m_alamat_supplier" id="m_alamat_supplier" cols="200" rows="2" readonly><?php echo $alamatsupplier; ?> </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><input class="input-medium" type="text" id="m_telepon" name="m_telepon" value="<?php echo $telepon; ?>" readonly/></td>
                        <td>Attention</td>
                        <td><input class="input-medium" type="text" id="m_picsupplier" name="m_picsupplier" value="<?php echo $picsupplier; ?>" readonly/></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="3"><input class="input-xxlarge" type="text" id="m_keterangan" name="m_keterangan" value="<?php echo $ket; ?>" /></td>
                    </tr>
                    <tr>
                        <td colspan = "2"></td>
                        <td>Total harga</td>
                        <td><input class="input-medium" type="text" id="m_jumlah_rp" name="m_jumlah_rp" value="<?php echo number_format($jumlah_rp ?? 0, 0, '.', ','); ?>" style="text-align:right"  onchange="recalc_header()"/></td>
                    </tr>
                    <tr>
                        <td>Diskon %</td>
                        <td><input class="input-medium" type="text" id="m_diskon_persen" name="m_diskon_persen" value="<?php echo number_format($diskon ?? 0, 2, '.', ','); ?>"  style="text-align:right" onchange="recalc_header()"/></td>
                        <td>Jumlah Diskon</td>
                        <td><input class="input-medium" type="text" id="m_diskon_jumlah" name="m_diskon_jumlah" value="<?php echo number_format($jumlah_diskon ?? 0, 0, '.', ','); ?>" style="text-align:right"  readonly/></td>
                    </tr>
                    <tr>
                        <td colspan= "3">Diskon 2 RP</td>
                        <td><input class="input-medium" type="text" id="m_diskon2_jumlah" name="m_diskon2_jumlah" value="<?php echo number_format($jumlah_diskon2 ?? 0, 0, '.', ','); ?>" style="text-align:right" onchange="recalc_header()"/></td>
                    </tr>
                    <tr>
                        <td>PPN %</td>
                        <td><input class="input-medium" type="text" id="m_ppn_persen" name="m_ppn_persen" value="<?php echo number_format($ppn ?? 0, 2, '.', ','); ?>" style="text-align:right"  onchange="recalc_header()"/></td>
                        <td>Jumlah PPN</td>
                        <td><input class="input-medium" type="text" id="m_ppn_jumlah" name="m_ppn_jumlah" value="<?php echo number_format($jumlah_ppn ?? 0, 0, '.', ','); ?>" style="text-align:right" readonly/></td>
                    </tr>
                    <tr>
                        <td colspan = "2"></td>
                        <td>Total Akhir</td>
                        <td><input class="input-medium" type="text" id="m_total_rp" name="m_total_rp" value="<?php echo number_format($total_rp ?? 0, 0, '.', ','); ?>" style="text-align:right" readonly /></td>
                    </tr>
					<tr>
						<td>Type</td>
						<td colspan="3">
							<select class="input-xlarge" id="m_type" name="m_type">
								<option value="" <?php echo ($type == '') ? 'selected' : ''; ?>>-- Pilih Type --</option>
								<option value="general" <?php echo ($type == 'general') ? 'selected' : ''; ?>>General</option>
								<option value="interior" <?php echo ($type == 'interior') ? 'selected' : ''; ?>>Interior</option>
								<option value="struktur" <?php echo ($type == 'struktur') ? 'selected' : ''; ?>>Struktur</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Term Pembayaran</td>
						<td colspan="3">
							<select name="m_payment_term" id="m_payment_term" class="input-xlarge">
								<option value="" <?php echo ($m_payment_term == '') ? 'selected' : ''; ?>>-- Pilih Term Pembayaran --</option>
								<?php
								// pastiin variabel ada biar gak warning
								$m_payment_term = isset($m_payment_term) ? $m_payment_term : '';

								$tsqlcara = "SELECT a.m_kode, a.m_nama FROM master_term_pembayaran a ORDER BY a.m_kode ASC";
								$stmtcara = $con_dbnew->query($tsqlcara);

								if ($stmtcara) {
									while ($rowcara = $stmtcara->fetch_assoc()) {
										$selected = ($rowcara['m_kode'] == $m_payment_term) ? 'selected' : '';
										?>
										<option value="<?php echo htmlspecialchars($rowcara['m_kode']); ?>" <?php echo $selected; ?>>
											<?php echo htmlspecialchars($rowcara['m_nama']); ?>
										</option>
										<?php
									}
								} else {
									echo '<option value="">-- Data tidak tersedia --</option>';
								}
								?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td data-label="Keterangan">Tanggal Kirim</td>
						<td colspan="3" data-label="Input Tanggal">
							<div class="input-group date" id="datepicker_m_tanggal_kirim">
								<input type="text" name="m_tanggal_kirim" id="m_tanggal_kirim" 
									   class="input-medium form-control" 
									   value="<?php echo $m_tanggal_kirim; ?>" />
								<span class="input-group-addon"><i class="icon-calendar"></i> *DD-MM-YYYY</span>
							</div>
						</td>
					</tr>
                </tbody>
            </table>
        </div>

    	<div class="container pull-left row-fluid" style="width: 60%; padding: 0 10px;">
            <table id="table_data" class="table table-bordered table-striped table-hover table-condensed">
                <thead>
                    <tr>
                        <th>No</th>
                        <th onclick="listrequest('')" style="text-align:center; text-decoration:underline; font-weight:bold; cursor:pointer;">Material</th>
                        <th>Keterangan</th>
                        <th><div align="center">Qty</div></th>
                        <th><div align="center">Unit</div></th>
                        <th><div align="center">Unit Price</div></th>
                        <th><div align="center">Diskon</div></th>
                        <th><div align="center">Harga Akhir</div></th>
                        <th><div align="center">Total Akhir</div></th>
                        <th><div align="center">DEL</div></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$i = 0 ;
                        $tqty = 0 ;
						$ttot = 0 ;
						$ttotal = 0 ;
						$tdiskonrp = 0 ;
						$takhir = 0 ;
						
						if ($nomor != '')
						{
							$tsql2 = "	select 	a.*, c.m_nama as co_namabarang
										from 	t_po2 a, master_item c 
										where 	a.m_nomor = '".$nomor."' and 
												a.m_item = c.m_kode   " ;
							//echo $tsql2;
							$stmt2 = $con_dbnew->query($tsql2);
                            while( $row2 = $stmt2->fetch_assoc())
							{	
								$i = $i + 1 ;
								
								$tqty = $tqty + $row2['m_qty'] ;
								$ttotal = $ttotal + ($row2['m_qty'] * $row2['m_harga']) ;
								$tdiskonrp = $tdiskonrp + $row2['m_diskon_rp'];
								$takhir = $takhir + ($row2['m_qty'] * $row2['m_harga'])  - $row2['m_diskon_rp'];
								
								?>
								<tr>
									<td><?php echo $i; ?> </td>
									<td><input class="input-xlarge" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value="<?php echo $row2['co_namabarang']; ?>" style="text-align:center;cursor:pointer"  onchange="listitem(<?php echo $i; ?>)" />
										<input class="input-small" type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value="<?php echo $row2['m_item']; ?>"/>
										<input class="input-small" type="hidden" id="m_nomor_request<?php echo $i; ?>" name="m_nomor_request<?php echo $i; ?>" value="<?php echo $row2['m_nomor_request']; ?>"/>
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $row2['m_no']; ?>"/>
									</td>
									<td><div align="center"><textarea class="input-large" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" style="width:300px; height:120px; resize:vertical;" /><?php echo htmlspecialchars($row2['m_keterangan']); ?></textarea></div></td>
									<td><div align="center"><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="<?php echo number_format($row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc()" /></div></td>
									<td><div align="center"><div align="center"><input class="input-mini" type="text" id="m_unit<?php echo $i; ?>" name="m_unit<?php echo $i; ?>" value="<?php echo $row2['m_unit']; ?>" style="text-align:center" /></div></td>
									<td><div align="center"><div align="center"><input class="input-large" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="<?php echo number_format($row2['m_harga'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc()" /></div></td>
									<td><div align="center"><div align="center"><input class="input-medium" type="text" id="m_diskon_rp<?php echo $i; ?>" name="m_diskon_rp<?php echo $i; ?>" value="<?php echo number_format($row2['m_diskon_rp'], 0, '.', ','); ?>" style="text-align:center" onChange="recalc()"/></div>
									<td><div align="center"><div align="center"><input class="input-large" type="text" id="m_total<?php echo $i; ?>" name="m_total<?php echo $i; ?>" value="<?php echo number_format($row2['m_harga']*$row2['m_qty'], 0, '.', ','); ?>" style="text-align:center" readonly/></div></td>
										
									</td>
									
									<td><div align="center"><div align="center"><input class="input-large" type="text" id="m_total_akhir<?php echo $i; ?>" name="m_total_akhir<?php echo $i; ?>" value="<?php echo number_format(($row2['m_harga']*$row2['m_qty'])-$row2['m_diskon_rp'], 0, '.', ','); ?>" style="text-align:center" readonly/></div></td>
									<td>
                                    	<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="T" />
                                    	<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
                                    </td>
								</tr>
								<?php
							}
						}
						else
						{
							$addrow = 0 ;
							/*
							while( $addrow <= 1 )
							{
								$addrow = $addrow + 1 ;
								$i = $i + 1 ;
								?>
								<tr> 
									<td><?php echo $i; ?> </td>
                                    <td><input class="input-medium" type="text" id="m_nmitem<?php echo $i; ?>" name="m_nmitem<?php echo $i; ?>" value=""  onchange="listitem('<?php echo $i; ?>')" style="text-align:center" onchange="listitem(<?php echo $i; ?>)" />
										<input class="input-small" type="hidden" id="m_item<?php echo $i; ?>" name="m_item<?php echo $i; ?>" value=""/>
										<input class="input-small" type="hidden" id="m_no<?php echo $i; ?>" name="m_no<?php echo $i; ?>" value="<?php echo $i; ?>"/>
										<input class="input-small" type="hidden" id="m_nomor_request<?php echo $i; ?>" name="m_nomor_request<?php echo $i; ?>" value="<?php echo $i; ?>"/>
									</td>	
                                    <td><div align="center"><input class="input-medium" type="text" id="m_keterangan<?php echo $i; ?>" name="m_keterangan<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_qty<?php echo $i; ?>" name="m_qty<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_unit<?php echo $i; ?>" name="m_unit<?php echo $i; ?>" value="" style="text-align:center" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_harga<?php echo $i; ?>" name="m_harga<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td><div align="center"><input class="input-mini" type="text" id="m_total<?php echo $i; ?>" name="m_total<?php echo $i; ?>" value="" style="text-align:center" onchange="recalc()" /></div></td>
									<td>
										<input type="hidden" id="m_new<?php echo $i; ?>" name="m_new<?php echo $i; ?>" value="Y" />
										<div align="center"><input type="checkbox" id="m_hapus<?php echo $i; ?>" name="m_hapus<?php echo $i; ?>" /></div>
									</td>
								</tr>
								<?php
							}
							*/
						}
                    ?>
                </tbody>
                <tfoot>           
                    <tr>
                        <th colspan="3"></th>
                        <th><div id="sp-totqty" align="center"><?php echo number_format($tqty, 0, '.', ','); ?></div></th>
                        <th></th>
                        <th></th>	
                        <th></th>	
                        <th></th>
                        <th><div id="sp-totakhir" align="center"><?php echo number_format($takhir, 0, '.', ','); ?></div></th>
                        <th></th>	
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="10">
                        <div>
                            <div class="pull-left" >
                                <input type="button" class="btn btn-success" id="bt_tambah" value="Add Row" onclick="add_data()" />
                            </div>
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

    <div id="tempdata" class="hide">
        <span id="dataplu">
            <input type="text" id="cek_kodebarang" name="cek_kodebarang" value="" />
            <input type="text" id="cek_noplu" name="cek_noplu" value="1" />
            <input type="text" id="cek_item" name="cek_item" value="" />
            <input type="text" id="cek_group" name="cek_group" value="" />
            <input type="text" id="cek_harga" name="cek_harga" value="0" />
            <input type="text" id="cek_karet" name="cek_karet" value="0" />
        </span>
    </div>         
    
    <div id="dialog-listcust" title="Daftar Project">
    <div id="datacust"></div>
	</div>
	
    <div id="dialog-listproject" title="Daftar Project">
    <div id="dataproject"></div>
	</div>
	
    <div id="dialog-listsupp" title="Daftar Supplier">
    <div id="datasupp"></div>
	</div>
	
	<div id="dialog-listrequest" title="Daftar Request">
	  <div id="datarequest" class="dialog-content-scroll"></div>
	</div>
	
	<div id="dialog-listitem" title="Daftar Request">
	  <div id="dataitem" class="dialog-content-scroll"></div>
	</div>
	
    
	<style>
    //* Umum, untuk semua dialog list */
	#dialog-listcust, 
	#dialog-listitem,
	#dialog-listproject,
	#dialog-listsupp,
	#dialog-listrequest {
		font-family: Arial, sans-serif;
		font-size: 13px;
	}

	/* Styling tabel di dalam masing-masing dialog */
	#dialog-listcust table,
	#dialog-listitem table,
	#dialog-listproject table,
	#dialog-listsupp table,
	#dialog-listrequest table  {
		border-collapse: collapse;
		width: 100%;
	}

	#dialog-listcust th, 
	#dialog-listcust td,
	#dialog-listitem th, 
	#dialog-listitem td,
	#dialog-listproject th, 
	#dialog-listproject td,
	#dialog-listsupp th, 
	#dialog-listsupp td,
	#dialog-listrequest th, 
	#dialog-listrequest td {
		border: 1px solid #ddd;
		padding: 8px;
	}

	#dialog-listcust th,
	#dialog-listitem th,
	#dialog-listproject th,
	#dialog-listsupp th,
	#dialog-listrequest th {
		background: #f4f4f4;
		font-weight: bold;
		text-align: left;
	}

	#dialog-listcust tr:nth-child(even),
	#dialog-listitem tr:nth-child(even),
	#dialog-listproject tr:nth-child(even),
	#dialog-listsupp tr:nth-child(even),
	#dialog-listrequest tr:nth-child(even) {
		background-color: #f9f9f9;
	}

	#dialog-listcust tr:hover,
	#dialog-listitem tr:hover,
	#dialog-listproject tr:hover,
	#dialog-listsupp tr:hover,
	#dialog-listrequest tr:hover {
		background-color: #f1f1f1;
	}

	/* Scroll khusus konten dialog */
	.ui-dialog .ui-dialog-content {
		overflow: hidden !important;
		padding: 0 !important;
	}
	
	body.ui-dialog-open {
		overflow: hidden !important;
	}

	.dialog-content-scroll {
		max-height: 90vh;
		overflow-y: auto;
	}
	/* 🔧 Fix double scrollbar dan jaga posisi tengah */
	.ui-dialog {
		overflow: hidden !important; /* hilangin scrollbar luar */
	}

	.ui-dialog .ui-dialog-content {
		overflow: hidden !important; /* hilangin scrollbar default jQuery UI */
		padding: 0 !important;
	}

	/* scroll hanya di area konten tabel */
	.dialog-content-scroll {
		max-height: calc(80vh - 80px);
		overflow-y: auto;
		padding-bottom: 8vh; /* kasih ruang sekitar 8% tinggi viewport */
	}
	
	


</style>
	
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="js/myjs.js"></script>
    <script type="text/javascript">
		$(function() {
			// Inisialisasi datetimepicker untuk tanggal kirim
			$('#datepicker_m_tanggal_kirim').datetimepicker({
				format: 'dd/MM/yyyy',
				minView: 2,             // cuma tampil hari, tanpa jam
				todayHighlight: true,   // highlight hari ini
				autoclose: true,        // otomatis close setelah pilih tanggal
				container: 'body'       // biar posisi popup gak error di dalam table
			});
			

			// dialog list customer
			$("#dialog-listcust").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});
			
			// dialog list project
			$("#dialog-listproject").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});
			
			// dialog list supplier
			$("#dialog-listsupp").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});

			// inisialisasi dialog list request
			$("#dialog-listrequest").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: [
					{ text: "Submit", click: function() { addSelectedRequests(); } },
					{ text: "Close", click: function() { $(this).dialog("close"); } }
				],
				open: function() {
					// mark body lagi buka dialog biar body gak scroll
					$("body").addClass("ui-dialog-open");
					// pastiin posisinya center
					$(this).parent().position({ my: "center", at: "center", of: window });
				},
				close: function() {
					// kalau ditutup, boleh scroll lagi
					$("body").removeClass("ui-dialog-open");
				}
			});

			// kalau user resize window, tetap center
			$(window).on("resize", function() {
				$(".ui-dialog:visible").each(function() {
					$(this).position({
						my: "center",
						at: "center",
						of: window
					});
				});
			});
			
			$("#dialog-listitem").dialog({
				autoOpen: false,
				height: $(window).height() * 0.8,
				width: $(window).width() * 0.8,
				modal: true,
				buttons: {
					"Close": function() {
						$(this).dialog("close");
					}
				}
			});

			// update ukuran saat resize window
			$(window).resize(function() {
				$("#dialog-listcust").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listcust").dialog("option", "height", $(window).height() * 0.8);
				
				$("#dialog-listproject").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listproject").dialog("option", "height", $(window).height() * 0.8);
				
				$("#dialog-listsupp").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listsupp").dialog("option", "height", $(window).height() * 0.8);

				$("#dialog-listitem").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listitem").dialog("option", "height", $(window).height() * 0.8);
				
				// di bagian window.resize handler tambahkan:
				$("#dialog-listrequest").dialog("option", "width", $(window).width() * 0.9);
				$("#dialog-listrequest").dialog("option", "height", $(window).height() * 0.8);
			});
		});
		
		$(function() {
		$( "#dialog-material" ).dialog({
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
		
  	
		function cancel_data(vparam,kdstore,periode)
		{
			window.open("pos.php?st="+base64_encode(kdstore)+'&pr='+base64_encode(periode)+'&prm='+base64_encode(vparam),'_self');
		}

		
		
		function listcust()
		{
			var data={tx:$('#m_nama').val()};

			var fungsi=function(respon){
					
				$("#datacust").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_po-cekproject.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listcust" ).dialog( "open" );
		}
		

		function listproject()
		{
			var data={tx:$('#m_nama_project').val()};

			var fungsi=function(respon){
					
				$("#dataproject").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_po-cekproject.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listproject" ).dialog( "open" );
		}
		
		
		function selectproject(vkode,vnama,valamat,vlokasi,vclient,vsupervisor)
		{
			
			document.getElementById('m_kode_project').value = vkode ;
			document.getElementById('m_nama_project').value = vnama ;
			document.getElementById('m_lokasi').value = vlokasi ;
			document.getElementById('m_namaclient').value = vclient ;
			document.getElementById('m_supervisor').value = vsupervisor ;
			
			$( "#dialog-listproject" ).dialog( "close" );
		}
		
		
		function listsupp()
		{
			var data={tx:$('#m_nama_supplier').val()};

			var fungsi=function(respon){
					
				$("#datasupp").html("<div class='result-wrapper'>" + respon + "</div>");
				};
			$.get('t_po-ceksupplier.php?rnd=' + new Date().getTime(), data, fungsi);
			
			$( "#dialog-listsupp" ).dialog( "open" );
		}
		
		
		function selectsupp(vkode,vnama,valamat,vtelepon,vpic)
		{
			
			document.getElementById('m_kode_supplier').value = vkode ;
			document.getElementById('m_nama_supplier').value = vnama ;
			//alert();
			document.getElementById('m_alamat_supplier').value = valamat ;
			document.getElementById('m_telepon').value = vtelepon ;
			document.getElementById('m_picsupplier').value = vpic ;
			
			$( "#dialog-listsupp" ).dialog( "close" );
		}
		
		function listrequest() 
		{
			var data = {
				kode_project: $('#m_kode_project').val()
			};

			var fungsi = function(respon) {
				$("#datarequest").html("<div class='result-wrapper'>" + respon + "</div>");
			};

			$.get('t_po-cekrequest.php?rnd=' + new Date().getTime(), data, fungsi);

			$("#dialog-listrequest").dialog("open");
		}
		
		function addSelectedRequests() 
		{
			var selected = $('#requestTable input[type="checkbox"]:checked');
			if (selected.length === 0) {
				alert("Silakan pilih minimal 1 request.");
				return;
			}

			selected.each(function() {
				var row = $(this).closest('tr');
				var nomorMR = row.find('td:eq(1)').text();
				var project = row.find('td:eq(2)').text();
				var item = row.find('td:eq(5)').text();
				var qtyReq = row.find('td:eq(6)').text();
				var qtyPo = row.find('td:eq(7)').text();
				var kodeItem = row.find('td:eq(9) .kode_item').val(); // ambil dari hidden input

				// Tambahkan row baru dengan data request
				addRowFromRequest(nomorMR, project, item, qtyReq-qtyPo, kodeItem);
			});
			
			$("#dialog-listrequest").dialog("close");
			recalc(); // update total otomatis
		}
		
		function listitem(rowke)
		{
			var tx = $('#m_nmitem'+rowke).val();  // simpan ke variabel tx

			
			var data={rk:rowke, tx:tx};
		
			var fungsi=function(respon){
					$("#dataitem").html(respon);
				};
			$.get('t_penawaran-cekitem.php',data,fungsi);
			
			$( "#dialog-listitem" ).dialog( "open" );
		}

		function selectitem(rowke,kodeitem,namaitem)
		{
			document.getElementById('m_item'+rowke).value = kodeitem ;
			document.getElementById('m_nmitem'+rowke).value = namaitem ;
			$( "#dialog-listitem" ).dialog( "close" );
		}
		
		function validasi()
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 1;
			
			var supervisor = document.getElementById('m_supervisor').value ;

			
			document.getElementById('jumrow').value = jumrow;
			
			if (supervisor == '') 
			{
				alert('Supervisor belum di isi !!!');
				return false ;
			}
			else
			{
				return true ;
			}
			
		}

		function recalc()
		{
			
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
		  	var jumrow = lastRow - 2;
			
			var tqty = 0 ;
			var tdiskonrp = 0 ;
			var takhir = 0 ;
			
			for(var i=1; i <= jumrow; i++) 
			{	
				var qty = Number(document.getElementById('m_qty' + i).value.replace(/,/g,""));
				var harga = Number(document.getElementById('m_harga' + i).value.replace(/,/g,""));
				var diskon_rp = Number(document.getElementById('m_diskon_rp' + i).value.replace(/,/g,""));
				
				var harga_akhir = harga - diskon_rp ;
				
				var total_akhir = qty * harga_akhir ;
				
				
				document.getElementById('m_qty' + i).value = formatangka(qty.toFixed(3).toString()) ;
				document.getElementById('m_harga' + i).value = formatangka(harga.toFixed(0).toString()) ;
				document.getElementById('m_total' + i).value = formatangka(harga_akhir.toFixed(0).toString()) ;
				document.getElementById('m_diskon_rp' + i).value = formatangka(diskon_rp.toFixed(0).toString()) ;
				document.getElementById('m_total_akhir' + i).value = formatangka(total_akhir.toFixed(0).toString()) ;
				
				tqty = tqty + qty;
				takhir = takhir + total_akhir;
				
				
			  $("#sp-totqty").html(formatangka((tqty).toFixed(3).toString()));
			  $("#sp-totakhir").html(formatangka((takhir).toFixed(0).toString()));
			  
			  // naik ke header
			$("#m_jumlah_rp").val(formatangka((takhir).toFixed(0).toString()));
			$("#m_total_rp").val(formatangka((takhir).toFixed(0).toString()));


			}
			
		}
		
		function recalc_header() {
			// Ambil subtotal
			var jumlah_awal = Number($("#m_jumlah_rp").val().replace(/,/g,"")) || 0;

			// Diskon
			var diskon_persen = Number($("#m_diskon_persen").val().replace(/,/g,"")) || 0;
			var diskon_jumlah = Math.round(jumlah_awal * diskon_persen / 100);
			var setelah_diskon = jumlah_awal - diskon_jumlah;
			if (setelah_diskon < 0) setelah_diskon = 0;

			$("#m_diskon_jumlah").val(formatangka(diskon_jumlah.toString()));


			// ============================
			// DISKON 2
			// ============================
			var diskon2_jumlah = Number($("#m_diskon2_jumlah").val().replace(/,/g,"")) || 0;

			
			var setelah_diskon2 = setelah_diskon - diskon2_jumlah;
			if (setelah_diskon2 < 0) setelah_diskon2 = 0;



			// tampilkan ke kolom diskon 2 jumlah
			$("#m_diskon2_jumlah").val(formatangka(diskon2_jumlah.toString()));

			// ============================
			// PPN & DPP dinamis (pakai metode 11/12)
			// ============================
			
			var ppn_persen = Number($("#m_ppn_persen").val().replace(/,/g,"")) || 0;
			
			if (ppn_persen == 12) 
			{

				var dpp = Math.round(setelah_diskon2 * 11 / 12);
				var ppn_jumlah = Math.round(dpp * ppn_persen / 100);
				
				var total_akhir = setelah_diskon2 + ppn_jumlah;
			}
			else if (ppn_persen == 11)
			{
				var dpp = setelah_diskon2;
				var ppn_jumlah = Math.round(setelah_diskon2 * ppn_persen/100);
				var total_akhir = dpp + ppn_jumlah;
				
			}
			else
			{
				var dpp = 0;
				var ppn_jumlah = 0;
				var total_akhir = setelah_diskon2;
			}
			
			$("#m_dpp_jumlah").val(formatangka(dpp.toString()));
			$("#m_ppn_jumlah").val(formatangka(ppn_jumlah.toString()));
			$("#m_total_rp").val(formatangka(total_akhir.toString()));
		}




		
		
		function addRowFromRequest(nomor_request, kode_project, itemName, qty,kodeItem) 
		{
			var tbl = document.getElementById('table_data');
			var lastRow = tbl.rows.length;
			var rowNum = lastRow - 2; // sebelum tfoot

			var row = tbl.insertRow(rowNum);

			// No urut
			var cell0 = row.insertCell(0);
			cell0.innerHTML = rowNum;

			// Material
			var cell1 = row.insertCell(1);
			cell1.innerHTML = 
				'<input class="input-xlarge" type="text" id="m_nmitem'+rowNum+'" name="m_nmitem'+rowNum+'" value="'+itemName+'" readonly />' +
				'<input type="hidden" id="m_item'+rowNum+'" name="m_item'+rowNum+'" value="'+kodeItem+'" />' +
				'<input type="hidden" id="m_nomor_request'+rowNum+'" name="m_nomor_request'+rowNum+'" value="'+nomor_request+'" />' +
				'<input type="hidden" id="m_no'+rowNum+'" name="m_no'+rowNum+'" value="'+rowNum+'" />';

			// Keterangan
			var cell2 = row.insertCell(2);
			cell2.innerHTML = '<div align="center"><textarea class="input-large"  id="m_keterangan'+rowNum+'" name="m_keterangan'+rowNum+'" value=""  style="width:300px; height:120px; resize:vertical;" /></textarea></div>';

			// Qty
			var cell3 = row.insertCell(3);
			cell3.innerHTML = '<div align="center"><input class="input-mini" type="text" id="m_qty'+rowNum+'" name="m_qty'+rowNum+'" value="'+qty+'" style="text-align:center" onchange="recalc()" /></div>';

			// Unit
			var cell4 = row.insertCell(4);
			cell4.innerHTML = '<div align="center"><input class="input-mini" type="text" id="m_unit'+rowNum+'" name="m_unit'+rowNum+'" value="" style="text-align:center" /></div>';

			// Unit Price
			var cell5 = row.insertCell(5);
			cell5.innerHTML = '<div align="center"><input class="input-large" type="text" id="m_harga'+rowNum+'" name="m_harga'+rowNum+'" value="" onchange="recalc()" style="text-align:center" /></div>';

			// Total Price
			var cell6 = row.insertCell(6);
			cell6.innerHTML = '<div align="center"><input class="input-medium" type="text" id="m_diskon_rp'+rowNum+'" name="m_diskon_rp'+rowNum+'" value="" onchange="recalc()" style="text-align:center" /></div>';

			// Total Price
			var cell6 = row.insertCell(7);
			cell6.innerHTML = '<div align="center"><input class="input-large" type="text" id="m_total'+rowNum+'" name="m_total'+rowNum+'" value=""  style="text-align:center"READONLY /></div>';

			// Total Price
			var cell6 = row.insertCell(8);
			cell6.innerHTML = '<div align="center"><input class="input-large" type="text" id="m_total_akhir'+rowNum+'" name="m_total_akhir'+rowNum+'" value=""  style="text-align:center"READONLY /></div>';

			// Delete
			var cell7 = row.insertCell(9);
			cell7.innerHTML = '<input type="hidden" id="m_new'+rowNum+'" name="m_new'+rowNum+'" value="Y" />' +
							  '<input type="checkbox" id="m_hapus'+rowNum+'" name="m_hapus'+rowNum+'" />';
							 
		}



		function add_data()
		{
		  var tbl = document.getElementById('table_data');
		  var lastRow = tbl.rows.length;
		  // if there's no header row in the table, then iteration = lastRow + 1
		  var iteration = lastRow - 2;
		  var row = tbl.insertRow(lastRow - 2);

		  var cellno = row.insertCell(0);
		  cellno.innerHTML='<td>'+iteration+'</td>';
		  
		  var cellno = row.insertCell(1);
		  cellno.innerHTML='<td><input class="input-xlarge" type="text" id="m_nmitem'+iteration+'" name="m_nmitem'+iteration+'" value=""  onchange="listitem('+iteration+')" /><input class="input-small" type="hidden" id="m_nomor_request'+iteration+'" name="m_nomor_request'+iteration+'" value=""/><input class="input-small" type="hidden" id="m_item'+iteration+'" name="m_item'+iteration+'" value=""/><input class="input-small" type="hidden" id="m_no'+iteration+'" name="m_no'+iteration+'" value="'+iteration+'"/></td>';
		  
		  var cellno = row.insertCell(2);
		  cellno.innerHTML='<td><div align="center"><textarea class="input-large" id="m_keterangan'+iteration+'" name="m_keterangan'+iteration+'" value="" /></textarea></div></td>';
		  
		  var cellno = row.insertCell(3);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_qty'+iteration+'" name="m_qty'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(4);
		  cellno.innerHTML='<td><div align="center"><input class="input-mini" type="text" id="m_unit'+iteration+'" name="m_unit'+iteration+'" value="" style="text-align:center" /></div></td>';
		  
		  var cellno = row.insertCell(5);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_harga'+iteration+'" name="m_harga'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(6);
		  cellno.innerHTML='<td><div align="center"><input class="input-medium" type="text" id="m_diskon_rp'+iteration+'" name="m_diskon_rp'+iteration+'" value="" style="text-align:center"  onchange= "recalc()"/></div></td>';
		  
		  var cellno = row.insertCell(7);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_total'+iteration+'" name="m_total'+iteration+'" value="" style="text-align:center"  onchange= "recalc()" READONLY/></div></td>';
		  
		  
		  var cellno = row.insertCell(8);
		  cellno.innerHTML='<td><div align="center"><input class="input-large" type="text" id="m_total_akhir'+iteration+'" name="m_total_akhir'+iteration+'" value="" style="text-align:center"  READONLY/></div></td>';
		  
		  var cellno = row.insertCell(9);
		  cellno.innerHTML='<td><input type="hidden" id="m_new'+iteration+'" name="m_new'+iteration+'" value="Y" /><div align="center"><input type="checkbox" id="m_hapus'+iteration+'" name="m_hapus'+iteration+'" /></div></td>';
		  
		  document.getElementById('m_nmitem'+iteration).focus();
		}
	</script>

    </body>
</html>