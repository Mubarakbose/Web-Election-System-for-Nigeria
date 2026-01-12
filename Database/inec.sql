-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 06:02 AM
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
-- Database: `inec`
--

-- --------------------------------------------------------

--
-- Table structure for table `contestant`
--

CREATE TABLE `contestant` (
  `ContestantID` int(11) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `OtherNames` varchar(30) NOT NULL,
  `PartyName` varchar(10) NOT NULL,
  `State` varchar(25) NOT NULL,
  `Position` varchar(25) NOT NULL DEFAULT 'President',
  `SenateZone` varchar(20) NOT NULL,
  `FedConstituency` varchar(200) NOT NULL,
  `StateConstituency` varchar(200) NOT NULL,
  `Image` blob NOT NULL,
  `Votes` int(10) DEFAULT 0,
  `ResultMode` varchar(10) NOT NULL DEFAULT 'Private'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contestant`
--

INSERT INTO `contestant` (`ContestantID`, `FirstName`, `OtherNames`, `PartyName`, `State`, `Position`, `SenateZone`, `FedConstituency`, `StateConstituency`, `Image`, `Votes`, `ResultMode`) VALUES
(1003, 'Usman', 'Bayero Nafada', 'PDP', 'Gombe', 'Governor', 'North', 'Nafada/Kashere', 'Nafada', 0x3137313935322e6a7067, 5, 'Public'),
(1004, 'Tahir', 'Kabir', 'PDP', 'Kano', 'Member', 'South', 'Nasarawa/Wudil', 'Nasarawa', 0x32393337312e6a7067, 0, 'Public'),
(1006, 'Haruna', 'Gerawa', 'NNPP', 'Kano', 'Governor', 'South', 'Nasarawa/Wudil', 'Wudil', 0x3736303030352e6a7067, 2, 'Public'),
(1008, 'Isa ', 'Ahmad Bose', 'APC', 'Taraba', 'Governor', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', 0x3938313733392e6a7067, 7, 'Public'),
(1009, 'Imrana', 'Tukur', 'NNPP', 'Kano', 'Senator', 'North', 'Nasarawa/Wudil', 'Nasarawa', 0x3336393232332e6a7067, 5, 'Public'),
(1010, 'Umar', 'Ahmed Accama', 'APC', 'Yobe', 'Member', 'Central', 'Damaturu/Potiskum', 'Potiskum 1', 0x3538343835382e6a7067, 7, 'Public'),
(1013, 'Umar', 'Mahe', 'PDP', 'Kaduna', 'Member', 'Central', 'Kaduna/Sami-naka', 'Kaduna 1', 0x3331353231302e6a7067, 9, 'Public'),
(1034, 'Atiku', 'Abubakar', 'PDP', 'Adamawa', 'President', 'South', 'Jada/Yola/Mayo', 'Jada', 0x37303730332e6a7067, 1, 'Public'),
(1023, 'Muhammad Sani', 'Yahaya', 'NNPP', 'Taraba', 'Governor', 'Central', 'Bali/Gassol', 'Gassol 1', 0x3135333631342e6a7067, 4, 'Public'),
(1032, 'Shamsu', 'Hassan', 'APC', 'Zamfara', 'Senator', 'Central', 'Mafara/Gusau', 'Mafara 2', 0x3132373538352e6a7067, 1, 'Public'),
(1028, 'Nasiru', 'El Rufai', 'NNPP', 'Kaduna', 'Member', 'Central', 'Kaduna/Sami-naka', 'Kaduna 1', 0x3738383735332e706e67, 3, 'Public'),
(1029, 'Isa', 'Presidor', 'NNPP', 'Taraba', 'Senator', 'Central', 'Bali/Gassol', 'Gassol 1', 0x3634373236332e6a7067, 3, 'Public'),
(1030, 'Hamza', 'Al Mustapha', 'AA', 'Yobe', 'President', 'South', 'Damaturu/Potiskum', 'Damaturu 1', 0x3233383433362e706e67, 0, 'Public'),
(1036, 'Mustapha', 'Bose', 'PDP', 'Taraba', 'Member', 'North', 'Ardo-kola/Karim/Lau', 'Sunkani', 0x3633303338372e6a7067, 0, 'Public'),
(1035, 'Muhammadu', 'Buhari', 'APC', 'Katsina', 'President', 'North', 'Daura/Malumfashi', 'Daura', 0x33353532332e6a7067, 2, 'Public'),
(1037, 'Yunusa', 'Muhammad', 'ADC', 'Taraba', 'Member', 'Central', 'Bali/Gassol', 'Bali 1', 0x3932323333392e6a7067, 0, 'Public'),
(1038, 'Babayo', 'Lawan', 'ADC', 'Taraba', 'Member', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', 0x3238343330322e6a7067, 2, 'Public'),
(1039, 'Babatunde', 'Bose', 'ADC', 'Taraba', 'Senator', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', 0x3731383335372e6a7067, 2, 'Public'),
(1040, 'Ibrahim', 'Ladan', 'APC', 'Taraba', 'Member', 'North', 'Ardo-kola/Karim-lamido/Lau', 'Sunkani', 0x3532373436322e6a7067, 0, 'Public');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `MessageID` int(11) NOT NULL,
  `MessageTittle` varchar(50) NOT NULL,
  `MainMessage` varchar(255) NOT NULL,
  `StaffName` varchar(50) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  `MsgTimeStamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`MessageID`, `MessageTittle`, `MainMessage`, `StaffName`, `StaffID`, `UnitID`, `MsgTimeStamp`) VALUES
