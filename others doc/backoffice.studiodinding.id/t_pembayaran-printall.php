<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
		header('Location: ./index.php');
	}
	include "phpfunction.php";
    include "mssql-dbnew.php" ;

	$nomor = base64_decode($_GET['nm']);

	
	$tsql = "select a.*, DATE_FORMAT(a.m_tanggal, '%d/%m/%Y') AS co_tgl, c.m_nama as nama_supplier, c.nama_rekening, c.nomor_rekening, c.bank_rekening,c.alamat, c.contact_person, c.nomor_telepon,
			 case when m_status = 'A' then 'Complete' else 'Batal' end m_status,
			 case when m_carabayar = '1' then 'CASH' when '2' then 'Transfer' when '3' then 'Giro' end m_carabayar
			 from t_pembayaran a, master_supplier c
			 where a.m_kode_supplier = c.m_kode and 	 
			 a.m_nomor = '".$nomor."' " ;

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
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Voucher</title>
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
          <td>Voucher Pembayaran</td>
        </tr>
      </table>

      <!-- INFO SECTION -->
      <div class="info-container">
        <!-- LEFT TABLE (Vendor) -->
        <table class="info-table">
          <tr><td class="label">Vendor Name</td><td><?php echo $row['nama_supplier']; ?></td></tr>
          <tr><td class="label">Address</td><td><?php echo $row['alamat']; ?></td></tr>
          <tr><td class="label">Telephone</td><td><?php echo $row['nomor_telepon']; ?></td></tr>
          <tr><td class="label">Contact Person</td><td><?php echo $row['contact_person']; ?></td></tr>
          <tr><td class="label">Alamat</td><td><?php echo $row['alamat']; ?></td></tr>
        </table>

        <!-- RIGHT TABLE (PO Info)-->
        <table class="info-table">
          <tr><td class="label">Type</td><td><?php echo $row['m_type']; ?></td></tr>
          <tr><td class="label">Cara Bayar</td><td><?php echo $row['m_carabayar']; ?></td></tr>
          <tr><td class="label">Keterangan</td><td><?php echo $row['m_keterangan']; ?></td></tr>
          <tr><td class="label">Bank</td><td><?php echo $row['bank_rekening']; ?></td></tr>
          <tr><td class="label">Rekening</td><td><?php echo $row['nama_rekening'] ." ( ". $row['nomor_rekening'] ." )"; ?></td></tr>
          <tr><td class="label"></td><td><?php echo ''; ?></td></tr>
        </table>
      </div>
      <br/>

      <!-- DETAIL -->
      <table class="detail-table">
        <thead>
        <tr>
          <th width="5%">No</th>
          <th width="10%">Nomor Po</th>
          <th width="10%">Project</th>
          <th width="10%">Tanggal Po</th>
          <th width="10%">Keterangan</th>
          <th width="10%">Nilai PO</th>
          <th width="10%">Jumlah Bayar</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $tjumlah = 0;
        $tjumlahpo = 0;
        $tsql2 = "	select 	ifnull(b.m_nomor,a.m_nomor_po) m_nomor_po,
								ifnull(b.m_tanggal,a.m_tanggal_po) m_tanggal_po,
								ifnull(b.m_nama_project,a.m_project) m_project, a.m_jumlah_po, a.m_jumlah, a.m_keterangan
								
                        from 	t_pembayaran2 a
						left join t_po b on a.m_nomor_po = b.m_nomor
                        where 	a.m_nomor = '".$nomor."'   " ;
								
			//echo $tsql2."<br>";
        $stmt2 = $con_dbnew->query($tsql2);
        while($row2 = $stmt2->fetch_assoc())
		{

          $tjumlah += $row2['m_jumlah'];
          $tjumlahpo += $row2['m_jumlah_po'];
		?>
          <tr>
            <td align="center"><?php echo $no++; ?></td>
            <td align="center"><?php echo $row2['m_nomor_po']; ?></td>
            <td align="center"><?php echo $row2['m_project']; ?></td>
            <td align="center"><?php echo $row2['m_tanggal_po']; ?></td>
            <td><?php echo nl2br(htmlspecialchars($row2['m_keterangan']??'')) ; ?>  </td>
            <td>
              <div style="display:flex; justify-content:space-between; width:100%;">
                <span>Rp</span>
                <span><?php echo number_format($row2['m_jumlah_po'],0,',','.'); ?></span>
              </div>
            </td>
            <td>
              <div style="display:flex; justify-content:space-between; width:100%;">
                <span>Rp</span>
                <span><?php echo number_format(($row2['m_jumlah']),0,',','.'); ?></span>
              </div>
            </td>
          </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="6" align="right"><b>TOTAL</b></td>
          <td>
            <div style="display:flex; justify-content:space-between; width:100%;">
              <span>Rp</span>
              <span><?php echo number_format($tjumlah,0,',','.'); ?></span>
            </div>
          </td>
        </tr>
        </tfoot>
      </table>
		<!-- BOTTOM SECTION -->
		<table class="bottom-section">
		  <tr>
			<!-- LEFT SIDE 
			<td class="bottom-left" style="width:55%; vertical-align:top;">
			  <table class="note-table">
				<tr>
				  <td style="width:20%;">Terms Of Payment</td>
				  <td>7 - 30 days after invoice</td>
				</tr>
				<tr>
				  <td>Deliver To</td>
				  <td><?php echo $row['alamat']; ?></td>
				</tr>
				<tr>
				  <td>Contact Person</td>
				  <td><?php echo $row['contact_person']; ?></td>
				</tr>
			  </table>
			</td>
-->
			<!-- RIGHT SIDE -->
			<td class="bottom-right" style="width:35%; vertical-align:top;">
			  <table class="total-table">
				<tr class="highlight">
				  <td class="label">Total</td>
				  <td class="amount">
					<div style="display:flex; justify-content:space-between; width:100%;">
					  <span>Rp</span>
					  <span><?php echo number_format($tjumlah?? 0,0,',','.'); ?></span>
					</div>
				  </td>
				</tr>
				<tr>
				  <td colspan="2" class="amount-words">
					Amount of: <br/>
					<span><?php echo terbilang($tjumlah); ?></span>
				  </td>
				</tr>
			  </table>
			</td>
		  </tr>
		</table>
		<!-- SIGNATURE SECTION -->
		<table class="signature-section" style="width:100%; margin-top:20px; text-align:center;">
		  <tr>
			<!-- LEFT SIDE -->
			<td style="width:50%; vertical-align:top;">
			  <div>Prepared by</div>
			  <div style="height:80px;"></div> <!-- space tanda tangan -->
			  <div><strong>Syifa Puspita</strong></div>
			  <div>Procurement</div>
			</td>

			<!-- RIGHT SIDE -->
			<td style="width:50%; vertical-align:top;">
			  <div>Approved by</div>
			  <div style="height:80px;"></div> <!-- space tanda tangan -->
			  <div><strong>Henry Chandra</strong></div>
			  <div>Project Manager</div>
			</td>
		  </tr>
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
  height: 35mm;   /* ukuran fisik, bukan pixel — biar pas saat print */
  width: auto;    /* biar proporsional */
  max-width: 45mm; /* batas maksimal lebar */
  object-fit: contain; /* biar ga ketarik */
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