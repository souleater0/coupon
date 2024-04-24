-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 03:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecfoodstub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `display_name`, `pin`, `role_id`, `location`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', 'jerome', '1234', 1, 'ESKINA', NULL, NULL),
(2, 'test2', 'test2', 'jhondoll', '1234', 1, 'SNACK BAR', NULL, NULL),
(3, 'admin', 'admin', 'admin', '1111', 2, NULL, NULL, NULL),
(11, 'test43', 'test3', 'test4', NULL, 1, 'secret', '2024-04-15 06:10:56', NULL),
(12, 'manager', 'manager', 'Manager', 'CPgt8J4Y24rm2PNo0SC5', 3, 'NA', '2024-04-11 13:52:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `balance_deducted`
--

CREATE TABLE `balance_deducted` (
  `id` int(11) NOT NULL,
  `amount_sd` varchar(255) DEFAULT NULL,
  `receipt_no` varchar(255) DEFAULT NULL,
  `sd_code` varchar(255) DEFAULT NULL,
  `owner_id` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `void` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balance_deducted`
--

INSERT INTO `balance_deducted` (`id`, `amount_sd`, `receipt_no`, `sd_code`, `owner_id`, `admin_id`, `void`, `created_at`) VALUES
(1, '200', '1', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-01 08:46:29'),
(2, '50', '2', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-02 08:46:44'),
(3, '350', '3', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-03 08:47:02'),
(4, '100', '4', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-03 00:23:57'),
(5, '100', '5', 'ECSTXSD2024006', '230629406', 1, 1, '2024-04-03 00:24:11'),
(6, '200', '6', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-16 00:24:35'),
(7, '50', '7', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-18 00:24:50'),
(8, '200', '8', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-19 00:28:21'),
(9, '400', '9', 'ECSTXSD2024007', '230629440', 1, 0, '2024-04-09 07:19:55'),
(10, '500', '10', 'ECSTXSD2024007', '230629440', 1, 0, '2024-04-11 07:20:25'),
(11, '150', '123', 'ECSTXSD2024006', '230629406', 1, 0, '2024-04-14 06:04:11'),
(12, '200', '1234', 'ECSTXSD2024006', '230629406', NULL, 0, '2024-04-22 03:52:35'),
(13, '100', '2221', 'ECSTXSD2024006', '230629406', NULL, 0, '2024-04-22 03:54:13'),
(14, '30', '2314', 'ECSTXSD2024006', '230629406', NULL, 0, '2024-04-22 03:54:37'),
(15, '20', '2312', 'ECSTXSD2024006', '230629406', NULL, 1, '2024-04-22 03:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `id` int(11) NOT NULL,
  `owner_id` varchar(255) DEFAULT NULL,
  `coupon_id` varchar(255) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `claim_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `claim_end_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `coupon_value` int(11) DEFAULT NULL,
  `owner_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `coupon_code`, `coupon_value`, `owner_id`, `created_at`, `updated_at`) VALUES
(2, 'ECXFS2024003', 60, '22112303', '2024-04-22 13:17:42', NULL),
(5, 'ECSTXFS2024', 60, '230629406', '2024-04-24 11:47:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `department_prefix` varchar(255) DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `department_name`, `department_prefix`, `from_time`, `to_time`) VALUES
(1, 'MAT\'S DONUT', 'MATSFNB', '03:00:00', '02:00:00'),
(2, 'EC CAFE', 'ECCFFS', '09:00:00', '23:59:59'),
(3, 'EC SOLUTIONS', 'ECSFS', '09:00:00', '23:59:59'),
(4, 'ECSTATICX', 'ECSTXFS', '09:00:00', '23:59:59'),
(5, 'ESKINA', 'FNBFS', '05:00:00', '23:59:59'),
(6, 'ECXPERIENCE', 'ECXFS', '21:00:00', '04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `staff_id` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `base_time` int(11) DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `owner_email` varchar(255) NOT NULL,
  `owner_department` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `staff_id`, `owner_name`, `base_time`, `from_time`, `to_time`, `owner_email`, `owner_department`) VALUES
(1, '22112303', 'ABIGAIL LAXAMANA', 1, '09:18:00', '21:18:00', '', 6),
(2, '230629406', 'Jerome De Lara', 1, NULL, NULL, '', 4);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'Clerk'),
(2, 'Admin'),
(3, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `salary_deduction`
--

CREATE TABLE `salary_deduction` (
  `id` int(11) NOT NULL,
  `sd_code` varchar(255) NOT NULL,
  `sd_credits` varchar(255) DEFAULT NULL,
  `owner_id` varchar(255) DEFAULT NULL,
  `first_cut_start` int(11) DEFAULT NULL,
  `first_cut_end` int(11) DEFAULT NULL,
  `second_cut_start` int(11) DEFAULT NULL,
  `second_cut_end` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_email` (`email`),
  ADD KEY `admin_role` (`role_id`);

--
-- Indexes for table `balance_deducted`
--
ALTER TABLE `balance_deducted`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_code_id` (`coupon_code`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_staff_Id` (`staff_id`),
  ADD KEY `owner_dep` (`owner_department`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_deduction`
--
ALTER TABLE `salary_deduction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_owner_id` (`owner_id`) USING BTREE,
  ADD KEY `uniq_sd_code` (`sd_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `balance_deducted`
--
ALTER TABLE `balance_deducted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `salary_deduction`
--
ALTER TABLE `salary_deduction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admin_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `fr_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`coupon_code`),
  ADD CONSTRAINT `fr_owner_id` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`staff_id`);

--
-- Constraints for table `owners`
--
ALTER TABLE `owners`
  ADD CONSTRAINT `owner_dep` FOREIGN KEY (`owner_department`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
