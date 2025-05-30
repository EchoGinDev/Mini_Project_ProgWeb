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
                <li><a href="login.php" class="contact-btn">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="application-form">
            <h2>Formulir Pengajuan Lamaran</h2>
            <form>
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" required>

                <label for="tanggal_lahir">Tanggal Lahir:</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

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
        <p>&copy; 2024 Job Portal. All rights reserved.</p>
    </footer>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            e.preventDefault();
            alert('Lamaran berhasil dikirim!');
        });
    </script>
</body>
</html>