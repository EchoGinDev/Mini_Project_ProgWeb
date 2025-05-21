<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Halaman Utama</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="index.php">
            <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
        </a>
        <ul class="nav-links">
            <li><a href="#">About</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php" class="contact-btn">login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form>
            <input type="text" placeholder="Nama Perusahaan">
            <input type="text" placeholder="Kategori Pekerjaan">
            <input type="text" placeholder="Lokasi">
            <input type="text" placeholder="Jenis Pekerjaan">
            <input type="text" placeholder="Rentang Gaji">
            <button type="submit" disabled>Cari (Fitur Belum Tersedia)</button>
        </form>
    </section>

    <h2>Temukan perusahaan Anda berikutnya</h2>
    <p>
        Jelajahi profil perusahaan untuk menemukan tempat kerja yang tepat bagi Anda. 
        Pelajari tentang pekerjaan, ulasan, budaya perusahaan, keuntungan, dan tunjangan.
    </p>

    <hr style="border: 1px solid rgb(39, 39, 39);">

    <section class="job-listings">
        <h1>Daftar Lowongan</h1>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM jobs");
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="job-item">';
            echo '<img src="' . htmlspecialchars($row['logo']) . '" alt="Logo Perusahaan">';
            echo '<h3>Nama Perusahaan: ' . htmlspecialchars($row['nama_perusahaan']) . '</h3>';
            echo '<p>Kategori: ' . htmlspecialchars($row['kategori']) . '</p>';
            echo '<p>Posisi: ' . htmlspecialchars($row['posisi']) . '</p>';
            echo '<p>Jenis: ' . htmlspecialchars($row['jenis']) . '</p>';
            echo '<p>Gaji: ' . htmlspecialchars($row['gaji']) . '</p>';
            echo '<a class="detail-btn" href="' . htmlspecialchars($row['detail_page']) . '">Lihat Detail</a>';
            echo '</div>';
        }
        ?>
    </section>
</main>

<footer>
    <p>&copy; 2024 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