(1, 'i cannot add voter', 'I have been trying to add voters since morning but the system keeps on saying: error while adding voter.', 'Mubarak Ahmad', 1000, 100, '2016-02-27 01:09:18'),
(2, 'Test', 'Test', 'Imrana Tukur', 1003, 103, '2016-05-05 11:30:38'),
(3, 'Test 2Test 2Test 2Test 2', 'Test 2Test 2Test 2Test 2Test 2Test 2Test 2', 'Mubarak Ahmad', 2, 103, '2023-10-09 21:53:42'),
(4, 'SMS and Email Issue', 'SMS and Emails are not going to voters. Should i go on with the registration?', 'Abdulrauf Ibrahim Dantsoho', 7, 108, '2025-11-23 23:40:19'),
(5, 'Baba sidi Blabla', 'Issues with blablabla, please plaplapla. Thank you!', 'Baba Sidi Dantsoho', 7, 108, '2025-11-25 00:22:20'),
(6, 'Test message', 'Test message and test message', 'Umar Accama', 13, 117, '2025-12-22 01:09:20'),
(7, 'Test 22 33', 'Test 22`Test 22`Test 22`Test 22`Test 22`', 'Umar Accama', 13, 117, '2025-12-22 01:16:19'),
(8, 'Issues with LGA selector', 'I cannot see LGAs after selecting a state in the LGA input', 'Ummi Hijabi', 17, 120, '2026-01-06 21:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `NewsID` int(11) NOT NULL,
  `NewsTittle` varchar(50) NOT NULL,
  `NewsBody` varchar(255) NOT NULL,
  `UpdateTimeStamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`NewsID`, `NewsTittle`, `NewsBody`, `UpdateTimeStamp`) VALUES
(1, 'FROM INEC HQ', 'Announcement from the IT department.\r\nThe INEC server will be off for 4 hours maintenance.\r\n \r\nFrom: 01:00 PM              To: 3:30 PM\r\n\r\nall polling staffs should take note of that. Thank you for your cooperation.', '2016-02-21 09:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `pollingunit`
--

CREATE TABLE `pollingunit` (
  `UnitID` int(11) NOT NULL,
  `State` varchar(20) NOT NULL,
  `LGA` varchar(30) NOT NULL,
  `PUName` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pollingunit`
--

INSERT INTO `pollingunit` (`UnitID`, `State`, `LGA`, `PUName`) VALUES
(128, 'Taraba', 'Ardo-kola', 'Kofar baba ladan 01'),
(126, 'Gombe', 'Nafada', 'Kofar Bayero Nafada'),
(113, 'Taraba', 'Ardo Kola', 'Kasuwan Buhu '),
(125, 'Zamfara', 'Mafara', 'Hassan Basko Hall'),
(127, 'Adamawa', 'Jada', 'Atiku Pri. School'),
(121, 'Yobe', 'Damaturu', 'Kofan Sarki 1'),
(122, 'Taraba', 'Jalingo', 'Kofan Galadima'),
(114, 'Taraba', 'Gassol', 'Tella Market 01'),
(123, 'Taraba', 'Jalingo', 'K. Yelwa'),
(117, 'Bauchi', 'Bauchi', 'GDSS Hassan'),
(119, 'Taraba', 'A Kola', 'GDSS Sunkani'),
(120, 'Taraba', 'Bali', 'GDSS Bali'),
(124, 'Taraba', 'Bali', 'Kofan Sarki');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `BirthDate` varchar(10) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Image` blob NOT NULL,
  `UserName` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` int(1) NOT NULL DEFAULT 1,
  `UnitID` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `BirthDate`, `Gender`, `PhoneNumber`, `Image`, `UserName`, `Password`, `UserType`, `UnitID`) VALUES
