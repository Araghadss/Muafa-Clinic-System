-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 24, 2025 at 07:18 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muafa`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `ID` int(11) NOT NULL,
  `PatientID` varchar(16) NOT NULL,
  `DoctorID` varchar(16) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`ID`, `PatientID`, `DoctorID`, `date`, `time`, `reason`, `status`) VALUES
(8, '1111', '3333', '2025-04-25', '22:16:00', 'somthing', 'DONE');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `ID` varchar(16) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `uniqueFileName` varchar(100) NOT NULL,
  `SpecialityID` varchar(100) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`ID`, `firstName`, `lastName`, `uniqueFileName`, `SpecialityID`, `emailAddress`, `password`) VALUES
('1111', 'Sara', 'almutari', '680a8649dabbddr.sara.png', '1111', 'sara@gmail.com', '$2y$10$a/0YC6sI5RvgwM8HgHX6NusgJJsqWiEVYJ1NXV5FWN3WSUIOgsbjG'),
('2222 ', 'Saleh', 'abdullah', '680a86f5d46dcdr.saleh.png', '2222', 'saleh@gmail.com', '$2y$10$f7jqrUqgSbu8hXEJczPSD.ISwaF2jvuXJPwhpe6R6vpAtairbafva'),
('3333', 'Wiliam', 'edoward', '680a87ae84077dr.rami.png', '3333', 'wiliam@gamil.com', '$2y$10$kRq/IJxmIZ5LjF/tpMgS8.FOKHci.QwLAuFf1nxsfgUOEhMbTIez2');

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `ID` varchar(16) NOT NULL,
  `MedicationName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`ID`, `MedicationName`) VALUES
('1', 'Aspirin'),
('2', 'Ibuprofen'),
('3', 'Paracetamol');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `ID` varchar(16) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `DOB` date NOT NULL,
  `emailAddress` text NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`ID`, `firstname`, `lastname`, `Gender`, `DOB`, `emailAddress`, `password`) VALUES
('1111', 'nouf', 'alshareef', 'Female', '2002-01-01', 'nouf@gamil.com', '$2y$10$zi0bYVZ2gLNhMfYaeXsjVeG.TbJ4U0CbpopiTpQhAcdtz97dQuFK2'),
('2222', 'raghad', 'ali', 'Female', '2015-06-11', 'raghad@gamil.com', '$2y$10$YitKnmKB2XFsdE5m/B1Jzu1PigUDPfRS1E.v4VvzOTeTK6evGPxMq'),
('3333', 'ibraheim', 'ali', 'Female', '2005-12-27', 'ibraheim@gamil.com', '$2y$10$ehRRpD0YqqyOt09zdna9bOOMY/WVB8w6CWn1YefGYFZQyCTesDzqK');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `ID` int(11) NOT NULL,
  `AppointmentID` varchar(16) NOT NULL,
  `MedicationID` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`ID`, `AppointmentID`, `MedicationID`) VALUES
(1, '1', '1'),
(2, '2', '2'),
(3, '3', '3'),
(4, '6', '2'),
(5, '6', '3'),
(6, '8', '2');

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `ID` varchar(16) NOT NULL,
  `Speciality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`ID`, `Speciality`) VALUES
('1111', 'Dentist'),
('2222', 'Dermatologist\r\n'),
('3333', 'Obstetrics & Gynec');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `uniqueFileName` (`uniqueFileName`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `MedicationID` (`MedicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
