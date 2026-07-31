<?php
session_start();
if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == "")) {
    header('Location: ./index.php');
}
include "mssql-dbnew.php";

$prm = $_POST['param'];
$m_nomor = $_POST['m_nomor'];
$m_cancel_note = $_POST['m_cancel_note'];

// ubah dd/mm/yyyy -> yyyy-mm-dd
if (!empty($m_tanggal)) {
    $tgl = DateTime::createFromFormat('d/m/Y', $m_tanggal);
    $m_tanggal = $tgl->format('Y-m-d');
} else {
    $m_tanggal = null;
}


// Insert ke database
$tsql = "
			update t_po set m_approved_at = CURRENT_TIMESTAMP(), m_approved_by = '".$_SESSION['loginid']."' , m_approved_note = '".$m_cancel_note."'
			where m_nomor = '".$m_nomor."'


";

//echo $tsql;

$stmt = $con_dbnew->query($tsql);

$con_dbnew->close();
header("Location: t_po.php?nm=".base64_encode($m_nomor)."&prm=".base64_encode($prm));
?>
