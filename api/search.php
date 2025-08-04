<?php
// Set header agar output berupa JSON
header('Content-Type: application/json');

// --- PENGATURAN DATABASE (GANTI DENGAN MILIK ANDA) ---
$host = "localhost";
$user = "root";
$pass = "";
$db = "oai";

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit();
}

// Array untuk menampung hasil
$result_array = [];

// Ambil keyword dari parameter GET
if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
    $keyword = trim($_GET['keyword']);
    $search_term = "%" . $keyword . "%";

    // Gunakan prepared statement untuk keamanan dari SQL Injection
    $stmt = $conn->prepare(
        "SELECT title, description, creator1, creator2, creator3, source1
         FROM artikel_oai
         WHERE title LIKE ? OR description LIKE ? OR creator1 LIKE ? OR subject1 LIKE ?"
    );
    
    // Bind parameter ke statement
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);

    // Eksekusi statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch hasil query ke dalam array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $result_array[] = $row;
        }
    }
    
    $stmt->close();
}

// Tutup koneksi
$conn->close();

// Kembalikan hasil dalam format JSON
echo json_encode($result_array);
?>
