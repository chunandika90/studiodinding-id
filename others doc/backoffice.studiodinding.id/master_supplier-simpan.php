<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
    exit();
}

include "mssql-dbnew.php";

$tnew = $_POST['m_new'];   // 'Y' kalau baru, 'T' kalau edit
$prm  = $_POST['param'];

$kode            = $_POST['m_kode'];
$m_nama          = $_POST['m_nama'];
$contact_person  = $_POST['contact_person'];
$nomor_telepon   = $_POST['nomor_telepon'];
$bank_rekening   = $_POST['bank_rekening'];
$nomor_rekening  = $_POST['nomor_rekening'];
$nama_rekening   = $_POST['nama_rekening'];
$alamat          = $_POST['alamat'];

// jika data baru → insert
if ($tnew == 'Y') {
    $tsql = "
        INSERT INTO master_supplier
        (m_kode, m_nama, contact_person, nomor_telepon, bank_rekening, nomor_rekening, nama_rekening, alamat)
        VALUES
        ('{$kode}', '{$m_nama}', '{$contact_person}', '{$nomor_telepon}', '{$bank_rekening}', '{$nomor_rekening}', '{$nama_rekening}', '{$alamat}')
    ";
} else {
    // jika edit → update
    $tsql = "
        UPDATE master_supplier
        SET 
            m_nama = '{$m_nama}',
            contact_person = '{$contact_person}',
            nomor_telepon = '{$nomor_telepon}',
            bank_rekening = '{$bank_rekening}',
            nomor_rekening = '{$nomor_rekening}',
            nama_rekening = '{$nama_rekening}',
            alamat = '{$alamat}'
        WHERE m_kode = '{$kode}'
    ";
}

$stmt = $con_dbnew->query($tsql);

if ($stmt === false) {
    echo "Error executing query: " . $con_dbnew->error;
    exit();
}

header("Location: master_supplier.php?prm=" . base64_encode($prm));
exit();
?>