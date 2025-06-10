<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

// Ambil id_user dari tabel users
$query_user = "SELECT id FROM users WHERE email = '$email_pengguna'";
$result_user = mysqli_query($conn, $query_user);
$row_user = mysqli_fetch_assoc($result_user);

if (!$row_user) {
    echo "<script>alert('User tidak ditemukan.'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $row_user['id'];

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah user sudah pernah melamar
    $check_query = "SELECT id FROM lamaran WHERE id_user = '$id_user'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Anda sudah pernah mengirimkan lamaran.'); window.location.href='index.php';</script>";
        exit;
    }

    // Ambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);

    // Maksimum ukuran file (5 MB)
    $max_size = 5 * 1024 * 1024;

    // Handle upload file CV
    $cv_name = $_FILES['cv']['name'];
    $cv_tmp = $_FILES['cv']['tmp_name'];
    $cv_size = $_FILES['cv']['size'];
    $cv_folder = "uploads/" . $cv_name;

    // Upload file portofolio (opsional)
    $portofolio_name = $_FILES['portofolio']['name'];
    $portofolio_tmp = $_FILES['portofolio']['tmp_name'];
    $portofolio_size = $_FILES['portofolio']['size'];
    $portofolio_folder = "uploads/" . $portofolio_name;

    // Upload surat lamaran (opsional)
    $surat_name = $_FILES['surat_lamaran']['name'];
    $surat_tmp = $_FILES['surat_lamaran']['tmp_name'];
    $surat_size = $_FILES['surat_lamaran']['size'];
    $surat_folder = "uploads/" . $surat_name;

    // Validasi ukuran file
    if ($cv_size > $max_size) {
        $error = "Ukuran file CV tidak boleh lebih dari 5 MB.";
    } elseif (!empty($portofolio_name) && $portofolio_size > $max_size) {
        $error = "Ukuran file Portofolio tidak boleh lebih dari 5 MB.";
    } elseif (!empty($surat_name) && $surat_size > $max_size) {
        $error = "Ukuran file Surat Lamaran tidak boleh lebih dari 5 MB.";
    } else {
        // Pastikan folder uploads/ ada
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        // Pindahkan file yang diupload
        move_uploaded_file($cv_tmp, $cv_folder);
        if (!empty($portofolio_name)) {
            move_uploaded_file($portofolio_tmp, $portofolio_folder);
        }
        if (!empty($surat_name)) {
            move_uploaded_file($surat_tmp, $surat_folder);
        }

        // Masukkan data ke database, sertakan id_user
        $sql = "INSERT INTO lamaran (id_user, nama, tanggal_lahir, email, nomor_hp, cv, portofolio, surat_lamaran)
                VALUES ('$id_user', '$nama', '$tanggal_lahir', '$email', '$nomor_hp', '$cv_name', '$portofolio_name', '$surat_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Lamaran berhasil dikirim!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
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
                    <label for="cv">Upload CV (PDF):</label>
                    <input type="file" id="cv" name="cv" accept=".pdf" required>
                </div>

                <div class="form-group">
                    <label for="portofolio">Upload Portofolio (PDF, opsional):</label>
                    <input type="file" id="portofolio" name="portofolio" accept=".pdf">
                </div>

                <div class="form-group">
                    <label for="surat_lamaran">Upload Surat Lamaran (opsional):</label>
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
