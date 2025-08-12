<?php
session_start();

// Keamanan: Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Jika tidak, tendang ke halaman login
    header("Location: login.html");
    exit();
}

// Sertakan header navigasi
include 'header.php';
?>
<title>Dashboard Admin - Portal Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>

        <div class="admin-content">
            <div class="admin-form-container">
                <h3>Tambah Sumber Jurnal Baru</h3>
                <p>Gunakan form ini untuk mendaftarkan e-jurnal baru yang akan di-harvest oleh sistem.</p>
                
                <form action="api/submit_journal.php" method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="journal_title">Nama Jurnal*</label>
                        <input type="text" id="journal_title" name="journal_title" required>
                    </div>
                    <div class="form-group">
                        <label for="oai_url">URL OAI-PMH*</label>
                        <input type="url" id="oai_url" name="oai_url" placeholder="https://.../index.php/jurnal/oai" required>
                    </div>
                    <div class="form-group">
                        <label for="fakultas">Fakultas*</label>
                        <select id="fakultas" name="fakultas" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <option value="Teknik">Teknik</option>
                            <option value="Pertanian">Pertanian</option>
                            <option value="Kedokteran">Kedokteran</option>
                            <option value="Hukum">Hukum</option>
                            <option value="Ilmu Sosial dan Politik">Ilmu Sosial dan Politik</option>
                            <option value="MIPA">MIPA</option>
                            <option value="Keguruan dan Ilmu Pendidikan">Keguruan dan Ilmu Pendidikan</option>
                            <option value="Ekonomi dan Bisnis">Ekonomi dan Bisnis</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="publisher_name">Nama Penerbit</label>
                        <input type="text" id="publisher_name" name="publisher_name">
                    </div>
                    <div class="form-group">
                        <label for="issn">ISSN (Cetak)</label>
                        <input type="text" id="issn" name="issn">
                    </div>
                    <div class="form-group">
                        <label for="eissn">EISSN (Online)</label>
                        <input type="text" id="eissn" name="eissn">
                    </div>
                     <div class="form-group">
                        <label for="cover_url">URL Cover Jurnal</label>
                        <input type="url" id="cover_url" name="cover_url" placeholder="https://.../cover.jpg">
                    </div>
                    
                    <button type="submit" class="submit-btn">Simpan Jurnal</button>
                </form>
            </div>

            <hr style="margin: 40px 0;">

            <div class="admin-action-container">
                <h3>Jalankan Proses Panen (Harvesting)</h3>
                <p>Klik tombol di bawah untuk memulai proses pengambilan metadata artikel dari semua jurnal yang terdaftar di database. Proses ini akan berjalan di tab baru dan mungkin memakan waktu beberapa saat.</p>
                <button onclick="window.open('harvester.php', '_blank');" class="submit-btn" style="background-image: none; ">
                    Mulai Proses Panen Semua Jurnal
                </button>
            </div>

        </div>
    </div>
</main>

</body>
</html>