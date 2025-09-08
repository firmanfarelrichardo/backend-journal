<?php
// Mulai atau lanjutkan sesi
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
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

// Ambil data statistik
$totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$totalAdmins = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetch_row()[0];
$totalPengelola = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'pengelola'")->fetch_row()[0];
$totalJurnals = $conn->query("SELECT COUNT(*) FROM jurnal_submissions")->fetch_row()[0];
$pendingJurnals = $conn->query("SELECT COUNT(*) FROM jurnal_submissions WHERE status = 'pending'")->fetch_row()[0];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Superadmin</title>
    <link rel="stylesheet" href="admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Superadmin</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_superadmin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
                <li><a href="manage_pengelola.php"><i class="fas fa-user-cog"></i> Kelola Pengelola</a></li>
                <li><a href="manage_journal.php"><i class="fas fa-book"></i> Kelola Jurnal</a></li>
                <li><a href="change_password.php"><i class="fas fa-key"></i> Ganti Password</a></li>
                <li><a href="../api/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <div class="user-profile">
                    <span>Role: Superadmin</span>
                    <a href="../api/logout.php">Logout</a>
                </div>
            </div>

            <div class="content-area">
                <div class="card">
                    <h3>Statistik Pengguna</h3>
                    <p>Total Pengguna: **<?php echo $totalUsers; ?>**</p>
                    <p>Total Admin: **<?php echo $totalAdmins; ?>**</p>
                    <p>Total Pengelola: **<?php echo $totalPengelola; ?>**</p>
                </div>
                <div class="card">
                    <h3>Statistik Jurnal</h3>
                    <p>Total Submissions: **<?php echo $totalJurnals; ?>**</p>
                    <p>Menunggu Persetujuan: **<?php echo $pendingJurnals; ?>**</p>
                </div>
            </div>
        </div>
        <!-- End Main Content -->
    </div>
</body>
</html>
<?php
$conn->close();
?>
