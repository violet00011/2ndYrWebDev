-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: May 27, 2025 at 06:02 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38820433_gatesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `gate`
--

CREATE TABLE `gate` (
  `GateID` varchar(100) NOT NULL,
  `Campus` varchar(50) NOT NULL,
  `GateNumber` varchar(10) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gate`
--

INSERT INTO `gate` (`GateID`, `Campus`, `GateNumber`, `Address`, `Status`) VALUES
('Bustos_1', 'Bustos', '1', 'L. Mercado St. Corner C.L. Hilario St. Bustos, Bulacan – 3007, Philippines', 'Entry/Exit'),
('Hagonoy_1', 'Hagonoy', '1', 'Iba-Carillo, Hagonoy Bulacan – 3002, Philippines', 'Entry/Exit'),
('Malolos_1', 'Malolos', '1', 'MacArthur Hwy, Malolos, Bulacan, Philippines.', 'Entry/Exit'),
('Malolos_2', 'Malolos', '2', 'MacArthur Hwy, Malolos, Bulacan, Philippines.', 'Entry/Exit'),
('Malolos_3', 'Malolos', '3', 'MacArthur Hwy, Malolos, Bulacan, Philippines.', 'Exit'),
('Malolos_4', 'Malolos', '4', 'MacArthur Hwy, Malolos, Bulacan, Philippines.', 'Entry'),
('Meneses_1', 'Meneses', '1', 'TJS Matungao, Bulakan Bulacan – 3017, Philippines', 'Entry/Exit'),
('SanRafael_1', 'San Rafael', '1', 'Bypass Road, Baranggay San Roque, San Rafael Bulacan – Philippines', 'Entry/Exit'),
('Sarmiento_1', 'Sarmiento', '1', 'University Heights, Brgy. Kaypian, City of San Jose del Monte Bulacan, 3023, Philippines', 'Entry/Exit');

-- --------------------------------------------------------

--
-- Table structure for table `guard`
--

CREATE TABLE `guard` (
  `GuardID` int(100) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) NOT NULL,
  `ShiftDays` varchar(60) NOT NULL,
  `ShiftHoursStart` time NOT NULL,
  `ShiftHoursEnd` time NOT NULL,
  `GateID` varchar(40) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Passward` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guard`
--

INSERT INTO `guard` (`GuardID`, `LastName`, `FirstName`, `MiddleName`, `ShiftDays`, `ShiftHoursStart`, `ShiftHoursEnd`, `GateID`, `Username`, `Passward`) VALUES
(1, 'Dela Cruz', 'Juan', 'Santos', 'MTWTHFS', '00:00:00', '00:00:00', 'Malolos_1', 'JuanSDC', 'JuanPogi'),
(2, 'Lee', 'Jack', 'Landayan', 'TTHS', '12:00:00', '24:00:00', 'Sarmiento_1', 'Jack', 'Jack'),
(17, 'Reyes', 'Armando', 'Ramos', 'MWF', '04:00:00', '12:59:00', 'Bustos_1', 'Armando', 'Armando'),
(18, 'Cruz', 'John', 'Loyde', 'TTHS', '12:00:00', '24:00:00', 'Hagonoy_1', 'John', 'John'),
(19, 'Pascual', 'Piolo', 'Pinto', 'MWF', '24:00:00', '08:00:00', 'Maalolos_2', 'Piolo', 'Piolo'),
(20, 'Mulach', 'Aga', 'Gabi', 'TTHSSU', '09:00:00', '17:00:00', 'Malolos_3', 'Aga', 'Aga'),
(21, 'Wick', 'Johnny', 'hehe', 'MWFSU', '12:00:00', '20:00:00', 'Malolos_4', 'Johnny', 'Johnny'),
(22, 'Reid', 'James', 'idk', 'TTHS', '24:00:00', '08:00:00', 'Meneses_1', 'James', 'James'),
(23, 'Elordi', 'Jacob', 'hha', 'TTHS', '12:00:00', '24:00:00', 'SanRafael_1', 'Jacob', 'Jacob');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) NOT NULL,
  `Position` varchar(60) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `LastName`, `FirstName`, `MiddleName`, `Position`, `Email`, `Username`, `Password`, `image`) VALUES
(3, 'VIllanueva', 'Armaine', 'Alajar', 'Staff', 'armaine.villanieva@gmail.com', 'StaffArmaine', 'Armaine', 'uploads/681a1a46264655.55025277.png'),
(6, 'reyes', 'visky', 'santos', 'Professor', 'fa@gmail.com', 'Armaine', 'Armaine', 'uploads/681b9d6c406515.26656075.png'),
(7, 'Admin', 'Admin', 'Admin', 'Admin', 'Admin@gmail.com', 'Admin', 'Admin', ''),
(8, 'Targaryen ', 'Daenerys', 'Stormborn', 'Staff', 'MotherofDrag@gmail.com', 'Daenerys', 'Daenerys', 'uploads/6834d36131b6d5.45607817.png'),
(9, 'Stark', 'Arya', 'Tully', 'Staff', 'Arya@gmail.com', 'Arya', 'Arya', 'uploads/6834d41ecb1738.61458440.png');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `VehicleID` int(255) NOT NULL,
  `PlateNumber` varchar(50) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Model` varchar(50) NOT NULL,
  `OwnerID` int(60) NOT NULL,
  `Status` varchar(40) NOT NULL,
  `DateRegistered` date NOT NULL DEFAULT current_timestamp(),
  `PlateNumberImage` varchar(255) NOT NULL,
  `Reg_Stat` varchar(100) NOT NULL DEFAULT 'Approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`VehicleID`, `PlateNumber`, `Type`, `Model`, `OwnerID`, `Status`, `DateRegistered`, `PlateNumberImage`, `Reg_Stat`) VALUES
(2, 'ABC 1242', 'Motorcycle', 'Honda Click', 1, 'Active', '2025-05-01', 'uploads/Screenshot 2025-05-27 042918.png', 'Approved'),
(14, 'CAT 1242', 'Motorcycle', 'Honda Click', 6, 'Active', '2025-05-06', 'uploads/Screenshot 2025-05-27 043030.png', 'Approved'),
(16, 'IVY 456', 'Pickup Truck', 'HILUX', 2, 'Active', '2025-05-26', 'uploads/Screenshot 2025-05-27 043252.png', 'Approved'),
(17, 'IVY 789', 'SUV', 'Honda CR-V', 2, 'Active', '2025-05-26', 'uploads/Screenshot 2025-05-27 043415.png', 'Approved'),
(18, 'IVY 999', 'Hatchback', 'Subaru Impreza', 2, 'Active', '2025-05-26', 'uploads/Screenshot 2025-05-27 043546.png', 'Approved'),
(19, 'ARM 123', 'Sedan', 'Mazda 3', 6, 'Active', '2025-05-26', 'uploads/Screenshot 2025-05-27 043805.png', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_approval`
--

CREATE TABLE `vehicle_approval` (
  `ID` int(11) NOT NULL,
  `PlateNumber` varchar(20) NOT NULL,
  `Type` varchar(100) NOT NULL,
  `Model` varchar(100) NOT NULL,
  `OwnerID` int(100) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `DateRegistered` date NOT NULL DEFAULT current_timestamp(),
  `PlateNumberImage` varbinary(100) NOT NULL,
  `Reg_Stat` varchar(100) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_approval`
--

INSERT INTO `vehicle_approval` (`ID`, `PlateNumber`, `Type`, `Model`, `OwnerID`, `Status`, `DateRegistered`, `PlateNumberImage`, `Reg_Stat`) VALUES
(1, 'CAR1234', 'Sedan', 'HOnda Civic', 2, 'Out', '2025-05-01', '', 'Approved'),
(2, '1111111', 'SUV', 'CRV', 1, '', '2025-05-01', 0x75706c6f6164732f53637265656e73686f7420323032352d30332d3035203130353433372e706e67, 'Denied'),
(3, 'REG 457', 'Pickup Truck', 'hILUX', 1, '', '2025-05-01', 0x75706c6f6164732f53637265656e73686f7420323032342d31312d3237203039323830352e706e67, 'Approved'),
(4, 'ABC 1242', 'Sedan', 'Honda Click', 1, '', '2025-05-01', 0x75706c6f6164732f6267696e6465782e706e67, 'Approved'),
(5, 'CAT 1242', 'Motorcycle', 'Honda Click', 6, '', '2025-05-06', 0x75706c6f6164732f52656420616e642077686974652070686f746f67726170686963206d697373696e672070657420706f737465722e706e67, 'Approved'),
(6, 'ABC 1242', 'Hatchback', 'WIGO', 6, '', '2025-05-19', 0x75706c6f6164732f707269736f6e62672e6a7067, 'Pending'),
(7, 'IVY 456', 'Pickup Truck', 'HILUX', 2, '', '2025-05-26', 0x75706c6f6164732f53637265656e73686f7420323032352d30352d3035203138303735372e706e67, 'Approved'),
(8, 'IVY 789', 'SUV', 'Honda CR-V', 2, '', '2025-05-26', 0x75706c6f6164732f53637265656e73686f7420323032352d30352d3131203233303532392e706e67, 'Approved'),
(9, 'IVY 999', 'Hatchback', 'Subaru Impreza', 2, '', '2025-05-26', 0x75706c6f6164732f53637265656e73686f7420323032352d30342d3239203136353834332e706e67, 'Approved'),
(10, 'IVY 908', 'Crossover', 'Mazda CX-5', 2, '', '2025-05-26', 0x75706c6f6164732f53637265656e73686f7420323032342d31312d3230203137343335362e706e67, 'Pending'),
(11, 'ARM 123', 'Sedan', 'Mazda 3', 6, '', '2025-05-26', 0x75706c6f6164732f63617069746f6c2e706e67, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_log`
--

CREATE TABLE `vehicle_log` (
  `LogID` int(255) NOT NULL,
  `VehicleID` int(50) NOT NULL,
  `GateID` varchar(50) NOT NULL,
  `GuardID` int(50) NOT NULL,
  `TimeIn` datetime(6) NOT NULL,
  `TimeOut` datetime(6) NOT NULL,
  `Status` text NOT NULL DEFAULT 'Inside'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_log`
--

INSERT INTO `vehicle_log` (`LogID`, `VehicleID`, `GateID`, `GuardID`, `TimeIn`, `TimeOut`, `Status`) VALUES
(2, 1, 'Malolos_2', 1, '2025-05-01 12:03:56.000000', '2025-04-25 23:21:00.000000', 'In'),
(25, 0, 'Malolos_1', 1, '2025-05-01 00:25:00.000000', '2025-04-30 23:06:00.000000', 'In'),
(29, 0, 'Meneses_1', 1, '2025-05-14 23:56:00.000000', '2025-05-07 02:21:36.000000', 'Out'),
(30, 0, 'Malolos_1', 1, '2025-05-07 02:21:42.000000', '2025-05-07 02:21:34.000000', 'In'),
(31, 0, 'Malolos_1', 1, '2025-05-07 02:21:45.000000', '2025-05-07 02:21:28.000000', 'In'),
(32, 0, 'Malolos_1', 1, '2025-05-01 00:25:21.000000', '0000-00-00 00:00:00.000000', 'In'),
(34, 2, 'MLLS001', 1, '2025-05-07 02:21:10.000000', '2025-05-07 02:21:22.000000', 'Out'),
(35, 98, 'MLLS001', 1, '2025-05-09 01:58:37.000000', '2025-05-09 01:58:55.000000', 'Out'),
(36, 0, 'Malolos_1', 1, '2025-05-19 04:12:36.000000', '2025-05-19 04:12:39.000000', 'Out'),
(37, 14, 'MLLS001', 1, '2025-05-19 04:17:57.000000', '2025-05-19 04:18:14.000000', 'Out'),
(38, 18, 'Malolos_1', 1, '2025-05-26 11:59:41.000000', '0000-00-00 00:00:00.000000', 'Inside'),
(39, 18, 'Malolos_1', 1, '2025-05-26 11:59:59.000000', '0000-00-00 00:00:00.000000', 'Inside'),
(40, 0, 'SanRafael_1', 23, '0000-00-00 00:00:00.000000', '0000-00-00 00:00:00.000000', 'Approved'),
(41, 18, 'SanRafael_1', 23, '2025-05-26 13:26:29.000000', '0000-00-00 00:00:00.000000', 'Inside'),
(42, 17, 'SanRafael_1', 23, '2025-05-26 13:26:26.000000', '0000-00-00 00:00:00.000000', 'Inside'),
(43, 0, 'Hagonoy_1', 18, '2025-05-26 13:17:38.000000', '2025-05-26 13:13:36.000000', 'Inside'),
(44, 18, 'Hagonoy_1', 18, '2025-05-26 12:38:51.916225', '2025-05-26 13:13:20.000000', 'Outside'),
(45, 18, 'Hagonoy_1', 18, '2025-05-26 12:46:35.550670', '2025-05-26 13:16:43.000000', 'Outside'),
(46, 17, 'Hagonoy_1', 18, '2025-05-26 12:47:09.994563', '2025-05-26 13:16:39.000000', 'Outside'),
(47, 17, 'Hagonoy_1', 18, '2025-05-26 12:51:43.939619', '2025-05-26 13:16:34.000000', 'Outside'),
(48, 18, 'Hagonoy_1', 18, '2025-05-26 13:00:37.784481', '2025-05-26 13:14:28.000000', 'Outside'),
(49, 17, 'Hagonoy_1', 18, '2025-05-26 13:02:28.367232', '2025-05-26 13:13:03.000000', 'Outside'),
(50, 18, 'Hagonoy_1', 18, '2025-05-26 13:25:51.000000', '0000-00-00 00:00:00.000000', 'Inside'),
(51, 17, 'Hagonoy_1', 18, '2025-05-26 13:25:48.000000', '2025-05-26 13:25:54.000000', 'Outside'),
(52, 18, 'Hagonoy_1', 18, '2025-05-26 13:25:41.435555', '0000-00-00 00:00:00.000000', 'Inside'),
(53, 14, 'Sarmiento_1', 2, '2025-05-26 13:27:57.082807', '2025-05-26 13:48:52.000000', 'Outside'),
(54, 17, 'Malolos_1', 1, '2025-05-26 13:43:23.965087', '2025-05-26 13:45:35.000000', 'Outside');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owner`
--

CREATE TABLE `vehicle_owner` (
  `OwnerID` int(255) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) NOT NULL,
  `Department` varchar(60) NOT NULL,
  `ContactNumber` int(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Position` varchar(30) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_owner`
--

INSERT INTO `vehicle_owner` (`OwnerID`, `LastName`, `FirstName`, `MiddleName`, `Department`, `ContactNumber`, `Email`, `Position`, `Username`, `Password`, `image_path`) VALUES
(1, 'Villanueva', 'Armaine', 'Alajar', 'CICT', 2147483647, 'armaine@gmail.com', 'Staff', 'OwnerArmaine', 'Armaine', 'Assets/ArmaineAV.jpg'),
(2, 'Malaï¿½gen', 'Ivy', 'Kalaw', 'COS', 2147483647, 'avheekm@gmail.com', 'Actuary', 'Ibyang', 'IbyangGanda', ''),
(6, 'Villanueva', 'Armaine', 'Alajar', 'CICT', 2147483647, 'armaine.villanieva@gmail.com', 'Staff', 'ArmaineAV', 'Armaine', 'uploads/1746536639_me.png'),
(7, 'Snow', 'Jon', 'Targaryen', 'CBEA', 2147483647, 'JonSn123@gmail.com', 'Staff', 'Jon', 'Jon', 'uploads/1748292282_Screenshot 2025-05-27 044427.png');

-- --------------------------------------------------------

--
-- Table structure for table `visitor`
--

CREATE TABLE `visitor` (
  `VisitorID` int(255) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) NOT NULL,
  `ContactNumber` int(11) NOT NULL,
  `ScheduledVisit` datetime(6) NOT NULL,
  `VehicleModel` varchar(100) NOT NULL,
  `PlateNumber` varchar(10) NOT NULL,
  `GateID` varchar(100) NOT NULL,
  `Purpose` text NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor`
--

INSERT INTO `visitor` (`VisitorID`, `LastName`, `FirstName`, `MiddleName`, `ContactNumber`, `ScheduledVisit`, `VehicleModel`, `PlateNumber`, `GateID`, `Purpose`, `Status`) VALUES
(1, 'reyes', 'visky', 'santos', 917818268, '2025-05-15 14:10:00.000000', 'Honda click haha', 'MTR1234', 'Malolos_1', 'MEETING ATTEND CHHCUHCUHHC', 'Approved'),
(2, 'Villanueva', 'Armaine', 'Alajar', 2147483647, '2025-05-06 03:12:00.000000', 'Honda Civic', '1234 567', 'Bustos_1', 'Pick uo documents', 'Approved'),
(4, 'Villanueva', 'Armaine', 'Alajar', 2147483647, '2025-04-30 19:37:00.000000', 'Honda Civic', 'ABC 123', 'Malolos_2', 'asfa', 'Approved'),
(7, 'reyes', 'visky', 'santos', 95482164, '2025-05-01 14:10:00.000000', 'Mitsubishi mirage', 'ARM 1824', 'Malolos_1', 'uuuuu', 'Approved'),
(9, 'Villanueva', 'Armaine', 'Alajar', 2147483647, '2025-05-08 02:24:00.000000', 'Honda Civic', 'ABC 123', 'Malolos_1', '123', 'Approved'),
(11, 'VILLANUEVA', 'GINA', 'ALAJAR', 2147483647, '2025-05-20 07:15:00.000000', 'Honda Civic', 'ERR 122', 'Sarmiento_1', 'May kukunin lang', 'Denied'),
(12, 'Cena', 'John', 'haha', 2147483647, '2025-05-28 07:00:00.000000', 'Mitsubishi Mirage', 'EDO155', 'SanRafael_1', 'Class', 'Approved'),
(13, 'Dizon', 'Jun', 'dhaha', 2147483647, '2025-05-28 07:30:00.000000', 'Honda Civic', 'OUY 345', 'Hagonoy_1', 'Speaker', 'Approved'),
(14, 'Uno', 'Uno', 'Uno', 2147483647, '2025-05-27 07:40:00.000000', 'Honda Civic', 'POM 123', 'Malolos_1', 'Hmmm...', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gate`
--
ALTER TABLE `gate`
  ADD PRIMARY KEY (`GateID`);

--
-- Indexes for table `guard`
--
ALTER TABLE `guard`
  ADD PRIMARY KEY (`GuardID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`),
  ADD UNIQUE KEY `Email` (`Username`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`VehicleID`);

--
-- Indexes for table `vehicle_approval`
--
ALTER TABLE `vehicle_approval`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `vehicle_log`
--
ALTER TABLE `vehicle_log`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `vehicle_owner`
--
ALTER TABLE `vehicle_owner`
  ADD PRIMARY KEY (`OwnerID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`VisitorID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guard`
--
ALTER TABLE `guard`
  MODIFY `GuardID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `VehicleID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `vehicle_approval`
--
ALTER TABLE `vehicle_approval`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vehicle_log`
--
ALTER TABLE `vehicle_log`
  MODIFY `LogID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `vehicle_owner`
--
ALTER TABLE `vehicle_owner`
  MODIFY `OwnerID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visitor`
--
ALTER TABLE `visitor`
  MODIFY `VisitorID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
