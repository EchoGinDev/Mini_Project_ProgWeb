<?php
session_start();
include 'koneksi.php';

// Query to get all applicants with their applied company names
$query = "SELECT l.*, j.username AS company_name 
          FROM lamaran l
          JOIN jobs j ON l.id = j.id";

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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1400px; /* Wider container */
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Add horizontal scroll if needed */
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        table {
            width: 100%;
            min-width: 1200px; /* Minimum table width */
            border-collapse: separate;
            border-spacing: 0;
            margin: 25px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 18px 15px; /* More vertical padding */
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap; /* Prevent text wrapping */
        }
        
        /* Wider columns for specific fields */
        td:nth-child(1), th:nth-child(1) { /* Nama */
            width: 180px;
        }
        
        td:nth-child(3), th:nth-child(3) { /* Email */
            width: 220px;
        }
        
        td:nth-child(5), th:nth-child(5) { /* Perusahaan */
            width: 150px;
        }
        
        th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9f7fe;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
            margin: 5px 0;
            min-width: 120px; /* Wider buttons */
        }
        
        .btn:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .btn-back {
            background-color: #7f8c8d;
            margin-top: 20px;
            display: inline-block;
            padding: 12px 25px;
        }
        
        .btn-back:hover {
            background-color: #34495e;
        }
    </style>
    
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
                        <td><a href="<?php echo htmlspecialchars($row['cv']); ?>" class="btn">Download CV</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['portofolio']); ?>" class="btn">Download Portofolio</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['surat_lamaran']); ?>" class="btn">Download Surat</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin_menu.php" class="btn btn-back">Kembali ke Admin Menu</a>
    </div>
</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>