<?php
session_start();

// Keamanan: Cek apakah user sudah login dan role-nya adalah 'admin' atau 'superadmin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
    header("Location: login.html");
    exit();
}
include 'header.php';

// Koneksi ke Database
$host = "localhost";
$user = "root";
$pass = "";
$db = "oai";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

$journal_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($journal_id === 0) {
    die("<main class='page-container'><div class='container'><p>ID Jurnal tidak valid.</p></div></main>");
}

// Ambil data jurnal
$stmt = $conn->prepare("SELECT * FROM jurnal_sumber WHERE id = ?");
$stmt->bind_param("i", $journal_id);
$stmt->execute();
$result = $stmt->get_result();
$journal_data = $result->fetch_assoc();
$stmt->close();

if (!$journal_data) {
    die("<main class='page-container'><div class='container'><p>Jurnal tidak ditemukan.</p></div></main>");
}
?>
<title>Detail Jurnal - <?php echo htmlspecialchars($journal_data['journal_title']); ?></title>

<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Detail & Verifikasi Jurnal</h1>
            <p>Jurnal: **<?php echo htmlspecialchars($journal_data['journal_title']); ?>**</p>
        </div>

        <div class="admin-content">
            <div class="admin-form-container">
                <h3>Informasi Pendaftaran Jurnal</h3>
                <form action="manage_journal_status.php" method="POST" class="admin-form">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="journal_id" value="<?php echo $journal_id; ?>">

                    <fieldset>
                        <legend>Detail Jurnal</legend>
                        <div class="form-group">
                            <label>Nama Jurnal</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['journal_title']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Pengelola</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['submitted_by_nip']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="oai_url">URL OAI-PMH*</label>
                            <input type="url" id="oai_url" name="oai_link" placeholder="https://.../oai" value="<?php echo htmlspecialchars($journal_data['oai_url']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status_approval">Status Persetujuan</label>
                            <select id="status_approval" name="new_status" required>
                                <option value="pending" <?php if($journal_data['status_approval'] == 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="approved" <?php if($journal_data['status_approval'] == 'approved') echo 'selected'; ?>>Selesai</option>
                                <option value="rejected" <?php if($journal_data['status_approval'] == 'rejected') echo 'selected'; ?>>Ditolak</option>
                                <option value="needs_edit" <?php if($journal_data['status_approval'] == 'needs_edit') echo 'selected'; ?>>Butuh Edit</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Nama Kontak</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['contact_name'] ?? '-'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email Kontak</label>
                            <input type="email" value="<?php echo htmlspecialchars($journal_data['contact_email'] ?? '-'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Fakultas</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['fakultas'] ?? '-'); ?>" disabled>
                        </div>
                         <div class="form-group">
                            <label>URL Website Jurnal</label>
                            <input type="url" value="<?php echo htmlspecialchars($journal_data['journal_website_url'] ?? '-'); ?>" disabled>
                        </div>
                         <div class="form-group">
                            <label>ISSN</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['issn'] ?? '-'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>EISSN</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['eissn'] ?? '-'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Prefix DOI</label>
                            <input type="text" value="<?php echo htmlspecialchars($journal_data['doi_prefix'] ?? '-'); ?>" disabled>
                        </div>
                    </fieldset>

                    <button type="submit" class="submit-btn">Update Status & OAI Link</button>
                    <a href="manage_journal_status.php" class="action-btn-secondary" style="display:inline-block; margin-top:10px;">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</main>
<?php
$conn->close();
include 'footer.php';
?>