-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 26, 2020 at 09:27 AM
-- Server version: 10.1.44-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tilladt`
--

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `name`, `title`) VALUES
(7, 'dbedit', 'input form'),
(13, 'clrlog', 'clrlog'),
(14, 'somehardware', 'Some hardware');

-- --------------------------------------------------------

--
-- Table structure for table `reqLog`
--

CREATE TABLE `reqLog` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `path` varchar(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `uaid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sec`
--

CREATE TABLE `sec` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sec`
--

INSERT INTO `sec` (`id`, `name`, `title`) VALUES
(7, 'eee1005', 'Asus Eee 1005 Pc'),
(8, 'eee900', 'Asus Eee 900 PC'),
(10, 'powersupl', 'power supplies'),
(11, 'network', 'The network');

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE `site` (
  `id` int(11) NOT NULL,
  `pageid` int(11) NOT NULL,
  `secid` int(11) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `pic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `site`
--

INSERT INTO `site` (`id`, `pageid`, `secid`, `content`, `pic`) VALUES
(60, 14, 10, 'All power supplies', 'powersupplies.jpg'),
(61, 14, 10, 'All power supplies and switches to turn off, which is never used.', NULL),
(62, 14, 10, 'The Asus Eee Pc\'s uses much less power than normal desktop\'s. The speaker amplifier has an old-fashion 50Htz iron core transformer which emit more standby power than modern SMPSs.', NULL),
(63, 14, 10, 'The stripes around til upper left 3 transformers, holds small bricks that lifts the transformer away from the surface to allow cooling air flow.', NULL),
(64, 14, 10, 'The white one, a 12volt 3amp for Eee 900 PC, is one of two bought in year 2008. The other stopped working - after only 10 years of uninterrupted current delivery!.', NULL),
(65, 14, 10, 'transformer|volt| effect', NULL),
(66, 14, 10, 'Asus Eee 1005|19|40W', NULL),
(67, 14, 10, 'Asus Eee 900|12|36W', NULL),
(68, 14, 10, 'Asus Eee 900|12|36W', NULL),
(74, 14, 8, 'Anno 2008 computer, which still rocks. Original shipped with Xandros (or windows XP). Works excellent with slackware 14.2, albeit videos in browser is more than it can run satisfactorily. It\'s VGA output extend its long time ago outdated value as a notebook to a fine 2020 desktop Computer.', 'eee900.jpg'),
(77, 14, 8, 'Windows\r\nNT 5.1 sp3 home edition\r\nIntel(R) Celeron(R) M processor 900MHz\r\n1GiB Ram\r\n15GB ssd drive', NULL),
(78, 14, 8, 'Linux\r\nIntel(R) Celeron(R) M processor 900MHz\r\n32KiB L1 cache\r\n512KiB L2 cache\r\nBogoMIPS 1800\r\n1GiB DIMM DDR2 Synchronous 400 MHz (2.5 ns)\r\n16GB ssd drive', NULL),
(83, 14, 7, 'Perhaps anno 2009 Asus Eee Pc. By installing ubuntu 18.04, a php 7.2 equipped server with all \'gemüse\' such as mysql, phpmyadmin and remote access-ability in form of ftp, ssh and nfs, was easy made.', 'eee1005.jpg'),
(84, 14, 7, 'Linux\r\nIntel(R) Atom(TM) CPU N450   @ 1.66GHz\r\n24KiB L1 cache\r\n512KiB L2 cache\r\nBogoMIPS 3333\r\n1GiB DIMM DDR2 Synchronous 667 MHz (1.5 ns)\r\n160GB WDC WD1600BEVT-2', NULL),
(85, 14, 10, 'ethernet switch|5|3W', NULL),
(86, 14, 10, 'usb hub|5|10W', NULL),
(87, 14, 10, 'speaker amplifier|12|18W', NULL),
(88, 14, 11, 'The mighty network in its glory.', 'network.jpg'),
(89, 14, 11, 'Huawei broadband stick connect the lan to internet - slackware acts as gateway. It HAS to be connected to a linux computer because it contains a drive which auto installs 80 Mbyte af useless shit in windows - I tried delete it all - the stick still worked because the driver layed at another place.', NULL),
(90, 14, 11, 'Below the linux Eee900, the KVM switch which facilitates, one at a time, one of two computers  with the keyboard/mouse/monitor. Slackware is always one of them, nearly newer ubuntu because every thing is setup which makes windows xp available for seldom task of sketchup use, ms-access database use, ms-visio drawing and printing.', NULL),
(91, 14, 11, 'By the way, xp home runs iis with both a ftp virtual dir and a website - Yes xp HOME - I neither have lived without that secutity  tab of file properties through which ownership is controlled. But don\'t ask me how i did it!', NULL),
(92, 14, 11, 'The file systems is connected using ftp, ssh and nfs. I never jumped on Samba, beacuse i knew i would learn a lot by missing it. From xp i can reach any corner of Slackware and selected directories on ubuntu. From Slackware i can reach exposed ftp and webserver directories on xp and ubuntu, selected directories on ubuntu using nfs and any corner of ubuntu through ssh. Ubuntu do not have the role to access files on lan.', NULL),
(93, 14, 10, 'max power||143W', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userAgent`
--

CREATE TABLE `userAgent` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `reqLog`
--
ALTER TABLE `reqLog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sec`
--
ALTER TABLE `sec`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userAgent`
--
ALTER TABLE `userAgent`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `reqLog`
--
ALTER TABLE `reqLog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=381;
--
-- AUTO_INCREMENT for table `sec`
--
ALTER TABLE `sec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT for table `userAgent`
--
ALTER TABLE `userAgent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
