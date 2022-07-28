-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jul 22, 2022 at 07:47 AM
-- Server version: 8.0.18
-- PHP Version: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy`
--
CREATE DATABASE IF NOT EXISTS `pharmacy` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `pharmacy`;

-- --------------------------------------------------------

--
-- Table structure for table `benefit`
--

DROP TABLE IF EXISTS `benefit`;
CREATE TABLE IF NOT EXISTS `benefit` (
  `benefit_id` int(11) NOT NULL AUTO_INCREMENT,
  `net_benefit` float NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`benefit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=ucs2;

--
-- Dumping data for table `benefit`
--

INSERT INTO `benefit` (`benefit_id`, `net_benefit`, `date`) VALUES
(18, 94, '2022-07-17'),
(19, 94, '2022-07-17'),
(20, 94, '2022-07-17'),
(21, 94, '2022-07-17'),
(22, 94, '2022-07-17'),
(23, 94, '2022-07-17'),
(24, 235, '2022-07-17'),
(25, 12, '2022-07-18'),
(26, 94, '2022-07-18'),
(27, 94, '2022-07-18'),
(28, 94, '2022-07-18'),
(29, 94, '2022-07-18'),
(30, 94, '2022-07-18'),
(31, 47, '2022-07-18'),
(32, 47, '2022-07-18'),
(33, 47, '2022-07-18'),
(34, 20, '2022-07-18'),
(35, 20, '2022-07-18'),
(36, 94, '2022-07-18'),
(37, 94, '2022-07-18'),
(38, 94, '2022-07-18'),
(39, 18, '2022-07-19'),
(40, 18, '2022-07-19'),
(41, 36, '2022-07-19'),
(42, 4, '2022-07-19'),
(43, 47, '2022-07-19'),
(44, 47, '2022-07-19');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `father_name` varchar(128) NOT NULL,
  `contact_num` char(10) NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `contact_num` (`contact_num`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `name`, `father_name`, `contact_num`) VALUES
(35, 'Jahan', 'Mahdi', '0789231783');

-- --------------------------------------------------------

--
-- Table structure for table `debt_detail`
--

