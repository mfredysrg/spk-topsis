-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2026 at 04:20 PM
-- Server version: 8.4.3
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_spk`
--

-- --------------------------------------------------------

--
-- Table structure for table `akses`
--

CREATE TABLE `akses` (
  `id_akses` int NOT NULL,
  `id_divisi` int DEFAULT NULL,
  `nama` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `akses` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akses`
--

INSERT INTO `akses` (`id_akses`, `id_divisi`, `nama`, `email`, `password`, `akses`) VALUES
(1, 0, 'operator', 'operator@gmail.com', '0192023a7bbd73250516f069df18b500', 'HRD'),
(2, 6, 'Windi Yanuariska', 'windygiga@gmail.com', 'd748c957a0018bfe3d974f8c44e4f3b7', 'Kepala Divisi'),
(3, 1, 'Syamsul Maarif', 'syamsulmaarif@gmail.com', '39a4dda18c93ccf4d47e44580bcaf1a0', 'Kepala Divisi'),
(4, 1, 'Bandha Laris', 'mariyadayu@gmail.com', '2ba672e97d6899f4d75e390daa5a35fe', 'Umkm'),
(5, 1, 'Zahra Ponsel', 'virra@gmail.com', '6e5870b70ea0f4b88932910f017c1b53', 'Umkm'),
(6, 1, 'Dewi Cemilan', 'dewipuspita@gmail.com', '5203e18ca19b351e51d598cdc9adeb62', 'Umkm'),
(7, 7, 'Didi Muhadi', 'didimuhadi@gmail.com', '9ccd1206b99e130c54fb4e9fa1771513', 'Kepala Divisi'),
(8, 4, 'Bayu Anugerah', 'bayuanugerah@gmail.com', 'cfd111106dc95e430bf5eff5f2d71b87', 'Kepala Divisi'),
(9, 6, 'Crispy Crunchy', 'fuadi@gmail.com', 'a0e9c90c2755cb0611ae7f198604905f', 'Umkm'),
(10, 6, 'Medina Beauty and Accessories', 'munawwarah@gmail.com', '29e4973de27a17dbeb7968f581abe96e', 'Umkm'),
(11, 0, 'Kepala Divisi', 'kadiv@gmail.com', '0192023a7bbd73250516f069df18b500', 'Pimpinan'),
(12, NULL, 'Dewi Cemilan', 'dewipspt@gmail.com', 'dewicemilan', 'UMKM'),
(13, NULL, 'Dewi Cemilan', 'dewipspt@gmail.com', 'dewicemilan', 'UMKM'),
(14, NULL, 'Dewi Cemilan', 'dewip@gmail.com', 'dewicemilan', 'UMKM'),
(15, NULL, 'Dewi Cemilan', 'dewipuspita@gmail.com', 'dewicemilan', 'UMKM'),
(16, NULL, 'Rayyan Mart', 'mhdriduan@gmail.com', 'dewicemilan', 'UMKM'),
(17, NULL, 'Riski Fotokopi', 'fajriah@gmail.com', 'adkma', 'UMKM'),
(18, NULL, 'Warung CRC', 'yunishara@gmail.com', 'dnsjd', 'UMKM');

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `alternatif` text NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `id_kriteria`, `alternatif`, `nilai`) VALUES
(1, 4, 'Ada', 5),
(2, 4, 'Tidak Ada', 1),
(5, 3, 'Belum Pernah Sama Sekali', 5),
(6, 3, 'Terakhir Menerima > 3 Tahun Lalu', 4),
(7, 3, 'Terakhir Menerima 2 - 3 Tahun Lalu', 3),
(8, 3, 'Terakhir Menerima 1-2 Tahun Lalu', 2),
(9, 2, 'Sangat Baik', 4),
(10, 2, 'Baik', 3),
(11, 2, 'Sedang', 2),
(12, 2, 'Buruk', 1),
(13, 1, 'Sangat Baik', 4),
(14, 1, 'Baik', 3),
(15, 1, 'Sedang', 2),
(16, 1, 'Buruk', 1),
(17, 5, '>= 5 Orang', 5),
(18, 5, '4 Orang', 4),
(19, 5, '3 Orang', 3),
(20, 8, '<= Rp. 100.000', 5),
(21, 8, 'Rp. 101.000 - Rp. 250.000', 4),
(22, 8, 'Rp. 251.000 - Rp. 500.000', 3),
(23, 8, 'Rp. 501.000 - Rp. 1000.000', 2),
(24, 8, '>= Rp. 1.000.000', 1),
(25, 7, '<= Rp. 3.000.000', 5),
(26, 7, 'Rp. 3.000.000 - Rp. 5.000.000', 4),
(27, 7, 'Rp. 5.001.000 - Rp. 7.000.000', 3),
(28, 7, 'Rp. 7.001.000 - Rp. 10.000.000', 2),
(29, 7, '>= Rp. 10.000.000', 1),
(30, 6, '>= 5 Tahun', 5),
(31, 6, '4 Tahun', 4),
(32, 6, '3 Tahun', 3),
(33, 6, '2 Tahun', 2),
(34, 6, '1 Tahun/ Baru Buka', 1),
(35, 5, '1-2 Orang', 2),
(36, 5, 'Tidak Ada', 1),
(37, 3, 'Baru Menerima > 1 Tahun Terakhir', 1),
(38, 9, 'as', 12),
(39, 9, '21', 212);

