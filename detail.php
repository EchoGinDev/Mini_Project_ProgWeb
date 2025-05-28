<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM jobs WHERE id=$id");

    if ($row = mysqli_fetch_assoc($result)) {
        // Data pekerjaan ditemukan, tampilkan di HTML nanti
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
            <li><a href="login.php" class="contact-btn">Login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="job-detail">
        <img src="<?php echo htmlspecialchars($row['logo']); ?>" alt="Logo Perusahaan" class="detail-logo">

        <h1><?php echo htmlspecialchars($row['posisi']); ?></h1>
        <h3>Perusahaan: <?php echo htmlspecialchars($row['nama_perusahaan']); ?></h3>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($row['kategori']); ?></p>
        <p><strong>Jenis Pekerjaan:</strong> <?php echo htmlspecialchars($row['jenis']); ?></p>
        <p><strong>Gaji:</strong> <?php echo htmlspecialchars($row['gaji']); ?></p>
        <p><strong>Deskripsi Pekerjaan:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

        <a href="lamaran.php" class="apply-btn">Lamar Pekerjaan Ini</a>
    </section>
</main>

<footer>
    <p>&copy; 2024 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
