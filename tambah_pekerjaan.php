<?php
session_start();
include 'koneksi.php';

// Hanya admin yang boleh mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $sql = "INSERT INTO lowongan (judul, deskripsi) VALUES ('$judul', '$deskripsi')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Lowongan berhasil ditambahkan.'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pekerjaan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.php">
                <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-container">
            <h2>Tambah Pekerjaan Baru</h2>
            <form method="POST" action="tambah_pekerjaan.php">
                <label for="judul">Judul Pekerjaan:</label>
                <input type="text" id="judul" name="judul" required>

                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>

                <button type="submit">Tambah Pekerjaan</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