-- --------------------------------------------------------

--
-- Table structure for table `anp_perbandingan`
--

CREATE TABLE `anp_perbandingan` (
  `id_perbandingan_anp` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `kriteria_1` int NOT NULL,
  `kriteria_2` int NOT NULL,
  `nilai_banding` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bobot_anp`
--

CREATE TABLE `bobot_anp` (
  `id_bobot_anp` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `eigen_vector` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bobot_swara`
--

CREATE TABLE `bobot_swara` (
  `id_swara` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `s_j` double DEFAULT '0',
  `k_j` double DEFAULT '1',
  `q_j` double DEFAULT '1',
  `w_j` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id_divisi` int NOT NULL,
  `nama_divisi` text NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id_divisi`, `nama_divisi`, `keterangan`) VALUES
(1, 'Driver', 'Bertugas mengantar urusan kerja'),
(2, 'Fungsional MA', 'Menjalankan tugas-tugas MA'),
(3, 'Fungsional MTs', 'Menjalankan tugas-tugas MTS'),
(4, 'GTT', 'Tugas tidak tahu apa itu GTT'),
(6, 'Kaur Guru MA', 'Menjalankan tugas Kaur Guru MA'),
(7, 'Guru MA', 'Mengajar pada siswa MA');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int NOT NULL,
  `kode_kriteria` varchar(20) NOT NULL,
  `kriteria` text NOT NULL,
  `atribut` varchar(20) NOT NULL,
  `bobot` int NOT NULL,
  `bobot_anp` double DEFAULT '0',
  `bobot_swara` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `kriteria`, `atribut`, `bobot`, `bobot_anp`, `bobot_swara`) VALUES
(3, 'C6', 'Riwayat Bantuan', 'Benefit', 10, 0.1887, 0.0041),
(4, 'C5', 'Kepemilikan SKTM', 'Benefit', 20, 0.3774, 0.0447),
(5, 'C4', 'Tanggungan Keluarga', 'Benefit', 20, 0.3774, 0.939),
(6, 'C3', 'Lama Usaha', 'Benefit', 0, 0.0189, 0.0041),
(7, 'C2', 'Aset Usaha', 'Cost', 0, 0.0189, 0.0041),
(8, 'C1', 'Omzet Usaha', 'Cost', 0, 0.0189, 0.0041);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_umkm` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `nama` text NOT NULL,
  `kriteria` text NOT NULL,
  `nilai` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_periode_penilaian`, `id_umkm`, `id_kriteria`, `nama`, `kriteria`, `nilai`) VALUES
