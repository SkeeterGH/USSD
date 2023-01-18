-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20221224.47627104f2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2023 at 10:20 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartcast`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `description`) VALUES
(1, 'Most Voted Person Of The Year');

-- --------------------------------------------------------

--
-- Table structure for table `invalids_log`
--

CREATE TABLE `invalids_log` (
  `id` int(11) NOT NULL,
  `sessionId` int(200) NOT NULL,
  `ussdLevel` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nominees`
--

CREATE TABLE `nominees` (
  `id` int(11) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `sch_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `shortcode` varchar(10) NOT NULL,
  `totalvotes` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `is_active` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nominees`
--

INSERT INTO `nominees` (`id`, `fullname`, `sch_id`, `cat_id`, `shortcode`, `totalvotes`, `photo`, `is_active`) VALUES
(1, 'Abukari Sumaila', 1, 1, 'MLM001', 840, '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `organisers`
--

CREATE TABLE `organisers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `phonenumber` varchar(50) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `cashout` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organisers`
--

INSERT INTO `organisers` (`id`, `fullname`, `phonenumber`, `pin`, `cashout`, `photo`) VALUES
(1, 'Mohammed Hamdan', '+233550555235', '$2y$10$VyZ0CKgqqBX5pH6jDdi9seW24mslY70AeXk1tm5s.NM4QK9SkXOym', '154.5', '');

-- --------------------------------------------------------

--
-- Table structure for table `scheme`
--

CREATE TABLE `scheme` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `org_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `alies` varchar(10) NOT NULL,
  `sendername` varchar(20) NOT NULL,
  `unitcost` varchar(20) NOT NULL,
  `charges` varchar(20) NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scheme`
--

INSERT INTO `scheme` (`id`, `title`, `org_id`, `status`, `alies`, `sendername`, `unitcost`, `charges`, `logo`) VALUES
(1, ' More light Multimedia', 1, '1', 'MLM', 'More light', '0.50', '25', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invalids_log`
--
ALTER TABLE `invalids_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nominees`
--
ALTER TABLE `nominees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisers`
--
ALTER TABLE `organisers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheme`
--
ALTER TABLE `scheme`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invalids_log`
--
ALTER TABLE `invalids_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nominees`
--
ALTER TABLE `nominees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organisers`
--
ALTER TABLE `organisers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `scheme`
--
ALTER TABLE `scheme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
