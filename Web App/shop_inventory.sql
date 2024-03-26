-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2024 at 01:45 PM
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
-- Database: `shop_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `productID` int(11) NOT NULL,
  `itemNumber` varchar(255) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `discount` float NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `unitPrice` float NOT NULL DEFAULT 0,
  `imageURL` varchar(255) NOT NULL DEFAULT 'imageNotAvailable.jpg',
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `storageLocation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`productID`, `itemNumber`, `itemName`, `discount`, `stock`, `unitPrice`, `imageURL`, `status`, `storageLocation`) VALUES
(50, '15', 'Oil Filter', 0, 15, 450, '1711083890_Oil Filter.jpg', 'In Stock', 'C3-R01'),
(51, '1', 'Air Filter', 0, 25, 650, '1711088383_Air filter.jpg', 'In Stock', 'C3-R02'),
(52, '2', 'Brake Pads', 0, 25, 880, '1711089350_Brake Pads.jpg', 'In Stock', 'C3-R03'),
(53, '3', 'Spark Plug', 0, 30, 600, '1711089980_Spark Plug.jpg', 'In Stock', 'C3-R04'),
(54, '4', 'Energizer Battery', 0, 15, 2500, '1711089992_Battery.jpg', 'In Stock', 'C4-R03'),
(55, '5', 'Bridgestone Tyers', 0, 10, 5800, '1711090013_Tyres.jpg', 'In Stock', 'C5-R01'),
(56, '6', 'Wiper Blades', 0, 30, 1200, '1711090021_Wiper Blades.jpg', 'In Stock', 'C4-R02'),
(57, '7', 'Serpentine Belt', 0, 0, 1000, '1711090036_serpentine belt.jpg', 'Out of Stock', 'C2-R01'),
(58, '8', 'Headlights', 0, 5, 9500, '1711090047_Headlights.jpg', 'Active', 'C1-R02'),
(59, '10', 'Tail Lights', 0, 10, 8000, '1711090056_Tail Lights.jpg', 'In Stock', 'C1-R01'),
(60, '11', 'Fuel pump', 0, 8, 6000, '1711090065_Fuel pump.jpg', 'In Stock', 'C4-R01'),
(61, '14', 'Alternator', 0, 2, 15000, '1711090077_Alternator.jpg', 'In Stock', 'C4-R04');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchaseID` int(11) NOT NULL,
  `itemNumber` varchar(255) NOT NULL,
  `purchaseDate` date NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `unitPrice` float NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `vendorName` varchar(255) NOT NULL DEFAULT 'Test Vendor',
  `vendorID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchaseID`, `itemNumber`, `purchaseDate`, `itemName`, `unitPrice`, `quantity`, `vendorName`, `vendorID`) VALUES
(39, '1', '2024-03-01', 'Air Filter', 650, 25, 'CWORKS Sri Lanka ', 3),
(40, '2', '2024-03-08', 'Brake Pads', 880, 25, 'NRS Brakes', 4),
(41, '4', '2024-03-09', 'Energizer Battery', 2500, 15, 'Energizer Lanka Limited', 2),
(43, '5', '2024-03-12', 'Bridgestone Tyers', 5800, 10, 'N&N Enterprises', 5),
(50, '14', '2024-03-15', 'Alternator', 15000, 2, 'Advanced Auto Care', 6),
(51, '11', '2024-03-18', 'Fuel pump', 6000, 8, 'Dhanushka Engineering', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fullName`, `username`, `password`, `status`) VALUES
(6, 'Kavindu Ranasinge', 'ManagerKR', 'c481df102100f0b001851c1dd72289b1', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vendorID` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` int(11) NOT NULL,
  `phone2` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `district` varchar(30) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `createdOn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendorID`, `fullName`, `email`, `mobile`, `phone2`, `address`, `address2`, `city`, `district`, `status`, `createdOn`) VALUES
(1, 'Dhanushka Engineering', '', 114413563, 0, 'Block 7C,', 'Mandavila Rd,', 'Kotikawatta', 'Colombo', 'Active', '2022-02-20 05:48:44'),
(2, 'Energizer Lanka Limited', 'energizerlk@gmail.com', 112698032, 0, 'No 106,', 'Dharmapala Mw', 'Colombo 07', 'Colombo', 'Active', '2022-02-20 06:12:02'),
(3, 'CWORKS Sri Lanka', 'info@toyotsu.lk', 112904949, 0, 'No.359,', 'Negombo Road', 'Wattala', 'Gampaha', 'Active', '2022-02-20 06:28:33'),
(4, 'NRS Brakes', '', 312255340, 0, 'No 114/1,', 'Chilaw Road,', 'Wennappuwa,', 'Puttalam', 'Active', '2022-02-20 06:29:41'),
(6, 'Advanced Auto Care', 'nivanthaa@gmail.com', 772222677, 0, 'No;639/7,', 'Trincomalee Hwy,', 'Batticaloa', 'Batticaloa', 'Active', '2022-02-20 06:53:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`productID`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchaseID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendorID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchaseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
