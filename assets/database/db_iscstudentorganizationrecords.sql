-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 03:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_iscstudentorganizationrecords`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `adID` int(11) NOT NULL,
  `adFname` varchar(30) NOT NULL,
  `adLname` varchar(20) NOT NULL,
  `adMname` varchar(20) DEFAULT NULL,
  `adSuffix` varchar(20) DEFAULT NULL,
  `adSalutations` varchar(20) NOT NULL,
  `adPronouns` varchar(20) NOT NULL,
  `adBirthDate` date NOT NULL,
  `adDepartment` varchar(100) NOT NULL,
  `adSection` varchar(20) NOT NULL,
  `adInstitution` varchar(100) NOT NULL,
  `adMobileNo` int(11) NOT NULL,
  `adEmail` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcements`
--

CREATE TABLE `tbl_announcements` (
  `anID` int(11) NOT NULL,
  `anTitle` varchar(200) NOT NULL,
  `anContent` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applications`
--

CREATE TABLE `tbl_applications` (
  `apID` int(11) NOT NULL,
  `apFname` varchar(30) NOT NULL,
  `apLname` varchar(20) NOT NULL,
  `apMname` varchar(20) DEFAULT NULL,
  `apSuffix` varchar(20) DEFAULT NULL,
  `apSalutations` varchar(20) NOT NULL,
  `apPronouns` varchar(20) NOT NULL,
  `apBirthDate` date NOT NULL,
  `apDepartment` varchar(100) NOT NULL,
  `apSection` varchar(20) NOT NULL,
  `apInstitution` varchar(100) NOT NULL,
  `apMobileNo` int(11) NOT NULL,
  `apEmail` varchar(20) NOT NULL,
  `apStatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applicationstatus`
--

CREATE TABLE `tbl_applicationstatus` (
  `apStatusID` int(11) NOT NULL,
  `apStatusDesc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_applicationstatus`
--

INSERT INTO `tbl_applicationstatus` (`apStatusID`, `apStatusDesc`) VALUES
(1, 'Pending'),
(2, 'For Interview'),
(3, 'Approved'),
(4, 'Denied');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventattendancestatus`
--

CREATE TABLE `tbl_eventattendancestatus` (
  `evAttendanceStatusID` int(11) NOT NULL,
  `evAttendanceStatusDesc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventparticipantsattendance`
--

CREATE TABLE `tbl_eventparticipantsattendance` (
  `evID` int(11) NOT NULL,
  `mbID` int(11) NOT NULL,
  `evAttendanceStatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `evID` int(11) NOT NULL,
  `evTitle` varchar(100) NOT NULL,
  `evDesc` varchar(500) NOT NULL,
  `evDate` date NOT NULL,
  `evTime` time NOT NULL,
  `evVenue` varchar(100) NOT NULL,
  `evInstructor` varchar(100) NOT NULL,
  `evLink` varchar(200) NOT NULL,
  `evEvaluationLink` varchar(200) NOT NULL,
  `evStatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventstatus`
--

CREATE TABLE `tbl_eventstatus` (
  `evStatusID` int(11) NOT NULL,
  `evStatusDesc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `fbID` int(11) NOT NULL,
  `fbContent` varchar(500) NOT NULL,
  `mbID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_members`
--

CREATE TABLE `tbl_members` (
  `mbID` int(11) NOT NULL,
  `mbFname` varchar(30) NOT NULL,
  `mbLname` varchar(20) NOT NULL,
  `mbMname` varchar(20) DEFAULT NULL,
  `mbSuffix` varchar(20) DEFAULT NULL,
  `mbSalutations` varchar(20) NOT NULL,
  `mbPronouns` varchar(20) NOT NULL,
  `mbBirthDate` date NOT NULL,
  `mbDepartment` varchar(100) NOT NULL,
  `mbSection` varchar(20) NOT NULL,
  `mbInstitution` varchar(100) NOT NULL,
  `mbMobileNo` int(11) NOT NULL,
  `mbEmail` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_newsletter`
--

CREATE TABLE `tbl_newsletter` (
  `nlID` int(11) NOT NULL,
  `nlEmail` varchar(100) NOT NULL,
  `mbID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sponsors`
--

CREATE TABLE `tbl_sponsors` (
  `spID` int(11) NOT NULL,
  `spName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`adID`);

--
-- Indexes for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  ADD PRIMARY KEY (`anID`);

--
-- Indexes for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  ADD PRIMARY KEY (`apID`),
  ADD KEY `FK_Applications_Status` (`apStatusID`);

--
-- Indexes for table `tbl_applicationstatus`
--
ALTER TABLE `tbl_applicationstatus`
  ADD PRIMARY KEY (`apStatusID`);

--
-- Indexes for table `tbl_eventattendancestatus`
--
ALTER TABLE `tbl_eventattendancestatus`
  ADD PRIMARY KEY (`evAttendanceStatusID`);

--
-- Indexes for table `tbl_eventparticipantsattendance`
--
ALTER TABLE `tbl_eventparticipantsattendance`
  ADD KEY `FK_EventAttendance_Event` (`evID`),
  ADD KEY `FK_EventAttendance_Member` (`mbID`),
  ADD KEY `FK_EventAttendance_Status` (`evAttendanceStatusID`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`evID`),
  ADD KEY `FK_Events_Status` (`evStatusID`);

--
-- Indexes for table `tbl_eventstatus`
--
ALTER TABLE `tbl_eventstatus`
  ADD PRIMARY KEY (`evStatusID`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`fbID`),
  ADD KEY `FK_Feedback_Members` (`mbID`);

--
-- Indexes for table `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`mbID`);

--
-- Indexes for table `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  ADD PRIMARY KEY (`nlID`),
  ADD KEY `FK_Newsletter_Members` (`mbID`);

--
-- Indexes for table `tbl_sponsors`
--
ALTER TABLE `tbl_sponsors`
  ADD PRIMARY KEY (`spID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  MODIFY `apID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  ADD CONSTRAINT `FK_Applications_Status` FOREIGN KEY (`apStatusID`) REFERENCES `tbl_applicationstatus` (`apStatusID`);

--
-- Constraints for table `tbl_eventparticipantsattendance`
--
ALTER TABLE `tbl_eventparticipantsattendance`
  ADD CONSTRAINT `FK_EventAttendance_Event` FOREIGN KEY (`evID`) REFERENCES `tbl_events` (`evID`),
  ADD CONSTRAINT `FK_EventAttendance_Member` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`),
  ADD CONSTRAINT `FK_EventAttendance_Status` FOREIGN KEY (`evAttendanceStatusID`) REFERENCES `tbl_eventattendancestatus` (`evAttendanceStatusID`);

--
-- Constraints for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD CONSTRAINT `FK_Events_Status` FOREIGN KEY (`evStatusID`) REFERENCES `tbl_eventstatus` (`evStatusID`);

--
-- Constraints for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD CONSTRAINT `FK_Feedback_Members` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`);

--
-- Constraints for table `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  ADD CONSTRAINT `FK_Newsletter_Members` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
