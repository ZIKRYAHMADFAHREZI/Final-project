-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 07:58 AM
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
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pay_methods`
--

CREATE TABLE `pay_methods` (
  `id_pay` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `no_pay` varchar(50) NOT NULL,
  `name_acc` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pay_methods`
--

INSERT INTO `pay_methods` (`id_pay`, `method`, `no_pay`, `name_acc`) VALUES
(1, 'Dana', '0878-5299-4008', 'Bagus Subandar'),
(2, 'Gopay', '0878-5299-4008', 'Bagus Subandar'),
(3, 'BNI', '1242729910', 'Bagus Subandar');

-- --------------------------------------------------------

--
-- Table structure for table `profile_user`
--

CREATE TABLE `profile_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `date_of_birth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT curdate(),
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `dekripsi` text DEFAULT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `nama`, `dekripsi`, `img`) VALUES
(1, 'Deluxe Ac', 'Kamar dengan vasilitas lengkap', 'kt1.png'),
(2, 'Familly Room', 'Kamar keluarga broken home', 'kt2.jpg'),
(3, 'Superior Ac', 'Ac memiliki kekuatan super', 'kt3.jpg'),
(4, 'Standar Ac', 'Ac dengan standar 2', 'kt3.jpg'),
(5, 'Superior Fan', 'Kipas dengan kekuatan super', 'kt1.png'),
(6, 'Standar Fan', 'Kipas angin dengan standar motor', 'kt2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'asiap', 'aadsfds@gmail.com', '$2y$10$CWRPnqbF6CT/UHDH1n7h9e1sLv.Bn2Bti0Hm923Dvw01cIFQueNb6', '2024-11-17 11:32:17'),
(2, 'Azaa31', 'azaanul10@gmail.com', '$2y$10$yzeblE49MpGMv.f3//y2p.uFqrT3r0Tbvrx8WE/SoMk3zVp1/D.lq', '2024-11-17 12:11:49'),
(3, 'jskkahs', 'aasa@yahoo.com', '$2y$10$JEgFwKmvMIlUMGgHJYtcFeXJNPhzGg6maDg4e19TgFWOcki4k1uhq', '2024-11-17 12:24:14'),
(4, 'admin', 'admin@admin.com', '$2y$10$dDf80owD5QSM02trXbe3H.gdzIoeXcg0gFN9op/T/sNiXJ9z34nau', '2024-11-18 07:15:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pay_methods`
--
ALTER TABLE `pay_methods`
  ADD PRIMARY KEY (`id_pay`);

--
-- Indexes for table `profile_user`
--
ALTER TABLE `profile_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pay_methods`
--
ALTER TABLE `pay_methods`
  MODIFY `id_pay` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profile_user`
--
ALTER TABLE `profile_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profile_user`
--
ALTER TABLE `profile_user`
  ADD CONSTRAINT `profile_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
