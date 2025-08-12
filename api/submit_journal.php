<?php
session_start();

// Keamanan: Pastikan hanya admin yang bisa mengakses skrip ini
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Akses ditolak. Anda harus login sebagai admin.");
}

// Validasi data yang wajib diisi
if (empty($_POST['journal_title']) || empty($_POST['oai_url']) || empty($_POST['fakultas'])) {
    die("Nama Jurnal, URL OAI, dan Fakultas wajib diisi. Silakan kembali dan lengkapi form.");
}

// Koneksi ke Database
$host = "localhost"; $user = "root"; $pass = ""; $db = "oai";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Siapkan variabel dari data POST
$journal_title = $_POST['journal_title'];
$oai_url = $_POST['oai_url'];
$fakultas = $_POST['fakultas'];
$publisher_name = $_POST['publisher_name'] ?? null; // Gunakan null jika tidak diisi
$issn = $_POST['issn'] ?? null;
$eissn = $_POST['eissn'] ?? null;
$cover_url = $_POST['cover_url'] ?? null;

// Gunakan Prepared Statement untuk memasukkan data dengan aman
$stmt = $conn->prepare(
    "INSERT INTO jurnal_sumber (journal_title, oai_url, fakultas, publisher_name, issn, eissn, cover_url) 
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

// Bind parameter ke statement
$stmt->bind_param("sssssss", 
    $journal_title, 
    $oai_url, 
    $fakultas, 
    $publisher_name, 
    $issn, 
    $eissn, 
    $cover_url
);

// Eksekusi query dan berikan feedback
if ($stmt->execute()) {
    echo "<h1>Sukses!</h1>";
    echo "<p>Jurnal '" . htmlspecialchars($journal_title) . "' telah berhasil ditambahkan ke database.</p>";
    echo "<a href='../dashboard_admin.php'>Kembali ke Dashboard</a>";
} else {
    echo "<h1>Error!</h1>";
    echo "<p>Terjadi kesalahan saat menyimpan data: " . $stmt->error . "</p>";
    echo "<a href='../dashboard_admin.php'>Kembali ke Dashboard</a>";
}

$stmt->close();
$conn->close();

?>