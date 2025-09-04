<?php
/**
 * File: login.php
 * Author: Farel Richardo
 *
 * Tujuan: Memproses data login yang dikirim dari form, memverifikasi kredensial pengguna,
 * dan mengarahkan mereka ke dashboard yang sesuai berdasarkan peran (role).
 */

// Memulai session untuk menyimpan data login
session_start();

// Jika pengguna sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/' . $_SESSION['role'] . '/index.php');
    exit();
}

// Memanggil file koneksi database
require_once '../config/database.php';

// Cek apakah form sudah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input sederhana
    if (empty($username) || empty($password)) {
        // Jika input kosong, kembalikan ke halaman login dengan pesan error
        header('Location: ../login.html?error=emptyfields');
        exit();
    }

    try {
        // Menyiapkan query untuk mencari user berdasarkan username
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verifikasi user dan password
        if ($user && password_verify($password, $user['password'])) {
            // Jika berhasil, simpan data user ke dalam session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan ke dashboard yang sesuai dengan peran
            header('Location: ../dashboard/' . $user['role'] . '/index.php');
            exit();
        } else {
            // Jika kredensial salah
            header('Location: ../login.html?error=wrongcredentials');
            exit();
        }
    } catch (PDOException $e) {
        // Tangani error database
        // Sebaiknya di-log, bukan ditampilkan langsung
        die("Error: " . $e->getMessage());
    }
} else {
    // Jika halaman diakses langsung tanpa metode POST, arahkan ke login
    header('Location: ../login.html');
    exit();
}
?>