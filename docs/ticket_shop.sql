-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 21, 2025 at 10:42 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticket_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_categories` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_categories`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_categories`, `name`, `description`) VALUES
(1, 'Patinaj', 'Bilete la spectacole de patinaj artistic, hochei și show-uri pe gheață.'),
(2, 'Teatru', 'Bilete la piese de teatru clasice, contemporane și spectacole de revistă.'),
(3, 'Concert', 'Bilete la concerte de muzică pop, rock, electronică și evenimente live majore.'),
(4, 'Opera', 'Bilete la spectacole de operă și balet, inclusiv gale și premiere.'),
(5, 'Fotbal', 'Bilete la meciuri de fotbal din campionatul intern și competiții internaționale.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id_order` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `stripe_transaction_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_order`),
  KEY `user_id` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total_amount`, `order_status`, `stripe_transaction_id`, `created_at`) VALUES
(1, 1, 580.50, 'Completed', 'ch_1J2kH7D4eT2oQ3rL4uP5vW6xY7zS8', '2025-11-20 10:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id_item` int NOT NULL AUTO_INCREMENT,
  `id_order` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item`),
  KEY `order_id` (`id_order`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id_item`, `id_order`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 2, 2, 180.00),
(2, 1, 5, 1, 220.50);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id_products` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `venue` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `event_date` date NOT NULL,
  `available_tickets` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_products`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_products`, `category_id`, `name`, `code`, `venue`, `event_date`, `available_tickets`, `price`, `description`, `image`) VALUES
(1, 5, 'Meci Steaua vs Dinamo', 'FOT001', 'Arena Națională', '2025-12-15', 5000, 55.00, 'Derby-ul tradițional, Peluza Nord.', '/images/fotbal.jpg'),
(2, 3, 'Concert Ed Sheeran', 'CON002', 'Cluj Arena', '2026-06-25', 1200, 180.00, 'Bilet normal, loc pe scaun.', '/images/concert.jpg'),
(3, 1, 'Spărgătorul de Nuci', 'PAT003', 'Sala Polivalentă', '2025-12-24', 350, 90.00, 'Spectacol de patinaj artistic pe gheață.', '/images/patinaj.jpg'),
(4, 2, 'O Noapte Furtunoasă', 'TEA004', 'Teatrul Național', '2026-01-10', 150, 45.00, 'Comedie clasică I.L. Caragiale.', '/images/teatru.jpg'),
(5, 4, 'Aida (Verdi)', 'OPE005', 'Opera Română', '2026-03-01', 80, 220.50, 'Loja centrală, premieră.', '/images/opera.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

DROP TABLE IF EXISTS `tbl_cart`;
CREATE TABLE IF NOT EXISTS `tbl_cart` (
  `id_cart` int NOT NULL AUTO_INCREMENT,
  `id_product` int NOT NULL,
  `id_user` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id_cart`),
  KEY `product_id` (`id_product`),
  KEY `member_id` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`id_cart`, `id_product`, `id_user`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', '', '$2y$10$wN.9.3X9lR/t/lZ8b.d3iO1.3S7jA2g.j4dO9mX9lR/wB4dM7g.j4', ''),
(2, 'irina', 'i@yahoo.com', '$2y$10$Sxwg8XL2zhAeQyfvDLF2QuYV1VineuJCDPNYCs/WVrHHMK8u/UmB2', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
