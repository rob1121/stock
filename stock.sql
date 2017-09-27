-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.2.3-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5174
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for stock
CREATE DATABASE IF NOT EXISTS `stock` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `stock`;

-- Dumping structure for table stock.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int(11) NOT NULL DEFAULT 0,
  `brand_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.brands: ~13 rows (approximately)
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_active`, `brand_status`) VALUES
	(1, 'Gap', 1, 2),
	(2, 'Forever 21', 1, 2),
	(3, 'Gap', 1, 2),
	(4, 'Forever 21', 1, 2),
	(5, 'Adidas', 1, 2),
	(6, 'Gap', 1, 2),
	(7, 'Forever 21', 1, 2),
	(8, 'Adidas', 1, 2),
	(9, 'Gap', 1, 2),
	(10, 'Forever 21', 1, 2),
	(11, 'Adidas', 1, 1),
	(12, 'Gap', 1, 1),
	(13, 'Forever 21', 1, 1);
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Dumping structure for table stock.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_name` varchar(255) NOT NULL,
  `categories_active` int(11) NOT NULL DEFAULT 0,
  `categories_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.categories: ~8 rows (approximately)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_active`, `categories_status`) VALUES
	(1, 'Sports ', 1, 2),
	(2, 'Casual', 1, 2),
	(3, 'Casual', 1, 2),
	(4, 'Sport', 1, 2),
	(5, 'Casual', 1, 2),
	(6, 'Sport wear', 1, 2),
	(7, 'Casual wear', 1, 1),
	(8, 'Sports ', 1, 1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table stock.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `contact` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  KEY `searchable` (`contact`,`name`,`address`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.clients: ~7 rows (approximately)
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` (`client_id`, `name`, `address`, `status`, `contact`) VALUES
	(4, '1', '1', 1, '1'),
	(1, 'John Doe', 'jd@gmail.com', 1, '9123411111'),
	(7, 'Jay', 'Cavite', 1, '9231323432'),
	(5, 'John Doe', 'jd@gmail.com', 1, '9232323222'),
	(6, 'John Smith', 'Dasm', 1, '9232922222'),
	(2, 'John Doe', 'jd@gmail.com', 1, '9233312321'),
	(3, 'John Doe', 'jd@gmail.com', 1, '9234123123');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;

-- Dumping structure for table stock.delivery_schedule
CREATE TABLE IF NOT EXISTS `delivery_schedule` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT 0,
  `po_number` varchar(50) DEFAULT '0',
  `order_id` int(11) DEFAULT 0,
  `delivery_date` date DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.delivery_schedule: ~3 rows (approximately)
/*!40000 ALTER TABLE `delivery_schedule` DISABLE KEYS */;
INSERT INTO `delivery_schedule` (`delivery_id`, `product_id`, `po_number`, `order_id`, `delivery_date`, `quantity`) VALUES
	(3, 7, '1', 16, '2017-09-28', 1),
	(7, 7, '1', 16, '2017-09-28', 1),
	(8, 7, 'PO1234', 16, '2017-09-29', 1);
/*!40000 ALTER TABLE `delivery_schedule` ENABLE KEYS */;

