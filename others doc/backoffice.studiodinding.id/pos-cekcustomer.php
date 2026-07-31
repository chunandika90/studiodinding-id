<?php
session_start();
if (!isset($_SESSION['loginid']) || $_SESSION['loginid'] == "") {
    header('Location: ./index.php');
    exit;
}

include "mssql-dbnew.php";

$sctx = isset($_GET['tx']) ? trim($_GET['tx']) : "";
$stmt = null;

$tsql = "SELECT a.m_kode, a.m_nama, a.m_alamat, a.m_kota, a.m_telepon1, a.m_telepon2 
         FROM mscustomer a";

if ($sctx !== '') {
    $tsql .= " WHERE a.m_nama LIKE '%" . $con_dbnew->real_escape_string($sctx) . "%'";
}

//echo $tsql;
$stmt = $con_dbnew->query($tsql);

	
?>

<table class="table table-bordered table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th width="20%">Nama</th>
            <th width="30%">Alamat</th>
            <th width="20%">Telepon</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($stmt && $stmt->num_rows > 0): ?>
            <?php while ($row = $stmt->fetch_assoc()): ?>
                <tr>
                    <td onClick="selectcust('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $row['m_alamat']; ?>','<?php echo $row['m_kota']; ?>','<?php echo $row['m_telepon1']; ?>','<?php echo $row['m_telepon2']; ?>')" style="cursor:pointer">
                        <?php echo $row['m_kode']; ?>
                    </td>
                    <td onClick="selectcust('<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>','<?php echo $row['m_alamat']; ?>','<?php echo $row['m_kota']; ?>','<?php echo $row['m_telepon1']; ?>','<?php echo $row['m_telepon2']; ?>')" style="cursor:pointer">
                        <?php echo $row['m_nama']; ?>
                    </td>
                    <td><?php echo $row['m_alamat'].'<br/>'.$row['m_kota']; ?></td>
                    <td><?php echo $row['m_telepon1']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Data tidak ditemukan</td></tr>
        <?php endif; ?>
    </tbody>
</table>