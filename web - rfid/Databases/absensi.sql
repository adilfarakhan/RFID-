-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 08:11 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_log`
--

CREATE TABLE `absensi_log` (
  `id` int(11) NOT NULL,
  `id_kartu` varchar(50) NOT NULL,
  `waktu` datetime NOT NULL,
  `keterangan` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absensi_log`
--

INSERT INTO `absensi_log` (`id`, `id_kartu`, `waktu`, `keterangan`) VALUES
(372, '1305219122', '2025-06-03 14:35:35', ''),
(373, '1191209921', '2025-06-03 14:35:38', ''),
(374, '1191200321', '2025-06-03 14:35:41', ''),
(375, '1289087250', '2025-06-03 14:35:43', ''),
(378, '1289053986', '2025-06-03 14:35:49', ''),
(379, '1191531473', '2025-06-03 14:35:50', ''),
(380, '1191526145', '2025-06-03 14:35:52', ''),
(381, '1289261090', '2025-06-03 14:35:53', ''),
(382, '1305191330', '2025-06-03 14:35:55', ''),
(383, '1305191330', '2025-06-03 16:15:40', ''),
(384, '1289261090', '2025-06-03 16:15:44', ''),
(385, '1191526145', '2025-06-03 16:16:04', ''),
(386, '1289053986', '2025-06-03 16:16:07', ''),
(387, '1289087250', '2025-06-03 16:16:09', ''),
(390, '1191531473', '2025-06-03 18:37:59', ''),
(393, '1191526145', '2025-06-17 14:46:48', ''),
(395, '1289443282', '2025-06-20 16:39:20', ''),
(396, '1191200321', '2025-06-20 16:39:52', ''),
(397, '1289087250', '2025-06-20 16:45:34', ''),
(398, '1191526145', '2025-06-24 14:44:19', ''),
(399, '1289092002', '2025-06-30 12:23:44', ''),
(400, '1289261090', '2025-06-30 12:46:34', ''),
(401, '1289189794', '2025-06-30 12:57:03', '');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `mapel` varchar(50) NOT NULL,
  `ruangan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `id_guru`, `hari`, `jam`, `mapel`, `ruangan`) VALUES
(4, 22, 'senen', '7 pagi', 'bahasa korea', '12A'),
(5, 21, 'selasa', '12 siang', 'bahasa isyarat', 'c'),
(7, 19, 'selasa', '12', 's', '13 c'),
(8, 22, 'rabu ', '9:30', 'bahasa padang', 'b'),
(9, 22, 'rabu ', '9:30', 'bahasa padang', 'b'),
(10, 22, 'rabu ', '9:30', 'bahasa padang', 'b'),
(11, 22, 'd', '12', 'bahasa isyarat', 'a'),
(12, 22, 'j', 'b', 'hb', '9'),
(13, 22, 'j', 'b', 'hb', '9'),
(14, 19, 'd', 'x', 'bahasa isyarat', '13 c'),
(15, 19, 'senen', '12', 's', '12 c'),
(16, 19, 'senen', '12', 's', '12 c'),
(30, 19, 'senen', '12', 'bahasa isyarat', '12'),
(31, 19, 'senen', '12', 'bahasa isyarat', '12'),
(32, 19, 'senen', '12', 'bahasa isyarat', '12'),
(33, 19, 'senen', '12', 'bahasa isyarat', '12'),
(34, 19, 'senen', '12', 'bahasa isyarat', '12');

-- --------------------------------------------------------

--
-- Table structure for table `murid`
--

CREATE TABLE `murid` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_kartu` varchar(50) CHARACTER SET utf8 NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8 NOT NULL,
  `kelas` varchar(50) CHARACTER SET utf8 NOT NULL,
  `nis` varchar(20) CHARACTER SET utf8 NOT NULL,
  `role` enum('guru','siswa','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `murid`
--

INSERT INTO `murid` (`id`, `id_kartu`, `nama`, `kelas`, `nis`, `role`) VALUES
(14, '1289189794', 'ADIL FARAKHAN', '12 A', '434342324355', 'siswa'),
(17, '1191209921', 'ILHAM LIDINILAH', '12 B', '3535353656545654', 'siswa'),
(18, '1289443282', 'Silvina setiani ', '12 A', '98729876986398', 'siswa'),
(19, '1289261090', 'ANNISA', '12 B', '7986969869868', 'siswa'),
(21, '1191531473', 'Adil Sektor 7', '12 A', '0987654321', 'siswa'),
(22, '1305219122', 'Haura Pengkor', '12 A', '0987634567876', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_kartu` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `role` enum('guru','siswa','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_kartu`, `nama`, `jabatan`, `role`) VALUES
(19, '1289053986', 'ZAKY PUTRANTO', 'GURU 12 A', 'guru'),
(20, '1305191330', 'WILLY ADREAN', 'GURU 12 B', 'guru'),
(21, '1289087250', 'PRATAMA', 'GURU 12 C', 'guru'),
(22, '1289092002', 'AKMAL BOBER', 'GURU 14 Z', 'guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi_log`
--
ALTER TABLE `absensi_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indexes for table `murid`
--
ALTER TABLE `murid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_kartu` (`id_kartu`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_kartu` (`id_kartu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_log`
--
ALTER TABLE `absensi_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `murid`
--
ALTER TABLE `murid`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
