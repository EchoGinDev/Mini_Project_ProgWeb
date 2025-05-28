<?php
include 'koneksi.php';
$nama_perusahaan = $_POST['nama_perusahaan'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$lokasi = $_POST['lokasi'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$gaji = $_POST['gaji'] ?? '';

// Siapkan query dasar
$query = "SELECT * FROM jobs WHERE 1=1";

// Tambahkan filter jika ada input
if ($nama_perusahaan !== '') {
    $query .= " AND nama_perusahaan LIKE '%" . mysqli_real_escape_string($conn, $nama_perusahaan) . "%'";
}
if ($kategori !== '') {
    $query .= " AND kategori LIKE '%" . mysqli_real_escape_string($conn, $kategori) . "%'";
}
if ($lokasi !== '') {
    $query .= " AND lokasi LIKE '%" . mysqli_real_escape_string($conn, $lokasi) . "%'";
}
if ($jenis !== '') {
    $query .= " AND jenis LIKE '%" . mysqli_real_escape_string($conn, $jenis) . "%'";
}
if ($gaji !== '') {
    $query .= " AND gaji LIKE '%" . mysqli_real_escape_string($conn, $gaji) . "%'";
}

$result = mysqli_query($conn, $query);
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
            <li><a href="login.php" class="contact-btn">Login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form method="post" action="index.php">
            <input type="text" name="nama_perusahaan" placeholder="Nama Perusahaan" value="<?= htmlspecialchars($nama_perusahaan) ?>">
            <input type="text" name="kategori" placeholder="Kategori Pekerjaan" value="<?= htmlspecialchars($kategori) ?>">
            <input type="text" name="lokasi" placeholder="Lokasi" value="<?= htmlspecialchars($lokasi) ?>">
            <input type="text" name="jenis" placeholder="Jenis Pekerjaan" value="<?= htmlspecialchars($jenis) ?>">
            <input type="text" name="gaji" placeholder="Rentang Gaji" value="<?= htmlspecialchars($gaji) ?>">
            <button type="submit">Cari</button>
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
            echo '<a class="detail-btn" href="detail.php?id=' . $row['id'] . '">Lihat Detail</a>';
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
<!-- tes -->