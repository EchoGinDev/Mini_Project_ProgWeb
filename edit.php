<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'company'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin_menu.php' : 'company_menu.php'));
    exit;
}

$id = intval($_GET['id']);
$username = '';

if ($_SESSION['role'] === 'company') {
    if (!isset($_SESSION['email'])) {
        die("<script>alert('Sesi tidak valid. Silakan login kembali.'); window.location='logout.php';</script>");
    }

    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ? AND role = 'company' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_user = $stmt->get_result();

    if ($result_user->num_rows === 0) {
        die("<script>alert('Data perusahaan tidak valid.'); window.location='company_menu.php';</script>");
    }

    $username_data = $result_user->fetch_assoc();
    $username = $username_data['username'];

    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $id, $username);
} else {
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $id);
}
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<script>alert('Data tidak ditemukan atau akses ditolak.'); window.location='" . ($_SESSION['role'] === 'admin' ? 'admin_menu.php' : 'company_menu.php') . "';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['role'] === 'admin') {
        if (empty($_POST['username'])) {
            die("<script>alert('Nama perusahaan wajib diisi.'); history.back();</script>");
        }
        $username = mysqli_real_escape_string($conn, $_POST['username']);
    }

    $required_fields = ['lokasi', 'kategori', 'posisi', 'jenis', 'gaji_min', 'gaji_max', 'deskripsi', 'batas_lamaran'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("<script>alert('$field wajib diisi.'); history.back();</script>");
        }
    }

    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $posisi = mysqli_real_escape_string($conn, $_POST['posisi']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $gaji_min = (int)$_POST['gaji_min'];
    $gaji_max = (int)$_POST['gaji_max'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $batas_lamaran = mysqli_real_escape_string($conn, $_POST['batas_lamaran']);

    $logo = $row['logo'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024;

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

    $update = $conn->prepare("UPDATE jobs SET username=?, lokasi=?, kategori=?, posisi=?, jenis=?, gaji_min=?, gaji_max=?, deskripsi=?, logo=?, batas_lamaran=? WHERE id=?");
    $update->bind_param("sssssiisssi", $username, $lokasi, $kategori, $posisi, $jenis, $gaji_min, $gaji_max, $deskripsi, $logo, $batas_lamaran, $id);

    if ($update->execute()) {
        $redirect = ($_SESSION['role'] === 'company') ? 'company_menu.php' : 'admin_menu.php';
        echo "<script>alert('Lowongan berhasil diperbarui.'); window.location='$redirect';</script>";
        exit;
    } else {
        die("<script>alert('Gagal memperbarui data: " . addslashes($conn->error) . "'); history.back();</script>");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Lowongan</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <nav class="navbar">
        <a href="<?= $_SESSION['role'] === 'admin' ? 'admin_menu.php' : 'company_menu.php' ?>">
            <img class="logo" src="images/navbarLogo.png" alt="Logo">
        </a>
        <ul class="nav-links">
            <li><a href="<?= $_SESSION['role'] === 'admin' ? 'admin_menu.php' : 'company_menu.php' ?>">Dashboard</a></li>
            <li><a href="logout.php" class="contact-btn">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="form-box">
        <h2>Edit Lowongan</h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="form-group">
                <label for="username">Nama Perusahaan:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($row['username']) ?>" required>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="lokasi">Lokasi:</label>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($row['lokasi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <input type="text" id="kategori" name="kategori" value="<?= htmlspecialchars($row['kategori']) ?>" required>
            </div>

            <div class="form-group">
                <label for="posisi">Posisi:</label>
                <input type="text" id="posisi" name="posisi" value="<?= htmlspecialchars($row['posisi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis Pekerjaan:</label>
                <select id="jenis" name="jenis" required>
                    <option value="">-- Pilih Jenis Pekerjaan --</option>
                    <?php
                    $options = ['Full-time', 'Part-time', 'Remote', 'Freelance'];
                    foreach ($options as $opt) {
                        $selected = ($row['jenis'] === $opt) ? 'selected' : '';
                        echo "<option value='$opt' $selected>$opt</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="gaji_min">Gaji Minimum:</label>
                <input type="number" id="gaji_min" name="gaji_min" value="<?= htmlspecialchars($row['gaji_min']) ?>" required>
            </div>

            <div class="form-group">
                <label for="gaji_max">Gaji Maksimum:</label>
                <input type="number" id="gaji_max" name="gaji_max" value="<?= htmlspecialchars($row['gaji_max']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Pekerjaan:</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="batas_lamaran">Batas Lamaran:</label>
                <input type="date" id="batas_lamaran" name="batas_lamaran" value="<?= htmlspecialchars($row['batas_lamaran']) ?>" required>
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
                <button type="submit" class="btn btn-submit">Simpan</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='<?= $_SESSION['role'] === 'admin' ? 'admin_menu.php' : 'company_menu.php' ?>'">Kembali</button>
            </div>
        </form>
    </div>
</main>

<footer></footer>
</body>
</html>
