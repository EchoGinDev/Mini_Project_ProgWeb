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

    $max_size = 5 * 1024 * 1024;

    $cv_name = $_FILES['cv']['name'];
    $cv_tmp = $_FILES['cv']['tmp_name'];
    $cv_size = $_FILES['cv']['size'];
    $cv_folder = "uploads/" . $cv_name;

    $portofolio_name = $_FILES['portofolio']['name'];
    $portofolio_tmp = $_FILES['portofolio']['tmp_name'];
    $portofolio_size = $_FILES['portofolio']['size'];
    $portofolio_folder = "uploads/" . $portofolio_name;

    $surat_name = $_FILES['surat_lamaran']['name'];
    $surat_tmp = $_FILES['surat_lamaran']['tmp_name'];
    $surat_size = $_FILES['surat_lamaran']['size'];
    $surat_folder = "uploads/" . $surat_name;

    if ($cv_size > $max_size) {
        $error = "Ukuran file CV tidak boleh lebih dari 5 MB.";
    } elseif (!empty($portofolio_name) && $portofolio_size > $max_size) {
        $error = "Ukuran file Portofolio tidak boleh lebih dari 5 MB.";
    } elseif (!empty($surat_name) && $surat_size > $max_size) {
        $error = "Ukuran file Surat Lamaran tidak boleh lebih dari 5 MB.";
    } else {
        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($cv_tmp, $cv_folder);
        if (!empty($portofolio_name)) move_uploaded_file($portofolio_tmp, $portofolio_folder);
        if (!empty($surat_name)) move_uploaded_file($surat_tmp, $surat_folder);

        $sql = "INSERT INTO lamaran 
                (nama, tanggal_lahir, email, nomor_hp, cv, portofolio, surat_lamaran, id_user, id_lowongan)
                VALUES 
                ('$nama', '$tanggal_lahir', '$email', '$nomor_hp', '$cv_name', '$portofolio_name', '$surat_name', '$id_user', '$id_lowongan')";

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