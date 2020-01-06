-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2019 at 09:51 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_live_chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat`
--

CREATE TABLE `tbl_chat` (
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `chat_message` text NOT NULL,
  `chat_image` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_online`
--

CREATE TABLE `tbl_chat_online` (
  `chat_online_id` int(11) NOT NULL,
  `chat_room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_room`
--

CREATE TABLE `tbl_chat_room` (
  `chat_room` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_chat_room`
--

INSERT INTO `tbl_chat_room` (`chat_room`, `room_name`, `created_at`, `updated_at`) VALUES
(1, 'Home', '2019-05-25 15:35:36', '2019-05-25 09:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `tbl_chat_online`
--
ALTER TABLE `tbl_chat_online`
  ADD PRIMARY KEY (`chat_online_id`);

--
-- Indexes for table `tbl_chat_room`
--
ALTER TABLE `tbl_chat_room`
  ADD PRIMARY KEY (`chat_room`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chat_online`
--
ALTER TABLE `tbl_chat_online`
  MODIFY `chat_online_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chat_room`
--
ALTER TABLE `tbl_chat_room`
  MODIFY `chat_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
