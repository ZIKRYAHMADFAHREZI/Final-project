-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2024 at 05:37 AM
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
  `img` tinytext NOT NULL,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','finished') DEFAULT 'pending',
  `amount` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_methods`
--

CREATE TABLE `pay_methods` (
  `id_pay_method` int NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `payment_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `account_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_methods`
--

INSERT INTO `pay_methods` (`id_pay_method`, `method`, `payment_number`, `account_name`, `active`) VALUES
(1, 'Dana', '0878-7888-4000', 'Bagus Subandar', 1),
(2, 'Gopay', '0878-7888-4000', 'Bagus Subandar', 1),
(3, 'BNI', '12313223', 'Bagus Subandar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `resevations`
--

CREATE TABLE `resevations` (
  `id_reservation` int NOT NULL,
  `id_user` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `destroy_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_type` int DEFAULT NULL,
  `id_room_rate` int DEFAULT NULL,
  `id_payment` int DEFAULT NULL,
  `id_pay_method` int DEFAULT NULL,
  `payment_proof` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id_room` int NOT NULL,
  `id_type` int DEFAULT NULL,
  `number_room` int NOT NULL,
  `status` enum('available','unavailable','pending') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id_room`, `id_type`, `number_room`, `status`) VALUES
(1, 1, 66, 'available'),
(2, 1, 104, 'available'),
(3, 2, 58, 'available'),
(4, 2, 60, 'pending'),
(5, 3, 54, 'available'),
(6, 3, 62, 'available'),
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
(26, 6, 124, 'available'),
(27, 4, 101, 'available'),
(28, 6, 1, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `room_rates`
--

CREATE TABLE `room_rates` (
  `id_room_rate` int NOT NULL,
  `id_type` int NOT NULL,
  `12hour` decimal(10,2) NOT NULL,
  `24hour` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_rates`
--

INSERT INTO `room_rates` (`id_room_rate`, `id_type`, `12hour`, `24hour`) VALUES
(1, 1, '250000.00', '350000.00'),
(2, 2, '250000.00', '350000.00'),
(3, 3, '200000.00', '300000.00'),
(4, 4, '175000.00', '275000.00'),
(5, 5, '150000.00', '200000.00'),
(6, 6, '120000.00', '150000.00');

-- --------------------------------------------------------

--
-- Table structure for table `transits`
--

CREATE TABLE `transits` (
  `id_transit` int NOT NULL,
  `id_type` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transits`
--

INSERT INTO `transits` (`id_transit`, `id_type`, `price`) VALUES
(1, 4, '150000.00'),
(2, 6, '100000.00');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int NOT NULL,
  `name_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text NOT NULL,
  `long_description` text NOT NULL,
  `fasility` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `start` varchar(50) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `name_type`, `description`, `long_description`, `fasility`, `start`, `img`) VALUES
(1, 'Deluxe Ac', 'Kamar mewah dengan desain elegan, dilengkapi AC, tempat tidur king size, TV, meja, kamar mandi yang dilengkapi dengan shower, dan area duduk. Ideal untuk tamu yang menginginkan kenyamanan premium.', '', '', '250.000', 'deluxe'),
(2, 'Familly Room', 'Kamar luas untuk 3-5 orang, dilengkapi AC, tempat tidur besar, kamar mandi, dan fasilitas tambahan seperti area duduk dan meja.', '', '', '250.000', 'familly'),
(3, 'Superior Ac', 'Kamar luas dan nyaman dengan AC, tempat tidurvking size,dan kamar mandi. Cocok untuk tamu yang mengutamakan kenyamanan lebih.', '', '', '200.000', 'superAc'),
(4, 'Standar Ac', 'Kamar nyaman dengan AC, tempat tidur double, kamar mandi pribadi, fasilitas dasar.', '', '', '150.000', 'StandAc'),
(5, 'Superior Fan', 'Kamar Superior Fan menawarkan kenyamanan dengan kasur besar, kipas angin, dan kamar mandi.', '', '', '150.000', 'SuperFan'),
(6, 'Standar Fan', 'Kamar sederhana dengan kipas angin, tempat tidur double, kamar mandi, dan fasilitas dasar.', '', '', '100.000', 'StandFan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`, `role`, `remember_token`, `password_reset_token`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$R1eJRC2U4Y8YWs5PuswEJOErrPiQk56rZ6s7hOCPBn6k5H.PqFX5a', '2024-11-26 11:23:58', 'admin', NULL, NULL),
(2, 'yuda69', 'yuda@gmail.com', '$2y$10$Tyb9VAQgjHFp4ZufrWT1FuqIbKpd64ryxy6hx/RmoeDRfdGCG5AAi', '2024-11-30 09:48:28', 'user', NULL, NULL),
(3, 'yuda666', 'yuda666@yahaha.com', '$2y$10$ooaZUV4B75cQG6L7xZPU2uqZ2mEEIOOviEVvFlExTZtgiV9OsG6MW', '2024-12-03 01:16:46', 'user', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id_profile` int NOT NULL,
  `id_user` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id_profile`, `id_user`, `username`, `first_name`, `last_name`, `phone_number`, `email`, `date_of_birth`, `create_at`) VALUES
(1, 2, 'yuda69', 'kiki', 'jmt', '9007644', 'yuda@gmail.com', '2024-12-06', '2024-12-06 02:46:37'),
(3, 3, 'yuda666', 'kikil', 'kuuoo', '090076440088', 'yuda666@yahaha.com', '2024-12-06', '2024-12-06 03:18:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pay_method` (`id_pay_method`);

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
  ADD KEY `fk_id_type` (`id_type`),
  ADD KEY `fk_id_room_rate` (`id_room_rate`),
  ADD KEY `fk_id_payment` (`id_payment`),
  ADD KEY `fk_id_pay_method` (`id_pay_method`);

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
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `transits`
--
ALTER TABLE `transits`
  ADD PRIMARY KEY (`id_transit`),
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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
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
  MODIFY `id_room` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `room_rates`
--
ALTER TABLE `room_rates`
  MODIFY `id_room_rate` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transits`
--
ALTER TABLE `transits`
  MODIFY `id_transit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`id_pay_method`) REFERENCES `pay_methods` (`id_pay_method`);

--
-- Constraints for table `resevations`
--
ALTER TABLE `resevations`
  ADD CONSTRAINT `fk_id_pay_method` FOREIGN KEY (`id_pay_method`) REFERENCES `pay_methods` (`id_pay_method`),
  ADD CONSTRAINT `fk_id_payment` FOREIGN KEY (`id_payment`) REFERENCES `payments` (`id_payment`),
  ADD CONSTRAINT `fk_id_room_rate` FOREIGN KEY (`id_room_rate`) REFERENCES `room_rates` (`id_room_rate`),
  ADD CONSTRAINT `fk_id_type` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `resevations_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`) ON DELETE CASCADE;

--
-- Constraints for table `room_rates`
--
ALTER TABLE `room_rates`
  ADD CONSTRAINT `room_rates_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

--
-- Constraints for table `transits`
--
ALTER TABLE `transits`
  ADD CONSTRAINT `transits_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `fk_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `fk_username` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
