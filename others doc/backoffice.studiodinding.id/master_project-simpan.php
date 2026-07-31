<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
    exit();
}

include "mssql-dbnew.php";

$tnew = $_POST['m_new'];   // 'Y' kalau baru, 'T' kalau edit
$prm = $_POST['param'];

$kode = $_POST['m_kode']; // buat update masih kepake
$nama_project = $_POST['nama_project'];
$tanggal_mulai_project = $_POST['tanggal_mulai_project'];
$supervisor_project = $_POST['supervisor_project'];
$m_telepon_spv = $_POST['m_telepon_spv'];
$nama_client = $_POST['nama_client'];
$m_lokasi = $_POST['m_lokasi'];
$m_alamat = $_POST['m_alamat'];

// pastikan tanggal diformat ke d/m/Y (sesuai penyimpanan di DB kamu)
if (!empty($tanggal_mulai_project)) {
    $tanggal_mulai_project = DateTime::createFromFormat('Y-m-d', $tanggal_mulai_project)
        ->format('d/m/Y');
}

if ($tnew == 'Y') {
    // === AUTO GENERATE m_kode ===
    $sqlMax = "SELECT MAX(m_kode) as last_kode FROM master_project WHERE m_kode LIKE 'P%'";
    $resMax = $con_dbnew->query($sqlMax);
    $rowMax = $resMax->fetch_assoc();

    if ($rowMax && $rowMax['last_kode'] != null) {
        $lastKode = $rowMax['last_kode'];    // contoh: P0012
        $num = intval(substr($lastKode, 1)); // ambil angka → 12
        $newNum = $num + 1;                  // increment → 13
        $newKode = "P" . str_pad($newNum, 4, "0", STR_PAD_LEFT); // jadi P0013
    } else {
        $newKode = "P0001"; // default kalau belum ada data
    }

    $tsql = "
        INSERT INTO master_project
        (m_kode, nama_project, tanggal_mulai_project, supervisor_project, nama_client, m_lokasi, m_alamat, m_telepon_spv)
        VALUES
        ('{$newKode}', '{$nama_project}', '{$tanggal_mulai_project}', '{$supervisor_project}', '{$nama_client}', '{$m_lokasi}', '{$m_alamat}', '{$m_telepon_spv}')
    ";
} else {
    // === UPDATE ===
    $tsql = "
        UPDATE master_project
        SET 
            nama_project = '{$nama_project}',
            tanggal_mulai_project = '{$tanggal_mulai_project}',
            supervisor_project = '{$supervisor_project}',
            nama_client = '{$nama_client}',
            m_lokasi = '{$m_lokasi}',
            m_alamat = '{$m_alamat}',
            m_telepon_spv = '{$m_telepon_spv}'
        WHERE m_kode = '{$kode}'
    ";
}

$stmt = $con_dbnew->query($tsql);

if ($stmt === false) {
    echo "Error executing query: " . $con_dbnew->error;
    exit();
}

header("Location: master_project.php?prm=" . base64_encode($prm));
exit();
?>
