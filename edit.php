<?php
session_start();
include 'koneksi.php';

// Pastikan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data
$id = intval($_GET['id']);
$query = "SELECT * FROM jobs WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Update jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = intval($_POST['gaji_min']);
    $gaji_max = intval($_POST['gaji_max']);
    $logo = mysqli_real_escape_string($conn, $_POST['logo']);

    $updateQuery = "UPDATE jobs SET
        nama_perusahaan='$nama_perusahaan',
        kategori='$kategori',
        posisi='$posisi',
        jenis='$jenis',
        gaji_min=$gaji_min,
        gaji_max=$gaji_max,
        logo='$logo'
        WHERE id=$id";
    mysqli_query($conn, $updateQuery);

    header("Location: admin_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Lowongan</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h2>Edit Lowongan</h2>
<form method="POST">
    <input type="text" name="nama_perusahaan" value="<?= htmlspecialchars($row['nama_perusahaan']) ?>" required>
    <input type="text" name="kategori" value="<?= htmlspecialchars($row['kategori']) ?>" required>
    <input type="text" name="posisi" value="<?= htmlspecialchars($row['posisi']) ?>" required>
    <input type="text" name="jenis" value="<?= htmlspecialchars($row['jenis']) ?>" required>
    <input type="number" name="gaji_min" value="<?= htmlspecialchars($row['gaji_min']) ?>" required>
    <input type="number" name="gaji_max" value="<?= htmlspecialchars($row['gaji_max']) ?>" required>
    <input type="text" name="logo" value="<?= htmlspecialchars($row['logo']) ?>" required>
    <button type="submit">Simpan</button>
</form>
<a href="admin_menu.php">Kembali</a>
</body>
</html>
