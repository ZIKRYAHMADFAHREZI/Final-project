-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2024 at 06:16 AM
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
  `id_user` int DEFAULT NULL,
  `id_pay_method` int DEFAULT NULL,
  `id_reservation` int DEFAULT NULL,
  `img` tinytext,
  `name_send` varchar(50) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `status` enum('pending','confirmed') DEFAULT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `resevations`
--

CREATE TABLE `resevations` (
  `id_reservation` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_room` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `create_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id_room` int NOT NULL,
  `id_type` int DEFAULT NULL,
  `number_room` int DEFAULT NULL,
  `status` enum('available','unvailable','pending') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_rates`
--

CREATE TABLE `room_rates` (
  `id_room_rate` int NOT NULL,
  `id_room` int DEFAULT NULL,
  `id_type` int DEFAULT NULL,
  `id_payment` int DEFAULT NULL,
  `12hour` varchar(45) DEFAULT NULL,
  `24hour` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transits`
--

CREATE TABLE `transits` (
  `id_transit` int NOT NULL,
  `id_room` int DEFAULT NULL,
  `id_type` int DEFAULT NULL,
  `id_payment` int DEFAULT NULL,
  `hour` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `description` text,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `type`, `description`, `img`) VALUES
(1, 'Deluxe Ac', 'Kamar bersama admin', 'deluxe.jpg'),
(2, 'Familly Room', 'Kamar bersama admin', 'familly.jpg'),
(3, 'Superior Ac', 'Kamar bersama admin', 'superAc.jpg'),
(4, 'Standar Ac', 'Kamar bersama admin', 'StanAc.jpg'),
(5, 'Superior Fan', 'Kamar bersama admin', 'SuperFan.jpg'),
(6, 'Standar Fan', 'Kamar bersama admin', 'StandFan.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id_profile` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `firs_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `create_at` timestamp(6) NULL DEFAULT NULL
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
  ADD KEY `id_room` (`id_room`);

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
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_payment` (`id_payment`);

--
-- Indexes for table `transits`
--
ALTER TABLE `transits`
  ADD PRIMARY KEY (`id_transit`),
  ADD KEY `id_room` (`id_room`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_payment` (`id_payment`);

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
  MODIFY `id_pay_method` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resevations`
--
ALTER TABLE `resevations`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_room` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_rates`
--
ALTER TABLE `room_rates`
  MODIFY `id_room_rate` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transits`
--
ALTER TABLE `transits`
  MODIFY `id_transit` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `room_rates_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `room_rates_ibfk_3` FOREIGN KEY (`id_payment`) REFERENCES `payments` (`id_payment`);

--
-- Constraints for table `transits`
--
ALTER TABLE `transits`
  ADD CONSTRAINT `transits_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id_room`),
  ADD CONSTRAINT `transits_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `transits_ibfk_3` FOREIGN KEY (`id_payment`) REFERENCES `payments` (`id_payment`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
