-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 08:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loker_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `posisi` varchar(100) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `gaji` varchar(50) DEFAULT NULL,
  `detail_page` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `syarat` text DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `batas_lamaran` date DEFAULT NULL,
  `gaji_min` int(11) DEFAULT NULL,
  `gaji_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `username`, `logo`, `kategori`, `posisi`, `jenis`, `gaji`, `detail_page`, `deskripsi`, `syarat`, `lokasi`, `batas_lamaran`, `gaji_min`, `gaji_max`) VALUES
(1, 'PT. Pindad', 'images/logo1.png', 'IT', 'Backend Developer', 'Remote', 'Rp 12.000.000 - Rp 18.000.000', 'detail1.php', 'Memiliki minat kuat dan pemahaman dasar di bidang industri pertahanan dan manufaktur. Antusias untuk berkontribusi pada kemajuan teknologi dan kemandirian bangsa melalui PT Pindad. Cepat belajar, proaktif, dan mampu bekerja sama dalam tim untuk mencapai tujuan bersama. Siap menghadapi tantangan baru dan mengembangkan diri secara profesional di lingkungan kerja yang dinamis.', NULL, NULL, NULL, 12000000, 19000000),
(2, 'Tokopedia', 'images/logo2.png', 'E-commerce', 'Digital Marketing', 'Freelance', 'Rp 7.000.000 - Rp 10.000.000', 'detail2.php', 'Kami mencari seorang Digital Marketing Specialist Freelance yang berpengalaman untuk membantu merencanakan, mengimplementasikan, dan mengelola kampanye pemasaran digital yang efektif untuk mencapai tujuan bisnis kami. Anda akan bekerja secara independen, namun tetap berkoordinasi dengan tim internal Tokopedia untuk memastikan strategi yang selaras dan terintegrasi.', NULL, NULL, NULL, 7000000, 10000000),
(3, 'INDOMARET', 'images/logo4.png', 'Logistik', 'Warehouse Staff', 'Full-time', 'Rp 5.000.000 - Rp 10.000.000', 'detail4.php', 'Melayani pelanggan dengan ramah, mengoperasikan mesin kasir, melakukan transaksi penjualan, menata produk di rak toko, menjaga kebersihan area toko, dan membantu pelaksanaan promosi.\r\n\r\nKualifikasi:\r\n\r\nPria/Wanita, usia maks. 23 tahun.\r\nPendidikan min. SMA/SMK sederajat.\r\nBelum menikah.\r\nTinggi badan Pria min. 165 cm, Wanita min. 155 cm (berat badan proporsional).\r\nBerpenampilan menarik, rapi, dan bersih.\r\nJujur, cekatan, teliti, dan bertanggung jawab.\r\nMampu berkomunikasi dengan baik dan bekerja sama dalam tim.\r\nBersedia bekerja dengan sistem shift dan pada hari libur.\r\nDiutamakan berdomisili di area [Nama Kota/Area, contoh: Tasikmalaya dan sekitarnya].', NULL, NULL, NULL, 5000000, 10000000),
(5, 'Indodax', 'uploads/logo_6844217a32a570.51167352.jpg', 'Bitcoin', 'Developer', 'Freelance', NULL, NULL, NULL, NULL, NULL, NULL, 10000000, 20000000);

-- --------------------------------------------------------

--
-- Table structure for table `lamaran`
--

CREATE TABLE `lamaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `portofolio` varchar(255) DEFAULT NULL,
  `surat_lamaran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lamaran`
--

INSERT INTO `lamaran` (`id`, `nama`, `tanggal_lahir`, `email`, `nomor_hp`, `cv`, `portofolio`, `surat_lamaran`) VALUES
(1, 'Efrant Emmanuel', '2005-04-09', 'efrantemmanuel13@gmail.com', '082129167863', '#9 PHP-2.pdf', '#10 PHP-3.pdf', '#11 PHP-4.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','company') NOT NULL,
  `username` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `username`) VALUES
(2, 'efrant@gmail.com', '$2y$10$K6LQ2KfIsezIitoIioSWhOqYkhCaMdO9zS/P0MrOdzlLY9xce7uU6', 'user', NULL),
(6, 'admin@gmail.com', '$2y$10$o30Q8QoCFzFaYhKX6nToXe2uMheCV5VV0k08JM0cVFT6E3wkqNFvG', 'admin', NULL),
(7, 'andriano@gmail.com', '$2y$10$e0oporg8e4vOhWbP26wLYeCejWB107xbmwAGX/zmGkqRaxbQpTXoe', 'user', NULL),
(8, 'pindad@company.com', '$2y$10$GlSxgR9mbD0LhW2BV6yjT.n8XhdjEH6QhL1u7i1KUEC7c4rvk54Oi', 'company', 'PT. Pindad'),
(9, 'tokopedia@company.com', '$2y$10$8jIcfNetwPCvVxqy0U28juXbSD81LSAY2.yba9r0MYU.nPmPjXnjq', 'company', 'Tokopedia'),
(10, 'indodax@company.com', '$2y$10$ah8z8lpG82rRIsvUZHqqSehofPiUo1.eiHRlb7H7bwMw88hispAKm', 'company', 'Indodax');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lamaran`
--
ALTER TABLE `lamaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lamaran`
--
ALTER TABLE `lamaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
