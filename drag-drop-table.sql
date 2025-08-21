-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 20, 2025 at 10:50 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drag-drop-table`
--

-- --------------------------------------------------------

--
-- Table structure for table `display_order_seq`
--

DROP TABLE IF EXISTS `display_order_seq`;
CREATE TABLE IF NOT EXISTS `display_order_seq` (
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `display_order_seq`
--

INSERT INTO `display_order_seq` (`id`) VALUES
(40);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `gender` varchar(10) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `display_order` int NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `phone`, `email`, `dob`, `status`, `gender`, `position`, `display_order`, `last_update`) VALUES
(1, 'Sunita Singh', '101 Sector 17, Chandigarh', '9876543213', 'sunita.singh@example.com', '1995-02-10', 'active', 'Female', 'UX Designer', 29, '2025-08-19 04:04:51'),
(2, 'Rahul Verma', '789 Civil Lines, Noida', '9876543212', 'rahul.verma@example.com', '1988-11-30', 'active', 'Male', 'Data Analyst', 39, '2025-08-19 04:04:51'),
(3, 'Priya Sharma', '456 Park Street, Mumbai', '9876543211', 'priya.sharma@example.com', '1992-08-22', 'active', 'Female', 'Project Manager', 38, '2025-08-19 04:04:51'),
(4, 'Amit Kumar', '123 MG Road, Delhi', '9876543210', 'amit.kumar@example.com', '1990-05-15', 'active', 'Male', 'Software Engineer', 2, '2025-08-20 10:46:15'),
(5, 'Vikram Rathore', '21B Baker Street, Kolkata', '9876543214', 'vikram.rathore@example.com', '1985-07-19', 'active', 'Male', 'Marketing Head', 31, '2025-08-19 04:04:51'),
(6, 'Anjali Mehta', '33 Jubilee Hills, Hyderabad', '9876543215', 'anjali.mehta@example.com', '1998-01-25', 'active', 'Female', 'HR Executive', 3, '2025-08-20 10:46:15'),
(7, 'Sandeep Gupta', '55 Koramangala, Bangalore', '9876543216', 'sandeep.gupta@example.com', '1991-04-05', 'inactive', 'Male', 'DevOps Engineer', 6, '2025-08-13 11:06:38'),
(8, 'Kavita Patel', '88 SG Highway, Ahmedabad', '9876543217', 'kavita.patel@example.com', '1993-09-12', 'active', 'Female', 'QA Tester', 14, '2025-08-19 04:04:51'),
(9, 'Manoj Tiwari', '12 Ashok Nagar, Chennai', '9876543218', 'manoj.tiwari@example.com', '1989-06-28', 'inactive', 'Male', 'Business Development', 8, '2025-08-13 11:22:23'),
(10, 'Deepika Nair', '7 C.G. Road, Pune', '9876543219', 'deepika.nair@example.com', '1996-12-01', 'active', 'Female', 'Content Writer', 4, '2025-08-20 10:46:15'),
(11, 'Rajesh Khanna', '42 Marine Drive, Mumbai', '9876543220', 'rajesh.k@example.com', '1994-03-20', 'active', 'Male', 'Systems Administrator', 40, '2025-08-19 04:04:51'),
(12, 'Pooja Reddy', '15 MG Road, Bangalore', '9876543221', 'pooja.r@example.com', '1997-11-08', 'active', 'Female', 'Graphic Designer', 37, '2025-08-19 04:04:51'),
(13, 'Arjun Singh', '7 Park Avenue, Delhi', '9876543222', 'arjun.s@example.com', '1987-01-14', 'active', 'Male', 'Operations Manager', 11, '2025-08-20 10:46:15'),
(14, 'Neha Desai', '11 Carter Road, Mumbai', '9876543223', 'neha.d@example.com', '1999-05-30', 'inactive', 'Female', 'Social Media Manager', 13, '2025-08-13 11:06:51'),
(15, 'Sanjay Mishra', '29 VIP Road, Lucknow', '9876543224', 'sanjay.m@example.com', '1986-10-02', 'active', 'Male', 'Sales Director', 25, '2025-08-19 04:04:51'),
(16, 'Meera Iyer', '5 Brigade Road, Bangalore', '9876543225', 'meera.i@example.com', '1990-08-18', 'inactive', 'Female', 'Lead Developer', 15, '2025-08-13 11:22:23'),
(17, 'Alok Nath', '18 Civil Lines, Allahabad', '9876543226', 'alok.n@example.com', '1984-04-25', 'active', 'Male', 'IT Support Specialist', 1, '2025-08-20 10:46:15'),
(18, 'Rina Das', '3 Netaji Subhas Road, Kolkata', '9876543227', 'rina.d@example.com', '1992-07-07', 'active', 'Female', 'Accountant', 24, '2025-08-19 04:04:51'),
(19, 'Imran Khan', '24 Banjara Hills, Hyderabad', '9876543228', 'imran.k@example.com', '1995-03-16', 'active', 'Male', 'Frontend Developer', 7, '2025-08-20 10:46:15'),
(20, 'Fatima Sheikh', '9 Mohammed Ali Road, Mumbai', '9876543229', 'fatima.s@example.com', '1993-11-21', 'inactive', 'Female', 'Product Owner', 19, '2025-08-13 11:06:58'),
(43, 'pawan', 'noida', '9199208167', 'user1@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 34, '2025-08-19 04:04:51'),
(42, 'pawan', 'pawan', '9199208167', 'user1@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 35, '2025-08-19 04:04:51'),
(41, 'pawan', 'Noida', '9199208167', 'hellopawan@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 36, '2025-08-19 04:04:51'),
(55, 'Shreya', 'Noida', '9199208167', 'shreya.trivedi@gmail.com', '2025-08-18', 'active', 'Female', 'DevOps', 26, '2025-08-19 04:04:51'),
(44, 'richa', 'noida', '9199208167', 'richa@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 0, '2025-08-20 10:46:08'),
(45, 'richa sharma', 'noida', '9199208167', 'rcicha1@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 22, '2025-08-19 04:04:51'),
(46, 'richa singh', 'noida', '9199208167', 'richa2@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 23, '2025-08-19 04:04:51'),
(47, 'richa', 'noida', '9199208167', 'richa.sharma@gmail.com', '2025-08-14', 'active', 'Female', 'developer', 17, '2025-08-19 04:04:51'),
(48, 'richa', 'noida', '9199208167', 'richa.dev@gmail.com', '2025-08-14', 'active', 'Female', 'developer', 20, '2025-08-19 04:04:51'),
(49, 'Divya Sharma', 'Noida, Uttar-Pradesh', '9199208167', 'ucontactdivyabharti@gmail.com', '2025-08-14', 'active', 'Male', 'developer', 5, '2025-08-20 10:46:15'),
(57, 'kamal', 'Noida', '9199208167', 'kamal.kumar@gmail.com', '2025-08-18', 'active', 'Male', 'Manager', 9, '2025-08-20 10:46:15'),
(50, 'Manish', '76 Noida, Uttar Pradesh', '9693059418', 'minish.singh98560@gmail.com', '2025-08-14', 'active', 'Male', 'Software Testing', 21, '2025-08-19 04:04:51'),
(51, 'ramesh', '104, Alpha, Greater Noida', '9199208167', 'hello.ramesh@gmail.com', '2025-08-14', 'active', 'Male', 'lead', 10, '2025-08-20 10:46:15'),
(52, 'suresh', '109, Alpha 2, Greater Noida', '9199208167', 'hello.suresh@gmail.com', '2025-08-14', 'active', 'Male', 'manager', 30, '2025-08-19 04:04:51'),
(53, 'Sita', '120, Alpha-2, Greater Noida', '9099208167', 'sita.ug@gmail.com', '2025-08-14', 'active', 'Male', 'web developer', 28, '2025-08-19 04:04:51'),
(54, 'Mahesh', 'Noida', '9199208167', 'mahesh.babu@gmail.com', '2025-08-14', 'active', 'Male', 'manager', 16, '2025-08-19 04:04:51'),
(56, 'Shreya Trivedi', 'Chandigarah', '8565239856', 'shreyaaa.trividi@gmail.com', '2025-08-18', 'active', 'Female', 'Developer', 27, '2025-08-19 04:04:51'),
(58, 'Kamal kumar', 'Noida', '9199208167', 'kamal@gmail.com', '2025-08-18', 'active', 'Male', 'manager', 12, '2025-08-19 04:04:51'),
(59, 'Manu', 'Delhi', '9199208167', 'manu@gmail.com', '2025-08-18', 'active', 'Male', 'developer', 32, '2025-08-19 04:04:51'),
(60, 'Mani', 'Delhi', '9199208167', 'mani@gmail.com', '2025-08-18', 'active', 'Male', 'developer', 18, '2025-08-19 04:04:51'),
(62, 'Neha', 'Punjab', '9199208167', 'neha.sharma@gmail.com', '2025-08-18', 'active', 'Female', 'Developer', 33, '2025-08-19 04:04:51');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
