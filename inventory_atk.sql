-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 11:45 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_atk`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `idbarang` varchar(255) NOT NULL,
  `namabarang` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `stok` int(9) DEFAULT NULL,
  `harga` int(12) DEFAULT NULL,
  `id_user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`idbarang`, `namabarang`, `brand`, `stok`, `harga`, `id_user`) VALUES
('D001', 'Lem Lilin', 'XIXI', 5, 7000, '4004'),
('X001', 'Pensil Fabel Castle', 'Fabel Castle', 4, 6000, '5555');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`) VALUES
('agus', 'agus', '$2y$10$2EKvzJ/sicFh02hU0DmvdeR3ZGcIcvlpaw4qOYKjMVi0p1.3oNwzC'),
('ari', 'Ari syafri', '$2y$10$.D1GtfER12DnEBpR64CYX.1Q/v4KT0OC6FnMUtU6HwQo0TTIEdFku'),
('tes', 'tes', '$2y$10$nx0M4NAmN8Vq81.HfmKujeM7tGSe/1o1pqRRq2PE3pt06src6shR.'),
('tes3', 'tes3', '$2y$10$08jlt7Fc30TTrBr.UiKMK.ojAeAHl6FebeJjQiTVdU6e9G8tiRkuy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`idbarang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sessions_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
