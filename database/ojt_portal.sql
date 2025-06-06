-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 06:20 AM
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
-- Database: `ojt_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Completed','Pending') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `time_in`, `time_out`, `date`, `created_at`, `status`) VALUES
(7, 2, '2025-02-19 13:19:44', '2025-02-19 13:19:46', '2025-02-19', '2025-02-19 12:19:44', 'Completed'),
(8, 2, '2025-02-19 13:19:48', '2025-02-19 13:19:51', '2025-02-19', '2025-02-19 12:19:48', 'Completed'),
(9, 2, '2025-02-19 13:20:04', '2025-02-19 13:40:57', '2025-02-19', '2025-02-19 12:20:04', 'Completed'),
(10, 2, '2025-02-19 13:40:59', '2025-02-19 13:41:00', '2025-02-19', '2025-02-19 12:40:59', 'Completed'),
(11, 2, '2025-02-19 13:41:03', '2025-02-19 13:41:04', '2025-02-19', '2025-02-19 12:41:03', 'Completed'),
(12, 2, '2025-02-19 13:41:06', '2025-02-19 13:41:07', '2025-02-19', '2025-02-19 12:41:06', 'Completed'),
(14, 2, '2025-02-19 13:58:21', '2025-02-19 13:58:23', '2025-02-19', '2025-02-19 12:58:21', 'Completed'),
(15, 3, '2025-02-19 14:04:37', '2025-02-19 14:04:38', '2025-02-19', '2025-02-19 13:04:37', 'Completed'),
(16, 2, '2025-02-19 14:05:18', '2025-02-19 14:05:20', '2025-02-19', '2025-02-19 13:05:18', 'Completed'),
(17, 2, '2025-02-19 14:14:29', '2025-02-19 22:33:12', '2025-02-19', '2025-02-19 13:14:29', 'Completed'),
(18, 3, '2025-02-19 14:15:48', '2025-02-19 14:26:33', '2025-02-19', '2025-02-19 13:15:48', 'Completed'),
(19, 4, '2025-02-19 14:30:20', '2025-02-19 14:44:30', '2025-02-19', '2025-02-19 13:30:20', 'Completed'),
(20, 4, '2025-02-19 14:49:15', '2025-02-19 14:49:20', '2025-02-19', '2025-02-19 13:49:15', 'Completed'),
(21, 4, '2025-02-19 21:53:43', '2025-02-19 21:54:01', '2025-02-19', '2025-02-19 13:53:43', 'Completed'),
(22, 4, '2025-02-19 21:55:07', '2025-02-19 22:33:29', '2025-02-19', '2025-02-19 13:55:07', 'Completed'),
(23, 2, '2025-02-19 22:33:16', '2025-02-20 09:58:03', '2025-02-19', '2025-02-19 14:33:16', 'Completed'),
(24, 4, '2025-02-19 22:33:32', '2025-02-19 22:38:49', '2025-02-19', '2025-02-19 14:33:32', 'Completed'),
(25, 4, '2025-02-19 22:38:50', '2025-02-19 22:40:57', '2025-02-19', '2025-02-19 14:38:50', 'Completed'),
(26, 2, '2025-02-20 09:58:05', '2025-02-20 16:42:51', '2025-02-20', '2025-02-20 01:58:05', 'Completed'),
(27, 2, '2025-02-20 16:44:50', '2025-02-20 16:45:26', '2025-02-20', '2025-02-20 08:44:50', 'Completed'),
(28, 2, '2025-02-20 16:59:36', '2025-02-21 08:23:13', '2025-02-20', '2025-02-20 08:59:36', 'Completed'),
(29, 2, '2025-02-21 08:23:16', '2025-02-21 16:56:53', '2025-02-21', '2025-02-21 00:23:16', 'Completed'),
(30, 2, '2025-02-24 08:45:45', '2025-02-24 17:00:58', '2025-02-24', '2025-02-24 00:45:45', 'Completed'),
(31, 2, '2025-02-25 08:12:34', '2025-02-25 17:06:48', '2025-02-25', '2025-02-25 00:12:34', 'Completed'),
(32, 2, '2025-02-26 09:07:20', '2025-03-03 17:50:59', '2025-02-26', '2025-02-26 01:07:20', 'Completed'),
(33, 2, '2025-03-04 09:01:50', '2025-03-05 22:30:25', '2025-03-04', '2025-03-04 01:01:50', 'Completed'),
(34, 2, '2025-03-06 12:29:08', '2025-03-07 14:27:32', '2025-03-06', '2025-03-06 04:29:08', 'Completed'),
(35, 2, '2025-03-08 15:33:43', '2025-03-08 16:11:29', '2025-03-08', '2025-03-08 07:33:43', 'Completed'),
(36, 2, '2025-03-08 16:11:33', '2025-03-12 08:21:48', '2025-03-08', '2025-03-08 08:11:33', 'Completed'),
(37, 5, '2025-06-06 12:19:16', '2025-06-06 12:19:18', '2025-06-06', '2025-06-06 04:19:16', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `ojt_hours` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `hours_completed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `company`, `ojt_hours`, `start_date`, `email`, `password`, `created_at`, `hours_completed`) VALUES
(1, 'Yuri', 'Gonzaga', 'PHilMech', 300, '2025-02-24', 'yuri@gmail.com', '$2y$10$FTcJSPChDdtRT2Q4V0a2tOtIDG1390pqRV1G59OPjAsM8S73QEUfC', '2025-02-18 05:34:22', 0),
(2, 'Aleck', 'Ferry', 'PHilMech', 300, '2025-02-24', 'aleck@gmail.com', '$2y$10$h9QIFwoHPkuaJGajT5iqy.O4aVw8ZxhDZBPglA3mhH0n18p.p4TU6', '2025-02-18 05:42:17', 0),
(3, 'Francis', 'Gago', 'PHilMech', 500, '2025-02-20', 'francis@gmail.com', '$2y$10$Z.lyRy0x1TRuJsKhheCjEuMjq6/QDwyPRwPyQCPj1BIIevs79a9AG', '2025-02-19 12:18:11', 0),
(4, 'Yuri', 'Gonzaga', 'PHilMech', 1, '2025-02-20', 'yurigonzaga643@gmail.com', '$2y$10$2imyPUVKQvcS4o/1nwzam.82J8Rxr/8IFdCgayekCXmfMSpVbdVfS', '2025-02-19 13:30:06', 0),
(5, 'Yuri', 'Gonzaga', 'Grand Luxe Hotel', 500, '2025-06-20', 'princes@gmail.com', '$2y$10$00VjPAvzhL4NoTgJ4k1a3OQGZk.Q8QjpMzznGIYnbzeFqVXc44tzW', '2025-06-06 04:13:09', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
