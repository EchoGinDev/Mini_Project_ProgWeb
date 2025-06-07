<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Pastikan parameter ID tersedia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_menu.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data pekerjaan yang akan diedit
$query = "SELECT * FROM jobs WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<p>Data lowongan tidak ditemukan.</p>";
    echo "<a href='admin_menu.php'>Kembali</a>";
    exit;
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = intval($_POST['gaji_min']);
    $gaji_max = intval($_POST['gaji_max']);

    // Default logo = logo lama
    $logo = $row['logo'];

    // Jika ada file baru diupload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['logo']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array("jpg", "jpeg", "png");

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('logo_', true) . '.' . $file_ext;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
                $logo = $target_file;
            } else {
                echo "<script>alert('Gagal mengunggah logo.');</script>";
            }
        } else {
            echo "<script>alert('Format file tidak valid. Hanya jpg, jpeg, atau png yang diperbolehkan.');</script>";
        }
    }

    $updateQuery = "UPDATE jobs SET
        nama_perusahaan='$nama_perusahaan',
        kategori='$kategori',
        posisi='$posisi',
        jenis='$jenis',
        gaji_min=$gaji_min,
        gaji_max=$gaji_max,
        logo='$logo'
        WHERE id=$id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Data lowongan berhasil diperbarui.'); window.location.href='admin_menu.php';</script>";
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
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
    <header>
        <nav class="navbar">
            <a href="admin_menu.php">
                <img class="logo" src="images/navbarLogo.png" alt="Logo">
            </a>
            <ul class="nav-links">
                <li><a href="admin_menu.php">Dashboard</a></li>
                <li><a href="logout.php" class="contact-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="form-container">
            <h2>Edit Lowongan</h2>
            <form method="POST" enctype="multipart/form-data">
                <label for="nama_perusahaan">Nama Perusahaan:</label>
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?= htmlspecialchars($row['nama_perusahaan']) ?>" required>

                <label for="kategori">Kategori:</label>
                <input type="text" id="kategori" name="kategori" value="<?= htmlspecialchars($row['kategori']) ?>" required>

                <label for="posisi">Posisi:</label>
                <input type="text" id="posisi" name="posisi" value="<?= htmlspecialchars($row['posisi']) ?>" required>

                <label for="jenis">Jenis:</label>
                <input type="text" id="jenis" name="jenis" value="<?= htmlspecialchars($row['jenis']) ?>" required>

                <label for="gaji_min">Gaji Minimum:</label>
                <input type="number" id="gaji_min" name="gaji_min" value="<?= htmlspecialchars($row['gaji_min']) ?>" required>

                <label for="gaji_max">Gaji Maksimum:</label>
                <input type="number" id="gaji_max" name="gaji_max" value="<?= htmlspecialchars($row['gaji_max']) ?>" required>

                <label for="logo">Upload Logo (JPG/PNG):</label>
                <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png">

                <?php if (!empty($row['logo'])): ?>
                    <p>Logo saat ini: <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo" width="100"></p>
                <?php endif; ?>

                <button type="submit">Simpan</button>
            </form>
            <br>
            <a href="admin_menu.php">Kembali</a>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
