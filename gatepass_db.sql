-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 07:38 AM
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
-- Database: `gatepass_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'IT Equipment'),
(2, 'Furniture'),
(3, 'Documents'),
(4, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_returnable` tinyint(1) DEFAULT 0,
  `photo_path` varchar(255) DEFAULT NULL,
  `item_status` enum('pending','verified','rejected','dispatched','received') DEFAULT 'pending',
  `serial_no` varchar(255) DEFAULT NULL,
  `item_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `request_id`, `item_name`, `quantity`, `is_returnable`, `photo_path`, `item_status`, `serial_no`, `item_photo`) VALUES
(1, 1, 'dc', 1, 0, NULL, 'pending', NULL, NULL),
(2, 1, 'dc', 1, 0, NULL, 'pending', NULL, NULL),
(3, 2, 'Lap', 1, 0, NULL, 'pending', NULL, NULL),
(4, 8, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(5, 8, 'Lap', 2, 1, NULL, 'pending', '212', NULL),
(6, 9, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(7, 10, 'Lap', 1, 0, NULL, 'pending', '2112', NULL),
(8, 10, 'Lap', 2, 1, NULL, 'pending', '211', NULL),
(9, 11, 'Lap', 1, 0, NULL, 'pending', '21123', NULL),
(10, 12, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(11, 13, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(12, 14, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(13, 15, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(14, 16, 'Lap', 1, 1, NULL, 'pending', '211', NULL),
(15, 19, 'Lap', 1, 1, NULL, 'pending', '2112', NULL),
(16, 20, 'dc', 1, 0, NULL, 'pending', '21123', NULL),
(17, 21, 'Lap', 1, 0, NULL, 'pending', '21123', 'item_684db60c74b762.41119870.PNG'),
(19, 23, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(20, 24, 'Lap', 1, 0, NULL, 'pending', '21123', NULL),
(21, 24, 'Lap', 1, 0, NULL, 'pending', '2112', NULL),
(22, 25, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(23, 27, 'Lap', 1, 0, NULL, 'pending', '211', NULL),
(24, 29, 'Monitor', 1, 0, NULL, 'pending', '2112', NULL),
(25, 30, 'Monitor', 1, 0, NULL, 'pending', '21123', NULL),
(26, 31, 'Monitor', 2, 0, NULL, 'pending', '21123', NULL),
(27, 34, 'Monitor', 1, 0, NULL, 'pending', '2112', NULL),
(28, 35, 'Monitor', 1, 0, NULL, 'pending', '2112', NULL),
(29, 36, 'Monitor', 1, 0, NULL, 'pending', '211', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `item_images`
--

INSERT INTO `item_images` (`id`, `item_id`, `image_path`, `uploaded_at`) VALUES
(1, 19, 'item_19_684eb88ce57111.13303262.png', '2025-06-15 12:11:56'),
(2, 19, 'item_19_684eb88ce5f3b7.75772361.jpeg', '2025-06-15 12:11:56'),
(3, 19, 'item_19_684eb88ce778a0.92465315.webp', '2025-06-15 12:11:56'),
(4, 20, 'item_20_684ee3b3afb388.72569309.jpg', '2025-06-15 15:16:03'),
(5, 21, 'item_21_684ee3b3b0f971.50730884.webp', '2025-06-15 15:16:03'),
(6, 22, 'item_22_684efb32d256f6.87678773.jfif', '2025-06-15 16:56:18'),
(7, 23, 'item_23_685a403c7c2c00.05709452.jpg', '2025-06-24 06:05:48'),
(8, 24, 'item_24_685a6490e31fb9.19757009.jpeg', '2025-06-24 08:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`) VALUES
(1, 'Head Office'),
(2, 'Branch Office 1'),
(3, 'Branch Office 2');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `executive_id` int(11) DEFAULT NULL,
  `out_location_id` int(11) DEFAULT NULL,
  `in_location_id` int(11) DEFAULT NULL,
  `receiver_name` varchar(100) DEFAULT NULL,
  `receiver_service_number` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sender_work_location` varchar(255) DEFAULT NULL,
  `sender_role` varchar(100) DEFAULT NULL,
  `sender_contact_number` varchar(100) DEFAULT NULL,
  `receiver_work_location` varchar(255) DEFAULT NULL,
  `receiver_role` varchar(100) DEFAULT NULL,
  `receiver_contact_number` varchar(100) DEFAULT NULL,
  `transport_method` enum('person','vehicle') DEFAULT NULL,
  `person_name` varchar(255) DEFAULT NULL,
  `person_address` varchar(255) DEFAULT NULL,
  `person_nic` varchar(50) DEFAULT NULL,
  `person_contact` varchar(50) DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `vehicle_no` varchar(50) DEFAULT NULL,
  `vehicle_contact` varchar(50) DEFAULT NULL,
  `receiver_user_id` int(11) DEFAULT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `executive_comments` text DEFAULT NULL,
  `duty_officer_comments` text DEFAULT NULL,
  `dispatch_checker_name` varchar(100) DEFAULT NULL,
  `dispatch_checker_service_no` varchar(50) DEFAULT NULL,
  `dispatch_checker_nic` varchar(20) DEFAULT NULL,
  `dispatch_checker_contact` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `executive_id`, `out_location_id`, `in_location_id`, `receiver_name`, `receiver_service_number`, `status`, `created_at`, `updated_at`, `sender_work_location`, `sender_role`, `sender_contact_number`, `receiver_work_location`, `receiver_role`, `receiver_contact_number`, `transport_method`, `person_name`, `person_address`, `person_nic`, `person_contact`, `driver_name`, `vehicle_no`, `vehicle_contact`, `receiver_user_id`, `created_by_user_id`, `executive_comments`, `duty_officer_comments`, `dispatch_checker_name`, `dispatch_checker_service_no`, `dispatch_checker_nic`, `dispatch_checker_contact`) VALUES
(1, 3, 4, 1, 2, 'ded', '23', 'rejected', '2025-06-08 14:47:50', '2025-06-09 11:12:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, 4, NULL, NULL, '', NULL, 'pending', '2025-06-09 02:58:00', '2025-06-09 02:58:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 2, 4, NULL, NULL, 'Yasith Wijekoon', NULL, 'pending', '2025-06-09 04:57:58', '2025-06-09 04:57:58', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 2, 4, 2, 1, 'Yasith Wijekoon', NULL, 'rejected', '2025-06-09 06:26:40', '2025-06-10 14:26:35', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 2, 4, 2, 1, 'Executive', NULL, 'rejected', '2025-06-09 07:23:01', '2025-06-09 13:48:41', 'Head office', '4', '712150200', 'Head Office', '2', '753545024', 'vehicle', '', '', '', '', 'Shantha', 'ABCd', '0712150201', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 4, 4, 2, 1, 'Duty', NULL, 'approved', '2025-06-09 08:15:45', '2025-06-09 11:09:35', 'Head Office', '2', '753545024', 'Head Office', '3', '726252074', 'vehicle', '', '', '', '', 'Shantha dias', 'ABCd', '768470920', 5, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 4, 4, 2, 1, 'Yasith Wijekoon', NULL, 'approved', '2025-06-09 15:14:01', '2025-06-10 14:26:15', 'Head Office', '2', '753545024', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha dias', 'ABC', '0712150200', 3, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 3, 4, 2, 1, 'System Admin', NULL, 'pending', '2025-06-10 05:27:26', '2025-06-10 05:27:26', 'Head Office', '1', '768470920', 'Head office', '4', '712150200', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 2, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 3, 4, 2, 1, 'System Admin', NULL, '', '2025-06-10 05:28:32', '2025-06-10 13:43:32', 'Head Office', '1', '768470920', 'Head office', '4', '712150200', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 2, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 2, 4, 2, 1, 'Yasith Wijekoon', NULL, '', '2025-06-10 14:02:37', '2025-06-10 14:15:13', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 2, 4, 2, 1, 'Yasith Wijekoon', NULL, 'pending_duty_officer', '2025-06-14 16:33:53', '2025-06-27 19:08:55', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'person', 'Shan', 'Colombo', '12345678', '0712150200', '', '', '', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 2, 8, 1, 1, '', NULL, 'pending', '2025-06-14 16:41:11', '2025-06-14 16:41:11', '', '', '', '', '', '', 'person', 'Shan', 'Colombo', '12345678', '0712150200', '', '', '', 5, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 2, 8, 2, 1, 'Executive', NULL, 'pending', '2025-06-14 17:22:41', '2025-06-14 17:22:41', 'Head office', '4', '712150200', 'Head Office', '2', '753545024', 'person', 'Shan', 'Colombo', '12345678', '0712150200', '', '', '', 4, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 2, 8, 2, 1, 'Duty', NULL, 'pending', '2025-06-14 17:29:06', '2025-06-14 17:29:06', 'Head office', '4', '712150200', 'Head Office', '3', '726252074', 'person', 'Shan', 'Colombo', '12345678', '0712150200', '', '', '', 5, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 2, 4, 3, 2, 'Yasith Wijekoon', NULL, 'approved', '2025-06-14 17:49:00', '2025-06-15 13:42:01', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'person', 'Shan', 'Aradhana', '12345678', '0712150200', '', '', '', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 2, 4, 3, 2, 'Yasith Wijekoon', NULL, 'approved', '2025-06-15 12:11:56', '2025-06-15 13:09:21', 'Head office', '4', '712150200', 'Head Office', '1', '768470920', 'person', 'Shan', 'Aradhana', '12345678', '0712150200', '', '', '', 3, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 3, 4, 2, 1, 'Duty', NULL, 'verified', '2025-06-15 15:16:03', '2025-06-15 16:45:36', 'Head Office', '1', '768470920', 'Head Office', '3', '726252074', 'person', 'Shan', 'Aradhana', '12345678', '0712150200', '', '', '', 5, 3, NULL, '', NULL, NULL, NULL, NULL),
(25, 3, 4, 2, 1, 'Sunil', NULL, 'verified', '2025-06-15 16:56:18', '2025-06-15 17:27:50', 'Head Office', '1', '768470920', '', '1', '', 'person', 'Shan', 'Aradhana', '12345678', '0712150200', '', '', '', 7, 3, NULL, '', NULL, NULL, NULL, NULL),
(26, 3, 4, 2, 1, 'Sunil', NULL, 'verified', '2025-06-24 05:53:16', '2025-06-24 05:54:57', 'Head Office', '1', '768470920', '', '1', '', 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 7, 3, NULL, '', NULL, NULL, NULL, NULL),
(27, 3, 8, 3, 1, 'Sunil', NULL, 'pending', '2025-06-24 06:05:48', '2025-06-24 06:05:48', 'Head Office', '1', '768470920', '', '1', '', 'vehicle', '', '', '', '', 'Shantha dias', 'ABCD', '0712150200', 7, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 3, 4, 2, 1, 'Yenuka', NULL, 'verified', '2025-06-24 06:33:32', '2025-06-24 07:15:27', 'Head Office', '1', '768470920', 'Head Office', '1', NULL, 'vehicle', '', '', '', '', 'Shantha', 'ABC', '0712150200', 10, 3, NULL, '', NULL, NULL, NULL, NULL),
(29, 3, 4, 2, 1, 'Sunil', NULL, 'verified', '2025-06-24 08:40:48', '2025-06-24 08:45:51', 'Head Office', '1', '768470920', '', '1', '', 'vehicle', '', '', '', '', 'Sunimal', 'ABCDE', '717171723', 7, 3, NULL, '', 'Kamal Amarasooriya', 'Sec002', '12345678', '0776767678'),
(30, 3, 8, 2, 1, 'Yenuka', NULL, 'pending_duty_officer', '2025-06-26 06:49:19', '2025-06-27 18:02:13', 'Head Office', '1', '768470920', 'Head Office', '1', '0721546026', 'vehicle', '', '', '', '', 'Sunimal', 'ABCD', '0712150200', 10, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 3, 4, 3, 1, 'Yenuka', NULL, 'received', '2025-06-26 17:10:19', '2025-06-26 18:28:22', 'Head Office', '1', '768470920', 'Head Office', '1', '0721546026', 'vehicle', '', '', '', '', 'Shantha dias', 'ABCDE', '0712150200', 10, 3, NULL, '', 'Kamal Amarasooriya', 'Sec002', '12345678', '0776767678'),
(32, 3, 4, 2, 1, 'Yenuka', NULL, 'rejected', '2025-06-26 19:18:25', '2025-06-26 19:37:32', 'Head Office', 'User', '768470920', 'Head Office', '1', '0721546026', 'person', '', '', '', '', '', '', '', 10, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 3, 4, 2, 1, 'Sunil', NULL, 'received', '2025-06-26 19:21:09', '2025-06-26 19:47:06', 'Head Office', 'User', '768470920', 'Head Office', '1', '787878651', 'person', '', '', '', '', '', '', '', 7, 3, NULL, '', 'Sumanasiri', 'Sec001', '12345678', '0776767678'),
(34, 10, 8, 2, 1, 'Yasith Wijekoon', NULL, 'pending_duty_officer', '2025-06-27 17:51:10', '2025-06-27 17:52:29', 'Head Office', 'User', '0721546026', 'Head Office', '1', '768470920', 'person', 'Shan', 'Aradhana', '12345678', '0712150200', '', '', '', 3, 10, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 10, 4, 3, 2, 'Yasith Wijekoon', NULL, 'pending', '2025-06-27 19:00:23', '2025-06-27 19:00:23', 'Head Office', 'User', '0721546026', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha', 'ABCd', '0712150200', 3, 10, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 10, 4, 3, 2, 'Yasith Wijekoon', NULL, 'pending_duty_officer', '2025-06-27 19:01:14', '2025-06-27 19:08:11', 'Head Office', 'User', '0721546026', 'Head Office', '1', '768470920', 'vehicle', '', '', '', '', 'Shantha', 'ABCD', '0712150200', 3, 10, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'User'),
(2, 'Executive Officer'),
(3, 'Duty Officer / Verifier'),
(4, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `work_location` varchar(255) DEFAULT NULL,
  `contact_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `role_id`, `created_at`, `work_location`, `contact_number`) VALUES
