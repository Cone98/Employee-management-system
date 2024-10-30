-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 04:21 AM
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
-- Database: `projekat`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_info`
--

CREATE TABLE `attendance_info` (
  `aten_id` int(20) NOT NULL,
  `atn_user_id` int(20) NOT NULL,
  `in_time` varchar(200) DEFAULT NULL,
  `out_time` varchar(150) DEFAULT NULL,
  `total_duration` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `attendance_info`
--

INSERT INTO `attendance_info` (`aten_id`, `atn_user_id`, `in_time`, `out_time`, `total_duration`) VALUES
(38, 18, '22-03-2021 13:51:01', NULL, NULL),
(35, 17, '22-03-2021 11:37:44', NULL, NULL),
(37, 21, '22-03-2021 13:49:26', NULL, NULL),
(39, 23, '22-03-2021 13:51:51', NULL, NULL),
(40, 20, '22-03-2021 13:52:24', NULL, NULL),
(41, 25, '22-03-2021 15:09:00', NULL, NULL),
(42, 1, '22-03-2021 22:01:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `task_titl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`ID`, `user_id`, `title`, `description`, `task_titl`) VALUES
(15, 37, 'Neki com', 'lalalala', 'Task1');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `task` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `ID` int(11) NOT NULL,
  `RoleNo` int(11) NOT NULL,
  `RoleName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`ID`, `RoleNo`, `RoleName`) VALUES
(1, 1, 'Admin'),
(2, 2, 'Izvrsilac'),
(3, 3, 'Rukovodilac');

-- --------------------------------------------------------

--
-- Table structure for table `taskgroup`
--

CREATE TABLE `taskgroup` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taskgroup`
--

INSERT INTO `taskgroup` (`ID`, `name`) VALUES
(1, 'finansijski'),
(2, 'administrativni'),
(4, 'Fizicki');

-- --------------------------------------------------------

--
-- Table structure for table `task_info`
--

CREATE TABLE `task_info` (
  `task_id` int(50) NOT NULL,
  `t_title` varchar(120) NOT NULL,
  `t_description` text DEFAULT NULL,
  `taskgroup` varchar(50) DEFAULT NULL,
  `t_end_time` varchar(100) DEFAULT NULL,
  `t_user_id` int(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = incomplete, 1 = In progress, 2 = complete',
  `rukovodioc` varchar(39) NOT NULL,
  `prioritet` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `task_info`
--

INSERT INTO `task_info` (`task_id`, `t_title`, `t_description`, `taskgroup`, `t_end_time`, `t_user_id`, `status`, `rukovodioc`, `prioritet`) VALUES
(101, 'Task1', '', '1', '', 39, 0, '45', 3),
(117, 'task4', 'tash', '4', '2022-09-14 12:00', 39, 0, '39', 8),
(108, 'Task3', 'task', '1', '', 36, 0, '45', 10),
(100, 'Task1', '', '1', '', 37, 0, '45', 3),
(99, 'Task1', '', '1', '', 36, 0, '45', 3),
(98, 'Task1', '', '1', '', 35, 0, '45', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `user_id` int(20) NOT NULL,
  `fullname` varchar(120) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_role` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`user_id`, `fullname`, `username`, `email`, `password`, `user_role`) VALUES
(1, 'Admin', 'admin', 'admin@gmail.com', 'codeastro1', 1),
(35, 'Pera peric', 'pera23', 'petar@smg.com', 'petar123', 2),
(36, 'Marko Karic', 'KareM', 'markoK@hotmail', '1234', 2),
(37, 'Negoslav', 'nega', 'destroyer@gmail', 'destroy', 2),
(39, 'Slavisa', 'slavko', 'slavko@gmail', 'slavko1', 3),
(45, 'Cone', 'Cone98', 'nemanja@gmail.com', 'cone', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `taskgroup`
--
ALTER TABLE `taskgroup`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `task_info`
--
ALTER TABLE `task_info`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `taskgroup` (`taskgroup`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taskgroup`
--
ALTER TABLE `taskgroup`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `task_info`
--
ALTER TABLE `task_info`
  MODIFY `task_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
