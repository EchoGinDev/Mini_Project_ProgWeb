<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk ambil data job sesuai id
    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        // Data ditemukan, simpan di variabel $job
        $job = $row;
    } else {
        echo "Lowongan tidak ditemukan.";
        exit;
    }
} else {
    echo "ID pekerjaan tidak diberikan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Lowongan - <?= htmlspecialchars($job['posisi']) ?></title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
    <nav class="navbar">
        <a href="index.php">
            <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan" />
        </a>
        <ul class="nav-links">
            <li><a href="#">About</a></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php" class="contact-btn">login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="job-detail">
        <h1><?= htmlspecialchars($job['posisi']) ?></h1>
        <img src="<?= htmlspecialchars($job['logo']) ?>" alt="Logo <?= htmlspecialchars($job['nama_perusahaan']) ?>" />
        <h3>Perusahaan: <?= htmlspecialchars($job['nama_perusahaan']) ?></h3>
        <p><strong>Kategori:</strong> <?= htmlspecialchars($job['kategori']) ?></p>
        <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($job['jenis']) ?></p>
        <p><strong>Gaji:</strong> <?= htmlspecialchars($job['gaji']) ?></p>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($job['lokasi'] ?? '-') ?></p>

        <h4>Deskripsi Pekerjaan</h4>
        <p><?= nl2br(htmlspecialchars($job['deskripsi'])) ?></p>

        <h4>Persyaratan</h4>
        <p><?= nl2br(htmlspecialchars($job['syarat'])) ?></p>

        <a href="lamaran.php?id=<?= $job['id'] ?>" class="apply-btn">Lamar Pekerjaan Ini</a>
    </section>
</main>

<footer>
    <p>&copy; 2024 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
