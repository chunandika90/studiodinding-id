<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";

$prm = $_POST['param'];
$m_nomor = $_POST['m_nomor'];
$m_item = $_POST['m_item'];
$m_penerima = $_POST['m_penerima'];
$m_tanggal = $_POST['m_tanggal'];
$m_keterangan = $_POST['m_keterangan'];
$m_jumlah = str_replace(",", "", $_POST['m_jumlah']);

// ubah dd/mm/yyyy -> yyyy-mm-dd
if (!empty($m_tanggal)) {
    $tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
    $m_tanggal = $tgl->format('Y-m-d');
} else {
    $m_tanggal = null;
}

// Hitung m_no otomatis
$tsqlNo = "SELECT IFNULL(MAX(m_no),0) AS max_no FROM t_penawaran_receive WHERE m_nomor='".$m_nomor."'";
$stmtNo = $con_dbnew->query($tsqlNo);
$rowNo = $stmtNo->fetch_assoc();
$m_no = $rowNo['max_no'] + 1;

// Folder upload
$uploadDir = "fotobarang/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Fungsi upload file
function uploadFile($inputName, $uploadDir) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
        $originalName = basename($_FILES[$inputName]['name']);
        $filename = $inputName . "_" . time() . "_" . $originalName;
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
            return $targetPath;
        }
    }
    return null;
}

// Upload 3 file attachment
$m_foto = uploadFile('m_foto', $uploadDir);
$m_foto2 = uploadFile('m_foto2', $uploadDir);
$m_foto3 = uploadFile('m_foto3', $uploadDir);

// Insert ke database
$tsql = "INSERT INTO t_penawaran_receive 
        (m_nomor, m_no, m_tanggal, m_item, m_penerima, m_keterangan, m_qty, m_foto, m_foto2, m_foto3) 
        VALUES 
        ('".$m_nomor."', ".$m_no.", '".$m_tanggal."', '".$m_item."', '".$m_penerima."', '".$m_keterangan."', ".$m_jumlah.",
        ".($m_foto ? "'$m_foto'" : "NULL").",
        ".($m_foto2 ? "'$m_foto2'" : "NULL").",
        ".($m_foto3 ? "'$m_foto3'" : "NULL").")";

$stmt = $con_dbnew->query($tsql);

// Update total penerimaan di header
$tsqlcek = "
    UPDATE t_penawaran a
    JOIN (
        SELECT m_nomor AS m_nomor, SUM(m_qty) AS m_jumlah
        FROM t_penawaran_receive
        WHERE m_nomor = '".$m_nomor."'
        GROUP BY m_nomor
    ) b ON a.m_nomor = b.m_nomor
    SET a.m_terima = b.m_jumlah
";
$stmtcek = $con_dbnew->query($tsqlcek);

$con_dbnew->close();
header("Location: t_penawaran.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));
?>
