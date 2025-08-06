<?php
session_start();

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Jika tidak, tendang ke halaman login
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! (<a href="api/logout.php">Logout</a>)</p>
    </header>
    <main>
        <h2>Tambah Sumber Jurnal Baru</h2>
        <p>Gunakan form ini untuk mendaftarkan e-jurnal baru yang akan di-harvest oleh sistem.</p>
        
        <p><i>(Fitur form tambah jurnal akan ditambahkan di sini)</i></p>

        <hr>

        <h2>Jalankan Harvester Manual</h2>
        <p>Klik tombol di bawah untuk memulai proses harvesting dari semua jurnal terdaftar.</p>
        <button onclick="window.open('../oai_test.php', '_blank');">Mulai Proses Panen</button>
    </main>
</body>
</html>