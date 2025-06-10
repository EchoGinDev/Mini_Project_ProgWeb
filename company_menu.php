<?php
session_start();
include 'koneksi.php';

// Cek login dan role company
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit;
}

$email_company = $_SESSION['email'];

// Ambil username dari tabel users
$email_company = mysqli_real_escape_string($conn, $email_company);
$query_user = "SELECT username FROM users WHERE email = '$email_company' LIMIT 1";
$result_user = mysqli_query($conn, $query_user);

if (!$result_user || mysqli_num_rows($result_user) == 0) {
    echo "Perusahaan tidak ditemukan.";
    exit;
}

$user_data = mysqli_fetch_assoc($result_user);
$nama_perusahaan_company = $user_data['username'];

// Handle hapus lowongan
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Pastikan lowongan ini milik perusahaan ini (buat ngefilter)
    $check_query = "SELECT * FROM jobs WHERE id = $id AND username = '$nama_perusahaan_company'"; //patokan dri username
    $check_result = mysqli_query($conn, $check_query);
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
    }
    header("Location: company_menu.php");
    exit;
}

// Fitur search
$kategori = $_POST['kategori'] ?? '';
$posisi = $_POST['posisi'] ?? '';
$jenis = $_POST['jenis'] ?? '';
$gaji_target = $_POST['gaji_target'] ?? '';
$lokasi = $_POST['lokasi'] ?? '';

$query = "SELECT * FROM jobs WHERE username = '$nama_perusahaan_company'";
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
if ($lokasi !== '') {
    $query .= " AND lokasi LIKE '%" . mysqli_real_escape_string($conn, $lokasi) . "%'";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Company Dashboard</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="company_menu.php">
            <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
        </a>
        <ul class="nav-links">
            <li><a href="#">About</a></li>
            <li><a href="company_menu.php">Home</a></li>
            <?php if (isset($_SESSION['email'])): ?>
                <li><span><?= htmlspecialchars($nama_perusahaan_company); ?></span></li>
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
    </section>

    <hr style="border: 1px solid rgb(39, 39, 39);">

    <section class="search-section">
        <h2>Cari Lowongan</h2>
        <form method="POST" action="company_menu.php">
            <input type="text" name="kategori" placeholder="Kategori" value="<?= htmlspecialchars($kategori) ?>">
            <input type="text" name="posisi" placeholder="Posisi" value="<?= htmlspecialchars($posisi) ?>">
            <input type="text" name="jenis" placeholder="Jenis" value="<?= htmlspecialchars($jenis) ?>">
            <input type="text" name="lokasi" placeholder="Lokasi" value="<?= htmlspecialchars($lokasi) ?>">
            <input type="number" name="gaji_target" placeholder="Gaji yang Diinginkan" value="<?= htmlspecialchars($gaji_target) ?>">
            <button type="submit">Cari</button>
        </form>
    </section>

    <h2>Daftar Lowongan (<?= htmlspecialchars($nama_perusahaan_company) ?>)</h2>
    <section class="job-listings">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="job-item">';
                echo '<img src="' . htmlspecialchars($row['logo']) . '" alt="Logo Perusahaan">';
                echo '<h3>Nama Perusahaan: ' . htmlspecialchars($row['username']) . '</h3>';
                echo '<p>Lokasi: ' . htmlspecialchars($row['lokasi']) . '</p>';
                echo '<p>Kategori: ' . htmlspecialchars($row['kategori']) . '</p>';
                echo '<p>Posisi: ' . htmlspecialchars($row['posisi']) . '</p>';
                echo '<p>Jenis: ' . htmlspecialchars($row['jenis']) . '</p>';
                echo '<p>Gaji: Rp ' . number_format($row['gaji_min'], 0, ',', '.') . ' - Rp ' . number_format($row['gaji_max'], 0, ',', '.') . '</p>';

                echo '<a class="detail-btn" href="edit.php?id=' . $row['id'] . '">Edit</a> ';
                echo '<a class="delete-btn" href="company_menu.php?hapus=' . $row['id'] . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus lowongan ini?\')">Hapus</a>';
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
