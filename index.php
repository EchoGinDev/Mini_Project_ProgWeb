<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Halaman Utama</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.html">
                <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
            </a>
            <!-- <div class="logo">LOGOBAKERY</div> -->
            <ul class="nav-links">
                <li><a href="#">About</a></li>
                <li><a href="index.html">Home</a></li>
                <li><a href="login.html" class="contact-btn">login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="search-section">
            <h2>Cari Lowongan</h2>
            <form>
                <input type="text" placeholder="Nama Perusahaan">
                <input type="text" placeholder="Kategori Pekerjaan">
                <input type="text" placeholder="Lokasi">
                <input type="text" placeholder="Jenis Pekerjaan">
                <input type="text" placeholder="Rentang Gaji">
                <button type="submit" disabled>Cari (Fitur Belum Tersedia)</button>
            </form>
        </section>

 
        <h2>Temukan perusahaan Anda berikutnya</h2>
        <p>
            Jelajahi profil perusahaan untuk menemukan tempat kerja yang tepat bagi Anda. 
            Pelajari tentang pekerjaan, ulasan, budaya perusahaan, keuntungan, dan tunjangan.
        </p>


        <hr style="border: 1px solid rgb(39, 39, 39);">

        <section class="job-listings">

            <h1>Daftar Lowongan</h1>
            <div class="job-item">
                <img src="images/logo1.png" alt="Logo Perusahaan">
                <h3>Nama Perusahaan: PT.Pindad</h3>
                <p>Kategori: IT</p>
                <p>Posisi: Backend Developer</p>
                <p>Jenis: Remote</p>
                <p>Gaji: Rp 12.000.000 - Rp 18.000.000</p>
                <a class="detail-btn" href="detail.html">Lihat Detail</a>
            </div>

            <div class="job-item">
                <img src="images/logo2.png" alt="Logo Perusahaan">
                <h3>Nama Perusahaan: Tokopedia</h3>
                <p>Kategori: E-commerce</p>
                <p>Posisi: Digital Marketing</p>
                <p>Jenis: Freelance</p>
                <p>Gaji: Rp 7.000.000 - Rp 10.000.000</p>
                <a class="detail-btn" href="detail2.html">Lihat Detail</a>
            </div>

            <div class="job-item">
                <img src="images/logo3.png" alt="Logo Perusahaan">
                <h3>Nama Perusahaan: Rhodes Island Pharmaceuticals Inc.</h3>
                <p>Kategori: Logistik</p>
                <p>Posisi: Distributor</p>
                <p>Jenis: Full-time</p>
                <p>Gaji: Rp 3.000.000 - Rp 6.000.000</p>
                <a class="detail-btn" href="detail3.html">Lihat Detail</a>
            </div>

            <div class="job-item">
                <img src="images/logo4.png" alt="Logo Perusahaan">
                <h3>Nama Perusahaan: INDOMARET</h3>
                <p>Kategori: Logistik</p>
                <p>Posisi: Warehouse Staff</p>
                <p>Jenis: Full-time</p>
                <p>Gaji: Rp 5.000.000 - Rp 10.000.000</p>
                <a class="detail-btn" href="detail4.html">Lihat Detail</a>
            </div>

            <div class="job-item">
                <img src="images/logo5.png" alt="Logo Perusahaan">
                <h3>Nama Perusahaan: Drowning Cat</h3>
                <p>Kategori: Game art</p>
                <p>Posisi: Art Illustrator</p>
                <p>Jenis: Remote</p>
                <p>Gaji: Rp 3.000.000 - Rp 7.000.000</p>
                <a class="detail-btn" href="detail5.html">Lihat Detail</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>