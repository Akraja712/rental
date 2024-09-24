-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2023 at 12:38 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `color_challenge`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `refer_code` text DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `refer_code`, `role`, `status`) VALUES
(1, 'Nanthakumar', 'Nantha34@gmail.com', 'Nantha@543', 'CMDS', 'Super admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `version` int(200) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `link`, `version`, `description`) VALUES
(1, 'https://play.google.com/store/apps/details?id=com.app.colorchallenge', 6, 'https://drive.google.com/drive/folders/1aQ8nEBJSp-Zg1YQ4D6wNUPD7b9s6Vgrp?usp=share_link');

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `coins` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT 'Wait for Result-0 |\r\nYou won - 1 |\r\nLoss - 2',
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`id`, `user_id`, `color_id`, `coins`, `status`, `datetime`) VALUES
(14, 17, 1, '10.00', 1, '2023-02-28 14:44:06'),
(15, 17, 5, '20.00', 2, '2023-02-28 14:44:53'),
(16, 17, 2, '10.00', 2, '2023-02-28 14:49:35'),
(17, 17, 4, '10.00', 2, '2023-02-28 14:49:50'),
(21, 25, 1, '2.00', 2, '2023-03-01 23:45:52'),
(22, 25, 2, '10.00', 1, '2023-03-01 23:51:42'),
(23, 25, 3, '8.00', 2, '2023-03-01 23:51:52'),
(24, 25, 4, '10.00', 2, '2023-03-01 23:51:58'),
(25, 25, 5, '10.00', 2, '2023-03-01 23:52:02'),
(26, 25, 6, '10.00', 2, '2023-03-01 23:52:06'),
(27, 20, 6, '10.00', 1, '2023-03-02 10:06:38'),
(28, 28, 3, '5.00', 2, '2023-03-02 13:28:42'),
(29, 28, 1, '5.00', 2, '2023-03-02 13:30:52'),
(30, 28, 2, '5.00', 2, '2023-03-02 13:30:55'),
(31, 28, 5, '5.00', 2, '2023-03-02 13:30:56'),
(32, 28, 4, '5.00', 2, '2023-03-02 13:31:00'),
(33, 28, 6, '5.00', 1, '2023-03-02 13:31:08'),
(34, 28, 6, '5.00', 1, '2023-03-02 13:31:16'),
(35, 28, 5, '5.00', 2, '2023-03-02 13:33:52'),
(36, 28, 2, '5.00', 2, '2023-03-02 13:33:54'),
(37, 28, 1, '5.00', 2, '2023-03-02 13:33:59'),
(38, 18, 5, '5.00', 0, '2023-03-07 10:29:03'),
(39, 18, 6, '6.00', 0, '2023-03-07 10:29:14'),
(40, 18, 2, '3.00', 0, '2023-03-07 10:30:27'),
(41, 18, 3, '1.00', 0, '2023-03-07 10:30:45'),
(42, 29, 3, '10.00', 0, '2023-03-07 14:18:53'),
(43, 30, 6, '10.00', 0, '2023-03-07 14:34:01'),
(44, 39, 4, '10.00', 0, '2023-03-07 15:18:07'),
(45, 39, 5, '5.00', 0, '2023-03-07 16:18:02'),
(46, 39, 6, '1.00', 0, '2023-03-07 16:19:18'),
(47, 39, 6, '1.00', 0, '2023-03-07 16:19:44'),
(48, 39, 5, '1.00', 0, '2023-03-07 16:40:24'),
(49, 39, 6, '1.00', 0, '2023-03-07 16:41:01'),
(50, 40, 2, '2.00', 1, '2023-03-08 09:59:00'),
(51, 40, 5, '2.00', 2, '2023-03-08 10:13:01'),
(52, 40, 6, '1.00', 2, '2023-03-08 13:31:44'),
(53, 39, 6, '1.00', 2, '2023-03-08 13:34:58'),
(54, 41, 1, '5.00', 0, '2023-03-09 13:14:41'),
(55, 41, 2, '10.00', 0, '2023-03-09 13:16:03'),
(56, 41, 4, '8.00', 1, '2023-03-09 16:10:28'),
(57, 37, 5, '1.00', 0, '2023-03-09 17:00:00'),
(58, 8, 3, '2.00', 0, '2023-03-09 18:00:00'),
(59, 8, 6, '2.00', 0, '2023-03-09 18:00:00'),
(60, 1, 5, '4.00', 0, '2023-03-09 19:00:00'),
(61, 2, 2, '50.00', 0, '2023-03-09 20:00:00'),
(62, 4, 1, '10.00', 0, '2023-03-09 21:00:00'),
(63, 4, 2, '30.00', 0, '2023-03-09 21:00:00'),
(64, 4, 5, '10.00', 0, '2023-03-09 21:00:00'),
(65, 8, 5, '10.00', 1, '2023-03-09 22:00:00'),
(66, 8, 1, '5.00', 2, '2023-03-09 22:00:00'),
(67, 8, 5, '12.00', 1, '2023-03-09 22:00:00'),
(68, 8, 5, '1.00', 1, '2023-03-09 22:00:00'),
(69, 8, 5, '12.00', 1, '2023-03-09 22:00:00'),
(70, 1, 1, '50.00', 1, '2023-03-09 23:00:00'),
(71, 1, 2, '50.00', 2, '2023-03-09 23:00:00'),
(72, 8, 2, '1.00', 1, '2023-03-10 09:00:00'),
(73, 1, 1, '5.00', 2, '2023-03-10 11:00:00'),
(74, 1, 2, '40.00', 1, '2023-03-10 11:00:00'),
(75, 8, 5, '2.00', 2, '2023-03-10 11:00:00'),
(76, 1, 2, '5.00', 1, '2023-03-10 12:00:00'),
(77, 8, 2, '1.00', 1, '2023-03-10 12:00:00'),
(78, 8, 5, '1.00', 2, '2023-03-10 12:00:00'),
(79, 8, 1, '1.00', 2, '2023-03-10 12:00:00'),
(80, 8, 2, '1.00', 1, '2023-03-10 12:00:00'),
(81, 8, 5, '1.00', 2, '2023-03-10 15:00:00'),
(82, 8, 2, '1.00', 2, '2023-03-10 15:00:00'),
(83, 8, 1, '1.00', 1, '2023-03-10 15:00:00'),
(84, 24, 1, '20.00', 1, '2023-03-10 15:00:00'),
(85, 24, 2, '10.00', 2, '2023-03-10 15:00:00'),
(86, 29, 2, '25.00', 1, '2023-03-10 17:00:00'),
(87, 30, 1, '50.00', 1, '2023-03-10 23:00:00'),
(88, 24, 5, '5.00', 2, '2023-03-11 01:00:00'),
(89, 24, 2, '5.00', 1, '2023-03-11 01:00:00'),
(90, 24, 1, '5.00', 2, '2023-03-11 01:00:00'),
(91, 31, 2, '10.00', 1, '2023-03-11 01:00:00'),
(92, 32, 5, '1.00', 2, '2023-03-11 02:00:00'),
(93, 32, 1, '2.00', 1, '2023-03-11 02:00:00'),
(94, 33, 2, '20.00', 2, '2023-03-11 08:00:00'),
(95, 33, 5, '30.00', 1, '2023-03-11 08:00:00'),
(96, 24, 2, '5.00', 1, '2023-03-11 09:00:00'),
(97, 32, 1, '10.00', 1, '2023-03-11 10:00:00'),
(98, 32, 2, '10.00', 1, '2023-03-11 11:00:00'),
(99, 34, 2, '20.00', 2, '2023-03-11 12:00:00'),
(100, 34, 5, '10.00', 1, '2023-03-11 12:00:00'),
(101, 34, 1, '20.00', 2, '2023-03-11 12:00:00'),
(102, 35, 2, '20.00', 2, '2023-03-11 12:00:00'),
(103, 35, 5, '10.00', 1, '2023-03-11 12:00:00'),
(104, 35, 1, '20.00', 2, '2023-03-11 12:00:00'),
(105, 32, 5, '10.00', 1, '2023-03-11 12:00:00'),
(106, 34, 2, '30.00', 2, '2023-03-11 13:00:00'),
(107, 34, 1, '20.00', 2, '2023-03-11 13:00:00'),
(108, 32, 5, '17.00', 1, '2023-03-11 13:00:00'),
(109, 31, 1, '20.00', 2, '2023-03-11 13:00:00'),
(110, 36, 2, '10.00', 2, '2023-03-11 14:00:00'),
(111, 36, 5, '10.00', 1, '2023-03-11 14:00:00'),
(112, 36, 1, '10.00', 2, '2023-03-11 14:00:00'),
(113, 24, 1, '10.00', 2, '2023-03-11 14:00:00'),
(114, 24, 1, '10.00', 2, '2023-03-11 14:00:00'),
(115, 37, 1, '10.00', 2, '2023-03-11 14:00:00'),
(116, 37, 2, '10.00', 2, '2023-03-11 14:00:00'),
(117, 37, 5, '10.00', 1, '2023-03-11 14:00:00'),
(118, 24, 2, '10.00', 2, '2023-03-11 14:00:00'),
(119, 24, 1, '20.00', 1, '2023-03-11 15:00:00'),
(120, 31, 2, '10.00', 1, '2023-03-11 17:00:00'),
(121, 29, 5, '25.00', 1, '2023-03-12 00:00:00'),
(122, 40, 5, '50.00', 2, '2023-03-12 11:00:00'),
(123, 32, 1, '10.00', 1, '2023-03-12 11:00:00'),
(124, 32, 2, '12.00', 2, '2023-03-12 11:00:00'),
(125, 32, 5, '10.00', 2, '2023-03-12 11:00:00'),
(126, 32, 5, '5.00', 1, '2023-03-12 14:00:00'),
(127, 32, 2, '13.00', 2, '2023-03-12 14:00:00'),
(128, 31, 1, '10.00', 1, '2023-03-13 19:00:00'),
(129, 42, 1, '50.00', 2, '2023-03-13 21:00:00'),
(130, 41, 1, '10.00', 2, '2023-03-13 21:00:00'),
(131, 43, 2, '20.00', 2, '2023-03-13 21:00:00'),
(132, 43, 1, '20.00', 2, '2023-03-13 21:00:00'),
(133, 43, 5, '10.00', 1, '2023-03-13 21:00:00'),
(134, 44, 1, '5.00', 2, '2023-03-13 21:00:00'),
(135, 44, 2, '5.00', 2, '2023-03-13 21:00:00'),
(136, 44, 5, '5.00', 1, '2023-03-13 21:00:00'),
(137, 45, 2, '50.00', 2, '2023-03-13 21:00:00'),
(138, 46, 1, '5.00', 2, '2023-03-13 21:00:00'),
(139, 46, 5, '5.00', 1, '2023-03-13 21:00:00'),
(140, 46, 2, '5.00', 2, '2023-03-13 21:00:00'),
(141, 47, 5, '10.00', 1, '2023-03-13 21:00:00'),
(142, 49, 1, '30.00', 2, '2023-03-13 21:00:00'),
(143, 49, 2, '10.00', 2, '2023-03-13 21:00:00'),
(144, 49, 5, '10.00', 1, '2023-03-13 21:00:00'),
(145, 50, 1, '10.00', 2, '2023-03-13 21:00:00'),
(146, 50, 5, '20.00', 1, '2023-03-13 21:00:00'),
(147, 52, 1, '10.00', 2, '2023-03-13 21:00:00'),
(148, 53, 2, '1.00', 2, '2023-03-13 21:00:00'),
(149, 53, 5, '1.00', 1, '2023-03-13 21:00:00'),
(150, 53, 1, '1.00', 2, '2023-03-13 21:00:00'),
(151, 54, 2, '20.00', 2, '2023-03-13 21:00:00'),
(152, 47, 2, '20.00', 2, '2023-03-13 22:00:00'),
(153, 44, 2, '10.00', 2, '2023-03-13 22:00:00'),
(154, 44, 5, '5.00', 2, '2023-03-13 22:00:00'),
(155, 55, 2, '30.00', 2, '2023-03-13 22:00:00'),
(156, 55, 1, '20.00', 1, '2023-03-13 22:00:00'),
(157, 48, 5, '10.00', 2, '2023-03-13 22:00:00'),
(158, 53, 1, '5.00', 1, '2023-03-13 22:00:00'),
(159, 53, 2, '5.00', 2, '2023-03-13 22:00:00'),
(160, 56, 2, '15.00', 2, '2023-03-13 22:00:00'),
(161, 52, 5, '10.00', 2, '2023-03-13 22:00:00'),
(162, 41, 5, '40.00', 2, '2023-03-13 22:00:00'),
(163, 46, 2, '2.00', 2, '2023-03-13 22:00:00'),
(164, 57, 1, '50.00', 1, '2023-03-13 22:00:00'),
(165, 50, 5, '20.00', 2, '2023-03-13 22:00:00'),
(166, 58, 5, '50.00', 2, '2023-03-13 23:00:00'),
(167, 59, 1, '10.00', 2, '2023-03-13 23:00:00'),
(168, 52, 1, '10.00', 2, '2023-03-13 23:00:00'),
(169, 60, 5, '10.00', 2, '2023-03-13 23:00:00'),
(170, 60, 1, '5.00', 2, '2023-03-13 23:00:00'),
(171, 61, 1, '10.00', 2, '2023-03-13 23:00:00'),
(172, 61, 2, '10.00', 1, '2023-03-13 23:00:00'),
(173, 61, 5, '10.00', 2, '2023-03-13 23:00:00'),
(174, 62, 5, '50.00', 2, '2023-03-13 23:00:00'),
(175, 64, 2, '10.00', 1, '2023-03-14 00:00:00'),
(176, 64, 1, '18.00', 2, '2023-03-14 00:00:00'),
(177, 64, 5, '10.00', 2, '2023-03-14 00:00:00'),
(178, 64, 2, '10.00', 1, '2023-03-14 01:00:00'),
(179, 65, 1, '5.00', 2, '2023-03-14 01:00:00'),
(180, 65, 2, '5.00', 1, '2023-03-14 01:00:00'),
(181, 65, 5, '5.00', 2, '2023-03-14 01:00:00'),
(182, 65, 5, '15.00', 2, '2023-03-14 01:00:00'),
(183, 65, 1, '15.00', 2, '2023-03-14 01:00:00'),
(184, 65, 2, '5.00', 1, '2023-03-14 01:00:00'),
(185, 66, 2, '10.00', 1, '2023-03-14 01:00:00'),
(186, 66, 5, '20.00', 2, '2023-03-14 01:00:00'),
(187, 66, 1, '20.00', 2, '2023-03-14 01:00:00'),
(188, 52, 2, '10.00', 1, '2023-03-14 02:00:00'),
(189, 67, 5, '25.00', 2, '2023-03-14 02:00:00'),
(190, 29, 1, '50.00', 1, '2023-03-14 03:00:00'),
(191, 68, 2, '50.00', 1, '2023-03-14 04:00:00'),
(192, 69, 5, '50.00', 1, '2023-03-14 07:00:00'),
(193, 70, 1, '1.00', 1, '2023-03-14 08:00:00'),
(194, 70, 2, '1.00', 2, '2023-03-14 08:00:00'),
(195, 70, 5, '1.00', 2, '2023-03-14 08:00:00'),
(196, 53, 5, '5.00', 2, '2023-03-14 08:00:00'),
(197, 71, 2, '20.00', 2, '2023-03-14 08:00:00'),
(198, 56, 5, '5.00', 2, '2023-03-14 08:00:00'),
(199, 72, 1, '6.00', 1, '2023-03-14 08:00:00'),
(200, 73, 2, '10.00', 2, '2023-03-14 09:00:00'),
(201, 73, 5, '10.00', 1, '2023-03-14 09:00:00'),
(202, 73, 1, '10.00', 2, '2023-03-14 09:00:00'),
(203, 70, 2, '2.00', 2, '2023-03-14 09:00:00'),
(204, 70, 5, '2.00', 1, '2023-03-14 09:00:00'),
(205, 70, 1, '2.00', 2, '2023-03-14 09:00:00'),
(206, 70, 5, '1.00', 1, '2023-03-14 09:00:00'),
(207, 44, 1, '5.00', 2, '2023-03-14 09:00:00'),
(208, 71, 2, '10.00', 2, '2023-03-14 09:00:00'),
(209, 71, 2, '10.00', 2, '2023-03-14 10:00:00'),
(210, 75, 2, '20.00', 2, '2023-03-14 10:00:00'),
(211, 75, 5, '10.00', 1, '2023-03-14 10:00:00'),
(212, 75, 1, '10.00', 2, '2023-03-14 10:00:00'),
(213, 75, 1, '10.00', 2, '2023-03-14 10:00:00'),
(214, 78, 2, '50.00', 2, '2023-03-14 10:00:00'),
(215, 71, 2, '5.00', 0, '2023-03-14 11:00:00'),
(216, 71, 5, '5.00', 0, '2023-03-14 11:00:00'),
(217, 53, 1, '10.00', 0, '2023-03-14 11:00:00'),
(218, 79, 2, '25.00', 0, '2023-03-14 11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`) VALUES
(1, 'Red', '#FF0000'),
(2, 'Blue', '#0000FF'),
(5, 'Green', '#008000');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `color_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `color_id`, `datetime`) VALUES
(1, 1, '2023-02-01 00:00:00'),
(2, 1, '2023-02-28 00:00:00'),
(3, 1, '2023-03-01 00:00:00'),
(4, 2, '2023-03-01 00:00:00'),
(5, 6, '2023-03-02 00:00:00'),
(6, 4, '2023-03-09 16:00:00'),
(7, 5, '2023-03-09 22:00:00'),
(8, 1, '2023-03-09 23:00:00'),
(9, 5, '2023-03-10 00:00:00'),
(10, 5, '2023-03-10 01:00:00'),
(11, 2, '2023-03-10 02:00:00'),
(12, 6, '2023-03-10 03:00:00'),
(13, 1, '2023-03-10 04:00:00'),
(14, 2, '2023-03-10 05:00:00'),
(15, 5, '2023-03-10 06:00:00'),
(16, 2, '2023-03-10 07:00:00'),
(17, 5, '2023-03-10 08:00:00'),
(18, 2, '2023-03-10 09:00:00'),
(19, 6, '2023-03-10 10:00:00'),
(20, 2, '2023-03-10 11:00:00'),
(21, 2, '2023-03-10 12:00:00'),
(22, 5, '2023-03-10 13:00:00'),
(23, 5, '2023-03-10 14:00:00'),
(24, 1, '2023-03-10 15:00:00'),
(25, 4, '2023-03-10 16:00:00'),
(26, 2, '2023-03-10 17:00:00'),
(27, 2, '2023-03-10 18:00:00'),
(28, 5, '2023-03-10 19:00:00'),
(29, 3, '2023-03-10 20:00:00'),
(30, 1, '2023-03-10 21:00:00'),
(31, 2, '2023-03-10 22:00:00'),
(32, 1, '2023-03-10 23:00:00'),
(33, 1, '2023-03-11 00:00:00'),
(34, 2, '2023-03-11 01:00:00'),
(35, 1, '2023-03-11 02:00:00'),
(36, 1, '2023-03-11 03:00:00'),
(37, 2, '2023-03-11 04:00:00'),
(38, 1, '2023-03-11 05:00:00'),
(39, 5, '2023-03-11 06:00:00'),
(40, 5, '2023-03-11 07:00:00'),
(41, 5, '2023-03-11 08:00:00'),
(42, 2, '2023-03-11 09:00:00'),
(43, 1, '2023-03-11 10:00:00'),
(44, 2, '2023-03-11 11:00:00'),
(45, 5, '2023-03-11 12:00:00'),
(46, 5, '2023-03-11 13:00:00'),
(47, 5, '2023-03-11 14:00:00'),
(48, 1, '2023-03-11 15:00:00'),
(49, 5, '2023-03-11 16:00:00'),
(50, 2, '2023-03-11 17:00:00'),
(51, 1, '2023-03-11 18:00:00'),
(52, 5, '2023-03-11 19:00:00'),
(53, 1, '2023-03-11 20:00:00'),
(54, 5, '2023-03-11 21:00:00'),
(55, 2, '2023-03-11 22:00:00'),
(56, 1, '2023-03-11 23:00:00'),
(57, 5, '2023-03-12 00:00:00'),
(58, 2, '2023-03-12 01:00:00'),
(59, 5, '2023-03-12 02:00:00'),
(60, 2, '2023-03-12 03:00:00'),
(61, 2, '2023-03-12 04:00:00'),
(62, 6, '2023-03-12 05:00:00'),
(63, 5, '2023-03-12 06:00:00'),
(64, 6, '2023-03-12 07:00:00'),
(65, 4, '2023-03-12 08:00:00'),
(66, 4, '2023-03-12 09:00:00'),
(67, 2, '2023-03-12 10:00:00'),
(68, 1, '2023-03-12 11:00:00'),
(69, 6, '2023-03-12 12:00:00'),
(70, 5, '2023-03-12 13:00:00'),
(71, 5, '2023-03-12 14:00:00'),
(72, 2, '2023-03-12 15:00:00'),
(73, 1, '2023-03-12 16:00:00'),
(74, 2, '2023-03-12 17:00:00'),
(75, 5, '2023-03-12 18:00:00'),
(76, 2, '2023-03-12 19:00:00'),
(77, 3, '2023-03-12 20:00:00'),
(78, 2, '2023-03-12 21:00:00'),
(79, 5, '2023-03-12 22:00:00'),
(80, 2, '2023-03-12 23:00:00'),
(81, 6, '2023-03-13 00:00:00'),
(82, 5, '2023-03-13 01:00:00'),
(83, 2, '2023-03-13 02:00:00'),
(84, 2, '2023-03-13 03:00:00'),
(85, 5, '2023-03-13 04:00:00'),
(86, 1, '2023-03-13 05:00:00'),
(87, 2, '2023-03-13 06:00:00'),
(88, 6, '2023-03-13 07:00:00'),
(89, 2, '2023-03-13 08:00:00'),
(90, 4, '2023-03-13 09:00:00'),
(91, 5, '2023-03-13 10:00:00'),
(92, 1, '2023-03-13 11:00:00'),
(93, 2, '2023-03-13 12:00:00'),
(94, 5, '2023-03-13 13:00:00'),
(95, 5, '2023-03-13 14:00:00'),
(96, 2, '2023-03-13 15:00:00'),
(97, 2, '2023-03-13 16:00:00'),
(98, 3, '2023-03-13 17:00:00'),
(99, 5, '2023-03-13 18:00:00'),
(100, 1, '2023-03-13 19:00:00'),
(101, 5, '2023-03-13 20:00:00'),
(102, 5, '2023-03-13 21:00:00'),
(103, 1, '2023-03-13 22:00:00'),
(104, 2, '2023-03-13 23:00:00'),
(105, 2, '2023-03-14 00:00:00'),
(106, 2, '2023-03-14 01:00:00'),
(107, 2, '2023-03-14 02:00:00'),
(108, 1, '2023-03-14 03:00:00'),
(109, 2, '2023-03-14 04:00:00'),
(110, 2, '2023-03-14 05:00:00'),
(111, 1, '2023-03-14 06:00:00'),
(112, 5, '2023-03-14 07:00:00'),
(113, 1, '2023-03-14 08:00:00'),
(114, 5, '2023-03-14 09:00:00'),
(115, 5, '2023-03-14 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE `rules` (
  `id` int(11) NOT NULL,
  `ruleId` int(11) DEFAULT NULL,
  `sender` text DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `ruleId`, `sender`, `message`) VALUES
(1, 31, 'Divakar', 'Helllo Divakar');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `register_coins` decimal(10,2) DEFAULT 0.00,
  `refer_coins` int(50) DEFAULT 0,
  `withdrawal_status` tinyint(4) DEFAULT 0,
  `challenge_status` tinyint(4) DEFAULT 0,
  `upi` varchar(255) DEFAULT NULL,
  `contact_us` varchar(255) DEFAULT NULL,
  `min_withdrawal` text DEFAULT NULL,
  `min_dp_coins` decimal(10,2) DEFAULT 0.00,
  `max_dp_coins` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `register_coins`, `refer_coins`, `withdrawal_status`, `challenge_status`, `upi`, `contact_us`, `min_withdrawal`, `min_dp_coins`, `max_dp_coins`) VALUES
(1, '50.00', 50, 1, 1, 'BHARATPE09912930379@yesbankltd', 'https://api.whatsapp.com/send?phone=917339276625', '100.00', '5.00', '100.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `upi` text DEFAULT '',
  `earn` decimal(10,2) DEFAULT 0.00,
  `coins` decimal(10,2) DEFAULT NULL,
  `device_id` text DEFAULT '',
  `balance` decimal(10,2) DEFAULT 0.00,
  `referred_by` text DEFAULT NULL,
  `refer_code` text DEFAULT NULL,
  `withdrawal_status` tinyint(4) DEFAULT 0,
  `challenge_status` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1 COMMENT 'Active-1\r\nBlocked -0',
  `joined_date` text DEFAULT NULL,
  `fcm_id` text DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `upi`, `earn`, `coins`, `device_id`, `balance`, `referred_by`, `refer_code`, `withdrawal_status`, `challenge_status`, `status`, `joined_date`, `fcm_id`, `datetime`, `last_updated`) VALUES
(2, 'jandraid0.1@gmail.com', 'prasad', '', '0.00', '0.00', '', '0.00', '66638', '49638', 0, 0, 0, '2023-03-09', 'ciHWrJ_eT4-A9pyUiosxzG:APA91bGqQH_tFtIeonzYr35tIDRyB9Cw867gTNHZmBa-tJJpcwdYOLwnzTd2X6q8HcvKNgwfDBG9tBuKvtWPz5fCEI-LWAxschizSob2qf2fGzgtX5ul7GlI5xll0WwTRkeYib8IbHCn', NULL, '2023-03-09 14:27:08'),
(3, 'jd.webapster@gmail.com', 'ayush', 'hhhh', '0.00', '50.00', '', '0.00', '66638', '24378', 0, 0, 1, '2023-03-09', 'f5S6d86fQiGSm4FyvAtt2N:APA91bHFxafIn7iBPhNBZsg4I7uHQhciDB7qUHWcnKuBbTdFnV7sD5_JsgC6WrZ7YFDOkIP3NweZqV4jiLfrO2Qc8vpfl4f_fc__PFdbY4BbKxqAD0TKEfag_dm4UExsd0b4Tea_Urqq', NULL, '2023-03-09 14:33:17'),
(4, 'nandha24.2000@gmail.com', 'Nandha ', '', '0.00', '50.00', '', '0.00', '', '95944', 0, 0, 1, '2023-03-09', 'd5EjdVTlRXKFkRinJDFmVr:APA91bHsB3_Yl13SdfVonUL392dplPulTKj6rCui8ULra1dCaKD77yfTIiDP2905SMLytySfJ2zX20-VQjLXiwjwGefRJoAbIuTHrfulk3ZRWGGZSf799_zvn9x0os-6zyqffwytY81Y', NULL, '2023-03-09 14:38:13'),
(5, 'c303443341@gmail.com', 'test', '', '0.00', '50.00', '', '0.00', 'test', '87056', 0, 0, 0, '2023-03-09', 'etncS56JQmGjLG_v4mLKV_:APA91bFk5ql5wi23u3qni6Ltun4QlsGfs9y-2NDH7bIMEBJt1CdaRfOBJaWW21iNAD5ms0q-puXU5Im1abVdkf6nfIplk5rfn9-BK06Vhp55ckwz3r3uVks7zcyru14Ny-VtRokXySQx', NULL, '2023-03-09 15:48:02'),
(7, 'arunmscit2021@gmail.com', 'arun', '', '0.00', '50.00', '', '0.00', 'test', '47883', 0, 0, 1, '2023-03-09', 'etncS56JQmGjLG_v4mLKV_:APA91bFk5ql5wi23u3qni6Ltun4QlsGfs9y-2NDH7bIMEBJt1CdaRfOBJaWW21iNAD5ms0q-puXU5Im1abVdkf6nfIplk5rfn9-BK06Vhp55ckwz3r3uVks7zcyru14Ny-VtRokXySQx', NULL, '2023-03-09 15:54:25'),
(8, 'arunkumar1561998@gmail.com', 'ajay', 'test@gmail.com', '0.00', '0.00', '', '78.00', 'test', '56342', 0, 0, 1, '2023-03-09', 'doDDozPKRLmHeYQmA3MqE1:APA91bFhHiilxYblB-Wfb0eZsSOPVKdkQD7jeCCBqqMzztgQvI1LBvZIHAinqg-rwIPLo38DEiWyBQPZYdjDkJjAyhl-W16wi_B_NjUSzD8fLtsmJdLJRwEqubcIo9D9tH54xpdHuDzK', NULL, '2023-03-09 15:56:48'),
(9, 'leroyburgess.75394@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '26853', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:40:08'),
(10, 'katrinaflores.73357@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '83100', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:40:12'),
(11, 'tonigraham.78278@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '75059', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:40:41'),
(12, 'rosareid.02317@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '40146', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:04'),
(13, 'kerryschneider.39209@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '41709', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:05'),
(14, 'everettsilva.20438@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', 'text', '53892', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:08'),
(15, 'veronicajensen.36138@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '73550', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:22'),
(16, 'triciamarsh.35175@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '60682', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:25'),
(17, 'jorgerose.87727@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '26308', 0, 0, 1, '2023-03-09', NULL, NULL, '2023-03-09 16:41:58'),
(18, 'leostevens.66561@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', 'text', '52622', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:48:14'),
(19, 'alexandermayo.96733@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '10857', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:49:06'),
(20, 'bryantbarrett.60557@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '32883', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:49:06'),
(21, 'tashashaw.96447@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '44224', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:49:32'),
(22, 'vaughnwerner.54099@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '37305', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:49:51'),
(23, 'katherinemullins.40402@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', 'text', '36581', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 07:52:50'),
(24, 'venkeshcreation@gmail.com', 'venkatesh', 'venkeshcreation@okaxis', '0.00', '0.00', '', '0.00', '95944', '61223', 0, 0, 1, '2023-03-10', 'eH-u8QcYTmeNcOnGdNo7xv:APA91bEFEUEFEhp4ZlwphshYqSeWMf4VdJpuYY6sD_d-Rn_04Vj67-AAoQMqCNwPdZrzYaJTiD-Jyx_n7JfuYZOrQXyGoyf1V_Hy-uB8aZ-pG3ble9XYSiddqGJWu0o1iMRg_Dtu-qx9', NULL, '2023-03-10 09:17:03'),
(25, 'camillepratt.49680@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', 'text', '65843', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 09:17:30'),
(26, 'dellaphillips.24254@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '14861', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 09:29:41'),
(27, 'calebray.40758@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '10218', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 09:43:06'),
(28, 'kelleyjohnson.65345@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '82291', 0, 0, 1, '2023-03-10', NULL, NULL, '2023-03-10 09:57:44'),
(29, 'jayaprasad356@gmail.com', 'prasad', '', '0.00', '50.00', '', '200.00', '', '68681', 0, 0, 1, '2023-03-10', 'f5S6d86fQiGSm4FyvAtt2N:APA91bHFxafIn7iBPhNBZsg4I7uHQhciDB7qUHWcnKuBbTdFnV7sD5_JsgC6WrZ7YFDOkIP3NweZqV4jiLfrO2Qc8vpfl4f_fc__PFdbY4BbKxqAD0TKEfag_dm4UExsd0b4Tea_Urqq', '2023-03-10 10:15:49', '2023-03-10 10:15:49'),
(30, 'temporarydummy25@gmail.com', 'dummy', '', '0.00', '0.00', '', '100.00', '', '56163', 0, 0, 1, '2023-03-10', 'dl9jMD_cSSK0tyf_Hp7FnG:APA91bGrHrJKMCbC2XMi7dlj6uYgkymLNljmK8xVe1QST9SEte44sunpx2EeOZjePVHCkMOoxIlNaAFtEWiRSYPOz7yqf2SlPrN8nIrNFAYj5KdWRfEC6E1Nl5XY2aLLcx_2VD3B8h6N', '2023-03-10 17:17:19', '2023-03-10 17:17:19'),
(31, 'yagnikbarot34@gmail.com', 'Yagnik ', '', '0.00', '0.00', '', '60.00', '', '44555', 0, 0, 1, '2023-03-10', 'c68_R4WpR12NEEDouuGfzE:APA91bH831DBEmk6zggE_o3F_eZ3nefMBYAkieJj85RInKyPbxv6PMDht_aO8dQRQvLfa_HKwtuFjuraiSYqrrwh5i4b0r9eD7jBuFqHa-m499W299-g7y-h5jsnr8qdKcu2bwZXwVQ6', '2023-03-10 19:28:39', '2023-03-10 19:28:39'),
(32, 'onlyfortrying23@gmail.com', 'APT', 'abdulc59@fbl', '0.00', '0.00', '', '18.00', '', '99973', 0, 0, 1, '2023-03-10', 'eeplKAhQRbqijhFaQcGurQ:APA91bHLZBV9_BO5IcUy1yYj_jLDFP1uieVg9X4bFKoWb9zJvmySqtRiiOxgIdWL59QhMJlN3Yb8Jj2JdHeSdFUMTB1mrzps36CU5pw04FkkDausK3qDVBzVR7bYRziXy5fQVP6gULu_', '2023-03-10 19:57:21', '2023-03-10 19:57:21'),
(33, 'ramkumarlingam141523@gmail.com', 'Ramkumar', '', '0.00', '50.00', '', '60.00', '', '20708', 0, 0, 1, '2023-03-11', 'c_EEo90zSKajHNgnLbA87O:APA91bE8W6Mv5sYvGTPQX4RJPC3SSep2gJiIJApdpn2wLct0s5sBBgsXX70Oe439QF8kRgzY4AMzSJg8CbS3PIeorn7ciCnFBliq4BnMcua8l9F2F4cxwtmofnc4wBgZmbCQv-RLtIsR', '2023-03-11 02:09:12', '2023-03-11 02:09:12'),
(34, 'srih2134@gmail.com', 'Sri Hari ', '', '0.00', '0.00', '', '20.00', '', '52899', 0, 0, 1, '2023-03-11', 'f_8ohL02SzObYnuG8jbfrL:APA91bEYLsbVwfdBRIdqJS9qp9ZSPMG5xRX3J0BvCLQ1d_8AKY04UtNPYKbl2lwC2q48pasvSPECzgUfqnmj36ewJ8p-DhVQKYp4DYkPt1IS7PQUGw1mdnS-TaeAnK2a8feeeZSXSac3', '2023-03-11 05:52:02', '2023-03-11 05:52:02'),
(35, 'nareshnalan.com@gmail.com', 'Nareshkumar ', '', '0.00', '0.00', '', '20.00', '52899', '31340', 0, 0, 1, '2023-03-11', 'ehjO3LCvTl6HNs1jcxw_Sh:APA91bGqOOdaS-3pGLeAcSQBxCuEglHHCyOaf5Z4bv0_xLLcinJx1xfUYP_b42VHpxh4vVIL5CYOKtA8n6ncJONsSxWsqxSmce_pxvCRAy9PDzOOhzOFYGmcw4C8nGusTH96ouzQKaC3', '2023-03-11 05:56:03', '2023-03-11 05:56:03'),
(36, 'tamil632000@gmail.com', 'Tamil', '', '0.00', '20.00', '', '20.00', '', '81467', 0, 0, 1, '2023-03-11', 'fb4pE0z_TGC1-upv-IR_Eb:APA91bE3pN1ytZ36bCHGUf7OsxuVy935mDZZwWl0O25SHGQM8DlU5v7et-W2_2YUSbWRbdAi1LTgiI_q93-u-_hPSB_gc8NN4BA0x3p1MEeLvhLHT-VndgM_yrogd7LNV7wE1K0v2yHJ', '2023-03-11 07:36:08', '2023-03-11 07:36:08'),
(37, 'mraathiyt2001@gmail.com', 'aathi', '', '0.00', '20.00', '', '20.00', '61223', '10465', 0, 0, 1, '2023-03-11', 'cPrRHoi-QLuo6S9AT6DggI:APA91bEC49H55s63rbIeIOxpZ3yDBNOF0yHz1QFL_vA8YkLINJPeUGudc4XZXGqdprDhfpdPfsIsbbEImogOxV-gdi-8GcMsqLV4pFsqu6Z_22L6QkfFoBSihOh6VJY31kanff4O6X4_', '2023-03-11 08:14:42', '2023-03-11 08:14:42'),
(38, 'daisybowers.95616@gmail.com', 'text', '', '0.00', '50.00', '', '0.00', '', '34324', 0, 0, 1, '2023-03-11', NULL, '2023-03-11 08:38:47', '2023-03-11 08:38:47'),
(39, 'aravindhash1995@gmail.com', 'Aravinth', '', '0.00', '50.00', '', '0.00', '', '49468', 0, 0, 1, '2023-03-11', 'fLV3TsVTRLKpc43M1Su70B:APA91bHqbYm8un1td-Bh1tXy0nQoHXb8FWAqcyX1gzkcvs7F5Q2viyPl9FQYkYQ62pCyxQMzkvflJEQL6SN5z9NGKuRrtsTfh1v3UP5RbfHB3GU0BARRntcROTJQaDCjeMKrNoiLIqw5', '2023-03-11 10:49:51', '2023-03-11 10:49:51'),
(40, 'samsonsleathers@gmail.com', 'AbdulAbdul', '', '0.00', '0.00', '', '0.00', '99973', '87877', 0, 0, 1, '2023-03-12', 'eeplKAhQRbqijhFaQcGurQ:APA91bHLZBV9_BO5IcUy1yYj_jLDFP1uieVg9X4bFKoWb9zJvmySqtRiiOxgIdWL59QhMJlN3Yb8Jj2JdHeSdFUMTB1mrzps36CU5pw04FkkDausK3qDVBzVR7bYRziXy5fQVP6gULu_', '2023-03-12 04:56:49', '2023-03-12 04:56:49'),
(41, 'boopathipalanisamy514@gmail.com', 'Boopathi ', '', '0.00', '0.00', '', '0.00', '', '62176', 0, 0, 1, '2023-03-13', 'f8_1AwEcT9-891hM3jrD65:APA91bHRSQWHZm9oSIHWrbsrtOh5Vqptde7DNnVnSpxqmnFw_kqbjFUhfhXRsiQkoiDhz17h6lRvd55CYGFvm3jX5zEk3aY2Hedo6YL3ThOIKBjEWBIvtn_M2BQ9k6CY0oDwWms4bE49', '2023-03-13 14:30:52', '2023-03-13 14:30:52'),
(42, 'someswarsurya@gmail.com', 'someswar', '', '0.00', '0.00', '', '0.00', '', '40971', 0, 0, 1, '2023-03-13', 'c8SfmrYxRZeX4fXD4w94zD:APA91bHtQ-9W6p9KA9xI2sG8lSL7OR_nWpE9X3pbMNFzVty-6KkxobMNeEg0VUGCRIo5LtwYBMtWOvSc8WKa-VkBywUjr-p2ATm6LxKkUIpwnB-tL50LWtn39K46lzHf8Kkyhse8s9lH', '2023-03-13 14:31:22', '2023-03-13 14:31:22'),
(43, 'niranjra80@gmail.com', 'sandeep g', '', '0.00', '0.00', '', '20.00', '', '67565', 0, 0, 1, '2023-03-13', 'cx1hZSjUTUe159ql_QdJ5W:APA91bHPo3RFsD5rlw2dNn8nZd-UBwRrQAVQQGg2escebAsXyj3eWIaQaPwfENI76ctRZNbRpomPFKqzfyAgY2jURQ3YrK-lgZfHPnNHsYILNc1W6QhHZ_TzLII9bZXTsXEUqlnc0i8a', '2023-03-13 14:32:09', '2023-03-13 14:32:09'),
(44, 'jayanthanjayanth40@gmail.com', 'jayanthan ', '', '0.00', '15.00', '', '10.00', '', '87941', 0, 0, 1, '2023-03-13', 'e_WjlgEYRNmN1oZMI-F4Un:APA91bG2mUhmVJ02lgBivmECkaGgK2LsCjW6yaqIzn1GM_UITjRvMADJC1XYQ8We_t49SIaYXj8cLIM7535NYc4_DZcsIYBIHyLaTztMSQa99ccNNcU9-WWliUwgijWnCEp9vzZ6rx-g', '2023-03-13 14:41:50', '2023-03-13 14:41:50'),
(45, 'akilrc666@gmail.com', 'akilan', 'akilrc666@gmail.com', '0.00', '0.00', '', '0.00', '613501', '39481', 0, 0, 1, '2023-03-13', 'dfsPhRGoQxe13a8DOyfFEl:APA91bFNWzfem9ArAyweKZ7eLdiojYrFokJWMShFbgoIcq8Uj-zgfBktfROuvTfQ7e7eUGX3gAz8tChzvLgZootMzuifM79iR8HfAPUH5o_FA3Eo7odw9SJpL2a7TW65HBAqwHuQOf2a', '2023-03-13 14:43:34', '2023-03-13 14:43:34'),
(46, 'ramcgod@gmail.com', 'Ram Kumar ', '', '0.00', '33.00', '', '10.00', '', '86615', 0, 0, 1, '2023-03-13', 'eD1r9HJQT96ynIdoRbcaKx:APA91bFmizJcFyBlCNHcUY5gvWmIgnzMvY5viYgIFs3SJkXyLTYmoaJuU0qadiAvuBws5Y1Ds9R5Mp6dANE5u3rYFdHDgHYF32Qef2ACfDgxROOJVgyJTx7QxCZbN96Idd7eFgsBhaKZ', '2023-03-13 14:44:20', '2023-03-13 14:44:20'),
(47, 'prathibsakthivel@gmail.com', 'Prathib PS', 'prathibharish428@okicici', '0.00', '20.00', '', '20.00', '', '56277', 0, 0, 1, '2023-03-13', 'dzzrzuUTSy-bENcbyVpy-I:APA91bFm6lMycmQ2HdBaRvJNYu2RAsiq2Eri-Ckf5xSW74Iyvj3Iy_KPZJAqtRbEd2RjYvScKpGVbY_2VjxaRt9umE-Cgg0VKuHcJcx7JlmFlMyhNj0ncJwmcqhXPfBcpL9or_ofTKUV', '2023-03-13 14:45:06', '2023-03-13 14:45:06'),
(48, 'vishnukarthi174@gmail.com', 'Vishnu kumar', '', '0.00', '40.00', '', '0.00', '', '87532', 0, 0, 1, '2023-03-13', 'dqQMN0vQSdSq0VbewopF2O:APA91bFBY92pOWL_vVLtnN4dXzpHkeCvyORd_2nusgDyIcssg8f9N1rZbfUL1qnup4n25qL3eZQo77UiwqXBW0PQxgTpsB2hkrjshIZcUssnK0K1v2vHqpxldd44uc7aYiORJYZGG3Hf', '2023-03-13 14:53:47', '2023-03-13 14:53:47'),
(49, 'reeganbabu21@gmail.com', 'Re', '', '0.00', '0.00', '', '20.00', '', '47529', 0, 0, 1, '2023-03-13', 'dYR_JOKkSiecjh1CYKBCuU:APA91bF6mJgpBk9H3V23X08vbB2EwHlYnW0ozzNutDV2ywsGj1wIVXdWBpur51tej8EX_KlAoqN-p1bBM6Fd1PkHrNo05n5VnYHVkbLQLaGUHjiAsFW8d4dOXmpOfXsvJ_SH1uWMIFZI', '2023-03-13 14:58:32', '2023-03-13 14:58:32'),
(50, 'rajam1324@gmail.com', 'Raja M', '', '0.00', '0.00', '', '40.00', '', '58593', 0, 0, 1, '2023-03-13', 'fz2f2T2YQ_CJGgASvSRty6:APA91bFWow-_b6pmUUOkQh7zjqWDvDwVgywq4LwCmg-HZXIeTp8TwpwSBu52lSBOnxB8tdYL8dT77OQl_NXZbkt0HOF_MDVclAH-SPjhbpQJg-FnjFTex_iTBABlw3ReNqad_xwHuc7J', '2023-03-13 15:02:17', '2023-03-13 15:02:17'),
(51, 'fanofthalapathyvijay@gmail.com', 'Saran', '', '0.00', '50.00', '', '0.00', '', '32648', 0, 0, 1, '2023-03-13', 'fKPyUezqRXC75WR3vWJbt4:APA91bHcDW8xrCFKNP7tOycjGa3Ra5QpfhoZS7_K2lqcoerl9b-j5gkQi6LOfNyP8Ssngw4xin3dvZHaT96pP6xloTQ18gCdqQPVJ9At56rTlXW06LeyDQvb6Ozb9gQI8SiFYKQho8jf', '2023-03-13 15:11:28', '2023-03-13 15:11:28'),
(52, 'cp980390@gmail.com', 'Pratap', '', '0.00', '10.00', '', '20.00', '', '53739', 0, 0, 1, '2023-03-13', 'd2DOhHBzSdORh_PCCRZCIX:APA91bH1eBT3upuscD9gcuzxoNdg8shhuz4oJQuckW5LZhUcdbul9ph_1uY9n7n2z35EF_lSBcE5_R-bus-oojq_3G6g9QGSZmPonq6lyzZRtIkOZ4r7prc2Y3GNJ2663a4bfh_CGJDJ', '2023-03-13 15:16:18', '2023-03-13 15:16:18'),
(53, 'asuva4042@gmail.com', 'Aziq', '', '0.00', '22.00', '', '12.00', '', '80849', 0, 0, 1, '2023-03-13', 'dX5EouxPT_CKOQ2g0HAK4J:APA91bExq93ArRkE203ONdfh1gHsKKGaTEn-GYky2mEFdAuDCPFESpPjt8Qs7KT_uxaAIXoBDXpcs7v54N0kS5KvX7g83q1x-DDOVhviR297OkjTm_RYyI4-s0bh_1w6P7qrhWGFtYx_', '2023-03-13 15:20:28', '2023-03-13 15:20:28'),
(54, 'karthick041204@gmail.com', 'D KARTHICKRAJA ', '', '0.00', '30.00', '', '0.00', '12345', '60291', 0, 0, 1, '2023-03-13', 'c5VJB4RMT2O6XFpLRyCp8q:APA91bG7Ktbqvp6LSemTLyXoBjV1DOjcZnuUBwjgnGwIlm1XmXG_Oqfrx8XKSY7mdpTdnt-hUrgIa5rWCJb586rPAsGwV6O8EiF0NEOHp6cmCZIENCX6_KJCPy1EZGwOBkrckKkoKEej', '2023-03-13 15:23:45', '2023-03-13 15:23:45'),
(55, 'killersandeepking@gmail.com', 'sandeep g', '', '0.00', '0.00', '', '40.00', '', '52169', 0, 0, 1, '2023-03-13', 'cx1hZSjUTUe159ql_QdJ5W:APA91bHPo3RFsD5rlw2dNn8nZd-UBwRrQAVQQGg2escebAsXyj3eWIaQaPwfENI76ctRZNbRpomPFKqzfyAgY2jURQ3YrK-lgZfHPnNHsYILNc1W6QhHZ_TzLII9bZXTsXEUqlnc0i8a', '2023-03-13 15:36:48', '2023-03-13 15:36:48'),
(56, 'elangocivilian@gmail.com', 'Elango', '', '0.00', '30.00', '', '0.00', '', '72081', 0, 0, 1, '2023-03-13', 'dVNM0CfpTWO0w8ucivUKDz:APA91bFc5EcG95yD5LiFLKeGSdLRyIwyF0B8M_38_rkY4GiGAyp106DTkk9yNAKBZThdf9Fya5dyI7bx90EAWyJPSwUmgOhaJMqG-0PNMld9-wFEDf_vl3Z_fXu3KtPnKIvsZuoJgmO5', '2023-03-13 15:59:03', '2023-03-13 15:59:03'),
(57, 'acquist.8@gmail.com', 'Elijah', 'acquist8@paytm', '0.00', '0.00', '', '0.00', '', '29007', 0, 0, 1, '2023-03-13', 'clZ6sUXIRfSJLvZZfbzqpw:APA91bHHtEnscNSpd2zK6KJEMID42yTqpGrpkWWiShka7pTXvADPtLq6RFUXrazY-O-HvaC9Fip2Iq5xk5uAouv76Lwa1IbgUo__KchfKGOPx9htXmA5GkY6FEX6EfdC-z8_ellRkucw', '2023-03-13 16:24:06', '2023-03-13 16:24:06'),
(58, 'kirubhakaran105@gmail.com', 'Kirubakaran S ', '', '0.00', '50.00', '', '0.00', '', '58849', 0, 0, 1, '2023-03-13', 'cGMTn5DHRn-Q_LP6RKK-go:APA91bGa8VlmOo5nG-SCu6C1xTeXjNBsbC9tKdNNuDJf1bAM_ihQPsXPFn-FZJvAHkmSW_F0cUAG00Qd3kUUMTQkBCN46jeLqEVrLap2NULT3FWYehOwcCmnbxHh7MmSPXpHwDO4Tu_x', '2023-03-13 16:35:13', '2023-03-13 16:35:13'),
(59, 'selvakumarrajendarn968@gmail.com', 'Sivaraj', '', '0.00', '40.00', '', '0.00', '', '97301', 0, 0, 1, '2023-03-13', 'eFFUmt1oQ_mOgGFIvm-AI_:APA91bFkSJwXe7RRh8U6liKDnczNXXTmZzrPyb1eZUe2ikGFgi2Lia3cQIWWe5YlLiOPPYcDc1pYWR4fAn76bLWEnrHVwLgyeWp1DSgmXdEbwkJKS0paucKTMwYF4BopM8HeD8nzBnbA', '2023-03-13 16:36:19', '2023-03-13 16:36:19'),
(60, 'gboobalan17@gmail.com', 'Boobalan', '', '0.00', '35.00', '', '0.00', '', '54804', 0, 0, 1, '2023-03-13', 'cpmlzvcqTYuDyvlEFCUrxQ:APA91bFy2vaHyi-ax-KYcL8KLJKEZo-4W2j0PWTNAoR06PMhvGz2dbc3WUlU9n92UBGNiYmCJ4om_ZpXcxC6eEX0mEUDneECLcOTaRBJzlVVLoFlqLLbBDu961GI3LCvcO_jvfrGQQLK', '2023-03-13 17:01:31', '2023-03-13 17:01:31'),
(61, 'nagaeswaran1131@gmail.com', 'Naga Eswaran', '', '0.00', '20.00', '', '20.00', '', '41847', 0, 0, 1, '2023-03-13', 'fAkiOPVSRSu4qOslachZpB:APA91bEiFkl9dWBBHrAX0G-V2bpIUrptUUdLbtAQHbmVxtUnIpBY7tGZu62ng3pE7kQ4-I_iG2IzHMxlX5LCwLw81GgRLjbsCU2kNgZOnM8DdSVv4ywflwah-UOr5d8nYJTaIHxwzMK7', '2023-03-13 17:14:33', '2023-03-13 17:14:33'),
(62, 'vkarthikeyan12mba@gmail.com', 'karthikeyan ', '', '0.00', '0.00', '', '0.00', '', '16393', 0, 0, 1, '2023-03-13', 'eRfDqHjHRVSyH_bkLSkN4l:APA91bGLWYYNQ_o3iISVATEGhHlfBvGZ_DhCe6Bmx_Z9xxYhYwA-0gSP2W9Xw8HlYyTcLbnzFTDlhEXPDf5dGUmyI_j06eYh5sF42ApMGt33LnsbU1TCgJbsjgkNCThxDgLhz-IUpzDx', '2023-03-13 17:25:26', '2023-03-13 17:25:26'),
(63, 'mk9159695@gmail.com', 'mani', '', '0.00', '50.00', '', '0.00', '20708', '97330', 0, 0, 1, '2023-03-13', 'e1FED4ZeSzCf5yWO5Byxz0:APA91bFjt-6Ku14G-BC0nJUNIDSzfDcY2ZMsqP2bNYKQEtSCfLGb6kCFnGoeyB4TgWcxd0pvI4mZux_ToX9MxRm_JbkBWv38YD4RrQ5eEr7VcSDbbN62uE6vtIzFEDO0-zOgnzQDMYK6', '2023-03-13 17:47:57', '2023-03-13 17:47:57'),
(64, 'cuteshruthiii2011@gmail.com', 'shruthi', '', '0.00', '2.00', '', '40.00', '', '75602', 0, 0, 1, '2023-03-13', 'clBhJt5dTPyg1rsmK-RKjN:APA91bErgwmtksTJT-30TxT0qEGNloqJ-wIMQ6XXb7HBa20JAbA7mBF6-kgqDgD7kMh_KsPCcSE4xdLhBQ8C_QB3f7h8F3fW9oRUMvR5uC-r_5xWgVotZkUavrfOVlkB0O8pJmZ-6YAc', '2023-03-13 18:21:23', '2023-03-13 18:21:23'),
(65, 'jeganbala45@gmail.com', 'Eren yeager', '', '0.00', '0.00', '', '20.00', '', '87731', 0, 0, 1, '2023-03-13', 'eCMCEcR6QNenL4yiaqbTs8:APA91bFWc_ILth6fzYT9iVdTrmrYl55W1JfLiEu3DnMEALeLBKe31D93L-Z2kZAx3hdqGFXo2dngyEF3FmiG54SfCmuVAkO8RYcA0lRb_Xogg6kYFGdA8wkGWw01Rgh8Q4JffsycWgRV', '2023-03-13 18:38:32', '2023-03-13 18:38:32'),
(66, 'shankarshreeram047@gmail.com', 'Shree Ram ', '', '0.00', '0.00', '', '20.00', '', '82146', 0, 0, 1, '2023-03-13', 'fg3ylDK9REym1lvuIyZaed:APA91bHDazw7A0lwTCqaB0q80eX4mOLRsp7pzVBsvVAqFMrXgIivjUSD4DHMSZZy24JG-bsN53nEFiLlTRxPl-n-61qAL3npTFES6FXHhyk2iznzyaz2xzPfa89Eo1x_okBJ5-2NOy3B', '2023-03-13 19:06:29', '2023-03-13 19:06:29'),
(67, 'mohammed8056713636@gmail.com', 'MOHAMMED JAFFERULLAH ', '', '0.00', '25.00', '', '0.00', '', '60156', 0, 0, 1, '2023-03-13', 'dBUl1NgBRFi4b9BdJUEvNR:APA91bEDTd1ewv8XxEYP1rHW9OetEVIE2CpHDWh49djr7vvonf6zJzhFfhn5wy8KR4T87E9AXVEWu0K00Oxnyq08uDmu464hJGgk6_UYwNXx-umtahIS7moB0yisxAnrwTM35ZtzpSvP', '2023-03-13 20:10:50', '2023-03-13 20:10:50'),
(68, 'emmanuel1321@gmail.com', 'Emmanuel ', 'emmanuel1321-1@okicici', '0.00', '100.00', '', '100.00', '', '64497', 0, 0, 1, '2023-03-13', 'fbwae2jQSGij2m-DyLLVqK:APA91bFxIfqcssYUHire-SrJQt1LPUTa_8KsIRn4hnyw5XHwUNMAyTA-VgHyD-jnBjv4ICFyddQUj40He_0qlJGkil9KmnfAEfDtXNuZR5OeEBmlCvMwgC7wpWwXxJDZkZt_OSYNZXQQ', '2023-03-13 22:10:29', '2023-03-13 22:10:29'),
(69, 'sarwesh0990@gmail.com', 'Sarweswaran ', 'sarwesh0990@oksbi', '0.00', '0.00', '', '0.00', '', '78075', 0, 0, 1, '2023-03-14', 'ftct7DPISMCvVvbyiTQxNw:APA91bGkw4qc9iidQiQe3eGCaJ-hlApA22Az01KCH8gOQ8gilrtWhCkpIPhVm6QrMKsanI9VnDJetzH3tD4Dcijn1dMBG7hylZBodXx6AOxB9dgKe2kA6orI1ptRx1T7xcbIgPzZP5ER', '2023-03-14 01:03:22', '2023-03-14 01:03:22'),
(70, 'ram74189mano@gmail.com', 'MANOJ', '8608411215@axl', '0.00', '40.00', '', '8.00', '625011', '98747', 0, 0, 1, '2023-03-14', 'c549unT-TaWYNaAdR0OD_m:APA91bEj9i2IP1Nd2wPs7B_frvGxw24bPFZZXBs_qL6aXietCCTjk5XeH8LcNO513bqTdWOR44acfS18evB83ElHVWQBeGXUQHpr98rRTJ_ldt4QnRqL9k6Wmi4GKr3P-emmtnB95Don', '2023-03-14 01:53:02', '2023-03-14 01:53:02'),
(71, 'kniderboy@gmail.com', 'sridhar', '', '0.00', '0.00', '', '0.00', '', '40059', 0, 0, 1, '2023-03-14', 'ewicdfEyRlK6jD_zCIc27M:APA91bE62KDiENlx5d940l7tY7LU8cIcazzPBmec5T9KeaMlertk03H8UeKOg4aChVV4-KmR_2ji1Sq7HCymMht9VurnrIbeV2FBeZ3rYa00oYFtrEpyNQEiM1Taa98MTZ_JKZRZqdy2', '2023-03-14 01:57:46', '2023-03-14 01:57:46'),
(72, 'abhimsdvj@gmail.com', 'abhishek', '', '0.00', '44.00', '', '12.00', '', '46400', 0, 0, 1, '2023-03-14', 'fAGnE0IVSNq2qsVWtPW-zJ:APA91bFtJF_iBOxnPxD_WN60Ea6DRKCuwohJ285nYmJIFv9UC7X7fIceYdNItxFuw4SoGcm_BUjnsOvuISSi5HxleV5p6zp0bzaxdXMP6zYJkUeU5Jd8P9PuN0QAFZsqHD9OczLtwMXm', '2023-03-14 02:18:46', '2023-03-14 02:18:46'),
(73, 'vickramwarrior317@gmail.com', 'Vickram', '', '0.00', '20.00', '', '20.00', '', '27550', 0, 0, 1, '2023-03-14', 'fxXZVklFS0mtZtLoPdHdXx:APA91bEPdci2U2WSXjqNW3A-EpBmWKVsVsenIcusc7H5wuBHlJ9SUAsFGnjMw3AeuzAjjAedoQWSxM89aD0HiPH3b3qrSeIKd5FpUE2yCdd4V3hIWopAY_r7VLwBkQoeWPigT9y89rTW', '2023-03-14 02:30:23', '2023-03-14 02:30:23'),
(74, 'vck86372@gmail.com', 'mr candy', '', '0.00', '200.00', '', '0.00', '', '90492', 0, 0, 1, '2023-03-14', 'e7BPnbJsTjW2FT09W5DOsx:APA91bFWx6PIE_0nNBtEelv1CxbwFVs0PZKTTIkhT5LjjrgjcZ5p5s0lSE9arKeW1kFMxpGukInlWCqSmv30r5l3M2E0C7E-aPJB3joh-5KfG42J9yDigwPUrOZEJyah0wJuVeo43NeZ', '2023-03-14 03:17:28', '2023-03-14 03:17:28'),
(75, 'lakshmipriyadas1996@gmail.com', 'lakshmi1996', '', '0.00', '0.00', '', '20.00', '90492', '17443', 0, 0, 1, '2023-03-14', 'eQmInssaTWyCQVsNxXESww:APA91bEG1y6KPnZNNyKpVydYCByFjsv1hsshfDNAXidzE37euUTNCbXlF-Yxl1s_DBJUkgVzarZwH-yzz_l_GiaQZwT-mJXonPvmIq2KtKth32rpwhWqHcop4MxcAzMzB1-cxP79mT_1', '2023-03-14 03:21:59', '2023-03-14 03:21:59'),
(76, 'vigneshwaran270401@gmail.com', 'vicky2704', '', '0.00', '50.00', '', '0.00', '90492', '87640', 0, 0, 1, '2023-03-14', 'e7BPnbJsTjW2FT09W5DOsx:APA91bFWx6PIE_0nNBtEelv1CxbwFVs0PZKTTIkhT5LjjrgjcZ5p5s0lSE9arKeW1kFMxpGukInlWCqSmv30r5l3M2E0C7E-aPJB3joh-5KfG42J9yDigwPUrOZEJyah0wJuVeo43NeZ', '2023-03-14 03:24:36', '2023-03-14 03:24:36'),
(77, 'mkumari041297@gmail.com', 'mkumari041297', '', '0.00', '50.00', '', '0.00', '90492', '30174', 0, 0, 1, '2023-03-14', 'eQmInssaTWyCQVsNxXESww:APA91bEG1y6KPnZNNyKpVydYCByFjsv1hsshfDNAXidzE37euUTNCbXlF-Yxl1s_DBJUkgVzarZwH-yzz_l_GiaQZwT-mJXonPvmIq2KtKth32rpwhWqHcop4MxcAzMzB1-cxP79mT_1', '2023-03-14 03:29:59', '2023-03-14 03:29:59'),
(78, 'logeshkesavan46@gmail.com', 'jjj', '', '0.00', '0.00', '', '0.00', '58849', '50659', 0, 0, 1, '2023-03-14', 'c3IqnuykQ5iCQWplJLHZ47:APA91bExANerdWiiJjm-Bc_NWuBW8ah-SOW30TRuFTf3iVEkqsGVLpHSauAucrZbMxC8TP56nLZHaRGNoqMEJaxBXcU-T9jCvsn151_H8JU7G6c-oWq_e6MO_G8IsJ-hrdUsFx9yhch-', '2023-03-14 04:18:45', '2023-03-14 04:18:45'),
(79, 'anonymousaj04@gmail.com', 'Vignesh k', '', '0.00', '25.00', '', '0.00', '', '82840', 0, 0, 1, '2023-03-14', 'cGNfOly2RFGZ0IALUK6ZiY:APA91bGn2w0n8a0y3mWon1WTRFcUJk-9qCnUWV2Frg-bdqz1r_FR_Ce26RZx9aiulVzwPlyJldNtOs1x1ObkUdfZ5ElJDPWOT8UrSqrtVlNuQ70heovaFhzGsUK5WV4fbNH0mWuDOJdD', '2023-03-14 04:49:58', '2023-03-14 04:49:58'),
(80, 'kathirse43@gmail.com', 'Kathiravan', '', '0.00', '100.00', 'ededddwd', '0.00', '', '33772', 0, 0, 1, '2023-03-14', NULL, '2023-03-14 12:28:44', '2023-03-14 11:28:44'),
(82, 'bqashhqj@gmail.com', 'dcdcce', '', '0.00', '50.00', 'ededddwd', '0.00', '33772', '33060', 0, 0, 1, '2023-03-14', NULL, '2023-03-14 12:36:04', '2023-03-14 11:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT 'Pending -0\r\ncompleted -1',
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `status`, `datetime`) VALUES
(1, 24, '100.00', 0, '2023-03-11 15:24:56'),
(2, 32, '110.00', 1, '2023-03-12 11:36:32'),
(3, 57, '100.00', 1, '2023-03-13 22:03:17'),
(4, 69, '100.00', 0, '2023-03-14 07:01:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `challenges`
--
ALTER TABLE `challenges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
