<?php
// Pengaturan Database
$host = "localhost"; $user = "root"; $pass = ""; $db = "oai";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$name = $_POST['nama'];
$nip = $_POST['nip'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validasi sederhana
if (empty($name) || empty($email) || empty($password) || empty($nip)) { // <-- Tambahkan validasi NIP
    die("Semua field harus diisi.");
}

// Hash password untuk keamanan
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Ubah query INSERT untuk menyertakan kolom dan nilai NIP
$stmt = $conn->prepare("INSERT INTO users (nama, email, password, nip, role) VALUES (?, ?, ?, ?, 'user')");
$stmt->bind_param("ssss", $name, $email, $hashed_password, $nip); // <-- Update bind_param (4 string -> "ssss")

if ($stmt->execute()) {
    echo "Registrasi berhasil! Silakan <a href='../login.html'>login</a>.";
} else {
    // Tampilkan error yang lebih spesifik
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>