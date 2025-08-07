<?php
include 'header.php';

// Cek apakah parameter fakultas ada di URL
if (!isset($_GET['fakultas']) || empty($_GET['fakultas'])) {
    echo "<main class='page-container'><div class='container'><h1>Fakultas tidak ditemukan.</h1></div></main></body></html>";
    exit();
}

$nama_fakultas = urldecode($_GET['fakultas']);

// Koneksi ke Database
$host = "localhost"; $user = "root"; $pass = ""; $db = "oai";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// Ambil data jurnal dari database berdasarkan fakultas
$stmt = $conn->prepare("SELECT journal_title, publisher_name, issn, eissn, cover_url FROM jurnal_sumber WHERE fakultas = ?");
$stmt->bind_param("s", $nama_fakultas);
$stmt->execute();
$result = $stmt->get_result();

?>
<title>Jurnal Fakultas <?php echo htmlspecialchars($nama_fakultas); ?></title>

<main class="page-container">
    <div class="container">
        <div class="page-header publisher-header">
             <img src="path/to/logo-fakultas/<?php echo urlencode($nama_fakultas); ?>.png" alt="Logo Fakultas" class="publisher-logo">
            <div>
                <h2>Fakultas <?php echo htmlspecialchars($nama_fakultas); ?></h2>
                <p>Universitas Anda</p>
                <span><?php echo $result->num_rows; ?> Jurnal Ditemukan</span>
            </div>
        </div>

        <div class="journal-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="journal-item">';
                    // Tampilkan cover jika ada, jika tidak, tampilkan placeholder
                    $cover = !empty($row['cover_url']) ? $row['cover_url'] : 'https://via.placeholder.com/100x140.png?text=No+Cover';
                    echo '<img src="' . htmlspecialchars($cover) . '" alt="Cover Jurnal" class="journal-cover">';
                    echo '<div class="journal-info">';
                    echo '<h4><a href="#">' . htmlspecialchars($row['journal_title']) . '</a></h4>';
                    echo '<p class="journal-publisher">' . htmlspecialchars($row['publisher_name']) . '</p>';
                    echo '<span class="journal-issn">ISSN: ' . htmlspecialchars($row['issn']) . '</span>';
                    echo '<span class="journal-issn">EISSN: ' . htmlspecialchars($row['eissn']) . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Belum ada jurnal yang terdaftar untuk fakultas ini.</p>';
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</main>

</body>
</html>