<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Pastikan ada kolom role di tabel users

            // Redirect sesuai role
            if ($user['role'] === 'admin') {
                header("Location: admin_menu.php");
            } elseif ($user['role'] === 'company') {
                header("Location: company_menu.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="login.php">
            <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
        </a>
    </nav>
</header>

<main>
    <section class="login-container">
        <div class="login-box">
            <img src="images/logo_login.png" alt="Lookjob Logo" class="login-logo">
            <h2>Sign in to your account</h2>

            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <div class="login-options">
                    <label class="checkbox">
                        <input type="checkbox"> Keep me logged in
                    </label>
                    <a href="#" class="forgot-password">Lupa password?</a>
                </div>
                
                <button type="submit" class="login-btn">Log in</button>
            </form>

            <p>Tidak punya akun? <a href="register.php">Sign up</a></p>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 Echo1</p>
</footer>
</body>
</html>
