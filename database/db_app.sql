-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2025 at 07:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `bs_setting`
--

CREATE TABLE `bs_setting` (
  `setting_id` int(10) UNSIGNED NOT NULL,
  `directory` varchar(100) NOT NULL DEFAULT '',
  `admin_dir` varchar(70) NOT NULL,
  `system_title` varchar(100) NOT NULL DEFAULT '',
  `abrv` varchar(70) NOT NULL DEFAULT '',
  `year_developed` year(4) NOT NULL,
  `description` text NOT NULL,
  `developer` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bs_setting`
--

INSERT INTO `bs_setting` (`setting_id`, `directory`, `admin_dir`, `system_title`, `abrv`, `year_developed`, `description`, `developer`, `website`) VALUES
(1001, 'app', 'app', 'APP Sample', 'AS', '2025', '', 'Trident Technology', 'www.tridentechnology.com');

-- --------------------------------------------------------

--
-- Table structure for table `bs_user`
--

CREATE TABLE `bs_user` (
  `user_id` int(100) UNSIGNED NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `date_added` varchar(50) DEFAULT NULL,
  `added_by` int(11) NOT NULL DEFAULT 0,
  `date_modified` varchar(50) DEFAULT NULL,
  `modified_by` int(11) NOT NULL DEFAULT 0,
  `date_deleted` varchar(50) DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uid` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bs_user`
--

INSERT INTO `bs_user` (`user_id`, `firstname`, `lastname`, `email`, `password`, `date_added`, `added_by`, `date_modified`, `modified_by`, `date_deleted`, `deleted_by`, `is_deleted`, `last_login`, `uid`) VALUES
(1, 'Trident', 'Corporation', 'trident@gmail.com', '$2y$10$xBT6X3bxEH7V/9RXUlycTO.WKQZGvGhHDR2/Rt9TWZrOktwyAbj/.', '2024-11-19 10:18:56', 1, NULL, 0, NULL, 0, 0, '2024-11-26 07:37:01', 'c4ca4238a0b923820dcc509a6f75849b'),
(2, 'Super', 'Admin', 'superadmin@gmail.com', '$2y$10$E0T.3t9a.tJSY3/J/zSpq.aqoAgTIoYy0CJucSBrY7PlbjHUWfY1y', '2024-11-19 10:27:38', 1, NULL, 0, NULL, 0, 0, '2024-11-26 07:37:03', 'c81e728d9d4c2f636f067f89cc14862c'),
(3, 'Admin', 'Admin', 'admin@gmail.com', '$2y$10$E0T.3t9a.tJSY3/J/zSpq.aqoAgTIoYy0CJucSBrY7PlbjHUWfY1y', '2024-11-19 10:35:35', 1, NULL, 0, NULL, 0, 0, '2025-02-14 05:02:30', 'eccbc87e4b5ce2fe28308fd9f2a7baf3'),
(4, 'Ronald', 'Tangguan', 'ronald@gmail.com', '$2y$10$3L4FOlaoc6tLAtncIJnYgOJJfUMDQdBqSLO.MZIYlIPGa0msBfMWK', '2024-11-26 13:40:23', 1, NULL, 0, NULL, 0, 0, '2025-03-03 06:09:46', 'a87ff679a2f3e71d9181a67b7542122c'),
(5, 'Benz', 'Lozada', 'benz@gmail.com', '$2y$10$Ak9bkFuEtCGZPIZkF5A4rObu7yF8qh.C0LxTHaksnF5tnkkOHjdQq', '2024-11-26 13:41:04', 1, NULL, 0, NULL, 0, 0, '2024-11-26 07:39:13', 'e4da3b7fbbce2345d7772b0674a318d5'),
(6, 'Kevin', 'Cortez', 'kevin@gmail.com', '$2y$10$OrZmObNRQApwT4l6llgNZObwWTLSJOImTk4FxRKEDQaD7Gwgmtia.', '2024-11-26 13:43:41', 1, NULL, 0, NULL, 0, 0, '2024-11-26 07:39:16', '1679091c5a880faf6fb5e6087eb1b2dc'),
(7, 'Hadden', 'James', 'hadden@gmail.com', '$2y$10$Cbra9y/DgbllPEOex0GKK.X11gWVuPIQx4vIV8eDvB3lkAbesMJZO', '2024-11-26 13:45:54', 1, NULL, 0, NULL, 0, 0, '2024-11-26 07:39:23', '8f14e45fceea167a5a36dedd4bea2543'),
(8, 'Christian', 'Mori', 'christian@gmail.com', '$2y$10$6WG3ZyqTH.6OpawAmc1oG.JrtAH7yOR.m4V.TmW2Yf8tEZvu05ZZy', '2024-11-26 13:47:17', 1, NULL, 0, NULL, 0, 0, '2024-12-27 03:06:22', 'c9f0f895fb98ab9159f51fd0297e236d'),
(9, NULL, NULL, 'ronald23@gmail.com', '$2y$10$EE9CVY6kScu4qf21SYHume/3bOsSfvxnBYHqHDNOWAT6tdFeA1Tvu', '2025-03-03 14:13:09', 0, NULL, 0, NULL, 0, 0, '2025-03-03 06:13:09', '45c48cce2e2d7fbdea1afc51c7c6ad26'),
(10, NULL, NULL, 'sample@gmail.com', '$2y$10$ruDD9e7YXEovs9O/8ZjAI.bR71zHQ8gMMwTXG8DnShu9kErbVCW3y', '2025-03-03 14:13:41', 0, NULL, 0, NULL, 0, 0, '2025-03-03 06:13:56', 'd3d9446802a44259755d38e6d163e820');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bs_setting`
--
ALTER TABLE `bs_setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `bs_user`
--
ALTER TABLE `bs_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bs_setting`
--
ALTER TABLE `bs_setting`
  MODIFY `setting_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `bs_user`
--
ALTER TABLE `bs_user`
  MODIFY `user_id` int(100) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
