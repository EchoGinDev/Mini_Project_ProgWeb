<?php
session_start();
include 'koneksi.php';

// Query to get all applicants with their applied company names
$query = "SELECT l.*, j.username AS company_name 
          FROM lamaran l
          JOIN jobs j ON l.id_lowongan = j.id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelamar</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="container">
        <h1>Daftar Pelamar</h1>
        
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Perusahaan</th>
                    <th>CV</th>
                    <th>Portofolio</th>
                    <th>Surat Lamaran</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomor_hp']); ?></td>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['cv']); ?>" class="btn-data">Download CV</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['portofolio']); ?>" class="btn-data">Download Portofolio</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['surat_lamaran']); ?>" class="btn-data">Download Surat</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin_menu.php" class="btn btn-cancel">Kembali</a>
    </div>
</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>