DROP TABLE IF EXISTS `debt_detail`;
CREATE TABLE IF NOT EXISTS `debt_detail` (
  `debt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_id` int(11) NOT NULL,
  `debt_date` date NOT NULL,
  `debt_amount` float NOT NULL,
  PRIMARY KEY (`debt_id`),
  KEY `customer_debtDetail_fk` (`cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `debt_detail`
--

INSERT INTO `debt_detail` (`debt_id`, `cust_id`, `debt_date`, `debt_amount`) VALUES
(26, 35, '2022-07-17', 200);

-- --------------------------------------------------------

--
-- Table structure for table `expire_medicine`
--

DROP TABLE IF EXISTS `expire_medicine`;
CREATE TABLE IF NOT EXISTS `expire_medicine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `whole_sale_price` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=ucs2;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

DROP TABLE IF EXISTS `medicine`;
CREATE TABLE IF NOT EXISTS `medicine` (
  `med_id` int(11) NOT NULL AUTO_INCREMENT,
  `generic_name` varchar(128) DEFAULT NULL,
  `comm_name` varchar(128) NOT NULL,
  `exp_date` date NOT NULL,
  `catagory_id` int(11) NOT NULL,
  `retail_price` int(11) NOT NULL,
  PRIMARY KEY (`med_id`),
  KEY `catagory_medicine_fk` (`catagory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`med_id`, `generic_name`, `comm_name`, `exp_date`, `catagory_id`, `retail_price`) VALUES
(135, 'Ceftraixone', 'Novosive', '2024-05-06', 4, 150),
(136, 'Salbotamol', 'Salmol', '2023-10-24', 1, 20),
(137, 'Cold Kit Plus', 'Cold Kit Plus', '2024-10-22', 1, 30),
(138, 'Diclifonac', 'vorin', '2025-09-14', 3, 30),
(139, 'Fulica', 'Folica', '2023-10-04', 11, 140),
(140, 'Gravinate', 'Gravinate', '2022-08-03', 5, 50),
(141, 'Azythomycin', 'Azitoma', '2023-10-17', 1, 150),
(142, 'Prixicom', 'Peram', '2025-05-05', 1, 20);

-- --------------------------------------------------------

--
-- Table structure for table `medicine_catagory`
--

DROP TABLE IF EXISTS `medicine_catagory`;
CREATE TABLE IF NOT EXISTS `medicine_catagory` (
  `catagory_id` int(11) NOT NULL AUTO_INCREMENT,
  `catagory_name` varchar(64) NOT NULL,
  PRIMARY KEY (`catagory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=ucs2;

--
-- Dumping data for table `medicine_catagory`
--

INSERT INTO `medicine_catagory` (`catagory_id`, `catagory_name`) VALUES
(1, 'Tablet'),
(2, 'Capsule'),
(3, 'Ampule'),
(4, 'Vial'),
(5, 'Syrup'),
(6, 'Lotion'),
(7, 'Drop'),
(8, 'Cream'),
(9, 'Ointment'),
(10, 'Spray'),
(11, 'Shampoo'),
(12, 'Soup'),
(13, 'Hygienic'),
(14, 'Different');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist`
--

DROP TABLE IF EXISTS `pharmacist`;
CREATE TABLE IF NOT EXISTS `pharmacist` (
  `name` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  PRIMARY KEY (`name`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pharmacist`
--

INSERT INTO `pharmacist` (`name`, `password`) VALUES
('Mahdi1999', 'Mahdi$72000Madadi');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `purchase_id` int(11) NOT NULL AUTO_INCREMENT,
  `med_id` int(11) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `purchase_amount` int(11) NOT NULL,
  `purchase_price` float NOT NULL,
  PRIMARY KEY (`purchase_id`),
  KEY `medicine_purchase_fk` (`med_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchase_id`, `med_id`, `purchase_date`, `purchase_amount`, `purchase_price`) VALUES
(11, 135, '2022-07-17', 255, 103),
(12, 136, '2022-07-18', 47, 16),
(13, 137, '2022-07-18', 398, 18),
(14, 138, '2022-07-18', 892, 21),
(15, 139, '2022-07-18', 60, 96),
(16, 140, '2022-07-18', 50, 30),
(17, 141, '2022-07-19', 500, 100),
(18, 142, '2022-07-19', 30, 14);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `med_id` int(11) DEFAULT NULL,
  `sales_date` date NOT NULL,
  `sale_amount` smallint(6) NOT NULL,
  `catagory_id` int(11) NOT NULL,
  `sales_price` float NOT NULL,
  PRIMARY KEY (`sales_id`),
  KEY `catagory_sales_fk` (`catagory_id`),
  KEY `medicine_sales_fk` (`med_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sales_id`, `med_id`, `sales_date`, `sale_amount`, `catagory_id`, `sales_price`) VALUES
(2, 135, '2022-07-17', 3, 4, 150),
(3, 135, '2022-07-17', 3, 4, 150),
(4, 135, '2022-07-17', 1, 4, 150),
(5, 135, '2022-07-17', 5, 4, 150),
(6, 135, '2022-07-17', 5, 4, 150),
(7, 135, '2022-07-17', 2, 4, 150),
(8, 135, '2022-07-17', 5, 4, 150),
(9, 136, '2022-07-18', 3, 1, 20),
(10, 135, '2022-07-18', 2, 4, 150),
(11, 135, '2022-07-18', 2, 4, 150),
(12, 135, '2022-07-18', 2, 4, 150),
(13, 135, '2022-07-18', 2, 4, 150),
(14, 135, '2022-07-18', 2, 4, 150),
(15, 135, '2022-07-18', 1, 4, 150),
(16, 135, '2022-07-18', 1, 4, 150),
(17, 135, '2022-07-18', 1, 4, 150),
(18, 136, '2022-07-18', 5, 1, 20),
(19, 136, '2022-07-18', 5, 1, 20),
(20, 135, '2022-07-18', 2, 4, 150),
(21, 135, '2022-07-18', 2, 4, 150),
(22, 135, '2022-07-18', 2, 4, 150),
(23, 138, '2022-07-19', 2, 3, 30),
(24, 138, '2022-07-19', 2, 3, 30),
(25, 138, '2022-07-19', 4, 3, 30),
(26, 137, '2022-07-19', 2, 1, 20),
(27, 135, '2022-07-19', 1, 4, 150),
(28, 135, '2022-07-19', 1, 4, 150);

--
-- Triggers `sales`
--
DROP TRIGGER IF EXISTS `sales_after_insert`;
DELIMITER $$
CREATE TRIGGER `sales_after_insert` AFTER INSERT ON `sales` FOR EACH ROW UPDATE purchase SET purchase_amount = purchase_amount - NEW.sale_amount
WHERE purchase.med_id = NEW.med_id
$$
DELIMITER ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `debt_detail`
--
ALTER TABLE `debt_detail`
  ADD CONSTRAINT `customer_debtDetail_fk` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`) ON UPDATE CASCADE;

--
-- Constraints for table `medicine`
--
ALTER TABLE `medicine`
  ADD CONSTRAINT `catagory_medicine_fk` FOREIGN KEY (`catagory_id`) REFERENCES `medicine_catagory` (`catagory_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `medicine_purchase_fk` FOREIGN KEY (`med_id`) REFERENCES `medicine` (`med_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `medicine_sales_fk` FOREIGN KEY (`med_id`) REFERENCES `medicine` (`med_id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