(1, 'admin', 'admin', '1990-03-30', 'Male', '012147483647', 0x3839323731362e6a7067, 'Admin123', '$2y$10$Ua62U6TZQAo6/LvCZ9BU0.85Bq3ND/KVaiAXbdMr6A5UgXqjuhm66', 0, NULL),
(13, 'Umar', 'Accama', '1985-09-26', 'Male', '09023445456', 0x3335393631392e6a7067, 'accama', '$2y$10$yGV3l6pkbl3yU3gkFzovs.wJCgou0tHnoi6zmYHy1ke6mYU9vaAxa', 1, 117),
(3, 'Imran', 'Lawal', '1990-05-20', 'Male', '08147483647', 0x3130333635302e706e67, 'Imran12', '$2y$10$4V9Pxcpn/aNbD7cEpxXPw.E', 1, 103),
(14, 'Haruna', 'Gerawa', '1992-03-12', 'Male', '08098234578', 0x3631333536382e6a7067, 'haruna', 'haruna', 1, 119),
(7, 'Baba', 'Sidi Dantsoho', '1994-12-10', 'Male', '7064882233', 0x3939393934372e6a7067, 'Sidi123', 'Sidi123', 1, 113),
(11, 'Isa', 'Ahmed Gassol', '1998-09-09', 'Male', '09189829187', 0x3232373637362e6a7067, 'Presidor1', 'Presidor1', 1, 103),
(16, 'Usman', 'Bayero Nafada', '1994-12-20', 'Male', '08098987665', 0x393534362e6a7067, 'nafada', '$2y$10$3rpyFHezFOwRZ3lceS7Gn.4k.zQMApIDqaljqIUV6s6IdGCSiYT9m', 1, 126),
(17, 'Ummi', 'Hijabi', '2001-01-01', 'Female', '09018726534', 0x3935313233372e6a7067, 'ummi123', '$2y$10$eXoV.54U6DGyp3KtB7knTeLTnQKkoMM3.PRpRKCQ/Nww/0IiUnb5a', 1, 120),
(18, 'Farid', 'T Bose', '2000-09-18', 'Male', '09087265780', 0x3536363839362e6a7067, 'Farid123', '$2y$10$.51fawmBpX95CTDyn.kwoOd8oNReB.aCV3KThA5elQN6dqXPbKq.2', 1, 128);

-- --------------------------------------------------------

--
-- Table structure for table `voter`
--

