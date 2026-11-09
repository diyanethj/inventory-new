-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 09, 2026 at 03:05 AM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u838229316_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'csbm', '$2y$10$7JgQlCcc0FVnk/bFCMOPxO6.eoqy1XhEUQzxV1AUDlmnhz5VocFF2', '2026-02-16 10:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `phone`, `created_at`) VALUES
(1, 'Dasun Diyaneth', 'dasun@csbm.edu.lk', '0707836287', '2026-02-16 10:38:49'),
(2, 'Chathudu Sahassara', 'chathudu@csbm.edu.lk', '0710667667', '2026-02-17 06:37:37'),
(3, 'Chamod Ranasinghe', 'chamod.h@csbm.edu.lk', NULL, '2026-02-17 06:37:49'),
(5, 'Isuru Batuwatta', 'isuru@csbm.edu.lk', NULL, '2026-02-17 08:13:49'),
(6, 'Imasha Nethmi', 'imasha@csbmcampus.com', NULL, '2026-02-17 08:15:00'),
(7, 'Movinya Senanayake', 'movinya@csbmcampus.com', NULL, '2026-02-17 08:15:56'),
(9, 'Janani Sepion', 'janani@csbm.edu.lk', NULL, '2026-02-17 08:16:52'),
(10, 'Divya Lushani', 'diviya.l@csbm.edu.lk', NULL, '2026-02-17 08:17:11'),
(11, 'Eshani Perera', 'eshani@csbmcampus.com', NULL, '2026-02-17 08:17:32'),
(12, 'kaveesha Rashmika', 'kaveesha@csbmcampus.com', NULL, '2026-02-17 08:18:03'),
(13, 'Shehani Sapnachapa', 'shehani@csbm.edu.lk', NULL, '2026-02-17 08:22:30'),
(14, 'Lakshani Wasundara', 'lakshani@csbmcampus.com', NULL, '2026-02-17 08:22:55'),
(16, 'Rasika Priyadarshani', 'rasika@csbm.edu.lk', NULL, '2026-02-17 08:23:31'),
(17, 'Ashen Viduranga', 'ashen@csbmcampus.com', NULL, '2026-02-17 08:23:49'),
(22, 'IT Department', NULL, NULL, '2026-03-20 10:31:27'),
(24, 'Rahal Wickramasinghe', 'rahal@csbmcampus.com', NULL, '2026-06-10 10:23:32'),
(25, 'Rashini Rathnayake', 'rashini@csbmcampus.com', NULL, '2026-06-10 10:25:01'),
(28, 'Lasandi Chathusara', 'lasandi@csbmcampus.com', NULL, '2026-06-10 10:32:23'),
(29, 'Sachinthi Isuri', 'isuri@csbmcampus.com', NULL, '2026-06-10 10:37:24'),
(30, 'Malshika Bandara', 'malshika@csbmcampus.com', NULL, '2026-06-16 12:36:20'),
(33, 'Isuri Herath', NULL, NULL, '2026-07-19 07:23:29'),
(34, 'Ishani Charuka', NULL, NULL, '2026-07-19 07:32:47'),
(35, 'Imasha Sonali', NULL, NULL, '2026-08-17 05:43:37'),
(36, 'Sankalpa Jayawardhane', NULL, NULL, '2026-08-17 05:52:31'),
(37, 'Lasantha Pradeep', 'lasantha@csbmcampus.com', NULL, '2026-09-21 05:13:52'),
(38, 'Loshiga Rasangi', 'loshiga@csbmcampus.com', NULL, '2026-09-21 05:14:27'),
(39, 'Nishadi Madumali', NULL, NULL, '2026-10-12 02:53:26');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `condition` enum('brandnew','used','personal') NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `venue_id`, `product_id`, `code`, `brand`, `serial_number`, `condition`, `assigned_to`, `assigned_at`, `created_at`) VALUES
(4, 1, 1, 'CORP-LAP-001', 'Asus', NULL, 'brandnew', 7, '2026-02-17 08:21:13', '2026-02-17 06:38:48'),
(5, 1, 1, 'CORP-LAP-002', 'DELL', NULL, 'used', 36, '2026-02-17 08:21:19', '2026-02-17 06:39:19'),
(6, 1, 1, 'CORP-LAP-003', 'Acer', NULL, 'brandnew', 9, '2026-02-17 08:21:24', '2026-02-17 06:39:33'),
(7, 1, 1, 'CORP-LAP-004', 'HP', NULL, 'used', 10, '2026-02-17 08:21:31', '2026-02-17 06:40:06'),
(8, 1, 1, 'CORP-LAP-005', 'HP', NULL, 'used', 11, '2026-02-17 08:22:06', '2026-02-17 06:40:21'),
(9, 1, 1, 'CORP-LAP-006', 'Acer', NULL, 'brandnew', 13, '2026-02-17 08:24:08', '2026-02-17 06:40:52'),
(10, 1, 1, 'CORP-LAP-007', 'HP', NULL, 'used', 16, '2026-02-17 08:24:21', '2026-02-17 06:42:08'),
(11, 1, 1, 'CORP-LAP-008', 'HP', NULL, 'used', 12, '2026-02-17 08:24:33', '2026-02-17 06:42:26'),
(12, 1, 1, 'CORP-LAP-009', 'HP', NULL, 'used', 37, '2026-02-17 08:24:39', '2026-02-17 06:42:34'),
(13, 1, 1, 'CORP-LAP-010', 'HP', NULL, 'used', 24, '2026-02-17 08:25:38', '2026-02-17 06:42:44'),
(14, 1, 1, 'CORP-LAP-011', 'DELL', NULL, 'used', 17, '2026-02-17 08:25:45', '2026-02-17 06:44:03'),
(15, 1, 1, 'CORP-LAP-012', 'DELL', NULL, 'used', 35, '2026-08-17 05:54:58', '2026-02-17 06:44:13'),
(16, 1, 1, 'CORP-LAP-013', 'Asus', NULL, 'personal', NULL, '2026-02-17 08:26:23', '2026-02-17 06:45:38'),
(17, 1, 1, 'CORP-LAP-014', 'Acer', NULL, 'brandnew', 3, '2026-02-17 08:36:22', '2026-02-17 06:45:51'),
(18, 1, 2, 'CORP-LAP-CHGR-001', 'Asus', NULL, 'brandnew', 7, '2026-02-17 08:26:40', '2026-02-17 06:51:07'),
(19, 1, 2, 'CORP-LAP-CHGR-002', 'DELL', NULL, 'used', 36, '2026-02-17 08:26:46', '2026-02-17 06:51:16'),
(20, 1, 2, 'CORP-LAP-CHGR-003', 'Acer', NULL, 'brandnew', 9, '2026-02-17 08:26:52', '2026-02-17 06:51:28'),
(21, 1, 2, 'CORP-LAP-CHGR-004', 'HP', NULL, 'used', 10, '2026-02-17 08:27:04', '2026-02-17 06:51:46'),
(22, 1, 2, 'CORP-LAP-CHGR-005', 'HP', NULL, 'used', 11, '2026-02-17 08:27:09', '2026-02-17 06:51:56'),
(23, 1, 2, 'CORP-LAP-CHGR-006', 'Acer', NULL, 'brandnew', 13, '2026-02-17 08:27:15', '2026-02-17 06:52:13'),
(24, 1, 2, 'CORP-LAP-CHGR-007', 'HP', NULL, 'used', 16, '2026-02-17 08:27:27', '2026-02-17 06:54:05'),
(25, 1, 2, 'CORP-LAP-CHGR-008', 'HP', NULL, 'used', 12, '2026-02-17 08:39:23', '2026-02-17 06:54:13'),
(26, 1, 2, 'CORP-LAP-CHGR-009', 'HP', NULL, 'used', 22, '2026-02-17 08:27:43', '2026-02-17 06:54:20'),
(27, 1, 2, 'CORP-LAP-CHGR-010', 'HP', NULL, 'used', 25, '2026-03-20 10:32:14', '2026-02-17 06:54:47'),
(28, 1, 2, 'CORP-LAP-CHGR-011', 'DELL', NULL, 'used', 17, '2026-02-17 08:37:05', '2026-02-17 06:55:16'),
(29, 1, 2, 'CORP-LAP-CHGR-012', 'DELL', NULL, 'used', 35, '2026-08-17 05:56:27', '2026-02-17 06:55:24'),
(30, 1, 2, 'CORP-LAP-CHGR-013', 'Asus', NULL, 'personal', NULL, '2026-02-17 08:18:20', '2026-02-17 06:56:11'),
(31, 1, 2, 'CORP-LAP-CHGR-014', 'Acer', NULL, 'brandnew', 3, '2026-02-17 08:36:35', '2026-02-17 06:56:21'),
(32, 1, 3, 'CORP-PC-001', 'Asus', NULL, 'brandnew', 1, '2026-02-17 08:13:18', '2026-02-17 08:11:55'),
(33, 1, 3, 'CORP-PC-002', 'Asus', NULL, 'brandnew', 5, '2026-02-17 08:13:58', '2026-02-17 08:12:09'),
(34, 1, 3, 'CORP-PC-003', 'Falcon', NULL, 'brandnew', 6, '2026-02-17 08:15:06', '2026-02-17 08:12:25'),
(35, 1, 4, 'CORP-UPS-001', 'COLORSit', NULL, 'brandnew', 1, '2026-02-17 08:41:18', '2026-02-17 08:40:53'),
(36, 1, 4, 'CORP-UPS-002', 'COLORSit', NULL, 'brandnew', 28, '2026-02-17 08:41:26', '2026-02-17 08:41:01'),
(37, 1, 4, 'CORP-UPS-003', 'COLORSit', NULL, 'brandnew', 6, '2026-02-17 08:41:32', '2026-02-17 08:41:10'),
(38, 1, 5, 'CORP-KB-001', 'DELL', NULL, 'used', 1, '2026-02-17 08:55:47', '2026-02-17 08:54:56'),
(39, 1, 5, 'CORP-KB-002', 'Logitech', NULL, 'brandnew', 5, '2026-02-17 08:55:53', '2026-02-17 08:55:15'),
(40, 1, 5, 'CORP-KB-003', 'Logitech', NULL, 'brandnew', 6, '2026-02-17 08:55:59', '2026-02-17 08:55:28'),
(41, 1, 6, 'CORP-MON-001', 'DELL', NULL, 'used', 1, '2026-02-17 09:50:19', '2026-02-17 09:49:33'),
(42, 1, 6, 'CORP-MON-002', 'BenQ', NULL, 'brandnew', 5, '2026-02-17 09:50:29', '2026-02-17 09:49:55'),
(43, 1, 6, 'CORP-MON-003', 'BenQ', NULL, 'brandnew', 6, '2026-02-17 09:50:38', '2026-02-17 09:50:06'),
(44, 1, 3, 'CORP-PC-004', 'Samsung', NULL, 'used', 34, '2026-03-23 06:32:32', '2026-03-23 06:28:08'),
(45, 1, 3, 'CORP-PC-005', 'Samsung', NULL, 'used', 24, '2026-03-23 06:33:03', '2026-03-23 06:28:20'),
(46, 1, 6, 'CORP-MON-004', 'Princeton', NULL, 'used', 34, '2026-03-23 06:31:30', '2026-03-23 06:28:39'),
(47, 1, 6, 'CORP-MON-005', 'Princeton', NULL, 'used', 24, '2026-03-23 06:32:43', '2026-03-23 06:28:52'),
(49, 1, 5, 'CORP-KB-005', 'Meetion', NULL, 'brandnew', 24, '2026-03-23 06:32:50', '2026-03-23 06:29:55'),
(50, 1, 5, 'CORP-KB-004', 'Meetion', NULL, 'brandnew', 34, '2026-03-23 06:32:15', '2026-03-23 06:32:00'),
(51, 6, 11, 'CONF-PROJEC-001', 'Epson', NULL, 'used', 22, '2026-03-23 06:37:29', '2026-03-23 06:35:31'),
(52, 3, 11, 'CLAS-02-PROJEC-002', 'BenQ', NULL, 'brandnew', 22, '2026-03-23 06:43:37', '2026-03-23 06:35:48'),
(53, 3, 11, 'CLAS-02-PROJEC-003', 'BenQ', NULL, 'used', 22, '2026-03-23 06:43:43', '2026-03-23 06:35:59'),
(54, 4, 11, 'CLAS-03-PROJEC-004', 'Hitachi', NULL, 'used', 22, '2026-03-23 06:44:05', '2026-03-23 06:36:35'),
(55, 3, 3, 'CLAS-02-PC-001', 'Samsung', NULL, 'used', 22, '2026-03-23 06:43:30', '2026-03-23 06:38:43'),
(56, 4, 3, 'CLAS-03-PC-002', 'Samsung', NULL, 'used', 22, '2026-03-23 06:43:56', '2026-03-23 06:38:53'),
(57, 4, 6, 'CLAS-03-MON-002', 'Princeton', NULL, 'used', 22, '2026-03-23 06:43:49', '2026-03-23 06:39:21'),
(58, 3, 5, 'CLAS-02-KB-001', 'Meetion', NULL, 'brandnew', 22, '2026-03-23 06:43:22', '2026-03-23 06:39:38'),
(59, 4, 5, 'CLAS-03-KB-002', 'Meetion', NULL, 'brandnew', 22, '2026-03-23 06:43:14', '2026-03-23 06:39:51'),
(60, 1, 12, 'CORP-MOUS-001', 'Random', NULL, 'used', 1, '2026-03-23 06:46:37', '2026-03-23 06:45:27'),
(61, 1, 12, 'CORP-MOUS-002', 'Random', NULL, 'used', 5, '2026-03-23 06:46:47', '2026-03-23 06:45:39'),
(62, 1, 12, 'CORP-MOUS-003', 'Random', NULL, 'used', 6, '2026-03-23 06:46:56', '2026-03-23 06:45:51'),
(63, 1, 12, 'CORP-MOUS-004', 'Meetion', NULL, 'brandnew', 34, '2026-03-23 06:47:04', '2026-03-23 06:46:05'),
(64, 1, 12, 'CORP-MOUS-005', 'Meetion', NULL, 'brandnew', 24, '2026-03-23 06:47:16', '2026-03-23 06:46:18'),
(65, 1, 10, 'CORP-TRIPOD-001', 'Random', NULL, 'brandnew', 22, '2026-03-23 07:44:39', '2026-03-23 07:42:45'),
(66, 1, 7, 'CORP-WEB-CAM-001', 'Logitech', NULL, 'brandnew', 22, '2026-03-23 07:44:56', '2026-03-23 07:42:56'),
(67, 1, 13, 'CORP-USB-CABLE-001', 'Random', NULL, 'brandnew', 22, '2026-03-23 07:44:49', '2026-03-23 07:44:22'),
(68, 3, 6, 'CLAS-02-MON-001', 'Lenovo', NULL, 'used', 22, '2026-03-23 07:46:23', '2026-03-23 07:45:56'),
(69, 1, 7, 'CORP-WEB-CAM-002', 'Logitech', NULL, 'brandnew', 22, '2026-03-23 07:52:56', '2026-03-23 07:48:11'),
(70, 1, 13, 'CORP-USB-CABLE-002', 'Random', NULL, 'brandnew', 22, '2026-03-23 07:52:48', '2026-03-23 07:48:24'),
(71, 3, 12, 'CLAS-02-MOUS-001', 'Meetion', NULL, 'brandnew', 22, '2026-03-23 07:52:31', '2026-03-23 07:51:07'),
(72, 4, 12, 'CLAS-03-MOUS-002', 'Meetion', NULL, 'brandnew', 22, '2026-03-23 07:52:24', '2026-03-23 07:52:11'),
(73, 1, 8, 'CORP-MIC-WIRELESS-001', 'Boya', NULL, 'brandnew', NULL, NULL, '2026-03-30 05:23:46'),
(74, 1, 8, 'CORP-MIC-WIRELESS-002', 'Boya', NULL, 'brandnew', NULL, NULL, '2026-03-30 05:24:24'),
(75, 1, 8, 'CORP-MIC-WIRELESS-003', 'Boya', NULL, 'brandnew', NULL, NULL, '2026-03-30 05:24:34'),
(76, 1, 8, 'CORP-MIC-WIRELESS-004', 'Boya', NULL, 'brandnew', NULL, NULL, '2026-03-30 05:25:01'),
(77, 1, 9, 'CORP-MIC-WIRED-001', 'Free Power', NULL, 'brandnew', NULL, NULL, '2026-03-30 06:36:02'),
(78, 1, 9, 'CORP-MIC-WIRED-002', 'Random', NULL, 'used', NULL, NULL, '2026-03-30 06:36:21'),
(79, 1, 3, 'CORP-PC-006', 'Samsung', NULL, 'used', 28, '2026-06-10 10:31:13', '2026-04-29 08:02:23'),
(80, 1, 3, 'CORP-PC-007', 'Samsung', NULL, 'used', 30, '2026-07-22 04:25:25', '2026-04-29 08:02:35'),
(81, 1, 6, 'CORP-MON-006', 'Random', NULL, 'used', 28, '2026-06-10 10:54:50', '2026-04-29 08:03:29'),
(82, 1, 6, 'CORP-MON-007', 'Random', NULL, 'used', 30, '2026-07-22 04:27:36', '2026-04-29 08:03:38'),
(83, 1, 12, 'CORP-MOUS-006', 'Meetion', NULL, 'brandnew', 28, '2026-06-10 10:50:05', '2026-04-29 08:08:17'),
(84, 1, 12, 'CORP-MOUS-007', 'Meetion', NULL, 'brandnew', 30, '2026-07-22 04:28:16', '2026-04-29 08:08:28'),
(85, 1, 5, 'CORP-KB-006', 'Meetion', NULL, 'brandnew', 28, '2026-06-10 10:47:29', '2026-04-29 08:08:48'),
(86, 1, 5, 'CORP-KB-007', 'Meetion', NULL, 'brandnew', 30, '2026-07-22 04:26:07', '2026-04-29 08:09:00'),
(87, 1, 10, 'CORP-TRIPOD-002', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:26:44'),
(88, 1, 10, 'CORP-TRIPOD-003', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:26:55'),
(89, 1, 10, 'CORP-TRIPOD-004', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:27:35'),
(90, 1, 10, 'CORP-TRIPOD-005', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:27:44'),
(91, 1, 13, 'CORP-USB-CABLE-003', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:28:59'),
(92, 1, 13, 'CORP-USB-CABLE-004', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:29:08'),
(93, 1, 13, 'CORP-USB-CABLE-005', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:29:20'),
(94, 1, 7, 'CORP-WEB-CAM-003', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:30:23'),
(95, 1, 7, 'CORP-WEB-CAM-004', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:30:33'),
(96, 1, 7, 'CORP-WEB-CAM-005', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:30:42'),
(97, 1, 14, 'CORP-MOB-ROUTERS-001', 'Huawei', NULL, 'used', NULL, NULL, '2026-04-29 08:32:19'),
(98, 1, 14, 'CORP-MOB-ROUTERS-002', 'Huawei', NULL, 'used', NULL, NULL, '2026-04-29 08:32:31'),
(99, 1, 16, 'CORP-CLICKER-001', 'Logitech', NULL, 'brandnew', 22, '2026-07-17 04:58:27', '2026-04-29 08:33:58'),
(100, 1, 16, 'CORP-CLICKER-002', 'Baseus', NULL, 'brandnew', 22, '2026-07-17 04:58:44', '2026-04-29 08:34:19'),
(101, 1, 15, 'CORP-HEADPHoNE-001', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:34:33'),
(102, 1, 15, 'CORP-HEADPHoNE-002', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:34:43'),
(103, 1, 15, 'CORP-HEADPHoNE-003', 'Logitech', NULL, 'brandnew', NULL, NULL, '2026-04-29 08:34:50'),
(104, 1, 17, 'CORP-PRINTER-001', 'Canon', 'LBP 6040', 'used', NULL, NULL, '2026-04-30 07:06:33'),
(105, 1, 17, 'CORP-PRINTER-002', 'HP LaserJet', 'M402dn', 'used', NULL, NULL, '2026-04-30 07:07:09'),
(106, 1, 17, 'CORP-PRINTER-003', 'Canon', 'MF641C', 'used', NULL, NULL, '2026-04-30 07:08:28'),
(107, 1, 6, 'CORP-MON-010', 'Asus', NULL, 'used', 5, '2026-07-17 04:58:16', '2026-04-30 07:29:42'),
(108, 1, 18, 'CORP-TV-001', 'Random', NULL, 'used', NULL, NULL, '2026-04-30 07:30:28'),
(109, 1, 19, 'CORP-NET-ADAPTER-001', 'Random', NULL, 'brandnew', 5, '2026-04-30 07:36:12', '2026-04-30 07:34:47'),
(110, 1, 19, 'CORP-NET-ADAPTER-002', 'Random', NULL, 'brandnew', 6, '2026-06-10 10:56:54', '2026-04-30 07:35:00'),
(111, 1, 19, 'CORP-NET-ADAPTER-003', 'Random', NULL, 'brandnew', 28, '2026-06-10 11:07:05', '2026-04-30 07:35:09'),
(112, 1, 19, 'CORP-NET-ADAPTER-004', 'Random', NULL, 'brandnew', 30, '2026-06-10 11:07:13', '2026-04-30 07:35:20'),
(113, 1, 19, 'CORP-NET-ADAPTER-005', 'Random', NULL, 'brandnew', 25, '2026-06-10 11:07:27', '2026-04-30 07:35:28'),
(114, 1, 19, 'CORP-NET-ADAPTER-006', 'Random', NULL, 'brandnew', 14, '2026-06-10 11:07:37', '2026-04-30 07:35:36'),
(115, 1, 19, 'CORP-NET-ADAPTER-007', 'Random', NULL, 'brandnew', 33, '2026-06-10 11:08:07', '2026-04-30 07:35:45'),
(116, 1, 20, 'CORP-EXT-HARD-001', 'Transcend', 'Broken', 'used', NULL, NULL, '2026-04-30 07:38:18'),
(117, 1, 20, 'CORP-EXT-HARD-002', 'Random', 'Broken', 'used', NULL, NULL, '2026-04-30 07:38:32'),
(118, 1, 21, 'CORP-SPEAKER-001', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-30 07:39:27'),
(119, 1, 21, 'CORP-SPEAKER-002', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-30 07:39:43'),
(120, 1, 22, 'CORP-USB-EXTENDER-001', 'Random', NULL, 'brandnew', NULL, NULL, '2026-04-30 07:43:07'),
(121, 1, 3, 'CORP-PC-008', 'Random', NULL, 'used', 14, '2026-06-10 10:30:26', '2026-06-10 10:27:52'),
(122, 1, 3, 'CORP-PC-009', 'Random', 'DESKTOP-1C9HHGH', 'used', 33, '2026-06-10 10:29:53', '2026-06-10 10:28:10'),
(123, 1, 5, 'CORP-KB-008', 'Meetion', NULL, 'brandnew', 14, NULL, '2026-06-10 10:44:59'),
(124, 1, 5, 'CORP-KB-009', 'Meetion', NULL, 'brandnew', 33, NULL, '2026-06-10 10:45:09'),
(125, 1, 12, 'CORP-MOUS-008', 'Meetion', NULL, 'brandnew', 14, '2026-06-10 10:51:11', '2026-06-10 10:50:44'),
(126, 1, 12, 'CORP-MOUS-009', 'Meetion', NULL, 'brandnew', 33, '2026-06-10 10:51:34', '2026-06-10 10:50:55'),
(127, 1, 6, 'CORP-MON-008', 'Random', NULL, 'used', 14, '2026-06-10 10:55:11', '2026-06-10 10:53:37'),
(128, 1, 6, 'CORP-MON-009', 'Random', NULL, 'used', 33, '2026-06-10 10:55:19', '2026-06-10 10:53:48'),
(129, 1, 19, 'CORP-NET-ADAPTER-008', 'Random', NULL, 'brandnew', 34, NULL, '2026-06-10 11:06:05'),
(130, 3, 19, 'CLAS-02-NET-ADAPTER-009', 'Random', NULL, 'brandnew', NULL, NULL, '2026-06-10 11:09:10'),
(131, 1, 12, 'CORP-MOUS-010', 'Random', NULL, 'used', 25, '2026-06-10 11:18:03', '2026-06-10 11:17:46'),
(132, 1, 23, 'CORP-USB-DRIVE-001', 'Kingston', NULL, 'brandnew', 9, '2026-07-17 04:55:39', '2026-07-17 04:54:25'),
(133, 1, 23, 'CORP-USB-DRIVE-002', 'Kingston', NULL, 'brandnew', 10, '2026-07-17 04:55:55', '2026-07-17 04:54:44'),
(134, 1, 23, 'CORP-USB-DRIVE-003', 'Kingston', NULL, 'brandnew', 16, '2026-07-17 04:57:18', '2026-07-17 04:54:56'),
(135, 1, 23, 'CORP-USB-DRIVE-004', 'Kingston', NULL, 'brandnew', 3, '2026-08-03 04:06:43', '2026-07-17 04:55:07'),
(136, 1, 23, 'CORP-USB-DRIVE-005', 'Kingston', NULL, 'brandnew', 7, '2026-09-21 05:10:08', '2026-08-03 04:09:25'),
(137, 8, 24, 'GEN-AREA-TABLE-001', 'Random', NULL, 'brandnew', NULL, NULL, '2026-08-05 07:53:53'),
(138, 1, 4, 'CORP-UPS-004', 'Random', NULL, 'brandnew', 39, '2026-08-17 05:45:09', '2026-08-17 05:44:27'),
(139, 1, 4, 'CORP-UPS-005', 'Random', NULL, 'brandnew', 14, '2026-08-17 05:46:00', '2026-08-17 05:44:39'),
(142, 1, 23, 'CORP-USB-DRIVE-006', 'Kingston', NULL, 'brandnew', 13, '2026-09-21 05:10:21', '2026-09-21 05:08:01'),
(144, 1, 23, 'CORP-USB-DRIVE-007', 'Kingston', NULL, 'brandnew', 1, '2026-09-21 05:10:36', '2026-09-21 05:08:29'),
(145, 1, 3, 'CORP-PC-010', 'Random', NULL, 'used', 37, '2026-09-21 05:15:01', '2026-09-21 05:11:51'),
(146, 1, 3, 'CORP-PC-011', 'Random', NULL, 'used', 38, '2026-09-21 05:15:41', '2026-09-21 05:12:08'),
(148, 1, 12, 'CORP-MOUS-011', 'Meetion', NULL, 'brandnew', 37, '2026-09-21 05:25:26', '2026-09-21 05:18:38'),
(149, 1, 12, 'CORP-MOUS-012', 'Meetion', NULL, 'brandnew', 38, '2026-09-21 05:25:37', '2026-09-21 05:19:00'),
(151, 1, 5, 'CORP-KB-010', 'Meetion', NULL, 'brandnew', 37, '2026-09-21 05:28:58', '2026-09-21 05:26:51'),
(152, 1, 5, 'CORP-KB-011', 'Meetion', NULL, 'brandnew', 38, '2026-09-21 05:29:16', '2026-09-21 05:27:11'),
(154, 1, 6, 'CORP-MON-011', 'Random', NULL, 'brandnew', 37, '2026-09-21 05:29:31', '2026-09-21 05:27:57'),
(156, 1, 6, 'CORP-MON-012', 'Random', NULL, 'brandnew', 38, '2026-09-21 05:29:42', '2026-09-21 05:28:24'),
(157, 1, 19, 'CORP-NET-ADAPTER-009', 'Random', NULL, 'brandnew', 37, '2026-09-21 05:37:33', '2026-09-21 05:36:59'),
(158, 1, 19, 'CORP-NET-ADAPTER-010', 'Random', NULL, 'brandnew', 38, '2026-09-21 05:37:43', '2026-09-21 05:37:15'),
(159, 1, 4, 'CORP-UPS-006', 'Random', NULL, 'brandnew', 38, '2026-10-12 02:53:09', '2026-10-12 02:49:27'),
(160, 1, 4, 'CORP-UPS-007', 'Random', NULL, 'brandnew', 30, '2026-10-19 03:59:05', '2026-10-12 02:49:38'),
(161, 1, 4, 'CORP-UPS-008', 'Random', NULL, 'brandnew', NULL, NULL, '2026-10-12 02:49:53'),
(162, 1, 4, 'CORP-UPS-009', 'Random', NULL, 'brandnew', NULL, NULL, '2026-10-12 02:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `created_at`) VALUES
(1, 'Laptops', 'LAP', '2026-02-16 10:39:01'),
(2, 'Laptop Chargers', 'LAP-CHGR', '2026-02-16 10:39:09'),
(3, 'Desktop PCs', 'PC', '2026-02-16 10:39:25'),
(4, 'UPS', 'UPS', '2026-02-16 10:39:35'),
(5, 'Key Boards', 'KB', '2026-02-17 06:36:52'),
(6, 'Monitors', 'MON', '2026-02-17 09:48:52'),
(7, 'Web Cameras', 'WEB-CAM', '2026-02-17 09:56:23'),
(8, 'Wireless Microphones', 'MIC-WIRELESS', '2026-02-17 09:57:17'),
(9, 'Wired Microphones', 'MIC-WIRED', '2026-02-17 09:57:38'),
(10, 'Tripods', 'TRIPOD', '2026-02-17 09:58:08'),
(11, 'Projectors', 'PROJEC', '2026-02-17 10:06:02'),
(12, 'Mouse', 'MOUS', '2026-03-23 06:30:59'),
(13, 'USB Cable', 'USB-CABLE', '2026-03-23 07:44:03'),
(14, 'Mobile Routers', 'MOB-ROUTERS', '2026-04-29 08:31:56'),
(15, 'Headphones', 'HEADPHoNE', '2026-04-29 08:33:20'),
(16, 'Clickers', 'CLICKER', '2026-04-29 08:33:40'),
(17, 'Printers', 'PRINTER', '2026-04-30 07:05:06'),
(18, 'TV', 'TV', '2026-04-30 07:30:09'),
(19, 'Network Adapter', 'NET-ADAPTER', '2026-04-30 07:34:32'),
(20, 'External Hard Drives', 'EXT-HARD', '2026-04-30 07:37:38'),
(21, 'Speakers', 'SPEAKER', '2026-04-30 07:39:14'),
(22, 'USB Extenders', 'USB-EXTENDER', '2026-04-30 07:42:55'),
(23, 'USB Drives', 'USB-DRIVE', '2026-07-17 04:54:05'),
(24, 'Tables', 'TABLE', '2026-08-05 07:53:37');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `code`, `created_at`) VALUES
(1, 'Coporate Office', 'CORP', '2026-02-16 10:40:16'),
(2, 'Classroom 01', 'CLAS-01', '2026-02-16 10:40:23'),
(3, 'Classroom 02', 'CLAS-02', '2026-02-17 10:03:24'),
(4, 'Classroom 03', 'CLAS-03', '2026-02-17 10:03:40'),
(5, 'Classroom 04', 'CLAS-04', '2026-02-17 10:03:56'),
(6, 'Conference Room', 'CONF', '2026-02-17 10:04:11'),
(8, 'General Area', 'GEN-AREA', '2026-08-05 07:53:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
