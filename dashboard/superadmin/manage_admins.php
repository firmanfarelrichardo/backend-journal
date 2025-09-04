<?php
/**
 * File: manage_admins.php
 * Author: Farel Richardo
 * Lokasi: /dashboard/superadmin/
 *
 * Tujuan: Halaman untuk mengelola (CRUD) akun dengan peran 'admin'.
 * Superadmin dapat menambah, melihat, mengedit, dan menghapus data admin.
 */

// Memasukkan header. Ini akan menangani keamanan dan tampilan atas.
require_once '../_header.php';
// Memasukkan file koneksi database.
require_once '../../config/database.php';

// Inisialisasi variabel untuk pesan feedback dan data edit
$feedback_message = '';
$admin_to_edit = null;
$edit_mode = false;

// --- LOGIKA PEMROSESAN FORM (CREATE, UPDATE, DELETE) ---

// Menangani permintaan DELETE via GET
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM roleuser WHERE id = ? AND role = 'admin'");
        $stmt->execute([$_GET['id']]);
        $_SESSION['feedback_message'] = "Akun admin berhasil dihapus.";
    } catch (PDOException $e) {
        $_SESSION['feedback_message'] = "Gagal menghapus akun admin: " . $e->getMessage();
    }
    // Redirect untuk membersihkan URL dari parameter GET
    header("Location: manage_admins.php");
    exit();
}

// Menangani permintaan POST untuk ADD dan EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $nip = trim($_POST['nip']);
    $password = $_POST['password'];

    try {
        if ($action === 'add') {
            // --- CREATE ---
            if (empty($username) || empty($email) || empty($password)) {
                throw new Exception("Username, Email, dan Password wajib diisi.");
            }
            // Enkripsi password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare(
                "INSERT INTO roleuser (username, email, nip, password, role) VALUES (?, ?, ?, ?, 'admin')"
            );
            $stmt->execute([$username, $email, $nip, $hashed_password]);
            $_SESSION['feedback_message'] = "Admin baru '{$username}' berhasil ditambahkan.";

        } elseif ($action === 'edit') {
            // --- UPDATE ---
            $admin_id = $_POST['admin_id'];
            if (empty($username) || empty($email)) {
                throw new Exception("Username dan Email wajib diisi.");
            }
            if (!empty($password)) {
                // Jika password diisi, update passwordnya
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare(
                    "UPDATE roleuser SET username = ?, email = ?, nip = ?, password = ? WHERE id = ? AND role = 'admin'"
                );
                $stmt->execute([$username, $email, $nip, $hashed_password, $admin_id]);
            } else {
                // Jika password kosong, jangan update passwordnya
                $stmt = $pdo->prepare(
                    "UPDATE roleuser SET username = ?, email = ?, nip = ? WHERE id = ? AND role = 'admin'"
                );
                $stmt->execute([$username, $email, $nip, $admin_id]);
            }
            $_SESSION['feedback_message'] = "Data admin '{$username}' berhasil diperbarui.";
        }
    } catch (Exception $e) {
        // Tangkap error duplikasi atau error lainnya
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $_SESSION['feedback_message'] = "Error: Username atau Email sudah terdaftar.";
        } else {
            $_SESSION['feedback_message'] = "Error: " . $e->getMessage();
        }
    }
    // Redirect untuk menghindari re-submit form
    header("Location: manage_admins.php");
    exit();
}

// Cek apakah ada pesan feedback dari session
if (isset($_SESSION['feedback_message'])) {
    $feedback_message = $_SESSION['feedback_message'];
    // Hapus pesan setelah ditampilkan
    unset($_SESSION['feedback_message']);
}


// Menangani permintaan untuk menampilkan form EDIT
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT id, username, email, nip FROM roleuser WHERE id = ? AND role = 'admin'");
    $stmt->execute([$_GET['id']]);
    $admin_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// --- LOGIKA READ ---
// Selalu ambil daftar admin untuk ditampilkan di tabel
$stmt = $pdo->query("SELECT id, username, email, nip, created_at FROM roleuser WHERE role = 'admin' ORDER BY created_at DESC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <div class="manage-header">
        <h2>Kelola Akun Admin</h2>
        <p>Tambah, edit, atau hapus akun untuk admin yang akan mengelola portal.</p>
    </div>

    <?php if ($feedback_message): ?>
        <div class="feedback-message">
            <?php echo htmlspecialchars($feedback_message); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h3><?php echo $edit_mode ? 'Edit Admin' : 'Tambah Admin Baru'; ?></h3>
        <form action="manage_admins.php" method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_to_edit['id']); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin_to_edit['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_to_edit['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="nip">NIP (Opsional)</label>
                <input type="text" id="nip" name="nip" value="<?php echo htmlspecialchars($admin_to_edit['nip'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="<?php echo $edit_mode ? 'Kosongkan jika tidak ingin diubah' : ''; ?>" <?php echo !$edit_mode ? 'required' : ''; ?>>
            </div>
            <div class="form-group">
                <button type="submit" class="button-primary"><?php echo $edit_mode ? 'Update Admin' : 'Tambah Admin'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="manage_admins.php" class="button-secondary">Batal Edit</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-container">
        <h3>Daftar Admin Terdaftar</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admins)): ?>
                    <tr>
                        <td colspan="6">Belum ada admin yang terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id']); ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                            <td><?php echo htmlspecialchars($admin['nip'] ?: '-'); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($admin['created_at'])); ?></td>
                            <td class="action-links">
                                <a href="manage_admins.php?action=edit&id=<?php echo $admin['id']; ?>">Edit</a>
                                <a href="manage_admins.php?action=delete&id=<?php echo $admin['id']; ?>" onclick="return confirm('Apakah kamu yakin ingin menghapus admin \'<?php echo htmlspecialchars($admin['username']); ?>\'?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Memasukkan footer untuk menutup halaman
require_once '../_footer.php';
?>