<?php
// Mulai atau lanjutkan sesi
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran pengelola
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pengelola') {
    header("Location: login.php");
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

// Ambil data statistik untuk dashboard pengelola
$pengelolaId = $_SESSION['user_id'];

// Total submissions yang dibuat oleh pengelola
$stmt = $conn->prepare("SELECT COUNT(*) FROM jurnal_submissions WHERE submitted_by_nip = ?");
$stmt->bind_param("s", $pengelolaId);
$stmt->execute();
$stmt->bind_result($totalSubmissions);
$stmt->fetch();
$stmt->close();

// Total submissions yang masih pending
$stmt = $conn->prepare("SELECT COUNT(*) FROM jurnal_submissions WHERE submitted_by_nip = ? AND status = 'pending'");
$stmt->bind_param("s", $pengelolaId);
$stmt->execute();
$stmt->bind_result($pendingSubmissions);
$stmt->fetch();
$stmt->close();

// Total submissions yang sudah disetujui
$stmt = $conn->prepare("SELECT COUNT(*) FROM jurnal_submissions WHERE submitted_by_nip = ? AND status = 'approved'");
$stmt->bind_param("s", $pengelolaId);
$stmt->execute();
$stmt->bind_result($approvedSubmissions);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengelola</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
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
                <li><a href="dashboard_pengelola.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                <li><a href="register_journal.php"><i class="fas fa-plus-circle"></i> <span>Daftar Jurnal Baru</span></a></li>
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

            <div class="content-area">
                <div class="dashboard-stats">
                    <div class="card">
                        <h3>Total Submissions</h3>
                        <p><?php echo $totalSubmissions; ?></p>
                    </div>
                    <div class="card">
                        <h3>Menunggu Persetujuan</h3>
                        <p><?php echo $pendingSubmissions; ?></p>
                    </div>
                    <div class="card">
                        <h3>Jurnal Disetujui</h3>
                        <p><?php echo $approvedSubmissions; ?></p>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>