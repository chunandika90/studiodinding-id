<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";

$prm = $_POST['param'];
$m_no = $_POST['m_no'];
$m_nomor = $_POST['m_nomor'];
$m_tanggal = $_POST['m_tanggal'];
$m_keterangan = $_POST['m_keterangan'];
$m_tipe_temuan = $_POST['m_tipe_temuan'];
$m_status_temuan = $_POST['m_status_temuan'];
$m_prioritas = $_POST['m_prioritas'];
$m_lantai = $_POST['m_lantai'];
$m_ruangan = $_POST['m_ruangan'];

// ubah dd/mm/yyyy -> yyyy-mm-dd
if (!empty($m_tanggal)) {
    $tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
    $m_tanggal = $tgl->format('Y-m-d');
} else {
    $m_tanggal = null;
}

if ($m_no == '')
{
	// Hitung m_no otomatis
	$tsqlNo = "SELECT IFNULL(MAX(m_no),0) AS max_no FROM t_survey2 WHERE m_nomor='".$m_nomor."'";
	$stmtNo = $con_dbnew->query($tsqlNo);
	$rowNo = $stmtNo->fetch_assoc();
	$m_no = $rowNo['max_no'] + 1;
}

// Folder upload
$uploadDir = "fotobarang/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

// Fungsi upload file
function uploadFile($inputName, $uploadDir) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == 0) {
        $originalName = basename($_FILES[$inputName]['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename = $inputName . "_" . time() . "." . $ext;
        $targetPath = $uploadDir . $filename;

        // Upload sementara
        $tmpPath = $_FILES[$inputName]['tmp_name'];
        
        // Resize sebelum disimpan
        if (resizeImage($tmpPath, $targetPath, 1280, 90)) { // max width 1280px, quality 90%
            return $targetPath;
        } else {
            // Kalau resize gagal, tetap upload normal
            move_uploaded_file($tmpPath, $targetPath);
            return $targetPath;
        }
    }
    return null;
}

function resizeImage($sourcePath, $targetPath, $maxWidth, $quality = 85) {
    list($width, $height, $type) = getimagesize($sourcePath);

    if ($width <= $maxWidth) {
        // Gak perlu resize, langsung pindahkan
        return move_uploaded_file($sourcePath, $targetPath);
    }

    $ratio = $width / $height;
    $newWidth = $maxWidth;
    $newHeight = $maxWidth / $ratio;

    // Buat image baru
    $dst = imagecreatetruecolor($newWidth, $newHeight);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($sourcePath);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            break;
        case IMAGETYPE_WEBP:
            $src = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false; // Format gak didukung
    }

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst, $targetPath, $quality);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst, $targetPath);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($dst, $targetPath, $quality);
            break;
    }

    imagedestroy($src);
    imagedestroy($dst);

    return true;
}

// Upload 3 file attachment
$m_foto = uploadFile('m_foto', $uploadDir);
$m_foto2 = uploadFile('m_foto2', $uploadDir);
$m_foto3 = uploadFile('m_foto3', $uploadDir);

// Insert ke database
$tsql = "INSERT INTO t_survey2 
        (m_nomor, m_no, m_tanggal, m_lantai, m_ruangan, m_keterangan, m_status_temuan, m_prioritas, m_tipe_temuan, m_foto, m_foto2, m_foto3) 
        VALUES 
        ('".$m_nomor."', '".$m_no."', '".$m_tanggal."', '".$m_lantai."', '".$m_ruangan."', '".$m_keterangan."', '".$m_status_temuan."', '".$m_prioritas."', '".$m_tipe_temuan."',
        ".($m_foto ? "'$m_foto'" : "NULL").",
        ".($m_foto2 ? "'$m_foto2'" : "NULL").",
        ".($m_foto3 ? "'$m_foto3'" : "NULL").")";

$stmt = $con_dbnew->query($tsql);


$con_dbnew->close();
header("Location: t_survey.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));
?>
