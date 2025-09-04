<?php
/**
 * File: index.php
 * Author: Farel Richardo
 * Lokasi: /dashboard/superadmin/
 *
 * Tujuan: Halaman utama (landing page) untuk dashboard Superadmin.
 * Menampilkan ringkasan informasi dan navigasi ke fitur-fitur utama.
 */

// Memasukkan header. Ini akan menangani keamanan dan tampilan atas.
require_once '../_header.php';
require_once '../../config/database.php'; // Koneksi ke database

// Logika untuk mengambil data ringkasan (contoh)
try {
    // Menghitung jumlah admin
    $stmt_admins = $pdo->prepare("SELECT COUNT(id) as total FROM roleuser WHERE role = 'admin'");
    $stmt_admins->execute();
    $admin_count = $stmt_admins->fetch(PDO::FETCH_ASSOC)['total'];

    // Menghitung jumlah pengelola
    $stmt_pengelola = $pdo->prepare("SELECT COUNT(id) as total FROM roleuser WHERE role = 'pengelola'");
    $stmt_pengelola->execute();
    $pengelola_count = $stmt_pengelola->fetch(PDO::FETCH_ASSOC)['total'];

    // Menghitung jumlah jurnal berdasarkan status
    $stmt_journals = $pdo->prepare("SELECT status, COUNT(id) as total FROM journals GROUP BY status");
    $stmt_journals->execute();
    $journal_counts = $stmt_journals->fetchAll(PDO::FETCH_KEY_PAIR);

} catch (PDOException $e) {
    // Tangani error jika query gagal
    die("Error mengambil data ringkasan: " . $e->getMessage());
}

?>

<div class="container">
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Ini adalah pusat kendali utama. Dari sini, kamu dapat mengelola seluruh aspek sistem Portal Jurnal Unila.</p>

    <div class="summary-cards">
        <div class="card">
            <h3>Total Admin</h3>
            <p><?php echo $admin_count; ?></p>
        </div>
        <div class="card">
            <h3>Total Pengelola</h3>
            <p><?php echo $pengelola_count; ?></p>
        </div>
        <div class="card">
            <h3>Jurnal Pending</h3>
            <p><?php echo $journal_counts['pending'] ?? 0; ?></p>
        </div>
        <div class="card">
            <h3>Jurnal Selesai</h3>
            <p><?php echo $journal_counts['selesai'] ?? 0; ?></p>
        </div>
    </div>
</div>

<?php
// Memasukkan footer untuk menutup halaman
require_once '../_footer.php';
?>