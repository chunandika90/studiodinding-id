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
$kode_project = isset($_GET['kode_project']) ? $_GET['kode_project'] : '';
$stmt = null;

$tsql = "
select 	tp2.m_nomor , tp2.m_kode_project ,tp2.m_nama m_project, DATE(tp2.m_tanggal) AS co_tgl, DATE(tp2.m_tanggal_kirim) AS co_tgl_kirim, tp.m_item m_kode_item, mi.m_nama m_item , 
		tp.m_qty qty_request, ifnull(tp.m_po ,0) qty_po,
		CASE when tp2.m_partial <> 'OK' or tp2.m_partial is null then case WHEN tp2.m_jumlah = tp2.m_po THEN 'Complete' ELSE 'Not Complete' END else 'Complete Partial' end status_bayar,
		tp.m_keterangan, tp.m_unit
from 	t_penawaran tp2 , master_item mi , t_penawaran2 tp 
where tp2.m_nomor = tp.m_nomor	 and tp.m_item  = mi.m_kode and tp2.m_status = 'A' ";

if ($kode_project !== '') {
    $tsql .= " and tp2.m_kode_project LIKE '%" . $con_dbnew->real_escape_string($kode_project) . "%' order by tp2.m_nomor";
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
                  <th width="10%">Nomor MR</th>
                  <th width="10%">Project</th>
                  <th width="10%">Tanggal </br> Request</th>
                  <th width="10%">Tanggal Kirim</th>
                  <th width="20%">Item </br> keterangan </br> Satuan</th>
                  <th width="5%">Qty<br>Request</th>
                  <th width="5%">Qty<br>PO</th>
                  <th width="5%">Status<br>Request</th>
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
                          <td><?php echo $row['m_project']; ?></td>
                          <td><?php echo $row['co_tgl']; ?></td>
                          <td><?php echo $row['co_tgl_kirim']; ?></td>
                          <td><?php echo $row['m_item'] ."</br>". $row['m_keterangan'] ."</br> Satuan = ". $row['m_unit']  ; ?></td>
                          <td><?php echo number_format($row['qty_request'], 0, '.', ','); ?></td>
                          <td><?php echo number_format($row['qty_po'], 0, '.', ','); ?></td>
                          <td><?php echo $row['status_bayar']; ?></td>
                          <td style="text-align:center;">
                              <input type="checkbox" name="selected_rows[]" value='<?php echo json_encode($row); ?>'>
							  <input type="hidden" class="kode_item" value="<?php echo $row['m_kode_item']; ?>">
                          </td>
                      </tr>
              <?php 
                  endwhile; 
              else: ?>
                  <tr><td colspan="9">Data tidak ditemukan</td></tr>
              <?php endif; ?>
          </tbody>
      </table>
    </form>
  </div>
</div>