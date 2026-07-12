-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2026 at 11:19 PM
-- Server version: 11.4.12-MariaDB-cll-lve-log
-- PHP Version: 8.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vkityfiw_tmvosa`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(10) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-23 16:38:31'),
(2, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-23 16:38:54'),
(3, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Cursor/3.8.11 Chrome/144.0.7559.236 Electron/40.10.3 Safari/537.36', '2026-06-23 16:39:28'),
(4, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 16:41:27'),
(5, 1, 'logout', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 16:43:03'),
(6, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 17:09:06'),
(7, 1, 'logout', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-23 17:23:53'),
(8, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 05:56:10'),
(9, 1, 'database_backup', 'system', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 05:57:31'),
(10, 1, 'logout', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 06:01:01'),
(11, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 06:01:12'),
(12, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:17:57'),
(13, 1, 'password_changed', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:17:58'),
(14, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:19:18'),
(15, 1, 'password_changed', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:19:19'),
(16, 1, 'logout', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 06:22:47'),
(17, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 06:23:10'),
(18, 1, 'logout', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 06:34:56'),
(19, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 06:53:03'),
(20, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:54:56'),
(21, 1, 'delete_application', 'member_applications', 1, '{\"status\": \"pending\", \"full_name\": \"vijaykumar keerththeejan\", \"application_number\": \"APP-2026-0001\"}', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 06:54:56'),
(22, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 07:04:57'),
(23, 1, 'email_test', 'email_settings', NULL, NULL, '{\"to\": \"tmvosa@vkitnet.info\", \"success\": true}', '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 07:05:02'),
(24, 1, 'login', 'users', 1, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8655', '2026-06-24 07:07:19'),
(25, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 07:09:18'),
(26, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 07:10:21'),
(27, 1, 'email_test', 'email_settings', NULL, NULL, '{\"to\": \"admin@osa-alumni.org\", \"success\": false}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 07:10:33'),
(28, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 07:10:33'),
(29, 1, 'email_test', 'email_settings', NULL, NULL, '{\"to\": \"keerththeejan@gmail.com\", \"success\": true}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 07:10:58'),
(30, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '175.157.8.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:16:24'),
(31, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '175.157.8.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:22:54'),
(32, 1, 'update_settings', 'email_settings', NULL, NULL, NULL, '175.157.8.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:30:21'),
(33, 1, 'email_test', 'email_settings', NULL, NULL, '{\"to\":\"keerththeejan@gmail.com\",\"success\":true}', '175.157.8.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 07:39:42'),
(34, 1, 'logout', 'users', 1, NULL, NULL, '175.157.8.98', 'Mozilla/5.0 (Linux; Android 13; SM-G981B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 08:01:40'),
(35, 1, 'login', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:27:53'),
(36, 1, 'approve_application', 'member_applications', 2, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:28:06'),
(37, 1, 'edit_member', 'members', 1, '{\"id\":\"1\",\"user_id\":null,\"membership_number\":\"OSA-2026-0001\",\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":\"1998-04-30\",\"nic_number\":\"981213432V\",\"current_address\":\"Kilinochchi\",\"permanent_address\":\"\",\"country_id\":\"1\",\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":null,\"studied_from_year\":\"2014\",\"studied_to_year\":\"2015\",\"grade_stream\":\"\",\"teacher_name\":\"\",\"occupation\":\"\",\"company\":\"user\",\"membership_type_id\":\"1\",\"status\":\"active\",\"photo\":null,\"membership_start_date\":\"2026-06-24\",\"membership_expiry_date\":\"2027-06-24\",\"batch\":\"2015\",\"notes\":null,\"created_by\":null,\"approved_by\":\"1\",\"approved_at\":\"2026-06-24 13:58:06\",\"created_at\":\"2026-06-24 04:28:06\",\"updated_at\":\"2026-06-24 04:28:06\",\"membership_type_name\":\"Ordinary Member\",\"fee\":\"1000.00\",\"country_name\":\"Sri Lanka\"}', '{\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":1,\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":1,\"status\":\"active\"}', '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:39:04'),
(38, 1, 'profile_updated', 'members', 1, '{\"id\":\"1\",\"user_id\":null,\"membership_number\":\"OSA-2026-0001\",\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":\"1998-04-30\",\"nic_number\":\"981213432V\",\"current_address\":\"Kilinochchi\",\"permanent_address\":\"\",\"country_id\":\"1\",\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":null,\"studied_from_year\":\"2014\",\"studied_to_year\":\"2015\",\"grade_stream\":\"\",\"teacher_name\":\"\",\"occupation\":\"\",\"company\":\"user\",\"membership_type_id\":\"1\",\"status\":\"active\",\"photo\":null,\"membership_start_date\":\"2026-06-24\",\"membership_expiry_date\":\"2027-06-24\",\"batch\":\"2015\",\"notes\":null,\"created_by\":null,\"approved_by\":\"1\",\"approved_at\":\"2026-06-24 13:58:06\",\"created_at\":\"2026-06-24 04:28:06\",\"updated_at\":\"2026-06-24 04:28:06\",\"membership_type_name\":\"Ordinary Member\",\"fee\":\"1000.00\",\"country_name\":\"Sri Lanka\"}', '{\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":1,\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":1,\"status\":\"active\"}', '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:39:04'),
(39, 1, 'edit_member', 'members', 1, '{\"id\":\"1\",\"user_id\":null,\"membership_number\":\"OSA-2026-0001\",\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":\"1\",\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"studied_from_year\":\"2014\",\"studied_to_year\":\"2015\",\"grade_stream\":\"\",\"teacher_name\":\"\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":\"1\",\"status\":\"active\",\"photo\":null,\"membership_start_date\":\"2026-06-24\",\"membership_expiry_date\":\"2027-06-24\",\"batch\":\"2015\",\"notes\":null,\"created_by\":null,\"approved_by\":\"1\",\"approved_at\":\"2026-06-24 13:58:06\",\"created_at\":\"2026-06-24 04:28:06\",\"updated_at\":\"2026-06-24 04:39:04\",\"membership_type_name\":\"Ordinary Member\",\"fee\":\"1000.00\",\"country_name\":\"Sri Lanka\"}', '{\"full_name_tamil\":\"Vijayakumar keerththeejan\",\"full_name_english\":\"Vijayakumar keerththeejan\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":1,\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":1,\"status\":\"active\"}', '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:40:43'),
(40, 1, 'profile_updated', 'members', 1, '{\"id\":\"1\",\"user_id\":null,\"membership_number\":\"OSA-2026-0001\",\"full_name_tamil\":\"user user\",\"full_name_english\":\"user user\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":\"1\",\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"studied_from_year\":\"2014\",\"studied_to_year\":\"2015\",\"grade_stream\":\"\",\"teacher_name\":\"\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":\"1\",\"status\":\"active\",\"photo\":null,\"membership_start_date\":\"2026-06-24\",\"membership_expiry_date\":\"2027-06-24\",\"batch\":\"2015\",\"notes\":null,\"created_by\":null,\"approved_by\":\"1\",\"approved_at\":\"2026-06-24 13:58:06\",\"created_at\":\"2026-06-24 04:28:06\",\"updated_at\":\"2026-06-24 04:39:04\",\"membership_type_name\":\"Ordinary Member\",\"fee\":\"1000.00\",\"country_name\":\"Sri Lanka\"}', '{\"full_name_tamil\":\"Vijayakumar keerththeejan\",\"full_name_english\":\"Vijayakumar keerththeejan\",\"gender\":\"male\",\"date_of_birth\":null,\"nic_number\":\"981213432V\",\"current_address\":\"\",\"permanent_address\":\"\",\"country_id\":1,\"mobile\":\"+94778870135\",\"whatsapp\":\"\",\"email\":\"keerththeejan@gmail.com\",\"occupation\":\"\",\"company\":\"\",\"membership_type_id\":1,\"status\":\"active\"}', '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:40:43'),
(41, 1, 'logout', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:46:22'),
(42, 1, 'login', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:48:57'),
(43, 1, 'update_settings', 'settings', NULL, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:51:21'),
(44, 1, 'logout', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-24 08:51:34'),
(45, 1, 'login', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 09:18:33'),
(46, 1, 'delete_application', 'member_applications', 4, '{\"application_number\":\"APP-2026-0004\",\"status\":\"pending\",\"full_name\":\"user user\"}', NULL, '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 09:18:42'),
(47, 1, 'delete_application', 'member_applications', 3, '{\"application_number\":\"APP-2026-0003\",\"status\":\"pending\",\"full_name\":\"keerththeejan\"}', NULL, '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 09:18:45'),
(48, 1, 'logout', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 09:35:47'),
(49, 1, 'login', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 09:56:08'),
(50, 1, 'login', 'users', 1, NULL, NULL, '175.157.53.218', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-24 21:05:16'),
(51, 1, 'login', 'users', 1, NULL, NULL, '175.157.170.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-26 13:43:55'),
(52, 1, 'approve_application', 'member_applications', 5, NULL, NULL, '175.157.170.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-26 13:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(3) NOT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `phone_code`, `is_active`) VALUES
(1, 'Sri Lanka', 'LK', '+94', 1),
(2, 'India', 'IN', '+91', 1),
(3, 'United Kingdom', 'GB', '+44', 1),
(4, 'United States', 'US', '+1', 1),
(5, 'Canada', 'CA', '+1', 1),
(6, 'Australia', 'AU', '+61', 1),
(7, 'United Arab Emirates', 'AE', '+971', 1),
(8, 'Qatar', 'QA', '+974', 1),
(9, 'Saudi Arabia', 'SA', '+966', 1),
(10, 'Singapore', 'SG', '+65', 1),
(11, 'Malaysia', 'MY', '+60', 1),
(12, 'Germany', 'DE', '+49', 1),
(13, 'France', 'FR', '+33', 1),
(14, 'Japan', 'JP', '+81', 1),
(15, 'Other', 'OT', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `variables`, `is_active`, `updated_at`) VALUES
(1, 'application_received', 'Application Submitted - {{application_number}}', '<p>Dear {{full_name}},</p><p>Thank you for submitting your OSA membership application.</p><p><strong>Application Number:</strong> {{application_number}}</p><p>Your application is under review. We will notify you once a decision is made.</p><p>Contact: 077 887 0135 | tmvosa@vkitnet.info</p>', '[\"full_name\", \"application_number\"]', 1, '2026-06-24 05:58:02'),
(2, 'application_approved', 'Application Approved - {{membership_number}}', '<p>Dear {{full_name}},</p><p>Congratulations! Your membership application has been <strong>approved</strong>.</p><p><strong>Membership Number:</strong> {{membership_number}}</p><p>Welcome to the Old Students\' Association.</p>', '[\"full_name\", \"membership_number\"]', 1, '2026-06-24 05:58:02'),
(3, 'application_rejected', 'Application Rejected', '<p>Dear {{full_name}},</p><p>We regret to inform you that your membership application could not be approved at this time.</p><p><strong>Reason:</strong> {{reason}}</p><p>For inquiries, contact 077 887 0135 or tmvosa@vkitnet.info</p>', '[\"full_name\", \"reason\"]', 1, '2026-06-24 05:58:02'),
(4, 'payment_verified', 'Payment Verified - {{receipt_number}}', '<p>Dear {{full_name}},</p><p>Your payment of Rs. {{amount}} has been verified. Receipt number: {{receipt_number}}</p><p>Thank you,<br>OSA Alumni</p>', '[\"full_name\", \"amount\", \"receipt_number\"]', 1, '2026-06-23 16:38:22'),
(5, 'welcome_email', 'Welcome to OSA Membership', '<p>Dear {{full_name}},</p><p>Welcome to the Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students\' Association.</p><p>Your membership number is <strong>{{membership_number}}</strong>.</p><p>Thank you for staying connected with our alumni community.</p>', '[\"full_name\", \"membership_number\"]', 1, '2026-06-24 05:58:02'),
(6, 'membership_activated', 'Membership Activated - {{membership_number}}', '<p>Dear {{full_name}},</p><p>Your OSA membership is now <strong>active</strong>.</p><p>Membership Number: <strong>{{membership_number}}</strong><br>Valid Until: <strong>{{expiry_date}}</strong></p><p>Thank you,<br>OSA Secretariat</p>', '[\"full_name\", \"membership_number\", \"expiry_date\"]', 1, '2026-06-24 05:58:02'),
(7, 'password_reset', 'Your OSA Account Password Has Been Reset', '<p>Dear {{full_name}},</p><p>Your account password was reset by an administrator.</p><p><strong>Temporary Password:</strong> {{temporary_password}}</p><p>Please log in and change your password immediately.</p>', '[\"full_name\", \"temporary_password\"]', 1, '2026-06-24 05:58:02'),
(8, 'password_changed_confirmation', 'Password Changed Successfully', '<p>Dear {{full_name}},</p><p>This confirms that your OSA account password was changed successfully on {{changed_at}}.</p><p>If you did not make this change, please contact the secretary immediately.</p>', '[\"full_name\", \"changed_at\"]', 1, '2026-06-24 05:58:02'),
(9, 'membership_expiry_reminder', 'Membership Expiry Reminder - {{membership_number}}', '<p>Dear {{full_name}},</p><p>Your OSA membership (<strong>{{membership_number}}</strong>) will expire on <strong>{{expiry_date}}</strong>.</p><p>Please renew your membership to remain active in the alumni association.</p><p>Contact: 077 887 0135 | tmvosa@vkitnet.info</p>', '[\"full_name\", \"membership_number\", \"expiry_date\"]', 1, '2026-06-24 05:58:02'),
(10, 'admin_notification', 'New Membership Application - {{application_number}}', '<p>A new membership application has been submitted.</p><p><strong>Application Number:</strong> {{application_number}}<br><strong>Applicant:</strong> {{full_name}}<br><strong>Mobile:</strong> {{mobile}}<br><strong>Email:</strong> {{email}}</p><p>Please review the application in the admin panel.</p>', '[\"application_number\", \"full_name\", \"mobile\", \"email\"]', 1, '2026-06-24 05:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `membership_number` varchar(20) NOT NULL,
  `full_name_tamil` varchar(200) DEFAULT NULL,
  `full_name_english` varchar(200) NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nic_number` varchar(20) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `country_id` smallint(5) UNSIGNED DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `studied_from_year` year(4) DEFAULT NULL,
  `studied_to_year` year(4) DEFAULT NULL,
  `grade_stream` varchar(100) DEFAULT NULL,
  `teacher_name` varchar(150) DEFAULT NULL,
  `occupation` varchar(150) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `membership_type_id` tinyint(3) UNSIGNED NOT NULL,
  `status` enum('pending','under_review','payment_verified','approved','active','suspended','expired') DEFAULT 'pending',
  `photo` varchar(255) DEFAULT NULL,
  `membership_start_date` date DEFAULT NULL,
  `membership_expiry_date` date DEFAULT NULL,
  `batch` varchar(20) GENERATED ALWAYS AS (`studied_to_year`) STORED,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `user_id`, `membership_number`, `full_name_tamil`, `full_name_english`, `gender`, `date_of_birth`, `nic_number`, `current_address`, `permanent_address`, `country_id`, `mobile`, `whatsapp`, `email`, `studied_from_year`, `studied_to_year`, `grade_stream`, `teacher_name`, `occupation`, `company`, `membership_type_id`, `status`, `photo`, `membership_start_date`, `membership_expiry_date`, `notes`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'OSA-2026-0001', 'Vijayakumar keerththeejan', 'Vijayakumar keerththeejan', 'male', NULL, '981213432V', '', '', 1, '+94778870135', '', 'keerththeejan@gmail.com', '2014', '2015', '', '', '', '', 1, 'active', 'photos/19a166ab4833469d807ada0c174983d3.jpeg', '2026-06-24', '2027-06-24', NULL, NULL, 1, '2026-06-24 17:58:06', '2026-06-24 08:28:06', '2026-06-24 08:40:43'),
