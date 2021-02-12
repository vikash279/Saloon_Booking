-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.28.12
-- Generation Time: Jun 09, 2020 at 12:12 AM
-- Server version: 5.5.8
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing`
--
CREATE DATABASE IF NOT EXISTS `billing` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `billing`;

-- --------------------------------------------------------

--
-- Table structure for table `ps_payments`
--

CREATE TABLE `ps_payments` (
  `payid` int(11) NOT NULL,
  `psid` int(11) NOT NULL,
  `txnid` bigint(20) NOT NULL,
  `mobile` varchar(9) NOT NULL,
  `amount` double NOT NULL,
  `balance_user` double NOT NULL,
  `balance_ps` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `ip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ps_transactions`
--

CREATE TABLE `ps_transactions` (
  `txnid` int(11) NOT NULL,
  `psid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` double NOT NULL,
  `balance` double NOT NULL,
  `docnum` varchar(50) DEFAULT NULL,
  `remarks` varchar(250) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ps_users`
--

CREATE TABLE `ps_users` (
  `psid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `apikey` varchar(100) NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `overdraft` double NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ps_user_ips`
--

CREATE TABLE `ps_user_ips` (
  `id` int(11) NOT NULL,
  `psid` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '0:Deveoper, 1:Super-Admin, 2:Admin, 3:User',
  `phone_flag` int(11) DEFAULT '0' COMMENT '0: No, 1: Yes',
  `email_flag` int(11) DEFAULT '0' COMMENT '0: No, 1: Yes',
  `status` tinyint(4) DEFAULT NULL COMMENT '0:In-Active, 1:Active',
  `reset_password_token` longtext COLLATE utf8mb4_unicode_ci,
  `reset_password_sent_at` datetime DEFAULT NULL,
  `unlock_token` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ps_payments`
--
ALTER TABLE `ps_payments`
  ADD PRIMARY KEY (`payid`);

--
-- Indexes for table `ps_transactions`
--
ALTER TABLE `ps_transactions`
  ADD PRIMARY KEY (`txnid`);

--
-- Indexes for table `ps_users`
--
ALTER TABLE `ps_users`
  ADD PRIMARY KEY (`psid`);

--
-- Indexes for table `ps_user_ips`
--
ALTER TABLE `ps_user_ips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ps_payments`
--
ALTER TABLE `ps_payments`
  MODIFY `payid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ps_transactions`
--
ALTER TABLE `ps_transactions`
  MODIFY `txnid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ps_users`
--
ALTER TABLE `ps_users`
  MODIFY `psid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ps_user_ips`
--
ALTER TABLE `ps_user_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
