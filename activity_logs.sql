-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Jan 2025 pada 09.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartrecruit`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` enum('resume','cover_letter','interview','translation') NOT NULL,
  `activity_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `activity_type`, `activity_id`, `action`, `details`, `created_at`) VALUES
(1, 1, 'cover_letter', 1, 'create', 'Cover letter untuk posisi Graphic Designer di PT Layanan Berbasis Awan telah dibuat', '2025-01-12 10:24:21'),
(2, 1, 'cover_letter', 2, 'create', 'Cover letter untuk posisi Graphic Designer di PT Layanan Berbasis Awan telah dibuat', '2025-01-12 10:27:23'),
(3, 1, 'cover_letter', 3, 'create', 'Cover letter untuk posisi GRAPHIC DESIGNER di PT LAYANAN BERBASIS AWAN telah dibuat', '2025-01-12 10:28:37'),
(4, 1, 'cover_letter', 4, 'create', 'Cover letter untuk posisi GRAPHIC DESIGNER di PT LAYANAN BERBASIS AWAN telah dibuat', '2025-01-12 10:30:02'),
(5, 1, 'cover_letter', 5, 'create', 'Cover letter untuk posisi Desain Grafis di Google telah dibuat', '2025-01-12 10:30:44'),
(6, 1, 'cover_letter', 6, 'create', 'Cover letter untuk posisi Pelayan di Warkop Bening telah dibuat', '2025-01-12 10:54:59'),
(7, 1, 'cover_letter', 7, 'create', 'Cover letter untuk posisi graphic desain di tes telah dibuat', '2025-01-12 12:24:49'),
(8, 1, 'cover_letter', 8, 'create', 'Cover letter untuk posisi hr di google telah dibuat', '2025-01-12 12:30:50'),
(9, 1, 'cover_letter', 9, 'create', 'Cover letter untuk posisi ui ux di tes telah dibuat', '2025-01-12 12:32:34'),
(10, 1, 'cover_letter', 10, 'create', 'Cover letter untuk posisi UI/UX di google telah dibuat', '2025-01-12 12:33:36'),
(11, 1, 'cover_letter', 11, 'create', 'Cover letter untuk posisi ui di google telah dibuat', '2025-01-12 12:35:09'),
(12, 1, 'cover_letter', 12, 'create', 'Cover letter untuk posisi UI/UX di TES telah dibuat', '2025-01-12 12:50:54'),
(13, 1, 'cover_letter', 13, 'create', 'Cover letter untuk posisi UI/UX di Warkop Bening telah dibuat', '2025-01-12 12:54:37'),
(14, 1, 'cover_letter', 11, 'delete', 'Cover letter telah dihapus', '2025-01-12 12:56:48'),
(15, 1, 'cover_letter', 2, 'delete', 'Cover letter telah dihapus', '2025-01-12 12:56:54'),
(16, 1, 'cover_letter', 13, 'delete', 'Cover letter telah dihapus', '2025-01-12 12:57:13'),
(17, 1, 'cover_letter', 12, 'delete', 'Cover letter telah dihapus', '2025-01-12 12:57:17'),
(18, 1, 'cover_letter', 14, 'create', 'Cover letter untuk posisi UI/UX di Warkop Bening telah dibuat', '2025-01-12 13:37:37'),
(19, 1, 'cover_letter', 14, 'delete', 'Cover letter telah dihapus', '2025-01-12 13:45:05'),
(20, 1, 'cover_letter', 15, 'create', 'Cover letter untuk posisi UI UX di KOSAN telah dibuat', '2025-01-12 13:45:33'),
(21, 1, 'cover_letter', 16, 'create', 'Cover letter untuk posisi UI UX di Tes guys telah dibuat', '2025-01-13 00:21:34'),
(22, 1, 'cover_letter', 16, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:15'),
(23, 1, 'cover_letter', 15, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:21'),
(24, 1, 'cover_letter', 10, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:26'),
(25, 1, 'cover_letter', 9, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:30'),
(26, 1, 'cover_letter', 8, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:34'),
(27, 1, 'cover_letter', 7, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:24:38'),
(28, 1, 'cover_letter', 17, 'create', 'Cover letter untuk posisi IT Enginering di Lenovo telah dibuat', '2025-01-13 00:34:05'),
(29, 1, 'cover_letter', 6, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:34:40'),
(30, 1, 'cover_letter', 18, 'create', 'Cover letter untuk posisi IT Enginering di Lenovo telah dibuat', '2025-01-13 00:35:07'),
(31, 1, 'cover_letter', 3, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:37:58'),
(32, 1, 'cover_letter', 1, 'delete', 'Cover letter telah dihapus', '2025-01-13 00:43:25'),
(33, 1, 'cover_letter', 19, 'create', 'Cover letter untuk posisi Pelayan di google telah dibuat', '2025-01-13 05:51:00'),
(34, 1, 'cover_letter', 20, 'create', 'Cover letter untuk posisi Pelayan di google telah dibuat', '2025-01-13 05:51:53'),
(35, 1, 'cover_letter', 21, 'create', 'Cover letter untuk posisi Pelayan di google telah dibuat', '2025-01-13 05:52:04'),
(36, 1, 'cover_letter', 22, 'create', 'Cover letter untuk posisi Pelayan di google telah dibuat', '2025-01-13 05:52:13'),
(37, 1, 'cover_letter', 23, 'create', 'Cover letter untuk posisi cek di cek telah dibuat', '2025-01-13 06:02:16'),
(38, 2, 'cover_letter', 24, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:04:40'),
(39, 2, 'cover_letter', 25, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:04:57'),
(40, 2, 'cover_letter', 26, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:05:06'),
(41, 2, 'cover_letter', 27, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:05:17'),
(42, 2, 'cover_letter', 28, 'create', 'Cover letter untuk posisi cek di cek telah dibuat', '2025-01-13 06:06:30'),
(43, 2, 'cover_letter', 29, 'create', 'Cover letter untuk posisi cek di cek telah dibuat', '2025-01-13 06:10:11'),
(44, 2, 'cover_letter', 30, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:10:32'),
(45, 2, 'cover_letter', 31, 'create', 'Cover letter untuk posisi graphic desain di Warkop Bening telah dibuat', '2025-01-13 06:11:10'),
(46, 2, 'cover_letter', 32, 'create', 'Cover letter untuk posisi tes di tes telah dibuat', '2025-01-13 06:14:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE IF NOT EXISTS `translation_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `source_language` varchar(10) NOT NULL,
  `target_language` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `translation_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
