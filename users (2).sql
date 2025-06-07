-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 06:44 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','company') NOT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `nama_perusahaan`) VALUES
(2, 'efrant@gmail.com', '$2y$10$K6LQ2KfIsezIitoIioSWhOqYkhCaMdO9zS/P0MrOdzlLY9xce7uU6', 'user', NULL),
(6, 'admin@gmail.com', '$2y$10$o30Q8QoCFzFaYhKX6nToXe2uMheCV5VV0k08JM0cVFT6E3wkqNFvG', 'admin', NULL),
(7, 'andriano@gmail.com', '$2y$10$e0oporg8e4vOhWbP26wLYeCejWB107xbmwAGX/zmGkqRaxbQpTXoe', 'user', NULL),
(8, 'pindad@company.com', '$2y$10$GlSxgR9mbD0LhW2BV6yjT.n8XhdjEH6QhL1u7i1KUEC7c4rvk54Oi', 'company', 'PT. Pindad'),
(9, 'tokopedia@company.com', '$2y$10$8jIcfNetwPCvVxqy0U28juXbSD81LSAY2.yba9r0MYU.nPmPjXnjq', 'company', 'Tokopedia');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
