-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2026 at 02:04 AM
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
-- Database: `labkom`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang_masters`
--

CREATE TABLE `barang_masters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `spesifikasi` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `satuan` varchar(50) NOT NULL DEFAULT 'unit',
  `stok_minimum` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_masters`
--

INSERT INTO `barang_masters` (`id`, `nama_barang`, `spesifikasi`, `kategori`, `satuan`, `stok_minimum`, `created_at`, `updated_at`) VALUES
(1, 'Mouse', 'Logitech M90', 'Peripheral', 'unit', 5, '2026-10-01 04:12:19', '2026-10-30 03:15:14'),
(2, 'Mouse', 'Logitech B100', 'Peripheral', 'unit', 5, '2026-10-01 04:12:19', '2026-10-30 03:14:55'),
(3, 'Keyboard', 'Logitech K120', 'Peripheral', 'unit', 5, '2026-10-01 04:12:19', '2026-10-30 03:13:23'),
(4, 'Headset', 'SADES TPower Gaming', 'Peripheral', 'unit', 5, '2026-10-01 04:12:19', '2026-10-30 03:13:05'),
(5, 'Mousepad', '21 x 17 cm', 'Peripheral', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:15:43'),
(6, 'Mousepad', '21 x 26 cm', 'Peripheral', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:15:59'),
(7, 'PSU 550W', 'FSP HV Pro 550W - 5IAAC', 'Komponen PC', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:16:37'),
(8, 'HDD 500 GB', 'WD Black', 'Komponen PC', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:12:12'),
(9, 'HDD 1 TB', 'WD Black', 'Komponen PC', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:11:57'),
(10, 'Converter', 'Type C to HDMI + VGA Cabletime', 'Peripheral', 'unit', 2, '2026-10-01 04:12:19', '2026-10-30 03:11:38'),
(11, 'Proyektor', 'ViewSonic', 'Sarana & Prasarana', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:16:16'),
(12, 'Baterai AA', 'Panasonic Eneloop BK-3MCCE / 4BT', 'Sarana & Prasarana', 'buah', 5, '2026-10-01 04:12:19', '2026-10-30 03:10:09'),
(13, 'Baterai AAA', 'Panasonic Eneloop BK-4MCCE / 4BT', 'Sarana & Prasarana', 'buah', 5, '2026-10-01 04:12:19', '2026-10-30 03:10:39'),
(14, 'Video Switch', 'Aten 2/4 Port VS-291', 'Sarana & Prasarana', 'unit', 2, '2026-10-01 04:12:19', '2026-10-30 03:20:24'),
(15, 'Video Splitter', 'Aten 2/4/8 Port VS-94A', 'Sarana & Prasarana', 'unit', 2, '2026-10-01 04:12:19', '2026-10-30 03:20:08'),
(16, 'Microphone + Kabel', 'Mic Shure BETA 58A', 'Sarana & Prasarana', 'pasang', 2, '2026-10-01 04:12:19', '2026-10-30 03:14:05'),
(17, 'Microphone + Kabel', 'KENWOOD NK-77K', 'Sarana & Prasarana', 'pasang', 2, '2026-10-01 04:12:19', '2026-10-30 03:13:46'),
(18, 'Microphone + Kabel', '-', 'Sarana & Prasarana', 'pasang', 2, '2026-10-01 04:12:19', '2026-10-30 03:14:37'),
(19, 'Adjustable height', 'X-Vision Extra Vision', 'Lainnya', 'buah', 0, '2026-10-01 04:12:19', '2026-10-30 03:09:38'),
(20, 'RJ45 Cat 5', NULL, 'Jaringan', 'set', 1, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(21, 'RJ45 Cat 6', NULL, 'Jaringan', 'set', 1, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(22, 'Switch 8 Port', 'TP-Link SG 108', 'Jaringan', 'unit', 3, '2026-10-01 04:12:19', '2026-10-30 03:23:01'),
(23, 'Switch 8 Port', 'TP-Link SG 1008', 'Jaringan', 'unit', 2, '2026-10-01 04:12:19', '2026-10-30 03:22:45'),
(24, 'Switch 8 Port', 'LS 108G', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:21:48'),
(25, 'Switch 16 Port', 'TP-Link SG 1016D', 'Jaringan', 'unit', 2, '2026-10-01 04:12:19', '2026-10-30 03:21:29'),
(26, 'Switch 16 Port', 'TP-Link SG 1016', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:21:07'),
(27, 'Switch 16 Port', 'D-Link 10/100', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:20:49'),
(28, 'Switch Wireless', 'EDIMAX Wireless 802 116/9', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:24:53'),
(29, 'CMOS', 'Camelion Lithium CR2032', 'Komponen PC', 'buah', 5, '2026-10-01 04:12:19', '2026-10-30 03:11:03'),
(30, 'Switch 8 Port', 'TP-Link SF 1008D', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:22:25'),
(31, 'Switch 8 Port', 'TL-SG 1008D', 'Jaringan', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:22:06'),
(32, 'RAM 4 GB', 'VGEN Platinum DDR3 - 12800U', 'Komponen PC', 'unit', 1, '2026-10-01 04:12:19', '2026-10-30 03:17:41'),
(33, 'Headset', 'Fantech CARBON 7.1 HG30 Black', 'Peripheral', 'unit', 2, '2026-10-29 07:53:04', '2026-10-30 03:54:52'),
(34, 'SSD NVME 500GB', '500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', 'Komponen PC', 'unit', 2, '2026-10-29 07:53:04', '2026-10-30 03:58:14'),
(36, 'RAM 8GB DDR4', 'Ripjaws DDR4 FA-3200C16D-16GVKR', 'Komponen PC', 'pasang', 2, '2026-10-29 07:53:04', '2026-10-30 04:40:25'),
(37, 'AC', 'Midea', 'Sarana & Prasarana', 'unit', 2, '2026-10-29 07:53:04', '2026-11-13 09:50:22'),
(41, 'RAM 4 GB', 'VGEN Platinum DDR3 - 10600U', 'Komponen PC', 'unit', 2, '2026-10-30 03:47:56', '2026-10-30 03:51:12'),
(43, 'PSU 550W', 'FSP VIC BD 550W', 'Komponen PC', 'unit', 2, '2026-10-30 03:47:56', '2026-10-30 03:52:13'),
(45, 'RAM 8GB DDR4', 'Tridenz DDR4 8GB 3200mz', 'Komponen PC', 'keping', 2, '2026-11-03 02:48:29', '2026-11-03 02:48:29'),
(50, 'Kipas Gantung', 'Ceiling', 'Sarana & Prasarana', 'unit', 2, '2026-11-03 07:03:48', '2026-11-13 09:51:12'),
(52, 'SSD SATA 512GB', 'Patriot P210', 'Komponen PC', 'buah', 2, '2026-11-12 05:41:18', '2026-11-13 09:49:40');

-- --------------------------------------------------------

--
-- Table structure for table `barang_tertinggals`
--

CREATE TABLE `barang_tertinggals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_ditemukan` date NOT NULL,
  `lab_nama` varchar(100) NOT NULL,
  `penemu` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `pemilik` varchar(255) DEFAULT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `tanggal_pengambilan` date DEFAULT NULL,
  `penyerah` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'belum_diambil',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_tertinggals`
--

