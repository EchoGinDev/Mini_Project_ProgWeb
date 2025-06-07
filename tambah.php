<?php
session_start();
include 'koneksi.php';

// Cek login role admin atau company
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'company'])) {
    header("Location: login.php");
    exit;
}

// Handle tambah lowongan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jika role company, ambil nama_perusahaan dari tabel users
    if ($_SESSION['role'] === 'company') {
        $email_company = mysqli_real_escape_string($conn, $_SESSION['email']);
        $query_user = "SELECT nama_perusahaan FROM users WHERE email = '$email_company' LIMIT 1";
        $result_user = mysqli_query($conn, $query_user);
        if ($result_user && mysqli_num_rows($result_user) > 0) {
            $user_data = mysqli_fetch_assoc($result_user);
            $nama_perusahaan = mysqli_real_escape_string($conn, $user_data['nama_perusahaan']);
        } else {
            echo "<script>alert('Perusahaan tidak ditemukan.'); window.location='company_menu.php';</script>";
            exit;
        }
    } else {
        // Role admin tetap input manual
        $nama_perusahaan = mysqli_real_escape_string($conn, $_POST['nama_perusahaan']);
    }

    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = intval($_POST['gaji_min']);
    $gaji_max = intval($_POST['gaji_max']);

    $logo = ""; // default logo kosong

    // Jika ada file logo diupload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

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

    $query = "INSERT INTO jobs (nama_perusahaan, kategori, posisi, jenis, gaji_min, gaji_max, logo)
              VALUES ('$nama_perusahaan', '$kategori', '$posisi', '$jenis', $gaji_min, $gaji_max, '$logo')";
    mysqli_query($conn, $query);

    if ($_SESSION['role'] === 'company') {
        header("Location: company_menu.php");
    } else {
        header("Location: admin_menu.php");
    }
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
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin_menu.php">
            <?php else: ?>
                <a href="company_menu.php">
            <?php endif; ?>
                <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
            </a>
            <ul class="nav-links">
                <li><a href="<?=$_SESSION['role']==='admin' ? 'admin_menu.php' : 'company_menu.php' ?>">Dashboard</a></li>
                <li><a href="logout.php" class="contact-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-box">
            <h2>Tambah Lowongan Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="form-group">
                    <label for="nama_perusahaan">Nama Perusahaan:</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" required>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" required>
                </div>

                <div class="form-group">
                    <label for="posisi">Posisi:</label>
                    <input type="text" id="posisi" name="posisi" required>
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis:</label>
                    <input type="text" id="jenis" name="jenis" required>
                </div>

                <div class="form-group">
                    <label for="gaji_min">Gaji Minimum:</label>
                    <input type="number" id="gaji_min" name="gaji_min" required>
                </div>

                <div class="form-group">
                    <label for="gaji_max">Gaji Maksimum:</label>
                    <input type="number" id="gaji_max" name="gaji_max" required>
                </div>

                <div class="form-group">
                    <label for="logo">Upload Logo (JPG/PNG):</label>
                    <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Simpan</button>
                    <a href="<?=$_SESSION['role']==='admin' ? 'admin_menu.php' : 'company_menu.php' ?>" class="btn-cancel">Kembali</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
