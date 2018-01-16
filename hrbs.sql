-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2018 at 04:24 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `electricity_charge`
--

CREATE TABLE `electricity_charge` (
  `id` int(11) NOT NULL,
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `electricity_charge`
--

INSERT INTO `electricity_charge` (`id`, `rate`) VALUES
(1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rent` varchar(100) NOT NULL,
  `rent_date` varchar(100) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `name`, `rent`, `rent_date`, `added_date`, `enabled`) VALUES
(4, 'Sachin Aryal', '14500', '2074/5/4', '2018-01-15 06:50:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rent_record`
--

CREATE TABLE `rent_record` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(200) NOT NULL,
  `rent` int(11) NOT NULL,
  `previous_electricity_unit` int(11) NOT NULL,
  `current_electricity_unit` int(11) NOT NULL,
  `water_cost` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rent_record`
--

INSERT INTO `rent_record` (`id`, `name`, `year`, `month`, `rent`, `previous_electricity_unit`, `current_electricity_unit`, `water_cost`, `status`) VALUES
(1, 'Sachin Aryal', 2070, 'Baishakh', 14500, 2222, 22222, 2222, 0),
(2, 'Sachin Aryal', 2070, 'Baishakh', 14500, 2222, 22222, 2222, 0),
(3, 'Sachin Aryal', 2070, 'Baishakh', 14500, 2222, 22222, 2222, 0),
(4, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(5, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(6, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(7, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(8, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(9, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(10, 'Sachin Aryal', 2070, 'Baishakh', 14500, 222, 22222222, 22222, 0),
(11, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(12, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(13, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(14, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(15, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(16, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(17, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(18, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(19, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(20, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(21, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(22, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(23, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(24, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(25, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(26, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 2222, 0),
(27, 'Sachin Aryal', 2071, 'Asar', 14500, 222, 222, 22222, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `electricity_charge`
--
ALTER TABLE `electricity_charge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rent_record`
--
ALTER TABLE `rent_record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `electricity_charge`
--
ALTER TABLE `electricity_charge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rent_record`
--
ALTER TABLE `rent_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
