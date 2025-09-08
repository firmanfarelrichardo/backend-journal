<?php
session_start();
// FILE: register_journal.php
// Fungsi: Formulir pendaftaran jurnal lengkap untuk pengelola.
// Menggunakan skema database baru yang telah diperbarui.

// Keamanan: Cek apakah user sudah login dan role-nya adalah 'pengelola'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.html");
    exit();
}

// Konfigurasi database MySQL
$host = "localhost";
$user = "root";
$pass = "";
$db = "oai";
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulir Pendaftaran Jurnal</title>
<link rel="stylesheet" href="admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

<style>
/* ===== PAGE STYLE ===== */
body {
  background: #f4f7fa;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

.page-container {
  max-width: 900px;
  margin: auto;
  padding: 2rem;
}

.page-header h1 {
  margin-bottom: 0.5rem;
  color: #222;
}

.page-header p {
  color: #666;
  margin-bottom: 1.5rem;
}

/* ===== FORM CONTAINER ===== */
.admin-form-container {
  background: #fff;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
  margin-top: 1rem;
}

/* ===== FIELDSET ===== */
.admin-form fieldset {
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  background: #fafafa;
}

.admin-form legend {
  font-weight: 600;
  font-size: 1.1rem;
  color: #333;
  padding: 0 0.5rem;
}

/* ===== LABEL & INPUT ===== */
.admin-form .form-group {
  margin-bottom: 1rem;
}

.admin-form label {
  display: block;
  font-weight: 500;
  margin-bottom: 0.4rem;
  color: #444;
}

.admin-form input[type="text"],
.admin-form input[type="email"],
.admin-form input[type="url"],
.admin-form input[type="number"],
.admin-form select,
.admin-form textarea {
  width: 100%;
  padding: 0.6rem 0.8rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.admin-form input:focus,
.admin-form select:focus,
.admin-form textarea:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
  outline: none;
}

/* ===== CHECKBOX GROUP ===== */
.admin-form .row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
}

.admin-form .row div {
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

/* ===== BUTTON ===== */
.submit-btn {
  background: #007bff;
  color: white;
  font-weight: 600;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.3s;
}

.submit-btn:hover {
  background: #0056b3;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0, 91, 187, 0.3);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .admin-form-container {
    padding: 1rem;
  }
  .admin-form fieldset {
    padding: 1rem;
  }
}
</style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <h2>Pengelola</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_pengelola.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="register_journal.php" class="active"><i class="fas fa-plus-circle"></i> <span>Daftar Jurnal Baru</span></a></li>
                <li><a href="view_my_submissions.php"><i class="fas fa-list-alt"></i> <span>Daftar & Status Jurnal</span></a></li>
                <li><a href="../api/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <div class="user-profile">
                    <span>Role: Pengelola</span>
                    <a href="../api/logout.php">Logout</a>
                </div>
            </div>


