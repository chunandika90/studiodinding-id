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

$sctx = isset($_GET['tx']) ? trim($_GET['tx']) : "";
$stmt = null;

$tsql = "SELECT *
         FROM master_project a";

if ($sctx !== '') {
    $tsql .= " WHERE a.nama_project LIKE '%" . $con_dbnew->real_escape_string($sctx) . "%'";
}

//echo $tsql;
$stmt = $con_dbnew->query($tsql);

	
?>
<!-- ✅ Bungkus table dengan table-responsive -->
<div id="dialog-listcust" title="Daftar Project">
  <div class="dialog-content-scroll">
    <table class="table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th width="20%">Nama Project</th>
                <th width="20%">Client</th>
                <th width="20%">Lokasi</th>
                <th width="30%">Alamat</th>
                <th width="30%">Supervisor</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($stmt && $stmt->num_rows > 0): ?>
                <?php while ($row = $stmt->fetch_assoc()): ?>
                    <tr>
                        <td onClick="selectproject('<?php echo $row['m_kode']; ?>','<?php echo $row['nama_project']; ?>','<?php echo $row['m_alamat']; ?>','<?php echo $row['m_lokasi']; ?>','<?php echo $row['nama_client']; ?>','<?php echo $row['supervisor_project']; ?>')" style="cursor:pointer">
                            <?php echo $row['nama_project']; ?>
                        </td>
                        <td onClick="selectproject('<?php echo $row['m_kode']; ?>','<?php echo $row['nama_project']; ?>','<?php echo $row['m_alamat']; ?>','<?php echo $row['m_lokasi']; ?>','<?php echo $row['nama_client']; ?>','<?php echo $row['supervisor_project']; ?>')" style="cursor:pointer">
                            <?php echo $row['nama_client']; ?>
                        </td>
                        <td><?php echo $row['m_lokasi']; ?></td>
                        <td><?php echo $row['m_alamat']; ?></td>
                        <td><?php echo $row['supervisor_project']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Data tidak ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
  </div>
</div>