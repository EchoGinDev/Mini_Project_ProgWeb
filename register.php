<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan ke database dengan role 'user'
            $query = "INSERT INTO users (email, password, role, username) 
                      VALUES ('$email', '$hashed_password', 'user', '$username')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Gagal mendaftar. Silakan coba lagi.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main>
        <section class="login-container">
            <div class="login-box">
                <h2>Daftar Akun Baru</h2>

                <?php if (!empty($error)): ?>
                    <p style="color: red;"><?= $error ?></p>
                <?php endif; ?>

                <form method="POST" action="register.php">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="Masukkan username">

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Masukkan email">

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Masukkan password">

                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="login-btn">Daftar</button>
                </form>

                <p>Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </section>
    </main>
</body>
</html>