<main class="page-container">
    <div class="container">
        <div class="page-header">
            <h1>Formulir Pendaftaran Jurnal</h1>
            <p>Isi formulir di bawah ini untuk mendaftarkan jurnal kamu. Semua kolom wajib diisi.</p>
        </div>
        
        <div class="admin-form-container">
            <form action="review_submission.php" method="POST" class="admin-form">
                <input type="hidden" name="submitted_by_nip" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">

                <!-- Fieldset: Informasi Kontak & Institusi -->
                <fieldset>
                    <legend>Informasi Kontak & Institusi</legend>
                    <div class="form-group">
                        <label for="nama_kontak">Nama Kontak PIC*</label>
                        <input type="text" id="nama_kontak" name="nama_kontak" required>
                    </div>
                    <div class="form-group">
                        <label for="email_kontak">Email Kontak PIC*</label>
                        <input type="email" id="email_kontak" name="email_kontak" required>
                    </div>
                    <div class="form-group">
                        <label for="journal_contact_phone">Nomor Telepon Kontak*</label>
                        <input type="text" id="journal_contact_phone" name="journal_contact_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="institusi">Institusi*</label>
                        <input type="text" id="institusi" name="institusi" required value="Universitas Lampung" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editorial_address">Alamat Editorial*</label>
                        <textarea id="editorial_address" name="editorial_address" rows="3" required></textarea>
                    </div>
                </fieldset>

                <!-- Fieldset: Detail Jurnal & Publikasi -->
                <fieldset>
                    <legend>Detail Jurnal & Publikasi</legend>
                    <div class="form-group">
                        <label for="judul_jurnal_asli">Judul Jurnal*</label>
                        <input type="text" id="judul_jurnal_asli" name="judul_jurnal_asli" required>
                    </div>
                     <div class="form-group">
                        <label for="journal_type">Tipe Jurnal*</label>
                        <select id="journal_type" name="journal_type" required>
                            <option value="Journal">Journal</option>
                            <option value="Conference">Conference</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="p_issn">P-ISSN*</label>
                        <input type="text" id="p_issn" name="p_issn" required>
                    </div>
                    <div class="form-group">
                        <label for="e_issn">E-ISSN*</label>
                        <input type="text" id="e_issn" name="e_issn" required>
                    </div>
                    <div class="form-group">
                        <label for="penerbit">Penerbit*</label>
                        <input type="text" id="penerbit" name="penerbit" required>
                    </div>
                    <div class="form-group">
                        <label for="country_of_publisher">Negara Penerbit*</label>
                        <input type="text" id="country_of_publisher" name="country_of_publisher" required value="Indonesia (ID)">
                    </div>
                     <div class="form-group">
                        <label for="fakultas">Fakultas*</label>
                        <select id="fakultas" name="fakultas" required>
                            <option value="">-- Pilih Fakultas --</option>
                            <option value="Fakultas Ekonomi dan Bisnis">Fakultas Ekonomi dan Bisnis</option>
                            <option value="Fakultas Hukum">Fakultas Hukum</option>
                            <option value="Fakultas Ilmu Sosial dan Ilmu Politik">Fakultas Ilmu Sosial dan Ilmu Politik</option>
                            <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
                            <option value="Fakultas Keguruan dan Ilmu Pendidikan">Fakultas Keguruan dan Ilmu Pendidikan</option>
                            <option value="Fakultas Matematika dan Ilmu Pengetahuan Alam">Fakultas Matematika dan Ilmu Pengetahuan Alam</option>
                            <option value="Fakultas Pertanian">Fakultas Pertanian</option>
                            <option value="Fakultas Teknik">Fakultas Teknik</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_year">Tahun Mulai Terbit*</label>
                        <input type="number" id="start_year" name="start_year" min="1900" max="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="issue_period">Periode Terbit*</label>
                        <small class="form-text text-muted d-block mb-2">Pilih bulan-bulan terbit (contoh: Januari, Juni)</small>
                        <div class="row">
                            <?php
                            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            foreach ($months as $month) {
                                echo '<div class="col-md-3 col-6">';
                                echo '<input type="checkbox" id="month_' . strtolower($month) . '" name="issue_period[]" value="' . $month . '"> ';
                                echo '<label for="month_' . strtolower($month) . '">' . $month . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="aim_and_scope">Tujuan dan Ruang Lingkup (Aim & Scope)*</label>
                        <textarea id="aim_and_scope" name="aim_and_scope" rows="5" required></textarea>
                    </div>
                </fieldset>
                
                <!-- Fieldset: Tautan & Keterindeksan -->
                <fieldset>
                    <legend>Tautan & Keterindeksan</legend>
                    <div class="form-group">
                        <label for="website_url">URL Website Jurnal*</label>
                        <input type="url" id="website_url" name="website_url" placeholder="https://..." required>
                    </div>
                    <div class="form-group">
                        <label for="link_oai">Link OAI-PMH*</label>
                        <input type="url" id="link_oai" name="link_oai" placeholder="https://.../oai" required>
                    </div>
                    <div class="form-group">
                        <label for="url_cover">URL Cover Jurnal*</label>
                        <input type="url" id="url_cover" name="url_cover" placeholder="https://..." required>
                    </div>
                    <div class="form-group">
                        <label for="url_editorial_board">URL Dewan Editorial*</label>
                        <input type="url" id="url_editorial_board" name="url_editorial_board" required>
                    </div>
                    <div class="form-group">
                        <label for="url_google_scholar">URL Google Scholar</label>
                        <input type="url" id="url_google_scholar" name="url_google_scholar" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label for="link_sinta">Link Sinta</label>
                        <input type="url" id="link_sinta" name="link_sinta" placeholder="https://sinta.kemdikbud.go.id/...">
                    </div>
                    <div class="form-group">
                        <label for="link_garuda">Link Garuda</label>
                        <input type="url" id="link_garuda" name="link_garuda" placeholder="https://garuda.ristekbrin.go.id/...">
                    </div>
                </fieldset>

                <!-- Fieldset: Kategori dan Indeks -->
                <fieldset>
                    <legend>Kategori & Akreditasi</legend>
                    <div class="form-group">
                        <label for="akreditasi_sinta">Akreditasi SINTA*</label>
                        <select id="akreditasi_sinta" name="akreditasi_sinta" required>
                            <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                            <option value="Sinta 1">Sinta 1</option>
                            <option value="Sinta 2">Sinta 2</option>
                            <option value="Sinta 3">Sinta 3</option>
                            <option value="Sinta 4">Sinta 4</option>
                            <option value="Sinta 5">Sinta 5</option>
                            <option value="Sinta 6">Sinta 6</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="index_scopus">Indeks Scopus</label>
                        <select id="index_scopus" name="index_scopus">
                            <option value="Belum Terindeks">Belum Terindeks</option>
                            <option value="Q1">Q1</option>
                            <option value="Q2">Q2</option>
                            <option value="Q3">Q3</option>
                            <option value="Q4">Q4</option>
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="subject_garuda">Subjek Garuda (pisahkan dengan koma)*</label>
                        <textarea id="subject_garuda" name="subject_garuda" rows="3" required></textarea>
                    </div>
                </fieldset>

                <button type="submit" class="submit-btn">Review & Ajukan Jurnal</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>