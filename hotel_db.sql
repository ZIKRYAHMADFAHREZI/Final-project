-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 06:53 AM
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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payments` int(11) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_methods` int(11) DEFAULT NULL,
  `id_reservations` int(11) DEFAULT NULL,
  `img` text DEFAULT NULL,
  `nama_send` varchar(255) DEFAULT NULL,
  `creat_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tanggal` date,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id_room` int(11) NOT NULL,
  `id_type` int(11) DEFAULT NULL,
  `number_room` int(4) NOT NULL,
  `status` enum('available','unvailable','pending') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id_room`, `id_type`, `number_room`, `status`) VALUES
(1, 1, 1, 'available'),
(2, 1, 2, 'available'),
(3, 1, 3, 'available'),
(4, 1, 4, 'available'),
(5, 1, 5, 'available'),
(6, 1, 6, 'available'),
(7, 2, 7, 'available'),
(8, 2, 8, 'available'),
(9, 3, 9, 'available'),
(10, 3, 10, 'available'),
(11, 4, 11, 'available'),
(12, 5, 12, 'available'),
(13, 5, 13, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `dekripsi` text DEFAULT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `type`, `dekripsi`, `img`) VALUES
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$6J9XHDM.TY/Dkn3VLsrlFezPX.JRWU/BYVEDyuZkIC5RAlKmN/69e', '2024-11-23 05:45:21', 'admin'),
(2, 'user', 'user@gmail.com', '$2y$10$fV3YxwIcA4Cp1k9eXby4CurPsoyOcS6qNw85U507abbUub5qVyhwG', '2024-11-23 05:47:01', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payments`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_methods` (`id_methods`),
  ADD KEY `id_reservations` (`id_reservations`);

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
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id_room`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id_type`);

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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payments` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`id_methods`) REFERENCES `pay_methods` (`id_pay`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`id_reservations`) REFERENCES `reservations` (`id_reservation`) ON DELETE CASCADE;

--
-- Constraints for table `profile_user`
--
ALTER TABLE `profile_user`
  ADD CONSTRAINT `profile_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