INSERT INTO `barang_tertinggals` (`id`, `tanggal_ditemukan`, `lab_nama`, `penemu`, `nama_barang`, `keterangan`, `pemilik`, `no_hp`, `tanggal_pengambilan`, `penyerah`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(10, '2026-09-15', 'D2G', 'Rafa', 'Kunci Motor', 'Yamaha', 'Abdul Ghani', '083191042465', '2026-09-15', 'Rafa', 'sudah_diambil', NULL, '2026-09-15 02:00:00', '2026-10-01 04:12:20'),
(11, '2026-09-15', 'D2D', 'Imam', 'Tas', 'Hitam', 'Muhamad Rafa Al Abrar', '082147986362', '2026-09-15', 'Imam', 'sudah_diambil', NULL, '2026-09-15 02:00:00', '2026-10-01 04:12:20'),
(12, '2026-09-15', 'D3M', 'Lukman', 'Charger Handphone', 'Type C Putih', 'Muhammad Ammar', '083869621384', '2026-09-15', 'Akmal', 'sudah_diambil', NULL, '2026-09-15 02:00:00', '2026-10-01 04:12:20'),
(13, '2026-09-15', 'D2C', 'Ariel', 'Tas', 'Putih Gambar Beruang', 'Valencio Darriel P', '089658009511', '2026-09-16', 'Yunazriel', 'sudah_diambil', NULL, '2026-09-15 02:00:00', '2026-10-01 04:12:20'),
(14, '2026-09-17', 'D2K', 'Ariella', 'Dompet', 'Coklat', 'Jaisyllah Zadin Ridlo', '085801243240', '2026-09-17', 'Ariella', 'sudah_diambil', NULL, '2026-09-17 02:00:00', '2026-10-01 04:12:20'),
(15, '2026-09-18', 'D2E', 'Ferdynand', 'Handphone', 'Xiaomi', 'Farel Balda A.Z', '08835894899', '2026-09-18', 'Ferdynand', 'sudah_diambil', NULL, '2026-09-18 02:00:00', '2026-10-01 04:12:20'),
(16, '2026-09-21', 'KM Perempuan', 'CS Bagus', 'Botol Minum', 'Bening', NULL, NULL, '2026-10-01', 'M Niam', 'diumumkan', NULL, '2026-09-21 02:00:00', '2026-10-01 05:31:34'),
(17, '2026-09-21', 'D2E', 'Ferdynand', 'Kacamata', 'Hitam campur putih', NULL, NULL, '2026-10-01', 'M Niam', 'diumumkan', NULL, '2026-09-21 02:00:00', '2026-10-01 05:29:49'),
(18, '2026-09-21', 'D3M', 'Lukman', 'Charger Laptop', 'Lenovo', NULL, NULL, '2026-09-25', 'Mahasiswa', 'sudah_diambil', NULL, '2026-09-21 02:00:00', '2026-10-01 04:12:20'),
(19, '2026-09-22', 'D2D', 'Imam', 'Kunci Motor', NULL, NULL, NULL, '2026-10-01', 'M Niam', 'diumumkan', NULL, '2026-09-22 02:00:00', '2026-10-01 05:27:14'),
(20, '2026-09-22', 'D2J', 'Elsandro', 'STNK', NULL, 'Linda Fitriyani', '085640366332', '2026-09-22', 'Elsandro', 'sudah_diambil', NULL, '2026-09-22 02:00:00', '2026-10-01 04:12:20'),
(21, '2026-09-22', 'D2B', 'Octa', 'Kacamata', 'Motif Harimau', NULL, NULL, '2026-10-01', 'M Niam', 'diumumkan', NULL, '2026-09-22 02:00:00', '2026-10-01 05:27:02'),
(22, '2026-09-23', 'D3M', 'Lukman', 'Charger Laptop', 'Dell', 'Fadella Aly Ramadani', '083138029723', '2026-09-24', 'Akmal', 'sudah_diambil', NULL, '2026-09-23 02:00:00', '2026-10-01 04:12:20'),
(23, '2026-09-23', 'D2D', 'Imam', 'Buku Binder', NULL, NULL, NULL, '2026-10-01', 'M Niam', 'diumumkan', NULL, '2026-09-23 02:00:00', '2026-10-01 05:26:51'),
(30, '2026-09-24', 'D2D', 'Imam', 'Dompet', NULL, 'Galuh Armashinta Kinasih', '087868475132', '2026-09-24', 'Imam', 'sudah_diambil', NULL, '2026-09-24 02:00:00', '2026-10-01 04:12:20'),
(31, '2026-09-28', 'D2D', 'Zendry', 'TWS', 'Putih Thinkplus', 'Febrian Nur Fuad', '087831000220', '2026-09-28', 'Zendry', 'sudah_diambil', NULL, '2026-09-28 02:00:00', '2026-10-01 04:12:20'),
(32, '2026-09-29', 'Depan Lift D3', 'Laboran lt 3', 'Laptop', 'Lenovo yoga 6 + Charger', 'Intan Aprilia Fajriyani', '087823216889', '2026-09-29', 'Niam', 'sudah_diambil', NULL, '2026-09-29 02:00:00', '2026-10-01 04:12:20'),
(33, '2026-09-30', 'D2K', 'Ariella', 'Flashdisk', 'thinkbook warna abu abu', 'Siti Hafiizh Naafi Atul Ma\'wa', '081252397922', '2026-09-30', 'Reno', 'sudah_diambil', NULL, '2026-09-30 02:00:00', '2026-10-01 04:12:20'),
(34, '2026-09-30', 'D2J', 'Nanda', 'Tumbler', 'Rosca Navy', 'Muhammad Ammar Desvanda Putra', '083069621384', '2026-09-30', 'Calvin', 'sudah_diambil', NULL, '2026-09-30 02:00:00', '2026-10-01 04:12:20'),
(41, '2026-10-01', 'D2D', 'Imam', 'Charger Handphone', 'putih Type C', 'Candisa', '081366900657', '2026-10-02', 'Imam', 'sudah_diambil', NULL, '2026-10-02 03:35:00', '2026-10-02 06:22:26'),
(42, '2026-10-01', 'D2D', 'Imam', 'Jaket', 'Jeans Biru', 'Ahmad Fadlan Setiawan', '082326738744', '2026-10-01', 'Imam', 'sudah_diambil', NULL, '2026-10-02 03:38:38', '2026-10-02 03:38:38'),
(43, '2026-10-01', 'D2D', 'Zendry', 'Kacamata', 'Bening', 'Nurul Huda', '085162624030', '2026-10-08', 'Zendry', 'sudah_diambil', NULL, '2026-10-02 03:39:13', '2026-10-09 02:45:11'),
(44, '2026-10-02', 'D2A', 'Megan', 'Stopmap', 'Hitam', 'Calvin Samuel Simbolon', '081227426908', '2026-10-02', 'Reno', 'sudah_diambil', NULL, '2026-10-02 06:23:34', '2026-10-02 06:23:34'),
(45, '2026-10-02', 'D3N', 'Lukman', 'Flashdisk', 'KIOXIA warna putih, tanpa tutup, kapasitas 32GB', NULL, NULL, '2026-10-06', 'M Niam', 'diumumkan', NULL, '2026-10-05 01:35:42', '2026-10-06 02:13:56'),
(46, '2026-10-05', 'D2A', 'Megan', 'Flashdisk', 'Sandisk', NULL, NULL, '2026-10-12', 'M Niam', 'diumumkan', NULL, '2026-10-05 04:26:38', '2026-10-12 08:06:55'),
(47, '2026-10-06', 'D2C', 'Yunazriel', 'Charger Laptop', 'Lenovo 65W', 'Raafi Choiril Akhram', '0895425539292', '2026-10-06', 'Yunazriel', 'sudah_diambil', NULL, '2026-10-06 02:12:34', '2026-10-06 06:53:07'),
(48, '2026-10-05', 'D2B', 'Nanda', 'Handgrip', 'Trailtop Silver', 'Muhammad Ulil Albab', '082326784823', '2026-10-06', 'Niam', 'sudah_diambil', NULL, '2026-10-06 03:07:05', '2026-10-06 03:07:05'),
(49, '2026-10-06', 'D3M', 'Vibra', 'Flashdisk', 'Sandisk biru', 'Rossa Nabilla', '0895423465936', '2026-10-07', 'Lukman', 'sudah_diambil', NULL, '2026-10-06 03:57:00', '2026-10-09 06:44:46'),
(50, '2026-10-06', 'D2J', 'Elsandro', 'Lipcream', 'OMG Merah', NULL, NULL, '2026-10-12', 'M Niam', 'diumumkan', NULL, '2026-10-07 01:22:35', '2026-10-12 08:06:46'),
(51, '2026-10-06', 'D2C', 'Yunazriel', 'Dompet', 'Kartu Identitas, STNK, Uang', 'Raafi Choiril Akhram', '0895425539292', '2026-10-08', 'Ariel', 'sudah_diambil', NULL, '2026-10-07 01:23:43', '2026-10-09 02:44:10'),
(52, '2026-10-07', 'D2E', 'Zendry', 'Tumbler', 'Silver', 'Zulfaa Nur Fadhilah', '0895378099952', '2026-10-07', 'Zendry', 'sudah_diambil', NULL, '2026-10-07 07:42:18', '2026-10-07 07:42:18'),
(53, '2026-10-09', 'D2D', 'Zendry', 'Handphone', 'Iphone', 'Candisa Nataya', '081366900657', '2026-10-09', 'Imam', 'sudah_diambil', NULL, '2026-10-09 08:50:51', '2026-10-09 08:50:51'),
(54, '2026-10-12', 'D2E', 'Ferdynand', 'Topi', 'New York Hitam', NULL, NULL, '2026-10-26', 'Ferdynand', 'sudah_diambil', NULL, '2026-10-13 03:25:29', '2026-10-26 08:03:55'),
(55, '2026-10-13', 'D2D', 'Imam', 'Kacamata', 'Bening', 'Amilla Hanin Lutfiyani', '085797170449', '2026-10-19', 'Imam', 'sudah_diambil', NULL, '2026-10-13 06:18:02', '2026-10-19 05:04:29'),
(56, '2026-10-13', 'D3N', 'Lukman', 'TWS', 'Putih', 'Dion Kevin Sinaga', '085647820128', '2026-10-13', 'Lukman', 'sudah_diambil', NULL, '2026-10-13 06:19:13', '2026-10-15 00:05:33'),
(57, '2026-10-13', 'D2K', 'Reno', 'Charger Laptop', NULL, 'Bima Yoga Pratama', '082223759531', '2026-10-21', 'Reno', 'sudah_diambil', NULL, '2026-10-14 01:14:49', '2026-10-21 07:31:10'),
(58, '2026-10-14', 'D3N', 'Lukman', 'Buku Tulis', 'Race : berisi notasi algoritma', NULL, NULL, '2026-10-21', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-14 06:41:17', '2026-10-21 07:57:46'),
(59, '2026-10-15', 'D2K', 'Ariella', 'Mouse Wireless', 'Hitam', NULL, NULL, '2026-10-21', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-15 08:02:02', '2026-10-21 07:57:30'),
(60, '2026-10-14', 'Depan D2H', 'CS Mak Tin', 'Kaca', 'Bentuk bunga, gambar beruang, warna coklat', NULL, NULL, '2026-10-21', 'Admin Laboratorium', 'diumumkan', 'Barang berada di UPT', '2026-10-15 23:49:40', '2026-10-21 07:58:11'),
(61, '2026-10-16', 'D2A', 'Megan', 'Jam Tangan', 'Analog emas', 'Khirani Dwitarisha', '089636334674', '2026-10-21', 'Rafa', 'sudah_diambil', NULL, '2026-10-16 02:14:58', '2026-10-22 02:36:00'),
(62, '2026-10-16', 'D3N', 'Vibra', 'Handphone', 'Xiaomi Redmi Cream', 'Hana Septikasari', '081225810337', '2026-10-16', 'VIbra', 'sudah_diambil', NULL, '2026-10-16 05:27:12', '2026-11-23 04:04:39'),
(63, '2026-10-16', 'D2I', 'Ariel', 'Kacamata', 'Bening', 'Danish Arma Putra A', '089601491563', '2026-10-23', 'Ariel', 'sudah_diambil', NULL, '2026-10-19 00:37:03', '2026-10-26 04:46:14'),
(64, '2026-10-19', 'D2G', 'Rafa', 'Gelang', 'Gelang Salib dengan manik hitam', NULL, NULL, '2026-11-03', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-19 06:42:25', '2026-11-03 03:11:00'),
(65, '2026-10-19', 'D2D', 'Zendry', 'Flashdisk', 'Sandisk 16 GB', 'Vanesha Berliana', '082137130469', '2026-10-21', 'Imam', 'sudah_diambil', NULL, '2026-10-19 07:05:39', '2026-10-21 07:51:12'),
(66, '2026-10-19', 'D2D', 'Imam', 'Jaket', 'Abu abu', 'Alexsandrea Violeta Chelsea Nugroho', '08894664594', '2026-10-19', 'Zendry', 'sudah_diambil', NULL, '2026-10-19 07:17:20', '2026-10-19 07:24:41'),
(67, '2026-10-19', 'D2E', 'Ferdynand', 'Charger Handphone', 'Samsung mikro putih', 'Syafa Natasha', '08895031530', '2026-10-20', 'Zendry', 'sudah_diambil', NULL, '2026-10-19 09:42:18', '2026-10-20 04:26:24'),
(68, '2026-10-19', 'D2I', 'Ariel', 'Bedak', 'wardah lightening powder', NULL, NULL, '2026-11-03', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-19 09:43:39', '2026-11-03 03:10:46'),
(69, '2026-10-14', 'D2I', 'Calvin', 'Tumbler', 'Hitam', NULL, NULL, '2026-10-21', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-19 23:56:08', '2026-10-21 07:56:57'),
(70, '2026-10-20', 'D2K', 'Ariella', 'Charger Laptop', 'Asus 45 W', 'Romirza Putra Aditya', '089602734263', '2026-10-20', 'Ariella', 'sudah_diambil', NULL, '2026-10-20 01:27:39', '2026-10-20 04:27:18'),
(71, '2026-10-20', 'Sekitar KM D2', 'CS Bagus', 'Handphone', 'Xiaomi Redmi Hitam', 'Rayhan Diaz Wicaksana', '081325162247', '2026-10-20', 'Niam', 'sudah_diambil', NULL, '2026-10-20 05:36:11', '2026-10-20 08:27:54'),
(72, '2026-10-20', 'D2C', 'Yunazriel', 'SSD Eksternal', 'SATA', 'Valencia Darriel Prathama', '089658009511', '2026-10-21', 'Yunazriel', 'sudah_diambil', NULL, '2026-10-21 01:32:51', '2026-10-21 01:58:01'),
(73, '2026-10-20', 'D2C', 'Yunazriel', 'Charger Handphone', 'Hitam Type C', 'Danish Kenzil Mahija', '082146726529', '2026-10-21', 'Yunazriel', 'sudah_diambil', NULL, '2026-10-21 01:33:31', '2026-10-21 01:44:31'),
(74, '2026-10-20', 'D2C', 'Yunazriel', 'Earphone', 'Kabel pink', 'Helmi Afiq Meilana W', '085876726039', '2026-10-21', 'Yunazriel', 'sudah_diambil', NULL, '2026-10-21 01:34:21', '2026-10-21 01:43:34'),
(75, '2026-10-20', 'D2I', 'Ca', 'Earphone', 'Kabel hitam', 'Tangguh Idi Pangestu', '088214455047', '2026-10-23', 'Calvin', 'sudah_diambil', NULL, '2026-10-21 01:37:39', '2026-10-26 04:57:33'),
(76, '2026-10-21', 'D2I', 'Calvin', 'Cermin', NULL, NULL, NULL, '2026-11-03', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-21 03:44:07', '2026-11-03 03:10:36'),
(77, '2026-10-21', 'D3M', 'Akmal', 'Mouse wireless', 'Logitech Hitam', NULL, NULL, '2026-11-03', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-21 05:52:55', '2026-11-03 03:10:20'),
(78, '2026-10-21', 'D3N', 'Lukman', 'Flashdisk', 'Hitam', NULL, NULL, '2026-11-03', 'Admin Laboratorium', 'diumumkan', NULL, '2026-10-21 07:15:09', '2026-11-03 03:09:58'),
(79, '2026-10-22', 'Depan Lift D2', 'Niam', 'Tumbler', 'Pink Corak Bunga', 'Salwa Angzela Ramadlina', '0882003607512', '2026-10-22', 'Niam', 'sudah_diambil', NULL, '2026-10-22 05:09:55', '2026-10-22 05:09:55'),
(80, '2026-10-23', 'D2D', 'Zendry', 'Flashdisk', 'Putih gantungan anime', 'Asti Callista Andhani', '089660200020', '2026-10-28', 'Imam', 'sudah_diambil', NULL, '2026-10-26 01:30:02', '2026-10-28 08:16:54'),
(81, '2026-10-27', 'D2E', 'Zendry', 'Tumbler', 'Cream motif bunga', NULL, NULL, '2026-10-29', 'Ferdynand', 'sudah_diambil', NULL, '2026-10-27 08:34:56', '2026-10-29 01:18:30'),
(82, '2026-10-27', 'D2B', 'Zendry', 'Charger Handphone', 'Putih Type C', 'Anung Sasoheko P', '087785104719', '2026-10-27', 'Zendry', 'sudah_diambil', NULL, '2026-10-28 01:17:39', '2026-10-28 01:17:39'),
(83, '2026-10-29', 'D2E', 'Ferdynand', 'tempat alat tulis', 'Hitam', 'Zulfaa Nur Fadhilah', '0895378099952', '2026-11-04', 'Ferdynand', 'sudah_diambil', NULL, '2026-10-29 01:16:54', '2026-11-04 03:33:08'),
(84, '2026-11-02', 'D2H', 'Abdusalam', 'Kunci Motor', 'Honda Gantungan Tasbih', NULL, NULL, '2026-11-02', 'Abdusalam', 'sudah_diambil', NULL, '2026-11-02 08:03:35', '2026-11-03 02:39:27'),
(85, '2026-11-04', 'D2C', 'Ariel', 'OWS', 'JBL', 'Valencia Darriel Prathama', '089658009511', '2026-11-04', 'Ariel', 'sudah_diambil', NULL, '2026-11-04 08:52:44', '2026-11-04 08:52:44'),
(86, '2026-11-09', 'D2C', 'Niam', 'Tumbler', 'Hijau gantungan andong', 'Meila Wulandari', '081328742313', '2026-11-09', 'Niam', 'sudah_diambil', NULL, '2026-11-09 10:02:13', '2026-11-10 00:49:42'),
(87, '2026-11-12', 'D2B', 'Riza', 'Kalkulator', 'Casio Abu', 'Xocitel Putri Chrestella Islamadina', '081323968953', '2026-11-12', 'Niam', 'sudah_diambil', NULL, '2026-11-16 03:18:39', '2026-11-16 03:18:39'),
(88, '2026-11-16', 'D2G', 'Rafa', 'Kunci Motor', 'Honda gantungan orange', 'Agus Fa\'il Ijmu Silama', '085325796016', '2026-11-16', 'Rafa', 'sudah_diambil', NULL, '2026-11-17 01:20:53', '2026-11-17 01:20:53'),
(89, '2026-11-16', 'D2K', 'Ariella', 'Mousepad', 'Signo E-sport Hitam', 'Muhammad Khoiree Yakoh', '0895393908016', '2026-11-23', 'Ariella', 'sudah_diambil', NULL, '2026-11-17 01:22:35', '2026-11-23 07:15:14'),
(90, '2026-11-17', 'D2A', 'Zaenal', 'Stopkontak', 'Putih lubang 4', 'Arza Rizqi Prihardika L', '081229508490', '2026-11-17', 'Zaenal', 'sudah_diambil', NULL, '2026-11-17 05:17:10', '2026-11-17 05:17:10'),
(91, '2026-11-17', 'D3M', 'Akmal', 'Charger Laptop', 'SPC', 'Nadiva Amrina Rosyada', '083182904129', '2026-11-18', 'Akmal', 'sudah_diambil', NULL, '2026-11-19 00:04:17', '2026-11-19 00:04:17'),
(92, '2026-11-18', 'D2E', 'Ferdynand', 'Kertas Binder', NULL, 'Danish Ara Nabila', '089666998132', '2026-11-18', 'Ferdynand', 'sudah_diambil', NULL, '2026-11-19 00:06:35', '2026-11-19 00:06:35'),
(93, '2026-11-17', 'D2D', 'Imam', 'Dasi', 'SMA/SMK Biru', NULL, NULL, NULL, NULL, 'belum_diambil', NULL, '2026-11-19 00:07:48', '2026-11-19 00:07:48'),
(94, '2026-11-18', 'KM Wanita', 'CS Tin', 'Tumbler', 'Hijau', NULL, NULL, NULL, NULL, 'belum_diambil', NULL, '2026-11-19 00:08:42', '2026-11-19 00:08:42'),
(95, '2026-11-23', 'D2G', 'Richard', 'Charger Handphone', 'HP Ugreen Putih', 'Arum Qurrota A\'yun', '089515806784', '2026-11-24', 'Rafa', 'sudah_diambil', NULL, '2026-11-23 07:55:26', '2026-11-24 00:54:05'),
(96, '2026-11-23', 'Lorong D2', 'Zaenal', 'Tumbler', 'Hitam bermotif', NULL, NULL, NULL, NULL, 'belum_diambil', NULL, '2026-11-24 08:02:17', '2026-11-24 08:02:17'),
(97, '2026-11-24', 'D2G', 'Rafa', 'Handphone', 'Samsung Hitam', NULL, NULL, NULL, NULL, 'belum_diambil', NULL, '2026-11-24 08:02:45', '2026-11-24 08:02:45');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `distribusis`
--

CREATE TABLE `distribusis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laporan_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stok_gudang_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `asal_barang` enum('pengajuan','stok_lama') NOT NULL DEFAULT 'stok_lama',
  `jenis_distribusi` enum('laporan','mandiri') NOT NULL DEFAULT 'laporan',
  `kategori_alasan` varchar(100) DEFAULT NULL,
  `alasan_distribusi` varchar(500) DEFAULT NULL,
  `pengajuan_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lab_tujuan` varchar(10) NOT NULL,
  `kode_pc_tujuan` varchar(255) DEFAULT NULL,
  `diterima_oleh` varchar(255) DEFAULT NULL,
  `tanggal_distribusi` datetime NOT NULL,
  `tanggal_terima_lab` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distribusis`
--

INSERT INTO `distribusis` (`id`, `laporan_item_id`, `stok_gudang_id`, `jumlah`, `asal_barang`, `jenis_distribusi`, `kategori_alasan`, `alasan_distribusi`, `pengajuan_item_id`, `lab_tujuan`, `kode_pc_tujuan`, `diterima_oleh`, `tanggal_distribusi`, `tanggal_terima_lab`, `created_at`, `updated_at`) VALUES
(1, 31, 2, 2, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D3N', 'PC 17 & PC 36', 'Lukman', '2026-09-09 00:00:00', '2026-09-09', '2026-10-01 04:12:20', '2026-10-01 05:20:21'),
(2, 22, 1, 1, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2A', 'PC 35', 'Megan', '2026-09-09 00:00:00', '2026-09-09', '2026-10-01 04:12:20', '2026-10-01 05:20:09'),
(3, 22, 6, 5, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2A', 'PC 14, PC 15, PC 32, PC 35, PC 40', 'Megan', '2026-09-09 00:00:00', '2026-09-09', '2026-10-01 04:12:20', '2026-10-01 05:19:54'),
(4, 19, 4, 1, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2A', 'PC 11', 'Megan', '2026-09-09 00:00:00', '2026-09-09', '2026-10-01 04:12:20', '2026-10-01 05:19:04'),
(5, 34, 7, 1, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2A', 'PC 45', 'Megan', '2026-09-09 00:00:00', '2026-09-09', '2026-10-01 04:12:20', '2026-10-01 05:18:45'),
(6, 9, 7, 1, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2J', 'PC 11', 'Nanda', '2026-09-10 00:00:00', '2026-09-10', '2026-10-01 04:12:20', '2026-10-01 05:18:34'),
(7, 7, 8, 1, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D3L', 'PC 17', 'Zaenal', '2026-09-10 00:00:00', '2026-09-10', '2026-10-01 04:12:20', '2026-10-01 05:18:21'),
(8, 3, 29, 3, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2D', 'PC 35, PC 6, PC 9', 'Imam', '2026-09-11 00:00:00', '2026-09-11', '2026-10-01 04:12:20', '2026-10-01 05:18:01'),
(9, 23, 33, 2, 'pengajuan', 'laporan', NULL, NULL, 4, 'D2B', 'B01, B02', 'Nanda', '2026-10-30 16:03:00', '2026-10-30', '2026-10-30 09:07:49', '2026-11-02 03:56:16'),
(10, 15, 33, 3, 'pengajuan', 'laporan', NULL, NULL, 4, 'D2B', 'B03, B04, B05', 'Nanda', '2026-10-30 16:09:00', '2026-10-30', '2026-10-30 09:10:48', '2026-11-02 03:56:05'),
(11, 41, 37, 2, 'pengajuan', 'laporan', NULL, NULL, 10, 'D2H', '2 AC kanan kiri', 'Wawan', '2026-11-02 10:52:00', '2026-11-02', '2026-11-02 03:55:12', '2026-11-02 03:55:12'),
(12, 31, 1, 2, 'pengajuan', 'laporan', NULL, NULL, 3, 'D3N', 'N26 dan N29', 'Lukman', '2026-11-02 11:14:00', '2026-11-02', '2026-11-02 04:15:40', '2026-11-02 07:12:55'),
(13, 42, 13, 2, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D3M', 'Remote AC', 'Lukman', '2026-11-02 14:10:00', '2026-11-02', '2026-11-02 07:11:37', '2026-11-02 07:12:49'),
(14, 4, 1, 1, 'pengajuan', 'laporan', NULL, NULL, 3, 'D2J', 'J03', 'Nanda', '2026-11-02 14:14:00', '2026-11-02', '2026-11-02 07:15:20', '2026-11-02 07:16:57'),
(15, 24, 1, 1, 'pengajuan', 'laporan', NULL, NULL, 3, 'D2J', 'J09', 'Nanda', '2026-11-02 14:15:00', '2026-11-02', '2026-11-02 07:16:04', '2026-11-02 07:16:54'),
(16, 5, 3, 2, 'stok_lama', 'laporan', NULL, NULL, NULL, 'D2J', 'J03 & J11', 'Nanda', '2026-11-02 14:16:00', '2026-11-02', '2026-11-02 07:16:46', '2026-11-02 07:16:51'),
(17, 33, 1, 2, 'pengajuan', 'laporan', NULL, NULL, 3, 'D3L', 'L26 & L27', 'Iqbal', '2026-11-02 14:27:00', '2026-11-02', '2026-11-02 07:28:25', '2026-11-02 07:31:11'),
(18, 26, 41, 1, 'pengajuan', 'laporan', NULL, NULL, 6, 'D3L', 'L39', 'Iqbal', '2026-11-02 14:41:00', '2026-11-02', '2026-11-02 07:42:36', '2026-11-02 07:42:44'),
(19, 25, 1, 5, 'pengajuan', 'laporan', NULL, NULL, 3, 'D2H', 'H02, H03, H05, H17, H18', 'Wawan', '2026-11-03 08:28:00', '2026-11-03', '2026-11-03 01:30:06', '2026-11-03 01:30:11'),
(20, 21, 1, 5, 'pengajuan', 'laporan', NULL, NULL, 3, 'D2H', 'H19, H20, H24, H27, H13', 'Wawan', '2026-11-03 08:30:00', '2026-11-03', '2026-11-03 01:31:22', '2026-11-03 01:31:26'),
(21, 27, 36, 1, 'pengajuan', 'laporan', NULL, NULL, 7, 'D2D', 'Laboran', 'Imam', '2026-11-03 09:59:00', '2026-11-03', '2026-11-03 03:00:19', '2026-11-03 03:00:23'),
(22, 18, 34, 1, 'pengajuan', 'laporan', NULL, NULL, 5, 'D2D', 'Laboran', 'Imam', '2026-11-03 14:16:00', '2026-11-03', '2026-11-03 07:18:15', '2026-11-03 07:18:29'),
(23, 8, 33, 1, 'pengajuan', 'laporan', NULL, NULL, 4, 'D2D', 'D26', 'Imam', '2026-11-03 14:20:00', '2026-11-03', '2026-11-03 07:21:07', '2026-11-03 07:21:07'),
(24, 17, 33, 1, 'pengajuan', 'laporan', NULL, NULL, 4, 'D2D', 'D36', 'Imam', '2026-11-03 14:21:00', '2026-11-03', '2026-11-03 07:21:43', '2026-11-03 07:21:43'),
(25, 40, 33, 2, 'pengajuan', 'laporan', NULL, NULL, 4, 'D2D', 'D37 & D41', 'Imam', '2026-11-03 14:21:00', '2026-11-03', '2026-11-03 07:22:25', '2026-11-03 07:22:25'),
(26, NULL, 34, 2, 'pengajuan', 'mandiri', 'Upgrade Perangkat Lab', 'Pembuatan master baru', 11, 'D2I', NULL, 'Rafa', '2026-11-13 13:00:00', '2026-11-13', '2026-11-13 09:39:08', '2026-11-13 09:39:08'),
(27, NULL, 52, 2, 'pengajuan', 'mandiri', 'Upgrade Perangkat Lab', 'Pembuatan master windows 10', 12, 'D2J', NULL, 'Nanda', '2026-11-12 15:41:00', '2026-11-12', '2026-11-13 09:43:15', '2026-11-13 09:43:15'),
(28, NULL, 52, 2, 'pengajuan', 'mandiri', 'Upgrade Perangkat Lab', 'Pembuatan master windows 10', 12, 'D2K', NULL, 'Reno', '2026-11-12 15:43:00', '2026-11-12', '2026-11-13 09:44:22', '2026-11-13 09:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_nama` varchar(10) NOT NULL,
  `bentuk_ketidaksesuaian` varchar(255) DEFAULT NULL,
  `tanggal_kejadian` date DEFAULT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `jabatan_pelapor` varchar(255) DEFAULT NULL,
  `tanggal_laporan` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `catatan_umum` text DEFAULT NULL,
  `uraian_pengamatan` text DEFAULT NULL,
  `tindakan_langsung` text DEFAULT NULL,
  `permintaan_tindakan` text DEFAULT NULL,
  `status` enum('dicatat','ditriase','dalam_proses','menunggu_keputusan_divisi','barang_diterima','siap_distribusi','selesai','ditolak','perlu_revisi') NOT NULL DEFAULT 'dicatat',
  `status_akhir` enum('closed','follow_up') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `lab_nama`, `bentuk_ketidaksesuaian`, `tanggal_kejadian`, `nama_pelapor`, `bagian`, `jabatan_pelapor`, `tanggal_laporan`, `tanggal_selesai`, `catatan_umum`, `uraian_pengamatan`, `tindakan_langsung`, `permintaan_tindakan`, `status`, `status_akhir`, `created_at`, `updated_at`) VALUES
(2, 'D2A', 'PSU Rusak', '2026-01-06', 'Harry', NULL, 'Laboran Magang', '2026-01-06', '2026-09-10', 'Didistribusikan 1 unit ke D2A (PC 45) tgl 09/09/2026 14:25 (Total terpenuhi: 1/1 unit).', NULL, 'Pemindahan PSU dari PSU PC laboran ke PC Client', 'Pengajuan PSU', 'selesai', NULL, '2026-01-06 01:00:00', '2026-09-10 09:00:00'),
(3, 'D3L', 'Mouse kurang responsif', '2026-01-07', 'Zaenal', NULL, 'Laboran Kontrak', '2026-01-07', '2026-11-02', NULL, NULL, NULL, 'Pengajuan 2 Mouse', 'selesai', NULL, '2026-01-07 01:00:00', '2026-11-02 07:29:08'),
(4, 'D2B', 'Webcam Rusak', '2026-01-29', 'Octa', NULL, 'Laboran Magang', '2026-01-29', NULL, NULL, NULL, NULL, 'Pengajuan webcam untuk PC 22 & 30', 'dicatat', NULL, '2026-01-29 01:00:00', '2026-10-01 04:12:20'),
(5, 'D2E', 'Kursi jebol', '2026-02-04', 'Silvanus', NULL, 'Laboran Magang', '2026-02-04', NULL, NULL, NULL, 'Melakukan pengecekan bagian yang jebol', 'Pengadaan 1 Kursi baru', 'ditriase', NULL, '2026-02-04 01:00:00', '2026-10-01 04:12:20'),
(6, 'D3N', '4 Mouse tidak responsif', '2026-02-03', 'Faqih', NULL, 'Laboran Kontrak', '2026-02-04', '2026-11-02', 'Didistribusikan sebagian: 2 dari 4 unit ke D3N (PC 17 & PC 36) tgl 2026-09-09. Sisa 2 unit belum terdistribusi.', NULL, NULL, 'Pengadaan Mouse baru 4 unit', 'selesai', NULL, '2026-02-04 01:00:00', '2026-11-02 04:16:21'),
(7, 'D2A', 'Mouse rusak klik kanan dan kiri macet', '2026-02-07', 'Megan', NULL, 'Laboran Magang', '2026-03-02', '2026-09-10', 'Didistribusikan 1 unit ke D2A (PC 35) tgl 2026-09-09 (Total terpenuhi: 1/1 unit).', NULL, NULL, 'Pengajuan mouse baru berjumlah 1', 'selesai', NULL, '2026-03-02 01:00:00', '2026-09-10 09:00:00'),
(8, 'D2H', 'Layar Proyektor Kotor', '2026-03-03', 'Richard', NULL, 'Laboran Magang', '2026-03-03', NULL, NULL, NULL, 'Sudha dibersihkan tapi sama saja', 'Penggantian layar proyektor baru', 'dicatat', NULL, '2026-03-03 01:00:00', '2026-10-01 04:12:20'),
(9, 'D2D', 'BSOD', '2026-02-24', 'Imam', NULL, 'Laboran Kontrak', '2026-03-04', '2026-11-03', NULL, NULL, NULL, 'Pengadaan RAM 1 pcs DDR 4 untuk melengkapi RAM yang sudah rusak', 'selesai', NULL, '2026-03-04 01:00:00', '2026-11-03 03:00:45'),
(10, 'D3L', '2 RAM Rusak', '2026-03-06', 'Zaenal', NULL, 'Laboran Kontrak', '2026-03-06', '2026-11-02', NULL, NULL, 'Menganalisis RAM rusak dan tidak terbaca dan sudah mengganti RAM PC sebelah', 'Pengadaan 2 RAM 2GB DDR3', 'selesai', NULL, '2026-03-06 01:00:00', '2026-11-02 07:45:18'),
(11, 'D2H', 'Mouse Rusak', '2026-03-10', 'Wawan', NULL, 'Laboran Tetap', '2026-03-10', '2026-11-03', NULL, NULL, NULL, 'Mengganti mouse baru 5 buah', 'selesai', NULL, '2026-03-10 01:00:00', '2026-11-03 01:32:07'),
(12, 'D2J', 'Mouse klik kanan tidak berfungsi', '2026-03-26', 'Ayu', NULL, 'Laboran Magang', '2026-03-27', '2026-11-02', NULL, NULL, 'Digantikan sementara menggunakan mouse PC laboran', 'Pengadaan mouse baru berjumlah 1', 'selesai', NULL, '2026-03-27 01:00:00', '2026-11-02 07:17:26'),
(13, 'D2B', 'Headset Rusak', '2026-03-31', 'Octa', NULL, 'Laboran Magang', '2026-03-31', '2026-11-02', NULL, NULL, 'Diganti dari laboran', 'Pengadaan 2 Headset', 'selesai', NULL, '2026-03-31 01:00:00', '2026-11-02 03:58:52'),
(14, 'D2H', 'Mouse Rusak', '2026-04-07', 'Richard', NULL, 'Laboran Magang', '2026-04-07', '2026-11-03', NULL, NULL, NULL, 'Penggantian mouse baru 5 unit', 'selesai', NULL, '2026-04-07 01:00:00', '2026-11-03 01:31:54'),
(15, 'D2A', 'Mousepad sudah tidak layak dipakai', '2026-04-02', 'Megan', NULL, 'Laboran Magang', '2026-04-06', '2026-09-10', 'Didistribusikan 5 unit ke D2A (PC 14, PC 15, PC 32, PC 35, PC 40) tgl 09/09/2026 14:21 (Total terpenuhi: 5/5 unit).', NULL, NULL, 'Pengajuan mousepad 5', 'selesai', NULL, '2026-04-06 01:00:00', '2026-09-10 09:00:00'),
(16, 'D2I', 'Mouse Macet', '2026-04-10', 'Calvin', NULL, 'Laboran Magang', '2026-04-10', '2026-10-30', NULL, NULL, NULL, 'Pengajuan Mouse baru', 'selesai', NULL, '2026-04-10 01:00:00', '2026-10-30 04:16:43'),
(17, 'D2A', 'Headset Rusak', '2026-04-09', 'Megan', NULL, 'Laboran Magang', '2026-04-13', NULL, 'Didistribusikan sebagian: 1 unit ke D2A (PC 11) tgl 09/09/2026 14:24 (Progres: 1/5 unit, Sisa: 4 unit).', NULL, NULL, 'Pengajuan Headset baru 5 untuk kelas editing video', 'ditriase', NULL, '2026-04-13 01:00:00', '2026-10-01 04:12:20'),
(18, 'D2D', 'Headset putus, SSD mengisi yang kosong', '2026-04-16', 'Imam', NULL, 'Laboran Kontrak', '2026-04-16', '2026-11-03', NULL, NULL, NULL, 'Pengadaan Headset dan SSD NVME', 'selesai', NULL, '2026-04-16 01:00:00', '2026-11-03 07:31:41'),
(19, 'D3L', 'Looping Switch', '2026-05-08', 'Zaenal', NULL, 'Laboran Kontrak', '2026-05-08', '2026-09-09', NULL, NULL, 'Perbaikan pengkabelan', 'butuh konektor RJ45 4 buah', 'selesai', NULL, '2026-05-08 01:00:00', '2026-09-09 09:00:00'),
(20, 'D2B', 'Headset Mati', '2026-05-29', 'Octa', NULL, 'Laboran Magang', '2026-05-29', '2026-11-02', NULL, NULL, NULL, 'Pengadaan 3 headset', 'selesai', NULL, '2026-05-29 01:00:00', '2026-11-02 03:59:11'),
(21, 'D2K', 'Kursi patah', '2026-06-27', 'Ariella', NULL, 'Laboran Magang', '2026-06-30', NULL, NULL, NULL, 'Pengembalian kursi patah ke gudang atas', 'Pengadaan kursi', 'ditriase', NULL, '2026-06-30 01:00:00', '2026-10-01 04:12:19'),
(22, 'D2C', 'Webcam Garis-Garis', '2026-05-10', 'Yunazriel', NULL, 'Laboran Magang', '2026-07-08', NULL, NULL, NULL, 'Sudah dicoba cabut colok dan sudah dilakukan pembersihan pada lensa hasilnya masih sama saja', 'Pengadaan 1 unit webcam', 'dicatat', NULL, '2026-07-08 01:00:00', '2026-10-01 04:12:19'),
(23, 'D2J', 'Port PSU Kendor', '2026-07-08', 'Nanda', NULL, 'Laboran Kontrak', '2026-07-10', '2026-09-10', NULL, NULL, 'Mencari posisi yang optimal', 'Perlu adanya kabel PSU baru sebanyak 5', 'selesai', NULL, '2026-07-10 01:00:00', '2026-09-10 09:00:00'),
(24, 'D2B', 'Headset dan Webcam', '2026-07-30', 'Octa', NULL, 'Laboran Magang', '2026-07-30', NULL, NULL, NULL, 'Mengganti dengan yang tidak dipakai', 'Pengadaan 13 Headset dan 2 Webcam', 'ditriase', NULL, '2026-07-30 01:00:00', '2026-10-01 04:12:19'),
(25, 'D2J', 'PSU Rusak', '2026-08-10', 'Nanda', NULL, 'Laboran Kontrak', '2026-08-10', '2026-09-10', 'Didistribusikan 1 unit ke D2J (PC 11) tgl 10/09/2026 14:33 (Total terpenuhi: 1/1 unit).', NULL, 'Pergantian kabel power tidak berpengaruh', 'Pergantian PSU Baru', 'selesai', NULL, '2026-08-10 01:00:00', '2026-09-10 09:00:00'),
(26, 'D2D', 'Kabel Headset putus', '2026-08-14', 'Imam', NULL, 'Laboran Kontrak', '2026-08-18', '2026-11-03', NULL, NULL, NULL, 'Pengadaan Headset baru', 'selesai', NULL, '2026-08-18 01:00:00', '2026-11-03 07:31:15'),
(27, 'D2K', 'Kerusakan Kabel Power Monitor', '2025-09-23', 'Reno', NULL, 'Laboran Magang', '2025-09-23', NULL, NULL, NULL, 'Mengganti dengan kabel power dan monitor cadangan di lab', 'Pengadaan 1 kabel power Monitor (Switching Adapter)\r\nLG \r\nModel : ADS-18SG-19-3 Max 0,6A\r\nInput : 100-240V = 50/100 Hz\r\nOutput : 19V = 0,84A', 'dicatat', NULL, '2025-09-23 01:00:00', '2026-10-01 04:12:20'),
(28, 'D2A', 'Kursi jebol', '2025-10-15', 'Harry', NULL, 'Laboran Magang', '2025-10-16', NULL, NULL, NULL, NULL, 'Penggantian/perbaikan 2 kursi', 'dicatat', NULL, '2025-10-16 01:00:00', '2026-10-01 04:12:20'),
(29, 'D2C', 'Kursi Rusak', NULL, 'Lukman', NULL, 'Laboran Magang', '2025-12-09', NULL, NULL, NULL, 'Mengganti kursi dengan cadangan', 'Perbaikan/penggantian Kursi', 'dicatat', NULL, '2025-12-09 01:00:00', '2026-10-01 04:12:20'),
(30, 'D3L', 'HDD Client Not Responding', '2026-09-08', 'Zaenal', NULL, 'Laboran Kontrak', '2026-09-08', '2026-09-10', 'Didistribusikan 1 unit ke D3L (PC 17) tgl 10/09/2026 15:11 (Total terpenuhi: 1/1 unit).', NULL, 'Sudah dilakukan percobaan dengan 2 Harddisk yang normal (kesehatan 100%) ternyata memang harus diganti dengan yang normal', 'Pengadaan HDD baru dengan kapasitas 512', 'selesai', NULL, '2026-09-08 01:00:00', '2026-09-10 09:00:00'),
(32, 'D2J', 'Mouse dan Keyboard Tidak Nyaman', '2026-09-10', 'Nanda', NULL, 'Laboran Kontrak', '2026-09-10', '2026-11-02', NULL, NULL, 'Memastikan hardware berfungsi tidak semestinya', 'Pergantian hardware berupa mouse 1 dan Keyboard 2', 'selesai', NULL, '2026-09-10 01:00:00', '2026-11-02 07:17:57'),
(33, 'D3M', 'Converter Rusak', '2026-09-10', 'Akmal', NULL, 'Laboran Magang', '2026-09-10', NULL, NULL, NULL, 'Menghubungkan output langsung tanpa converter secara bergantian', 'Melakukan penggantian Converter HDMI to VGA', 'dicatat', NULL, '2026-09-10 01:00:00', '2026-10-01 04:12:19'),
(34, 'D2D', 'Baterai CMOS Habis', '2026-09-11', 'Imam', NULL, 'Laboran Kontrak', '2026-09-11', '2026-10-01', 'Didistribusikan 3 unit ke D2D (PC 35, PC 6, PC 9) tgl 11/09/2026 12:58 (Total terpenuhi: 3/3 unit).', NULL, 'Mengecek BIOS dan menyetel ulang', 'Mengganti Baterai CMOS sebanyak 3 Pcs yang sudah habis dengan yang baru', 'selesai', NULL, '2026-09-11 01:00:00', '2026-10-01 05:07:15'),
(35, 'D2G', 'Baut Kursi Hilang', '2026-09-28', 'Richard', NULL, 'Laboran Magang', '2026-09-28', '2026-09-30', NULL, NULL, NULL, NULL, 'selesai', NULL, '2026-09-28 01:00:00', '2026-09-30 09:00:00'),
(36, 'D3M', '4 Baut Kursi hilang', '2026-09-30', 'Lukman', NULL, 'Laboran Kontrak', '2026-09-30', '2026-09-30', NULL, NULL, NULL, NULL, 'selesai', NULL, '2026-09-30 01:00:00', '2026-09-30 09:00:00'),
(39, 'D2D', 'Headset mengalami putus kabel', '2026-09-10', 'Imam', NULL, 'Laboran Kontrak', '2026-10-30', '2026-11-03', NULL, '2 Headset bernomor PC 36 dan 41 mengalami putus kabel. sehingga perlu diganti dengan yang baru', 'Mencopot dan menyimpan headset dari PC terkait', 'Mengajukan Penggantian Headset yang rusak dengan yang baru berjumlah 2 pcs untuk PC no 36 dan 41', 'selesai', NULL, '2026-10-30 04:44:24', '2026-11-03 07:31:00'),
(40, 'D2H', 'AC Bocor', '2026-01-10', 'Wawan', NULL, 'Laboran Tetap', '2026-04-13', '2026-11-03', NULL, 'AC sering bocor', 'Diperbaiki oleh Teknisi', 'Teknisi menyarankan ganti kompresor atau AC baru', 'selesai', NULL, '2026-11-02 03:50:51', '2026-11-03 01:31:44'),
(41, 'D3M', 'Baterai AAA hilang', '2026-10-19', 'Lukman', NULL, 'Laboran Kontrak', '2026-11-02', '2026-11-02', NULL, 'Baterai pada remote AC setelah permbersihan AC hilang, setelah dicek tidak menggunakan baterai charge', 'menggunakan baterai sekali pakai', 'pengajuan baterai aaa charge 2 buah', 'selesai', NULL, '2026-11-02 07:10:40', '2026-11-02 07:12:12'),
(42, 'D2A', 'AC mengeluarkan air', '2026-09-29', 'Megan', NULL, 'Laboran Magang', '2026-09-29', '2026-11-03', NULL, 'AC didekat meja laboran selalu mengeluarkan kristal es dan menetes airnya', 'Melaporkan AC menetes untuk segera di service', 'Perbaikan AC oleh teknisi', 'selesai', NULL, '2026-11-03 07:43:34', '2026-11-03 07:45:16'),
(43, 'D2B', 'Keramik lantai rusak', '2026-10-21', 'Nanda', NULL, 'Laboran Kontrak', '2026-10-21', '2026-11-03', NULL, 'Keramik lantai pecah', 'Melaporkan kerusakan', 'Perbaikan lantai oleh pihak terkait', 'selesai', NULL, '2026-11-03 07:47:03', '2026-11-03 07:49:02'),
(44, 'D2D', 'Lampu berkedip', '2026-10-23', 'Imam', NULL, 'Laboran Kontrak', '2026-10-23', '2026-11-03', NULL, 'Salah satu dari lampu lab D2D berkedip secara terus menerus', 'Melaporkan ke teknisi untuk dilakukan perbaikan langsung', 'Teknisi mengganti dengan lampu baru', 'selesai', NULL, '2026-11-03 07:50:45', '2026-11-03 07:51:29'),
(45, 'D2A', 'Lampu berkedip', '2026-10-30', 'Megan', NULL, 'Laboran Magang', '2026-10-30', '2026-11-03', NULL, 'Lampu tengah satu biji setiap dinyalakan selalu berkedip', 'laporan lampu rusak berkedip terus', 'Perbaikan lampu oleh teknisi', 'selesai', NULL, '2026-11-03 08:05:19', '2026-11-03 08:05:47'),
(46, 'D2E', 'AC Mati', '2026-11-03', 'Ferdynand', NULL, 'Laboran Magang', '2026-11-03', '2026-11-04', NULL, 'AC disisi kiri dalam keadaan mati, tidak bisa merespon remote AC, walau kondisi MCB telah on', NULL, 'Pengecekan dan perbaikan oleh teknisi', 'selesai', NULL, '2026-11-04 03:34:47', '2026-11-04 03:36:06'),
(47, 'D2D', 'AC meneteskan air', '2026-11-10', 'Imam', NULL, 'Laboran Kontrak', '2026-11-10', '2026-11-11', NULL, 'AC sebelah stimur meneteskan air', 'Mematikan AC yang bocor agar tidak mengganggu kelas', 'Melaporkan kepada teknisi', 'selesai', NULL, '2026-11-11 03:28:24', '2026-11-11 03:30:31'),
(48, 'D3N', 'Lampu mati', '2026-11-10', 'Vibra', NULL, 'Laboran Magang', '2026-11-16', '2026-11-17', NULL, 'Lampu Berkedip kemudian mati', NULL, NULL, 'selesai', NULL, '2026-11-17 01:43:07', '2026-11-17 01:44:16'),
(49, 'D2C', 'AC Bocor', '2026-11-16', 'Lukman', NULL, 'Laboran Kontrak', '2026-11-16', '2026-11-17', NULL, 'AC sebelah kanan (meja dosen) mengalami kebocoran dan mengeluarkan banyak air', 'Mematikan AC tersebut', 'menghubungi teknisi untuk melakukan perbaikan', 'selesai', NULL, '2026-11-17 01:46:26', '2026-11-17 01:50:38'),
(50, 'D3M', 'Lampu berkedip', '2026-11-16', 'Akmal', NULL, 'Laboran Magang', '2026-11-16', '2026-11-17', NULL, 'Terjadi lampu berkedip parah pada lampu tengah kanan. lampu berkedip sampai mati salah satu', NULL, NULL, 'selesai', NULL, '2026-11-17 01:48:33', '2026-11-17 01:49:26'),
(51, 'D3N', 'Keramik lantai rusak', '2026-11-17', 'Vibra', NULL, 'Laboran Magang', '2026-11-17', '2026-11-18', NULL, 'Keramik lantai mengalami memuai hingga pecah', NULL, 'Mohon perbaikan ke pihak terkait', 'selesai', NULL, '2026-11-18 02:05:21', '2026-11-18 02:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_items`
--

CREATE TABLE `laporan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `satuan` varchar(50) NOT NULL DEFAULT 'unit',
  `jenis_tindak_lanjut` varchar(50) NOT NULL DEFAULT 'pending',
  `keterangan` text DEFAULT NULL,
  `status_item` enum('menunggu_triase','proses_perbaikan','selesai_perbaikan','menunggu_pengadaan','terdistribusi','selesai') NOT NULL DEFAULT 'menunggu_triase',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_items`
--

INSERT INTO `laporan_items` (`id`, `laporan_id`, `nama_barang`, `deskripsi`, `jumlah`, `satuan`, `jenis_tindak_lanjut`, `keterangan`, `status_item`, `created_at`, `updated_at`) VALUES
(1, 36, 'Baut', NULL, 4, 'buah', 'penggantian', NULL, 'selesai', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(2, 35, 'Baut', NULL, 41, 'buah', 'penggantian', NULL, 'selesai', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(3, 34, 'CMOS', 'Habis Power', 3, 'unit', 'penggantian', 'Didistribusikan 3 unit ke D2D (PC 35, PC 6, PC 9) tgl 11/09/2026 12:58 (Total terpenuhi: 3/3 unit).', 'selesai', '2026-10-01 04:12:19', '2026-10-01 05:07:15'),
(4, 32, 'Mouse', 'Klik kanan susah', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2J (J03) tgl 02/11/2026 14:14 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:19', '2026-11-02 07:17:50'),
(5, 32, 'Keyboard', 'Enter dan Space tidak nyaman', 2, 'unit', 'penggantian', 'Didistribusikan 2 unit ke D2J (J03 & J11) tgl 02/11/2026 14:16 (Total terpenuhi: 2/2 unit).', 'selesai', '2026-10-01 04:12:19', '2026-11-02 07:17:57'),
(6, 33, 'Converter HDMI to VGA', 'tidak berfungsi', 1, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(7, 30, 'HDD', 'Not Responding dan Kesehatan 60%', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D3L (PC 17) tgl 10/09/2026 15:11 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(8, 26, 'Headset', 'Kabel putus', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2D (D26) tgl 03/11/2026 14:20 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:19', '2026-11-03 07:31:15'),
(9, 25, 'PSU', 'Tidak menyalurkan listrik', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2J (PC 11) tgl 10/09/2026 14:33 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(10, 24, 'Headset', 'Kabel terbuka dan barang tidak ada', 13, 'unit', 'penggantian', NULL, 'selesai', '2026-10-01 04:12:19', '2026-11-02 06:51:58'),
(11, 24, 'Webcam', NULL, 2, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(12, 23, 'Kabel PSU', 'Kendor', 5, 'unit', 'penggantian', NULL, 'selesai', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(13, 22, 'Webcam', 'Meanmpilkan Garis hijau', 1, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(14, 21, 'Kursi', 'Patah', 1, 'unit', 'penggantian', NULL, 'menunggu_pengadaan', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(15, 20, 'Headset', NULL, 3, 'unit', 'penggantian', 'Didistribusikan 3 unit ke D2B (B03, B04, B05) tgl 30/10/2026 16:09 (Total terpenuhi: 3/3 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 03:59:11'),
(16, 19, 'RJ45', NULL, 1, 'unit', 'perbaikan', NULL, 'selesai_perbaikan', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(17, 18, 'Headset', 'Kabel putus', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2D (D36) tgl 03/11/2026 14:21 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-03 07:31:34'),
(18, 18, 'SSD NVME', NULL, 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2D (Laboran) tgl 03/11/2026 14:16 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-03 07:31:41'),
(19, 17, 'Headset', 'rusak', 5, 'unit', 'penggantian', 'Didistribusikan sebagian: 1 unit ke D2A (PC 11) tgl 09/09/2026 14:24 (Progres: 1/5 unit, Sisa: 4 unit).', 'menunggu_pengadaan', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(20, 16, 'Mouse', 'macet', 1, 'unit', 'penggantian', NULL, 'selesai', '2026-10-01 04:12:20', '2026-10-30 04:16:43'),
(21, 14, 'Mouse', NULL, 5, 'unit', 'penggantian', 'Didistribusikan 5 unit ke D2H (H19, H20, H24, H27, H13) tgl 03/11/2026 08:30 (Total terpenuhi: 5/5 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-03 01:31:54'),
(22, 15, 'Mousepad', 'Sudah tidak layak dan mengelupas', 5, 'unit', 'penggantian', 'Didistribusikan 5 unit ke D2A (PC 14, PC 15, PC 32, PC 35, PC 40) tgl 09/09/2026 14:21 (Total terpenuhi: 5/5 unit).', 'selesai', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(23, 13, 'Headset', 'rusak/patah', 2, 'unit', 'penggantian', 'Didistribusikan 2 unit ke D2B (B01, B02, B03, B04, B05) tgl 30/10/2026 16:03 (Total terpenuhi: 2/2 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 03:58:52'),
(24, 12, 'Mouse', 'Klik kanan tidak berfungsi', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2J (J09) tgl 02/11/2026 14:15 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 07:17:26'),
(25, 11, 'Mouse', 'Klik kanan double', 5, 'unit', 'penggantian', 'Didistribusikan 5 unit ke D2H (H02, H03, H05, H17, H18) tgl 03/11/2026 08:28 (Total terpenuhi: 5/5 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-03 01:32:07'),
(26, 10, 'RAM 2GB DDR3', NULL, 2, 'unit', 'penggantian', 'Didistribusikan sebagian: 1 unit ke D3L (L39) tgl 02/11/2026 14:41 (Progres: 1/2 unit, Sisa: 1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 07:45:18'),
(27, 9, 'RAM DDR4', NULL, 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2D (Laboran) tgl 03/11/2026 09:59 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-03 03:00:45'),
(28, 8, 'Layar Proyektor', 'Kotor', 1, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(29, 7, 'Mouse', 'rusak', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2A (PC 35) tgl 2026-09-09 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(30, 5, 'Kursi', 'jebol', 1, 'unit', 'penggantian', NULL, 'menunggu_pengadaan', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(31, 6, 'Mouse', 'Tidak responsif', 4, 'unit', 'penggantian', 'Didistribusikan sebagian: 2 dari 4 unit ke D3N (PC 17 & PC 36) tgl 2026-09-09. Sisa 2 unit belum terdistribusi.\nDidistribusikan 2 unit ke D3N (N26 dan N29) tgl 02/11/2026 11:14 (Total terpenuhi: 4/4 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 04:16:21'),
(32, 4, 'Webcam', 'rusak', 2, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(33, 3, 'Mouse', 'Kurang responsif', 2, 'unit', 'penggantian', 'Didistribusikan 2 unit ke D3L (L26 & L27) tgl 02/11/2026 14:27 (Total terpenuhi: 2/2 unit).', 'selesai', '2026-10-01 04:12:20', '2026-11-02 07:29:08'),
(34, 2, 'PSU', 'PSU Rusak', 1, 'unit', 'penggantian', 'Didistribusikan 1 unit ke D2A (PC 45) tgl 09/09/2026 14:25 (Total terpenuhi: 1/1 unit).', 'selesai', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(35, 29, 'Kursi', NULL, 1, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(36, 28, 'Kursi', 'jebol', 2, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(37, 27, 'Kabel Monitor', NULL, 1, 'unit', 'pending', NULL, 'menunggu_triase', '2026-10-01 04:12:20', '2026-10-01 04:12:20'),
(40, 39, 'Headset', 'Kabel putus', 2, 'unit', 'penggantian', 'Didistribusikan 2 unit ke D2D (D37 & D41) tgl 03/11/2026 14:21 (Total terpenuhi: 2/2 unit).', 'selesai', '2026-10-30 04:44:24', '2026-11-03 07:31:00'),
(41, 40, 'AC', 'Sering bocor', 2, 'unit', 'perbaikan', 'Didistribusikan 2 unit ke D2H (2 AC kanan kiri) tgl 02/11/2026 10:52 (Total terpenuhi: 2/2 unit).', 'selesai', '2026-11-02 03:50:51', '2026-11-03 01:31:44'),
(42, 41, 'Baterai AAA', 'hilang', 2, 'buah', 'penggantian', 'Didistribusikan 2 buah ke D3M (Remote AC) tgl 02/11/2026 14:10 (Total terpenuhi: 2/2 buah).', 'selesai', '2026-11-02 07:10:40', '2026-11-02 07:12:12'),
(43, 42, 'AC', 'Mengeluarkan air', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-03 07:43:34', '2026-11-03 07:45:16'),
(44, 43, 'Lantai', 'Pecah', 1, 'meter', 'perbaikan', NULL, 'selesai', '2026-11-03 07:47:03', '2026-11-03 07:49:02'),
(45, 44, 'Lampu', 'Berkedip', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-03 07:50:46', '2026-11-03 07:51:29'),
(46, 45, 'Lampu', 'Berkedip', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-03 08:05:19', '2026-11-03 08:05:47'),
(47, 46, 'AC', 'Mati', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-04 03:34:47', '2026-11-04 03:36:06'),
(48, 47, 'AC', 'Bocor', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-11 03:28:24', '2026-11-11 03:30:31'),
(49, 48, 'Lampu', 'Mati', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-17 01:43:07', '2026-11-17 01:44:16'),
(50, 49, 'AC', 'Bocor', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-17 01:46:26', '2026-11-17 01:50:38'),
(51, 50, 'Lampu', 'Berkedip', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-17 01:48:33', '2026-11-17 01:49:26'),
(52, 51, 'Keramik', 'Pecah', 1, 'unit', 'perbaikan', NULL, 'selesai', '2026-11-18 02:05:21', '2026-11-18 02:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_item_penanganans`
--

CREATE TABLE `laporan_item_penanganans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laporan_item_id` bigint(20) UNSIGNED NOT NULL,
  `tahap` varchar(255) NOT NULL,
  `jenis_tindakan` varchar(100) DEFAULT NULL,
  `aktor_peran` varchar(100) DEFAULT NULL,
  `tindakan_atau_hasil` text DEFAULT NULL,
  `petugas` varchar(255) DEFAULT NULL,
  `status_item_saat_itu` varchar(255) DEFAULT NULL,
  `status_sebelum` varchar(100) DEFAULT NULL,
  `tanggal_tindakan` datetime DEFAULT NULL,
  `tanggal_hasil` datetime DEFAULT NULL,
  `tanggal_waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_item_penanganans`
--

INSERT INTO `laporan_item_penanganans` (`id`, `laporan_item_id`, `tahap`, `jenis_tindakan`, `aktor_peran`, `tindakan_atau_hasil`, `petugas`, `status_item_saat_itu`, `status_sebelum`, `tanggal_tindakan`, `tanggal_hasil`, `tanggal_waktu`, `created_at`, `updated_at`) VALUES
(1, 3, 'Distribusi barang ke lab', 'distribusi_barang', 'Admin: Admin Laboratorium', 'Distribusi 3 CMOS ke pc 35, 6, 9', 'M Niam', 'terdistribusi', 'selesai', '2026-09-11 12:05:00', NULL, '2026-09-11 05:05:00', '2026-10-01 05:07:05', '2026-10-01 05:07:05'),
(2, 3, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: M Niam', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'M Niam', 'selesai', 'terdistribusi', '2026-10-01 12:07:15', '2026-10-01 12:07:15', '2026-10-01 05:07:15', '2026-10-01 05:07:15', '2026-10-01 05:07:15'),
(7, 4, 'Pengajuan Pengadaan ke BIUM', 'pengajuan_bium', 'Admin: M Niam', 'Pengajuan #PGD/2026/07/001 ke Divisi BIUM (Biro Umum) dikirim. Diajukan: Mouse Logitech M90 (60 unit).', 'M Niam', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-07-23 13:44:17', NULL, '2026-07-23 06:44:17', '2026-10-01 06:44:17', '2026-10-01 06:44:17'),
(8, 8, 'Pengajuan Pengadaan ke BIUM', 'pengajuan_bium', 'Admin: M Niam', 'Pengajuan #PGD/2026/07/001 ke Divisi BIUM (Biro Umum) dikirim. Diajukan: Headset Fantech CARBON (25 unit) (Spesifikasi: CARBON 7.1 HG30 Black).', 'M Niam', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-07-23 13:44:17', NULL, '2026-07-23 06:44:17', '2026-10-01 06:44:17', '2026-10-01 06:44:17'),
(9, 18, 'Pengajuan Pengadaan ke BIUM', 'pengajuan_bium', 'Admin: M Niam', 'Pengajuan #PGD/2026/07/001 ke Divisi BIUM (Biro Umum) dikirim. Diajukan: SSD NVME Kingston 500GB (1 unit) (Spesifikasi: 500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3).', 'M Niam', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-07-23 13:44:17', NULL, '2026-07-23 06:44:17', '2026-10-01 06:44:17', '2026-10-01 06:44:17'),
(10, 4, 'Keputusan BIUM', 'keputusan_bium', 'Divisi BIUM / Admin: Admin Laboratorium', 'Keputusan BIUM (PGD/2026/07/001): Status Disetujui oleh BIUM.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:44:27', NULL, '2026-10-29 07:44:27', '2026-10-29 07:44:27', '2026-10-29 07:44:27'),
(11, 8, 'Keputusan BIUM', 'keputusan_bium', 'Divisi BIUM / Admin: Admin Laboratorium', 'Keputusan BIUM (PGD/2026/07/001): Status Disetujui oleh BIUM.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:44:27', NULL, '2026-10-29 07:44:27', '2026-10-29 07:44:27', '2026-10-29 07:44:27'),
(12, 18, 'Keputusan BIUM', 'keputusan_bium', 'Divisi BIUM / Admin: Admin Laboratorium', 'Keputusan BIUM (PGD/2026/07/001): Status Disetujui oleh BIUM.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:44:27', NULL, '2026-10-29 07:44:27', '2026-10-29 07:44:27', '2026-10-29 07:44:27'),
(13, 4, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 60 unit diterima (Status: Sesuai).', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:53:04', NULL, '2026-10-29 07:53:04', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(14, 8, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 25 unit diterima (Status: Sesuai). Spek diterima: CARBON 7.1 HG30 Black.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:53:04', NULL, '2026-10-29 07:53:04', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(15, 18, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 1 unit diterima (Status: Sesuai). Spek diterima: 500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:53:04', NULL, '2026-10-29 07:53:04', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(16, 4, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 60 unit diterima (Status: Sesuai Penuh).', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 10:47:56', NULL, '2026-10-29 03:47:56', '2026-10-30 03:47:56', '2026-10-30 03:47:56'),
(17, 8, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 25 unit diterima (Status: Sesuai Penuh). Spek diterima: CARBON 7.1 HG30 Black.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 10:47:56', NULL, '2026-10-29 03:47:56', '2026-10-30 03:47:56', '2026-10-30 03:47:56'),
(18, 18, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 1 unit diterima (Status: Sesuai Penuh). Spek diterima: 500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 10:47:56', NULL, '2026-10-29 03:47:56', '2026-10-30 03:47:56', '2026-10-30 03:47:56'),
(19, 20, 'Niam', 'instruksi_laboran', 'Calvin', 'Mouse sudah berjalan normal', 'Admin Laboratorium', 'selesai', 'menunggu_pengadaan', '2026-09-09 11:15:00', '2026-09-09 13:18:00', '2026-09-09 04:15:00', '2026-10-30 04:16:43', '2026-10-30 04:16:43'),
(20, 40, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: 2 Headset bernomor PC 36 dan 41 mengalami putus kabel. sehingga perlu diganti dengan yang baru', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-10-30 11:44:24', NULL, '2026-10-30 04:44:24', '2026-10-30 04:44:24', '2026-10-30 04:44:24'),
(21, 40, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Imam', 'Mencopot dan menyimpan headset dari PC terkait', 'Imam', 'menunggu_triase', 'menunggu_triase', '2026-09-10 11:44:24', NULL, '2026-09-10 04:44:24', '2026-10-30 04:44:24', '2026-10-30 04:44:24'),
(22, 40, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Kontrak: Imam', 'Permintaan tindakan: Mengajukan Penggantian Headset yang rusak dengan yang baru berjumlah 2 pcs untuk PC no 36 dan 41. Usulan tindak lanjut item: Penggantian / Pengadaan Barang', 'Imam', 'menunggu_pengadaan', 'menunggu_triase', '2026-10-30 11:44:24', NULL, '2026-10-30 04:44:24', '2026-10-30 04:44:24', '2026-10-30 04:44:24'),
(23, 23, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Headset) sebanyak 2 unit didistribusikan ke Lab D2B (PC: B01, B02, B03, B04, B05). Pengiriman tuntas (2/2 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Nanda.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-10-30 16:03:00', NULL, '2026-10-30 09:03:00', '2026-10-30 09:07:49', '2026-10-30 09:07:49'),
(24, 15, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Headset) sebanyak 3 unit didistribusikan ke Lab D2B (PC: B03, B04, B05). Pengiriman tuntas (3/3 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Nanda.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-10-30 16:09:00', NULL, '2026-10-30 09:09:00', '2026-10-30 09:10:48', '2026-10-30 09:10:48'),
(25, 41, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: AC sering bocor', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-04-13 10:50:51', NULL, '2026-04-13 03:50:51', '2026-11-02 03:50:51', '2026-11-02 03:50:51'),
(26, 41, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Tetap: Wawan', 'Diperbaiki oleh Teknisi', 'Wawan', 'menunggu_triase', 'menunggu_triase', '2026-01-10 10:50:51', NULL, '2026-01-10 03:50:51', '2026-11-02 03:50:51', '2026-11-02 03:50:51'),
(27, 41, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Tetap: Wawan', 'Permintaan tindakan: Teknisi menyarankan ganti kompresor atau AC baru. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Wawan', 'proses_perbaikan', 'menunggu_triase', '2026-04-13 10:50:51', NULL, '2026-04-13 03:50:51', '2026-11-02 03:50:51', '2026-11-02 03:50:51'),
(28, 41, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (AC) sebanyak 2 unit didistribusikan ke Lab D2H (PC: 2 AC kanan kiri). Pengiriman tuntas (2/2 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Wawan.', 'Admin Laboratorium', 'terdistribusi', 'proses_perbaikan', '2026-11-02 10:52:00', NULL, '2026-11-02 03:52:00', '2026-11-02 03:55:12', '2026-11-02 03:55:12'),
(29, 23, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 10:58:52', '2026-11-02 10:58:52', '2026-11-02 03:58:52', '2026-11-02 03:58:52', '2026-11-02 03:58:52'),
(30, 15, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 10:59:11', '2026-11-02 10:59:11', '2026-11-02 03:59:11', '2026-11-02 03:59:11', '2026-11-02 03:59:11'),
(31, 31, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 2 unit didistribusikan ke Lab D3N (PC: N26 dan N29). Pengiriman tuntas (4/4 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Lukman.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 11:14:00', NULL, '2026-11-02 04:14:00', '2026-11-02 04:15:40', '2026-11-02 04:15:40'),
(32, 31, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 11:16:21', '2026-11-02 11:16:21', '2026-11-02 04:16:21', '2026-11-02 04:16:21', '2026-11-02 04:16:21'),
(33, 10, 'Koor SH', 'instruksi_laboran', 'Laboran', 'Melaporkan barang yang benar benar tidak bisa terpakai', 'Admin Laboratorium', 'selesai', 'menunggu_pengadaan', '2026-11-02 13:50:00', NULL, '2026-11-02 06:50:00', '2026-11-02 06:51:58', '2026-11-02 06:51:58'),
(34, 42, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Baterai pada remote AC setelah permbersihan AC hilang, setelah dicek tidak menggunakan baterai charge', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-02 14:10:40', NULL, '2026-11-02 07:10:40', '2026-11-02 07:10:40', '2026-11-02 07:10:40'),
(35, 42, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Lukman', 'menggunakan baterai sekali pakai', 'Lukman', 'menunggu_triase', 'menunggu_triase', '2026-10-19 14:10:40', NULL, '2026-10-19 07:10:40', '2026-11-02 07:10:40', '2026-11-02 07:10:40'),
(36, 42, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Kontrak: Lukman', 'Permintaan tindakan: pengajuan baterai aaa charge 2 buah. Usulan tindak lanjut item: Penggantian / Pengadaan Barang', 'Lukman', 'menunggu_pengadaan', 'menunggu_triase', '2026-11-02 14:10:40', NULL, '2026-11-02 07:10:40', '2026-11-02 07:10:40', '2026-11-02 07:10:40'),
(37, 42, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Baterai AAA) sebanyak 2 buah didistribusikan ke Lab D3M (PC: Remote AC). Pengiriman tuntas (2/2 buah). Asal barang: Stok Gudang Lab. Diterima oleh: Lukman.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:10:00', NULL, '2026-11-02 07:10:00', '2026-11-02 07:11:37', '2026-11-02 07:11:37'),
(38, 42, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:12:12', '2026-11-02 14:12:12', '2026-11-02 07:12:12', '2026-11-02 07:12:12', '2026-11-02 07:12:12'),
(39, 4, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 1 unit didistribusikan ke Lab D2J (PC: J03). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Nanda.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:14:00', NULL, '2026-11-02 07:14:00', '2026-11-02 07:15:20', '2026-11-02 07:15:20'),
(40, 24, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 1 unit didistribusikan ke Lab D2J (PC: J09). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Nanda.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:15:00', NULL, '2026-11-02 07:15:00', '2026-11-02 07:16:04', '2026-11-02 07:16:04'),
(41, 5, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Keyboard) sebanyak 2 unit didistribusikan ke Lab D2J (PC: J03 & J11). Pengiriman tuntas (2/2 unit). Asal barang: Stok Gudang Lab. Diterima oleh: Nanda.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:16:00', NULL, '2026-11-02 07:16:00', '2026-11-02 07:16:46', '2026-11-02 07:16:46'),
(42, 24, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:17:26', '2026-11-02 14:17:26', '2026-11-02 07:17:26', '2026-11-02 07:17:26', '2026-11-02 07:17:26'),
(43, 4, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:17:50', '2026-11-02 14:17:50', '2026-11-02 07:17:50', '2026-11-02 07:17:50', '2026-11-02 07:17:50'),
(44, 5, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:17:57', '2026-11-02 14:17:57', '2026-11-02 07:17:57', '2026-11-02 07:17:57', '2026-11-02 07:17:57'),
(45, 33, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 2 unit didistribusikan ke Lab D3L (PC: L26 & L27). Pengiriman tuntas (2/2 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Iqbal.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:27:00', NULL, '2026-11-02 07:27:00', '2026-11-02 07:28:25', '2026-11-02 07:28:25'),
(46, 33, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:29:08', '2026-11-02 14:29:08', '2026-11-02 07:29:08', '2026-11-02 07:29:08', '2026-11-02 07:29:08'),
(47, 26, 'Distribusi barang ke Lab', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Distribusi Sebagian: Barang (RAM 4 GB) sebanyak 1 unit didistribusikan ke Lab D3L (PC: L39). Terkirim 1 karena unit sudah memenuhi. Asal barang: Pengadaan BIUM. Diterima oleh: Iqbal.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-02 14:41:00', NULL, '2026-11-02 07:41:00', '2026-11-02 07:42:36', '2026-11-02 07:45:03'),
(48, 26, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-02 14:45:18', '2026-11-02 14:45:18', '2026-11-02 07:45:18', '2026-11-02 07:45:18', '2026-11-02 07:45:18'),
(49, 25, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 5 unit didistribusikan ke Lab D2H (PC: H02, H03, H05, H17, H18). Pengiriman tuntas (5/5 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Wawan.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 08:28:00', NULL, '2026-11-03 01:28:00', '2026-11-03 01:30:06', '2026-11-03 01:30:06'),
(50, 21, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Mouse) sebanyak 5 unit didistribusikan ke Lab D2H (PC: H19, H20, H24, H27, H13). Pengiriman tuntas (5/5 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Wawan.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 08:30:00', NULL, '2026-11-03 01:30:00', '2026-11-03 01:31:22', '2026-11-03 01:31:22'),
(51, 41, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 08:31:44', '2026-11-03 08:31:44', '2026-11-03 01:31:44', '2026-11-03 01:31:44', '2026-11-03 01:31:44'),
(52, 21, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 08:31:54', '2026-11-03 08:31:54', '2026-11-03 01:31:54', '2026-11-03 01:31:54', '2026-11-03 01:31:54'),
(53, 25, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 08:32:07', '2026-11-03 08:32:07', '2026-11-03 01:32:07', '2026-11-03 01:32:07', '2026-11-03 01:32:07'),
(54, 27, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (RAM 8GB DDR4) sebanyak 1 unit didistribusikan ke Lab D2D (PC: Laboran). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Imam.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 09:59:00', NULL, '2026-11-03 02:59:00', '2026-11-03 03:00:19', '2026-11-03 03:00:19'),
(55, 27, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 10:00:45', '2026-11-03 10:00:45', '2026-11-03 03:00:45', '2026-11-03 03:00:45', '2026-11-03 03:00:45'),
(56, 4, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 60 unit diterima (Status: Sesuai Penuh). Spek diterima: Logitech M90.', 'Admin Laboratorium', 'selesai', 'selesai', '2026-10-29 14:03:48', NULL, '2026-10-29 07:03:48', '2026-11-03 07:03:48', '2026-11-03 07:03:48'),
(57, 8, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 25 unit diterima (Status: Sesuai Penuh). Spek diterima: CARBON 7.1 HG30 Black.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:03:48', NULL, '2026-10-29 07:03:48', '2026-11-03 07:03:48', '2026-11-03 07:03:48'),
(58, 18, 'Verifikasi Penerimaan Barang', 'verifikasi_penerimaan', 'Petugas Penerima: Admin Laboratorium', 'Verifikasi Penerimaan: 1 unit diterima (Status: Sesuai Penuh). Spek diterima: 500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3.', 'Admin Laboratorium', 'menunggu_pengadaan', 'menunggu_pengadaan', '2026-10-29 14:03:48', NULL, '2026-10-29 07:03:48', '2026-11-03 07:03:48', '2026-11-03 07:03:48'),
(59, 18, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (SSD NVME 500GB) sebanyak 1 unit didistribusikan ke Lab D2D (PC: Laboran). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Imam.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 14:16:00', NULL, '2026-11-03 07:16:00', '2026-11-03 07:18:15', '2026-11-03 07:18:15'),
(60, 8, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Headset) sebanyak 1 unit didistribusikan ke Lab D2D (PC: D26). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Imam.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 14:20:00', NULL, '2026-11-03 07:20:00', '2026-11-03 07:21:07', '2026-11-03 07:21:07'),
(61, 17, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Headset) sebanyak 1 unit didistribusikan ke Lab D2D (PC: D36). Pengiriman tuntas (1/1 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Imam.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 14:21:00', NULL, '2026-11-03 07:21:00', '2026-11-03 07:21:43', '2026-11-03 07:21:43'),
(62, 40, 'Distribusi Barang ke Lab (Tuntas)', 'distribusi_barang', 'Petugas Distribusi: Admin Laboratorium', 'Barang (Headset) sebanyak 2 unit didistribusikan ke Lab D2D (PC: D37 & D41). Pengiriman tuntas (2/2 unit). Asal barang: Pengadaan BIUM. Diterima oleh: Imam.', 'Admin Laboratorium', 'terdistribusi', 'menunggu_pengadaan', '2026-11-03 14:21:00', NULL, '2026-11-03 07:21:00', '2026-11-03 07:22:25', '2026-11-03 07:22:25'),
(63, 40, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 14:31:00', '2026-11-03 14:31:00', '2026-11-03 07:31:00', '2026-11-03 07:31:00', '2026-11-03 07:31:00'),
(64, 8, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 14:31:15', '2026-11-03 14:31:15', '2026-11-03 07:31:15', '2026-11-03 07:31:15', '2026-11-03 07:31:15'),
(65, 17, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 14:31:34', '2026-11-03 14:31:34', '2026-11-03 07:31:34', '2026-11-03 07:31:34', '2026-11-03 07:31:34'),
(66, 18, 'Konfirmasi Laboran — Barang Berfungsi Baik', 'konfirmasi_laboran', 'Admin Lab: Admin Laboratorium', 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.', 'Admin Laboratorium', 'selesai', 'terdistribusi', '2026-11-03 14:31:41', '2026-11-03 14:31:41', '2026-11-03 07:31:41', '2026-11-03 07:31:41', '2026-11-03 07:31:41'),
(67, 43, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: AC didekat meja laboran selalu mengeluarkan kristal es dan menetes airnya', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-09-29 14:43:34', NULL, '2026-09-29 07:43:34', '2026-11-03 07:43:34', '2026-11-03 07:43:34'),
(68, 43, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Magang: Megan', 'Melaporkan AC menetes untuk segera di service', 'Megan', 'menunggu_triase', 'menunggu_triase', '2026-09-29 14:43:34', NULL, '2026-09-29 07:43:34', '2026-11-03 07:43:34', '2026-11-03 07:43:34'),
(69, 43, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Megan', 'Permintaan tindakan: Perbaikan AC oleh teknisi. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Megan', 'proses_perbaikan', 'menunggu_triase', '2026-09-29 14:43:34', NULL, '2026-09-29 07:43:34', '2026-11-03 07:43:34', '2026-11-03 07:43:34'),
(70, 43, 'Perbaikan Teknisi', 'laporan_ditutup', 'Teknisi', 'Perbaikan oleh teknisi', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-09-29 14:43:00', '2026-09-29 14:44:00', '2026-09-29 07:43:00', '2026-11-03 07:45:16', '2026-11-03 07:45:16'),
(71, 44, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Keramik lantai pecah', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-10-21 14:47:03', NULL, '2026-10-21 07:47:03', '2026-11-03 07:47:03', '2026-11-03 07:47:03'),
(72, 44, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Nanda', 'Melaporkan kerusakan', 'Nanda', 'menunggu_triase', 'menunggu_triase', '2026-10-21 14:47:03', NULL, '2026-10-21 07:47:03', '2026-11-03 07:47:03', '2026-11-03 07:47:03'),
(73, 44, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Kontrak: Nanda', 'Permintaan tindakan: Perbaikan lantai oleh pihak terkait. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Nanda', 'proses_perbaikan', 'menunggu_triase', '2026-10-21 14:47:03', NULL, '2026-10-21 07:47:03', '2026-11-03 07:47:03', '2026-11-03 07:47:03'),
(74, 44, 'Perbaikan lantai', 'ditugaskan_teknisi', 'Rindang costa', 'Perbaikan dan penggantian keramik oleh pihak Rindang Costa', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-10-21 14:47:00', '2026-10-21 14:48:00', '2026-10-21 07:47:00', '2026-11-03 07:49:02', '2026-11-03 07:49:02'),
(75, 45, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Salah satu dari lampu lab D2D berkedip secara terus menerus', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-10-23 14:50:46', NULL, '2026-10-23 07:50:46', '2026-11-03 07:50:46', '2026-11-03 07:50:46'),
(76, 45, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Imam', 'Melaporkan ke teknisi untuk dilakukan perbaikan langsung', 'Imam', 'menunggu_triase', 'menunggu_triase', '2026-10-23 14:50:46', NULL, '2026-10-23 07:50:46', '2026-11-03 07:50:46', '2026-11-03 07:50:46'),
(77, 45, 'Pengajuan Tindak Lanjut oleh Laboran', 'hasil_perbaikan', 'Laboran Kontrak: Imam', 'Teknisi mengganti dengan lampu baru. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Imam', 'selesai', 'menunggu_triase', '2026-10-23 14:50:00', '2026-10-23 14:51:00', '2026-10-23 07:50:46', '2026-11-03 07:50:46', '2026-11-03 07:51:29'),
(78, 46, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Lampu tengah satu biji setiap dinyalakan selalu berkedip', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-10-30 15:05:19', NULL, '2026-10-30 08:05:19', '2026-11-03 08:05:19', '2026-11-03 08:05:19'),
(79, 46, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Magang: Megan', 'laporan lampu rusak berkedip terus', 'Megan', 'menunggu_triase', 'menunggu_triase', '2026-10-30 15:05:19', NULL, '2026-10-30 08:05:19', '2026-11-03 08:05:19', '2026-11-03 08:05:19'),
(80, 46, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Megan', 'Perbaikan lampu oleh teknisi. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Megan', 'selesai', 'menunggu_triase', '2026-10-30 15:05:00', '2026-10-30 15:05:00', '2026-10-30 08:05:19', '2026-11-03 08:05:19', '2026-11-03 08:05:47'),
(81, 47, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: AC disisi kiri dalam keadaan mati, tidak bisa merespon remote AC, walau kondisi MCB telah on', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-03 10:34:47', NULL, '2026-11-03 03:34:47', '2026-11-04 03:34:47', '2026-11-04 03:34:47'),
(82, 47, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Ferdynand', 'Permintaan tindakan: Pengecekan dan perbaikan oleh teknisi. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Ferdynand', 'proses_perbaikan', 'menunggu_triase', '2026-11-03 10:34:47', NULL, '2026-11-03 03:34:47', '2026-11-04 03:34:47', '2026-11-04 03:34:47'),
(83, 47, 'Perbaikan Teknisi', 'ditugaskan_teknisi', 'Teknisi', 'Dilakukan pengecekan dan perbaikan pada MCB serta penggantian modul AC', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-04 09:32:00', '2026-11-04 10:35:00', '2026-11-04 02:32:00', '2026-11-04 03:36:06', '2026-11-04 03:36:06'),
(84, 48, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: AC sebelah stimur meneteskan air', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-10 10:28:24', NULL, '2026-11-10 03:28:24', '2026-11-11 03:28:24', '2026-11-11 03:28:24'),
(85, 48, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Imam', 'Mematikan AC yang bocor agar tidak mengganggu kelas', 'Imam', 'menunggu_triase', 'menunggu_triase', '2026-11-10 10:28:24', NULL, '2026-11-10 03:28:24', '2026-11-11 03:28:24', '2026-11-11 03:28:24'),
(86, 48, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Kontrak: Imam', 'Permintaan tindakan: Melaporkan kepada teknisi. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Imam', 'proses_perbaikan', 'menunggu_triase', '2026-11-10 10:28:24', NULL, '2026-11-10 03:28:24', '2026-11-11 03:28:24', '2026-11-11 03:28:24'),
(87, 48, 'Perbaikan AC', 'ditugaskan_teknisi', 'Teknisi', 'Perbaikan oleh Teknisi', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-10 18:00:00', '2026-11-10 18:30:00', '2026-11-10 11:00:00', '2026-11-11 03:30:31', '2026-11-11 03:30:31'),
(88, 49, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Lampu Berkedip kemudian mati', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-16 08:43:07', NULL, '2026-11-16 01:43:07', '2026-11-17 01:43:07', '2026-11-17 01:43:07'),
(89, 49, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Vibra', 'Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Vibra', 'proses_perbaikan', 'menunggu_triase', '2026-11-16 08:43:07', NULL, '2026-11-16 01:43:07', '2026-11-17 01:43:07', '2026-11-17 01:43:07'),
(90, 49, 'Perbaikan Teknisi', 'ditugaskan_teknisi', 'Teknisi', 'Pergantian lampu oleh teknisi', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-16 10:43:00', '2026-11-16 10:43:00', '2026-11-16 03:43:00', '2026-11-17 01:44:16', '2026-11-17 01:44:16'),
(91, 50, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: AC sebelah kanan (meja dosen) mengalami kebocoran dan mengeluarkan banyak air', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-16 08:46:26', NULL, '2026-11-16 01:46:26', '2026-11-17 01:46:26', '2026-11-17 01:46:26'),
(92, 50, 'Tindakan Langsung (Laboran)', 'tindakan_langsung', 'Laboran Kontrak: Lukman', 'Mematikan AC tersebut', 'Lukman', 'menunggu_triase', 'menunggu_triase', '2026-11-16 08:46:26', NULL, '2026-11-16 01:46:26', '2026-11-17 01:46:26', '2026-11-17 01:46:26'),
(93, 50, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Kontrak: Lukman', 'Permintaan tindakan: menghubungi teknisi untuk melakukan perbaikan. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Lukman', 'proses_perbaikan', 'menunggu_triase', '2026-11-16 08:46:26', NULL, '2026-11-16 01:46:26', '2026-11-17 01:46:26', '2026-11-17 01:46:26'),
(94, 51, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Terjadi lampu berkedip parah pada lampu tengah kanan. lampu berkedip sampai mati salah satu', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-16 08:48:33', NULL, '2026-11-16 01:48:33', '2026-11-17 01:48:33', '2026-11-17 01:48:33'),
(95, 51, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Akmal', 'Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Akmal', 'proses_perbaikan', 'menunggu_triase', '2026-11-16 08:48:33', NULL, '2026-11-16 01:48:33', '2026-11-17 01:48:33', '2026-11-17 01:48:33'),
(96, 51, 'Perbaikan Teknisi', 'ditugaskan_teknisi', 'Teknisi', 'Pergantian salah satu lampu yang bermasalah', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-16 11:48:00', '2026-11-16 12:17:00', '2026-11-16 04:48:00', '2026-11-17 01:49:26', '2026-11-17 01:49:26'),
(97, 50, 'Perbaikan Teknisi', 'ditugaskan_teknisi', 'Teknisi', 'Perbaikan AC oleh teknisi', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-16 10:04:00', '2026-11-16 10:33:00', '2026-11-16 03:04:00', '2026-11-17 01:50:38', '2026-11-17 01:50:38'),
(98, 52, 'Laporan Dicatat', 'laporan_dicatat', 'Admin: Admin Laboratorium', 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium. Uraian pengamatan: Keramik lantai mengalami memuai hingga pecah', 'Admin Laboratorium', 'menunggu_triase', NULL, '2026-11-17 09:05:21', NULL, '2026-11-17 02:05:21', '2026-11-18 02:05:21', '2026-11-18 02:05:21'),
(99, 52, 'Pengajuan Tindak Lanjut oleh Laboran', 'diteruskan_koordinator', 'Laboran Magang: Vibra', 'Permintaan tindakan: Mohon perbaikan ke pihak terkait. Usulan tindak lanjut item: Perbaikan oleh Teknisi', 'Vibra', 'proses_perbaikan', 'menunggu_triase', '2026-11-17 09:05:21', NULL, '2026-11-17 02:05:21', '2026-11-18 02:05:21', '2026-11-18 02:05:21'),
(100, 52, 'Perbaikan Vendor', 'ditugaskan_teknisi', 'Rindang costa', 'Perbaikan dan penggantian keramik oleh vendor', 'Admin Laboratorium', 'selesai', 'proses_perbaikan', '2026-11-17 07:05:00', '2026-11-17 09:07:00', '2026-11-17 00:05:00', '2026-11-18 02:08:04', '2026-11-18 02:08:04');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_24_000001_create_laporans_table', 1),
(5, '2026_08_24_000002_create_laporan_items_table', 1),
(6, '2026_08_24_000003_create_pengajuans_table', 1),
(7, '2026_08_24_000004_create_pengajuan_items_table', 1),
(8, '2026_08_24_000005_create_barang_masters_table', 1),
(9, '2026_08_24_000006_create_stok_gudangs_table', 1),
(10, '2026_08_24_000007_create_stok_transaksis_table', 1),
(11, '2026_08_24_000008_create_distribusis_table', 1),
(12, '2026_08_28_000001_create_laporan_item_penanganans_table', 1),
(13, '2026_08_28_000002_update_laporan_item_penanganans_table', 1),
(14, '2026_09_01_000001_add_form_fields_to_laporans_table', 1),
(15, '2026_09_01_000002_enhance_laporan_item_penanganans_and_items', 1),
(16, '2026_09_07_000001_add_catatan_to_stok_gudangs_table', 1),
(17, '2026_09_09_000001_change_tanggal_distribusi_to_datetime_in_distribusis_table', 1),
(18, '2026_09_18_000001_create_barang_tertinggals_table', 1),
(19, '2026_10_30_000001_add_sesuai_beda_spesifikasi_to_pengajuan_items', 2),
(20, '2026_10_30_000001_add_spesifikasi_to_barang_masters_table', 3),
(21, '2026_11_13_000001_add_distribusi_mandiri_to_distribusis_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_pengajuan` varchar(255) NOT NULL,
  `divisi_tujuan` varchar(255) NOT NULL,
  `tanggal_ajuan` date NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `tanggal_keputusan` date DEFAULT NULL,
  `catatan_keputusan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `nomor_pengajuan`, `divisi_tujuan`, `tanggal_ajuan`, `status`, `tanggal_keputusan`, `catatan_keputusan`, `created_at`, `updated_at`) VALUES
(2, 'PGD/2026/07/001', 'BIUM (Biro Umum)', '2026-07-23', 'disetujui', '2026-10-29', NULL, '2026-10-01 06:44:17', '2026-10-29 07:44:27'),
(3, 'PGD/2026/08/001', 'BIUM (Biro Umum)', '2026-08-20', 'disetujui', '2026-11-12', NULL, '2026-11-12 05:39:42', '2026-11-12 05:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_items`
--

CREATE TABLE `pengajuan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pengajuan_id` bigint(20) UNSIGNED NOT NULL,
  `laporan_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `jumlah_diajukan` int(11) NOT NULL DEFAULT 1,
  `satuan` varchar(50) NOT NULL DEFAULT 'unit',
  `jumlah_diterima` int(11) DEFAULT NULL,
  `spesifikasi_diterima` text DEFAULT NULL,
  `status_kesesuaian` enum('sesuai','sesuai_beda_spesifikasi','tidak_sesuai') DEFAULT NULL,
  `catatan_selisih` text DEFAULT NULL,
  `tanggal_terima` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan_items`
--

INSERT INTO `pengajuan_items` (`id`, `pengajuan_id`, `laporan_item_id`, `nama_barang`, `spesifikasi`, `jumlah_diajukan`, `satuan`, `jumlah_diterima`, `spesifikasi_diterima`, `status_kesesuaian`, `catatan_selisih`, `tanggal_terima`, `created_at`, `updated_at`) VALUES
(3, 2, 4, 'Mouse', 'Logitech M90', 60, 'unit', 60, 'Logitech M90', 'sesuai', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-11-03 07:03:48'),
(4, 2, 8, 'Headset', 'Fantech CARBON 7.1 HG30 Black', 25, 'unit', 25, 'CARBON 7.1 HG30 Black', 'sesuai', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:01:44'),
(5, 2, 18, 'SSD NVME 500GB', '500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', 1, 'unit', 1, '500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', 'sesuai', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:02:03'),
(6, 2, NULL, 'RAM 4GB DDR3', 'Kingston KVR 4GB DDR3', 3, 'unit', 3, '[Merk/Model: VGEN Platinum DDR3 - 10600U] Kingston KVR 4GB DDR3 10600U', 'sesuai_beda_spesifikasi', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:02:26'),
(7, 2, NULL, 'RAM 8GB DDR4', 'Tridenz 3200mhz 8GB DDR4 F4-3200C16D-16GTZB', 1, 'pasang', 1, 'Tridenz 3200mhz 8GB DDR4 F4-3200C16D-16GTZB', 'sesuai', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:02:45'),
(8, 2, NULL, 'PSU 550W', 'FSP HV Pro 550W - 5IAAC', 5, 'unit', 5, '[Merk/Model: FSP VIC BD 550W] VIC BD 550W', 'sesuai_beda_spesifikasi', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:03:06'),
(9, 2, NULL, 'Kipas Gantung', 'Ceiling', 2, 'unit', 2, 'Ceiling', 'sesuai', NULL, '2026-11-03', '2026-10-01 06:44:17', '2026-11-03 07:03:48'),
(10, 2, NULL, 'AC', 'Daikin 2PK', 3, 'unit', 3, '[Merk/Model: Midea 2PK] Midea 2PK', 'sesuai_beda_spesifikasi', NULL, '2026-10-29', '2026-10-01 06:44:17', '2026-10-30 04:03:19'),
(11, 3, NULL, 'SSD NVME 500GB', '500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', 45, 'buah', 45, '500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', 'sesuai', NULL, '2026-11-12', '2026-11-12 05:39:42', '2026-11-12 05:41:18'),
(12, 3, NULL, 'SSD SATA 512GB', 'Kingston', 90, 'buah', 90, '[Merk/Model: Patriot P210] Kingston', 'sesuai_beda_spesifikasi', NULL, '2026-11-12', '2026-11-12 05:39:42', '2026-11-12 05:41:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0wXcF4UrXxm8BEPkuE6ACb6iSFha622gJjhExBZj', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFFKRkxROVlHdDFGNEZtcW9hV0oxTlZCOU95MGFURXF6cUZZZ1hOUyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JhcmFuZy10ZXJ0aW5nZ2FsLzk3IjtzOjU6InJvdXRlIjtzOjIyOiJiYXJhbmctdGVydGluZ2dhbC5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1795507366),
('4AC08v8LIGZF40k0XWooImAqcgktLrYTb5psrH8T', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia3FvMnBwbmpMb2JzeVRUdVhOaVNFa2JoU3Q3WkU4YW1QekVQSTJZMyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xhcG9yYW4iO3M6NToicm91dGUiO3M6MTM6ImxhcG9yYW4uaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1795488448),
('MSKLwopubWfTAMbsatl3dRq2Hs9esvT1g1IDTb9U', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUWhGeUtPdHpjNVM3OHBPMjhVUFprZWM1eW9XR1VJWDBXZXRyTDV1UiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo4NToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JhcmFuZy10ZXJ0aW5nZ2FsP2VuZF9kYXRlPSZsYWI9RDJDJnNlYXJjaD0mc3RhcnRfZGF0ZT0mc3RhdHVzPSI7czo1OiJyb3V0ZSI7czoyMzoiYmFyYW5nLXRlcnRpbmdnYWwuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1795516364);

-- --------------------------------------------------------

--
-- Table structure for table `stok_gudangs`
--

CREATE TABLE `stok_gudangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `barang_master_id` bigint(20) UNSIGNED NOT NULL,
  `stok_saat_ini` int(11) NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_gudangs`
--

INSERT INTO `stok_gudangs` (`id`, `barang_master_id`, `stok_saat_ini`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, 45, NULL, '2026-10-01 04:12:19', '2026-11-03 01:31:22'),
(2, 2, 3, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(3, 3, 13, NULL, '2026-10-01 04:12:19', '2026-11-02 07:16:46'),
(4, 4, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(5, 5, 7, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(6, 6, 31, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(7, 7, 11, NULL, '2026-10-01 04:12:19', '2026-10-30 03:59:39'),
(8, 8, 12, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(9, 9, 53, NULL, '2026-10-01 04:12:19', '2026-11-23 03:59:09'),
(10, 10, 3, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(11, 11, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(12, 12, 46, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(13, 13, 42, NULL, '2026-10-01 04:12:19', '2026-11-02 07:11:37'),
(14, 14, 1, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(15, 15, 3, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(16, 16, 3, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(17, 17, 1, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(18, 18, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(19, 19, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(20, 20, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(21, 21, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(22, 22, 17, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(23, 23, 3, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(24, 24, 1, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(25, 25, 10, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(26, 26, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(27, 27, 1, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(28, 28, 1, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(29, 29, 31, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(30, 30, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(31, 31, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(32, 32, 2, NULL, '2026-10-01 04:12:19', '2026-10-01 04:12:19'),
(33, 33, 16, NULL, '2026-10-29 07:53:04', '2026-11-03 07:22:25'),
(34, 34, 43, NULL, '2026-10-29 07:53:04', '2026-11-13 09:39:08'),
(36, 36, 0, NULL, '2026-10-29 07:53:04', '2026-11-03 03:00:19'),
(37, 37, 1, NULL, '2026-10-29 07:53:04', '2026-11-02 03:55:12'),
(41, 41, 2, NULL, '2026-10-30 03:47:56', '2026-11-02 07:42:36'),
(43, 43, 5, NULL, '2026-10-30 03:47:56', '2026-10-30 03:59:58'),
(45, 45, 1, 'Pengembalian RAM dari D2D dengan kondisi normal', '2026-11-03 02:48:29', '2026-11-03 02:48:29'),
(50, 50, 2, NULL, '2026-11-03 07:03:48', '2026-11-03 07:03:48'),
(52, 52, 86, NULL, '2026-11-12 05:41:18', '2026-11-13 09:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `stok_transaksis`
--

CREATE TABLE `stok_transaksis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stok_gudang_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `referensi_type` varchar(255) DEFAULT NULL,
  `referensi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_transaksis`
--

INSERT INTO `stok_transaksis` (`id`, `stok_gudang_id`, `jenis`, `jumlah`, `referensi_type`, `referensi_id`, `keterangan`, `tanggal_transaksi`, `created_at`, `updated_at`) VALUES
(1, 1, 'masuk', 60, 'pengajuan_items', 3, 'Penerimaan pengadaan PGD/2026/07/001 - Mouse Logitech M90', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(2, 33, 'masuk', 25, 'pengajuan_items', 4, 'Penerimaan pengadaan PGD/2026/07/001 - Headset Fantech CARBON', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(3, 34, 'masuk', 1, 'pengajuan_items', 5, 'Penerimaan pengadaan PGD/2026/07/001 - SSD NVME Kingston 500GB', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(5, 36, 'masuk', 1, 'pengajuan_items', 7, 'Penerimaan pengadaan PGD/2026/07/001 - RAM Tridenz 8GB DDR4', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(6, 7, 'masuk', 5, 'pengajuan_items', 8, 'Penerimaan pengadaan PGD/2026/07/001 - PSU 550W FSP HV Pro 550W - 5IAAC', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(7, 37, 'masuk', 3, 'pengajuan_items', 10, 'Penerimaan pengadaan PGD/2026/07/001 - AC Daikin', '2026-10-29', '2026-10-29 07:53:04', '2026-10-29 07:53:04'),
(8, 41, 'masuk', 3, 'manual', NULL, 'Penyesuaian penerimaan barang PGD/2026/07/001', '2026-10-29', '2026-10-30 03:50:51', '2026-10-30 03:50:51'),
(9, 7, 'keluar', 5, 'manual', NULL, 'Penyesuaian kesalahan verifikasi penerimaan barang', '2026-10-30', '2026-10-30 03:59:39', '2026-10-30 03:59:39'),
(10, 43, 'masuk', 5, 'manual', NULL, 'Penerimaan pengadaan PGD/2026/07/001 - PSU 550W FSP HV Pro 550W - 5IAAC', '2026-10-29', '2026-10-30 03:59:58', '2026-10-30 03:59:58'),
(11, 33, 'keluar', 2, 'distribusi', 9, 'Distribusi ke Lab D2B (B01, B02, B03, B04, B05)', '2026-10-30', '2026-10-30 09:07:49', '2026-10-30 09:07:49'),
(12, 33, 'keluar', 3, 'distribusi', 10, 'Distribusi ke Lab D2B (B03, B04, B05)', '2026-10-30', '2026-10-30 09:10:48', '2026-10-30 09:10:48'),
(13, 37, 'keluar', 2, 'distribusi', 11, 'Distribusi ke Lab D2H (2 AC kanan kiri)', '2026-11-02', '2026-11-02 03:55:12', '2026-11-02 03:55:12'),
(14, 1, 'keluar', 2, 'distribusi', 12, 'Distribusi ke Lab D3N (N26 dan N29)', '2026-11-02', '2026-11-02 04:15:40', '2026-11-02 04:15:40'),
(15, 13, 'keluar', 2, 'distribusi', 13, 'Distribusi ke Lab D3M (Remote AC)', '2026-11-02', '2026-11-02 07:11:37', '2026-11-02 07:11:37'),
(16, 1, 'keluar', 1, 'distribusi', 14, 'Distribusi ke Lab D2J (J03)', '2026-11-02', '2026-11-02 07:15:20', '2026-11-02 07:15:20'),
(17, 1, 'keluar', 1, 'distribusi', 15, 'Distribusi ke Lab D2J (J09)', '2026-11-02', '2026-11-02 07:16:04', '2026-11-02 07:16:04'),
(18, 3, 'keluar', 2, 'distribusi', 16, 'Distribusi ke Lab D2J (J03 & J11)', '2026-11-02', '2026-11-02 07:16:46', '2026-11-02 07:16:46'),
(19, 1, 'keluar', 2, 'distribusi', 17, 'Distribusi ke Lab D3L (L26 & L27)', '2026-11-02', '2026-11-02 07:28:25', '2026-11-02 07:28:25'),
(20, 41, 'keluar', 1, 'distribusi', 18, 'Distribusi ke Lab D3L (L39)', '2026-11-02', '2026-11-02 07:42:36', '2026-11-02 07:42:36'),
(21, 1, 'keluar', 5, 'distribusi', 19, 'Distribusi ke Lab D2H (H02, H03, H05, H17, H18)', '2026-11-03', '2026-11-03 01:30:06', '2026-11-03 01:30:06'),
(22, 1, 'keluar', 5, 'distribusi', 20, 'Distribusi ke Lab D2H (H19, H20, H24, H27, H13)', '2026-11-03', '2026-11-03 01:31:22', '2026-11-03 01:31:22'),
(23, 45, 'masuk', 1, 'manual', NULL, 'Pencatatan stok awal barang baru', '2026-11-03', '2026-11-03 02:48:29', '2026-11-03 02:48:29'),
(24, 36, 'keluar', 1, 'distribusi', 21, 'Distribusi ke Lab D2D (Laboran)', '2026-11-03', '2026-11-03 03:00:19', '2026-11-03 03:00:19'),
(25, 50, 'masuk', 2, 'pengajuan_items', 9, 'Penerimaan pengadaan PGD/2026/07/001 - Kipas Gantung - Ceiling', '2026-11-03', '2026-11-03 07:03:48', '2026-11-03 07:03:48'),
(26, 34, 'keluar', 1, 'distribusi', 22, 'Distribusi ke Lab D2D (Laboran)', '2026-11-03', '2026-11-03 07:18:15', '2026-11-03 07:18:15'),
(27, 33, 'keluar', 1, 'distribusi', 23, 'Distribusi ke Lab D2D (D26)', '2026-11-03', '2026-11-03 07:21:07', '2026-11-03 07:21:07'),
(28, 33, 'keluar', 1, 'distribusi', 24, 'Distribusi ke Lab D2D (D36)', '2026-11-03', '2026-11-03 07:21:43', '2026-11-03 07:21:43'),
(29, 33, 'keluar', 2, 'distribusi', 25, 'Distribusi ke Lab D2D (D37 & D41)', '2026-11-03', '2026-11-03 07:22:25', '2026-11-03 07:22:25'),
(30, 34, 'masuk', 45, 'pengajuan_items', 11, 'Penerimaan pengadaan PGD/2026/08/001 - SSD NVME 500GB - 500GB m.2 NVMe 2280 PCIe 4.0 Kingston NV3', '2026-11-12', '2026-11-12 05:41:18', '2026-11-12 05:41:18'),
(31, 52, 'masuk', 90, 'pengajuan_items', 12, 'Penerimaan pengadaan PGD/2026/08/001 - SSD SATA 512GB - Patriot P210 (Penyesuaian Spek)', '2026-11-12', '2026-11-12 05:41:18', '2026-11-12 05:41:18'),
(32, 34, 'keluar', 2, 'distribusi', 26, 'Distribusi Mandiri [Upgrade Perangkat Lab] ke Lab D2I - Pembuatan master baru', '2026-11-13', '2026-11-13 09:39:08', '2026-11-13 09:39:08'),
(33, 52, 'keluar', 2, 'distribusi', 27, 'Distribusi Mandiri [Upgrade Perangkat Lab] ke Lab D2J - Pembuatan master windows 10', '2026-11-12', '2026-11-13 09:43:15', '2026-11-13 09:43:15'),
(34, 52, 'keluar', 2, 'distribusi', 28, 'Distribusi Mandiri [Upgrade Perangkat Lab] ke Lab D2K - Pembuatan master windows 10', '2026-11-12', '2026-11-13 09:44:22', '2026-11-13 09:44:22'),
(35, 9, 'masuk', 2, 'manual', NULL, 'Kondisi HDD baik berasal dari D2K', '2026-11-23', '2026-11-23 03:59:09', '2026-11-23 03:59:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 'Gamani Wacana M.Ak', 'sandriani@example.com', '2026-10-01 03:54:11', '$2y$04$vQ1HmWUu.HU8dI1ZkYGfj.9QJV.PJsGchc9E8TDfMuA6MsWLhUUjG', 'kaToimKVAf', '2026-10-01 03:54:11', '2026-10-01 03:54:11'),
(7, 'M Niam', 'admin@labkomp.com', NULL, '$2y$12$u.om0YSB/2vCCZA2eQz/q.7WjXx7FzF2R00OOynpe/wQ2drmHUg0G', 'dX8saQDZ4RWIVgxrFAUEhzEjvL2Eq6spqxYqDxmUSx66NCm4zw1cRtOzd5r0', '2026-10-01 04:00:46', '2026-10-01 04:12:19'),
(9, 'Admin Laboratorium', 'admin@labkom.id', NULL, '$2y$12$rgzov/.hkxIDbsnQc5XEAuTeEWWif3p5Nswn.R7scbz1eiSkTO6CC', 'nIgkKkRPPqxqmYHspmqdGjuOmyRy3NezhijkNZuL4bl8Tft7rU9DYeduoDYX', '2026-10-01 04:11:17', '2026-10-01 04:12:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang_masters`
--
ALTER TABLE `barang_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang_tertinggals`
--
ALTER TABLE `barang_tertinggals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `distribusis`
--
ALTER TABLE `distribusis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribusis_laporan_item_id_foreign` (`laporan_item_id`),
  ADD KEY `distribusis_stok_gudang_id_foreign` (`stok_gudang_id`),
  ADD KEY `distribusis_pengajuan_item_id_foreign` (`pengajuan_item_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_items`
--
ALTER TABLE `laporan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_items_laporan_id_foreign` (`laporan_id`);

--
-- Indexes for table `laporan_item_penanganans`
--
ALTER TABLE `laporan_item_penanganans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_item_penanganans_laporan_item_id_foreign` (`laporan_item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengajuans_nomor_pengajuan_unique` (`nomor_pengajuan`);

--
-- Indexes for table `pengajuan_items`
--
ALTER TABLE `pengajuan_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_items_pengajuan_id_foreign` (`pengajuan_id`),
  ADD KEY `pengajuan_items_laporan_item_id_foreign` (`laporan_item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stok_gudangs`
--
ALTER TABLE `stok_gudangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stok_gudangs_barang_master_id_unique` (`barang_master_id`);

--
-- Indexes for table `stok_transaksis`
--
ALTER TABLE `stok_transaksis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stok_transaksis_stok_gudang_id_foreign` (`stok_gudang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_masters`
--
ALTER TABLE `barang_masters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `barang_tertinggals`
--
ALTER TABLE `barang_tertinggals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `distribusis`
--
ALTER TABLE `distribusis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `laporan_items`
--
ALTER TABLE `laporan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `laporan_item_penanganans`
--
ALTER TABLE `laporan_item_penanganans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengajuan_items`
--
ALTER TABLE `pengajuan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stok_gudangs`
--
ALTER TABLE `stok_gudangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `stok_transaksis`
--
ALTER TABLE `stok_transaksis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribusis`
--
ALTER TABLE `distribusis`
  ADD CONSTRAINT `distribusis_laporan_item_id_foreign` FOREIGN KEY (`laporan_item_id`) REFERENCES `laporan_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `distribusis_pengajuan_item_id_foreign` FOREIGN KEY (`pengajuan_item_id`) REFERENCES `pengajuan_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `distribusis_stok_gudang_id_foreign` FOREIGN KEY (`stok_gudang_id`) REFERENCES `stok_gudangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_items`
--
ALTER TABLE `laporan_items`
  ADD CONSTRAINT `laporan_items_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_item_penanganans`
--
ALTER TABLE `laporan_item_penanganans`
  ADD CONSTRAINT `laporan_item_penanganans_laporan_item_id_foreign` FOREIGN KEY (`laporan_item_id`) REFERENCES `laporan_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan_items`
--
ALTER TABLE `pengajuan_items`
  ADD CONSTRAINT `pengajuan_items_laporan_item_id_foreign` FOREIGN KEY (`laporan_item_id`) REFERENCES `laporan_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pengajuan_items_pengajuan_id_foreign` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_gudangs`
--
ALTER TABLE `stok_gudangs`
  ADD CONSTRAINT `stok_gudangs_barang_master_id_foreign` FOREIGN KEY (`barang_master_id`) REFERENCES `barang_masters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_transaksis`
--
ALTER TABLE `stok_transaksis`
  ADD CONSTRAINT `stok_transaksis_stok_gudang_id_foreign` FOREIGN KEY (`stok_gudang_id`) REFERENCES `stok_gudangs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
