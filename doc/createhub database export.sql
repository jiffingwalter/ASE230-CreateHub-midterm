-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2023 at 02:21 AM
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
-- Database: `createhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `aid` int(11) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `ext` varchar(6) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`aid`, `pid`, `file_name`, `ext`, `size`, `type`, `date_created`) VALUES
(1, 2, '20231019183921_2.jpg', 'jpg', 149728, 'image/jpeg', '2023-10-30 12:00:30'),
(2, 3, 'Cave_Women.PNG', 'png', 149728, 'image/png', '2023-10-16 03:24:04'),
(3, 4, '3c75d20.jpg', 'jpg', 2899318, 'image/jpeg', '2023-10-16 12:00:30'),
(4, 5, '2023-04-20.png', 'png', 149728, 'image/png', '2023-11-09 12:00:30'),
(5, 7, '2RP5BgZ.jpg', 'jpg', 520855, 'image/jpeg', '2023-10-15 11:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `fid` int(11) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `category` varchar(32) DEFAULT NULL,
  `images` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`fid`, `author`, `name`, `category`, `images`) VALUES
(1, 4, 'another Portfolio', 'Images', 'gort.jpg,patproj.jpg'),
(2, 4, 'Resume', 'Art', 'astrowave wallpaper.jpg'),
(3, 4, 'Test portfolio', 'Work', 'DV4pD0pVAAUVWFf.png,feelsbetterman.jpg,Kronk_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `pid` int(11) NOT NULL,
  `author` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `has_attachment` tinyint(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_edited` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`pid`, `author`, `title`, `content`, `has_attachment`, `date_created`, `last_edited`) VALUES
(1, 1, 'POST TITLE', 'POST CONTENT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 3, 'testing new posts', 'test post body', 1, '2023-10-30 12:00:30', '2023-11-08 03:15:23'),
(3, 4, 'test post', 'post bod', 1, '2023-10-16 03:24:04', '2023-11-08 03:15:23'),
(4, 4, 'testing for posts', 'thank you for viewing this post', 1, '2023-10-16 12:00:30', '2023-10-16 12:00:30'),
(5, 3, '', '', 1, '2023-11-09 12:00:30', '2023-11-18 03:15:23'),
(6, 2, 'user 1s post', 'she sells sea shells by the sea shore', 1, '2023-10-14 16:05:00', '2023-10-14 06:34:23'),
(7, 2, 'cool picture', 'this picture is cool', 1, '2023-10-15 11:43:05', '2023-10-15 11:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `tid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`tid`, `pid`) VALUES
(1, 2),
(2, 2),
(3, 4),
(4, 4),
(5, 6),
(6, 6),
(7, 6),
(8, 7),
(9, 7),
(10, 7),
(11, 7),
(1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(0, 'Admin'),
(1, 'User'),
(2, 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tid` int(11) NOT NULL,
  `tag` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tid`, `tag`) VALUES
(1, 'picture'),
(2, 'horse'),
(3, 'cool'),
(4, 'art'),
(5, 'test'),
(6, 'post'),
(7, 'wee doggy'),
(8, 'this'),
(9, 'is'),
(10, 'a'),
(11, 'nice');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `name`, `email`, `password`, `date_created`, `role`) VALUES
(1, 'UNDEFINED', 'template@createhub.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '0000-00-00 00:00:00', 1),
(2, 'ADMIN', 'admin@createhub.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '0000-00-00 00:00:00', 0),
(3, 'test account 1', 'test1@email.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '2023-10-15 09:46:44', 1),
(4, 'test account 2', 'test2@email.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '2023-10-20 11:52:49', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
