-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2024 at 04:08 AM
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
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int NOT NULL,
  `id_user` int NOT NULL,
  `id_room` int NOT NULL,
  `reservation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_status` enum('paid','unpaid','refunded') DEFAULT 'unpaid',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payment_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `start_date` date NOT NULL,
  `id_pay_method` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_user`, `id_room`, `reservation_date`, `check_in_date`, `check_out_date`, `status`, `payment_status`, `total_amount`, `created_at`, `updated_at`, `payment_proof`, `to_date`, `start_date`, `id_pay_method`) VALUES
(26, 7, 17, '2024-12-18 02:43:22', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 02:43:22', '2024-12-18 02:43:30', '20241218024330_676236d2c3196.jpg', NULL, '2024-12-24', 2),
(27, 8, 18, '2024-12-18 02:58:42', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 02:58:42', '2024-12-18 02:58:50', '20241218025850_67623a6a3f248.jpg', NULL, '2024-12-26', 2),
(28, 7, 24, '2024-12-18 03:14:33', NULL, NULL, 'pending', 'unpaid', '100000.00', '2024-12-18 03:14:33', '2024-12-18 03:14:41', '20241218031441_67623e219a928.jpg', NULL, '2024-12-20', 2),
(29, 7, 23, '2024-12-18 03:21:39', NULL, NULL, 'pending', 'unpaid', '100000.00', '2024-12-18 03:21:39', '2024-12-18 03:21:47', '20241218032147_67623fcb24b8f.jpg', NULL, '2025-01-02', 1),
(30, 7, 22, '2024-12-18 03:23:49', NULL, NULL, 'pending', 'unpaid', '100000.00', '2024-12-18 03:23:49', '2024-12-18 03:23:57', '20241218032357_6762404d4454b.jpg', NULL, '2024-12-21', 2),
(31, 7, 26, '2024-12-18 03:26:39', NULL, NULL, 'pending', 'unpaid', '100000.00', '2024-12-18 03:26:39', '2024-12-18 03:26:50', '20241218032650_676240fa58fc6.jpg', NULL, '2024-12-19', 3),
(32, 7, 16, '2024-12-18 03:31:05', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 03:31:05', '2024-12-18 03:31:28', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-21', 2),
(33, 7, 10, '2024-12-18 03:32:48', NULL, NULL, 'pending', 'unpaid', '175000.00', '2024-12-18 03:32:48', '2024-12-18 03:33:06', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-27', 2),
(34, 7, 9, '2024-12-18 03:34:48', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 03:34:48', '2024-12-18 03:35:04', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-19', 1),
(35, 7, 6, '2024-12-18 03:38:48', NULL, NULL, 'pending', 'unpaid', '200000.00', '2024-12-18 03:38:48', '2024-12-18 03:39:05', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-20', 1),
(36, 7, 5, '2024-12-18 03:45:15', NULL, NULL, 'pending', 'unpaid', '200000.00', '2024-12-18 03:45:15', '2024-12-18 03:45:31', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-19', 2),
(37, 7, 11, '2024-12-18 03:50:44', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 03:50:44', '2024-12-18 03:51:00', 'Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.', NULL, '2024-12-21', 3),
(38, 7, 27, '2024-12-18 04:06:33', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 04:06:33', '2024-12-18 04:06:33', NULL, NULL, '2024-12-20', 1),
(39, 7, 8, '2024-12-18 04:07:01', NULL, NULL, 'pending', 'unpaid', '150000.00', '2024-12-18 04:07:01', '2024-12-18 04:07:01', NULL, NULL, '2024-12-20', 2);

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
(1, 1, 66, 'unavailable'),
(2, 1, 104, 'pending'),
(3, 2, 58, 'pending'),
(4, 2, 60, 'pending'),
(5, 3, 54, 'pending'),
(6, 3, 62, 'pending'),
(8, 4, 102, 'pending'),
(9, 4, 103, 'pending'),
(10, 4, 105, 'pending'),
(11, 4, 107, 'pending'),
(12, 5, 142, 'available'),
(13, 5, 144, 'available'),
(14, 5, 146, 'available'),
(15, 5, 148, 'available'),
(16, 5, 150, 'pending'),
(17, 5, 152, 'pending'),
(18, 5, 154, 'pending'),
(19, 6, 106, 'pending'),
(20, 6, 108, 'pending'),
(21, 6, 110, 'pending'),
(22, 6, 112, 'pending'),
(23, 6, 118, 'pending'),
(24, 6, 120, 'pending'),
(25, 6, 122, 'pending'),
(26, 6, 124, 'pending'),
(27, 4, 101, 'pending');

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
(1, 'DELUXE AC', 'Kamar mewah dengan desain elegan, dilengkapi AC, tempat tidur king size, TV, meja, kamar mandi dengan shower, dan area duduk.', '\r\nKamar Deluxe AC dengan desain elegan dan modern, menawarkan kenyamanan maksimal untuk tamu yang menginginkan pengalaman menginap premium. Dilengkapi dengan tempat tidur king size yang luas, TV layar datar, meja kerja, serta kamar mandi pribadi dengan shower. Untuk kenyamanan ekstra, kamar ini juga menyediakan area duduk yang nyaman, ideal untuk relaksasi setelah seharian beraktivitas. Fasilitas AC memastikan suhu ruangan tetap sejuk, menciptakan suasana yang menyenangkan selama menginap. Kamar ini adalah pilihan sempurna bagi Anda yang mengutamakan kualitas dan kenyamanan dalam setiap detail.', 'AC (Air Conditioning) – Menjaga kenyamanan suhu di dalam kamar.\r\nTempat Tidur King Size – Memberikan kenyamanan tidur yang optimal dengan ukuran besar.\r\nTV – Untuk hiburan selama menginap.\r\nMeja Kerja – Memudahkan untuk bekerja atau menyelesaikan tugas selama menginap.\r\nKamar Mandi dengan Shower – Menyediakan kenyamanan dan kesegaran dengan fasilitas shower modern.\r\nArea Duduk – Tempat untuk bersantai atau menikmati waktu luang.\r\nWi-Fi Gratis – Koneksi internet cepat untuk kebutuhan kerja atau hiburan.\r\nLemari Pakaian – Menyediakan ruang untuk menyimpan pakaian dan barang pribadi.\r\nMinibar – Tersedia berbagai pilihan minuman dan camilan.', '250.000', 'deluxe'),
(2, 'FAMILLY ROOM', 'Kamar luas untuk 3-5 orang, dilengkapi AC, tempat tidur besar, kamar mandi, dan fasilitas tambahan seperti area duduk dan meja.', 'Family Room ini dirancang khusus untuk keluarga atau kelompok yang membutuhkan ruang lebih luas dan nyaman. Dapat menampung hingga 3-5 orang, dengan tempat tidur besar yang dapat disesuaikan untuk kenyamanan semua anggota keluarga.Kamar ini dilengkapi dengan AC untuk menciptakan suasana yang sejuk dan nyaman, cocok untuk berbagai cuaca.\r\nTerdapat juga kamar mandi pribadi dengan fasilitas lengkap, termasuk shower yang memberikan kenyamanan dan kesegaran setelah aktivitas sepanjang hari. Untuk meningkatkan kenyamanan, tersedia area duduk yang luas, di mana keluarga dapat bersantai bersama, menikmati waktu berkualitas, atau berbincang. Selain itu, terdapat meja kerja yang memudahkan Anda menyelesaikan pekerjaan atau aktivitas lainnya tanpa mengganggu waktu berkumpul. Dengan desain yang modern dan ruang yang lapang, Family Room ini menawarkan kenyamanan maksimal, ideal untuk keluarga yang menginginkan penginapan yang praktis, tetapi tetap premium dan menyenangkan.', 'AC (Air Conditioning) – Menjaga suhu ruangan tetap nyaman.\r\nTempat Tidur Besar – Ideal untuk 3-5 orang, memberikan kenyamanan tidur yang optimal.\r\nKamar Mandi Pribadi – Dilengkapi dengan shower untuk kenyamanan mandi.\r\nArea Duduk – Tempat bersantai bersama keluarga atau teman.\r\nMeja Kerja – Cocok untuk aktivitas atau bekerja selama menginap.\r\nTV – Hiburan untuk keluarga dengan berbagai saluran.\r\nWi-Fi Gratis – Koneksi internet cepat untuk kebutuhan hiburan atau pekerjaan.\r\nLemari Pakaian – Untuk menyimpan barang-barang pribadi selama menginap.\r\nPerlengkapan Mandi Lengkap – Seperti handuk, sabun, sampo, dan lainnya.', '250.000', 'familly'),
(3, 'SUPERIOR AC', 'Kamar luas dan nyaman dengan AC, tempat tidur king size,dan kamar mandi. Cocok untuk tamu yang mengutamakan kenyamanan lebih.', 'Kamar Superior dengan AC ini menawarkan kenyamanan maksimal untuk tamu yang mengutamakan kualitas penginapan. Dilengkapi dengan tempat tidur king size yang luas dan nyaman, memberikan pengalaman tidur yang menyenangkan sepanjang malam. Dengan AC yang terpasang, suhu di dalam kamar dapat disesuaikan sesuai keinginan, menciptakan atmosfer yang sejuk dan menyegarkan di setiap waktu. Kamar ini juga dilengkapi dengan kamar mandi pribadi yang modern, lengkap dengan fasilitas shower untuk memberikan kesegaran ekstra setelah beraktivitas. Desain interiornya yang elegan dan luas memberikan rasa lega dan privasi yang lebih. Didesain untuk memberikan kenyamanan ekstra, kamar ini cocok untuk tamu yang menginginkan ruang lebih luas dan fasilitas yang memadai untuk beristirahat dengan tenang. Dengan fasilitas premium dan suasana yang nyaman, Kamar Superior ini menjadi pilihan sempurna untuk pengalaman menginap yang lebih dari sekadar biasa.', 'AC (Air Conditioning) – Menjaga suhu ruangan tetap sejuk dan nyaman.\r\nTempat Tidur King Size – Memberikan kenyamanan tidur yang optimal dengan ukuran yang luas.\r\nKamar Mandi Pribadi – Dilengkapi dengan shower untuk kenyamanan mandi yang menyegarkan.\r\nTV – Hiburan selama menginap dengan berbagai saluran.\r\nMeja Kerja – Menyediakan ruang untuk bekerja atau menyelesaikan tugas.\r\nWi-Fi Gratis – Koneksi internet cepat untuk kebutuhan hiburan atau pekerjaan.\r\nLemari Pakaian – Untuk menyimpan barang pribadi dengan rapi dan aman.\r\nPerlengkapan Mandi Lengkap – Termasuk handuk, sabun, sampo, dan perlengkapan lainnya.\r\nTelepon – Untuk komunikasi dengan layanan hotel atau kebutuhan lainnya.', '200.000', 'superAc'),
(4, 'STANDAR AC', 'Kamar nyaman dengan AC, tempat tidur double, kamar mandi pribadi, fasilitas dasar.', 'Kamar Standar dengan AC ini dirancang untuk memberikan kenyamanan dasar yang ideal bagi tamu yang mencari tempat menginap yang praktis dan nyaman. Dilengkapi dengan tempat tidur double yang pas untuk satu atau dua orang, memberikan kenyamanan tidur yang optimal setelah beraktivitas seharian. AC yang tersedia di kamar ini menjaga suhu ruangan tetap sejuk dan nyaman, menciptakan suasana yang tenang untuk istirahat yang nyenyak. Kamar ini juga dilengkapi dengan kamar mandi pribadi yang menyediakan fasilitas dasar, termasuk shower yang memberikan kesegaran. Desain interiornya sederhana namun fungsional, menciptakan ruang yang nyaman untuk beristirahat. Dengan fasilitas dasar yang lengkap, kamar ini menjadi pilihan ideal untuk tamu yang mengutamakan kenyamanan dengan harga yang lebih terjangkau, tanpa mengurangi kualitas penginapan. Cocok untuk perjalanan singkat, bisnis, atau liburan dengan anggaran terbatas, tetapi tetap mengutamakan kenyamanan dan kebutuhan dasar selama menginap.', 'AC (Air Conditioning) – Menjaga suhu ruangan tetap sejuk dan nyaman sepanjang hari.\r\nTempat Tidur Double – Memberikan kenyamanan tidur untuk satu atau dua orang.\r\nKamar Mandi Pribadi – Dilengkapi dengan shower untuk kenyamanan dan kesegaran.\r\nTV – Hiburan untuk tamu dengan berbagai saluran.\r\nMeja Sederhana – Untuk keperluan menulis atau bekerja ringan.\r\nWi-Fi Gratis – Koneksi internet cepat untuk kebutuhan hiburan atau komunikasi.\r\nLemari Pakaian – Menyediakan ruang untuk menyimpan barang pribadi dengan rapi.\r\nPerlengkapan Mandi Dasar – Seperti handuk, sabun, dan sampo.\r\nTelepon – Untuk komunikasi dengan layanan hotel atau kebutuhan lainnya.', '150.000', 'StandAc'),
(5, 'SUPERIOR FAN', 'Kamar Superior Fan menawarkan kenyamanan dengan kasur besar, kipas angin, dan kamar mandi.', 'Kamar Superior Fan ini dirancang untuk memberikan kenyamanan sederhana namun menyenangkan bagi tamu yang mengutamakan relaksasi. Dilengkapi dengan kasur besar yang memastikan tidur yang nyaman dan nyenyak, kamar ini menawarkan pengalaman menginap yang tenang dan nyaman. Untuk menjaga suhu ruangan tetap sejuk, tersedia kipas angin yang efisien, menciptakan atmosfer yang menyegarkan tanpa memerlukan AC. Kamar ini juga dilengkapi dengan kamar mandi pribadi, lengkap dengan fasilitas shower untuk kesegaran setelah aktivitas. Desainnya yang sederhana namun elegan memberikan ruang yang ideal untuk tamu yang mencari kenyamanan tanpa kelebihan. Kamar ini cocok bagi Anda yang menginginkan akomodasi dengan harga terjangkau namun tetap memprioritaskan kualitas penginapan.', 'Kasur Besar – Memberikan kenyamanan tidur yang optimal dengan ukuran yang luas.\r\nKipas Angin – Menjaga suhu ruangan tetap sejuk dan nyaman.\r\nKamar Mandi Pribadi – Dilengkapi dengan shower untuk kenyamanan mandi yang menyegarkan.\r\nTV – Hiburan dengan berbagai saluran untuk menikmati waktu luang.\r\nWi-Fi Gratis – Koneksi internet cepat untuk keperluan hiburan atau pekerjaan.\r\nLemari Pakaian – Menyediakan ruang untuk menyimpan barang pribadi dengan rapi.\r\nPerlengkapan Mandi Dasar – Seperti handuk, sabun, sampo, dan perlengkapan lainnya.\r\nMeja Sederhana – Untuk menulis atau menyelesaikan pekerjaan ringan.', '150.000', 'SuperFan'),
(6, 'STANDAR FAN', 'Kamar sederhana dengan kipas angin, tempat tidur double, kamar mandi, dan fasilitas dasar.', 'Kamar Standar Fan ini dirancang untuk memberikan kenyamanan dasar dengan fasilitas yang praktis dan fungsional. Dilengkapi dengan tempat tidur double yang nyaman, kamar ini cocok untuk satu atau dua orang yang menginginkan tempat istirahat yang tenang dan nyaman. Kipas angin yang tersedia menjaga suhu ruangan tetap sejuk dan nyaman, terutama di cuaca yang lebih panas, memberikan kenyamanan tanpa memerlukan AC. Kamar ini juga dilengkapi dengan kamar mandi pribadi, yang menyediakan fasilitas dasar seperti shower, untuk memberikan kesegaran setelah beraktivitas. Desain interior kamar sangat sederhana namun efisien, dengan penataan yang memberi ruang untuk beristirahat dengan nyaman. Fasilitas dasar seperti Wi-Fi gratis, TV, dan meja sederhana untuk bekerja atau menyelesaikan tugas ringan, juga tersedia. Kamar ini adalah pilihan yang sempurna untuk tamu yang menginginkan penginapan yang praktis dan terjangkau tanpa mengurangi kenyamanan. Ideal untuk perjalanan singkat atau tamu yang membutuhkan tempat tidur yang nyaman dengan fasilitas dasar yang memadai.', 'Kipas Angin – Menjaga suhu ruangan tetap sejuk dan nyaman.\r\nTempat Tidur Double – Memberikan kenyamanan tidur untuk satu atau dua orang.\r\nKamar Mandi Pribadi – Dilengkapi dengan shower untuk kenyamanan mandi.\r\nTV – Hiburan untuk waktu luang dengan berbagai saluran.\r\nWi-Fi Gratis – Koneksi internet cepat untuk hiburan atau komunikasi.\r\nLemari Pakaian – Tempat menyimpan barang pribadi dengan rapi.', '100.000', 'StandFan');

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
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `created_at`, `role`, `remember_token`, `password_reset_token`) VALUES
(7, 'fufufafa', 'fufufafa@gmail.com', '$2y$10$V5DF2fFB8ksi1YzYOa94w.wDHtyWlrOc7GS9dbtBzVegyyUVAPBNa', '2024-12-18 02:37:19', 'user', NULL, NULL),
(8, 'admin', 'admin@gmail.com', '$2y$10$TGd/jMe6pn1SHQQwGBaTeutdBSudL3OV/3QoBTSj4.gLGs0GnK0tO', '2024-12-18 02:50:49', 'admin', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id_profile` int NOT NULL,
  `id_user` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(45) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id_profile`, `id_user`, `username`, `email`, `first_name`, `last_name`, `phone_number`, `date_of_birth`, `create_at`) VALUES
(5, 7, 'fufufafafa', 'fufufafa@gmail.como', 'ali', 'mm', '08333', '2026-10-18', '2024-12-18 02:38:22');

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
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_room` (`id_room`),
  ADD KEY `fk_pay_methods` (`id_pay_method`);

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
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_pay_methods` FOREIGN KEY (`id_pay_method`) REFERENCES `pay_methods` (`id_pay_method`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`id_room`) REFERENCES `rooms` (`id_room`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
