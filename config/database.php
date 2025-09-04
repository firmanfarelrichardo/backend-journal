<?php
/**
 * File: database.php
 * Author: Farel Richardo
 *
 * Tujuan: Mengatur dan membuat koneksi ke database MySQL menggunakan PDO.
 * File ini akan di-include oleh file lain yang memerlukan akses database.
 */

// Konfigurasi kredensial database
$host = 'localhost';      // Host database, biasanya 'localhost'
$dbname = 'oai'; // Ganti dengan nama database yang kamu buat
$username = 'root';       // Username database, ganti jika berbeda
$password = '';           // Password database, ganti jika ada

// Membuat Data Source Name (DSN) untuk koneksi PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Opsi untuk koneksi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menampilkan error sebagai exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mengambil data sebagai associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepared statements untuk keamanan
];

try {
    // Membuat instance PDO baru untuk koneksi
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Jika koneksi gagal, hentikan script dan tampilkan pesan error
    // Pada lingkungan produksi, sebaiknya log error ini daripada menampilkannya ke pengguna.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>