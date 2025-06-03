<?php
session_start();
include 'koneksi.php';

// Cek login admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle tambah lowongan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = intval($_POST['gaji_min']);
    $gaji_max = intval($_POST['gaji_max']);
    $logo = mysqli_real_escape_string($conn, $_POST['logo']);

    $query = "INSERT INTO jobs (nama_perusahaan, kategori, posisi, jenis, gaji_min, gaji_max, logo)
              VALUES ('$nama_perusahaan', '$kategori', '$posisi', '$jenis', $gaji_min, $gaji_max, '$logo')";
    mysqli_query($conn, $query);
    header("Location: admin_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Lowongan</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="admin_menu.php">
            <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
        </a>
        <ul class="nav-links">
            <li><a href="#">About</a></li>
            <li><a href="admin_menu.php">Home</a></li>
            <li><a href="login.php" class="contact-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="search-section">
        <h2>Tambah Lowongan Baru</h2>
        <form method="POST">
            <input type="text" name="nama_perusahaan" placeholder="Nama Perusahaan" required>
            <input type="text" name="kategori" placeholder="Kategori" required>
            <input type="text" name="posisi" placeholder="Posisi" required>
            <input type="text" name="jenis" placeholder="Jenis" required>
            <input type="number" name="gaji_min" placeholder="Gaji Minimum" required>
            <input type="number" name="gaji_max" placeholder="Gaji Maksimum" required>
            <input type="text" name="logo" placeholder="URL Logo" required>
            <button type="submit">Simpan</button>
        </form>
        <a href="admin_menu.php">Kembali</a>
    </section>
</main>

<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
