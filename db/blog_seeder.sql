-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2025 at 01:37 PM
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
-- Database: `blog_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `id_comment` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`id_comment`, `id_post`, `id_user`, `username`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'user1', 'Comment 1 on Post 1', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(2, 2, 2, 'user2', 'Comment 2 on Post 2', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(3, 3, 3, 'user3', 'Comment 3 on Post 3', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(4, 4, 4, 'user4', 'Comment 4 on Post 4', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(5, 5, 5, 'user5', 'Comment 5 on Post 5', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(6, 6, 6, 'user6', 'Comment 6 on Post 6', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(7, 7, 7, 'user7', 'Comment 7 on Post 7', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(8, 8, 8, 'user8', 'Comment 8 on Post 8', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(9, 9, 9, 'user9', 'Comment 9 on Post 9', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(10, 10, 10, 'user10', 'Comment 10 on Post 10', '2025-02-12 12:31:24', '2025-02-12 12:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE `Post` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`id_post`, `id_user`, `title`, `content`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Post 1', 'Content for post 1', 'image1.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(2, 2, 'Post 2', 'Content for post 2', 'image2.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(3, 3, 'Post 3', 'Content for post 3', 'image3.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(4, 4, 'Post 4', 'Content for post 4', 'image4.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(5, 5, 'Post 5', 'Content for post 5', 'image5.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(6, 6, 'Post 6', 'Content for post 6', 'image6.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(7, 7, 'Post 7', 'Content for post 7', 'image7.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(8, 8, 'Post 8', 'Content for post 8', 'image8.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(9, 9, 'Post 9', 'Content for post 9', 'image9.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL),
(10, 10, 'Post 10', 'Content for post 10', 'image10.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Tags`
--

CREATE TABLE `Tags` (
  `id_tag` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `tag_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Tags`
--

INSERT INTO `Tags` (`id_tag`, `id_post`, `tag_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tag1', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(2, 2, 'Tag2', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(3, 3, 'Tag3', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(4, 4, 'Tag4', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(5, 5, 'Tag5', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(6, 6, 'Tag6', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(7, 7, 'Tag7', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(8, 8, 'Tag8', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(9, 9, 'Tag9', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(10, 10, 'Tag10', '2025-02-12 12:31:24', '2025-02-12 12:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id_user`, `username`, `name`, `email`, `password`, `profile_picture_url`, `created_at`, `updated_at`) VALUES
(1, 'user1', 'User One', 'user1@example.com', 'password1', 'profile1.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(2, 'user2', 'User Two', 'user2@example.com', 'password2', 'profile2.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(3, 'user3', 'User Three', 'user3@example.com', 'password3', 'profile3.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(4, 'user4', 'User Four', 'user4@example.com', 'password4', 'profile4.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(5, 'user5', 'User Five', 'user5@example.com', 'password5', 'profile5.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(6, 'user6', 'User Six', 'user6@example.com', 'password6', 'profile6.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(7, 'user7', 'User Seven', 'user7@example.com', 'password7', 'profile7.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(8, 'user8', 'User Eight', 'user8@example.com', 'password8', 'profile8.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(9, 'user9', 'User Nine', 'user9@example.com', 'password9', 'profile9.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24'),
(10, 'user10', 'User Ten', 'user10@example.com', 'password10', 'profile10.jpg', '2025-02-12 12:31:24', '2025-02-12 12:31:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`id_tag`),
  ADD KEY `id_post` (`id_post`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Tags`
--
ALTER TABLE `Tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `Post` (`id_post`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `Post`
--
ALTER TABLE `Post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `User` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `Tags`
--
ALTER TABLE `Tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `Post` (`id_post`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
