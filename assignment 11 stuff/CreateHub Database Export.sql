-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2023 at 03:38 AM
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
-- Table structure for table `attached_to`
--

CREATE TABLE `attached_to` (
  `attachment_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attached_to`
--

INSERT INTO `attached_to` (`attachment_id`, `post_id`) VALUES
(123456, 770308),
(468516, 944839),
(985456, 475467),
(569485, 328885),
(456735, 123903);

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `aid` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `ext` varchar(5) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`aid`, `file_name`, `ext`, `size`, `type`) VALUES
(123456, '20231019183921_2.jpg', 'jpg', 149728, 'image/jpeg'),
(456735, '2RP5BgZ.jpg', 'jpg', 520855, 'image/jpeg'),
(468516, 'Cave_Women.PNG', 'png', 149728, 'image/png'),
(569485, '2023-04-20.png', 'png', 149728, 'image/png'),
(985456, '3c75d20.jpg', 'jpg', 2899318, 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `fid` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `category` varchar(32) DEFAULT NULL,
  `images` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`fid`, `name`, `category`, `images`) VALUES
(123456, 'Test portfolio', 'Work', '[\"DV4pD0pVAAUVWFf.png\",\"feelsbetterman.jpg\",\"Kronk_.jpg\"]'),
(234567, 'another Portfolio', 'Images', '[\"gort.jpg\",\"patproj.jpg\"]'),
(894567, 'Resume', 'Art', '[\"astrowave wallpaper.jpg\"]');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `pid` int(11) NOT NULL,
  `title` varchar(64) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `has_attachment` int(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_edited` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`pid`, `title`, `content`, `has_attachment`, `date_created`, `last_edited`) VALUES
(0, 'POST TITLE', 'POST CONTENT', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1, 'user 1s post', 'she sells sea shells by the sea shore', 1, '2023-10-14 16:05:00', '2023-10-14 06:34:23'),
(123903, 'cool picture', 'this picture is cool', 1, '2023-10-15 11:43:05', '2023-10-15 11:43:05'),
(328885, '', '', 1, '2023-11-09 12:00:30', '2023-11-18 03:15:23'),
(475467, 'testing for posts', 'thank you for viewing this post', 1, '2023-10-16 12:00:30', '2023-10-16 12:00:30'),
(770308, 'testing new posts', 'test post body', 1, '2023-10-30 12:00:30', '2023-11-08 03:15:23'),
(944839, 'test post', 'post bod', 1, '2023-10-16 03:24:04', '2023-11-08 03:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `tag_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`tag_id`, `post_id`) VALUES
(0, 770308),
(3, 770308),
(1, 475467),
(2, 475467),
(4, 1),
(5, 1),
(6, 1),
(7, 123903),
(8, 123903),
(9, 123903),
(10, 123903),
(11, 123903);

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
  `id` int(11) NOT NULL,
  `tag` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`) VALUES
(0, 'red dead redemption 2'),
(1, 'cool'),
(2, 'art'),
(3, 'horses'),
(4, 'test'),
(5, 'post'),
(6, 'wee doggy'),
(7, 'this'),
(8, 'is'),
(9, 'a'),
(10, 'cool'),
(11, 'picture');

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
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `name`, `email`, `password`, `date_created`, `role`) VALUES
(0, 'UNDEFINED', 'template@createhub.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '0000-00-00 00:00:00', 1),
(1, 'ADMIN', 'admin@createhub.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '0000-00-00 00:00:00', 0),
(520790, 'test account 2', 'test2@email.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '2023-10-20 11:52:49', 1),
(983282, 'test account 1', 'test1@email.com', '$2y$10$OQ4aqiyJi6RWhXO3kuTHtus1bwIh6/TP0hOj4/yJ8r2qGwCihpz8m', '2023-10-15 09:46:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_portfolios`
--

CREATE TABLE `user_portfolios` (
  `user_id` int(11) DEFAULT NULL,
  `portfolio_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_portfolios`
--

INSERT INTO `user_portfolios` (`user_id`, `portfolio_id`) VALUES
(983282, 123456),
(983282, 234567),
(983282, 894567);

-- --------------------------------------------------------

--
-- Table structure for table `user_posts`
--

CREATE TABLE `user_posts` (
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_posts`
--

INSERT INTO `user_posts` (`user_id`, `post_id`) VALUES
(520790, 770308),
(983282, 944839),
(983282, 475467),
(520790, 328885),
(1, 1),
(1, 123903);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
