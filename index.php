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

    <div id="light-rays-background"></div>

<main>
    <section class="hero-banner">
        <div class="hero-content">
            <h1>Unila E-Journal System</h1>
            <p class="hero-subtitle">Temukan artikel dari berbagai Fakultas di Universitas Lampung.</p>
            
            <div class="hero-search-container">
                <form action="search.php" method="GET" class="hero-search-form">
                    <div class="search-input-wrapper">
                        <input type="search" name="keyword" placeholder="Cari artikel, judul, penulis..." required>
                    </div>
                    <button type="submit" aria-label="Cari">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="hero-actions">
                <a href="fakultas.php" class="action-button">
                    <i class="fas fa-book"></i> Telusuri berdasarkan subjek
                </a>
            </div>
        </div>
    </section>

    <section class="content-showcase">
        <div class="container">
            <div class="section-header">
                <h2>Jelajahi Dunia Akademik</h2>
                <p>Akses koleksi jurnal yang dikelompokkan berdasarkan bidang keilmuan di setiap fakultas.</p>
            </div>
    </section>
</main>

<?php include 'footer.php'; ?>
</body>
</html>