CREATE TABLE `voter` (
  `VoterID` int(11) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `OtherName` varchar(40) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `BirthDate` date NOT NULL,
  `Phone` varchar(16) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `State` varchar(30) NOT NULL,
  `LGA` varchar(30) NOT NULL,
  `PostCode` int(7) NOT NULL,
  `HomeAddress` varchar(255) NOT NULL,
  `Image` blob NOT NULL,
  `UnitID` int(11) DEFAULT NULL,
  `UserName` varchar(30) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `SenateZone` varchar(10) NOT NULL,
  `FedConstituency` varchar(200) NOT NULL,
  `StateConstituency` varchar(200) NOT NULL,
  `AccessLevel` varchar(6) NOT NULL DEFAULT '2'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `voter`
--

INSERT INTO `voter` (`VoterID`, `FirstName`, `OtherName`, `Gender`, `BirthDate`, `Phone`, `Email`, `State`, `LGA`, `PostCode`, `HomeAddress`, `Image`, `UnitID`, `UserName`, `Password`, `SenateZone`, `FedConstituency`, `StateConstituency`, `AccessLevel`) VALUES
(1090, 'Tahir', 'Kabir', 'Male', '1996-05-04', '09015166930', 'yasum8@yopmail.com', 'Taraba', 'Jalingo', 93923, 'Samunaka 22', 0x34313838392e6a7067, 103, 'Tk1234', 'Tk1234', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1089, 'Imran', 'Tukur', 'Male', '2000-09-07', '08103563780', 'yasum7@yopmail.com', 'Kaduna', 'Kaduna', 98190, 'Zaria road', 0x3739303136322e6a7067, 103, 'Imran12@', 'Imran12@', 'Central', 'Kaduna/Sami-naka', 'Kaduna 1', '2'),
(1088, 'Bamaina ', 'Garba', 'Male', '1992-09-29', '09016693353', 'yasum6@yopmail.com', 'Taraba', 'Jalingo', 9090, 'Tudun wada', 0x3939323839352e6a7067, 103, 'BGarba123', 'BGarba123', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1087, 'usman', 'Bayero', 'Male', '1993-04-09', '08167523789', 'yasum5@yopmail.com', 'Gombe', 'Nafada', 989800, 'Gombe road', 0x3334313536392e6a7067, 103, 'Bayero123', 'Bayero123', 'North', 'Kurmi/Gashaka/Sardauna', 'Bauchi 2', '2'),
(1086, 'umar', 'usman', 'Male', '1998-12-12', '07105237909', 'yasum4@yopmail.com', 'Adamawa', 'Yola', 676768, 'Yolde road', 0x3533373230302e6a7067, 103, '65e7a5180278d', '1998-12-12', 'Central', 'Yola/May-balwa/Ngurore', 'Yola 2', '2'),
(1085, 'test', 'test', 'Male', '1993-04-23', '09151668930', 'yasum3@yopmail.com', 'Taraba', 'Jalingo', 660213, '22 Test road', 0x3130373634322e6a7067, 103, '65e79f82433aa', '1993-04-23', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1084, 'Test', 'Inec', 'Male', '1998-02-19', '08102545786', 'yasum2@yopmail.com', 'Taraba', 'Jalingo', 908288, 'Jeka da fari', 0x3334393233312e6a7067, 103, 'Test123', 'Test123', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1129, 'Yasir', 'Umar', 'Male', '2001-01-01', '08098098712', 'yasum@yopmail.com', 'Taraba', 'Jalingo', 819281, 'Jeka da fari, gov\'t house area', 0x3537373339372e6a7067, 120, 'Yasir123', 'Yasir123', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', '2'),
(1130, 'Abbas', 'Bello', 'Male', '1999-01-01', '08019827389', 'yasum1@yopmail.com', 'Taraba', 'Jalingo', 189201, 'Jeka da fari jalingo 1', 0x3730383932312e6a7067, 120, 'ABello12', 'ABello12', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', '2'),
(1108, 'Muhammad', 'Auwal', 'Male', '1987-09-01', '07139539090', 'aab@yahoo.com', 'Kaduna', 'Zaria', 90829, 'Maje Road 20', 0x3534353831362e6a7067, 103, '65f6f460be073', '1987-09-01', 'North', 'Jalingo/Yorro/Zing', 'Sardauna', '2'),
(1111, 'Haruna', 'Gerawa', 'Male', '1997-02-19', '08109837698', 'hger@yahoo.com', 'Taraba', 'Jalingo', 90387, 'Kofan Sarki', 0x3235383431322e6a7067, 117, 'Gerawa', 'Gerawa1', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1127, 'Hamzah', 'Robertson', 'Male', '1982-09-12', '09087657908', 'tag@yopmail.com', 'Taraba', 'Jalingo', 918290, 'Lamurde lamido tafida', 0x3531393931392e6a7067, 117, '694c9e2c6d2a6', '1982-09-12', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', '2'),
(1119, 'Maladan', 'Mane', 'Male', '1998-12-01', '08192093490', 'malladan@yahoo.com', 'Taraba', 'Gashaka', 660213, 'Jeka da fari 13 Jalingo', 0x766f7465725f363932346630616163366165302e6a7067, 108, 'Mane123', 'Mane123', 'Central', 'Kurmi/Gashaka/Sardauna', '', '2'),
(1121, 'Balki', 'Bose', 'Female', '1998-12-31', '09089898898', 'bs.bose@live.com', 'Taraba', 'Ardo Kola', 102808, 'Kasuwan Buhu Sunkani', 0x3836303438362e6a7067, 108, 'Balki12', 'Balki12', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 2', '2'),
(1122, 'Isa', 'Pesidor', 'Male', '1993-12-19', '09089878723', 'prs@gima.com', 'Taraba', 'Gassol', 899200, 'Tell market 12', 0x3837393839362e6a7067, 108, 'Isa123', 'Isa123', 'Central', 'Bali/Gassol', 'Gassol 1', '2'),
(1131, 'Ihsan', 'Bose', 'Female', '1999-01-01', '09810928762', 'ib@yopmail.com', 'Taraba', 'Jalingo', 982099, 'Jeka da fari govth area 3', 0x3833393432312e6a7067, 117, 'IBose123', 'IBose123', 'North', 'Jalingo/Yorro/Zing', 'Jalingo 1', '2');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `Votes` int(10) NOT NULL,
  `VoterID` int(10) NOT NULL,
  `Position` varchar(30) NOT NULL,
  `ContestantID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`Votes`, `VoterID`, `Position`, `ContestantID`) VALUES
(1, 1001, 'President', 1000),
(2, 1001, 'Governor', 1000),
(3, 1003, 'President', 1013),
(4, 1002, 'Governor', 1006),
(5, 1002, 'President', 1005),
(6, 1020, 'President', 1005),
(7, 1020, 'Governor', 1000),
(8, 1020, 'Senator', 1003),
(9, 1002, 'Senator', 1009),
(10, 1001, 'Senator', 1003),
(11, 1001, 'Member', 1013),
(12, 1119, 'President', 1028),
(13, 1119, 'Governor', 1023),
(14, 1119, 'Member', 1013),
(15, 1119, 'Senator', 1003),
(16, 1122, 'Governor', 1008),
(17, 1122, 'President', 1025),
(18, 1122, 'Member', 1013),
(19, 1122, 'Senator', 1003),
(20, 1089, 'Governor', 1008),
(21, 1089, 'Member', 1013),
(22, 1089, 'President', 1032),
(23, 1089, 'Senator', 1007),
(24, 1111, 'Governor', 1023),
(25, 1111, 'Member', 1013),
(26, 1111, 'President', 1010),
(27, 1111, 'Senator', 1009),
(28, 1127, 'Governor', 1006),
(29, 1127, 'Member', 1013),
(30, 1127, 'President', 1010),
(31, 1127, 'Senator', 1009),
(32, 1108, 'Governor', 1023),
(33, 1108, 'Member', 1013),
(34, 1108, 'President', 1010),
(35, 1108, 'Senator', 1009),
(36, 1121, 'Governor', 1008),
(37, 1121, 'Member', 1013),
(38, 1121, 'Senator', 1009),
(39, 1121, 'President', 1010),
(40, 1129, 'Governor', 1008),
(41, 1129, 'Member', 1028),
(42, 1129, 'President', 1010),
(43, 1129, 'Senator', 1029),
(44, 1130, 'Governor', 1008),
(45, 1130, 'Member', 1028),
(46, 1130, 'President', 1010),
(47, 1130, 'Senator', 1029),
(48, 1131, 'Governor', 1008),
(49, 1131, 'Member', 1013),
(50, 1131, 'President', 1010),
(51, 1131, 'Senator', 1029),
(52, 1090, 'Governor', 1008),
(53, 1090, 'President', 1035),
(54, 1090, 'Senator', 1039),
(55, 1090, 'Member', 1038),
(56, 1088, 'Governor', 1023),
(57, 1088, 'President', 1035),
(58, 1088, 'Member', 1038),
(59, 1088, 'Senator', 1039),
(60, 1087, 'Governor', 1003),
(61, 1087, 'President', 1034);

-- --------------------------------------------------------

--
-- Table structure for table `votesresults`
--

CREATE TABLE `votesresults` (
  `ResultID` int(10) NOT NULL,
  `ContestantID` int(11) NOT NULL,
  `ContestantName` varchar(50) NOT NULL,
  `PartyName` varchar(10) NOT NULL,
  `Votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `votesresults`
--

INSERT INTO `votesresults` (`ResultID`, `ContestantID`, `ContestantName`, `PartyName`, `Votes`) VALUES
(1, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(2, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(3, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(4, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(5, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(6, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(7, 1003, 'Usman Bayero Nafada', 'PDP', 5),
(8, 1013, 'Umar Mahe', 'PDP', 9),
(9, 1028, 'Nasiru El Rufai', 'NNPP', 3),
(10, 1023, 'Muhammad Sani Yahaya', 'NNPP', 4),
(11, 1008, 'Isa  Ahmad Bose', 'APC', 7),
(12, 1025, 'Final Test', 'ADC', 1),
(13, 1032, 'Shamsu Hassan', 'APC', 1),
(14, 1007, 'Abdul Jalal Shehu', 'APC', 1),
(15, 1010, 'Umar Ahmed Accama', 'APC', 7),
(16, 1009, 'Imrana Tukur', 'PDP', 4),
(17, 1006, 'Adamu  Bashiru', 'ANPP', 1),
(18, 1029, 'Isa Presidor', 'NNPP', 3),
(19, 1035, 'Muhammadu Buhari', 'APC', 2),
(20, 1039, 'Babatunde Bose', 'ADC', 2),
(21, 1038, 'Babayo Lawan', 'ADC', 2),
(22, 1034, 'Atiku Abubakar', 'PDP', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contestant`
--
ALTER TABLE `contestant`
  ADD PRIMARY KEY (`ContestantID`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`MessageID`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`NewsID`);

--
-- Indexes for table `pollingunit`
--
ALTER TABLE `pollingunit`
  ADD PRIMARY KEY (`UnitID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `voter`
--
ALTER TABLE `voter`
  ADD PRIMARY KEY (`VoterID`),
  ADD UNIQUE KEY `Phone` (`Phone`,`Email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`Votes`);

--
-- Indexes for table `votesresults`
--
ALTER TABLE `votesresults`
  ADD PRIMARY KEY (`ResultID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contestant`
--
ALTER TABLE `contestant`
  MODIFY `ContestantID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1041;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `NewsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pollingunit`
--
ALTER TABLE `pollingunit`
  MODIFY `UnitID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `voter`
--
ALTER TABLE `voter`
  MODIFY `VoterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1132;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `Votes` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `votesresults`
--
ALTER TABLE `votesresults`
  MODIFY `ResultID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
