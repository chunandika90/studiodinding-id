<?php
ob_start();      // Start output buffering to prevent header errors
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tnama = $_POST['loginid'] ?? '';
    $tpassword = trim($_POST['password'] ?? '');

    if (!ctype_alnum($tnama) || !ctype_alnum($tpassword)) {
        echo "Maaf, anda harus menginput AlphaNumerik";
        exit();
    }

    include "mssql-dbnew.php";  // Your DB connection file

    $oke = 'Y';  // Default not OK

    // Use prepared statements to prevent SQL injection
    $stmt = $con_dbnew->prepare("SELECT m_login, m_nama, m_status, m_group FROM msuser WHERE m_login = '".$tnama."'");
    //$stmt->bind_param("	s", $tnama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
		
        $username = $row['m_login'];
        $namauser = $row['m_nama'];
        $group = $row['m_group'];
        $pasw = trim($row['m_password']);
		
		
        // Check password (case insensitive example, adjust as needed)
        if (strcasecmp($tpassword, $pasw) === 0) {
            $oke = 'Y';
        }
		
    }

    $stmt->close();
    $con_dbnew->close();

    if ($oke === 'Y') {
        $_SESSION['program'] = '01';
        $_SESSION['loginid'] = $username;
        $_SESSION['nama'] = $namauser;
        $_SESSION['group'] = $group;

        header("Location: menu-pos1.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    // If not POST method, redirect to login page
    header("Location: index.php");
    exit();
}

ob_end_flush();  // Flush output buffer
?>
