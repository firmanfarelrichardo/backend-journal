<?php
session_start();
// FILE: review_form_demo.php
// Fungsi: Menampilkan pratinjau data formulir pendaftaran jurnal
// sebelum pengelola mengunggahnya secara permanen.

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.html");
    exit();
}
include 'header.php';

// Data yang dikirim dari form pendaftaran
$form_data = $_POST;

// Validasi sederhana di sisi server
if (empty($form_data['judul_jurnal_asli']) || empty($form_data['nama_kontak']) || empty($form_data['email_kontak']) || empty($form_data['fakultas']) || empty($form_data['website_url'])) {
    die("<main class='page-container'><div class='container'><p>Semua field wajib diisi. Silakan kembali dan lengkapi formulir.</p><a href='register_journal.php'>Kembali ke Formulir</a></div></main>");
}
?>
<title>Pratinjau Jurnal</title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Pratinjau Jurnal</h1>
            <p>Ini adalah tampilan demo bagaimana jurnalmu akan terlihat. Periksa dengan teliti sebelum mengirimkan.</p>
        </div>
        
        <div class="journal-preview-card">
            <div class="journal-cover-mockup">
                <img src="<?php echo htmlspecialchars($form_data['url_cover'] ?? 'https://via.placeholder.com/200x280.png?text=Cover'); ?>" alt="Cover Jurnal">
            </div>
            <div class="journal-info-preview">
                <h2><?php echo htmlspecialchars($form_data['judul_jurnal_asli']); ?></h2>
                <p><strong>Penerbit:</strong> <?php echo htmlspecialchars($form_data['penerbit']); ?></p>
                <p><strong>ISSN:</strong> <?php echo htmlspecialchars($form_data['p_issn']); ?></p>
                <p><strong>E-ISSN:</strong> <?php echo htmlspecialchars($form_data['e_issn']); ?></p>
                <p><strong>Fakultas:</strong> <?php echo htmlspecialchars($form_data['fakultas']); ?></p>
                <p><strong>URL Website:</strong> <a href="<?php echo htmlspecialchars($form_data['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($form_data['website_url']); ?></a></p>
                <p class="journal-description-preview">
                    <strong>Tujuan dan Ruang Lingkup:</strong><br>
                    <?php echo nl2br(htmlspecialchars($form_data['aim_and_scope'])); ?>
                </p>
            </div>
        </div>
        
        <hr style="margin: 40px 0;">
        
        <div class="text-center">
            <a href="register_journal.php" class="action-btn-secondary" style="margin-right: 15px;">Kembali ke Formulir</a>
            <form action="api/submit_submission.php" method="POST" style="display:inline;">
                <?php
                foreach ($form_data as $key => $value) {
                     if ($key === 'issue_period' && is_array($value)) {
                        $value = implode(',', $value);
                     }
                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                }
                ?>
                <button type="submit" class="submit-btn">Unggah Jurnal</button>
            </form>
        </div>
    </div>
</main>

<style>
    .journal-preview-card {
        display: flex;
        gap: 30px;
        background-color: var(--light-gray);
        border: 1px solid var(--border-color);
        padding: 30px;
        border-radius: 8px;
        align-items: flex-start;
    }
    .journal-cover-mockup img {
        width: 200px;
        height: auto;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .journal-info-preview h2 {
        font-size: 1.8rem;
        color: var(--heading-color);
    }
    .journal-info-preview p {
        font-size: 1rem;
        color: var(--text-color);
        margin-bottom: 5px;
    }
    .journal-description-preview {
        margin-top: 15px;
        font-style: italic;
        border-left: 3px solid var(--primary-color);
        padding-left: 15px;
    }
</style>

<?php include 'footer.php'; ?>