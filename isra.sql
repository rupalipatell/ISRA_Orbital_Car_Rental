-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 31, 2025 at 10:46 PM
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
-- Database: `isra`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(2, 'Shreya', 'shreya@1234', '$2y$10$9EFrW1QrrM/G0rwZOkzJeu3fCRGCMrfJl/HEwLIMNHTqQ8iRMzTiW'),
(3, 'Divyansh', 'div@0312', '$2y$10$efvpFn5LH79Ll1PYOUVExOFgnz4y5IGVcei7fD3xL2AT8qTQWneXS');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BookingID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `BookingDate` datetime DEFAULT current_timestamp(),
  `StartDateTime` datetime NOT NULL,
  `EndDateTime` datetime NOT NULL,
  `TotalHours` decimal(5,2) DEFAULT NULL,
  `TotalAmount` decimal(10,2) DEFAULT NULL,
  `PaymentStatus` enum('Pending','Completed') DEFAULT 'Pending',
  `status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `CustomerID`, `VehicleID`, `BookingDate`, `StartDateTime`, `EndDateTime`, `TotalHours`, `TotalAmount`, `PaymentStatus`, `status`) VALUES
(1, 2, 3, '2025-06-01 00:49:21', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 999.99, 255824.00, 'Pending', ''),
(2, 2, 3, '2025-06-01 00:50:27', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 999.99, 255824.00, 'Pending', ''),
(3, 2, 3, '2025-06-01 00:53:37', '2025-12-15 12:30:00', '2025-12-16 13:00:00', 24.50, 196.00, 'Pending', ''),
(4, 2, 3, '2025-06-01 01:42:48', '2005-05-23 16:06:00', '2006-04-25 23:16:00', 999.99, 64761.33, 'Pending', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Address` text DEFAULT NULL,
  `DrivingLicenseNumber` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `Name`, `Email`, `PhoneNumber`, `Address`, `DrivingLicenseNumber`, `password`) VALUES
(2, 'Pranjali', 'prn@234', '7894', 'abchfe9iwo', '67328', '$2y$10$JVqNEzzs.CArmun3CcfaveADKwTdrcat5IvbKho7e92Ax0h0ULkpe'),
(3, 'nikki', 'nik@456', '823672', 'xyz', 't8732', '$2y$10$p90bWQdNUqjecqGwjzYEIuuc8Y3BoKMaEFRTb11yo3caG09Ciz4um');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `BookingID` int(11) DEFAULT NULL,
  `PaymentDate` datetime DEFAULT current_timestamp(),
  `PaymentAmount` decimal(10,2) NOT NULL,
  `PaymentMethod` enum('Card','Cash','UPI','NetBanking') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(50) DEFAULT 'Booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `ReviewID` int(11) NOT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `VehicleID` int(11) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL CHECK (`Rating` >= 1 and `Rating` <= 5),
  `Comment` text DEFAULT NULL,
  `ReviewDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `VehicleID` int(11) NOT NULL,
  `Brand` varchar(50) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `RegistrationNumber` varchar(20) NOT NULL,
  `Capacity` int(11) NOT NULL,
  `RentPerHour` decimal(10,2) NOT NULL,
  `AvailableStatus` tinyint(1) DEFAULT 1,
  `Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`VehicleID`, `Brand`, `Model`, `RegistrationNumber`, `Capacity`, `RentPerHour`, `AvailableStatus`, `Image`) VALUES
(3, 'bgruie', 'd eui', 'bcuid', 9, 8.00, 1, NULL),
(4, 'xyx', 'hde', '6239', 9, 6.00, 1, NULL),
(5, 'HII', 'fduie', '732', 7, 98.00, 1, NULL),
(6, 'hdgdcuids', 'cjkd', '64', 89, 6.00, 1, '683aad61243db_Screenshot 2025-04-21 173515.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `DrivingLicenseNumber` (`DrivingLicenseNumber`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `BookingID` (`BookingID`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `VehicleID` (`VehicleID`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`VehicleID`),
  ADD UNIQUE KEY `RegistrationNumber` (`RegistrationNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BookingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `VehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`VehicleID`) REFERENCES `vehicles` (`VehicleID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`BookingID`) REFERENCES `booking` (`BookingID`) ON DELETE CASCADE;

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `customers` (`CustomerID`),
  ADD CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `vehicles` (`VehicleID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`VehicleID`) REFERENCES `vehicles` (`VehicleID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
