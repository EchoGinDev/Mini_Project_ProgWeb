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

// Ambil input dari form pencarian, gunakan nilai default jika kosong
$username = $_POST['username'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$posisi = $_POST['posisi'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$gaji_target = $_POST['gaji_target'] ?? '';

// Mulai query dasar
$query = "SELECT * FROM jobs WHERE 1=1";

// Tambahkan filter berdasarkan input form (jika tidak kosong)
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

// Filter berdasarkan rentang gaji yang diinput user (jika valid angka)
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
    <link rel="stylesheet" href="styles.css"> <!-- CSS eksternal -->
</head>
<body>

<!-- Bagian Header / Navbar -->
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
    <!-- Form Pencarian -->
    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form method="post" action="index.php">
            <!-- Input pencarian -->
            <input type="text" name="username" placeholder="Nama Perusahaan" value="<?= htmlspecialchars($username) ?>">
            <input type="text" name="kategori" placeholder="Kategori Pekerjaan" value="<?= htmlspecialchars($kategori) ?>">
            <input type="text" name="posisi" placeholder="Posisi" value="<?= htmlspecialchars($posisi) ?>">
            <input type="text" name="jenis" placeholder="Jenis Pekerjaan" value="<?= htmlspecialchars($jenis) ?>">
            <input type="number" name="gaji_target" placeholder="Gaji yang Diinginkan" value="<?= htmlspecialchars($gaji_target) ?>">
            <button type="submit">Cari</button>
        </form>
    </section>

    

    <!-- Deskripsi Halaman -->
    <h2>Temukan perusahaan Anda berikutnya</h2>
    <p>
        Jelajahi profil perusahaan untuk menemukan tempat kerja yang tepat bagi Anda. 
        Pelajari tentang pekerjaan, ulasan, budaya perusahaan, dan keuntungan.
    </p>

    <hr style="border: 1px solid rgb(39, 39, 39);">
    
    <!-- Daftar Lowongan Pekerjaan -->
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
                echo '<a class="detail-btn" href="detail.php?id=' . $row['id'] . '">Lihat Detail</a>';
                echo '</div>';
            }
        } else {
            echo '<p>Tidak ada lowongan yang sesuai dengan pencarian Anda.</p>';
        }
        ?>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Job Portal. All rights reserved.</p>
</footer>
</body>
</html>
