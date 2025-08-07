<?php include 'header.php';?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Agregator Jurnal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Portal Agregator Jurnal Ilmiah</h1>
        <p>Temukan ribuan artikel dari berbagai jurnal di Indonesia.</p>
    </header>

    <main>
        <form id="form-pencarian">
            <input type="search" id="input-keyword" placeholder="Masukkan judul, penulis, atau kata kunci..." required>
            <button type="submit">Cari</button>
        </form>

        <div id="loading" style="display:none;">
            <p>Mencari...</p>
        </div>

        <section id="hasil-pencarian">
            </section>
        

    <section class="stats-section">
        <div class="container">
            <div class="stat-item">
                <h2>1,250</h2>
                <p>Jurnal Terindeks</p>
            </div>
            <div class="stat-item">
                <h2>35,800</h2>
                <p>Artikel Ditemukan</p>
            </div>
            <div class="stat-item">
                <h2>8</h2>
                <p>Fakultas Tergabung</p>
            </div>
        </div>
    </section>    
    </main>

    <script src="script.js"></script>
    <!-- <?php include 'footer.php'; // Opsional jika Anda punya footer ?> -->



