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
    // Jika role company, ambil username dari tabel users berdasarkan email
    if ($_SESSION['role'] === 'company') {
        if (!isset($_SESSION['email'])) {
            die("<script>alert('Sesi tidak valid. Silakan login kembali.'); window.location='logout.php';</script>");
        }
        
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT username FROM users WHERE email = ? AND role = 'company' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            die("<script>alert('Data perusahaan tidak valid.'); window.location='company_menu.php';</script>");
        }
        
        $user_data = $result->fetch_assoc();
        $username = $user_data['username'];
    } else {
        // Role admin - validasi input manual
        if (empty($_POST['username'])) {
            die("<script>alert('Nama perusahaan wajib diisi.'); history.back();</script>");
        }
        $username = mysqli_real_escape_string($conn, $_POST['username']);
    }

    // Validasi input wajib
    $required_fields = ['lokasi', 'kategori', 'posisi', 'jenis', 'gaji_min', 'gaji_max', 'deskripsi', 'batas_lamaran'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("<script>alert('$field wajib diisi.'); history.back();</script>");
        }
    }

    // Sanitasi input
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = (int)$_POST['gaji_min'];
    $gaji_max = (int)$_POST['gaji_max'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $batas_lamaran = $_POST['batas_lamaran']; // Sudah berupa format YYYY-MM-DD dari input type="date"

    // Handle upload logo
    $logo = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['logo']['type'], $allowed_types)) {
            die("<script>alert('Format file harus JPG/PNG.'); history.back();</script>");
        }
        
        if ($_FILES['logo']['size'] > $max_size) {
            die("<script>alert('Ukuran file maksimal 2MB.'); history.back();</script>");
        }
        
        $target_dir = "uploads/logos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $file_ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('logo_', true) . '.' . strtolower($file_ext);
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_file)) {
            $logo = $target_file;
        }
    }

    // Insert ke database dengan prepared statement
    $stmt = $conn->prepare("INSERT INTO jobs (username, lokasi, kategori, posisi, jenis, gaji_min, gaji_max, deskripsi, logo, batas_lamaran) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiisss", $username, $lokasi, $kategori, $posisi, $jenis, $gaji_min, $gaji_max, $deskripsi, $logo, $batas_lamaran);
    
    if ($stmt->execute()) {
        $redirect = ($_SESSION['role'] === 'company') ? 'company_menu.php' : 'admin_menu.php';
        header("Location: $redirect?success=1");
        exit;
    } else {
        die("<script>alert('Gagal menyimpan data: " . addslashes($conn->error) . "'); history.back();</script>");
    }
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
                <label for="username">Nama Perusahaan:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="lokasi">Lokasi:</label>
                <input type="text" id="lokasi" name="lokasi" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <input type="text" id="kategori" name="kategori" required>
            </div>

            <div class="form-group">
                <label for="posisi">Posisi:</label>
                <input type="text" id="posisi" name="posisi" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Pekerjaan:</label>
                <select id="jenis" name="jenis" required>
                    <option value="">-- Pilih Jenis Pekerjaan --</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Remote">Remote</option>
                    <option value="Freelance">Freelance</option>
                </select>
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
                <label for="batas_lamaran">Batas Lamaran:</label>
                <input type="date" id="batas_lamaran" name="batas_lamaran" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Pekerjaan:</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
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
