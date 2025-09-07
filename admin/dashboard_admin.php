<?php
// Mulai atau lanjutkan sesi
session_start();

// Cek apakah pengguna sudah login dan memiliki peran yang sesuai
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    // Jika tidak, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Pengaturan Database MySQL
$host = "localhost";
$user = "root";
$pass = "";
$db = "oai";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) { 
    die("Koneksi gagal: " . $conn->connect_error); 
}

// Ambil data statistik untuk dashboard
$totalUsers = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetch_row()[0];
$totalPendingJournals = $conn->query("SELECT COUNT(*) FROM journal_submissions WHERE status = 'pending'")->fetch_row()[0];
$totalApprovedJournals = $conn->query("SELECT COUNT(*) FROM journal_submissions WHERE status = 'approved'")->fetch_row()[0];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Admin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_journal.php"><i class="fas fa-tasks"></i> Kelola Status Jurnal</a></li>
                <li><a href="../api/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Selamat Datang, <?php echo $_SESSION['user_name']; ?></h1>
                <div class="user-profile">
                    <span>Role: Admin</span>
                    <a href="../api/logout.php">Logout</a>
                </div>
            </div>

            <div class="content-area">
                <div class="card">
                    <h3>Statistik Pengguna</h3>
                    <p>Total Pengguna Publik: **<?php echo $totalUsers; ?>**</p>
                </div>
                <div class="card">
                    <h3>Status Jurnal</h3>
                    <p>Jurnal Menunggu Persetujuan: **<?php echo $totalPendingJournals; ?>**</p>
                    <p>Jurnal Diterima: **<?php echo $totalApprovedJournals; ?>**</p>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </div>
</body>
</html>
