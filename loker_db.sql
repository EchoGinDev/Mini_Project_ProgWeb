-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 07:56 PM
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
  `nama_perusahaan` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `posisi` varchar(100) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `gaji` varchar(50) DEFAULT NULL,
  `detail_page` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `nama_perusahaan`, `logo`, `kategori`, `posisi`, `jenis`, `gaji`, `detail_page`) VALUES
(1, 'PT. Pindad', 'images/logo1.png', 'IT', 'Backend Developer', 'Remote', 'Rp 12.000.000 - Rp 18.000.000', 'detail1.php'),
(2, 'Tokopedia', 'images/logo2.png', 'E-commerce', 'Digital Marketing', 'Freelance', 'Rp 7.000.000 - Rp 10.000.000', 'detail2.php'),
(3, 'INDOMARET', 'images/logo4.png', 'Logistik', 'Warehouse Staff', 'Full-time', 'Rp 5.000.000 - Rp 10.000.000', 'detail4.php');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(2, 'efrant@gmail.com', '$2y$10$K6LQ2KfIsezIitoIioSWhOqYkhCaMdO9zS/P0MrOdzlLY9xce7uU6'),
(4, 'admin@example.com', '$2y$10$ngLsoHJHZ7.V.CDhsp177O8ZeDsW9kbLBH1IBysNKP94HQPZ.biYa'),
(5, 'uzumaki@email.com', '$2y$10$hlvwFT.FdPsDckdXh8lTquaTTopPeokm8h6GVlpuon/b/EStYy8Ru'),
(6, 'uzu@email.com', '$2y$10$pDaVg/6dNfmQwottVrrnc.jVB33uUFMmWntWH6qoGD8w.RcBJqwCa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
