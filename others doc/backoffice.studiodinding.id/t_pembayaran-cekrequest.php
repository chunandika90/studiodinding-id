<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
if (!isset($_SESSION['loginid']) || $_SESSION['loginid'] == "") {
    header('Location: ./index.php');
    exit;
}

include "mssql-dbnew.php";
$kode_supplier = isset($_GET['kode_supplier']) ? $_GET['kode_supplier'] : '';
$stmt = null;

$tsql = "
select 	tp2.m_nomor , tp2.m_kode_project ,tp2.m_nama_project m_project, DATE(tp2.m_tanggal) AS co_tgl, tp2.m_nama_supplier, ifnull(tp2.m_total_rp,0)m_total_rp, ifnull(tp2.m_bayar,0)m_bayar
from 	t_po tp2 
where ifnull(tp2.m_bayar,0) <> ifnull(m_total_rp,0) ";

if ($kode_supplier !== '') {
    $tsql .= " and tp2.m_kode_supplier LIKE '%" . $con_dbnew->real_escape_string($kode_supplier) . "%'";
}

//echo $tsql;
$stmt = $con_dbnew->query($tsql);

	
?>
<!-- ✅ Bungkus table dengan table-responsive -->
<div id="dialog-listrequest" title="Daftar Request">
  <div class="dialog-content-scroll">
    <form id="form-select-request">
      <table class="table table-bordered table-striped table-hover table-condensed" id="requestTable">
          <thead>
              <tr>
                  <th width="5%">No</th>
                  <th width="10%">Nomor PO</th>
                  <th width="10%">Tanggal </br> PO</th>
                  <th width="10%">Supplier</th>
                  <th width="10%">Project</th>
                  <th width="10%">Total PO</th>
                  <th width="20%">Total </br> Terbayar</th>
                  <th width="20%">Sisa</th>
                  <th width="5%">Checklist</th>
              </tr>
          </thead>
          <tbody>
              <?php 
              $i=1;
              if ($stmt && $stmt->num_rows > 0): 
                  while ($row = $stmt->fetch_assoc()): 
              ?>
                      <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $row['m_nomor']; ?></td>
                          <td><?php echo $row['co_tgl']; ?></td>
                          <td><?php echo $row['m_nama_supplier']; ?></td>
                          <td><?php echo $row['m_project']; ?></td>
                          <td><?php echo number_format($row['m_total_rp'], 0, '.', ','); ?></td>
                          <td><?php echo number_format($row['m_bayar'], 0, '.', ','); ?></td>
                          <td><?php echo number_format($row['m_total_rp'] - $row['m_bayar'], 0, '.', ','); ?></td>
                          <td style="text-align:center;">
                              <input type="checkbox" name="selected_rows[]" value='<?php echo json_encode($row); ?>'>
							  <input type="hidden" class="nomor_po" value="<?php echo $row['m_nomor']; ?>">
                          </td>
                      </tr>
              <?php 
                  endwhile; 
              else: ?>
                  <tr><td colspan="9">Data tidak ditemukan</td></tr>
              <?php endif; ?>
          </tbody>
      </table>

      <!-- ✅ Tombol Submit & Cancel -->
	  <div style="margin-top:10px; text-align:right;">
		  <button type="button" onclick="addSelectedRequests()">Submit</button>
		  <button type="button" onclick="$('#dialog-listrequest').dialog('close')">Cancel</button>
	  </div>
    </form>
  </div>
</div>