(1, 1, 1, 1, 'Ujang Suherman', 'Kopetensi', 4),
(2, 1, 1, 2, 'Ujang Suherman', 'Loyalitas', 3),
(3, 1, 1, 3, 'Ujang Suherman', 'Tarbiyah', 2),
(4, 1, 1, 4, 'Ujang Suherman', 'Tahsin', 3),
(5, 1, 2, 1, 'Sabil Kurniawan', 'Kopetensi', 3),
(6, 1, 2, 2, 'Sabil Kurniawan', 'Loyalitas', 3),
(7, 1, 2, 3, 'Sabil Kurniawan', 'Tarbiyah', 2),
(8, 1, 2, 4, 'Sabil Kurniawan', 'Tahsin', 1),
(9, 1, 3, 1, 'Adung Sudiadi', 'Kopetensi', 3),
(10, 1, 3, 2, 'Adung Sudiadi', 'Loyalitas', 4),
(11, 1, 3, 3, 'Adung Sudiadi', 'Tarbiyah', 4),
(12, 1, 3, 4, 'Adung Sudiadi', 'Tahsin', 1),
(13, 1, 4, 1, 'Mia Rusmia, S.Pt.', 'Kopetensi', 4),
(14, 1, 4, 2, 'Mia Rusmia, S.Pt.', 'Loyalitas', 4),
(15, 1, 4, 3, 'Mia Rusmia, S.Pt.', 'Tarbiyah', 3),
(16, 1, 4, 4, 'Mia Rusmia, S.Pt.', 'Tahsin', 4),
(17, 1, 5, 1, 'Elfa Robi, Lc.', 'Kopetensi', 4),
(18, 1, 5, 2, 'Elfa Robi, Lc.', 'Loyalitas', 4),
(19, 1, 5, 3, 'Elfa Robi, Lc.', 'Tarbiyah', 2),
(20, 1, 5, 4, 'Elfa Robi, Lc.', 'Tahsin', 2),
(21, 2, 1, 1, 'Ujang Suherman', 'Kopetensi', 4),
(22, 2, 1, 2, 'Ujang Suherman', 'Loyalitas', 3),
(23, 2, 1, 3, 'Ujang Suherman', 'Tarbiyah', 2),
(24, 2, 1, 4, 'Ujang Suherman', 'Tahsin', 4),
(25, 2, 2, 1, 'Sabil Kurniawan', 'Kopetensi', 3),
(26, 2, 2, 2, 'Sabil Kurniawan', 'Loyalitas', 3),
(27, 2, 2, 3, 'Sabil Kurniawan', 'Tarbiyah', 3),
(28, 2, 2, 4, 'Sabil Kurniawan', 'Tahsin', 2),
(29, 2, 3, 1, 'Adung Sudiadi', 'Kopetensi', 3),
(30, 2, 3, 2, 'Adung Sudiadi', 'Loyalitas', 3),
(31, 2, 3, 3, 'Adung Sudiadi', 'Tarbiyah', 4),
(32, 2, 3, 4, 'Adung Sudiadi', 'Tahsin', 3),
(33, 2, 4, 1, 'Mia Rusmia, S.Pt.', 'Kopetensi', 4),
(34, 2, 4, 2, 'Mia Rusmia, S.Pt.', 'Loyalitas', 4),
(35, 2, 4, 3, 'Mia Rusmia, S.Pt.', 'Tarbiyah', 3),
(36, 2, 4, 4, 'Mia Rusmia, S.Pt.', 'Tahsin', 2),
(37, 2, 5, 1, 'Elfa Robi, Lc.', 'Kopetensi', 4),
(38, 2, 5, 2, 'Elfa Robi, Lc.', 'Loyalitas', 3),
(39, 2, 5, 3, 'Elfa Robi, Lc.', 'Tarbiyah', 2),
(40, 2, 5, 4, 'Elfa Robi, Lc.', 'Tahsin', 4),
(41, 4, 1, 8, 'Bandha Laris', 'Omzet Usaha', 3),
(42, 4, 1, 7, 'Bandha Laris', 'Aset Usaha', 4),
(43, 4, 1, 6, 'Bandha Laris', 'Lama Usaha', 3),
(44, 4, 1, 5, 'Bandha Laris', 'Tanggungan Keluarga', 3),
(45, 4, 1, 4, 'Bandha Laris', 'Kepemilikan SKTM', 5),
(46, 4, 1, 3, 'Bandha Laris', 'Riwayat Bantuan', 5),
(47, 4, 2, 8, 'Zahra Ponsel', 'Omzet Usaha', 3),
(48, 4, 2, 7, 'Zahra Ponsel', 'Aset Usaha', 4),
(49, 4, 2, 6, 'Zahra Ponsel', 'Lama Usaha', 5),
(50, 4, 2, 5, 'Zahra Ponsel', 'Tanggungan Keluarga', 2),
(51, 4, 2, 4, 'Zahra Ponsel', 'Kepemilikan SKTM', 5),
(52, 4, 2, 3, 'Zahra Ponsel', 'Riwayat Bantuan', 5),
(53, 4, 3, 8, 'Dewi Cemilan', 'Omzet Usaha', 4),
(54, 4, 3, 7, 'Dewi Cemilan', 'Aset Usaha', 1),
(55, 4, 3, 6, 'Dewi Cemilan', 'Lama Usaha', 1),
(56, 4, 3, 5, 'Dewi Cemilan', 'Tanggungan Keluarga', 2),
(57, 4, 3, 4, 'Dewi Cemilan', 'Kepemilikan SKTM', 1),
(58, 4, 3, 3, 'Dewi Cemilan', 'Riwayat Bantuan', 5),
(59, 4, 4, 8, 'Crispy Crunchy', 'Omzet Usaha', 3),
(60, 4, 4, 7, 'Crispy Crunchy', 'Aset Usaha', 2),
(61, 4, 4, 6, 'Crispy Crunchy', 'Lama Usaha', 1),
(62, 4, 4, 5, 'Crispy Crunchy', 'Tanggungan Keluarga', 3),
(63, 4, 4, 4, 'Crispy Crunchy', 'Kepemilikan SKTM', 1),
(64, 4, 4, 3, 'Crispy Crunchy', 'Riwayat Bantuan', 5),
(65, 4, 5, 8, 'Medina Beauty and Accessories', 'Omzet Usaha', 2),
(66, 4, 5, 7, 'Medina Beauty and Accessories', 'Aset Usaha', 1),
(67, 4, 5, 6, 'Medina Beauty and Accessories', 'Lama Usaha', 2),
(68, 4, 5, 5, 'Medina Beauty and Accessories', 'Tanggungan Keluarga', 3),
(69, 4, 5, 4, 'Medina Beauty and Accessories', 'Kepemilikan SKTM', 1),
(70, 4, 5, 3, 'Medina Beauty and Accessories', 'Riwayat Bantuan', 5),
(71, 4, 6, 8, 'Rayyan Mart', 'Omzet Usaha', 3),
(72, 4, 6, 7, 'Rayyan Mart', 'Aset Usaha', 1),
(73, 4, 6, 6, 'Rayyan Mart', 'Lama Usaha', 5),
(74, 4, 6, 5, 'Rayyan Mart', 'Tanggungan Keluarga', 2),
(75, 4, 6, 4, 'Rayyan Mart', 'Kepemilikan SKTM', 1),
(76, 4, 6, 3, 'Rayyan Mart', 'Riwayat Bantuan', 5),
(77, 4, 7, 8, 'Riski Fotokopi', 'Omzet Usaha', 3),
(78, 4, 7, 7, 'Riski Fotokopi', 'Aset Usaha', 1),
(79, 4, 7, 6, 'Riski Fotokopi', 'Lama Usaha', 5),
(80, 4, 7, 5, 'Riski Fotokopi', 'Tanggungan Keluarga', 2),
(81, 4, 7, 4, 'Riski Fotokopi', 'Kepemilikan SKTM', 5),
(82, 4, 7, 3, 'Riski Fotokopi', 'Riwayat Bantuan', 5),
(83, 4, 8, 8, 'Warung CRC', 'Omzet Usaha', 3),
(84, 4, 8, 7, 'Warung CRC', 'Aset Usaha', 2),
(85, 4, 8, 6, 'Warung CRC', 'Lama Usaha', 4),
(86, 4, 8, 5, 'Warung CRC', 'Tanggungan Keluarga', 3),
(87, 4, 8, 4, 'Warung CRC', 'Kepemilikan SKTM', 1),
(88, 4, 8, 3, 'Warung CRC', 'Riwayat Bantuan', 5),
(89, 4, 1, 9, 'Bandha Laris', 'apa aja', 12);

