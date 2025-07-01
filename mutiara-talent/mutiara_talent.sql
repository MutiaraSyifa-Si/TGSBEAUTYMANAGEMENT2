-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 08:00 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mutiara_talent`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `status` enum('Menunggu Verifikasi','Selesai','Diproses') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-06-21 13:43:50', '2025-06-21 13:43:50'),
(2, 'mutiara syifa', 'mutiara@gmail.com', '$2y$10$5o.hpzT4O/kaWvz5CU7BhesbQAeeOwRycPIUySHRpfx9MmCU1wBve', NULL, '2025-06-21 13:52:25', '2025-06-21 13:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `nama_campaign` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `min_followers` varchar(50) DEFAULT NULL,
  `engagement_rate` varchar(20) DEFAULT NULL,
  `kategori_konten` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_pengajuan` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `client_id`, `nama_campaign`, `brand`, `kategori`, `deskripsi`, `min_followers`, `engagement_rate`, `kategori_konten`, `lokasi`, `status`, `tanggal_pengajuan`, `created_at`, `updated_at`) VALUES
(1, 0, 'Begadang', 'video', 'MIKRO', 'MENGANTUK', '10', 'TAU', 'APA AJA', 'DEPOK', 'disetujui', '2025-06-21', '2025-06-21 16:34:13', '2025-06-21 16:54:54'),
(2, 0, 'Begadang 2', 'Lembur Tugas Laporan', 'mikro', 'Sudah Lelah Ini Mata', '9', 'auuuuh', 'APA AJA', 'bogor', 'disetujui', '2025-06-21', '2025-06-21 16:38:49', '2025-06-21 17:11:30'),
(3, 3, 'nn', 'nnn', 'nn', 'nnn', '8', 'oooo', 'oooo', 'DEPOK', 'ditolak', '2025-06-21', '2025-06-21 16:51:11', '2025-06-21 17:07:56'),
(4, 3, 'mmmmm', 'video', 'MIKRO', 'muiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', '10', 'bnm', 'oooo', 'jakartA', 'menunggu', '2025-06-22', '2025-06-21 17:09:00', '2025-06-21 17:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `password`, `company_name`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'SURABAYA', 'surabaya@gmail.com', '$2y$10$UKyW/dqQxNUU.eaYuxcBP.SiddbQazO/Zyq1.8lM4KVeckaDfYtRK', NULL, NULL, NULL, '2025-06-21 16:14:31', '2025-06-21 16:14:31'),
(2, 'joko', 'joko@gmail.com', '$2y$10$qhYSriauU4XRjJKQzPfase/1yJPI2bcIKGvYKzKlKXcF6uzlsZDb6', NULL, NULL, NULL, '2025-06-21 16:16:25', '2025-06-21 16:16:25'),
(3, 'messi', 'messi@gmail.com', '$2y$10$d7WQ6jNy5XbuFQbGHiO3BOz2VfMC29y2Pfk5jU6z5fMI0ExxFEnc2', NULL, NULL, NULL, '2025-06-21 16:22:18', '2025-06-21 16:22:18');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `judul`, `deskripsi`, `file_name`, `file_path`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'Sistem Basis Data', 'pertemuan 4', 'Pertemuan9_Relasi Dua Tabel.pdf', '6856cb817cb68_1750518657.pdf', 2, '2025-06-21 15:10:57', '2025-06-21 15:10:57');

-- --------------------------------------------------------

--
-- Table structure for table `talents`
--

CREATE TABLE `talents` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` enum('Nano','Mikro','Makro','Mega') NOT NULL,
  `niche` varchar(100) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `status` enum('Aktif','Nonaktif','Pending') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `talents`
--

INSERT INTO `talents` (`id`, `nama`, `kategori`, `niche`, `lokasi`, `status`, `created_at`, `updated_at`, `client_id`) VALUES
(1, 'MESSI', 'Nano', 'Apa aja Lah', 'Jakarta', 'Pending', '2025-06-21 17:48:51', '2025-06-21 17:51:07', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `talents`
--
ALTER TABLE `talents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `talents`
--
ALTER TABLE `talents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admin` (`id`);

--
-- Constraints for table `talents`
--
ALTER TABLE `talents`
  ADD CONSTRAINT `talents_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
