<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";
$nomor = isset($_GET['nm']) ? $_GET['nm'] : "";
$prm   = isset($_GET['prm']) ? $_GET['prm'] : "";
$xparam = explode('/', $prm);

// Ambil data MR
$tsql = "SELECT a.*, CONVERT(a.m_tanggal, DATE) co_tgl, convert(a.m_tanggal_batal, date) tanggal_batal,convert(a.m_tanggal_kirim, date) m_tanggal_kirim,
         b.m_alamat, b.m_lokasi, b.nama_client, b.supervisor_project m_nama_supervisor,
         CASE when a.m_partial <> 'OK' or a.m_partial is null then case WHEN a.m_jumlah = a.m_po THEN 'Complete' ELSE 'Not Complete' END else 'Complete Partial' end status_bayar,
         CASE WHEN a.m_jumlah = a.m_terima THEN 'Beres Terima' ELSE 'Belum Terima' END  status_terima
         FROM t_penawaran a
         JOIN master_project b ON a.m_kode_project = b.m_kode
         WHERE a.m_nomor = '".$nomor."'";

//echo $tsql ;
$stmt = $con_dbnew->query($tsql);
if ($stmt && $row = $stmt->fetch_assoc()) {
    // data aman
} else {
    echo "<div style='color:red'>Data tidak ditemukan untuk nomor: ".$nomor."</div>";
    $row = [
        'm_cabang' => '',
        'm_nomor' => '',
        'co_tgl' => '',
        'tanggal_batal' => '',
        'm_tanggal_kirim' => '',
        'm_kodecust' => '',
        'm_nama' => '',
        'm_alamat' => '',
        'm_lokasi' => '',
        'm_nama_client' => '',
        'm_nama_supervisor' => '',
        'm_keterangan' => '',
        'm_status' => ''
    ];
}

// Cek sisa belum diterima
$tsqlcek = "SELECT IFNULL(m_jumlah,0) cototal FROM t_penawaran WHERE m_nomor = '".$nomor."'";
$stmtcek = $con_dbnew->query($tsqlcek);
$rowcek = $stmtcek->fetch_assoc();

$tsqlcek2 = "SELECT SUM(IFNULL(m_qty,0)) as cobayar FROM t_penawaran_receive WHERE m_nomor = '".$nomor."'";
$stmtcek2 = $con_dbnew->query($tsqlcek2);
$rowcek2 = $stmtcek2->fetch_assoc();

$sisa = $rowcek['cototal'] - $rowcek2['cobayar'];
if ($sisa < 0){ $sisa = 0; }
?>

<!-- Info MR -->
<div class="table-responsive">
<table class="table table-bordered table-condensed">
    <tbody>
        <tr>
            <td width="15%">Nomor</td>
            <td width="40%"><b><?php echo $row['m_nomor']; ?></b></td>
            <td width="10%">Tanggal</td>
            <td width="35%"><?php echo $row['co_tgl']; ?></td>
        </tr>
        <tr>
            <td>Project</td>
            <td><?php echo '( '.$row['m_kode_project'].' ) '.$row['m_nama']; ?></td>
            <td width="10%">Tanggal Kirim</td>
            <td width="35%"><?php echo $row['m_tanggal_kirim']; ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3"><?php echo $row['m_alamat']; ?></td>
        </tr>
        <tr>
            <td>Lokasi</td>
            <td><?php echo $row['m_lokasi']; ?></td>
            <td>Client</td>
            <td><?php echo $row['nama_client']; ?></td>
        </tr>
        <tr>
            <td>Supervisor</td>
            <td colspan="3"><?php echo $row['m_nama_supervisor']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td colspan="3"><?php echo $row['m_keterangan']; ?></td>
        </tr>
        <tr>
            <td>Tanggal Partial</td>
            <td colspan="3"><?php echo $row['m_partial_tanggal']; ?></td>
        </tr>
        <tr>
            <td>Alasan Partial</td>
            <td colspan="3"><?php echo $row['m_partial_alasan']; ?></td>
        </tr>
        <tr>
            <td>Status Pemenuhan</td>
            <td colspan="3"><?php echo $row['status_bayar']; ?></td>
        </tr>
        <tr>
            <td>Status Penerimaan Barang</td>
            <td colspan="3"><?php echo $row['status_terima']; ?></td>
        </tr>
    </tbody>
</table>
</div>