(2, NULL, 'OSA-2026-0002', 'THURAIRASA KOBINATH', 'THURAIRASA KOBINATH', 'male', '1991-11-13', '913181050V', 'No:10/3,THIRUVAIYARU KILINOCHCHI', 'NO:10/3,THIRUVAIYARU KILINOCHCHI', 1, '0779219713', '0779219713', 'kobinathfriend@gmail.com', '2000', '2008', '', 'Mrs RAJARATNAM', 'Executive', 'MAS ACTIVEWEAR VAANAVIL', 1, 'active', NULL, '2026-06-26', '2027-06-26', NULL, NULL, 1, '2026-06-26 23:14:33', '2026-06-26 13:44:33', '2026-06-26 13:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `membership_cards`
--

CREATE TABLE `membership_cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `card_number` varchar(30) NOT NULL,
  `qr_code_data` text NOT NULL,
  `qr_code_path` varchar(500) DEFAULT NULL,
  `pdf_path` varchar(500) DEFAULT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membership_cards`
--

INSERT INTO `membership_cards` (`id`, `member_id`, `card_number`, `qr_code_data`, `qr_code_path`, `pdf_path`, `image_path`, `issued_at`, `expires_at`, `is_active`) VALUES
(1, 1, 'OSA-2026-0001', '{\"membership_number\":\"OSA-2026-0001\",\"name\":\"user user\",\"type\":\"Ordinary Member\",\"expiry\":\"2027-06-24\",\"verify_url\":\"\\/public\\/verify\\/OSA-2026-0001\"}', 'qr/OSA-2026-0001.png', NULL, NULL, '2026-06-24 08:39:27', '2027-06-24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `membership_types`
--

CREATE TABLE `membership_types` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration_years` int(10) UNSIGNED DEFAULT 1,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `membership_types`
--

INSERT INTO `membership_types` (`id`, `name`, `slug`, `fee`, `duration_years`, `description`, `is_active`, `created_at`) VALUES
(1, 'Ordinary Member', 'ordinary', 1000.00, 1, 'Annual membership - Rs. 1,000', 1, '2026-06-23 16:38:22'),
(2, '10-Year Membership', 'ten_year', 10000.00, 10, '10-Year membership - Rs. 10,000', 1, '2026-06-23 16:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `member_applications`
--

CREATE TABLE `member_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_number` varchar(20) NOT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `full_name_tamil` varchar(200) DEFAULT NULL,
  `full_name_english` varchar(200) NOT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nic_number` varchar(20) DEFAULT NULL,
  `current_address` text DEFAULT NULL,
  `permanent_address` text DEFAULT NULL,
  `country_id` smallint(5) UNSIGNED DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `studied_from_year` year(4) DEFAULT NULL,
  `studied_to_year` year(4) DEFAULT NULL,
  `grade_stream` varchar(100) DEFAULT NULL,
  `teacher_name` varchar(150) DEFAULT NULL,
  `occupation` varchar(150) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `proposer_name` varchar(150) DEFAULT NULL,
  `proposer_contact` varchar(20) DEFAULT NULL,
  `membership_type_id` tinyint(3) UNSIGNED NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_number` varchar(100) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('pending','under_review','payment_verified','approved','rejected','active') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `reviewed_by` int(10) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_applications`
--

INSERT INTO `member_applications` (`id`, `application_number`, `member_id`, `full_name_tamil`, `full_name_english`, `gender`, `date_of_birth`, `nic_number`, `current_address`, `permanent_address`, `country_id`, `mobile`, `whatsapp`, `email`, `studied_from_year`, `studied_to_year`, `grade_stream`, `teacher_name`, `occupation`, `company`, `proposer_name`, `proposer_contact`, `membership_type_id`, `amount_paid`, `payment_method`, `transaction_number`, `payment_date`, `status`, `rejection_reason`, `reviewed_by`, `reviewed_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(2, 'APP-2026-0002', 1, 'user user', 'user user', 'male', '1998-04-30', '981213432V', 'Kilinochchi', '', 1, '+94778870135', '', NULL, '2014', '2015', '', '', '', 'user', '', '', 1, 1000.00, 'bank_transfer', '', '2026-06-24', 'approved', NULL, 1, '2026-06-24 17:58:06', '175.157.53.218', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-06-24 17:54:35', '2026-06-24 08:28:06'),
(5, 'APP-2026-0005', 2, 'THURAIRASA KOBINATH', 'THURAIRASA KOBINATH', 'male', '1991-11-13', '913181050V', 'No:10/3,THIRUVAIYARU KILINOCHCHI', 'NO:10/3,THIRUVAIYARU KILINOCHCHI', 1, '0779219713', '0779219713', 'kobinathfriend@gmail.com', '2000', '2008', '', 'Mrs RAJARATNAM', 'Executive', 'MAS ACTIVEWEAR VAANAVIL', '', '', 1, 1000.00, 'cash', '', '2026-06-24', 'approved', NULL, 1, '2026-06-26 23:14:33', '175.157.236.144', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Mobile Safari/537.36', '2026-06-24 19:50:53', '2026-06-26 13:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `member_documents`
--

CREATE TABLE `member_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `application_id` int(10) UNSIGNED DEFAULT NULL,
  `document_type` enum('nic_copy','passport_photo','payment_slip','other') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(10) UNSIGNED DEFAULT 0,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_documents`
--

INSERT INTO `member_documents` (`id`, `member_id`, `application_id`, `document_type`, `file_name`, `file_path`, `file_size`, `mime_type`, `uploaded_by`, `created_at`) VALUES
(1, 1, 2, 'payment_slip', '17822894412944399786562647027403.jpg', 'documents/52f542a82f4a1950e17ac264816b5941.jpg', 3966042, 'image/jpeg', NULL, '2026-06-24 08:24:35'),
(4, 2, 5, 'passport_photo', 'file_00000000f56472089f7d976580002e23.jpg', 'documents/3ff2ba289d01922158dec7f1ffae5ac5.webp', 81886, 'image/webp', NULL, '2026-06-24 10:20:54'),
(5, 2, 5, 'payment_slip', '17822963628238542553698743624430.jpg', 'documents/a23258ffdcce1a27c614283e836cfdff.webp', 73618, 'image/webp', NULL, '2026-06-24 10:20:54'),
(6, 2, 5, 'nic_copy', 'IMG-20240319-WA0076.jpg', 'documents/816110560068ba67838cdee10e0ba428.webp', 79950, 'image/webp', NULL, '2026-06-24 10:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `number_sequences`
--

CREATE TABLE `number_sequences` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence_type` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `last_number` int(10) UNSIGNED DEFAULT 0,
  `prefix` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `number_sequences`
--

INSERT INTO `number_sequences` (`id`, `sequence_type`, `year`, `last_number`, `prefix`) VALUES
(1, 'membership', '2026', 2, 'OSA'),
(2, 'receipt', '2026', 0, 'REC'),
(3, 'application', '2026', 5, 'APP');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_number` varchar(100) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int(10) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_receipts`
--

CREATE TABLE `payment_receipts` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED NOT NULL,
  `receipt_number` varchar(20) NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `issued_by` int(10) UNSIGNED DEFAULT NULL,
  `issued_at` timestamp NULL DEFAULT current_timestamp(),
  `pdf_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super_admin', 'Full system access', '[\"*\"]', '2026-06-23 16:38:22', '2026-06-23 16:38:22'),
(2, 'Secretary', 'secretary', 'Member and application management', '[\"members.view\", \"members.create\", \"members.edit\", \"members.approve\", \"applications.view\", \"applications.edit\", \"applications.approve\", \"reports.view\"]', '2026-06-23 16:38:22', '2026-06-23 16:38:22'),
(3, 'Treasurer', 'treasurer', 'Payment management', '[\"payments.view\", \"payments.verify\", \"payments.edit\", \"receipts.generate\", \"reports.financial\"]', '2026-06-23 16:38:22', '2026-06-23 16:38:22'),
(4, 'Alumni Member', 'member', 'Alumni member access', '[\"profile.view\", \"profile.edit\", \"card.view\", \"application.submit\"]', '2026-06-23 16:38:22', '2026-06-23 16:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `description`, `updated_at`) VALUES
(1, 'site_name', 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA', 'general', 'Organization name', '2026-06-23 16:38:22'),
(2, 'site_name_tamil', 'கிளிநொச்சி/ திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்', 'general', 'Organization name in Tamil', '2026-06-24 08:51:21'),
(3, 'membership_prefix', 'OSA', 'membership', 'Membership number prefix', '2026-06-23 16:38:22'),
(4, 'receipt_prefix', 'REC', 'payment', 'Receipt number prefix', '2026-06-23 16:38:22'),
(5, 'session_timeout', '3600', 'security', 'Session timeout in seconds', '2026-06-23 16:38:22'),
(6, 'max_upload_size', '5242880', 'upload', 'Max file upload size in bytes (5MB)', '2026-06-23 16:38:22'),
(7, 'allowed_file_types', 'jpg,jpeg,png,pdf', 'upload', 'Allowed file extensions', '2026-06-23 16:38:22'),
(8, 'smtp_host', 'localhost', 'email', 'SMTP host', '2026-06-24 07:28:55'),
(9, 'smtp_port', '587', 'email', 'SMTP port', '2026-06-24 07:28:55'),
(10, 'smtp_encryption', 'tls', 'email', 'SMTP encryption', '2026-06-24 07:28:55'),
(11, 'smtp_username', 'tmvosa@vkitnet.info', 'email', 'SMTP username', '2026-06-24 05:58:02'),
(12, 'smtp_password', '', 'email', 'SMTP password', '2026-06-23 16:38:22'),
(13, 'from_email', 'tmvosa@vkitnet.info', 'email', 'From email address', '2026-06-24 05:58:02'),
(14, 'from_name', 'Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students&amp;amp;amp;amp;#039; Association', 'email', 'From name', '2026-06-24 07:30:21'),
(15, 'school_logo', 'assets/img/school-logo.png', 'branding', 'School logo path', '2026-06-23 16:38:22'),
(16, 'alumni_logo', 'assets/img/alumni-logo.png', 'branding', 'Alumni logo path', '2026-06-23 16:38:22'),
(17, 'admin_notification_email', 'tmvosa@vkitnet.info', 'email', 'Admin notification email address', '2026-06-24 05:58:02'),
(19, 'block_duplicate_mobile', '0', 'membership', 'Block application if mobile number already exists (1=yes, 0=warning only)', '2026-06-24 06:44:43'),
(20, 'block_duplicate_email', '0', 'membership', 'Block application if email already exists (1=yes, 0=warning only)', '2026-06-24 06:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` tinyint(3) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `full_name` varchar(150) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `email`, `password`, `password_changed_at`, `force_password_change`, `full_name`, `mobile`, `avatar`, `is_active`, `last_login_at`, `last_login_ip`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'admin@osa-alumni.org', '$2y$12$8aMQ6f1aVGxsBA2fxRPdAew3WOGykMUL/TZLvwdNHuFCZIsril.4K', '2026-06-24 06:19:19', 0, 'System Administrator', '0770000000', NULL, 1, '2026-06-26 23:13:55', '175.157.170.16', NULL, '2026-06-23 16:38:22', '2026-06-26 13:43:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `membership_number` (`membership_number`),
  ADD UNIQUE KEY `uq_members_nic_number` (`nic_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_membership_number` (`membership_number`),
  ADD KEY `idx_nic` (`nic_number`),
  ADD KEY `idx_mobile` (`mobile`),
  ADD KEY `idx_country` (`country_id`),
  ADD KEY `idx_membership_type` (`membership_type_id`),
  ADD KEY `idx_batch` (`studied_to_year`),
  ADD KEY `idx_expiry` (`membership_expiry_date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `approved_by` (`approved_by`);
ALTER TABLE `members` ADD FULLTEXT KEY `idx_search` (`full_name_english`,`full_name_tamil`,`occupation`,`company`);

--
-- Indexes for table `membership_cards`
--
ALTER TABLE `membership_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `membership_types`
--
ALTER TABLE `membership_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `member_applications`
--
ALTER TABLE `member_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_number` (`application_number`),
  ADD UNIQUE KEY `uq_member_applications_nic_number` (`nic_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_application_number` (`application_number`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `membership_type_id` (`membership_type_id`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `member_documents`
--
ALTER TABLE `member_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_member` (`member_id`),
  ADD KEY `idx_application` (`application_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_member` (`member_id`);

--
-- Indexes for table `number_sequences`
--
ALTER TABLE `number_sequences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_sequence` (`sequence_type`,`year`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_member` (`member_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_date` (`payment_date`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `verified_by` (`verified_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`),
  ADD KEY `idx_receipt_number` (`receipt_number`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `issued_by` (`issued_by`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `membership_cards`
--
ALTER TABLE `membership_cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `membership_types`
--
ALTER TABLE `membership_types`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `member_applications`
--
ALTER TABLE `member_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `member_documents`
--
ALTER TABLE `member_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `number_sequences`
--
ALTER TABLE `number_sequences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `members_ibfk_3` FOREIGN KEY (`membership_type_id`) REFERENCES `membership_types` (`id`),
  ADD CONSTRAINT `members_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `members_ibfk_5` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `membership_cards`
--
ALTER TABLE `membership_cards`
  ADD CONSTRAINT `membership_cards_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_applications`
--
ALTER TABLE `member_applications`
  ADD CONSTRAINT `member_applications_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `member_applications_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `member_applications_ibfk_3` FOREIGN KEY (`membership_type_id`) REFERENCES `membership_types` (`id`),
  ADD CONSTRAINT `member_applications_ibfk_4` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `member_documents`
--
ALTER TABLE `member_documents`
  ADD CONSTRAINT `member_documents_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `member_documents_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `member_applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `member_documents_ibfk_3` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`application_id`) REFERENCES `member_applications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payment_receipts`
--
ALTER TABLE `payment_receipts`
  ADD CONSTRAINT `payment_receipts_ibfk_1` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_receipts_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_receipts_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