(2, 'admin', '$2y$10$P5M1Zx3GzkabcDEFghijKLmnopQRstuvwXYz123456789abcd\r\n', 'System Admin', 'admin@example.com', 4, '2025-06-08 13:35:54', 'Head office', '712150200'),
(3, 'User001', '$2y$10$VdnkKHMSuvx4nLKJ/P8lhekkFzioqL8iEg1B4HTuycZGtjvNkXx/K', 'Yasith Wijekoon', 'user@example.com', 1, '2025-06-08 14:05:07', 'Head Office', '768470920'),
(4, 'Exe001', '$2y$10$gOkJocyMHghhw2eiHb/R/.UZznyq6ZVQ7xKQoR6A1I1DAO2sN1whK', 'Executive', 'exe@gmail.com', 2, '2025-06-08 14:43:18', 'Head Office', '753545024'),
(5, 'Duty001', '$2y$10$Y1EbxJb3fPmca.kcBjUsTuRowXTPqU0Lq9TOTek1osNp.eTV4Ei8K', 'Duty', 'duty@gmail.com', 3, '2025-06-08 14:44:51', 'Head Office', '726252074'),
(6, 'Admin001', '$2y$10$TI/txiUX26iTl2yYKAuZDuRLB06dK5fm9vbudrtNlZhQSuTrQVTNm', 'Admin', 'admin@gmail.com', 4, '2025-06-08 14:45:28', 'Head Office', '781769370'),
(7, 'User002', '$2y$10$nQsiVJZuq3AgYTxk.DeJ6eW9mgDBCRXdXlGiwGMGr2JEMkJ8NtQ3W', 'Sunil', 'Sunil@gmail.com', 1, '2025-06-10 15:04:47', 'Head Office', '787878651'),
(8, 'Exe002', '$2y$10$aUkOh3TEE1Da39F6H.X1bu7Z3Jsc0yOi2pDvqvldIqnKAp8YafTUa', 'Shantha', 'shantha@gmail.com', 2, '2025-06-10 15:05:39', NULL, NULL),
(9, 'Duty002', '$2y$10$W5cbme97S1Eqw3wRBWL9nuCHwRX2M2H4XAs7bDctdaxwjXweuGo.m', 'Nimal', 'nimal@gmail.com', 3, '2025-06-10 15:06:20', NULL, NULL),
(10, 'User003', '$2y$10$DK7Q5KblMutlWE6jlM5jUO0Hdb.dxoJggcSrE1GOIglVLS0vBnrdG', 'Yenuka', 'yenuka@gmail.com', 1, '2025-06-17 06:02:08', 'Head Office', '0721546026');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `executive_id` (`executive_id`),
  ADD KEY `out_location_id` (`out_location_id`),
  ADD KEY `in_location_id` (`in_location_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `item_images`
--
ALTER TABLE `item_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `item_images_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`executive_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`out_location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `requests_ibfk_4` FOREIGN KEY (`in_location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
