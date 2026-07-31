<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";

$prm = $_POST['param'];
$m_nomor = $_POST['m_nomor'];
$m_partial_alasan = $_POST['m_partial_alasan'];
$m_tanggal = $_POST['m_tanggal'];

// ubah dd/mm/yyyy -> yyyy-mm-dd
if (!empty($m_tanggal)) {
    $tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
    $m_tanggal = $tgl->format('Y-m-d');
} else {
    $m_tanggal = null;
}


// Insert ke database
$tsql = "
			update t_penawaran set m_partial_tanggal = '".$m_tanggal."' , m_partial_alasan = '".$m_partial_alasan."', m_partial = 'OK'
			where m_nomor = '".$m_nomor."'


";

$stmt = $con_dbnew->query($tsql);

$con_dbnew->close();
header("Location: t_penawaran.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));
?>
