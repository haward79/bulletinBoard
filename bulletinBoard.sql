-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2019 at 09:04 AM
-- Server version: 10.3.17-MariaDB-0+deb10u1
-- PHP Version: 7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bulletinBoard`
--
CREATE DATABASE IF NOT EXISTS `bulletinBoard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `bulletinBoard`;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin`
--

CREATE TABLE `bulletin` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `file` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bulletin`
--

INSERT INTO `bulletin` (`id`, `title`, `content`, `type`, `datetime`, `link`, `file`) VALUES
(1, '[>>請先讀我<<] 系統建置完成 歡迎使用', '本系統已建置完成，歡迎使用本系統。\r\n\r\n✈ For User\r\n您可以盡情瀏覽本系統上的公告，祝您使用愉快。\r\n\r\n✈ For Admin\r\n請您回到首頁後點擊「管理後台」即可進入登入頁面。\r\n登入後即可在首頁看到「新增公告」的按鈕，點擊各則公告後可於頁面底端進行編輯與刪除等操作。\r\n\r\n如使用本系統時遇到問題，請您與系統管理員聯繫。\r\n', 5, '2019-11-09 09:01:35', 'haward79\nhttps://www.haward79.tw/\nTaiwancan\nhttps://www.taiwancan.tw/\n', '附件1 範例附件.pdf\n201911090901350\n');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('admin', '$1$5ua.FWNh$xTo0JBWJ3R6EGMltKOoTm/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bulletin`
--
ALTER TABLE `bulletin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bulletin`
--
ALTER TABLE `bulletin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
