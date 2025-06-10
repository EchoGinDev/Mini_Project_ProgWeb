<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

// Ambil ID lowongan dari URL
if (!isset($_GET['id'])) {
    echo "<script>alert('ID lowongan tidak tersedia.'); window.location.href='index.php';</script>";
    exit;
}

$id_lowongan = intval($_GET['id']);

// Ambil ID user dari email
$get_user = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email_pengguna'");
if ($user = mysqli_fetch_assoc($get_user)) {
    $id_user = $user['id'];
} else {
    echo "<script>alert('User tidak ditemukan.'); window.location.href='index.php';</script>";
    exit;
}

// Cek apakah user sudah pernah melamar lowongan ini
$cek_duplikat = mysqli_query($conn, "SELECT * FROM lamaran WHERE id_user = '$id_user' AND id_lowongan = '$id_lowongan'");
if (mysqli_num_rows($cek_duplikat) > 0) {
    echo "<script>alert('Anda sudah melamar pekerjaan ini sebelumnya.'); window.location.href='index.php';</script>";
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);

    $max_size = 5 * 1024 * 1024; // 5MB
    $upload_dir = "uploads/";
    
    // Buat folder uploads jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Proses upload CV
    $cv = '';
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == UPLOAD_ERR_OK) {
        $cv_name = basename($_FILES['cv']['name']);
        $cv_tmp = $_FILES['cv']['tmp_name'];
        $cv_size = $_FILES['cv']['size'];
        
        if ($cv_size > $max_size) {
            $error = "Ukuran file CV tidak boleh lebih dari 5 MB.";
        } else {
            $cv = $upload_dir . uniqid() . '_' . $cv_name;
            if (!move_uploaded_file($cv_tmp, $cv)) {
                $error = "Gagal mengupload CV.";
            }
        }
    } else {
        $error = "CV wajib diupload.";
    }

    // Proses upload Portofolio (opsional)
    $portofolio = '';
    if (isset($_FILES['portofolio']) && $_FILES['portofolio']['error'] == UPLOAD_ERR_OK) {
        $portofolio_name = basename($_FILES['portofolio']['name']);
        $portofolio_tmp = $_FILES['portofolio']['tmp_name'];
        $portofolio_size = $_FILES['portofolio']['size'];
        
        if ($portofolio_size > $max_size) {
            $error = "Ukuran file Portofolio tidak boleh lebih dari 5 MB.";
        } else {
            $portofolio = $upload_dir . uniqid() . '_' . $portofolio_name;
            if (!move_uploaded_file($portofolio_tmp, $portofolio)) {
                $error = "Gagal mengupload Portofolio.";
            }
        }
    }

    // Proses upload Surat Lamaran (opsional)
    $surat_lamaran = '';
    if (isset($_FILES['surat_lamaran']) && $_FILES['surat_lamaran']['error'] == UPLOAD_ERR_OK) {
        $surat_name = basename($_FILES['surat_lamaran']['name']);
        $surat_tmp = $_FILES['surat_lamaran']['tmp_name'];
        $surat_size = $_FILES['surat_lamaran']['size'];
        
        if ($surat_size > $max_size) {
            $error = "Ukuran file Surat Lamaran tidak boleh lebih dari 5 MB.";
        } else {
            $surat_lamaran = $upload_dir . uniqid() . '_' . $surat_name;
            if (!move_uploaded_file($surat_tmp, $surat_lamaran)) {
                $error = "Gagal mengupload Surat Lamaran.";
            }
        }
    }

    // Jika tidak ada error, simpan ke database
    if (!isset($error)) {
        $sql = "INSERT INTO lamaran 
                (nama, tanggal_lahir, email, nomor_hp, cv, portofolio, surat_lamaran, id_user, id_lowongan)
                VALUES 
                ('$nama', '$tanggal_lahir', '$email', '$nomor_hp', '$cv', " . 
                (!empty($portofolio) ? "'$portofolio'" : "NULL") . ", " .
                (!empty($surat_lamaran) ? "'$surat_lamaran'" : "NULL") . ", 
                '$id_user', '$id_lowongan')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Lamaran berhasil dikirim!'); window.location.href='index.php';</script>";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
    
    // Jika ada error, tampilkan
    if (isset($error)) {
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Pengajuan Lamaran</title>
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
                <li><span class="user-email"><?= htmlspecialchars($email_pengguna) ?></span></li>
                <li><a href="logout.php" class="contact-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="form-box">
            <h2>Formulir Pengajuan Lamaran</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email_pengguna) ?>" required readonly>
                </div>

                <div class="form-group">
                    <label for="nomor_hp">Nomor HP:</label>
                    <input type="tel" id="nomor_hp" name="nomor_hp" required>
                </div>

                <div class="form-group">
                    <label for="cv">Upload CV (PDF, maks 5MB):</label>
                    <input type="file" id="cv" name="cv" accept=".pdf" required>
                </div>

                <div class="form-group">
                    <label for="portofolio">Upload Portofolio (PDF, maks 5MB, opsional):</label>
                    <input type="file" id="portofolio" name="portofolio" accept=".pdf">
                </div>

                <div class="form-group">
                    <label for="surat_lamaran">Upload Surat Lamaran (maks 5MB, opsional):</label>
                    <input type="file" id="surat_lamaran" name="surat_lamaran">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Submit Lamaran</button>
                    <a href="index.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>