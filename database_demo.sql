-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Jul 2026 pada 12.23
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
-- Database: `keuangan_tpq`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `iuran`
--

CREATE TABLE `iuran` (
  `id_iuran` int(11) NOT NULL,
  `nama_iuran` varchar(100) DEFAULT NULL,
  `tahun` int(4) NOT NULL,
  `periode_tipe` enum('bulanan','tahunan') NOT NULL DEFAULT 'bulanan',
  `nominal` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `iuran`
--

INSERT INTO `iuran` (`id_iuran`, `nama_iuran`, `tahun`, `periode_tipe`, `nominal`, `keterangan`, `created_at`) VALUES
(1, 'SPP', 2026, 'bulanan', 35000.00, '', '2026-02-03 05:17:30'),
(2, 'Daftar Ulang', 2026, 'tahunan', 120000.00, '', '2026-03-27 13:42:11'),
(5, 'Ekskul', 2026, 'bulanan', 5000.00, '', '2026-03-28 05:14:14'),
(6, 'POMG', 2026, 'tahunan', 40000.00, '', '2026-03-28 05:14:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_umum`
--

CREATE TABLE `jurnal_umum` (
  `id_jurnal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_pos` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jurnal_umum`
--

INSERT INTO `jurnal_umum` (`id_jurnal`, `tanggal`, `id_pos`, `uraian`, `jenis`, `nominal`, `created_at`, `updated_at`) VALUES
(5, '2026-04-01', 17, 'Saldo bk prestasi dan bk tabungan', 'Pemasukan', 1108000.00, '2026-04-01 08:51:53', '2026-04-01 08:51:53'),
(6, '2026-04-01', 13, 'saldo akhir', 'Pemasukan', 4465725.00, '2026-04-01 08:53:07', '2026-04-01 08:53:07'),
(8, '2026-04-01', 15, 'saldo akhir', 'Pemasukan', 1568100.00, '2026-04-01 08:54:04', '2026-04-01 08:54:04'),
(9, '2026-04-01', 16, 'saldo akhir', 'Pemasukan', 391000.00, '2026-04-01 08:54:30', '2026-04-01 08:54:30'),
(10, '2026-04-01', 14, 'saldo akhir', 'Pemasukan', 985000.00, '2026-04-01 08:55:09', '2026-04-01 08:55:09'),
(11, '2026-04-01', 19, 'saldo akhir', 'Pemasukan', 1042000.00, '2026-04-01 08:55:33', '2026-04-01 08:55:33'),
(12, '2026-04-01', 18, 'saldo akhir', 'Pemasukan', 363000.00, '2026-04-01 08:55:51', '2026-04-01 08:55:51'),
(13, '2026-04-01', 12, 'saldo akhir', 'Pemasukan', 875000.00, '2026-04-01 08:56:58', '2026-04-01 08:56:58'),
(14, '2026-04-03', 18, 'Sumbangan ke TPQ cilengsi', 'Pengeluaran', 50000.00, '2026-04-03 12:02:26', '2026-04-03 12:18:09'),
(15, '2026-04-03', 16, 'Sumbangan ke TPQ cilengsi', 'Pengeluaran', 50000.00, '2026-04-03 12:03:21', '2026-04-03 12:17:39'),
(16, '2026-04-13', 15, 'Bulanan', 'Pemasukan', 170000.00, '2026-04-13 12:53:17', '2026-04-13 12:53:17'),
(17, '2026-04-13', 15, 'Pulsa guru', 'Pengeluaran', 100000.00, '2026-04-13 12:53:45', '2026-04-13 12:53:45'),
(18, '2026-04-13', 14, 'Pulsa guru', 'Pengeluaran', 75000.00, '2026-04-13 12:54:13', '2026-04-13 12:54:13'),
(19, '2026-04-20', 12, 'Spp maret', 'Pemasukan', 1095000.00, '2026-04-20 11:43:06', '2026-04-20 11:43:06'),
(20, '2026-04-20', 12, 'Honor guru', 'Pengeluaran', 1230000.00, '2026-04-20 11:43:37', '2026-04-20 11:43:37'),
(21, '2026-04-27', 13, 'Air minum', 'Pengeluaran', 15000.00, '2026-04-27 13:26:01', '2026-04-27 13:26:01'),
(22, '2026-05-06', 2, 'Saldo akhir', 'Pemasukan', 7398290.00, '2026-05-06 13:07:25', '2026-05-06 13:07:25'),
(23, '2026-05-06', 18, 'Dari igtka', 'Pemasukan', 50000.00, '2026-05-06 13:12:22', '2026-05-06 13:12:22'),
(24, '2026-05-06', 18, 'Jenguk cucu b wawang', 'Pengeluaran', 124000.00, '2026-05-06 13:12:55', '2026-05-06 13:12:55'),
(25, '2026-05-06', 12, 'Pemasukan bln april', 'Pemasukan', 2130000.00, '2026-05-06 13:16:48', '2026-05-06 13:16:48'),
(26, '2026-05-06', 12, 'Honor guru', 'Pengeluaran', 1710000.00, '2026-05-06 13:17:25', '2026-05-06 13:17:25'),
(27, '2026-05-06', 17, 'B harti', 'Pemasukan', 7000.00, '2026-05-06 13:22:10', '2026-05-06 13:22:10'),
(28, '2026-05-11', 15, 'Ekskul bln April 2026', 'Pemasukan', 305000.00, '2026-05-11 06:03:39', '2026-05-11 06:03:39'),
(29, '2026-05-11', 15, 'Pulsa guru', 'Pengeluaran', 100000.00, '2026-05-11 06:04:54', '2026-05-11 06:04:54'),
(30, '2026-05-11', 14, 'Pulsa guru', 'Pengeluaran', 75000.00, '2026-05-11 06:05:20', '2026-05-11 06:05:20'),
(32, '2026-05-13', 17, 'Bk prestasi b erna', 'Pemasukan', 7000.00, '2026-05-13 09:35:25', '2026-05-13 09:35:25'),
(33, '2026-05-13', 16, 'Infak jumat', 'Pemasukan', 120000.00, '2026-05-13 09:37:38', '2026-05-13 09:37:38'),
(34, '2026-05-13', 19, 'Rapot fahri', 'Pemasukan', 50000.00, '2026-05-13 09:38:59', '2026-05-13 09:38:59'),
(35, '2026-05-29', 16, 'Infak jumat', 'Pemasukan', 68500.00, '2026-05-29 14:59:48', '2026-05-29 14:59:48'),
(36, '2026-05-31', 12, 'Spp santri', 'Pemasukan', 1185000.00, '2026-05-31 06:18:48', '2026-05-31 06:18:48'),
(38, '2026-06-03', 12, 'Honor guru', 'Pengeluaran', 1526000.00, '2026-06-03 14:19:03', '2026-06-03 14:19:03'),
(39, '2026-06-03', 15, 'Pulsa guru', 'Pengeluaran', 100000.00, '2026-06-03 14:25:55', '2026-06-03 14:25:55'),
(40, '2026-06-03', 15, 'Ekskul bln mei', 'Pemasukan', 170000.00, '2026-06-03 14:26:48', '2026-06-03 14:26:48'),
(41, '2026-06-03', 14, 'Pulsa guru', 'Pengeluaran', 75000.00, '2026-06-03 14:27:23', '2026-06-03 14:27:23'),
(42, '2026-06-12', 16, 'Infak jumat', 'Pemasukan', 61500.00, '2026-06-12 09:37:07', '2026-06-12 09:37:07'),
(43, '2026-06-17', 19, 'Rapot Anastasya', 'Pemasukan', 50000.00, '2026-06-17 10:13:39', '2026-06-17 10:13:39'),
(44, '2026-06-17', 19, 'Ijazah rayhand', 'Pemasukan', 70000.00, '2026-06-17 10:14:13', '2026-06-17 10:14:13'),
(45, '2026-06-17', 19, 'Ijazah meica', 'Pemasukan', 70000.00, '2026-06-17 10:14:39', '2026-06-17 10:14:39'),
(46, '2026-06-17', 19, 'Ijazah ridho', 'Pemasukan', 70000.00, '2026-06-17 10:15:01', '2026-06-17 10:15:01'),
(47, '2026-06-17', 19, 'Ijazah syasya', 'Pemasukan', 70000.00, '2026-06-17 10:15:25', '2026-06-17 10:15:25'),
(48, '2026-06-18', 19, 'Rapot Qonita', 'Pemasukan', 50000.00, '2026-06-18 10:00:18', '2026-06-18 10:00:18'),
(49, '2026-06-19', 16, 'Infak jumat', 'Pemasukan', 31000.00, '2026-06-19 09:35:48', '2026-06-19 09:35:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel data kelas untuk santri';

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `created_at`) VALUES
(19, 'A1', '2026-02-14 00:21:20'),
(20, 'A2', '2026-02-14 00:21:20'),
(21, 'B1', '2026-02-14 00:21:20'),
(22, 'B2', '2026-02-14 00:21:20'),
(23, 'C', '2026-02-14 00:21:20'),
(24, 'D', '2026-02-14 00:21:20'),
(25, 'E', '2026-02-14 00:21:20'),
(26, 'F', '2026-02-14 00:21:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `tabel` varchar(50) DEFAULT NULL,
  `data_id` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `tabel`, `data_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Logout dari sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 03:33:15'),
(2, 1, 'Login ke sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 03:33:17'),
(3, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:28:23'),
(4, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:28:29'),
(5, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:28:30'),
(6, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:28:35'),
(7, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:29:59'),
(8, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:30:00'),
(9, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:30:01'),
(10, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:30:04'),
(11, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:32:16'),
(12, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:32:17'),
(13, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:32:18'),
(14, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:32:22'),
(15, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:18'),
(16, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:20'),
(17, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:20'),
(18, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:24'),
(19, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:36'),
(20, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:40'),
(21, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:40'),
(22, 1, 'Mengubah profil', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:33:44'),
(23, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 04:38:34'),
(24, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:45:09'),
(25, 1, 'Menghapus data guru', 'guru', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 04:47:03'),
(26, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:52:47'),
(27, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:52:49'),
(28, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:52:57'),
(29, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:53:22'),
(30, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:53:23'),
(31, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:54:18'),
(32, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:54:19'),
(33, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:29'),
(34, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:31'),
(35, 1, 'Menyimpan data guru (diubah)', 'guru', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:35'),
(36, 1, 'Menghapus data guru', 'guru', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:40'),
(37, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:56'),
(38, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:55:57'),
(39, 1, 'Menyimpan data guru (diubah)', 'guru', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:56:04'),
(40, 1, 'Menghapus data guru', 'guru', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:56:08'),
(41, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 04:56:12'),
(42, 1, 'Menyimpan data guru (ditambahkan)', 'guru', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 04:57:07'),
(43, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:14:04'),
(44, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:15:13'),
(45, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:16:10'),
(46, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:16:53'),
(47, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:17:29'),
(48, 1, 'Menyimpan data SPP (ditambahkan)', 'spp', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:17:30'),
(49, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:17:54'),
(50, 1, 'Menyimpan data kelas (ditambahkan)', 'kelas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:17:55'),
(51, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:17:57'),
(52, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:18:23'),
(53, 1, 'Menambah data santri baru', 'santri', '1770095904', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:18:24'),
(54, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:19:32'),
(55, 1, 'Menambah data santri baru', 'santri', '1770095975', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:19:35'),
(56, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:20:22'),
(57, 1, 'Menambah data santri baru', 'santri', '1770096022', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:20:23'),
(58, 1, 'Menyimpan data kelas (ditambahkan)', 'kelas', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:21:09'),
(59, 1, 'Mengubah pengaturan payment gateway', 'settings', 'payment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:24:23'),
(60, 1, 'Mengubah pengaturan payment gateway', 'settings', 'payment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:24:50'),
(61, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:26:01'),
(62, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:27:49'),
(63, 1, 'Input pembayaran SPP', 'pembayaran', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:28:35'),
(64, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:28:49'),
(65, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:30:34'),
(66, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:30:42'),
(67, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 05:31:33'),
(68, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:43:40'),
(69, 1, 'Menambah data santri baru', 'santri', '1770097420', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 05:43:42'),
(70, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 09:09:28'),
(71, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 09:43:49'),
(72, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 09:45:27'),
(73, 1, 'Menambah data santri baru', 'santri', '1770111928', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 09:45:30'),
(74, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 10:01:20'),
(75, 1, 'Menambah data santri baru', 'santri', '1770112882', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 10:01:23'),
(76, 1, 'Menambah data santri baru', 'santri', '34324', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 10:09:34'),
(77, 1, 'Menyimpan data kelas (ditambahkan)', 'kelas', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 10:44:25'),
(78, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:00:32'),
(79, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:01:39'),
(80, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:03:42'),
(81, 1, 'Input pembayaran SPP', 'pembayaran', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:03:43'),
(82, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:05:19'),
(83, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:06:55'),
(84, 1, 'Menambah data santri baru', 'santri', '1770120416', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:06:57'),
(85, 1, 'Input pembayaran SPP', 'pembayaran', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:06:59'),
(86, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:07:38'),
(87, 1, 'Menambah data santri baru', 'santri', '1770120459', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:07:39'),
(88, 1, 'Input pembayaran SPP', 'pembayaran', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:07:41'),
(89, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:08:21'),
(90, 1, 'Menambah data santri baru', 'santri', '1770120502', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:08:22'),
(91, 1, 'Input pembayaran SPP', 'pembayaran', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:08:23'),
(92, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:09:21'),
(93, 1, 'Menambah data santri baru', 'santri', '1770120561', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:09:22'),
(94, 1, 'Input pembayaran SPP', 'pembayaran', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:09:23'),
(95, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:10:30'),
(96, 1, 'Menambah data santri baru', 'santri', '1770120631', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:10:31'),
(97, 1, 'Input pembayaran SPP', 'pembayaran', '7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:10:33'),
(98, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:12:29'),
(99, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:12:34'),
(100, 1, 'Menambah data santri baru', 'santri', '1770120755', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:12:35'),
(101, 1, 'Input pembayaran SPP', 'pembayaran', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.7632.6 Safari/537.36', '2026-02-03 12:12:36'),
(102, 1, 'Input pembayaran SPP', 'pembayaran', '9', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:16:42'),
(103, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:35:50'),
(104, 1, 'Menyimpan data guru (diubah)', 'guru', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:36:54'),
(105, 1, 'Logout dari sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:36:58'),
(106, 8, 'Login ke sistem', 'guru', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:37:01'),
(107, 8, 'Input pembayaran SPP', 'pembayaran', '10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:37:17'),
(108, 8, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:38:25'),
(109, 8, 'Logout dari sistem', 'guru', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:39:04'),
(110, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:39:09'),
(111, 1, 'Mengubah pengaturan umum', 'settings', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:43:15'),
(112, 1, 'Mengubah pengaturan umum', 'settings', 'general', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:44:34'),
(113, 1, 'Mengubah pengaturan payment gateway', 'settings', 'payment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:45:22'),
(114, 1, 'Mengubah pengaturan payment gateway', 'settings', 'payment', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-03 12:45:40'),
(115, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-05 00:23:42'),
(116, 1, 'Export laporan pembayaran', 'laporan', 'Februari 2026', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-02-05 00:23:52'),
(117, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 06:29:04'),
(118, 1, 'Menyimpan data Pos Keuangan (ditambahkan)', 'pos_keuangan', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 06:31:23'),
(119, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 06:32:03'),
(120, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 06:36:38'),
(121, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 06:42:07'),
(122, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 06:42:42'),
(123, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 07:08:40'),
(124, 1, 'Menambah data santri baru', 'santri', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-27 11:53:21'),
(125, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:35:45'),
(126, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:38:35'),
(127, 1, 'Menyimpan kategori biaya (ditambahkan)', 'biaya', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:42:11'),
(128, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:42:19'),
(129, 1, 'Menyimpan kategori biaya (ditambahkan)', 'biaya', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:42:37'),
(130, 1, 'Menyimpan kategori biaya (ditambahkan)', 'biaya', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:42:53'),
(131, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:43:01'),
(132, 1, 'Mengubah data santri ID: 1', 'santri', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:43:14'),
(133, 1, 'Mengubah data santri ID: 1', 'santri', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:43:20'),
(134, 1, 'Menghapus transaksi pembayaran batch', 'pembayaran_transaksi', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:43:26'),
(135, 1, 'Menghapus kategori biaya', 'biaya', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:45:31'),
(136, 1, 'Menghapus kategori biaya', 'biaya', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:45:35'),
(137, 1, 'Menghapus data santri', 'santri', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:45:55'),
(138, 1, 'Menghapus data santri', 'santri', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:45:56'),
(139, 1, 'Menambah data santri baru', 'santri', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:55:43'),
(140, 1, 'Mengubah data santri ID: 2', 'santri', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 13:57:10'),
(141, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-27 14:04:09'),
(142, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-28 01:33:59'),
(143, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 01:38:07'),
(144, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-28 01:43:08'),
(145, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:32:16'),
(146, 1, 'Menghapus transaksi pembayaran batch', 'pembayaran_transaksi', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:34:21'),
(147, 1, 'Menghapus transaksi pembayaran batch', 'pembayaran_transaksi', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:38:05'),
(148, 1, 'Menghapus data santri', 'santri', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:38:11'),
(149, 1, 'Menambah data santri baru', 'santri', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:49:54'),
(150, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:50:14'),
(151, 1, 'Mengubah data santri ID: 3', 'santri', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 04:57:06'),
(152, 1, 'Mengubah data santri ID: 3', 'santri', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:10:20'),
(153, 1, 'Menghapus transaksi pembayaran batch', 'pembayaran_transaksi', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:10:29'),
(154, 1, 'Logout dari sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:13:16'),
(155, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:13:34'),
(156, 1, 'Menyimpan kategori biaya (ditambahkan)', 'biaya', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:14:14'),
(157, 1, 'Menyimpan kategori biaya (ditambahkan)', 'biaya', '6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:14:27'),
(158, 1, 'Mengubah data santri ID: 3', 'santri', '3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 05:14:38'),
(159, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-29 21:34:18'),
(160, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '2026-03-29 21:37:09'),
(161, 1, 'Logout dari sistem', 'guru', '1', '::1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1', '2026-03-29 21:37:16'),
(162, 1, 'Login ke sistem', 'guru', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 21:37:31'),
(163, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 21:37:56'),
(164, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 21:51:33'),
(165, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 22:04:02'),
(166, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 22:04:08'),
(167, 1, 'Menyimpan kategori biaya (diubah)', 'biaya', '2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-29 22:04:16'),
(168, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 03:15:57'),
(169, NULL, 'Menambah data santri baru', 'santri', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 05:16:09'),
(170, NULL, 'Menambah data santri baru', 'santri', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 05:20:50'),
(171, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 05:21:28'),
(172, 1, 'Menghapus transaksi pembayaran batch', 'pembayaran_transaksi', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 05:28:57'),
(173, 1, 'Input pembayaran batch', 'pembayaran_transaksi', '6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-30 05:29:13'),
(174, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-03-31 23:42:08'),
(175, 1, 'Menghapus data Jurnal Umum', 'jurnal_umum', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:07:30'),
(176, 1, 'Input pembayaran', 'pembayaran', '19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:46:59'),
(177, 1, 'Menghapus data pembayaran', 'pembayaran', '19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:47:06'),
(178, 1, 'Mengubah data santri ID: 3', 'santri', '3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:53:30'),
(179, 1, 'Pembayaran kolektif', 'pembayaran', '20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:54:33'),
(180, 1, 'Menghapus data pembayaran', 'pembayaran', '20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 00:54:51'),
(181, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:42:45'),
(182, 1, 'Mengubah data santri ID: 4', 'santri', '4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:43:35'),
(183, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:51:53'),
(184, 1, 'Menyimpan data Jurnal Umum (diubah)', 'jurnal_umum', '5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:52:08'),
(185, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:53:07'),
(186, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:53:29'),
(187, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:54:04'),
(188, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:54:30'),
(189, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:55:09'),
(190, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '11', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:55:33'),
(191, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:55:51'),
(192, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 08:56:58'),
(193, 1, 'Pembayaran kolektif', 'pembayaran', '21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(194, 1, 'Pembayaran kolektif', 'pembayaran', '22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(195, 1, 'Pembayaran kolektif', 'pembayaran', '23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(196, 1, 'Pembayaran kolektif', 'pembayaran', '24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(197, 1, 'Pembayaran kolektif', 'pembayaran', '25', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(198, 1, 'Pembayaran kolektif', 'pembayaran', '26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(199, 1, 'Pembayaran kolektif', 'pembayaran', '27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(200, 1, 'Pembayaran kolektif', 'pembayaran', '28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(201, 1, 'Pembayaran kolektif', 'pembayaran', '29', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(202, 1, 'Pembayaran kolektif', 'pembayaran', '30', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(203, 1, 'Pembayaran kolektif', 'pembayaran', '31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(204, 1, 'Pembayaran kolektif', 'pembayaran', '32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(205, 1, 'Pembayaran kolektif', 'pembayaran', '33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(206, 1, 'Pembayaran kolektif', 'pembayaran', '34', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:05:59'),
(207, 1, 'Logout dari sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:07:13'),
(208, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 09:08:47'),
(209, 1, 'Mengubah data santri ID: 2', 'santri', '2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 10:26:27'),
(210, 1, 'Pembayaran kolektif', 'pembayaran', '35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:03:50'),
(211, 1, 'Pembayaran kolektif', 'pembayaran', '36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:03:50'),
(212, 1, 'Menghapus data pembayaran', 'pembayaran', '36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:04:09'),
(213, 1, 'Menghapus data pembayaran', 'pembayaran', '35', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:04:11'),
(214, 1, 'Pembayaran kolektif', 'pembayaran', '37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:04:45'),
(215, 1, 'Pembayaran kolektif', 'pembayaran', '38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 11:04:45'),
(216, 1, 'Login ke sistem', 'guru', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 12:18:11'),
(217, 1, 'Menghapus data pembayaran', 'pembayaran', '38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 12:18:19'),
(218, 1, 'Menghapus data pembayaran', 'pembayaran', '37', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-01 12:18:21'),
(219, 1, 'Login ke sistem', 'guru', '1', '180.252.171.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-01 13:29:36'),
(220, 1, 'Logout dari sistem', 'guru', '1', '180.252.171.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-01 13:39:27'),
(221, 1, 'Login ke sistem', 'guru', '1', '180.252.171.119', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 11:51:42'),
(222, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '14', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 12:02:26'),
(223, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '15', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 12:03:21'),
(224, 1, 'Menyimpan data Jurnal Umum (diubah)', 'jurnal_umum', '15', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 12:17:39'),
(225, 1, 'Menyimpan data Jurnal Umum (diubah)', 'jurnal_umum', '14', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 12:18:09'),
(226, 1, 'Pembayaran kolektif', 'pembayaran', '39', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 13:04:06'),
(227, 1, 'Pembayaran kolektif', 'pembayaran', '40', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 13:04:06'),
(228, 1, 'Pembayaran kolektif', 'pembayaran', '41', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 13:06:57'),
(229, 1, 'Pembayaran kolektif', 'pembayaran', '42', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-03 13:06:57'),
(230, 1, 'Login ke sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-05 08:26:46'),
(231, 1, 'Logout dari sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-05 08:32:35'),
(232, 1, 'Login ke sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:149.0) Gecko/20100101 Firefox/149.0', '2026-04-05 08:33:30'),
(233, 1, 'Login ke sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-05 14:18:06'),
(234, 1, 'Login ke sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:31:17'),
(235, 1, 'Pembayaran kolektif', 'pembayaran', '43', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(236, 1, 'Pembayaran kolektif', 'pembayaran', '44', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(237, 1, 'Pembayaran kolektif', 'pembayaran', '45', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(238, 1, 'Pembayaran kolektif', 'pembayaran', '46', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(239, 1, 'Pembayaran kolektif', 'pembayaran', '47', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(240, 1, 'Pembayaran kolektif', 'pembayaran', '48', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(241, 1, 'Pembayaran kolektif', 'pembayaran', '49', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(242, 1, 'Pembayaran kolektif', 'pembayaran', '50', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:33:13'),
(243, 1, 'Pembayaran kolektif', 'pembayaran', '51', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:34:12'),
(244, 1, 'Pembayaran kolektif', 'pembayaran', '52', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:34:12'),
(245, 1, 'Pembayaran kolektif', 'pembayaran', '53', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:34:12'),
(246, 1, 'Pembayaran kolektif', 'pembayaran', '54', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:34:12'),
(247, 1, 'Pembayaran kolektif', 'pembayaran', '55', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:35:50'),
(248, 1, 'Pembayaran kolektif', 'pembayaran', '56', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 05:35:50'),
(249, 1, 'Login ke sistem', 'guru', '1', '180.252.161.65', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-08 23:31:18'),
(250, 1, 'Login ke sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-04-10 02:08:18'),
(251, 1, 'Login ke sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:36:24'),
(252, 1, 'Pembayaran kolektif', 'pembayaran', '57', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:47:53'),
(253, 1, 'Pembayaran kolektif', 'pembayaran', '58', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:47:53'),
(254, 1, 'Logout dari sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:49:40'),
(255, 1, 'Login ke sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:49:42'),
(256, 1, 'Backup database', 'backup', 'backup_2026-04-11_155258.sql', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:52:59'),
(257, 1, 'Logout dari sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:55:10'),
(258, 1, 'Login ke sistem', 'guru', '1', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-11 08:55:51'),
(259, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '16', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-13 12:53:17'),
(260, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '17', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-13 12:53:45'),
(261, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '18', '180.252.162.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-13 12:54:13'),
(262, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '19', '180.252.170.161', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-20 11:43:06'),
(263, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '20', '180.252.170.161', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-20 11:43:37'),
(264, 1, 'Login ke sistem', 'guru', '1', '182.6.2.27', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 09:39:44');
INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `tabel`, `data_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(265, 1, 'Pembayaran kolektif', 'pembayaran', '59', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:37:52'),
(266, 1, 'Pembayaran kolektif', 'pembayaran', '60', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:37:52'),
(267, 1, 'Pembayaran kolektif', 'pembayaran', '61', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(268, 1, 'Pembayaran kolektif', 'pembayaran', '62', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(269, 1, 'Pembayaran kolektif', 'pembayaran', '63', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(270, 1, 'Pembayaran kolektif', 'pembayaran', '64', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(271, 1, 'Pembayaran kolektif', 'pembayaran', '65', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(272, 1, 'Pembayaran kolektif', 'pembayaran', '66', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(273, 1, 'Pembayaran kolektif', 'pembayaran', '67', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(274, 1, 'Pembayaran kolektif', 'pembayaran', '68', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(275, 1, 'Pembayaran kolektif', 'pembayaran', '69', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(276, 1, 'Pembayaran kolektif', 'pembayaran', '70', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(277, 1, 'Pembayaran kolektif', 'pembayaran', '71', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(278, 1, 'Pembayaran kolektif', 'pembayaran', '72', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(279, 1, 'Pembayaran kolektif', 'pembayaran', '73', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(280, 1, 'Pembayaran kolektif', 'pembayaran', '74', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:43:55'),
(281, 1, 'Pembayaran kolektif', 'pembayaran', '75', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:44:43'),
(282, 1, 'Pembayaran kolektif', 'pembayaran', '76', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:44:43'),
(283, 1, 'Pembayaran kolektif', 'pembayaran', '77', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:48:59'),
(284, 1, 'Pembayaran kolektif', 'pembayaran', '78', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:48:59'),
(285, 1, 'Pembayaran kolektif', 'pembayaran', '79', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:48:59'),
(286, 1, 'Pembayaran kolektif', 'pembayaran', '80', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:48:59'),
(287, 1, 'Menghapus data pembayaran', 'pembayaran', '76', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:52:14'),
(288, 1, 'Input pembayaran', 'pembayaran', '84', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 12:56:49'),
(289, 1, 'Menghapus data pembayaran', 'pembayaran', '84', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:04:48'),
(290, 1, 'Menghapus data pembayaran', 'pembayaran', '83', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:05:15'),
(291, 1, 'Menghapus data pembayaran', 'pembayaran', '82', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:05:24'),
(292, 1, 'Pembayaran kolektif', 'pembayaran', '85', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:11:08'),
(293, 1, 'Pembayaran kolektif', 'pembayaran', '86', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:11:08'),
(294, 1, 'Pembayaran kolektif', 'pembayaran', '87', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(295, 1, 'Pembayaran kolektif', 'pembayaran', '88', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(296, 1, 'Pembayaran kolektif', 'pembayaran', '89', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(297, 1, 'Pembayaran kolektif', 'pembayaran', '90', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(298, 1, 'Pembayaran kolektif', 'pembayaran', '91', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(299, 1, 'Pembayaran kolektif', 'pembayaran', '92', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(300, 1, 'Pembayaran kolektif', 'pembayaran', '93', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(301, 1, 'Pembayaran kolektif', 'pembayaran', '94', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(302, 1, 'Pembayaran kolektif', 'pembayaran', '95', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(303, 1, 'Pembayaran kolektif', 'pembayaran', '96', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:13:58'),
(304, 1, 'Pembayaran kolektif', 'pembayaran', '97', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(305, 1, 'Pembayaran kolektif', 'pembayaran', '98', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(306, 1, 'Pembayaran kolektif', 'pembayaran', '99', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(307, 1, 'Pembayaran kolektif', 'pembayaran', '100', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(308, 1, 'Pembayaran kolektif', 'pembayaran', '101', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(309, 1, 'Pembayaran kolektif', 'pembayaran', '102', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(310, 1, 'Pembayaran kolektif', 'pembayaran', '103', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(311, 1, 'Pembayaran kolektif', 'pembayaran', '104', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:15:32'),
(312, 1, 'Pembayaran kolektif', 'pembayaran', '105', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:17:09'),
(313, 1, 'Pembayaran kolektif', 'pembayaran', '106', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:17:09'),
(314, 1, 'Pembayaran kolektif', 'pembayaran', '107', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:30:10'),
(315, 1, 'Pembayaran kolektif', 'pembayaran', '108', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:30:10'),
(316, 1, 'Pembayaran kolektif', 'pembayaran', '109', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:36:52'),
(317, 1, 'Pembayaran kolektif', 'pembayaran', '110', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:36:52'),
(318, 1, 'Pembayaran kolektif', 'pembayaran', '111', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:36:52'),
(319, 1, 'Pembayaran kolektif', 'pembayaran', '112', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:36:52'),
(320, 1, 'Pembayaran kolektif', 'pembayaran', '113', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:37:18'),
(321, 1, 'Pembayaran kolektif', 'pembayaran', '114', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:37:18'),
(322, 1, 'Pembayaran kolektif', 'pembayaran', '115', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:44:32'),
(323, 1, 'Pembayaran kolektif', 'pembayaran', '116', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-24 13:44:32'),
(324, 1, 'Pembayaran kolektif', 'pembayaran', '117', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:38:09'),
(325, 1, 'Pembayaran kolektif', 'pembayaran', '118', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:38:09'),
(326, 1, 'Pembayaran kolektif', 'pembayaran', '119', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:38:54'),
(327, 1, 'Pembayaran kolektif', 'pembayaran', '120', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:38:54'),
(328, 1, 'Pembayaran kolektif', 'pembayaran', '121', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:39:46'),
(329, 1, 'Pembayaran kolektif', 'pembayaran', '122', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:39:46'),
(330, 1, 'Pembayaran kolektif', 'pembayaran', '123', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:40:37'),
(331, 1, 'Pembayaran kolektif', 'pembayaran', '124', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:40:37'),
(332, 1, 'Pembayaran kolektif', 'pembayaran', '125', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:40:37'),
(333, 1, 'Pembayaran kolektif', 'pembayaran', '126', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:40:37'),
(334, 1, 'Pembayaran kolektif', 'pembayaran', '127', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:41:11'),
(335, 1, 'Pembayaran kolektif', 'pembayaran', '128', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:41:11'),
(336, 1, 'Pembayaran kolektif', 'pembayaran', '129', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(337, 1, 'Pembayaran kolektif', 'pembayaran', '130', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(338, 1, 'Pembayaran kolektif', 'pembayaran', '131', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(339, 1, 'Pembayaran kolektif', 'pembayaran', '132', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(340, 1, 'Pembayaran kolektif', 'pembayaran', '133', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(341, 1, 'Pembayaran kolektif', 'pembayaran', '134', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:43:05'),
(342, 1, 'Pembayaran kolektif', 'pembayaran', '135', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(343, 1, 'Pembayaran kolektif', 'pembayaran', '136', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(344, 1, 'Pembayaran kolektif', 'pembayaran', '137', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(345, 1, 'Pembayaran kolektif', 'pembayaran', '138', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(346, 1, 'Pembayaran kolektif', 'pembayaran', '139', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(347, 1, 'Pembayaran kolektif', 'pembayaran', '140', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(348, 1, 'Pembayaran kolektif', 'pembayaran', '141', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(349, 1, 'Pembayaran kolektif', 'pembayaran', '142', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(350, 1, 'Pembayaran kolektif', 'pembayaran', '143', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(351, 1, 'Pembayaran kolektif', 'pembayaran', '144', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 04:58:35'),
(352, 1, 'Pembayaran kolektif', 'pembayaran', '145', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:06:52'),
(353, 1, 'Pembayaran kolektif', 'pembayaran', '146', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:06:52'),
(354, 1, 'Pembayaran kolektif', 'pembayaran', '147', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:07:11'),
(355, 1, 'Pembayaran kolektif', 'pembayaran', '148', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:07:11'),
(356, 1, 'Pembayaran kolektif', 'pembayaran', '149', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:07:34'),
(357, 1, 'Pembayaran kolektif', 'pembayaran', '150', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:07:34'),
(358, 1, 'Pembayaran kolektif', 'pembayaran', '151', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(359, 1, 'Pembayaran kolektif', 'pembayaran', '152', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(360, 1, 'Pembayaran kolektif', 'pembayaran', '153', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(361, 1, 'Pembayaran kolektif', 'pembayaran', '154', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(362, 1, 'Pembayaran kolektif', 'pembayaran', '155', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(363, 1, 'Pembayaran kolektif', 'pembayaran', '156', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(364, 1, 'Pembayaran kolektif', 'pembayaran', '157', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(365, 1, 'Pembayaran kolektif', 'pembayaran', '158', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(366, 1, 'Pembayaran kolektif', 'pembayaran', '159', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(367, 1, 'Pembayaran kolektif', 'pembayaran', '160', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:38'),
(368, 1, 'Pembayaran kolektif', 'pembayaran', '161', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:59'),
(369, 1, 'Pembayaran kolektif', 'pembayaran', '162', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:08:59'),
(370, 1, 'Mengubah data santri ID: 17', 'santri', '17', '180.252.163.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-25 05:11:26'),
(371, 1, 'Login ke sistem', 'guru', '1', '180.252.168.30', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-26 04:12:38'),
(372, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '21', '180.252.168.30', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-04-27 13:26:01'),
(373, 1, 'Login ke sistem', 'guru', '1', '118.99.76.224', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-05 09:45:52'),
(374, NULL, 'Menghapus data Jurnal Umum', 'jurnal_umum', '7', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:02:48'),
(375, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '22', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:07:25'),
(376, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '23', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:12:22'),
(377, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '24', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:12:55'),
(378, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '25', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:16:48'),
(379, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '26', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:17:25'),
(380, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '27', '180.252.171.177', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-06 13:22:10'),
(381, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '28', '180.252.169.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:03:39'),
(382, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '29', '180.252.169.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:04:54'),
(383, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '30', '180.252.169.129', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', '2026-05-11 06:05:20'),
(384, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '31', '182.2.176.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-13 09:35:05'),
(385, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '32', '182.2.176.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-13 09:35:25'),
(386, NULL, 'Menghapus data Jurnal Umum', 'jurnal_umum', '31', '182.2.176.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-13 09:36:17'),
(387, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '33', '182.2.176.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-13 09:37:38'),
(388, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '34', '182.2.176.37', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-13 09:38:59'),
(389, 1, 'Login ke sistem', 'guru', '1', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:39:55'),
(390, 1, 'Pembayaran kolektif', 'pembayaran', '163', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(391, 1, 'Pembayaran kolektif', 'pembayaran', '164', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(392, 1, 'Pembayaran kolektif', 'pembayaran', '165', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(393, 1, 'Pembayaran kolektif', 'pembayaran', '166', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(394, 1, 'Pembayaran kolektif', 'pembayaran', '167', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(395, 1, 'Pembayaran kolektif', 'pembayaran', '168', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(396, 1, 'Pembayaran kolektif', 'pembayaran', '169', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(397, 1, 'Pembayaran kolektif', 'pembayaran', '170', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:42:23'),
(398, 1, 'Pembayaran kolektif', 'pembayaran', '171', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:30'),
(399, 1, 'Pembayaran kolektif', 'pembayaran', '172', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:30'),
(400, 1, 'Pembayaran kolektif', 'pembayaran', '173', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:30'),
(401, 1, 'Pembayaran kolektif', 'pembayaran', '174', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:30'),
(402, 1, 'Pembayaran kolektif', 'pembayaran', '175', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:51'),
(403, 1, 'Pembayaran kolektif', 'pembayaran', '176', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-28 04:44:51'),
(404, 1, 'Login ke sistem', 'guru', '1', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 09:57:36'),
(405, 1, 'Pembayaran kolektif', 'pembayaran', '177', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 09:59:02'),
(406, 1, 'Pembayaran kolektif', 'pembayaran', '178', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 09:59:02'),
(407, 1, 'Pembayaran kolektif', 'pembayaran', '179', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 09:59:02'),
(408, 1, 'Pembayaran kolektif', 'pembayaran', '180', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 09:59:02'),
(411, 1, 'Pembayaran kolektif', 'pembayaran', '183', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 10:03:41'),
(412, 1, 'Pembayaran kolektif', 'pembayaran', '184', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 10:03:41'),
(413, 1, 'Pembayaran kolektif', 'pembayaran', '185', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 10:04:59'),
(414, 1, 'Pembayaran kolektif', 'pembayaran', '186', '182.2.187.232', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 10:04:59'),
(415, 1, 'Pembayaran kolektif', 'pembayaran', '187', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:45:36'),
(416, 1, 'Pembayaran kolektif', 'pembayaran', '188', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:45:36'),
(417, 1, 'Pembayaran kolektif', 'pembayaran', '189', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:49:03'),
(418, 1, 'Pembayaran kolektif', 'pembayaran', '190', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:49:03'),
(419, 1, 'Pembayaran kolektif', 'pembayaran', '191', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:49:03'),
(420, 1, 'Pembayaran kolektif', 'pembayaran', '192', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 12:49:03'),
(421, 1, 'Pembayaran kolektif', 'pembayaran', '193', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:05:48'),
(422, 1, 'Pembayaran kolektif', 'pembayaran', '194', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:05:48'),
(423, 1, 'Pembayaran kolektif', 'pembayaran', '195', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(424, 1, 'Pembayaran kolektif', 'pembayaran', '196', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(425, 1, 'Pembayaran kolektif', 'pembayaran', '197', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(426, 1, 'Pembayaran kolektif', 'pembayaran', '198', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(427, 1, 'Pembayaran kolektif', 'pembayaran', '199', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(428, 1, 'Pembayaran kolektif', 'pembayaran', '200', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(429, 1, 'Pembayaran kolektif', 'pembayaran', '201', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(430, 1, 'Pembayaran kolektif', 'pembayaran', '202', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(431, 1, 'Pembayaran kolektif', 'pembayaran', '203', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(432, 1, 'Pembayaran kolektif', 'pembayaran', '204', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:12:35'),
(433, 1, 'Pembayaran kolektif', 'pembayaran', '205', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:13:09'),
(434, 1, 'Pembayaran kolektif', 'pembayaran', '206', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:13:09'),
(435, 1, 'Pembayaran kolektif', 'pembayaran', '207', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(436, 1, 'Pembayaran kolektif', 'pembayaran', '208', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(437, 1, 'Pembayaran kolektif', 'pembayaran', '209', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(438, 1, 'Pembayaran kolektif', 'pembayaran', '210', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(439, 1, 'Pembayaran kolektif', 'pembayaran', '211', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(440, 1, 'Pembayaran kolektif', 'pembayaran', '212', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(441, 1, 'Pembayaran kolektif', 'pembayaran', '213', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(442, 1, 'Pembayaran kolektif', 'pembayaran', '214', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:16'),
(443, 1, 'Pembayaran kolektif', 'pembayaran', '215', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:54'),
(444, 1, 'Pembayaran kolektif', 'pembayaran', '216', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:14:54'),
(445, 1, 'Pembayaran kolektif', 'pembayaran', '217', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:15:21'),
(446, 1, 'Pembayaran kolektif', 'pembayaran', '218', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:15:21'),
(447, 1, 'Pembayaran kolektif', 'pembayaran', '219', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:15:50'),
(448, 1, 'Pembayaran kolektif', 'pembayaran', '220', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:15:50'),
(449, 1, 'Pembayaran kolektif', 'pembayaran', '221', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(450, 1, 'Pembayaran kolektif', 'pembayaran', '222', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(451, 1, 'Pembayaran kolektif', 'pembayaran', '223', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(452, 1, 'Pembayaran kolektif', 'pembayaran', '224', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(453, 1, 'Pembayaran kolektif', 'pembayaran', '225', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(454, 1, 'Pembayaran kolektif', 'pembayaran', '226', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(455, 1, 'Pembayaran kolektif', 'pembayaran', '227', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(456, 1, 'Pembayaran kolektif', 'pembayaran', '228', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:24:49'),
(457, 1, 'Pembayaran kolektif', 'pembayaran', '229', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:25:28'),
(458, 1, 'Pembayaran kolektif', 'pembayaran', '230', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 13:25:28'),
(459, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '35', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 14:59:48'),
(460, 1, 'Menambah data santri baru', 'santri', '53', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 23:29:52'),
(461, 1, 'Mengubah data santri ID: 53', 'santri', '53', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-29 23:44:34'),
(462, 1, 'Login ke sistem', 'guru', '1', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-30 22:45:49'),
(463, 1, 'Pembayaran kolektif', 'pembayaran', '231', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-30 22:55:18'),
(464, 1, 'Pembayaran kolektif', 'pembayaran', '232', '180.252.166.115', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-30 22:55:18'),
(465, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '36', '182.2.186.29', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-05-31 06:18:48'),
(466, 1, 'Login ke sistem', 'guru', '1', '182.2.176.200', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-02 10:32:27'),
(467, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '37', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:17:47'),
(468, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '38', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:19:03'),
(469, NULL, 'Menghapus data Jurnal Umum', 'jurnal_umum', '37', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:20:19'),
(470, 1, 'Login ke sistem', 'guru', '1', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:23:50'),
(471, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '39', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:25:55'),
(472, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '40', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:26:48'),
(473, 1, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '41', '180.252.162.45', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', '2026-06-03 14:27:23'),
(474, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '42', '182.2.176.43', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-12 09:37:07'),
(475, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '43', '182.6.7.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-17 10:13:39'),
(476, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '44', '182.6.7.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-17 10:14:13'),
(477, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '45', '182.6.7.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-17 10:14:39'),
(478, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '46', '182.6.7.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-17 10:15:01'),
(479, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '47', '182.6.7.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-17 10:15:25'),
(480, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '48', '182.6.6.32', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-18 10:00:18'),
(481, NULL, 'Menyimpan data Jurnal Umum (ditambahkan)', 'jurnal_umum', '49', '182.6.2.118', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-19 09:35:48'),
(482, 1, 'Login ke sistem', 'guru', '1', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:03:15'),
(483, 1, 'Pembayaran kolektif', 'pembayaran', '233', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:09:43'),
(484, 1, 'Pembayaran kolektif', 'pembayaran', '234', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:09:43'),
(485, 1, 'Pembayaran kolektif', 'pembayaran', '235', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:10:16'),
(486, 1, 'Pembayaran kolektif', 'pembayaran', '236', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:10:16'),
(487, 1, 'Pembayaran kolektif', 'pembayaran', '237', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:10:46'),
(488, 1, 'Pembayaran kolektif', 'pembayaran', '238', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:10:46'),
(489, 1, 'Pembayaran kolektif', 'pembayaran', '239', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:11:33'),
(490, 1, 'Pembayaran kolektif', 'pembayaran', '240', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:11:33'),
(491, 1, 'Pembayaran kolektif', 'pembayaran', '241', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:11:33'),
(492, 1, 'Pembayaran kolektif', 'pembayaran', '242', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:11:33'),
(500, 1, 'Menghapus data pembayaran', 'pembayaran', '240', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:13:58'),
(501, 1, 'Menghapus data pembayaran', 'pembayaran', '239', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:14:12'),
(502, 1, 'Pembayaran kolektif', 'pembayaran', '250', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:16:22'),
(503, 1, 'Pembayaran kolektif', 'pembayaran', '251', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:16:22'),
(504, 1, 'Pembayaran kolektif', 'pembayaran', '252', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:17:26'),
(505, 1, 'Pembayaran kolektif', 'pembayaran', '253', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:17:26'),
(511, 1, 'Pembayaran kolektif', 'pembayaran', '259', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(512, 1, 'Pembayaran kolektif', 'pembayaran', '260', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(513, 1, 'Pembayaran kolektif', 'pembayaran', '261', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(514, 1, 'Pembayaran kolektif', 'pembayaran', '262', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46');
INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `tabel`, `data_id`, `ip_address`, `user_agent`, `created_at`) VALUES
(515, 1, 'Pembayaran kolektif', 'pembayaran', '263', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(516, 1, 'Pembayaran kolektif', 'pembayaran', '264', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(517, 1, 'Pembayaran kolektif', 'pembayaran', '265', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(518, 1, 'Pembayaran kolektif', 'pembayaran', '266', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(519, 1, 'Pembayaran kolektif', 'pembayaran', '267', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(520, 1, 'Pembayaran kolektif', 'pembayaran', '268', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(521, 1, 'Pembayaran kolektif', 'pembayaran', '269', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(522, 1, 'Pembayaran kolektif', 'pembayaran', '270', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(523, 1, 'Pembayaran kolektif', 'pembayaran', '271', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(524, 1, 'Pembayaran kolektif', 'pembayaran', '272', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(525, 1, 'Pembayaran kolektif', 'pembayaran', '273', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(526, 1, 'Pembayaran kolektif', 'pembayaran', '274', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:20:46'),
(527, 1, 'Pembayaran kolektif', 'pembayaran', '275', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:23:20'),
(528, 1, 'Pembayaran kolektif', 'pembayaran', '276', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:23:20'),
(529, 1, 'Pembayaran kolektif', 'pembayaran', '277', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:23:20'),
(530, 1, 'Pembayaran kolektif', 'pembayaran', '278', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:23:20'),
(541, 1, 'Pembayaran kolektif', 'pembayaran', '289', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:32:59'),
(542, 1, 'Pembayaran kolektif', 'pembayaran', '290', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:32:59'),
(543, 1, 'Pembayaran kolektif', 'pembayaran', '291', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:32:59'),
(544, 1, 'Pembayaran kolektif', 'pembayaran', '292', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:32:59'),
(545, 1, 'Login ke sistem', 'guru', '1', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:36:07'),
(546, 1, 'Pembayaran kolektif', 'pembayaran', '294', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(547, 1, 'Pembayaran kolektif', 'pembayaran', '295', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(548, 1, 'Pembayaran kolektif', 'pembayaran', '296', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(549, 1, 'Pembayaran kolektif', 'pembayaran', '297', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(550, 1, 'Pembayaran kolektif', 'pembayaran', '298', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(551, 1, 'Pembayaran kolektif', 'pembayaran', '299', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:37:17'),
(552, 1, 'Pembayaran kolektif', 'pembayaran', '300', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(553, 1, 'Pembayaran kolektif', 'pembayaran', '301', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(554, 1, 'Pembayaran kolektif', 'pembayaran', '302', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(555, 1, 'Pembayaran kolektif', 'pembayaran', '303', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(556, 1, 'Pembayaran kolektif', 'pembayaran', '304', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(557, 1, 'Pembayaran kolektif', 'pembayaran', '305', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:38:56'),
(563, 1, 'Menghapus data pembayaran', 'pembayaran', '262', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:42:51'),
(564, 1, 'Menghapus data pembayaran', 'pembayaran', '261', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:43:22'),
(565, 1, 'Menghapus data pembayaran', 'pembayaran', '242', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:43:39'),
(566, 1, 'Menghapus data pembayaran', 'pembayaran', '241', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:43:53'),
(567, 1, 'Menghapus data pembayaran', 'pembayaran', '238', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:01'),
(568, 1, 'Menghapus data pembayaran', 'pembayaran', '236', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:08'),
(569, 1, 'Menghapus data pembayaran', 'pembayaran', '235', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:14'),
(570, 1, 'Menghapus data pembayaran', 'pembayaran', '234', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:20'),
(571, 1, 'Menghapus data pembayaran', 'pembayaran', '233', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:27'),
(572, 1, 'Menghapus data pembayaran', 'pembayaran', '237', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:44:34'),
(573, 1, 'Pembayaran kolektif', 'pembayaran', '311', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(574, 1, 'Pembayaran kolektif', 'pembayaran', '312', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(575, 1, 'Pembayaran kolektif', 'pembayaran', '313', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(576, 1, 'Pembayaran kolektif', 'pembayaran', '314', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(577, 1, 'Pembayaran kolektif', 'pembayaran', '315', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(578, 1, 'Pembayaran kolektif', 'pembayaran', '316', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(579, 1, 'Pembayaran kolektif', 'pembayaran', '317', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(580, 1, 'Pembayaran kolektif', 'pembayaran', '318', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(581, 1, 'Pembayaran kolektif', 'pembayaran', '319', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(582, 1, 'Pembayaran kolektif', 'pembayaran', '320', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(583, 1, 'Pembayaran kolektif', 'pembayaran', '321', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(584, 1, 'Pembayaran kolektif', 'pembayaran', '322', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:45:50'),
(585, 1, 'Menghapus data pembayaran', 'pembayaran', '272', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:57:31'),
(586, 1, 'Menghapus data pembayaran', 'pembayaran', '271', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 09:57:49'),
(589, 1, 'Menghapus data pembayaran', 'pembayaran', '266', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:00:11'),
(590, 1, 'Menghapus data pembayaran', 'pembayaran', '265', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:00:25'),
(597, 1, 'Pembayaran kolektif', 'pembayaran', '331', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(598, 1, 'Pembayaran kolektif', 'pembayaran', '332', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(599, 1, 'Pembayaran kolektif', 'pembayaran', '333', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(600, 1, 'Pembayaran kolektif', 'pembayaran', '334', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(601, 1, 'Pembayaran kolektif', 'pembayaran', '335', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(602, 1, 'Pembayaran kolektif', 'pembayaran', '336', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(603, 1, 'Pembayaran kolektif', 'pembayaran', '337', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(604, 1, 'Pembayaran kolektif', 'pembayaran', '338', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(605, 1, 'Pembayaran kolektif', 'pembayaran', '339', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(606, 1, 'Pembayaran kolektif', 'pembayaran', '340', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:03:53'),
(607, 1, 'Pembayaran kolektif', 'pembayaran', '341', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:04:50'),
(608, 1, 'Pembayaran kolektif', 'pembayaran', '342', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:04:50'),
(609, 1, 'Pembayaran kolektif', 'pembayaran', '343', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(610, 1, 'Pembayaran kolektif', 'pembayaran', '344', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(611, 1, 'Pembayaran kolektif', 'pembayaran', '345', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(612, 1, 'Pembayaran kolektif', 'pembayaran', '346', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(613, 1, 'Pembayaran kolektif', 'pembayaran', '347', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(614, 1, 'Pembayaran kolektif', 'pembayaran', '348', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:06:05'),
(621, 1, 'Pembayaran kolektif', 'pembayaran', '355', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:09:31'),
(622, 1, 'Pembayaran kolektif', 'pembayaran', '356', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:09:31'),
(626, 1, 'Pembayaran kolektif', 'pembayaran', '360', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:11:01'),
(627, 1, 'Pembayaran kolektif', 'pembayaran', '361', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:11:01'),
(628, 1, 'Pembayaran kolektif', 'pembayaran', '362', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:11:01'),
(629, 1, 'Pembayaran kolektif', 'pembayaran', '363', '180.252.169.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-20 10:11:01'),
(633, 1, 'Login ke sistem', 'guru', '1', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-22 03:30:37'),
(634, 1, 'Login ke sistem', 'guru', '1', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 06:20:06'),
(638, 1, 'Pembayaran kolektif', 'pembayaran', '370', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(639, 1, 'Pembayaran kolektif', 'pembayaran', '371', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(640, 1, 'Pembayaran kolektif', 'pembayaran', '372', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(641, 1, 'Pembayaran kolektif', 'pembayaran', '373', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(642, 1, 'Pembayaran kolektif', 'pembayaran', '374', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(643, 1, 'Pembayaran kolektif', 'pembayaran', '375', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(644, 1, 'Pembayaran kolektif', 'pembayaran', '376', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(645, 1, 'Pembayaran kolektif', 'pembayaran', '377', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(646, 1, 'Pembayaran kolektif', 'pembayaran', '378', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(647, 1, 'Pembayaran kolektif', 'pembayaran', '379', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(648, 1, 'Pembayaran kolektif', 'pembayaran', '380', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(649, 1, 'Pembayaran kolektif', 'pembayaran', '381', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:29:42'),
(650, 1, 'Pembayaran kolektif', 'pembayaran', '382', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:30:30'),
(651, 1, 'Pembayaran kolektif', 'pembayaran', '383', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:30:30'),
(652, 1, 'Pembayaran kolektif', 'pembayaran', '384', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:30:30'),
(653, 1, 'Pembayaran kolektif', 'pembayaran', '385', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:30:30'),
(657, 1, 'Pembayaran kolektif', 'pembayaran', '389', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(658, 1, 'Pembayaran kolektif', 'pembayaran', '390', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(659, 1, 'Pembayaran kolektif', 'pembayaran', '391', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(660, 1, 'Pembayaran kolektif', 'pembayaran', '392', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(661, 1, 'Pembayaran kolektif', 'pembayaran', '393', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(662, 1, 'Pembayaran kolektif', 'pembayaran', '394', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(663, 1, 'Pembayaran kolektif', 'pembayaran', '395', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(664, 1, 'Pembayaran kolektif', 'pembayaran', '396', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(665, 1, 'Pembayaran kolektif', 'pembayaran', '397', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(666, 1, 'Pembayaran kolektif', 'pembayaran', '398', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:32:55'),
(667, 1, 'Pembayaran kolektif', 'pembayaran', '399', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(668, 1, 'Pembayaran kolektif', 'pembayaran', '400', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(669, 1, 'Pembayaran kolektif', 'pembayaran', '401', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(670, 1, 'Pembayaran kolektif', 'pembayaran', '402', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(671, 1, 'Pembayaran kolektif', 'pembayaran', '403', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(672, 1, 'Pembayaran kolektif', 'pembayaran', '404', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(673, 1, 'Pembayaran kolektif', 'pembayaran', '405', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(674, 1, 'Pembayaran kolektif', 'pembayaran', '406', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(675, 1, 'Pembayaran kolektif', 'pembayaran', '407', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(676, 1, 'Pembayaran kolektif', 'pembayaran', '408', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(677, 1, 'Pembayaran kolektif', 'pembayaran', '409', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(678, 1, 'Pembayaran kolektif', 'pembayaran', '410', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:33:47'),
(679, 1, 'Pembayaran kolektif', 'pembayaran', '411', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:34:54'),
(680, 1, 'Pembayaran kolektif', 'pembayaran', '412', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:34:54'),
(681, 1, 'Pembayaran kolektif', 'pembayaran', '413', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:34:54'),
(682, 1, 'Pembayaran kolektif', 'pembayaran', '414', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:34:54'),
(683, 1, 'Pembayaran kolektif', 'pembayaran', '415', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(684, 1, 'Pembayaran kolektif', 'pembayaran', '416', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(685, 1, 'Pembayaran kolektif', 'pembayaran', '417', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(686, 1, 'Pembayaran kolektif', 'pembayaran', '418', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(687, 1, 'Pembayaran kolektif', 'pembayaran', '419', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(688, 1, 'Pembayaran kolektif', 'pembayaran', '420', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:35:50'),
(689, 1, 'Pembayaran kolektif', 'pembayaran', '421', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(690, 1, 'Pembayaran kolektif', 'pembayaran', '422', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(691, 1, 'Pembayaran kolektif', 'pembayaran', '423', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(692, 1, 'Pembayaran kolektif', 'pembayaran', '424', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(693, 1, 'Pembayaran kolektif', 'pembayaran', '425', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(694, 1, 'Pembayaran kolektif', 'pembayaran', '426', '180.252.173.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 13:36:41'),
(695, NULL, 'Menghapus data Pos Keuangan', 'pos_keuangan', '20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-30 00:20:59'),
(696, NULL, 'Menyimpan data Pos Keuangan (ditambahkan)', 'pos_keuangan', '21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-30 00:21:03'),
(697, NULL, 'Menghapus data Pos Keuangan', 'pos_keuangan', '21', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-30 00:21:13'),
(698, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-30 04:20:57'),
(699, NULL, 'Menyimpan kategori iuran (ditambahkan)', 'iuran', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-06 02:07:20'),
(700, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-06 02:08:08'),
(701, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:34:27'),
(702, 1, 'Input pembayaran', 'pembayaran', '428', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:13'),
(703, 1, 'Input pembayaran', 'pembayaran', '430', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:30'),
(704, 1, 'Menghapus data pembayaran', 'pembayaran', '430', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:43'),
(705, 1, 'Menghapus data pembayaran', 'pembayaran', '429', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:45'),
(706, 1, 'Menghapus data pembayaran', 'pembayaran', '428', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:46'),
(707, 1, 'Menghapus data pembayaran', 'pembayaran', '427', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 04:35:48'),
(708, 1, 'Mengubah pengaturan umum', 'settings', 'general', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:01:59'),
(709, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:02:10'),
(710, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:02:23'),
(711, 1, 'Mengubah pengaturan umum', 'settings', 'general', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:02:54'),
(712, 1, 'Mengubah pengaturan umum', 'settings', 'general', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:03:06'),
(713, 1, 'Mengubah pengaturan umum', 'settings', 'general', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:04:02'),
(714, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:04:10'),
(715, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-14 05:04:22'),
(716, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-15 07:53:01'),
(717, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-15 08:02:56'),
(718, NULL, 'Menyimpan kategori iuran (ditambahkan)', 'iuran', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 12:32:32'),
(719, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 12:33:39'),
(720, 1, 'Menghapus kategori iuran', 'iuran', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 12:44:26'),
(721, 1, 'Input pembayaran', 'pembayaran', '432', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 13:32:44'),
(722, 1, 'Menghapus data pembayaran', 'pembayaran', '432', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 14:31:46'),
(723, 1, 'Menghapus data pembayaran', 'pembayaran', '431', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-17 14:31:48'),
(724, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-18 06:31:08'),
(725, 1, 'Menghapus data santri', 'santri', '44', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-18 06:31:21'),
(726, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-20 03:24:43'),
(727, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-20 08:05:23'),
(728, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-07-21 04:16:07'),
(729, 1, 'Login ke sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 04:42:11'),
(730, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-22 09:35:28'),
(731, 1, 'Menonaktifkan 2 santri secara massal dari halaman pindah kelas', 'santri', '48,47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-22 10:29:22'),
(732, 1, 'Menghapus 1 santri secara massal dari halaman pindah kelas', 'santri', '48,47', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-22 10:29:27'),
(733, 1, 'Mengubah data santri ID: 48', 'santri', '48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-22 10:31:42'),
(734, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 04:38:29'),
(735, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 04:38:42'),
(736, 8, 'Logout dari sistem', 'petugas', '8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 04:43:38'),
(737, 1, 'Login ke sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 04:43:42'),
(738, 8, 'Logout dari sistem', 'petugas', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 04:47:54'),
(739, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 04:47:58'),
(740, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 06:11:58'),
(741, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-23 06:52:31'),
(742, 1, 'Login ke sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:24:20'),
(743, 1, 'Logout dari sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:24:44'),
(744, 1, 'Login ke sistem', 'petugas', '1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:24:48'),
(745, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-26 18:52:56'),
(746, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 01:55:24'),
(747, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 01:55:40'),
(748, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 01:55:57'),
(749, 1, 'Logout dari sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 01:56:19'),
(750, 8, 'Login ke sistem', 'petugas', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 01:56:24'),
(751, 8, 'Logout dari sistem', 'petugas', '8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 02:37:38'),
(752, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 03:47:32'),
(753, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-27 06:54:41'),
(754, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-28 02:04:51'),
(755, 1, 'Menghapus kategori iuran', 'iuran', '7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-28 02:20:54'),
(756, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-28 02:25:39'),
(757, 1, 'Meluluskan 1 santri secara massal dari halaman pindah kelas', 'santri', '48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-28 02:56:02'),
(758, 1, 'Login ke sistem', 'petugas', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', '2026-07-28 08:16:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_santri` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `periode_tipe` enum('bulanan','tahunan') NOT NULL DEFAULT 'bulanan',
  `periode_key` varchar(7) NOT NULL DEFAULT '',
  `bulan_dibayar` varchar(20) NOT NULL,
  `tahun_dibayar` varchar(4) NOT NULL,
  `id_iuran` int(11) DEFAULT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `metode_bayar` enum('tunai','transfer','online') DEFAULT 'tunai',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_santri`, `id_user`, `tgl_bayar`, `periode_tipe`, `periode_key`, `bulan_dibayar`, `tahun_dibayar`, `id_iuran`, `jumlah_bayar`, `metode_bayar`, `keterangan`, `created_at`) VALUES
(39, 33, 1, '2026-04-03', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-03 13:04:06'),
(40, 33, 1, '2026-04-03', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-03 13:04:06'),
(41, 46, 1, '2026-04-03', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-03 13:06:57'),
(42, 46, 1, '2026-04-03', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-03 13:06:57'),
(43, 43, 1, '2026-04-08', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:33:13'),
(44, 43, 1, '2026-04-08', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:33:13'),
(45, 43, 1, '2026-04-08', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:33:13'),
(46, 43, 1, '2026-04-08', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:33:13'),
(47, 43, 1, '2026-04-08', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:33:13'),
(48, 43, 1, '2026-04-08', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:33:13'),
(49, 43, 1, '2026-04-08', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:33:13'),
(50, 43, 1, '2026-04-08', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:33:13'),
(51, 45, 1, '2026-04-08', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:34:12'),
(52, 45, 1, '2026-04-08', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:34:12'),
(53, 45, 1, '2026-04-08', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:34:12'),
(54, 45, 1, '2026-04-08', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:34:12'),
(55, 32, 1, '2026-04-08', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-08 05:35:50'),
(56, 32, 1, '2026-04-08', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-08 05:35:50'),
(57, 43, 1, '2026-04-11', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-04-11 08:47:53'),
(58, 43, 1, '2026-04-11', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-04-11 08:47:53'),
(59, 25, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:37:52'),
(60, 25, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 20000.00, 'tunai', '', '2026-04-24 12:37:52'),
(61, 23, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(62, 23, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(63, 24, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(64, 24, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(65, 51, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(66, 51, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(67, 29, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(68, 29, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(69, 30, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(70, 30, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(71, 26, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(72, 26, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(73, 27, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:43:55'),
(74, 27, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:43:55'),
(75, 31, 1, '2026-04-24', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:44:43'),
(77, 23, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:48:59'),
(78, 23, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:48:59'),
(79, 51, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 12:48:59'),
(80, 51, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:48:59'),
(81, 31, 1, '2026-04-24', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 12:56:49'),
(85, 24, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:11:08'),
(86, 24, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:11:08'),
(87, 42, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:13:58'),
(88, 42, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:13:58'),
(89, 37, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:13:58'),
(90, 37, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:13:58'),
(91, 52, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:13:58'),
(92, 52, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:13:58'),
(93, 36, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:13:58'),
(94, 36, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:13:58'),
(95, 40, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:13:58'),
(96, 40, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:13:58'),
(97, 42, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:15:32'),
(98, 42, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:15:32'),
(99, 35, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:15:32'),
(100, 35, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:15:32'),
(101, 52, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:15:32'),
(102, 52, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:15:32'),
(103, 41, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:15:32'),
(104, 41, 1, '2026-04-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:15:32'),
(105, 38, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:17:09'),
(106, 38, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 30000.00, 'tunai', '', '2026-04-24 13:17:09'),
(107, 46, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:30:10'),
(108, 46, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:30:10'),
(109, 23, 1, '2026-04-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:36:52'),
(110, 23, 1, '2026-04-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:36:52'),
(111, 24, 1, '2026-04-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:36:52'),
(112, 24, 1, '2026-04-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:36:52'),
(113, 24, 1, '2026-04-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:37:18'),
(114, 24, 1, '2026-04-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-04-24 13:37:18'),
(115, 48, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-24 13:44:32'),
(116, 48, 1, '2026-04-24', 'bulanan', '2026-04', 'April', '2026', 1, 30000.00, 'tunai', '', '2026-04-24 13:44:32'),
(117, 10, 1, '2026-04-25', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:38:09'),
(118, 10, 1, '2026-04-25', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:38:09'),
(119, 10, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:38:54'),
(120, 10, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:38:54'),
(121, 6, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:39:46'),
(122, 6, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:39:46'),
(123, 1, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:40:37'),
(124, 1, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:40:37'),
(125, 6, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:40:37'),
(126, 6, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:40:37'),
(127, 10, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:41:11'),
(128, 10, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:41:11'),
(129, 8, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:43:05'),
(130, 8, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:43:05'),
(131, 9, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:43:05'),
(132, 9, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:43:05'),
(133, 13, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:43:05'),
(134, 13, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:43:05'),
(135, 2, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:58:35'),
(136, 2, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:58:35'),
(137, 1, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:58:35'),
(138, 1, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:58:35'),
(139, 6, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:58:35'),
(140, 6, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:58:35'),
(141, 4, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:58:35'),
(142, 4, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:58:35'),
(143, 5, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 04:58:35'),
(144, 5, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 04:58:35'),
(145, 19, 1, '2026-04-25', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:06:52'),
(146, 19, 1, '2026-04-25', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:06:52'),
(147, 19, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:07:11'),
(148, 19, 1, '2026-04-25', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:07:11'),
(149, 19, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:07:34'),
(150, 19, 1, '2026-04-25', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:07:34'),
(151, 16, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:38'),
(152, 16, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:38'),
(153, 17, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:38'),
(154, 17, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:38'),
(155, 18, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:38'),
(156, 18, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:38'),
(157, 19, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:38'),
(158, 19, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:38'),
(159, 21, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:38'),
(160, 21, 1, '2026-04-25', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:38'),
(161, 19, 1, '2026-04-25', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-04-25 05:08:59'),
(162, 19, 1, '2026-04-25', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-04-25 05:08:59'),
(163, 16, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:42:23'),
(164, 16, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:42:23'),
(165, 17, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:42:23'),
(166, 17, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:42:23'),
(167, 18, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:42:23'),
(168, 18, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:42:23'),
(169, 21, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:42:23'),
(170, 21, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:42:23'),
(171, 32, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:44:30'),
(172, 32, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:44:30'),
(173, 33, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:44:30'),
(174, 33, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:44:30'),
(175, 46, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-28 04:44:51'),
(176, 46, 1, '2026-05-28', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-28 04:44:51'),
(177, 35, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 09:59:02'),
(178, 35, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 09:59:02'),
(179, 41, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 09:59:02'),
(180, 41, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 09:59:02'),
(183, 38, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 10:03:41'),
(184, 38, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 30000.00, 'tunai', '', '2026-05-29 10:03:41'),
(185, 48, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 10:04:59'),
(186, 48, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 30000.00, 'tunai', '', '2026-05-29 10:04:59'),
(187, 35, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 12:45:36'),
(188, 35, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 12:45:36'),
(189, 36, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 12:49:03'),
(190, 36, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 12:49:03'),
(191, 40, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 12:49:03'),
(192, 40, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 12:49:03'),
(193, 14, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:05:48'),
(194, 14, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:05:48'),
(195, 2, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:12:35'),
(196, 2, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:12:35'),
(197, 6, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:12:35'),
(198, 6, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:12:35'),
(199, 4, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:12:35'),
(200, 4, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:12:35'),
(201, 7, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:12:35'),
(202, 7, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:12:35'),
(203, 5, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:12:35'),
(204, 5, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:12:35'),
(205, 7, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:13:09'),
(206, 7, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:13:09'),
(207, 8, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:14:16'),
(208, 8, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:14:16'),
(209, 9, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:14:16'),
(210, 9, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:14:16'),
(211, 12, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:14:16'),
(212, 12, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:14:16'),
(213, 13, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:14:16'),
(214, 13, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:14:16'),
(215, 12, 1, '2026-05-29', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:14:54'),
(216, 12, 1, '2026-05-29', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:14:54'),
(217, 12, 1, '2026-05-29', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:15:21'),
(218, 12, 1, '2026-05-29', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:15:21'),
(219, 12, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:15:50'),
(220, 12, 1, '2026-05-29', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:15:50'),
(221, 29, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:24:49'),
(222, 29, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:24:49'),
(223, 30, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:24:49'),
(224, 30, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:24:49'),
(225, 26, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:24:49'),
(226, 26, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:24:49'),
(227, 27, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:24:49'),
(228, 27, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-29 13:24:49'),
(229, 25, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-29 13:25:28'),
(230, 25, 1, '2026-05-29', 'bulanan', '2026-05', 'Mei', '2026', 1, 20000.00, 'tunai', '', '2026-05-29 13:25:28'),
(231, 37, 1, '2026-05-31', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-05-30 22:55:18'),
(232, 37, 1, '2026-05-31', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-05-30 22:55:18'),
(250, 34, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:16:22'),
(251, 34, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 09:16:22'),
(252, 34, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:17:26'),
(253, 34, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 09:17:26'),
(259, 35, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:20:46'),
(260, 35, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:20:46'),
(263, 37, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:20:46'),
(264, 37, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:20:46'),
(267, 36, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:20:46'),
(268, 36, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:20:46'),
(269, 53, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:20:46'),
(270, 53, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:20:46'),
(273, 40, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:20:46'),
(274, 40, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:20:46'),
(275, 34, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:23:20'),
(276, 34, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 09:23:20'),
(277, 38, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:23:20'),
(278, 38, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 09:23:20'),
(289, 32, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:32:59'),
(290, 32, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:32:59'),
(291, 33, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:32:59'),
(292, 33, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:32:59'),
(294, 43, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:37:17'),
(295, 43, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:37:17'),
(296, 46, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:37:17'),
(297, 46, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:37:17'),
(298, 45, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:37:17'),
(299, 45, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:37:17'),
(300, 45, 1, '2026-06-20', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:38:56'),
(301, 45, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:38:56'),
(302, 45, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:38:56'),
(303, 45, 1, '2026-06-20', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:38:56'),
(304, 45, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:38:56'),
(305, 45, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:38:56'),
(311, 39, 1, '2026-06-20', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(312, 39, 1, '2026-06-20', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(313, 39, 1, '2026-06-20', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(314, 39, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(315, 39, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(316, 39, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 09:45:50'),
(317, 39, 1, '2026-06-20', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(318, 39, 1, '2026-06-20', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(319, 39, 1, '2026-06-20', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(320, 39, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(321, 39, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(322, 39, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 09:45:50'),
(331, 23, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:03:53'),
(332, 23, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:03:53'),
(333, 29, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:03:53'),
(334, 29, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:03:53'),
(335, 30, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:03:53'),
(336, 30, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:03:53'),
(337, 26, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:03:53'),
(338, 26, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:03:53'),
(339, 27, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:03:53'),
(340, 27, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:03:53'),
(341, 25, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:04:50'),
(342, 25, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 20000.00, 'tunai', '', '2026-06-20 10:04:50'),
(343, 28, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:06:05'),
(344, 28, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:06:05'),
(345, 28, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:06:05'),
(346, 28, 1, '2026-06-20', 'bulanan', '2026-04', 'April', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 10:06:05'),
(347, 28, 1, '2026-06-20', 'bulanan', '2026-05', 'Mei', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 10:06:05'),
(348, 28, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 10:06:05'),
(355, 48, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:09:31'),
(356, 48, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 30000.00, 'tunai', '', '2026-06-20 10:09:31'),
(360, 52, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:11:01'),
(361, 52, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:11:01'),
(362, 41, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-20 10:11:01'),
(363, 41, 1, '2026-06-20', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-20 10:11:01'),
(370, 14, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(371, 14, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(372, 16, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(373, 16, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(374, 17, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(375, 17, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(376, 18, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(377, 18, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(378, 19, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(379, 19, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(380, 21, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:29:42'),
(381, 21, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:29:42'),
(382, 20, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:30:30'),
(383, 20, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:30:30'),
(384, 20, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:30:30'),
(385, 20, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:30:30'),
(389, 2, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:32:55'),
(390, 2, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:32:55'),
(391, 6, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:32:55'),
(392, 6, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:32:55'),
(393, 4, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:32:55'),
(394, 4, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:32:55'),
(395, 7, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:32:55'),
(396, 7, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:32:55'),
(397, 5, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:32:55'),
(398, 5, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:32:55'),
(399, 3, 1, '2026-06-24', 'bulanan', '2026-01', 'Januari', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(400, 3, 1, '2026-06-24', 'bulanan', '2026-02', 'Februari', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(401, 3, 1, '2026-06-24', 'bulanan', '2026-03', 'Maret', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(402, 3, 1, '2026-06-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(403, 3, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(404, 3, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:33:47'),
(405, 3, 1, '2026-06-24', 'bulanan', '2026-01', 'Januari', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(406, 3, 1, '2026-06-24', 'bulanan', '2026-02', 'Februari', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(407, 3, 1, '2026-06-24', 'bulanan', '2026-03', 'Maret', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(408, 3, 1, '2026-06-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(409, 3, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(410, 3, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:33:47'),
(411, 1, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:34:54'),
(412, 1, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:34:54'),
(413, 1, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:34:54'),
(414, 1, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:34:54'),
(415, 8, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:35:50'),
(416, 8, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:35:50'),
(417, 9, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:35:50'),
(418, 9, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:35:50'),
(419, 13, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:35:50'),
(420, 13, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:35:50'),
(421, 10, 1, '2026-06-24', 'bulanan', '2026-04', 'April', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:36:41'),
(422, 10, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:36:41'),
(423, 10, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 5, 5000.00, 'tunai', '', '2026-06-24 13:36:41'),
(424, 10, 1, '2026-06-24', 'bulanan', '2026-04', 'April', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:36:41'),
(425, 10, 1, '2026-06-24', 'bulanan', '2026-05', 'Mei', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:36:41'),
(426, 10, 1, '2026-06-24', 'bulanan', '2026-06', 'Juni', '2026', 1, 35000.00, 'tunai', '', '2026-06-24 13:36:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `level` enum('admin','bendahara','kepala_tpq') NOT NULL DEFAULT 'bendahara',
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama`, `email`, `no_telp`, `level`, `foto`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@sekolah.sch.id', '&amp;amp;amp;am', 'admin', 'default.png', 'active', '2026-07-28 15:16:32', '2026-02-03 03:31:39', '2026-07-28 08:16:32'),
(3, 'guru_1770094368321', '$2y$10$952cbNtntoPGJ8nJV2cLtu0X9jauO2NKVdE3ZOcqcQg0r.0yhTbPO', 'Guru Test 1770094368321', 'guru1770094368321@test.com', '08123456789', 'bendahara', 'default.png', 'active', NULL, '2026-02-03 04:52:49', '2026-06-30 00:13:45'),
(4, 'guru_1770094402500', '$2y$10$6GPr3gW8p0LX1Gl20QyV.uE2PvDcC0TzpKGvw7yMFR4vE7IbObQnq', 'Guru Test 1770094402500', 'guru1770094402500@test.com', '08123456789', 'bendahara', 'default.png', 'active', NULL, '2026-02-03 04:53:23', '2026-06-30 00:13:45'),
(5, 'guru_1770094459031', '$2y$10$C8Vqd3J5oFgw5FttMIyAVuekWOk/F.A3YWighc/ZwCG2QT4zA1PeG', 'Guru Test 1770094459031', 'guru1770094459031@test.com', '08123456789', 'bendahara', 'default.png', 'active', NULL, '2026-02-03 04:54:19', '2026-06-30 00:13:45'),
(8, 'kasmi', '$2y$10$Pa1M8h0V7DGiOFOjVRYZou19xXx7WAoq.bgzO//w1keAcvudRW70i', 'daskd', 'dashu@gmial.com', '87979675765', 'bendahara', 'default.png', 'active', '2026-07-27 08:56:24', '2026-02-03 04:57:07', '2026-07-27 01:56:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pos_keuangan`
--

CREATE TABLE `pos_keuangan` (
  `id_pos` int(11) NOT NULL,
  `nama_pos` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pos_keuangan`
--

INSERT INTO `pos_keuangan` (`id_pos`, `nama_pos`, `created_at`, `updated_at`) VALUES
(2, 'Donasi', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(3, 'Bantuan Operasional', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(4, 'Lain-lain (Masuk)', '2026-02-07 01:42:43', '2026-02-07 01:42:43'),
(5, 'Gaji Guru', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(6, 'Listrik & Air', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(9, 'Lain-lain (Keluar)', '2026-02-07 01:42:43', '2026-02-07 13:45:13'),
(11, 'Uang Kas', '2026-02-07 03:34:13', '2026-02-07 03:34:13'),
(12, 'SPP', '2026-03-03 02:06:52', '2026-03-03 02:06:52'),
(13, 'Daftar Ulang', '2026-03-03 02:06:52', '2026-03-03 02:06:52'),
(14, 'POMG', '2026-03-03 02:06:52', '2026-03-03 02:06:52'),
(15, 'Ekskul', '2026-03-04 00:33:01', '2026-03-04 00:33:01'),
(16, 'Infak Jumat', '2026-03-07 08:31:22', '2026-03-07 08:31:22'),
(17, 'Bk prestasi dan Bk tabungan', '2026-03-07 08:37:12', '2026-03-07 08:37:12'),
(18, 'Sisa kegiatan TPQ', '2026-03-07 08:38:16', '2026-03-07 08:38:26'),
(19, 'Rapot dan ijazah', '2026-03-07 08:39:16', '2026-03-07 08:39:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `santri`
--

CREATE TABLE `santri` (
  `id_santri` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `alamat` text DEFAULT NULL,
  `id_iuran` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `status` enum('active','nonaktif') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `santri`
--

INSERT INTO `santri` (`id_santri`, `nama`, `tempat_lahir`, `tanggal_lahir`, `id_kelas`, `alamat`, `id_iuran`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Amanda Anindia Wijaya', 'Pandeglang', '2022-01-31', 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:53:35'),
(2, 'Afiza Ghania Rezkya', 'Bogor', '2021-07-20', 19, '', 5, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 10:26:27'),
(3, 'Chana Alifia Iskandar', 'Bogor', '2020-08-18', 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:52:21'),
(4, 'Muhammad Faiz', 'Bogor', '2018-01-20', 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(5, 'Shafiah Haura', 'Pekan Baru', '2019-01-07', 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(6, 'Anastsya S. F', '', NULL, 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:53:03'),
(7, 'Qanita', '', NULL, 19, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:54:14'),
(8, 'Aisha Zahra', 'Bogor', '2020-05-31', 20, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(9, 'Almaira Rezkya', 'Serang', '2019-05-20', 20, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:55:14'),
(10, 'Atha Hafiz Alfarizi', 'Bogor', '2018-06-02', 20, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:56:17'),
(12, 'Mohammad Alfatih', 'Bogor', '2019-04-11', 20, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:57:27'),
(13, 'Noval Dwi Kurnia', 'Bogor', '2020-02-12', 20, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-27 21:57:53'),
(14, 'Adreena Putri Sulaeman', 'Jakarta', '2018-12-13', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(16, 'Anisa Elifa Putri', 'Jakarta', '2018-09-13', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(17, 'Jamal Karim Abdullah', 'Jakarta', '2018-08-03', 21, '', 5, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-25 05:11:26'),
(18, 'Kimmy Qurrota Aini', 'Jakarta', '2018-09-13', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(19, 'Mikhayla Tsagie Arifah', 'Jakarta', '2017-07-07', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-02-13 09:37:41'),
(20, 'Nara Anindira', 'Bogor', '2018-09-27', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(21, 'Unna Rumeisa Ramadhan', 'Bogor', '2019-05-15', 21, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(22, 'M. Fahri Khoiri Afandi', 'Bogor', '2016-11-24', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(23, 'Alika Nayla Putri', 'Bogor', '2017-11-20', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(24, 'Defhan Akbar Wijaya', 'Bogor', '2018-03-18', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(25, 'M. Lattif Jaluli', 'Jakarta', '2017-05-30', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(26, 'Risya Hanifa Damayanti', 'Bogor', '2017-05-13', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(27, 'Sulistia Adawiyatul R.', 'Bogor', '2017-03-29', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(28, 'Arshaka Raqqila A.', 'Tangerang', '2018-07-01', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(29, 'M. Arrash Hermawan', 'Bogor', '2017-05-30', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(30, 'Rafeyfa Syakir A.', 'Jakarta', '2018-01-31', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(31, 'Ainaya Violla Z.', 'Sago', '2017-05-28', 22, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(32, 'Arfan Maulana Attaya', 'Tangerang', '2016-01-27', 23, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(33, 'Keenan Aidil Basyir', 'Jakarta', '2016-06-26', 23, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(34, 'Alvaro Caesar Anandito', 'Tangerang', '2016-05-24', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(35, 'Asyraf Zahirul Ubaid', 'Bogor', '2015-10-10', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(36, 'M. Naufal Abiyyu', 'Depok', '2015-06-20', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(37, 'Fathina Rubbyasmine', 'Tangerang', '2014-03-09', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(38, 'Erlangga Julian Tahir', 'Blora', '2016-07-11', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(39, 'Cesar Ramadhan I.', 'Serang', '2016-06-09', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(40, 'Yusuf Al Rasyid', 'Depok', '2015-09-17', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(41, 'Siti Aennurizki', 'Bogor', '2016-01-31', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(42, 'Afiqah Dwi Nafilah', 'Tangsel', '2014-01-30', 24, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:42'),
(43, 'Aaleyah Zhafira M.', 'Jakarta', '2014-01-23', 25, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(45, 'Ridho Zakaria', 'Bogor', '2013-12-17', 25, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(46, 'Dalisha Mecca M.', 'Bogor', '2014-05-20', 25, '', 1, 'default.png', 'active', '2026-02-13 09:21:20', '2026-04-01 12:15:43'),
(48, 'Amira Syifa Sutrisno', 'Blora', '2012-11-26', 26, '', 1, 'default.png', 'nonaktif', '2026-02-13 09:21:20', '2026-07-28 02:56:02'),
(50, 'Alika Rizkia Riyanto', 'Jakarta', '2017-04-18', 22, 'Perumahan Palm Parung', 1, 'default.png', 'active', '2026-02-24 07:32:22', '2026-04-01 12:15:42'),
(51, 'Kasih Widya Browning', '', NULL, 22, 'Perumahan metro parung', 1, 'default.png', 'active', '2026-02-24 07:34:36', '2026-04-01 12:15:42'),
(52, 'M. Fakhri Jaffan K.', '', NULL, 24, '', 1, 'default.png', 'active', '2026-02-27 22:32:10', '2026-04-01 12:15:43'),
(53, 'Muhammad Adhiyasta', '', '0000-00-00', 24, '', 5, 'default.png', 'active', '2026-05-29 23:29:52', '2026-05-29 23:44:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `santri_iuran`
--

CREATE TABLE `santri_iuran` (
  `id_santri_iuran` int(11) NOT NULL,
  `id_santri` int(11) NOT NULL,
  `id_iuran` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `santri_iuran`
--

INSERT INTO `santri_iuran` (`id_santri_iuran`, `id_santri`, `id_iuran`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(2, 1, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(3, 1, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(4, 1, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(5, 2, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(6, 2, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(7, 2, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(8, 2, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(9, 3, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(10, 3, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(11, 3, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(12, 3, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(13, 4, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(14, 4, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(15, 4, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(16, 4, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(17, 5, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(18, 5, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(19, 5, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(20, 5, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(21, 6, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(22, 6, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(23, 6, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(24, 6, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(25, 7, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(26, 7, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(27, 7, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(28, 7, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(29, 8, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(30, 8, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(31, 8, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(32, 8, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(33, 9, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(34, 9, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(35, 9, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(36, 9, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(37, 10, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(38, 10, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(39, 10, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(40, 10, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(45, 12, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(46, 12, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(47, 12, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(48, 12, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(49, 13, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(50, 13, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(51, 13, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(52, 13, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(53, 14, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(54, 14, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(55, 14, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(56, 14, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(61, 16, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(62, 16, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(63, 16, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(64, 16, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(65, 17, 1, 0, '2026-04-01 10:35:39', '2026-04-25 05:11:26'),
(66, 17, 2, 0, '2026-04-01 10:35:39', '2026-04-25 05:11:26'),
(67, 17, 5, 0, '2026-04-01 10:35:39', '2026-04-25 05:11:26'),
(68, 17, 6, 0, '2026-04-01 10:35:39', '2026-04-25 05:11:26'),
(69, 18, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(70, 18, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(71, 18, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(72, 18, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(73, 19, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(74, 19, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(75, 19, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(76, 19, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(77, 20, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(78, 20, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(79, 20, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(80, 20, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(81, 21, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(82, 21, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(83, 21, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(84, 21, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(85, 22, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(86, 22, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(87, 22, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(88, 22, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(89, 23, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(90, 23, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(91, 23, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(92, 23, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(93, 24, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(94, 24, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(95, 24, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(96, 24, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(97, 25, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(98, 25, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(99, 25, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(100, 25, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(101, 26, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(102, 26, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(103, 26, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(104, 26, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(105, 27, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(106, 27, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(107, 27, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(108, 27, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(109, 28, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(110, 28, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(111, 28, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(112, 28, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(113, 29, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(114, 29, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(115, 29, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(116, 29, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(117, 30, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(118, 30, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(119, 30, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(120, 30, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(121, 31, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(122, 31, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(123, 31, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(124, 31, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(125, 32, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(126, 32, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(127, 32, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(128, 32, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(129, 33, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(130, 33, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(131, 33, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(132, 33, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(133, 34, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(134, 34, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(135, 34, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(136, 34, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(137, 35, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(138, 35, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(139, 35, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(140, 35, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(141, 36, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(142, 36, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(143, 36, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(144, 36, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(145, 37, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(146, 37, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(147, 37, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(148, 37, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(149, 38, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(150, 38, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(151, 38, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(152, 38, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(153, 39, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(154, 39, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(155, 39, 5, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(156, 39, 6, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(157, 40, 1, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(158, 40, 2, 1, '2026-04-01 10:35:39', '2026-04-01 10:35:39'),
(159, 40, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(160, 40, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(161, 41, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(162, 41, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(163, 41, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(164, 41, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(165, 42, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(166, 42, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(167, 42, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(168, 42, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(169, 43, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(170, 43, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(171, 43, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(172, 43, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(177, 45, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(178, 45, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(179, 45, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(180, 45, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(181, 46, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(182, 46, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(183, 46, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(184, 46, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(189, 48, 1, 0, '2026-04-01 10:35:40', '2026-07-22 10:29:22'),
(190, 48, 2, 0, '2026-04-01 10:35:40', '2026-07-22 10:29:22'),
(191, 48, 5, 0, '2026-04-01 10:35:40', '2026-07-22 10:29:22'),
(192, 48, 6, 0, '2026-04-01 10:35:40', '2026-07-22 10:29:22'),
(197, 50, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(198, 50, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(199, 50, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(200, 50, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(201, 51, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(202, 51, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(203, 51, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(204, 51, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(205, 52, 1, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(206, 52, 2, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(207, 52, 5, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(208, 52, 6, 1, '2026-04-01 10:35:40', '2026-04-01 10:35:40'),
(209, 17, 5, 1, '2026-04-25 05:11:26', '2026-04-25 05:11:26'),
(210, 17, 1, 1, '2026-04-25 05:11:26', '2026-04-25 05:11:26'),
(211, 17, 2, 1, '2026-04-25 05:11:26', '2026-04-25 05:11:26'),
(212, 17, 6, 1, '2026-04-25 05:11:26', '2026-04-25 05:11:26'),
(213, 53, 5, 0, '2026-05-29 23:29:52', '2026-05-29 23:44:34'),
(214, 53, 1, 0, '2026-05-29 23:29:52', '2026-05-29 23:44:34'),
(215, 53, 2, 0, '2026-05-29 23:29:52', '2026-05-29 23:44:34'),
(216, 53, 6, 0, '2026-05-29 23:29:52', '2026-05-29 23:44:34'),
(217, 53, 5, 1, '2026-05-29 23:44:34', '2026-05-29 23:44:34'),
(218, 53, 1, 1, '2026-05-29 23:44:34', '2026-05-29 23:44:34'),
(219, 53, 2, 1, '2026-05-29 23:44:34', '2026-05-29 23:44:34'),
(220, 53, 6, 1, '2026-05-29 23:44:34', '2026-05-29 23:44:34'),
(221, 48, 1, 0, '2026-07-22 10:31:42', '2026-07-28 02:56:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','password','email','number','boolean','json') DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'nama_sekolah', 'TPQ Al-Ikhlas Metro Parung', 'text', 'general', 'Nama sekolah', '2026-02-03 03:31:39', '2026-02-03 12:43:15'),
(2, 'alamat_sekolah', 'PERUM METRO PARUNG, BLOK A5, Rt. 02, Rw. 07', 'text', 'general', 'Alamat sekolah', '2026-02-03 03:31:39', '2026-02-03 12:44:34'),
(3, 'telp_sekolah', '021-12345678', 'text', 'general', 'Telepon sekolah', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(4, 'email_sekolah', 'info@smkn1contoh.sch.id', 'email', 'general', 'Email sekolah', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(5, 'website_sekolah', 'https://smkn1contoh.sch.id', 'text', 'general', 'Website sekolah', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(6, 'logo_sekolah', 'logo.png', 'text', 'general', 'Logo sekolah', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(7, 'smtp_host', 'smtp.gmail.com', 'text', 'email', 'SMTP Host', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(8, 'smtp_port', '587', 'number', 'email', 'SMTP Port', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(9, 'smtp_username', '', 'email', 'email', 'SMTP Username/Email', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(10, 'smtp_password', '', 'password', 'email', 'SMTP Password/App Password', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(11, 'smtp_encryption', 'tls', 'text', 'email', 'SMTP Encryption (tls/ssl)', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(12, 'email_from_name', 'Sistem SPP Sekolah', 'text', 'email', 'Nama pengirim email', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(13, 'wa_api_provider', 'fonnte', 'text', 'whatsapp', 'Provider API WhatsApp (fonnte/wablas/custom)', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(14, 'wa_api_url', 'https://api.fonnte.com/send', 'text', 'whatsapp', 'URL API WhatsApp', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(15, 'wa_api_token', '', 'password', 'whatsapp', 'Token API WhatsApp', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(16, 'wa_sender', '', 'text', 'whatsapp', 'Nomor pengirim WhatsApp', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(17, 'payment_enabled', '0', 'boolean', 'payment', 'Enable online payment', '2026-02-03 03:31:39', '2026-02-03 05:24:50'),
(18, 'midtrans_server_key', 'password', 'password', 'payment', 'Midtrans Server Key', '2026-02-03 03:31:39', '2026-02-03 05:24:23'),
(19, 'midtrans_client_key', '', 'text', 'payment', 'Midtrans Client Key', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(20, 'midtrans_is_production', '0', 'boolean', 'payment', 'Midtrans Production Mode', '2026-02-03 03:31:39', '2026-02-03 12:45:40'),
(21, 'notif_email_enabled', '0', 'boolean', 'notification', 'Enable email notifications', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(22, 'notif_wa_enabled', '0', 'boolean', 'notification', 'Enable WhatsApp notifications', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(23, 'notif_payment_template', 'Pembayaran SPP untuk {nama} bulan {bulan} sebesar Rp {jumlah} telah diterima. Terima kasih.', 'text', 'notification', 'Template notifikasi pembayaran', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(24, 'auto_backup_enabled', '0', 'boolean', 'backup', 'Enable auto backup', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(25, 'backup_interval', 'weekly', 'text', 'backup', 'Backup interval (daily/weekly/monthly)', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(26, 'backup_keep_days', '30', 'number', 'backup', 'Jumlah hari backup disimpan', '2026-02-03 03:31:39', '2026-02-03 03:31:39'),
(27, 'logo_path', 'assets/img/logo.png', 'text', 'general', NULL, '2026-07-14 05:01:59', '2026-07-14 05:04:02');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `iuran`
--
ALTER TABLE `iuran`
  ADD PRIMARY KEY (`id_iuran`);

--
-- Indeks untuk tabel `jurnal_umum`
--
ALTER TABLE `jurnal_umum`
  ADD PRIMARY KEY (`id_jurnal`),
  ADD KEY `id_pos` (`id_pos`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `log_aktivitas_ibfk_1` (`id_user`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_spp` (`id_iuran`),
  ADD KEY `pembayaran_ibfk_1` (`id_user`),
  ADD KEY `idx_bayar_periode` (`id_santri`,`id_iuran`,`periode_key`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pos_keuangan`
--
ALTER TABLE `pos_keuangan`
  ADD PRIMARY KEY (`id_pos`);

--
-- Indeks untuk tabel `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id_santri`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_spp` (`id_iuran`);

--
-- Indeks untuk tabel `santri_iuran`
--
ALTER TABLE `santri_iuran`
  ADD PRIMARY KEY (`id_santri_iuran`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `iuran`
--
ALTER TABLE `iuran`
  MODIFY `id_iuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `jurnal_umum`
--
ALTER TABLE `jurnal_umum`
  MODIFY `id_jurnal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=759;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pos_keuangan`
--
ALTER TABLE `pos_keuangan`
  MODIFY `id_pos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `santri`
--
ALTER TABLE `santri`
  MODIFY `id_santri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `santri_iuran`
--
ALTER TABLE `santri_iuran`
  MODIFY `id_santri_iuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jurnal_umum`
--
ALTER TABLE `jurnal_umum`
  ADD CONSTRAINT `jurnal_umum_ibfk_1` FOREIGN KEY (`id_pos`) REFERENCES `pos_keuangan` (`id_pos`);

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_santri` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_3` FOREIGN KEY (`id_iuran`) REFERENCES `iuran` (`id_iuran`);

--
-- Ketidakleluasaan untuk tabel `santri`
--
ALTER TABLE `santri`
  ADD CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `santri_ibfk_2` FOREIGN KEY (`id_iuran`) REFERENCES `iuran` (`id_iuran`);

--
-- Ketidakleluasaan untuk tabel `santri_iuran`
--
ALTER TABLE `santri_iuran`
  ADD CONSTRAINT `fk_santri_iuran_santri` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
