<?php
session_start();
include 'koneksi.php';

// Pastikan hanya admin atau company yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'company'])) {
    header("Location: login.php");
    exit;
}

// Pastikan parameter ID tersedia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "admin_menu.php" : "company_menu.php"));
    exit;
}

$id = intval($_GET['id']);

// Jika role company, ambil nama_perusahaan dari session (dari tabel users)
$nama_perusahaan_company = '';
if ($_SESSION['role'] === 'company') {
    $email_company = mysqli_real_escape_string($conn, $_SESSION['email']);
    $query_user = "SELECT nama_perusahaan FROM users WHERE email = '$email_company' LIMIT 1";
    $result_user = mysqli_query($conn, $query_user);
    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $user_data = mysqli_fetch_assoc($result_user);
        $nama_perusahaan_company = $user_data['nama_perusahaan'];
    } else {
        echo "Perusahaan tidak ditemukan.";
        exit;
    }
}

// Ambil data pekerjaan yang akan diedit
if ($_SESSION['role'] === 'admin') {
    $query = "SELECT * FROM jobs WHERE id = $id";
} else {
    // Untuk company, pastikan lowongan milik perusahaan yang login
    $nama_perusahaan_company_esc = mysqli_real_escape_string($conn, $nama_perusahaan_company);
    $query = "SELECT * FROM jobs WHERE id = $id AND nama_perusahaan = '$nama_perusahaan_company_esc'";
}

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "<p>Data lowongan tidak ditemukan atau Anda tidak memiliki izin mengedit.</p>";
    echo "<a href='" . ($_SESSION['role'] === 'admin' ? "admin_menu.php" : "company_menu.php") . "'>Kembali</a>";
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

    // Untuk role company, paksa nama_perusahaan sesuai session, agar tidak diubah seenaknya
    if ($_SESSION['role'] === 'company') {
        $nama_perusahaan = $nama_perusahaan_company;
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
        echo "<script>alert('Data lowongan berhasil diperbarui.'); window.location.href='" . ($_SESSION['role'] === 'admin' ? "admin_menu.php" : "company_menu.php") . "';</script>";
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
    <!-- <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            background-color: #2c3e50;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-links li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin-left: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav-links li a:hover {
            background-color: #34495e;
        }
        
        .contact-btn {
            background-color: #e74c3c;
        }
        
        .contact-btn:hover {
            background-color: #c0392b;
        }
        
        .form-box {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 700px;
            margin: 30px auto;
            padding: 30px;
        }
        
        .form-box h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52,152,219,0.5);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn-submit {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #27ae60;
        }
        
        .btn-cancel {
            background-color: #95a5a6;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .btn-cancel:hover {
            background-color: #7f8c8d;
        }
        
        .current-logo {
            margin: 20px 0;
            text-align: center;
        }
        
        .current-logo img {
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        
    </style> -->
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
        <div class="form-box">
            <h2>Edit Lowongan</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_perusahaan">Nama Perusahaan:</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" 
                           value="<?= htmlspecialchars($row['nama_perusahaan']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" 
                           value="<?= htmlspecialchars($row['kategori']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="posisi">Posisi:</label>
                    <input type="text" id="posisi" name="posisi" 
                           value="<?= htmlspecialchars($row['posisi']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis:</label>
                    <input type="text" id="jenis" name="jenis" 
                           value="<?= htmlspecialchars($row['jenis']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="gaji_min">Gaji Minimum:</label>
                    <input type="number" id="gaji_min" name="gaji_min" 
                           value="<?= htmlspecialchars($row['gaji_min']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="gaji_max">Gaji Maksimum:</label>
                    <input type="number" id="gaji_max" name="gaji_max" 
                           value="<?= htmlspecialchars($row['gaji_max']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="logo">Upload Logo (JPG/PNG):</label>
                    <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png">
                </div>

                <?php if (!empty($row['logo'])): ?>
                    <div class="current-logo">
                        <p>Logo saat ini:</p>
                        <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo" width="100">
                    </div>
                <?php endif; ?>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Simpan</button>
                    <a href="admin_menu.php" class="btn-cancel">Kembali</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>