-- --------------------------------------------------------

--
-- Table structure for table `normalisasi`
--

CREATE TABLE `normalisasi` (
  `id_normalisasi` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `normalisasi` double NOT NULL,
  `sqrt_normalisasi` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi`
--

INSERT INTO `normalisasi` (`id_normalisasi`, `id_periode_penilaian`, `id_kriteria`, `normalisasi`, `sqrt_normalisasi`) VALUES
(1, 1, 1, 66, 8),
(2, 1, 2, 66, 8),
(3, 1, 3, 37, 6),
(4, 1, 4, 31, 6),
(5, 2, 1, 66, 8),
(6, 2, 2, 52, 7),
(7, 2, 3, 42, 6),
(8, 2, 4, 49, 7),
(9, 4, 3, 200, 14.1421),
(10, 4, 4, 80, 8.9443),
(11, 4, 5, 52, 7.2111),
(12, 4, 6, 106, 10.2956),
(13, 4, 7, 44, 6.6332),
(14, 4, 8, 74, 8.6023),
(15, 4, 9, 144, 12);

-- --------------------------------------------------------

--
-- Table structure for table `normalisasi_terbobot`
--

CREATE TABLE `normalisasi_terbobot` (
  `id_normalisasi_terbobot` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `id_umkm` int NOT NULL,
  `normalisasi_terbobot` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi_terbobot`
--

INSERT INTO `normalisasi_terbobot` (`id_normalisasi_terbobot`, `id_periode_penilaian`, `id_kriteria`, `id_umkm`, `normalisasi_terbobot`) VALUES
(1, 1, 1, 1, '20'),
(2, 1, 2, 1, '11.25'),
(3, 1, 3, 1, '3.33'),
(4, 1, 4, 1, '10'),
(5, 1, 1, 2, '15'),
(6, 1, 2, 2, '11.25'),
(7, 1, 3, 2, '3.33'),
(8, 1, 4, 2, '3.33'),
(9, 1, 1, 3, '15'),
(10, 1, 2, 3, '15'),
(11, 1, 3, 3, '6.67'),
(12, 1, 4, 3, '3.33'),
(13, 1, 1, 4, '20'),
(14, 1, 2, 4, '15'),
(15, 1, 3, 4, '5'),
(16, 1, 4, 4, '13.33'),
(17, 1, 1, 5, '20'),
(18, 1, 2, 5, '15'),
(19, 1, 3, 5, '3.33'),
(20, 1, 4, 5, '6.67'),
(21, 2, 1, 1, '20'),
(22, 2, 2, 1, '12.86'),
(23, 2, 3, 1, '3.33'),
(24, 2, 4, 1, '11.43'),
(25, 2, 1, 2, '15'),
(26, 2, 2, 2, '12.86'),
(27, 2, 3, 2, '5'),
(28, 2, 4, 2, '5.71'),
(29, 2, 1, 3, '15'),
(30, 2, 2, 3, '12.86'),
(31, 2, 3, 3, '6.67'),
(32, 2, 4, 3, '8.57'),
(33, 2, 1, 4, '20'),
(34, 2, 2, 4, '17.14'),
(35, 2, 3, 4, '5'),
(36, 2, 4, 4, '5.71'),
(37, 2, 1, 5, '20'),
(38, 2, 2, 5, '12.86'),
(39, 2, 3, 5, '3.33'),
(40, 2, 4, 5, '11.43'),
(41, 4, 3, 1, '0.0473'),
(42, 4, 4, 1, '0.1116'),
(43, 4, 5, 1, '0.0801'),
(44, 4, 6, 1, '0.0593'),
(45, 4, 7, 1, '0.0526'),
(46, 4, 8, 1, '0.064'),
(47, 4, 3, 2, '0.0473'),
(48, 4, 4, 2, '0.1116'),
(49, 4, 5, 2, '0.0534'),
(50, 4, 6, 2, '0.0989'),
(51, 4, 7, 2, '0.0526'),
(52, 4, 8, 2, '0.064'),
(53, 4, 3, 3, '0.0473'),
(54, 4, 4, 3, '0.0223'),
(55, 4, 5, 3, '0.0534'),
(56, 4, 6, 3, '0.0198'),
(57, 4, 7, 3, '0.0131'),
(58, 4, 8, 3, '0.0853'),
(59, 4, 3, 4, '0.0473'),
(60, 4, 4, 4, '0.0223'),
(61, 4, 5, 4, '0.0801'),
(62, 4, 6, 4, '0.0198'),
(63, 4, 7, 4, '0.0263'),
(64, 4, 8, 4, '0.064'),
(65, 4, 3, 5, '0.0473'),
(66, 4, 4, 5, '0.0223'),
(67, 4, 5, 5, '0.0801'),
(68, 4, 6, 5, '0.0396'),
(69, 4, 7, 5, '0.0131'),
(70, 4, 8, 5, '0.0426'),
(71, 4, 3, 6, '0.0473'),
(72, 4, 4, 6, '0.0223'),
(73, 4, 5, 6, '0.0534'),
(74, 4, 6, 6, '0.0989'),
(75, 4, 7, 6, '0.0131'),
(76, 4, 8, 6, '0.064'),
(77, 4, 3, 7, '0.0473'),
(78, 4, 4, 7, '0.1116'),
(79, 4, 5, 7, '0.0534'),
(80, 4, 6, 7, '0.0989'),
(81, 4, 7, 7, '0.0131'),
(82, 4, 8, 7, '0.064'),
(83, 4, 3, 8, '0.0473'),
(84, 4, 4, 8, '0.0223'),
(85, 4, 5, 8, '0.0801'),
(86, 4, 6, 8, '0.0791'),
(87, 4, 7, 8, '0.0263'),
(88, 4, 8, 8, '0.064'),
(89, 4, 9, 1, '0.912'),
(90, 4, 9, 2, '0'),
(91, 4, 9, 3, '0'),
(92, 4, 9, 4, '0'),
(93, 4, 9, 5, '0'),
(94, 4, 9, 6, '0'),
(95, 4, 9, 7, '0'),
(96, 4, 9, 8, '0');

-- --------------------------------------------------------

--
-- Table structure for table `periode_penilaian`
--

CREATE TABLE `periode_penilaian` (
  `id_periode_penilaian` int NOT NULL,
  `tanggal` varchar(25) NOT NULL,
  `keterangan` text NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `periode_penilaian`
--

INSERT INTO `periode_penilaian` (`id_periode_penilaian`, `tanggal`, `keterangan`, `status`) VALUES
(1, '2023-06-11', 'Testting', 'Selesai'),
(2, '2023-05-01', 'Testting2', 'Selesai'),
(3, '2023-04-05', 'Testting3', 'Proses'),
(4, '2026-08-16', 'UMKM Lhokseumawe', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `preferensi`
--

CREATE TABLE `preferensi` (
  `id_preferensi` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_umkm` int NOT NULL,
  `positif` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `negatif` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `preferensi` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `preferensi`
--

INSERT INTO `preferensi` (`id_preferensi`, `id_periode_penilaian`, `id_umkm`, `positif`, `negatif`, `preferensi`) VALUES
(1, 1, 1, '6.03', '5', '0.45'),
(2, 1, 2, '7.83', '6.67', '0.46'),
(3, 1, 3, '6.01', '8.35', '0.58'),
(4, 1, 4, '6.87', '7.28', '0.51'),
(5, 1, 5, '3.34', '7.08', '0.68'),
(6, 2, 1, '6.14', '5', '0.45'),
(7, 2, 2, '7.37', '5.96', '0.45'),
(8, 2, 3, '6.58', '4.4', '0.4'),
(9, 2, 4, '3.31', '8.88', '0.73'),
(10, 2, 5, '6.14', '5', '0.45'),
(11, 4, 1, '0.0598', '0.1035', '0.6338'),
(12, 4, 2, '0.0522', '0.1212', '0.699'),
(13, 4, 3, '0.1295', '0.0394', '0.2335'),
(14, 4, 4, '0.1219', '0.0431', '0.2612'),
(15, 4, 5, '0.1072', '0.0669', '0.3843'),
(16, 4, 6, '0.0956', '0.0909', '0.4874'),
(17, 4, 7, '0.0342', '0.1274', '0.7886'),
(18, 4, 8, '0.0948', '0.0733', '0.4361');

-- --------------------------------------------------------

--
-- Table structure for table `solusi_ideal`
--

CREATE TABLE `solusi_ideal` (
  `id_solusi_ideal` int NOT NULL,
  `id_periode_penilaian` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `positif_negatif` varchar(25) NOT NULL,
  `solusi_ideal` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `solusi_ideal`
--

INSERT INTO `solusi_ideal` (`id_solusi_ideal`, `id_periode_penilaian`, `id_kriteria`, `positif_negatif`, `solusi_ideal`) VALUES
(1, 1, 1, 'Positif', '20'),
(2, 1, 2, 'Positif', '15'),
(3, 1, 3, 'Positif', '6.67'),
(4, 1, 4, 'Positif', '6.67'),
(5, 1, 1, 'Negatif', '15'),
(6, 1, 2, 'Negatif', '11.25'),
(7, 1, 3, 'Negatif', '3.33'),
(8, 1, 4, 'Negatif', '10'),
(9, 2, 1, 'Positif', '20'),
(10, 2, 2, 'Positif', '17.14'),
(11, 2, 3, 'Positif', '6.67'),
(12, 2, 4, 'Positif', '8.57'),
(13, 2, 1, 'Negatif', '15'),
(14, 2, 2, 'Negatif', '12.86'),
(15, 2, 3, 'Negatif', '3.33'),
(16, 2, 4, 'Negatif', '11.43'),
(17, 4, 3, 'Positif', '0.0473'),
(18, 4, 4, 'Positif', '0.1116'),
(19, 4, 5, 'Positif', '0.0801'),
(20, 4, 6, 'Positif', '0.0989'),
(21, 4, 7, 'Positif', '0.0131'),
(22, 4, 8, 'Positif', '0.0426'),
(23, 4, 3, 'Negatif', '0.0473'),
(24, 4, 4, 'Negatif', '0.0223'),
(25, 4, 5, 'Negatif', '0.0534'),
(26, 4, 6, 'Negatif', '0.0198'),
(27, 4, 7, 'Negatif', '0.0526'),
(28, 4, 8, 'Negatif', '0.0853'),
(29, 4, 9, 'Positif', '0.912'),
(30, 4, 9, 'Negatif', '0');

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id_umkm` int NOT NULL,
  `id_akses` int NOT NULL,
  `nama_umkm` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nip` varchar(100) DEFAULT NULL,
  `kontak` varchar(25) NOT NULL,
  `nama_pemilik` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id_umkm`, `id_akses`, `nama_umkm`, `nip`, `kontak`, `nama_pemilik`) VALUES
(1, 4, 'Bandha Laris', 'Jl. Darussalam, Banda Sakti, Lhokseumawe', '082246128425', 'Mariyadayu Ms'),
(2, 5, 'Zahra Ponsel', 'Jl. Darussalam, Kec. Banda Sakti, Lhoksuemawe', '081266993724', 'Virra'),
(3, 6, 'Dewi Cemilan', 'Jl Medan-Banda Aceh, Desa Blang Pulo', '085277955456', 'Puspita Dewi'),
(4, 9, 'Crispy Crunchy', 'Jl. Medan-Banda Aceh, Pintu dua Arun', '082162240720', 'Fuadi'),
(5, 10, 'Medina Beauty and Accessories', 'Jl Pipa Len, Kecamatan Muara Ssatu', '085372674746', 'Munawwarah'),
(6, 16, 'Rayyan Mart', 'Jl. Pipa Len, Desa Padang Sakti', '085277666150', 'Mhd Riduan'),
(7, 17, 'Riski Fotokopi', 'Jl Medan-Banda Aceh, Simpang Len', '085277123424', 'Fajriah'),
(8, 18, 'Warung CRC', 'Jl Medan-Banda Aceh, Simpang Len', '082311705786', 'Yuni Shara');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akses`
--
ALTER TABLE `akses`
  ADD PRIMARY KEY (`id_akses`);

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `anp_perbandingan`
--
ALTER TABLE `anp_perbandingan`
  ADD PRIMARY KEY (`id_perbandingan_anp`);

--
-- Indexes for table `bobot_anp`
--
ALTER TABLE `bobot_anp`
  ADD PRIMARY KEY (`id_bobot_anp`);

--
-- Indexes for table `bobot_swara`
--
ALTER TABLE `bobot_swara`
  ADD PRIMARY KEY (`id_swara`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `normalisasi`
--
ALTER TABLE `normalisasi`
  ADD PRIMARY KEY (`id_normalisasi`);

--
-- Indexes for table `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  ADD PRIMARY KEY (`id_normalisasi_terbobot`);

--
-- Indexes for table `periode_penilaian`
--
ALTER TABLE `periode_penilaian`
  ADD PRIMARY KEY (`id_periode_penilaian`);

--
-- Indexes for table `preferensi`
--
ALTER TABLE `preferensi`
  ADD PRIMARY KEY (`id_preferensi`);

--
-- Indexes for table `solusi_ideal`
--
ALTER TABLE `solusi_ideal`
  ADD PRIMARY KEY (`id_solusi_ideal`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id_umkm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akses`
--
ALTER TABLE `akses`
  MODIFY `id_akses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `anp_perbandingan`
--
ALTER TABLE `anp_perbandingan`
  MODIFY `id_perbandingan_anp` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bobot_anp`
--
ALTER TABLE `bobot_anp`
  MODIFY `id_bobot_anp` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bobot_swara`
--
ALTER TABLE `bobot_swara`
  MODIFY `id_swara` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id_divisi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `normalisasi`
--
ALTER TABLE `normalisasi`
  MODIFY `id_normalisasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `normalisasi_terbobot`
--
ALTER TABLE `normalisasi_terbobot`
  MODIFY `id_normalisasi_terbobot` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `periode_penilaian`
--
ALTER TABLE `periode_penilaian`
  MODIFY `id_periode_penilaian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `preferensi`
--
ALTER TABLE `preferensi`
  MODIFY `id_preferensi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `solusi_ideal`
--
ALTER TABLE `solusi_ideal`
  MODIFY `id_solusi_ideal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id_umkm` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
