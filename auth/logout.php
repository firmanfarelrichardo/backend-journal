<?php
/**
 * File: logout.php
 * Author: Farel Richardo
 *
 * Tujuan: Menghancurkan session pengguna saat ini (logout) dan
 * mengarahkannya kembali ke halaman login.
 */

// Selalu mulai session sebelum melakukan operasi terkait session
session_start();

// Hapus semua variabel session
$_SESSION = array();

// Hancurkan session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Arahkan kembali ke halaman login
header("Location: ../login.html");
exit();
?>