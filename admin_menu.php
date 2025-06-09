<?php 
session_start();
include 'koneksi.php';

// Cek login dan role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil email admin dari session
$email_admin = $_SESSION['email'];

// Ambil username admin dari tabel users
$email_admin = mysqli_real_escape_string($conn, $email_admin);
$query_user = "SELECT username FROM users WHERE email = '$email_admin' LIMIT 1";
$result_user = mysqli_query($conn, $query_user);

if (!$result_user || mysqli_num_rows($result_user) == 0) {
    echo "Admin tidak ditemukan.";
    exit;
}

$user_data = mysqli_fetch_assoc($result_user);
$username_admin = $user_data['username'];

// Handle hapus lowongan
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
    header("Location: admin_menu.php");
    exit;
}

// Fitur search (tetap sama)
$username = $_POST['username'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$posisi = $_POST['posisi'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$gaji_target = $_POST['gaji_target'] ?? '';

$query = "SELECT * FROM jobs WHERE 1=1";
if ($username !== '') {
    $query .= " AND username LIKE '%" . mysqli_real_escape_string($conn, $username) . "%'";
}
if ($kategori !== '') {
    $query .= " AND kategori LIKE '%" . mysqli_real_escape_string($conn, $kategori) . "%'";
}
if ($posisi !== '') {
    $query .= " AND posisi LIKE '%" . mysqli_real_escape_string($conn, $posisi) . "%'";
}
if ($jenis !== '') {
    $query .= " AND jenis LIKE '%" . mysqli_real_escape_string($conn, $jenis) . "%'";
}
if ($gaji_target !== '' && is_numeric($gaji_target)) {
    $gaji_target = intval($gaji_target);
    $query .= " AND gaji_min <= $gaji_target AND gaji_max >= $gaji_target";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
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
            <?php if (isset($_SESSION['email'])): ?>
                <li><span> <?= htmlspecialchars($username_admin); ?></span></li>
                <li><a href="logout.php" class="contact-btn">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="contact-btn">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <section class="search-section">
        <h2>Kelola Lowongan</h2>
        <a href="tambah.php" class="add-btn">Tambah Lowongan Baru</a>
        <a href="registcompany.php" class="add-btn">Buat Akun Company</a>
    </section>

    <hr style="border: 1px solid rgb(39, 39, 39);">

    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form method="POST" action="admin_menu.php">
            <input type="text" name="username" placeholder="Nama Perusahaan" value="<?= htmlspecialchars($username) ?>">
            <input type="text" name="kategori" placeholder="Kategori" value="<?= htmlspecialchars($kategori) ?>">
            <input type="text" name="posisi" placeholder="Posisi" value="<?= htmlspecialchars($posisi) ?>">
            <input type="text" name="jenis" placeholder="Jenis" value="<?= htmlspecialchars($jenis) ?>">
            <input type="number" name="gaji_target" placeholder="Gaji yang Diinginkan" value="<?= htmlspecialchars($gaji_target) ?>">
            <button type="submit">Cari</button>
        </form>
    </section>

    <h2>Daftar Lowongan</h2>
    <section class="job-listings">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="job-item">';
                echo '<img src="' . htmlspecialchars($row['logo']) . '" alt="Logo Perusahaan">';
                echo '<h3>Nama Perusahaan: ' . htmlspecialchars($row['username']) . '</h3>';
                echo '<p>Kategori: ' . htmlspecialchars($row['kategori']) . '</p>';
                echo '<p>Posisi: ' . htmlspecialchars($row['posisi']) . '</p>';
                echo '<p>Jenis: ' . htmlspecialchars($row['jenis']) . '</p>';
                echo '<p>Gaji: Rp ' . number_format($row['gaji_min'], 0, ',', '.') . ' - Rp ' . number_format($row['gaji_max'], 0, ',', '.') . '</p>';
                echo '<a class="detail-btn" href="edit.php?id=' . $row['id'] . '">Edit</a> ';
                echo '<a class="delete-btn" href="admin_menu.php?hapus=' . $row['id'] . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus lowongan ini?\')">Hapus</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Tidak ada lowongan yang sesuai dengan pencarian Anda.</p>';
        }
        ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
