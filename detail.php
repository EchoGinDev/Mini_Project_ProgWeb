<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

// Cek apakah parameter ID diberikan
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        // Data pekerjaan ditemukan
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
    <title>Detail Lowongan - <?php echo htmlspecialchars($row['posisi']); ?></title>
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
            <li><span style="color: white; margin-right: 10px;"><?php echo htmlspecialchars($email_pengguna); ?></span></li>
            <li><a href="logout.php" class="contact-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="job-detail">
        <img src="<?php echo htmlspecialchars($row['logo']); ?>" alt="Logo Perusahaan" class="detail-logo">

        <h1><?php echo htmlspecialchars($row['posisi']); ?></h1>
        <h3>Perusahaan: <?php echo htmlspecialchars($row['username']); ?></h3>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($row['kategori']); ?></p>
        <p><strong>Jenis Pekerjaan:</strong> <?php echo htmlspecialchars($row['jenis']); ?></p>
        <p><strong>Gaji:</strong> Rp <?= number_format($row['gaji_min'], 0, ',', '.') ?> - Rp <?= number_format($row['gaji_max'], 0, ',', '.') ?></p>
        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($row['lokasi'] ?? 'Tidak disebutkan'); ?></p>
        <p><strong>Deskripsi Pekerjaan:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

        <a href="lamaran.php?id=<?php echo $row['id']; ?>" class="apply-btn">Lamar Pekerjaan Ini</a>
    </section>
</main>

<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
