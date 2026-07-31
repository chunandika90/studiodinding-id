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
         FROM master_supplier a";

if ($sctx !== '') {
    $tsql .= " WHERE a.m_nama LIKE '%" . $con_dbnew->real_escape_string($sctx) . "%'";
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
                <th width="20%">Nama Supplier</th>
                <th width="20%">Alamat</th>
                <th width="20%">Telepon</th>
                <th width="30%">Contact Person</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($stmt && $stmt->num_rows > 0): ?>
                <?php while ($row = $stmt->fetch_assoc()): ?>
                    <tr>
                        <td onClick="selectsupp('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $row['alamat']; ?>','<?php echo $row['nomor_telepon']; ?>','<?php echo $row['contact_person']; ?>')" style="cursor:pointer">
                            <?php echo $row['m_nama']; ?>
                        </td>
                        <td onClick="selectsupp('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $row['alamat']; ?>','<?php echo $row['nomor_telepon']; ?>','<?php echo $row['contact_person']; ?>')" style="cursor:pointer">
                            <?php echo $row['alamat']; ?>
                        </td>
                        <td><?php echo $row['nomor_telepon']; ?></td>
                        <td><?php echo $row['contact_person']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">Data tidak ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
  </div>
</div>