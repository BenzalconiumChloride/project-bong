-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 08:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_bmi_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `school` varchar(100) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `section` varchar(10) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) NOT NULL,
  `bmi_category` varchar(20) NOT NULL,
  `measurement_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `school`, `grade`, `section`, `age`, `gender`, `height`, `weight`, `bmi`, `bmi_category`, `measurement_date`, `created_at`) VALUES
(1, 'Alex Johnson', 'Central High School', '9', 'A', 14, 'male', 160.00, 41.50, 16.20, 'Underweight', '2023-10-15', '2026-01-30 07:26:46'),
(2, 'Maria Garcia', 'Lincoln Middle School', '7', 'B', 12, 'female', 155.00, 54.00, 22.50, 'Normal', '2023-10-16', '2026-01-30 07:26:46'),
(3, 'Jamie Smith', 'Westside Elementary', '5', 'C', 10, 'other', 145.00, 60.30, 28.70, 'Overweight', '2023-10-12', '2026-01-30 07:26:46'),
(4, 'Taylor Brown', 'Central High School', '11', 'A', 16, 'female', 165.00, 84.90, 31.20, 'Obese', '2023-10-10', '2026-01-30 07:26:46'),
(5, 'Jordan Lee', 'Northside Academy', '8', 'B', 13, 'male', 152.00, 45.70, 19.80, 'Normal', '2023-10-18', '2026-01-30 07:26:46'),
(6, 'Sam Smith', 'DMLMHS', '9', 'venus', 15, 'male', 157.00, 68.00, 27.59, 'Overweight', '2026-01-30', '2026-01-30 07:29:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
