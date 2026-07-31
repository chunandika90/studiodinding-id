<?php
	session_start();
	if ((!isset($_SESSION['loginid'])) || ($_SESSION['loginid'] == ""))
	{
		header('Location: ./index.php');
	}
	include "mssql-dbnew.php";
	
	$kdprog = '01';
	$login = $_POST['m_login'];
	$jumrow = $_POST['jumrow'];
	$prm = $_POST['param'];

	for ($i = 1; $i <= $jumrow; $i++) 
{
    $tkode = $_POST['m_kode'.$i] ?? '';

    // Default T
    $vakses = 'T';
    $vadd   = 'T';
    $vedit  = 'T';
    $vdel   = 'T';
    $vprint = 'T';

    // Cek hanya kalau ada POST
    if (isset($_POST['m_akses'.$i])) { $vakses = 'Y'; }
    if (isset($_POST['m_add'.$i]))   { $vadd   = 'Y'; }
    if (isset($_POST['m_edit'.$i]))  { $vedit  = 'Y'; }
    if (isset($_POST['m_delete'.$i])){ $vdel   = 'Y'; }
    if (isset($_POST['m_print'.$i])) { $vprint = 'Y'; }

    // Jangan eksekusi kalau $tkode kosong (berarti row ini gak ada di form)
    if ($tkode != '') {
        $tsql = "UPDATE msakses 
                 SET m_akses = '".$vakses."', 
                     m_add = '".$vadd."', 
                     m_edit = '".$vedit."', 
                     m_delete = '".$vdel."', 
                     m_print = '".$vprint."' 
                 WHERE m_login = '".$login."' 
                   AND m_kode = '".$tkode."'" ;
		//echo $tsql ."<br>";
        $stmt = $con_dbnew->query($tsql);
    }
}

	$tmenu = '1F0000';

	header("Location: msuser-akses.php?lg=".base64_encode($login)."&prm=".base64_encode($prm));

?>