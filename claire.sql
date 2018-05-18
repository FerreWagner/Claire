-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-05-18 11:57:02
-- 服务器版本： 10.1.32-MariaDB
-- PHP Version: 7.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `claire`
--

-- --------------------------------------------------------

--
-- 表的结构 `claire_admin`
--

CREATE TABLE `claire_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(225) NOT NULL,
  `count` int(11) DEFAULT '1',
  `email` varchar(100) NOT NULL,
  `role` tinyint(1) DEFAULT '1' COMMENT '0为超级管理员,1为管理员',
  `switch` varchar(5) DEFAULT 'true' COMMENT 'true为开启,false为关闭',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `claire_admin`
--

INSERT INTO `claire_admin` (`id`, `username`, `password`, `count`, `email`, `role`, `switch`, `update_time`, `create_time`) VALUES
(1, 'ferre', '6226514790b1175cf90bca075f3887a6c54ef58e', 56, '1573646491@qq.com', 0, 'true', 1526611272, 1513926388),
(3, 'root', '6226514790b1175cf90bca075f3887a6c54ef58e', 11, '1573646491@qq.com', 1, 'true', 1526529131, 1514172018),
(4, 'alexa', '6226514790b1175cf90bca075f3887a6c54ef58e', 3, '123@qq.com', 1, 'true', 1514969258, 1514966522),
(7, 'Rick', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, '123@qq.com', 1, 'false', 1515576815, 1515576815),
(8, 'Freeze', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, '1573646491@qq.com', 1, 'false', 1515577073, 1515577073),
(17, 'YYF', 'cb89c0b02495e9bbd1d2f99f1abe1b6c01b2e38b', 1, '1573646491@qq.com', 0, 'false', 1515651524, 1515638468),
(18, 'asd', '011c945f30ce2cbafc452f39840f025693339c42', 1, '1573646491@qq.com', 0, 'true', 1515728342, 1515728342);

-- --------------------------------------------------------

--
-- 表的结构 `claire_alog`
--

CREATE TABLE `claire_alog` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `claire_alog`
--

INSERT INTO `claire_alog` (`id`, `type`, `name`, `ip`, `time`) VALUES
(1, 0, 'ferre', '127.0.0.1', 1526287749),
(2, 0, 'ferre', '127.0.0.1', 1526287754),
(3, 0, 'root', '127.0.0.1', 1526287760),
(4, 1, 'root', '127.0.0.1', 1526287767),
(5, 0, 'root', '127.0.0.1', 1526450761),
(6, 1, 'root', '127.0.0.1', 1526450773),
(7, 1, 'root', '127.0.0.1', 1526529131),
(8, 1, 'ferre', '127.0.0.1', 1526611272);

-- --------------------------------------------------------

--
-- 表的结构 `claire_article`
--

CREATE TABLE `claire_article` (
  `id` int(11) NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `title` text,
  `cate` text NOT NULL,
  `order` int(11) DEFAULT NULL,
  `content` text,
  `thumb` text,
  `desc` text,
  `see` int(11) DEFAULT NULL,
  `keywords` text,
  `time` int(11) DEFAULT NULL,
  `pic` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `claire_artsee`
--

CREATE TABLE `claire_artsee` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `rid` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `claire_category`
--

CREATE TABLE `claire_category` (
  `id` int(11) NOT NULL,
  `catename` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL DEFAULT 'Alexa Zhang',
  `pid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `claire_category`
--

INSERT INTO `claire_category` (`id`, `catename`, `sort`, `desc`, `pid`) VALUES
(1, 'Alexa', 3, 'Alexa Zhang', 0),
(2, 'Ferre', 6, '', 1),
(3, 'Ashly', 7, '', 2),
(4, 'Freeze', 9, '', 2),
(5, '老铁', 123, '', 0),
(6, '222', 222, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `claire_link`
--

CREATE TABLE `claire_link` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` text NOT NULL,
  `sort` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `claire_link`
--

INSERT INTO `claire_link` (`id`, `name`, `url`, `sort`) VALUES
(1, '百度', 'https://www.baidu.com', '1');

-- --------------------------------------------------------

--
-- 表的结构 `claire_system`
--

CREATE TABLE `claire_system` (
  `id` int(11) NOT NULL,
  `is_close` tinyint(4) NOT NULL,
  `title` text NOT NULL,
  `keywords` text NOT NULL,
  `desc` text NOT NULL,
  `is_mail` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0为本地存储；1为七牛云；2为阿里云OSS',
  `record` varchar(50) NOT NULL DEFAULT '',
  `is_update` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `claire_system`
--

INSERT INTO `claire_system` (`id`, `is_close`, `title`, `keywords`, `desc`, `is_mail`, `type`, `record`, `is_update`) VALUES
(1, 0, 'Alexa2', '萨法1', 'About Alexa', 1, 1, '蜀ICP备17036283号-2', 0);

-- --------------------------------------------------------

--
-- 表的结构 `claire_tourist`
--

CREATE TABLE `claire_tourist` (
  `id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `claire_admin`
--
ALTER TABLE `claire_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_alog`
--
ALTER TABLE `claire_alog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_article`
--
ALTER TABLE `claire_article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_artsee`
--
ALTER TABLE `claire_artsee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_category`
--
ALTER TABLE `claire_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_link`
--
ALTER TABLE `claire_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_system`
--
ALTER TABLE `claire_system`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `claire_tourist`
--
ALTER TABLE `claire_tourist`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `claire_admin`
--
ALTER TABLE `claire_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- 使用表AUTO_INCREMENT `claire_alog`
--
ALTER TABLE `claire_alog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `claire_article`
--
ALTER TABLE `claire_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `claire_artsee`
--
ALTER TABLE `claire_artsee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `claire_category`
--
ALTER TABLE `claire_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `claire_link`
--
ALTER TABLE `claire_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `claire_system`
--
ALTER TABLE `claire_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `claire_tourist`
--
ALTER TABLE `claire_tourist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
