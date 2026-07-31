<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
		header('Location: ./index.php');
	}
	include "phpfunction.php";
    include "mssql-dbnew.php" ;

	$nomor = base64_decode($_GET['nm']);

	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl , 
                    b.supervisor_project as m_nama_supervisor, b.nama_project as m_nama_project, 
                    b.m_lokasi, b.m_alamat m_alamat_project, b.nama_client, 
                    c.m_nama as nama_supplier, c.alamat m_alamat_supplier, c.nomor_telepon m_telepon_supplier, c.contact_person m_contact_supplier,
                    case when a.m_jumlah_qty = a.m_terima_qty then 'Complete' else 'Not Complete' end m_status
			 from t_survey a
             join master_project b on a.m_kode_project = b.m_kode
             join master_supplier c on a.m_kode_supplier = c.m_kode
			 where a.m_nomor = '".$nomor."' " ;

	$stmt = $con_dbnew->query($tsql);
	if ($stmt && $row = $stmt->fetch_assoc()) {
		// ada data, aman dipakai
	} else {
		echo "<div style='color:red'>Data tidak ditemukan untuk nomor: ".$nomor."</div>";
		$row = [
			'm_cabang' => '',
			'm_nomor' => '',
			'co_tgl' => '',
			'm_kodecust' => '',
			'm_nama_project' => '',
			'm_alamat_project' => '',
			'm_lokasi' => '',
			'm_nama_client' => '',
			'm_nama_supervisor' => '',
			'm_nama_supplier' => '',
			'm_alamat_supplier' => '',
			'm_telepon_supplier' => '',
			'm_contact_supplier' => '',
			'm_keterangan' => '',
			'm_status' => '',
			'm_diskon_persen' => 0,
			'm_diskon_jumlah' => 0,
			'm_ppn_persen' => 0,
			'm_ppn_jumlah' => 0,
			'm_total_rp' => 0
		];
	}
	
	
	function terbilang($x) {
    $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima",
                  "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($x < 12)
        return " " . $abil[$x];
    elseif ($x < 20)
        return terbilang($x - 10) . " Belas";
    elseif ($x < 100)
        return terbilang(intval($x / 10)) . " Puluh" . terbilang($x % 10);
    elseif ($x < 200)
        return " Seratus" . terbilang($x - 100);
    elseif ($x < 1000)
        return terbilang(intval($x / 100)) . " Ratus" . terbilang($x % 100);
    elseif ($x < 2000)
        return " Seribu" . terbilang($x - 1000);
    elseif ($x < 1000000)
        return terbilang(intval($x / 1000)) . " Ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000)
        return terbilang(intval($x / 1000000)) . " Juta" . terbilang($x % 1000000);
    elseif ($x < 1000000000000)
        return terbilang(intval($x / 1000000000)) . " Miliar" . terbilang($x % 1000000000);
    else
        return "Angka terlalu besar";
	
	
	$imgFile_henry = 'ttd_henry.png'; // nama file tanda tangan henry
	$imgFile_admin = 'ttd_syifa.png'; // nama file tanda tangan admin
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print PO</title>
<script type="text/javascript" src="js/myjs.js"></script>
<link rel="stylesheet" type="text/css" href="css/mycss1.css" />

</head>

<body>
<!-- WRAPPER BESAR -->
<table class="outer-wrapper">
  <tr>
    <td>

		  <!-- LOGO -->
		<div class="logo-row">
			<img src="logo.png" alt="Logo" class="logo-img">
		</div>

      <!-- Judul -->
      <table class="title-table">
        <tr>
          <td>PURCHASE ORDER</td>
        </tr>
      </table>

      <!-- INFO SECTION -->
      <div class="info-container">
        <!-- LEFT TABLE (Vendor) -->
        <table class="info-table">
          <tr><td class="label">Vendor Name</td><td><?php echo $row['m_nama_supplier']; ?></td></tr>
          <tr><td class="label">Address</td><td><?php echo $row['m_alamat_supplier']; ?></td></tr>
          <tr><td class="label">Telephone</td><td><?php echo $row['m_telepon_supplier']; ?></td></tr>
          <tr><td class="label">Faximile</td><td><?php echo ''; ?></td></tr>
          <tr><td class="label">Attention</td><td><?php echo $row['m_contact_supplier']; ?></td></tr>
          <tr><td class="label">Ref Quot. No</td><td><?php echo ''; ?></td></tr>
        </table>

        <!-- RIGHT TABLE (PO Info) -->
        <table class="info-table">
          <tr><td class="label">PO Number</td><td><?php echo $row['m_nomor']; ?></td></tr>
          <tr><td class="label">Date</td><td><?php echo $row['co_tgl']; ?></td></tr>
          <tr><td class="label">Email</td><td><?php echo 'fin.studiodinding@gmail.com'; ?></td></tr>
          <tr><td class="label">Delivery Date</td><td><?php echo $row['co_tgl']; ?></td></tr>
          <tr><td class="label">Attention</td><td><?php echo 'Bpk Henry Chandra'; ?></td></tr>
          <tr><td class="label">Project</td><td><?php echo $row['m_nama_project']; ?></td></tr>
        </table>
      </div>

      <br/>

      <!-- DETAIL -->
      <table class="detail-table">
        <thead>
        <tr>
          <th width="5%">No</th>
          <th width="35%">Nama Barang</th>
          <th width="10%">Qty</th>
          <th width="10%">Unit</th>
          <th width="15%">Unit Price</th>
          <th width="15%">Total Price</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $tqty = 0;
        $ttotal = 0;
        $tdiskonrp = 0;
        $takhir = 0;
        $tsql2 = "SELECT a.*, c.m_nama AS co_namabarang 
                  FROM t_survey2 a 
                  JOIN master_item c ON a.m_item = c.m_kode 
                  WHERE a.m_nomor = '".$nomor."'";
        $stmt2 = $con_dbnew->query($tsql2);
        while($row2 = $stmt2->fetch_assoc()){

          $tqty += $row2['m_qty'];
          $ttotal += ($row2['m_qty']*$row2['m_harga']);
          $tdiskonrp += $row2['m_diskon_rp'];
          $takhir += $row2['m_qty']*($row2['m_harga']-$row2['m_diskon_rp']);
        ?>
          <tr>
            <td align="center"><?php echo $no++; ?></td>
            <td><?php echo $row2['co_namabarang'] ."</br>". nl2br(htmlspecialchars($row2['m_keterangan'])) ; ?>  </td>
            <td align="center"><?php echo number_format($row2['m_qty'],0,',','.'); ?></td>
            <td align="center"><?php echo $row2['m_unit']; ?></td>
            <td>
              <div style="display:flex; justify-content:space-between; width:100%;">
                <span>Rp</span>
                <span><?php echo number_format($row2['m_harga'],0,',','.'); ?></span>
              </div>
            </td>
            <td>
              <div style="display:flex; justify-content:space-between; width:100%;">
                <span>Rp</span>
                <span><?php echo number_format(($row2['m_qty']*($row2['m_harga'])-$row2['m_diskon_rp']),0,',','.'); ?></span>
              </div>
            </td>
          </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="5" align="right"><b>TOTAL</b></td>
          <td>
            <div style="display:flex; justify-content:space-between; width:100%;">
              <span>Rp</span>
              <span><?php echo number_format($takhir,0,',','.'); ?></span>
            </div>
          </td>
        </tr>
        </tfoot>
      </table>
		<!-- BOTTOM SECTION -->
		<table class="bottom-section">
		  <tr>
			<!-- LEFT SIDE -->
			<td class="bottom-left" style="width:65%; vertical-align:top;">
			  <table class="note-table">
				<tr>
				  <td style="width:30%;">Terms Of Payment</td>
				  <td>7 - 30 days after invoice</td>
				</tr>
				<tr>
				  <td>Deliver To</td>
				  <td><?php echo $row['m_alamat_project']; ?></td>
				</tr>
				<tr>
				  <td>Contact Person</td>
				  <td><?php echo $row['m_nama_supervisor']; ?></td>
				</tr>
			  </table>
			</td>

			<!-- RIGHT SIDE -->
			<td class="bottom-right" style="width:35%; vertical-align:top;">
			  <table class="total-table">
				<tr>
				  <td class="label">Subtotal</td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format($row['m_jumlah_rp'] ?? 0,0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr>
				  <td class="label">Diskon <?php echo number_format($row['m_diskon_persen'] ?? 0, 2, '.', ',').' %'; ?></td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format($row['m_diskon_jumlah'] ?? 0,0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr>
				  <td class="label">DPP</td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format(($row['m_jumlah_rp'] - $row['m_diskon_jumlah']),0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr>
				  <td class="label">PPN <?php echo number_format($row['m_ppn_persen'] ?? 0, 2, '.', ',').' %'; ?></td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format($row['m_ppn_jumlah'] ?? 0,0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr class="highlight">
				  <td class="label">Total</td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format($row['m_total_rp'] ?? 0,0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr>
				  <td colspan="2" class="amount-words">
					Amount of: <br/>
					<span><?php echo terbilang($row['m_total_rp']); ?></span>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		</table>
		
		<!-- NOTES SECTION -->
		<table class="notes-table" style="width:100%; margin-top:20px; border-collapse:collapse; border:1px solid #000;">
		  <thead>
			<tr>
			  <th style="border:1px solid #000; padding:6px; width:5%;">No</th>
			  <th style="border:1px solid #000; padding:6px; text-align:left;">Notes</th>
			</tr>
		  </thead>
		  <tbody>
			<?php for($i=1; $i<=3; $i++): ?>
			<tr>
			  <td style="border:1px solid #000; text-align:center; padding:6px;"><?php echo $i; ?></td>
			  <td style="border:1px solid #000; padding:6px;"></td>
			</tr>
			<?php endfor; ?>
		  </tbody>
		</table>

		<!-- SIGNATURE SECTION -->
		<table class="signature-section" style="width:100%; margin-top:20px; text-align:center;">
		  <tr>
			<!-- LEFT SIDE -->
			<td style="width:50%; vertical-align:top;">
			  <div>Prepared by</div>
			  <div style="height:80px;"> <img src="ttd_syifa.png"
					   alt="Tanda Tangan admin"
					   style="max-height:80px; display:block; margin:0 auto;" /> </div> <!-- space tanda tangan -->
			  <div><strong>Syifa Puspita</strong></div>
			  <div>Procurement</div>
			</td>

			<!-- RIGHT SIDE -->
			<td style="width:50%; vertical-align:top;">
			  <div>Approved by</div>
			  <div style="height:80px;">
				<?php if (($row['m_approved_by'] != '')): ?>
				  <img src="ttd_henry.png"
					   alt="Tanda Tangan Henry"
					   style="max-height:80px; display:block; margin:0 auto;" />
				<?php endif; ?>
			  </div>
			  
			  <div><strong>Henry Chandra</strong></div>
			  <div>Project Manager</div>
			</td>
		  </tr>
		</table>
		
		<!-- BILLING REQUIREMENTS SECTION -->
		<table class="billing-table" style="width:100%; margin-top:20px; border-collapse:collapse; border:1px solid #000;">
		  <thead>
			<tr>
			  <th style="border:1px solid #000; padding:6px; text-align:left;">
				BILLING REQUIREMENTS
			  </th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
			  <td style="border:1px solid #000; padding:8px; line-height:1.5;">
				● Untuk Material : Surat Jalan (dengan mencantumkan No. MR & PO) + Rekap Penerimaan Material + BA Penerimaan Material + BA Pembayaran<br/>
				● Untuk Sewa Alat : Time Sheet + Rekap Pemakaian Alat + BA Pemakaian Alat + BA Pembayaran<br/><br/>
				Pembayaran hanya akan dilakukan jika vendor melengkapi Invoice, Faktur Pajak dan Copy PO dengan dokumen berikut secara lengkap dan benar:
			  </td>
			</tr>
		  </tbody>
		</table>
		
    </td>
  </tr>
</table> 

</body>
</html>

<style>
@media print {
  @page {
    size: A4 portrait;
    margin: 15mm;
  }
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    color: #000;
  }
  table {
    width: 180mm;
    margin: 0 auto;
    border-collapse: collapse;
    font-size: 12px;
  }
  thead { display: table-header-group; }
  tfoot { display: table-row-group; }
  tr { page-break-inside: avoid; }
}

@media screen {
  body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
  }
  table {
    width: 180mm;
    margin: 20px auto;
    border-collapse: collapse;
    font-size: 12px;
  }
}

/* ========================= */
/*  FRAME LUAR               */
/* ========================= */
.outer-wrapper {
  width: 185mm;
  margin: 0 auto 10px auto;
  border: 3px solid #000; /* border luar tebal */
  border-collapse: collapse;
}
.outer-wrapper td {
  border: none;
  padding: 8px;
}

/* ========================= */
/*  LOGO                     */
/* ========================= */
/* LOGO TANPA BORDER */
.logo-row {
  text-align: left;
  margin-bottom: 8px;
}
.logo-img {
  height: 50px;
}

/* ========================= */
/*  JUDUL                    */
/* ========================= */
.title-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 8px;
}
.title-table td {
  border: 1px solid #888; /* tipis */
  text-align: center;
  font-size: 16px;
  font-weight: bold;
  padding: 6px;
}

/* ========================= */
/*  INFO SECTION (kiri-kanan)*/
/* ========================= */
.info-container {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  margin-top: 5px;
}
.info-table {
  width: 49%;
  border-collapse: collapse;
  font-size: 12px;
}
.info-table td {
  border: 1px solid #888; /* tipis */
  padding: 4px;
}
.info-table .label {
  font-weight: bold;
  width: 35%;
}

/* ========================= */
/*  DETAIL TABLE             */
/* ========================= */
.detail-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}
.detail-table th,
.detail-table td {
  border: 1px solid #888; /* tipis */
  padding: 4px;
}
.detail-table th {
  background: #f0f0f0 !important;
  font-weight: bold;
  text-align: center;
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
}
/* ========================= */
/*  BOTTOM SECTION           */
/* ========================= */
.bottom-section {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0; /* nempel ke detail-table */
}
.bottom-section td {
  border: none; /* jangan bikin border baru */
  padding: 4px;
  vertical-align: top;
}

/* NOTE TABLE */
.note-table {
  width: 100%;
  border-collapse: collapse;
}
.note-table td {
  border: 1px solid #888; /* tetap ada border dalam */
  padding: 4px;
}

/* TOTAL TABLE */
.total-table {
  width: 100%;
  border-collapse: collapse;
}
.total-table td {
  border: 1px solid #888; /* tetap ada border dalam */
  padding: 4px;
}
.total-table .label {
  font-weight: bold;
  width: 70%;
}
.total-table .amount {
  text-align: right;
}
.total-table .highlight td {
  font-weight: bold;
}
.total-table .amount-words {
  font-size: 11px;
  font-style: italic;
  border-top: 1px solid #888;
  padding-top: 6px;
}

</style>