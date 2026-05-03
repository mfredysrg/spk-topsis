-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 28, 2025 at 08:02 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `husnul`
--

-- --------------------------------------------------------

--
-- Table structure for table `akses`
--

DROP TABLE IF EXISTS `akses`;
CREATE TABLE IF NOT EXISTS `akses` (
  `id_akses` int(15) NOT NULL AUTO_INCREMENT,
  `id_divisi` int(10) DEFAULT NULL,
  `nama` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `akses` varchar(20) NOT NULL,
  PRIMARY KEY (`id_akses`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `akses`
--

INSERT INTO `akses` (`id_akses`, `id_divisi`, `nama`, `email`, `password`, `akses`) VALUES
(1, 0, 'Hudzaifa', 'dhiforester@gmail.com', 'f4a3229c9c5f1bdd9c6a6791080791b7', 'HRD'),
(2, 6, 'Windi Yanuariska', 'windygiga@gmail.com', 'd748c957a0018bfe3d974f8c44e4f3b7', 'Kepala Divisi'),
(3, 1, 'Syamsul Maarif', 'syamsulmaarif@gmail.com', '39a4dda18c93ccf4d47e44580bcaf1a0', 'Kepala Divisi'),
(4, 1, 'Ujang Suherman', 'ujang@gmail.com', '2ba672e97d6899f4d75e390daa5a35fe', 'Karyawan'),
(5, 1, 'Sabil Kurniawan', 'sabil@gmail.com', '6e5870b70ea0f4b88932910f017c1b53', 'Karyawan'),
(6, 1, 'Adung Sudiadi', 'adung@gmail.com', '5203e18ca19b351e51d598cdc9adeb62', 'Karyawan'),
(7, 7, 'Didi Muhadi', 'didimuhadi@gmail.com', '9ccd1206b99e130c54fb4e9fa1771513', 'Kepala Divisi'),
(8, 4, 'Bayu Anugerah', 'bayuanugerah@gmail.com', 'cfd111106dc95e430bf5eff5f2d71b87', 'Kepala Divisi'),
(9, 6, 'Mia Rusmia, S.Pt.', 'mia@gmail.com', 'a0e9c90c2755cb0611ae7f198604905f', 'Karyawan'),
(10, 6, 'Elfa Robi, Lc.', 'elfa@gmail.com', '29e4973de27a17dbeb7968f581abe96e', 'Karyawan'),
(11, 0, 'Ustadz Mulyadin, Lc', 'mulyadin@gmail.com', '4eb5ed033b5aead94fbddd707996c4a3', 'Pimpinan');

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

DROP TABLE IF EXISTS `alternatif`;
CREATE TABLE IF NOT EXISTS `alternatif` (
  `id_alternatif` int(15) NOT NULL AUTO_INCREMENT,
  `id_kriteria` int(15) NOT NULL,
  `alternatif` text NOT NULL,
  `nilai` int(15) NOT NULL,
  PRIMARY KEY (`id_alternatif`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `id_kriteria`, `alternatif`, `nilai`) VALUES
(1, 4, 'Sangat Baik', 4),
(2, 4, 'Baik', 3),
(3, 4, 'Sedang', 2),
(4, 4, 'Buruk', 1),
(5, 3, 'Sangat Baik', 4),
(6, 3, 'Baik', 3),
(7, 3, 'Sedang', 2),
(8, 3, 'Buruk', 1),
(9, 2, 'Sangat Baik', 4),
(10, 2, 'Baik', 3),
(11, 2, 'Sedang', 2),
(12, 2, 'Buruk', 1),
(13, 1, 'Sangat Baik', 4),
(14, 1, 'Baik', 3),
(15, 1, 'Sedang', 2),
(16, 1, 'Buruk', 1),
(17, 5, 'Tinggi', 5),
(18, 5, 'Sedang', 3),
(19, 5, 'Rendah', 1);

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

DROP TABLE IF EXISTS `divisi`;
CREATE TABLE IF NOT EXISTS `divisi` (
  `id_divisi` int(10) NOT NULL AUTO_INCREMENT,
  `nama_divisi` text NOT NULL,
  `keterangan` text NOT NULL,
  PRIMARY KEY (`id_divisi`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

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
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE IF NOT EXISTS `karyawan` (
  `id_karyawan` int(15) NOT NULL AUTO_INCREMENT,
  `id_akses` int(15) NOT NULL,
  `nama` text NOT NULL,
  `nip` varchar(25) NOT NULL,
  `kontak` varchar(25) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  PRIMARY KEY (`id_karyawan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `id_akses`, `nama`, `nip`, `kontak`, `jabatan`) VALUES
(1, 4, 'Ujang Suherman', 'PPHH0001', '08960000001', 'Driver'),
(2, 5, 'Sabil Kurniawan', 'PPHH0002', '08960000002', 'Driver'),
(3, 6, 'Adung Sudiadi', 'PPHH0003', '08960000003', 'Driver'),
(4, 9, 'Mia Rusmia, S.Pt.', 'PPHH0004', '08960000004', 'Guru'),
(5, 10, 'Elfa Robi, Lc.', 'PPHH00055', '08960000005', 'Guru');

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

DROP TABLE IF EXISTS `kriteria`;
CREATE TABLE IF NOT EXISTS `kriteria` (
  `id_kriteria` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kriteria` varchar(20) NOT NULL,
  `kriteria` text NOT NULL,
  `atribut` varchar(20) NOT NULL,
  `bobot` int(15) NOT NULL,
  PRIMARY KEY (`id_kriteria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kode_kriteria`, `kriteria`, `atribut`, `bobot`) VALUES
(1, 'K1', 'Kopetensi', 'Benefit', 40),
(2, 'K2', 'Loyalitas', 'Benefit', 30),
(3, 'K3', 'Tarbiyah', 'Benefit', 10),
(4, 'K4', 'Tahsin', 'Benefit', 20),
(5, 'A', 'Absensi', 'Benefit', 20);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
CREATE TABLE IF NOT EXISTS `nilai` (
  `id_nilai` int(15) NOT NULL AUTO_INCREMENT,
  `id_periode_penilaian` int(15) NOT NULL,
  `id_karyawan` int(15) NOT NULL,
  `id_kriteria` int(15) NOT NULL,
  `nama` text NOT NULL,
  `kriteria` text NOT NULL,
  `nilai` int(10) NOT NULL,
  PRIMARY KEY (`id_nilai`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_periode_penilaian`, `id_karyawan`, `id_kriteria`, `nama`, `kriteria`, `nilai`) VALUES
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
(40, 2, 5, 4, 'Elfa Robi, Lc.', 'Tahsin', 4);

-- --------------------------------------------------------

--
-- Table structure for table `normalisasi`
--

DROP TABLE IF EXISTS `normalisasi`;
CREATE TABLE IF NOT EXISTS `normalisasi` (
  `id_normalisasi` int(15) NOT NULL AUTO_INCREMENT,
  `id_periode_penilaian` int(15) NOT NULL,
  `id_kriteria` int(15) NOT NULL,
  `normalisasi` int(15) NOT NULL,
  `sqrt_normalisasi` int(15) NOT NULL,
  PRIMARY KEY (`id_normalisasi`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

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
(8, 2, 4, 49, 7);

-- --------------------------------------------------------

--
-- Table structure for table `normalisasi_terbobot`
--

DROP TABLE IF EXISTS `normalisasi_terbobot`;
CREATE TABLE IF NOT EXISTS `normalisasi_terbobot` (
  `id_normalisasi_terbobot` int(15) NOT NULL AUTO_INCREMENT,
  `id_periode_penilaian` int(15) NOT NULL,
  `id_kriteria` int(15) NOT NULL,
  `id_karyawan` int(15) NOT NULL,
  `normalisasi_terbobot` varchar(20) NOT NULL,
  PRIMARY KEY (`id_normalisasi_terbobot`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `normalisasi_terbobot`
--

INSERT INTO `normalisasi_terbobot` (`id_normalisasi_terbobot`, `id_periode_penilaian`, `id_kriteria`, `id_karyawan`, `normalisasi_terbobot`) VALUES
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
(40, 2, 4, 5, '11.43');

-- --------------------------------------------------------

--
-- Table structure for table `periode_penilaian`
--

DROP TABLE IF EXISTS `periode_penilaian`;
CREATE TABLE IF NOT EXISTS `periode_penilaian` (
  `id_periode_penilaian` int(15) NOT NULL AUTO_INCREMENT,
  `tanggal` varchar(25) NOT NULL,
  `keterangan` text NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id_periode_penilaian`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `periode_penilaian`
--

INSERT INTO `periode_penilaian` (`id_periode_penilaian`, `tanggal`, `keterangan`, `status`) VALUES
(1, '2023-06-11', 'Testting', 'Selesai'),
(2, '2023-05-01', 'Testting2', 'Selesai'),
(3, '2023-04-05', 'Testting3', 'Proses'),
(4, '2024-01-13', 'Testting', 'Proses');

-- --------------------------------------------------------

--
-- Table structure for table `preferensi`
--

DROP TABLE IF EXISTS `preferensi`;
CREATE TABLE IF NOT EXISTS `preferensi` (
  `id_preferensi` int(15) NOT NULL AUTO_INCREMENT,
  `id_periode_penilaian` int(15) NOT NULL,
  `id_karyawan` int(15) NOT NULL,
  `positif` varchar(15) NOT NULL,
  `negatif` varchar(15) NOT NULL,
  `preferensi` varchar(15) NOT NULL,
  PRIMARY KEY (`id_preferensi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `preferensi`
--

INSERT INTO `preferensi` (`id_preferensi`, `id_periode_penilaian`, `id_karyawan`, `positif`, `negatif`, `preferensi`) VALUES
(1, 1, 1, '6.03', '5', '0.45'),
(2, 1, 2, '7.83', '6.67', '0.46'),
(3, 1, 3, '6.01', '8.35', '0.58'),
(4, 1, 4, '6.87', '7.28', '0.51'),
(5, 1, 5, '3.34', '7.08', '0.68'),
(6, 2, 1, '6.14', '5', '0.45'),
(7, 2, 2, '7.37', '5.96', '0.45'),
(8, 2, 3, '6.58', '4.4', '0.4'),
(9, 2, 4, '3.31', '8.88', '0.73'),
(10, 2, 5, '6.14', '5', '0.45');

-- --------------------------------------------------------

--
-- Table structure for table `solusi_ideal`
--

DROP TABLE IF EXISTS `solusi_ideal`;
CREATE TABLE IF NOT EXISTS `solusi_ideal` (
  `id_solusi_ideal` int(15) NOT NULL AUTO_INCREMENT,
  `id_periode_penilaian` int(15) NOT NULL,
  `id_kriteria` int(15) NOT NULL,
  `positif_negatif` varchar(25) NOT NULL,
  `solusi_ideal` varchar(25) NOT NULL,
  PRIMARY KEY (`id_solusi_ideal`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

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
(16, 2, 4, 'Negatif', '11.43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
