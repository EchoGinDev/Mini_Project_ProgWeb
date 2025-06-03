<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_pengguna = $_SESSION['email'];

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $nomor_hp = mysqli_real_escape_string($conn, $_POST['nomor_hp']);

    // Handle upload file CV
    $cv_name = $_FILES['cv']['name'];
    $cv_tmp = $_FILES['cv']['tmp_name'];
    $cv_folder = "uploads/" . $cv_name;

    // Upload file portofolio (opsional)
    $portofolio_name = $_FILES['portofolio']['name'];
    $portofolio_tmp = $_FILES['portofolio']['tmp_name'];
    $portofolio_folder = "uploads/" . $portofolio_name;

    // Upload surat lamaran (opsional)
    $surat_name = $_FILES['surat_lamaran']['name'];
    $surat_tmp = $_FILES['surat_lamaran']['tmp_name'];
    $surat_folder = "uploads/" . $surat_name;

    // Pastikan folder uploads/ ada
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Pindahkan file yang diupload
    move_uploaded_file($cv_tmp, $cv_folder);
    if ($portofolio_name != "") {
        move_uploaded_file($portofolio_tmp, $portofolio_folder);
    }
    if ($surat_name != "") {
        move_uploaded_file($surat_tmp, $surat_folder);
    }

    // Masukkan data ke database
    $sql = "INSERT INTO lamaran (nama, tanggal_lahir, email, nomor_hp, cv, portofolio, surat_lamaran)
            VALUES ('$nama', '$tanggal_lahir', '$email', '$nomor_hp', '$cv_name', '$portofolio_name', '$surat_name')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Lamaran berhasil dikirim!'); window.location.href='index.php';</script>";
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
                <li><span style="color: white; margin-right: 10px;"><?= htmlspecialchars($email_pengguna) ?></span></li>
                <li><a href="logout.php" class="contact-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="application-form">
            <h2>Formulir Pengajuan Lamaran</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" required>

                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email_pengguna) ?>" required readonly>

                <label for="nomor_hp">Nomor HP:</label>
                <input type="tel" id="nomor_hp" name="nomor_hp" required>

                <label for="cv">Upload CV (PDF):</label>
                <input type="file" id="cv" name="cv" accept=".pdf" required>

                <label for="portofolio">Upload Portofolio (PDF, opsional):</label>
                <input type="file" id="portofolio" name="portofolio" accept=".pdf">

                <label for="surat_lamaran">Upload Surat Lamaran (opsional):</label>
                <input type="file" id="surat_lamaran" name="surat_lamaran">

                <button type="submit">Submit</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
