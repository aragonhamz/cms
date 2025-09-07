-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 09:15 PM
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
-- Database: `ecom`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `role`, `email`, `mobile`, `status`) VALUES
(1, 'PREETI', 'PRADHAN', 0, 'preetipradhan097@gmail.com', '12345678910', 1),
(10, 'Shivangi', 'Pandey', 1, 'shivangi050mca20@igdtuw.ac.in', '123456789', 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(75) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `comment` text NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `mobile`, `comment`, `added_on`) VALUES
(2, 'HIMANSHI', 'himanshi010mca20@igdtuw.ac.in', '987654321', 'Drop me a message and I\'ll get back to you AS SOON AS POSSIBLE!', '2020-12-02 21:55:35'),
(3, 'ADITI MOHANTY', 'aditi078mca20@igdtuw.ac.in', '123456789', 'DM ME YOUR QUERY !!', '2020-11-11 21:57:15'),
(4, 'SHIVANGI PANDEY', 'shivangi050mca20@igdtuw.ac.in', '987654321', 'I WOULD LOVE TO HELP YOU OUT !!', '2020-12-09 21:58:47'),
(5, 'MEGHA RAGHAV', 'megha023mca20@igdtuw.ac.in', '123456789', 'ANY QUERY DM ME !!', '2020-11-11 22:01:40'),
(6, 'PRIYA GUPTA ', 'priya034mca20@igdtuw.ac.in', '987654321', 'Don\'t hesitate! Drop me a message !!', '2020-12-02 22:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_master`
--

CREATE TABLE `coupon_master` (
  `id` int(11) NOT NULL,
  `coupon_code` varchar(50) NOT NULL,
  `coupon_value` int(11) NOT NULL,
  `coupon_type` varchar(10) NOT NULL,
  `cart_min_value` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `coupon_master`
--

INSERT INTO `coupon_master` (`id`, `coupon_code`, `coupon_value`, `coupon_type`, `cart_min_value`, `status`) VALUES
(7, 'FOODGRAINS , OILS AND GHEE', 60, 'Percentage', 567, 0),
(8, 'FLOUR and GRAINS', 25, 'Percentage', 125, 1),
(9, 'PACKAGED FOODS', 30, 'Rupee', 500, 1),
(10, 'FIRST 50', 20, 'Rupee', 1000, 0),
(11, 'SPECIAL OFFER', 50, 'Percentage', 2000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dcer`
--

CREATE TABLE `dcer` (
  `cer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `doctype` varchar(100) NOT NULL,
  `cerno` varchar(50) NOT NULL,
  `rollno` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dept` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `college` varchar(255) DEFAULT NULL,
  `degree` varchar(255) NOT NULL,
  `degreetype` varchar(100) NOT NULL,
  `honours` varchar(255) DEFAULT NULL,
  `gradyear` varchar(10) NOT NULL,
  `examheld` varchar(20) DEFAULT NULL,
  `regno` varchar(30) NOT NULL,
  `ofyear` varchar(30) DEFAULT NULL,
  `cgpa` varchar(4) DEFAULT NULL,
  `grade` char(2) DEFAULT NULL,
  `division` varchar(30) DEFAULT NULL,
  `appliedon` datetime DEFAULT NULL,
  `payment` varchar(100) DEFAULT NULL,
  `paymentOn` datetime DEFAULT NULL,
  `amount` int(100) DEFAULT NULL,
  `transactionID` varchar(255) DEFAULT NULL,
  `bank_ref_no` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `msgs` varchar(255) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  `regcard_file` varchar(255) DEFAULT NULL,
  `marksheet_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dcer`
--

INSERT INTO `dcer` (`cer_id`, `user_id`, `doctype`, `cerno`, `rollno`, `name`, `dept`, `school`, `college`, `degree`, `degreetype`, `honours`, `gradyear`, `examheld`, `regno`, `ofyear`, `cgpa`, `grade`, `division`, `appliedon`, `payment`, `paymentOn`, `amount`, `transactionID`, `bank_ref_no`, `status`, `msgs`, `application_id`, `regcard_file`, `marksheet_file`) VALUES
(1, NULL, 'Degree', '1', '18MTECHIT34', 'DIBYA LAXMI DAHAL', 'INFORMATION TECHNOLOGY', NULL, NULL, 'MASTER OF TECHNOLOGY IN IT', 'MTECH', NULL, '2020', NULL, '22090082', NULL, '7.45', NULL, 'FIRST CLASS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 6, 'Degree', '2', '19MBBS34', 'PURBASHA BORA', NULL, NULL, NULL, 'Doctor of Medicine (DESCIPLINE)', 'UG', NULL, '2020', 'October, 2022', '56214', 'OF 2011', NULL, NULL, NULL, '2025-05-02 23:14:10', 'Aborted', '2025-05-02 20:12:24', 1, '113749860479', NULL, 'Applied', NULL, '28937', 'uploads/regcard_56214_6815046ae4918.pdf', ''),
(3, NULL, 'Degree', '3', '22MAPOL07', 'ROHIT MAHANTA', 'POLITICAL SCIENCE', NULL, NULL, 'MASTER OF ARTS IN POLITICAL SCIENCE', 'PG1', NULL, '2020', NULL, '22090023', NULL, '7.27', 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, 'Degree', '4', '17PHDSOC02', 'AGGELIA SONAWI SHADAP', NULL, 'HUMANITIES', NULL, 'DOCTOR OF PHILOSPHY IN COMMERECE', 'PHD', NULL, '2020', NULL, '19002971', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 6, 'Degree', '5', 'HM170021', 'DAIABHA MUKHIM', NULL, NULL, 'North Eastern Institute of Ayurveda and Homeopathy:', 'Bachelor of Homeopathic Medicine and Surgery(BHMS)', 'INTERNSHIP1', NULL, '2020', 'October, 2021', '19006735', NULL, NULL, NULL, NULL, '2025-04-29 00:43:30', 'Success', NULL, 300, '113744079044', '110833', 'Applied', NULL, '55925', 'uploads/regcard_19006735_680fd35a61cf5.pdf', 'uploads/marksheet_19006735_680fd35a6201c.pdf'),
(6, NULL, 'Degree', '6', '23BTECHECE04', 'LALHMINGMOI INFIMATE', 'Electronics And Communication Engineering', NULL, NULL, 'BACHELOR OF TECHNOLOGY IN ELECTRONICS AND COMMUNICATION', 'SOT1', NULL, '2020', NULL, '22090066', NULL, '7.23', NULL, 'First Division', NULL, '', NULL, 400, NULL, NULL, '', NULL, '', '', ''),
(7, NULL, 'Degree', '7', '18MBA08', 'LILO V ZHIMO', NULL, NULL, NULL, 'MASTER OF BUSINESS ADMINISTRATION IN FINANCE', 'PG2', NULL, '2020', NULL, '22090008', NULL, '7.14', 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 6, 'Degree', '8', '22BALLB02', 'JUBIN KRISHNA', NULL, NULL, NULL, 'BACHELOR OF LAW', 'UG', NULL, '2020', NULL, '22090530', NULL, NULL, NULL, 'FIRST DIVISION', '2025-04-30 21:21:08', 'Aborted', '0000-00-00 00:00:00', 200, '113747052388', NULL, 'Applied', NULL, '8717', 'uploads/regcard_22090530_681246ec1f2de.pdf', 'uploads/marksheet_22090530_681246ec1f87f.pdf'),
(9, NULL, 'Degree', '9', '19MCA03', 'AMEDA LARIBHA SUTING', NULL, NULL, NULL, 'MASTER OF COMPUTER APPLICATION', 'MCA', NULL, '2020', NULL, '22090065', NULL, '7.09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, NULL, 'Degree', '10', 'A2202384', 'JOHN DIENGDOH', NULL, NULL, 'ST ANTHONY\'S COLLEGE', 'BACHELOR OF ARTS', 'UG', 'ENGLISH', '2023', 'March, 2025', '21087389', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `degreetype`
--

CREATE TABLE `degreetype` (
  `type_id` int(11) NOT NULL,
  `degreetype` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `degreetype`
--

INSERT INTO `degreetype` (`type_id`, `degreetype`, `degree`) VALUES
(1, 'UG', 'Bachelor of Arts Honours'),
(2, 'UG', 'Bachelor of Arts PASS'),
(3, 'SOT', 'Bachelor of Technology in IT'),
(4, 'PG', 'Master of Arts'),
(5, 'PG', 'MSC'),
(6, 'INTERNSHIP1', 'Bachelor of Homeopathic Medicine and Surgery(BHMS)');

-- --------------------------------------------------------

--
-- Table structure for table `degree_cer`
--

CREATE TABLE `degree_cer` (
  `cer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cerno` varchar(50) NOT NULL,
  `rollno` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `regNo` varchar(30) NOT NULL,
  `year` varchar(10) NOT NULL,
  `cgpa` int(5) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `appliedon` date DEFAULT NULL,
  `payment` varchar(10) NOT NULL,
  `status` varchar(50) NOT NULL,
  `msgs` text DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `degree_cer`
--

INSERT INTO `degree_cer` (`cer_id`, `user_id`, `cerno`, `rollno`, `name`, `dept`, `regNo`, `year`, `cgpa`, `grade`, `appliedon`, `payment`, `status`, `msgs`, `application_id`) VALUES
(590, NULL, '1', '22MAPOL38', 'Hamar Kharz1', 'POLITICAL SCIENCE', '22090082', '2024', 7, 'A', NULL, '', '', '', NULL),
(591, NULL, '2', '22MAPOL22', 'Hamar Kharz2', 'POLITICAL SCIENCE', '22090080', '2024', 7, 'A', NULL, '', '', '', NULL),
(592, 7, '3', '22MAPOL07', 'Hamar Kharz3', 'POLITICAL SCIENCE', '22090023', '2024', 7, 'A', '2025-04-02', 'Pending', 'Under process', '', 5923394),
(593, NULL, '4', '22MAPOL15', 'Hamar Kharz4', 'POLITICAL SCIENCE', '19002971', '2024', 7, 'A', NULL, '', '', '', NULL),
(594, NULL, '5', '22MAPOL42', 'Hamar Kharz5', 'POLITICAL SCIENCE', '19006735', '2024', 7, 'A', NULL, '', '', '', NULL),
(595, 6, '6', '22MAPOL47', 'Hamar Kharz6', 'POLITICAL SCIENCE', '22090066', '2024', 7, 'A', '2025-04-02', 'Pending', 'Under process', '', 5959504),
(596, NULL, '7', '22MAPOL01', 'Hamar Kharz7', 'POLITICAL SCIENCE', '22090008', '2024', 7, 'A', NULL, '', '', '', NULL),
(597, NULL, '8', '22MAPOL32', 'Hamar Kharz8', 'POLITICAL SCIENCE', '22090530', '2024', 7, 'A', NULL, '', '', '', NULL),
(598, NULL, '9', '22MAPOL46', 'Hamar Kharz9', 'POLITICAL SCIENCE', '22090065', '2024', 7, 'A', NULL, '', '', '', NULL),
(599, NULL, '10', '22MAPOL13', 'Hamar Kharz10', 'POLITICAL SCIENCE', '22090813', '2024', 7, 'A', NULL, '', '', '', NULL),
(600, NULL, '11', '22MAPOL18', 'Hamar Kharz11', 'POLITICAL SCIENCE', '19006739', '2024', 7, 'A', NULL, '', '', '', NULL),
(601, NULL, '12', '22MAPOL37', 'Hamar Kharz12', 'POLITICAL SCIENCE', '22090024', '2024', 7, 'A', NULL, '', '', '', NULL),
(602, NULL, '13', '22MAPOL02', 'Hamar Kharz13', 'POLITICAL SCIENCE', '22090064', '2024', 7, 'A', NULL, '', '', '', NULL),
(603, NULL, '14', '22MAPOL03', 'Hamar Kharz14', 'POLITICAL SCIENCE', '19006105', '2024', 7, 'A', NULL, '', '', '', NULL),
(604, NULL, '15', '22MAPOL19', 'Hamar Kharz15', 'POLITICAL SCIENCE', '18008318', '2024', 7, 'A', NULL, '', '', '', NULL),
(605, NULL, '16', '22MAPOL17', 'Hamar Kharz16', 'POLITICAL SCIENCE', '22090529', '2024', 6, 'B+', NULL, '', '', '', NULL),
(606, NULL, '17', '22MAPOL23', 'Hamar Kharz17', 'POLITICAL SCIENCE', '19006430', '2024', 6, 'B+', NULL, '', '', '', NULL),
(607, NULL, '18', '22MAPOL16', 'Hamar Kharz18', 'POLITICAL SCIENCE', '22090528', '2024', 6, 'B+', NULL, '', '', '', NULL),
(608, NULL, '19', '22MAPOL08', 'Hamar Kharz19', 'POLITICAL SCIENCE', '19006113', '2024', 6, 'B+', NULL, '', '', '', NULL),
(609, NULL, '20', '22MAPOL52', 'Hamar Kharz20', 'POLITICAL SCIENCE', '19006736', '2024', 6, 'B+', NULL, '', '', '', NULL),
(610, NULL, '21', '22MAPOL10', 'Hamar Kharz21', 'POLITICAL SCIENCE', '22090007', '2024', 6, 'B+', NULL, '', '', '', NULL),
(611, NULL, '22', '22MAPOL30', 'Hamar Kharz22', 'POLITICAL SCIENCE', 'A1906391', '2024', 6, 'B+', NULL, '', '', '', NULL),
(612, NULL, '23', '22MAPOL39', 'Hamar Kharz23', 'POLITICAL SCIENCE', '22090201', '2024', 6, 'B+', NULL, '', '', '', NULL),
(613, NULL, '24', '22MAPOL05', 'Hamar Kharz24', 'POLITICAL SCIENCE', '22090098', '2024', 6, 'B+', NULL, '', '', '', NULL),
(614, NULL, '1', '22MAPOL38', 'Hamar Kharz1', 'POLITICAL SCIENCE', '22090082', '2024', 7, 'A', NULL, '', '', '', NULL),
(615, NULL, '2', '22MAPOL22', 'Hamar Kharz2', 'POLITICAL SCIENCE', '22090080', '2024', 7, 'A', NULL, '', '', '', NULL),
(616, NULL, '3', '22MAPOL07', 'Hamar Kharz3', 'POLITICAL SCIENCE', '22090023', '2024', 7, 'A', NULL, '', '', '', NULL),
(617, NULL, '4', '22MAPOL15', 'Hamar Kharz4', 'POLITICAL SCIENCE', '19002971', '2024', 7, 'A', NULL, '', '', '', NULL),
(618, NULL, '5', '22MAPOL42', 'Hamar Kharz5', 'POLITICAL SCIENCE', '19006735', '2024', 7, 'A', NULL, '', '', '', NULL),
(619, NULL, '6', '22MAPOL47', 'Hamar Kharz6', 'POLITICAL SCIENCE', '22090066', '2024', 7, 'A', NULL, '', '', '', NULL),
(620, NULL, '7', '22MAPOL01', 'Hamar Kharz7', 'POLITICAL SCIENCE', '22090008', '2024', 7, 'A', NULL, '', '', '', NULL),
(621, NULL, '8', '22MAPOL32', 'Hamar Kharz8', 'POLITICAL SCIENCE', '22090530', '2024', 7, 'A', NULL, '', '', '', NULL),
(622, NULL, '9', '22MAPOL46', 'Hamar Kharz9', 'POLITICAL SCIENCE', '22090065', '2024', 7, 'A', NULL, '', '', '', NULL),
(623, NULL, '10', '22MAPOL13', 'Hamar Kharz10', 'POLITICAL SCIENCE', '22090813', '2024', 7, 'A', NULL, '', '', '', NULL),
(624, NULL, '11', '22MAPOL18', 'Hamar Kharz11', 'POLITICAL SCIENCE', '19006739', '2024', 7, 'A', NULL, '', '', '', NULL),
(625, NULL, '12', '22MAPOL37', 'Hamar Kharz12', 'POLITICAL SCIENCE', '22090024', '2024', 7, 'A', NULL, '', '', '', NULL),
(626, NULL, '13', '22MAPOL02', 'Hamar Kharz13', 'POLITICAL SCIENCE', '22090064', '2024', 7, 'A', NULL, '', '', '', NULL),
(627, NULL, '14', '22MAPOL03', 'Hamar Kharz14', 'POLITICAL SCIENCE', '19006105', '2024', 7, 'A', NULL, '', '', '', NULL),
(628, NULL, '15', '22MAPOL19', 'Hamar Kharz15', 'POLITICAL SCIENCE', '18008318', '2024', 7, 'A', NULL, '', '', '', NULL),
(629, NULL, '16', '22MAPOL17', 'Hamar Kharz16', 'POLITICAL SCIENCE', '22090529', '2024', 6, 'B+', NULL, '', '', '', NULL),
(630, NULL, '17', '22MAPOL23', 'Hamar Kharz17', 'POLITICAL SCIENCE', '19006430', '2024', 6, 'B+', NULL, '', '', '', NULL),
(631, NULL, '18', '22MAPOL16', 'Hamar Kharz18', 'POLITICAL SCIENCE', '22090528', '2024', 6, 'B+', NULL, '', '', '', NULL),
(632, NULL, '19', '22MAPOL08', 'Hamar Kharz19', 'POLITICAL SCIENCE', '19006113', '2024', 6, 'B+', NULL, '', '', '', NULL),
(633, NULL, '20', '22MAPOL52', 'Hamar Kharz20', 'POLITICAL SCIENCE', '19006736', '2024', 6, 'B+', NULL, '', '', '', NULL),
(634, NULL, '21', '22MAPOL10', 'Hamar Kharz21', 'POLITICAL SCIENCE', '22090007', '2024', 6, 'B+', NULL, '', '', '', NULL),
(635, NULL, '22', '22MAPOL30', 'Hamar Kharz22', 'POLITICAL SCIENCE', 'A1906391', '2024', 6, 'B+', NULL, '', '', '', NULL),
(636, NULL, '23', '22MAPOL39', 'Hamar Kharz23', 'POLITICAL SCIENCE', '22090201', '2024', 6, 'B+', NULL, '', '', '', NULL),
(637, NULL, '24', '22MAPOL05', 'Hamar Kharz24', 'POLITICAL SCIENCE', '22090098', '2024', 6, 'B+', NULL, '', '', '', NULL),
(638, NULL, '1', '22MAPOL38', 'Hamar Kharz1', 'POLITICAL SCIENCE', '22090082', '2024', 7, 'A', NULL, '', '', '', NULL),
(639, NULL, '2', '22MAPOL22', 'Hamar Kharz2', 'POLITICAL SCIENCE', '22090080', '2024', 7, 'A', NULL, '', '', '', NULL),
(640, NULL, '3', '22MAPOL07', 'Hamar Kharz3', 'POLITICAL SCIENCE', '22090023', '2024', 7, 'A', NULL, '', '', '', NULL),
(641, NULL, '4', '22MAPOL15', 'Hamar Kharz4', 'POLITICAL SCIENCE', '19002971', '2024', 7, 'A', NULL, '', '', '', NULL),
(642, NULL, '5', '22MAPOL42', 'Hamar Kharz5', 'POLITICAL SCIENCE', '19006735', '2024', 7, 'A', NULL, '', '', '', NULL),
(643, NULL, '6', '22MAPOL47', 'Hamar Kharz6', 'POLITICAL SCIENCE', '22090066', '2024', 7, 'A', NULL, '', '', '', NULL),
(644, NULL, '7', '22MAPOL01', 'Hamar Kharz7', 'POLITICAL SCIENCE', '22090008', '2024', 7, 'A', NULL, '', '', '', NULL),
(645, NULL, '8', '22MAPOL32', 'Hamar Kharz8', 'POLITICAL SCIENCE', '22090530', '2024', 7, 'A', NULL, '', '', '', NULL),
(646, NULL, '9', '22MAPOL46', 'Hamar Kharz9', 'POLITICAL SCIENCE', '22090065', '2024', 7, 'A', NULL, '', '', '', NULL),
(647, NULL, '10', '22MAPOL13', 'Hamar Kharz10', 'POLITICAL SCIENCE', '22090813', '2024', 7, 'A', NULL, '', '', '', NULL),
(648, NULL, '11', '22MAPOL18', 'Hamar Kharz11', 'POLITICAL SCIENCE', '19006739', '2024', 7, 'A', NULL, '', '', '', NULL),
(649, NULL, '12', '22MAPOL37', 'Hamar Kharz12', 'POLITICAL SCIENCE', '22090024', '2024', 7, 'A', NULL, '', '', '', NULL),
(650, NULL, '13', '22MAPOL02', 'Hamar Kharz13', 'POLITICAL SCIENCE', '22090064', '2024', 7, 'A', NULL, '', '', '', NULL),
(651, NULL, '14', '22MAPOL03', 'Hamar Kharz14', 'POLITICAL SCIENCE', '19006105', '2024', 7, 'A', NULL, '', '', '', NULL),
(652, NULL, '15', '22MAPOL19', 'Hamar Kharz15', 'POLITICAL SCIENCE', '18008318', '2024', 7, 'A', NULL, '', '', '', NULL),
(653, NULL, '16', '22MAPOL17', 'Hamar Kharz16', 'POLITICAL SCIENCE', '22090529', '2024', 6, 'B+', NULL, '', '', '', NULL),
(654, NULL, '17', '22MAPOL23', 'Hamar Kharz17', 'POLITICAL SCIENCE', '19006430', '2024', 6, 'B+', NULL, '', '', '', NULL),
(655, NULL, '18', '22MAPOL16', 'Hamar Kharz18', 'POLITICAL SCIENCE', '22090528', '2024', 6, 'B+', NULL, '', '', '', NULL),
(656, NULL, '19', '22MAPOL08', 'Hamar Kharz19', 'POLITICAL SCIENCE', '19006113', '2024', 6, 'B+', NULL, '', '', '', NULL),
(657, NULL, '20', '22MAPOL52', 'Hamar Kharz20', 'POLITICAL SCIENCE', '19006736', '2024', 6, 'B+', NULL, '', '', '', NULL),
(658, NULL, '21', '22MAPOL10', 'Hamar Kharz21', 'POLITICAL SCIENCE', '22090007', '2024', 6, 'B+', NULL, '', '', '', NULL),
(659, NULL, '22', '22MAPOL30', 'Hamar Kharz22', 'POLITICAL SCIENCE', 'A1906391', '2024', 6, 'B+', NULL, '', '', '', NULL),
(660, NULL, '23', '22MAPOL39', 'Hamar Kharz23', 'POLITICAL SCIENCE', '22090201', '2024', 6, 'B+', NULL, '', '', '', NULL),
(661, NULL, '24', '22MAPOL05', 'Hamar Kharz24', 'POLITICAL SCIENCE', '22090098', '2024', 6, 'B+', NULL, '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctype`
--

CREATE TABLE `doctype` (
  `doc_id` int(11) NOT NULL,
  `document` varchar(255) NOT NULL,
  `enable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctype`
--

INSERT INTO `doctype` (`doc_id`, `document`, `enable`) VALUES
(1, 'Degree', 1),
(2, 'Duplicate Degree', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `fees_id` int(11) NOT NULL,
  `degree` int(20) NOT NULL,
  `dup_degree` int(20) NOT NULL,
  `migration` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mdcer`
--

CREATE TABLE `mdcer` (
  `cer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `doctype` varchar(100) NOT NULL,
  `rollno` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dept` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `college` varchar(255) DEFAULT NULL,
  `degree` varchar(255) NOT NULL,
  `degreetype` varchar(100) NOT NULL,
  `honours` varchar(255) DEFAULT NULL,
  `gradyear` varchar(10) NOT NULL,
  `examheld` varchar(20) DEFAULT NULL,
  `regno` varchar(30) NOT NULL,
  `cgpa` varchar(4) DEFAULT NULL,
  `grade` char(2) DEFAULT NULL,
  `division` varchar(30) DEFAULT NULL,
  `appliedon` datetime DEFAULT NULL,
  `payment` varchar(100) DEFAULT NULL,
  `paymentOn` datetime DEFAULT NULL,
  `amount` int(100) DEFAULT NULL,
  `transactionID` varchar(255) DEFAULT NULL,
  `bank_ref_no` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `msgs` varchar(255) DEFAULT NULL,
  `application_id` varchar(100) DEFAULT NULL,
  `regcard_file` varchar(255) NOT NULL,
  `marksheet_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mdcer`
--

INSERT INTO `mdcer` (`cer_id`, `user_id`, `doctype`, `rollno`, `name`, `dept`, `school`, `college`, `degree`, `degreetype`, `honours`, `gradyear`, `examheld`, `regno`, `cgpa`, `grade`, `division`, `appliedon`, `payment`, `paymentOn`, `amount`, `transactionID`, `bank_ref_no`, `status`, `msgs`, `application_id`, `regcard_file`, `marksheet_file`) VALUES
(6, 6, 'Migration', '21MAKHA19', 'HAMAR BAMUT LANG KHARZ', 'INFORMATION TECHNOLOGY', 'TECHNOLOGY', '', 'Bachelor of Arts PASS', 'UG', '', '2022', '', '234232', '7.8', 'O', '', '2025-04-25 21:52:51', 'Success', NULL, NULL, NULL, NULL, 'Applied', NULL, '50007729', 'uploads/regcard_234232_680be81378d31.pdf', 'uploads/marksheet_234232_680be81378f3f.pdf'),
(11, NULL, 'Provisional', '18BALLB3628', 'Mista XXX YYY', '', '', '', 'BSCH', 'UG', 'LAW', '2025', '', '51342132', '', '', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, '', '', ''),
(12, 6, 'Degree', '17MAMSCGEO2937', 'Saving Star Khariong', '', '', '', 'Master of Arts', 'PG', '', '2025', '', '2353423', '', '', '', '2025-05-05 00:18:47', 'Pending', NULL, NULL, NULL, NULL, 'Applied', NULL, '2025050599721', 'uploads/regcard_2353423_6817b68f78baa.pdf', 'uploads/marksheet_2353423_6817b68f78ded.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `post_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `post_date`) VALUES
(1, 'Application for Degree certificate of 2024 batch is now open', '2025-03-27'),
(2, 'Only Degree certificate is applicable online', '2025-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `regcard`
--

CREATE TABLE `regcard` (
  `reg_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` int(12) NOT NULL,
  `father` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `file` text NOT NULL,
  `reg_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regcard`
--

INSERT INTO `regcard` (`reg_id`, `name`, `gender`, `email`, `contact`, `father`, `dob`, `file`, `reg_on`) VALUES
(3, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '0000-00-00', '1-Sample (1).pdf', '2025-02-22'),
(4, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '1970-01-01', '4-10067714053306006.pdf', '2025-02-22'),
(5, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '1970-01-01', '5-Sample (2).pdf', '2025-02-22'),
(6, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-12', '6-10.02.2025 M.Tech 1st and 3rd end semester examination time table.pdf', '2025-02-22'),
(7, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '7-PG(Dip)_Transcript (4).pdf', '2025-02-22'),
(8, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-04', '8-B2100456_NIHAAL VISALPARA.pdf', '2025-02-22'),
(9, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '9-hamar.kharshiing@gmail.comRegCard.pdf', '2025-02-25'),
(10, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '9-hamar.kharshiing@gmail.comRegCard.pdf', '2025-02-25'),
(11, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '9-hamar.kharshiing@gmail.comRegCard.pdf', '2025-02-25'),
(12, 'HAMAR BAMUT LANG KHARSHIING', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '9-hamar.kharshiing@gmail.comRegCard.pdf', '2025-02-25'),
(13, 'aaaaaaaaaaaaa', 'Male', 'hamar.kharshiing@gmail.com', 2147483647, 'aaaaaa', '2025-02-06', '9-hamar.kharshiing@gmail.comRegCard.pdf', '2025-02-25');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settings_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `categories` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `categories`, `status`) VALUES
(11, 'FRUITS & VEGETABLES', 1),
(12, 'FOODGRAINS, OIL & MASALA', 1),
(13, 'BAKERY, CAKES & DAIRY', 0),
(14, 'BEVERAGES', 1),
(15, 'SNACKS & BRANDED FOODS', 1),
(16, 'EGGS , MEAT & FISH', 0),
(17, 'GOURMET & WORLD FOOD', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member`
--

CREATE TABLE `tbl_member` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `role` varchar(50) NOT NULL,
  `email_verification_link` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `login_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `status`, `role`, `email_verification_link`, `email_verified_at`, `login_token`) VALUES
(6, 'HAMAR BAMUT LANG KHARSHIING', 'hamar.kharshiing@gmail.com', '98635541258', '912ec803b2ce49e4a541068d495ab570', 1, 'admin', 'bae443691bdcf242d30246e1be5fb4265842', NULL, '084255c3a38b354a6c8a5260f512bafa'),
(7, 'Aragon', 'hamar@nehu.ac.in', '9863447987', '74dc8d5f58297164a11346450fe631c9', 1, 'user', 'e456e7c11adc0cc8dfa1caf68958b2fc2992', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `profileid` int(50) NOT NULL,
  `userid` int(50) NOT NULL,
  `dob` date NOT NULL,
  `fathername` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `nationality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`profileid`, `userid`, `dob`, `fathername`, `address`, `nationality`) VALUES
(2, 7, '1997-02-02', 'L Marba', 'Mawpat', 'India');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_master`
--
ALTER TABLE `coupon_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dcer`
--
ALTER TABLE `dcer`
  ADD PRIMARY KEY (`cer_id`);

--
-- Indexes for table `degreetype`
--
ALTER TABLE `degreetype`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `degree_cer`
--
ALTER TABLE `degree_cer`
  ADD PRIMARY KEY (`cer_id`);

--
-- Indexes for table `doctype`
--
ALTER TABLE `doctype`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`fees_id`);

--
-- Indexes for table `mdcer`
--
ALTER TABLE `mdcer`
  ADD PRIMARY KEY (`cer_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regcard`
--
ALTER TABLE `regcard`
  ADD PRIMARY KEY (`reg_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_member`
--
ALTER TABLE `tbl_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`profileid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupon_master`
--
ALTER TABLE `coupon_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dcer`
--
ALTER TABLE `dcer`
  MODIFY `cer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `degreetype`
--
ALTER TABLE `degreetype`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `degree_cer`
--
ALTER TABLE `degree_cer`
  MODIFY `cer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=662;

--
-- AUTO_INCREMENT for table `doctype`
--
ALTER TABLE `doctype`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `fees_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mdcer`
--
ALTER TABLE `mdcer`
  MODIFY `cer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `regcard`
--
ALTER TABLE `regcard`
  MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_member`
--
ALTER TABLE `tbl_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `profileid` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