<!-- Detail Material -->
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="3%">No</th>
            <th width="15%">Material</th>
            <th width="13%">Keterangan</th>
            <th width="5%">Unit</th>
            <th width="5%">Qty</th>
            <th width="5%">Qty PO</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        $tqty = 0;
        $tpo = 0;
        $tsql2 = "SELECT a.*, c.m_nama as co_namabarang
                  FROM t_penawaran2 a
                  JOIN master_item c ON a.m_item = c.m_kode
                  WHERE a.m_nomor = '".$nomor."'";
        $stmt2 = $con_dbnew->query($tsql2);
        while($row2 = $stmt2->fetch_assoc()) {
            $i++;
            $tqty += $row2['m_qty'];
            $tpo += $row2['m_po'];
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row2['co_namabarang']; ?></td>
            <td><?php echo $row2['m_keterangan']; ?></td>
            <td><?php echo $row2['m_unit']; ?></td>
            <td class="text-end"><?php echo number_format($row2['m_qty'],0,'.',','); ?></td>
            <td class="text-end"><?php echo number_format($row2['m_po'] ?? 0,0,'.',','); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4"></th>
            <th class="text-end"><?php echo number_format($tqty,0,'.',','); ?></th>
            <th class="text-end"><?php echo number_format($tpo,0,'.',','); ?></th>
        </tr>
        <tr>
            <th colspan="6">
                <div class="d-flex flex-wrap justify-content-between">
                    <?php if($row['m_status'] != 'B') 
					{ ?>
                        <div class="mb-1">
                            <?php if($_SESSION['group'] <> '03' && substr($xparam[3],1,1) == 'Y') { ?>
                                <button class="btn btn-primary" onclick="edit_data('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Edit</button>
                            <?php } ?>
						</div>
						<div class="mb-1">
                            <?php if(substr($xparam[3],3,1) == 'Y') { ?>
                                <button class="btn btn-warning" onclick="print_all('<?php echo $nomor; ?>')">Print MR</button>
                            <?php } ?>
                        </div>
						<div class="mb-1">
                            <?php if(substr($xparam[3],3,1) == 'Y') { ?>
                                <button class="btn btn-warning" onclick="partial_dialog('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Parsial Complete</button>
                            <?php } ?>
                        </div>
                        <?php if($row['status_bayar'] <> 'Complete' && substr($xparam[3],2,1) == 'Y') { ?>
                        <div class="mb-1">
                                <button class="btn btn-danger" onclick="batal_pos('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Batal Faktur</button>
                        </div>
                        <?php } ?>
                    <?php 
					} ?>
                </div>
            </th>
        </tr>
    </tfoot>
</table>
</div>

<!-- Informasi Penerimaan Barang -->
<h4 class="fw-bold mt-3">Informasi Penerimaan Barang</h4>
<div class="table-responsive">
<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="2%">No</th>
            <th width="8%">Tanggal</th>
            <th width="8%">Item / Material</th>
            <th width="8%">Penerima</th>
            <th width="6%">Keterangan</th>
            <th width="6%">View Foto</th>
            <th width="6%">View Foto 2</th>
            <th width="6%">View Foto 3</th>
            <th width="6%">Qty</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=0; $tjumlah=0;
        $tsql3 = "SELECT a.*, DATE_FORMAT(m_tanggal,'%Y-%m-%d') AS m_tanggal, b.m_nama nama_item
                  FROM t_penawaran_receive a, master_item b
                  WHERE a.m_nomor = '".$nomor."' and a.m_item = b.m_kode
				  
				  ";
        $stmt3 = $con_dbnew->query($tsql3);
        while($row3 = $stmt3->fetch_assoc()) {
            $i++;
            $tjumlah += $row3['m_qty'];
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row3['m_tanggal']; ?></td>
            <td><?php echo $row3['nama_item']; ?></td>
            <td><?php echo $row3['m_penerima']; ?></td>
            <td><?php echo $row3['m_keterangan']; ?></td>
            <?php
            $fotoColumns = ['m_foto','m_foto2','m_foto3'];
            foreach($fotoColumns as $col) {
                $foto = $row3[$col];
                if(!empty($foto)){
                    echo '<td><img src="'.$foto.'" class="img-fluid" style="max-width:40px; cursor:pointer;" onclick="showImageModal(\''.$foto.'\')"></td>';
                } else {
                    echo '<td>-</td>';
                }
            }
            ?>
            <td class="text-end"><?php echo number_format($row3['m_qty']??0,0,'.',','); ?></td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8"></th>
            <th class="text-end"><?php echo number_format($tjumlah,0,'.',','); ?></th>
        </tr>
        <tr>
            <th colspan="9" class="text-danger">
                <div>
                    <?php
                    if(($row['m_status'] != 'B') && (substr($xparam[3],1,1) == 'Y')){
                         ?>
                            <button class="btn btn-primary" onclick="add_inv('<?php echo $prm; ?>','<?php echo $nomor; ?>')">Entry Penerimaan Material</button>
                        
						<?php 
                    } else {
                        echo "Cancel date : ".' ( '.$row['tanggal_batal'].' ), Note : '.$row['m_cancelnote'];
                    }
                    ?>
                </div>
            </th>
        </tr>
    </tfoot>
</table>
</div>

<!-- Modal untuk view image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="modalImage" src="" style="width:100%; max-width:500px;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// Fungsi untuk tampilkan image di modal
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    $('#imageModal').modal('show');
}
</script>
