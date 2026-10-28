-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 01:09 PM
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
-- Database: `db_gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `nama_barang`, `satuan`, `stok`, `lokasi`) VALUES
(9, '40000016', 'Belerang', 'kg', 4230, 'Gudang Material'),
(10, '91037284', 'Lakban putih besar', 'EA', 316, 'Gudang Material'),
(11, '50001042', 'Plastik Kemasan Gula', 'ROL', 50, 'Gudang Material'),
(12, '40000013', 'ASAM PHOSPATE', 'Kg', 1120, 'Gudang Material'),
(13, '400005654', 'TSL POWER', 'L', 1060, 'Gudang B'),
(14, '40005971', 'SODIUM CARBONATE:NA2CO3;99,8%', 'kg', 1000, 'Gudang Material'),
(15, '40000262', 'SODA ASH', 'Kg', 2270, 'Gudang Material'),
(16, '82048524', 'SHELL DIALA B.', 'L', 418, 'Gudang B'),
(17, '40000357', 'SCALE REMOVER ASAM CAIR', 'Kg', 540, 'Gudang Material'),
(18, '50001293', 'SACK;PLASTIC;50KG;58X96CM;PP;1 PLY', 'EA', 134343, 'Gudang C'),
(19, '91018511', 'PELUMAS CARDIUM COMPOUND', 'kg', 35, 'Gudang Material'),
(20, '91057007', 'LUBE OIL,  TURBINE : ISO 68;105 VISC', 'L', 400, 'Gudang Material'),
(21, '91053536', 'BOILER COMPOUND :SCALE DISPERSANT;SL', 'L', 2325, 'Gudang Material');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_keluar` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_keluar`, `tanggal`, `id_barang`, `jumlah`, `tujuan`, `keterangan`) VALUES
(3, '2026-10-02', 7, 5, 'stasiun putaran', 'percobaan'),
(4, '2026-10-04', 7, 100, 'stasiun putaran', 'percobaan sistem'),
(5, '2026-10-03', 8, 10, 'Tanaman', 'percobaan sistem\r\n'),
(6, '2026-10-22', 10, 10, 'sugar bin', 'untuk keperluan sugargin');

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `asal_barang` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `tanggal`, `id_barang`, `jumlah`, `asal_barang`, `keterangan`) VALUES
(5, '2026-10-01', 7, 10, 'supplier test', 'percobaan'),
(6, '2026-10-03', 7, 200, '', 'percobaan sistem'),
(7, '2026-10-01', 9, 20, 'supplier', 'percobaan sistem'),
(8, '2026-10-21', 13, 320, 'supplier', 'stok Masuk TSL POWER');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '35ca4d9e3b57f7eb0d4e7ec907b87b8b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_keluar`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`);

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
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
