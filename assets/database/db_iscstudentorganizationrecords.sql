-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 07:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
  `adMobileNo` varchar(20) NOT NULL,
  `adEmail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`adID`, `adFname`, `adLname`, `adMname`, `adSuffix`, `adSalutations`, `adPronouns`, `adBirthDate`, `adDepartment`, `adSection`, `adInstitution`, `adMobileNo`, `adEmail`) VALUES
(1, 'Anne Ritchel', 'Sumague', 'De Guzman', '', 'ms', 'she', '2004-10-03', 'bsit', '3-1', 'PUPSTC', '+639948669327', 'anneritcheldsumague@iskolarngbayan.pup.edu.ph');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcements`
--

CREATE TABLE `tbl_announcements` (
  `anID` int(11) NOT NULL,
  `anTitle` varchar(200) NOT NULL,
  `anContent` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_announcements`
--

INSERT INTO `tbl_announcements` (`anID`, `anTitle`, `anContent`) VALUES
(1, 'Trial Announcement', 'This announcement informs members about the upcoming trial period, providing important details such as the schedule, purpose, and guidelines. It helps set expectations, encourage participation, and ensure everyone is prepared for the trial.');

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
  `apMobileNo` varchar(20) NOT NULL,
  `apEmail` varchar(100) NOT NULL,
  `apStatusID` int(11) NOT NULL,
  `interviewSent` tinyint(1) NOT NULL DEFAULT 0
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

--
-- Dumping data for table `tbl_eventattendancestatus`
--

INSERT INTO `tbl_eventattendancestatus` (`evAttendanceStatusID`, `evAttendanceStatusDesc`) VALUES
(1, 'Present'),
(2, 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventparticipantsattendance`
--

CREATE TABLE `tbl_eventparticipantsattendance` (
  `evID` int(11) NOT NULL,
  `mbID` int(11) NOT NULL,
  `evAttendanceStatusID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_eventparticipantsattendance`
--

INSERT INTO `tbl_eventparticipantsattendance` (`evID`, `mbID`, `evAttendanceStatusID`) VALUES
(2, 1, 1);

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
  `evStatusID` int(11) NOT NULL,
  `isHidden` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`evID`, `evTitle`, `evDesc`, `evDate`, `evTime`, `evVenue`, `evInstructor`, `evLink`, `evEvaluationLink`, `evStatusID`, `isHidden`) VALUES
(4, 'Final Presentation of the System', 'This presentation showcases the completed system, highlighting its core features, functionality, and overall workflow. It demonstrates how users interact with the system, how data is processed, and how the system meets its intended objectives. The presentation also emphasizes the system’s effectiveness, usability, and readiness for real-world implementation.', '2026-01-15', '09:00:00', 'PUPSTC - Comlab 1', 'Dr. Melanie Castillo, Sir. CJ De Claro, and Sir Aris Dela Rea', 'https://forms.gle/SiChvt64Wog52Deb9', 'https://forms.gle/SiChvt64Wog52Deb9', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_eventstatus`
--

CREATE TABLE `tbl_eventstatus` (
  `evStatusID` int(11) NOT NULL,
  `evStatusDesc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_eventstatus`
--

INSERT INTO `tbl_eventstatus` (`evStatusID`, `evStatusDesc`) VALUES
(1, 'Upcoming'),
(2, 'Ongoing'),
(3, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `fbID` int(11) NOT NULL,
  `fbContent` varchar(500) NOT NULL,
  `mbID` int(11) NOT NULL,
  `mbMobileNo` varchar(20) NOT NULL,
  `mbEmail` varchar(100) NOT NULL,
  `fbWebsiteName` varchar(100) NOT NULL,
  `fbCategory` varchar(50) NOT NULL,
  `fbName` varchar(100) NOT NULL,
  `fbStatus` varchar(20) NOT NULL DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`fbID`, `fbContent`, `mbID`, `mbMobileNo`, `mbEmail`, `fbWebsiteName`, `fbCategory`, `fbName`, `fbStatus`) VALUES
(28, 'Hello, this is a Trial feedback message.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Feedback', 'Anne Sumague', 'received'),
(29, 'Hello, this is a Trial Complaint message.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Complaint', 'Anne Sumague', 'received'),
(30, 'Hello, this is a Trial Report message.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Report', 'Anne Sumague', 'received'),
(31, 'Hello, this is a Trial feedback message.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Feedback', 'Anne Sumague', 'received'),
(32, 'Hello, this is a Trial Complaint message 2.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Complaint', 'Anne Sumague', 'open'),
(33, 'Hello, this is a Trial feedback message 2.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Feedback', 'Anne Sumague', 'open'),
(34, 'Hello, this is a Trial Report message 2.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Report', 'Anne Sumague', 'open'),
(35, 'Hello, this is a Trial Report message 2.', 3, '09948669327', 'anneritchel.deguzman.sumague@gmail.com', 'ISC Organization System', 'Report', 'Anne Sumague', 'received');

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
  `mbMobileNo` varchar(20) NOT NULL,
  `mbEmail` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_members`
--

INSERT INTO `tbl_members` (`mbID`, `mbFname`, `mbLname`, `mbMname`, `mbSuffix`, `mbSalutations`, `mbPronouns`, `mbBirthDate`, `mbDepartment`, `mbSection`, `mbInstitution`, `mbMobileNo`, `mbEmail`) VALUES
(3, 'Anne ', 'Sumague', 'De Guzman', '', 'Ms', 'She-Her', '2004-10-03', 'BSIT', '3-1', 'Polytechnic University of the Philippines', '09948669327', 'anneritchel.deguzman.sumague@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_newsletter`
--

CREATE TABLE `tbl_newsletter` (
  `nlID` int(11) NOT NULL,
  `nlEmail` varchar(100) NOT NULL,
  `mbID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_newsletter`
--

INSERT INTO `tbl_newsletter` (`nlID`, `nlEmail`, `mbID`) VALUES
(1, 'anneritchel.deguzman.sumague@gmail.com', 3);

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
  ADD KEY `apStatusID` (`apStatusID`);

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
  ADD KEY `evID` (`evID`),
  ADD KEY `mbID` (`mbID`),
  ADD KEY `evAttendanceStatusID` (`evAttendanceStatusID`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`evID`),
  ADD KEY `evStatusID` (`evStatusID`);

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
  ADD KEY `mbID` (`mbID`),
  ADD KEY `fk_feedback_mobile` (`mbMobileNo`),
  ADD KEY `fk_feedback_email` (`mbEmail`);

--
-- Indexes for table `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`mbID`),
  ADD UNIQUE KEY `uq_mbMobileNo` (`mbMobileNo`),
  ADD UNIQUE KEY `uq_mbEmail` (`mbEmail`);

--
-- Indexes for table `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  ADD PRIMARY KEY (`nlID`),
  ADD KEY `mbID` (`mbID`);

--
-- Indexes for table `tbl_sponsors`
--
ALTER TABLE `tbl_sponsors`
  ADD PRIMARY KEY (`spID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `adID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_announcements`
--
ALTER TABLE `tbl_announcements`
  MODIFY `anID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  MODIFY `apID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `evID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `fbID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `mbID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  MODIFY `nlID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sponsors`
--
ALTER TABLE `tbl_sponsors`
  MODIFY `spID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  ADD CONSTRAINT `tbl_applications_ibfk_1` FOREIGN KEY (`apStatusID`) REFERENCES `tbl_applicationstatus` (`apStatusID`);

--
-- Constraints for table `tbl_eventparticipantsattendance`
--
ALTER TABLE `tbl_eventparticipantsattendance`
  ADD CONSTRAINT `tbl_eventparticipantsattendance_ibfk_1` FOREIGN KEY (`evID`) REFERENCES `tbl_events` (`evID`),
  ADD CONSTRAINT `tbl_eventparticipantsattendance_ibfk_2` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`),
  ADD CONSTRAINT `tbl_eventparticipantsattendance_ibfk_3` FOREIGN KEY (`evAttendanceStatusID`) REFERENCES `tbl_eventattendancestatus` (`evAttendanceStatusID`);

--
-- Constraints for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD CONSTRAINT `tbl_events_ibfk_1` FOREIGN KEY (`evStatusID`) REFERENCES `tbl_eventstatus` (`evStatusID`);

--
-- Constraints for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD CONSTRAINT `fk_feedback_email` FOREIGN KEY (`mbEmail`) REFERENCES `tbl_members` (`mbEmail`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feedback_mobile` FOREIGN KEY (`mbMobileNo`) REFERENCES `tbl_members` (`mbMobileNo`),
  ADD CONSTRAINT `tbl_feedback_ibfk_1` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`);

--
-- Constraints for table `tbl_newsletter`
--
ALTER TABLE `tbl_newsletter`
  ADD CONSTRAINT `tbl_newsletter_ibfk_1` FOREIGN KEY (`mbID`) REFERENCES `tbl_members` (`mbID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
