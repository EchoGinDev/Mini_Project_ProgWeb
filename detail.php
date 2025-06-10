<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        // Ambil ID user dari email
        $query_user = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_pengguna'");
        $data_user = mysqli_fetch_assoc($query_user);
        $id_user = $data_user['id'] ?? 0;

        // Cek apakah sudah melamar
        $check_lamaran = mysqli_query($conn, "SELECT * FROM lamaran WHERE id_user = '$id_user' AND id_lowongan = '$id'");
        $sudah_melamar = mysqli_num_rows($check_lamaran) > 0;
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lowongan - <?= htmlspecialchars($row['posisi']); ?></title>
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
            <li><span style="color: white;"><?= htmlspecialchars($email_pengguna); ?></span></li>
            <li><a href="logout.php" class="contact-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="job-detail">
        <img src="<?= htmlspecialchars($row['logo']); ?>" alt="Logo Perusahaan" class="detail-logo">
        <h1><?= htmlspecialchars($row['posisi']); ?></h1>
        <h3>Perusahaan: <?= htmlspecialchars($row['username']); ?></h3>
        <p><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']); ?></p>
        <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($row['jenis']); ?></p>
        <p><strong>Gaji:</strong> Rp <?= number_format($row['gaji_min'], 0, ',', '.') ?> - Rp <?= number_format($row['gaji_max'], 0, ',', '.') ?></p>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi'] ?? 'Tidak disebutkan'); ?></p>
        <p><strong>Deskripsi Pekerjaan:</strong></p>
        <p><?= nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

        <?php if ($sudah_melamar): ?>
            <p style="color: red; font-weight: bold;">Anda sudah pernah melamar lowongan ini!</p>
        <?php else: ?>
            <a href="lamaran.php?id=<?= $row['id']; ?>" class="apply-btn">Lamar Pekerjaan Ini</a>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>