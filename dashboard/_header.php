<?php
/**
 * File: _header.php
 * Author: Farel Richardo
 *
 * Tujuan: Header yang dapat digunakan kembali untuk semua halaman dashboard.
 * - Memulai session.
 * - Melakukan validasi keamanan untuk memastikan hanya peran (role) yang tepat yang dapat mengakses.
 * - Menampilkan bagian atas HTML dan navigasi utama.
 */

// Memulai atau melanjutkan session yang sudah ada.
session_start();

// Validasi Keamanan: Pastikan pengguna sudah login dan memiliki peran 'superadmin'.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    // Jika tidak, hancurkan session dan alihkan ke halaman login.
    session_destroy();
    header("Location: ../../login2.html?error=auth");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin - Portal Jurnal Unila</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="header-content">
            <h1>Portal Jurnal Unila - Superadmin</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage_admins.php">Kelola Admin</a></li>
                    <li><a href="manage_pengelola.php">Kelola Pengelola</a></li>
                    <li><a href="view_journals.php">Lihat Jurnal</a></li>
                    <li><a href="../../auth/logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="dashboard-main"></main>