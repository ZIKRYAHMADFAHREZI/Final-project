-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2024 at 08:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id_payment` int NOT NULL,
  `id_user` int NOT NULL,
  `id_pay_method` int NOT NULL,
  `id_reservation` int NOT NULL,
  `img` tinytext NOT NULL,
  `name_send` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_methods`
--

CREATE TABLE `pay_methods` (
  `id_pay_method` int NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `no_pay` varchar(50) DEFAULT NULL,
  `name_acc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_methods`
--

INSERT INTO `pay_methods` (`id_pay_method`, `method`, `no_pay`, `name_acc`) VALUES
(1, 'Dana', '0878-7888-4000', 'Bagus Subandar'),
(2, 'Gopay', '0878-7888-4000', 'Bagus Subandar'),
(3, 'BNI', '12313223', 'Bagus Subandar');

-- --------------------------------------------------------

--
-- Table structure for table `resevations`
--

CREATE TABLE `resevations` (
  `id_reservation` int NOT NULL,
  `id_user` int NOT NULL,
  `id_room` int NOT NULL,
  `date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `destroy_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type` int DEFAULT NULL,
  `id_room_rate` int DEFAULT NULL,
  `id_payment` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id_room` int NOT NULL,
  `id_type` int NOT NULL,
  `number_room` int NOT NULL,
  `status` enum('available','unvailable','pending') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id_room`, `id_type`, `number_room`, `status`) VALUES
(1, 1, 66, 'available'),
(2, 1, 104, 'available'),
(3, 2, 58, 'available'),
(4, 2, 60, 'available'),
(5, 3, 54, 'available'),
(6, 3, 62, 'available'),
(7, 4, 101, 'available'),
(8, 4, 102, 'available'),
(9, 4, 103, 'available'),
(10, 4, 105, 'available'),
(11, 4, 107, 'available'),
(12, 5, 142, 'available'),
(13, 5, 144, 'available'),
(14, 5, 146, 'available'),
(15, 5, 148, 'available'),
(16, 5, 150, 'available'),
(17, 5, 152, 'available'),
(18, 5, 154, 'available'),
(19, 6, 106, 'available'),
(20, 6, 108, 'available'),
(21, 6, 110, 'available'),
(22, 6, 112, 'available'),
(23, 6, 118, 'available'),
(24, 6, 120, 'available'),
(25, 6, 122, 'available'),
(26, 6, 124, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `room_rates`
--

CREATE TABLE `room_rates` (
  `id_room_rate` int NOT NULL,
  `id_room` int NOT NULL,
  `id_type` int NOT NULL,
  `12hour` decimal(10,3) NOT NULL,
  `24hour` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_rates`
--

INSERT INTO `room_rates` (`id_room_rate`, `id_room`, `id_type`, `12hour`, `24hour`) VALUES
(1, 1, 1, '250.000', '350.000'),
(2, 2, 1, '250.000', '350.000'),
(3, 3, 2, '250.000', '350.000'),
(4, 4, 2, '250.000', '350.000'),
(5, 5, 3, '200.000', '300.000'),
(6, 6, 3, '200.000', '300.000'),
(7, 7, 4, '175.000', '275.000'),
(8, 8, 4, '175.000', '275.000'),
(9, 9, 4, '175.000', '275.000'),
(10, 10, 4, '175.000', '275.000'),
(11, 11, 4, '175.000', '275.000'),
(12, 12, 5, '150.000', '200.000'),
(13, 13, 5, '150.000', '200.000'),
(14, 14, 5, '150.000', '200.000'),
(15, 15, 5, '150.000', '200.000'),
(16, 16, 5, '150.000', '200.000'),
(17, 17, 5, '150.000', '200.000'),
(18, 18, 5, '150.000', '200.000'),
(19, 19, 6, '120.000', '150.000'),
(20, 20, 6, '120.000', '150.000'),
(21, 21, 6, '120.000', '150.000'),
(22, 22, 6, '120.000', '150.000'),
(23, 23, 6, '120.000', '150.000'),
(24, 24, 6, '120.000', '150.000'),
(25, 25, 6, '120.000', '150.000');

-- --------------------------------------------------------

--
-- Table structure for table `transits`
--

CREATE TABLE `transits` (
  `id_transit` int NOT NULL,
  `id_room` int NOT NULL,
  `id_type` int NOT NULL,
  `price` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transits`
--

INSERT INTO `transits` (`id_transit`, `id_room`, `id_type`, `price`) VALUES
(1, 7, 4, '150.000'),
(2, 8, 4, '150.000'),
(3, 9, 4, '150.000'),
(4, 10, 4, '150.000'),
(5, 11, 4, '150.000'),
(6, 19, 6, '100.000'),
(7, 20, 6, '100.000'),
(8, 21, 6, '100.000'),
(9, 22, 6, '100.000'),
(10, 23, 1, '100.000'),
(11, 24, 6, '100.000'),
(12, 25, 6, '100.000');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `type`, `description`, `img`) VALUES
(1, 'Deluxe Ac', 'Kamar bersama admin', 'deluxe.jpg'),
(2, 'Familly Room', 'Kamar bersama admin', 'familly.jpg'),
(3, 'Superior Ac', 'Kamar bersama admin', 'superAc.jpg'),
(4, 'Standar Ac', 'Kamar bersama admin', 'StandAc.jpg'),
(5, 'Superior Fan', 'Kamar bersama admin', 'SuperFan.jpg'),
(6, 'Standar Fan', 'Kamar bersama admin', 'StandFan.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$R1eJRC2U4Y8YWs5PuswEJOErrPiQk56rZ6s7hOCPBn6k5H.PqFX5a', '2024-11-26 11:23:58', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id_profile` int NOT NULL,
  `id_user` int NOT NULL,
  `firs_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `date_of_birth` date NOT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pay_method` (`id_pay_method`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `pay_methods`
--
ALTER TABLE `pay_methods`
  ADD PRIMARY KEY (`id_pay_method`);

--
-- Indexes for table `resevations`
--
ALTER TABLE `resevations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_room` (`id_room`),
  ADD KEY `fk_id_type` (`id_type`),
  ADD KEY `fk_id_room_rate` (`id_room_rate`),
  ADD KEY `fk_id_payment` (`id_payment`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id_room`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `room_rates`
--
ALTER TABLE `room_rates`
  ADD PRIMARY KEY (`id_room_rate`),
  ADD KEY `id_room` (`id_room`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `transits`
--
ALTER TABLE `transits`
  ADD PRIMARY KEY (`id_transit`),
  ADD KEY `id_room` (`id_room`),
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
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pay_methods`
--
ALTER TABLE `pay_methods`
  MODIFY `id_pay_method` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resevations`
--
ALTER TABLE `resevations`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_room` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `room_rates`
--
ALTER TABLE `room_rates`
  MODIFY `id_room_rate` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transits`
--
ALTER TABLE `transits`
  MODIFY `id_transit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`id_pay_method`) REFERENCES `pay_methods` (`id_pay_method`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`id_reservation`) REFERENCES `resevations` (`id_reservation`);

--
-- Constraints for table `resevations`
--
ALTER TABLE `resevations`
  ADD CONSTRAINT `fk_id_payment` FOREIGN KEY (`id_payment`) REFERENCES `payments` (`id_payment`),
  ADD CONSTRAINT `fk_id_room_rate` FOREIGN KEY (`id_room_rate`) REFERENCES `room_rates` (`id_room_rate`),
  ADD CONSTRAINT `fk_id_type` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `resevations_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `resevations_ibfk_2` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id_room`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

--
-- Constraints for table `room_rates`
--
ALTER TABLE `room_rates`
  ADD CONSTRAINT `room_rates_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id_room`),
  ADD CONSTRAINT `room_rates_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

--
-- Constraints for table `transits`
--
ALTER TABLE `transits`
  ADD CONSTRAINT `transits_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id_room`),
  ADD CONSTRAINT `transits_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
