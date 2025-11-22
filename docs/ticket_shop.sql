-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1:3306
-- Timp de generare: nov. 21, 2025 la 04:21 PM
-- Versiune server: 9.1.0
-- Versiune PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `ticket_shop`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `categories`
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
-- Eliminarea datelor din tabel `categories`
--

INSERT INTO `categories` (`id_categories`, `name`, `description`) VALUES
(1, 'Patinaj', 'Bilete la spectacole de patinaj artistic, hochei și show-uri pe gheață.'),
(2, 'Teatru', 'Bilete la piese de teatru clasice, contemporane și spectacole de revistă.'),
(3, 'Concert', 'Bilete la concerte de muzică pop, rock, electronică și evenimente live majore.'),
(4, 'Opera', 'Bilete la spectacole de operă și balet, inclusiv gale și premiere.'),
(5, 'Fotbal', 'Bilete la meciuri de fotbal din campionatul intern și competiții internaționale.');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `orders`
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total_amount`, `order_status`, `stripe_transaction_id`, `created_at`) VALUES
(1, 1, 580.50, 'Completed', 'ch_1J2kH7D4eT2oQ3rL4uP5vW6xY7zS8', '2025-11-20 10:30:00'),
(2, 3, 110.00, 'pending', NULL, '2025-11-21 15:55:42'),
(3, 3, 110.00, 'pending', NULL, '2025-11-21 16:04:04'),
(4, 3, 55.00, 'pending', NULL, '2025-11-21 16:04:36'),
(5, 3, 110.00, 'pending', NULL, '2025-11-21 16:11:46'),
(6, 3, 90.00, 'pending', NULL, '2025-11-21 16:16:38'),
(7, 3, 55.00, 'pending', NULL, '2025-11-21 16:22:30'),
(8, 3, 55.00, 'pending', NULL, '2025-11-21 16:23:39'),
(9, 3, 110.00, 'pending', NULL, '2025-11-21 16:32:37'),
(10, 3, 55.00, 'pending', NULL, '2025-11-21 16:34:59'),
(11, 3, 55.00, 'pending', NULL, '2025-11-21 16:51:45'),
(12, 3, 180.00, 'pending', NULL, '2025-11-21 17:47:28');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `order_items`
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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `order_items`
--

INSERT INTO `order_items` (`id_item`, `id_order`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 2, 2, 180.00),
(2, 1, 5, 1, 220.50),
(3, 3, 1, 2, 55.00),
(4, 4, 1, 1, 55.00),
(5, 5, 1, 2, 55.00),
(6, 6, 3, 1, 90.00),
(7, 7, 1, 1, 55.00),
(8, 8, 1, 1, 55.00),
(9, 9, 1, 2, 55.00),
(10, 10, 1, 1, 55.00),
(11, 11, 1, 1, 55.00),
(12, 12, 2, 1, 180.00);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `products`
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
-- Eliminarea datelor din tabel `products`
--

INSERT INTO `products` (`id_products`, `category_id`, `name`, `code`, `venue`, `event_date`, `available_tickets`, `price`, `description`, `image`) VALUES
(1, 5, 'Meci Steaua vs Dinamo', 'FOT001', 'Arena Națională', '2025-12-15', 4989, 55.00, 'Derby-ul tradițional, Peluza Nord.', '/bilete-evenimente-cluj/assets/images/dinamo_vs_steaua.webp'),
(2, 3, 'Concert Ed Sheeran', 'CON002', 'Cluj Arena', '2026-06-25', 1199, 180.00, 'Bilet normal, loc pe scaun.', '/bilete-evenimente-cluj/assets/images/Ed-Sheeran.jpg'),
(3, 1, 'Spărgătorul de Nuci', 'PAT003', 'Sala Polivalentă', '2025-12-24', 349, 90.00, 'Spectacol de patinaj artistic pe gheață.', '/bilete-evenimente-cluj/assets/images/nutcracker.webp'),
(4, 2, 'O Noapte Furtunoasă', 'TEA004', 'Teatrul Național', '2026-01-10', 150, 45.00, 'Comedie clasică I.L. Caragiale.', '/bilete-evenimente-cluj/assets/images/noapte_furtunoasa.webp'),
(5, 4, 'Aida (Verdi)', 'OPE005', 'Opera Română', '2026-03-01', 80, 220.50, 'Loja centrală, premieră.', '/bilete-evenimente-cluj/assets/images/Opera-Aida-Verdi.jpg');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `tbl_cart`
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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `tbl_cart`
--

INSERT INTO `tbl_cart` (`id_cart`, `id_product`, `id_user`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 4, 1, 1);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `users`
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Eliminarea datelor din tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(2, 'irina', 'i@yahoo.com', '$2y$10$Sxwg8XL2zhAeQyfvDLF2QuYV1VineuJCDPNYCs/WVrHHMK8u/UmB2', 'user'),
(3, 'vanesa', 'vanesa@yahoo.com', '$2y$10$677ZzzzOoqxwuHClhaEgI.KVmth7AfrRrNu8wfJ7rwKLdGPNps7QK', 'user'),
(1, 'admin', 'admin@yahoo.com', '$2y$10$hRgIGC5ScL.0heYmETJEm.CKNRDurhuzsTPKOs4n4TSTqCCt8ZW5e', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