-- Dumping structure for table stock.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_date` date NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `order_status` int(11) NOT NULL DEFAULT 0,
  `po_number` varchar(25) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT '',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.orders: ~20 rows (approximately)
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`order_id`, `order_date`, `client_name`, `client_contact`, `sub_total`, `vat`, `total_amount`, `discount`, `grand_total`, `paid`, `due`, `payment_type`, `payment_status`, `order_status`, `po_number`, `remarks`) VALUES
	(1, '2016-07-15', 'John Doe', '9807867564', '2700.00', '351.00', '3051.00', '1000.00', '2051.00', '1000.00', '1051.00', 2, 2, 2, '1', ''),
	(2, '2016-07-15', 'John Doe', '9808746573', '3400.00', '442.00', '3842.00', '500.00', '3342.00', '3342', '0', 2, 1, 2, '2', ''),
	(3, '2016-07-16', 'John Doe', '9809876758', '3600.00', '468.00', '4068.00', '568.00', '3500.00', '3500', '0', 2, 1, 2, '3', ''),
	(4, '2016-08-01', 'Indra', '19208130', '1200.00', '156.00', '1356.00', '1000.00', '356.00', '356', '0.00', 2, 1, 2, '4', ''),
	(5, '2016-07-16', 'John Doe', '9808767689', '3600.00', '468.00', '4068.00', '500.00', '3568.00', '3568', '0', 2, 1, 2, '5', ''),
	(6, '2017-09-28', '1', '9123411111', '3600.00', '468.00', '4068.00', '0', '4068.00', '0', '4068.00', 2, 3, 2, '123456', ''),
	(7, '2017-09-28', '1', '9123411111', '3600.00', '468.00', '4068.00', '0', '4068.00', '0', '4068.00', 2, 3, 2, '123456', ''),
	(8, '2017-09-28', '6', '9232922222', '4800.00', '624.00', '5424.00', '0', '5424.00', '0', '5424.00', 2, 3, 2, '321', ''),
	(9, '2017-09-27', '7', '9231323432', '3600.00', '468.00', '4068.00', '1', '4067.00', '1', '4066.00', 2, 3, 2, '14344', ''),
	(10, '2017-09-28', '7', '9231323432', '3600.00', '468.00', '4068.00', '1', '4067.00', '1', '4066.00', 2, 2, 2, '1', ''),
	(11, '2017-09-28', '2', '9233312321', '3600.00', '468.00', '4068.00', '1', '4067.00', '1', '4066.00', 2, 1, 2, '111', ''),
	(12, '2017-09-28', '7', '9231323432', '3600.00', '468.00', '4068.00', '1', '4067.00', '1', '4066.00', 2, 1, 2, '1', ''),
	(13, '2017-09-28', '1', '9123411111', '1200.00', '156.00', '1356.00', '1', '1355.00', '1', '1354.00', 2, 1, 2, '1', ''),
	(14, '2017-09-13', '6', '9232922222', '1200.00', '156.00', '1356.00', '1', '1355.00', '1', '1354.00', 1, 2, 2, '11', ''),
	(15, '2017-09-28', '7', '9231323432', '2400.00', '312.00', '2712.00', '1', '2711.00', '1', '2710.00', 2, 1, 2, '11111111111', ''),
	(16, '2017-09-28', '7', '9231323432', '2400.00', '312.00', '2712.00', '1', '2711.00', '1', '2710.00', 2, 1, 1, '1', ''),
	(17, '2017-09-27', '6', '9232922222', '1200.00', '156.00', '1356.00', '0', '1356.00', '0', '1356.00', 2, 3, 1, 'PO0987', ''),
	(18, '2017-09-27', '1', '9123411111', '4800.00', '624.00', '5424.00', '0', '5424.00', '0', '5424.00', 2, 3, 2, 'PO1234', ''),
	(19, '2017-09-27', '1', '9123411111', '4800.00', '624.00', '5424.00', '0', '5424.00', '0', '5424.00', 2, 3, 1, 'PO1234', ''),
	(20, '2017-09-28', '6', '9232922222', '3600.00', '468.00', '4068.00', '1', '4067.00', '1', '4066.00', 2, 2, 1, '123123', ' ');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

-- Dumping structure for table stock.order_item
CREATE TABLE IF NOT EXISTS `order_item` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `order_item_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.order_item: ~32 rows (approximately)
/*!40000 ALTER TABLE `order_item` DISABLE KEYS */;
INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `rate`, `total`, `order_item_status`) VALUES
	(1, 1, 1, '1', '1500', '1500.00', 2),
	(2, 1, 2, '1', '1200', '1200.00', 2),
	(3, 2, 3, '2', '1200', '2400.00', 2),
	(4, 2, 4, '1', '1000', '1000.00', 2),
	(5, 3, 5, '2', '1200', '2400.00', 2),
	(6, 3, 6, '1', '1200', '1200.00', 2),
	(7, 4, 5, '1', '1200', '1200.00', 2),
	(8, 5, 7, '2', '1200', '2400.00', 2),
	(9, 5, 8, '1', '1200', '1200.00', 2),
	(10, 6, 8, '2', '1200', '2400.00', 2),
	(11, 6, 7, '1', '1200', '1200.00', 2),
	(12, 7, 8, '2', '1200', '2400.00', 2),
	(13, 7, 7, '1', '1200', '1200.00', 2),
	(14, 8, 7, '3', '1200', '3600.00', 2),
	(15, 8, 8, '1', '1200', '1200.00', 2),
	(16, 9, 7, '2', '1200', '2400.00', 2),
	(17, 9, 8, '1', '1200', '1200.00', 2),
	(18, 10, 7, '2', '1200', '2400.00', 2),
	(19, 10, 8, '1', '1200', '1200.00', 2),
	(20, 11, 8, '1', '1200', '1200.00', 2),
	(21, 11, 7, '2', '1200', '2400.00', 2),
	(22, 12, 7, '2', '1200', '2400.00', 2),
	(23, 12, 8, '1', '1200', '1200.00', 2),
	(24, 13, 7, '1', '1200', '1200.00', 2),
	(25, 14, 7, '1', '1200', '1200.00', 2),
	(26, 15, 7, '2', '1200', '2400.00', 2),
	(27, 16, 7, '2', '1200', '2400.00', 1),
	(28, 17, 7, '1', '1200', '1200.00', 1),
	(29, 18, 7, '4', '1200', '4800.00', 2),
	(30, 19, 7, '4', '1200', '4800.00', 1),
	(31, 20, 7, '2', '1200', '2400.00', 1),
	(32, 20, 8, '1', '1200', '1200.00', 1);
/*!40000 ALTER TABLE `order_item` ENABLE KEYS */;

-- Dumping structure for table stock.product
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `product_image` text NOT NULL,
  `brand_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.product: ~8 rows (approximately)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` (`product_id`, `product_name`, `product_image`, `brand_id`, `categories_id`, `quantity`, `rate`, `active`, `status`) VALUES
	(1, 'Half pant', '../assests/images/stock/2847957892502c7200.jpg', 1, 2, '19', '1500', 2, 2),
	(2, 'T-Shirt', '../assests/images/stock/163965789252551575.jpg', 2, 2, '9', '1200', 2, 2),
	(3, 'Half Pant', '../assests/images/stock/13274578927924974b.jpg', 5, 3, '18', '1200', 2, 2),
	(4, 'T-Shirt', '../assests/images/stock/12299578927ace94c5.jpg', 6, 3, '29', '1000', 2, 2),
	(5, 'Half Pant', '../assests/images/stock/24937578929c13532e.jpg', 8, 5, '17', '1200', 2, 2),
	(6, 'Polo T-Shirt', '../assests/images/stock/10222578929f733dbf.jpg', 9, 5, '29', '1200', 2, 2),
	(7, 'Half Pant', '../assests/images/stock/1770257893463579bf.jpg', 11, 7, '8', '1200', 1, 1),
	(8, 'Polo T-shirt', '../assests/images/stock/136715789347d1aea6.jpg', 12, 7, '10', '1200', 1, 1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Dumping structure for table stock.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table stock.users: ~0 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `username`, `password`, `email`) VALUES
	(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
