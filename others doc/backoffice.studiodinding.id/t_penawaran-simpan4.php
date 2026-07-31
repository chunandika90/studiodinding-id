<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";

$prm = $_POST['param'];
$m_nomor = $_POST['m_nomor'];
$m_cancelnote = $_POST['m_cancelnote'];

// ubah dd/mm/yyyy -> yyyy-mm-dd
if (!empty($m_tanggal)) {
    $tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
    $m_tanggal = $tgl->format('Y-m-d');
} else {
    $m_tanggal = null;
}


// Insert ke database
$tsql = "
			update t_penawaran set m_tanggal_batal = CURRENT_TIMESTAMP() , m_cancelnote = '".$m_cancelnote."', m_status = 'B'
			where m_nomor = '".$m_nomor."'


";

$stmt = $con_dbnew->query($tsql);

$con_dbnew->close();
header("Location: t_penawaran.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));
?>
