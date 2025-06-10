<?php
session_start();
include 'koneksi.php'; // Koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

// Query untuk mengambil username
$query_user = "SELECT username FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email_pengguna) . "'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);

// Jika username kosong atau NULL, fallback ke email
$username_pengguna = $row_user && !empty($row_user['username']) ? $row_user['username'] : $email_pengguna;

// Cek role admin (jika butuh)
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Ambil input dari form pencarian
$username = $_POST['username'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$posisi = $_POST['posisi'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$gaji_target = $_POST['gaji_target'] ?? '';

// Mulai query dasar dan hanya tampilkan lowongan yang belum kadaluarsa
$query = "SELECT * FROM jobs WHERE batas_lamaran >= CURDATE()";

// Tambahkan filter berdasarkan input form
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

// Eksekusi query
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
            <li><span style="color: white; margin-right: 10px;"><?= htmlspecialchars($username_pengguna) ?></span></li>
            <li><a href="logout.php" class="contact-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form method="post" action="index.php">
            <input type="text" name="username" placeholder="Nama Perusahaan" value="<?= htmlspecialchars($username) ?>">
            <input type="text" name="kategori" placeholder="Kategori Pekerjaan" value="<?= htmlspecialchars($kategori) ?>">
            <input type="text" name="posisi" placeholder="Posisi" value="<?= htmlspecialchars($posisi) ?>">
            <input type="text" name="jenis" placeholder="Jenis Pekerjaan" value="<?= htmlspecialchars($jenis) ?>">
            <input type="number" name="gaji_target" placeholder="Gaji yang Diinginkan" value="<?= htmlspecialchars($gaji_target) ?>">
            <button type="submit">Cari</button>
        </form>
    </section>

    <h2>Temukan perusahaan Anda berikutnya</h2>
    <p>Jelajahi profil perusahaan untuk menemukan tempat kerja yang tepat bagi Anda.</p>
    <hr style="border: 1px solid rgb(39, 39, 39);">

    <section class="job-listings">
        <h1>Daftar Lowongan</h1>

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
                echo '<p>Batas Lamaran: ' . date("d M Y", strtotime($row['batas_lamaran'])) . '</p>';
                echo '<a class="detail-btn" href="detail.php?id=' . $row['id'] . '">Lihat Detail</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Tidak ada lowongan yang sesuai atau semua lowongan sudah kadaluarsa.</p>';
        }
        ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
