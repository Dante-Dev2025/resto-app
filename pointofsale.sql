-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2026 at 04:37 PM
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
-- Database: `pointofsale`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `total_price` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `kitchen_note` text DEFAULT NULL,
  `status` enum('pending','cooking','ready','served','finished') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `table_number`, `total_price`, `payment_method`, `kitchen_note`, `status`, `created_at`) VALUES
(51, '#ORD-5467', 'TAKEAWAY', 135000, 'QRIS', '', 'finished', '2025-11-27 06:53:40'),
(52, '#ORD-1292', '9', 45000, 'QRIS', '', 'finished', '2025-11-27 07:02:15'),
(53, '#ORD-9268', '4', 836000, 'QRIS', 'LAPAAAAAAAAAAR', 'finished', '2025-11-27 07:20:02'),
(54, '#ORD-2266', 'TAKEAWAY', 45000, 'Cash', '', 'served', '2025-11-27 07:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `qty`, `price`, `subtotal`) VALUES
(25, 15, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(26, 15, 4, 'Spicy Chicken Wings', 1, 38000, 38000),
(27, 16, 4, 'Spicy Chicken Wings', 3, 38000, 114000),
(28, 16, 8, 'Ayam Bakar', 1, 20000, 20000),
(29, 17, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(30, 17, 4, 'Spicy Chicken Wings', 1, 38000, 38000),
(31, 18, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(32, 18, 6, 'Iced Lemon Tea', 1, 12000, 12000),
(33, 19, 1, 'Super Cheese Burgir', 1, 45000, 45000),
(34, 19, 2, 'Golden French Fries', 1, 25000, 25000),
(35, 20, 1, 'Super Cheese Burgir', 6, 45000, 270000),
(36, 21, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(37, 22, 1, 'Super Cheese Burgir', 5, 45000, 225000),
(38, 23, 1, 'Super Cheese Burgir', 4, 45000, 180000),
(39, 24, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(40, 25, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(41, 26, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(42, 27, 1, 'Super Cheese Burgir', 1, 45000, 45000),
(43, 28, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(44, 29, 1, 'Super Cheese Burgir', 4, 45000, 180000),
(45, 30, 1, 'Super Cheese Burgir', 14, 45000, 630000),
(46, 31, 4, 'Spicy Chicken Wings', 5, 38000, 190000),
(47, 32, 4, 'Spicy Chicken Wings', 5, 38000, 190000),
(48, 33, 4, 'Spicy Chicken Wings', 1, 38000, 38000),
(49, 34, 1, 'Super Cheese Burgir', 4, 45000, 180000),
(50, 35, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(51, 35, 4, 'Spicy Chicken Wings', 1, 38000, 38000),
(52, 35, 8, 'Ayam Bakar', 2, 20000, 40000),
(53, 36, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(54, 37, 1, 'Super Cheese Burgir', 3, 45000, 135000),
(55, 37, 2, 'Golden French Fries', 1, 25000, 25000),
(56, 38, 1, 'Super Cheese Burgir', 2, 45000, 90000),
(57, 38, 3, 'Coca Cola Zero', 3, 15000, 45000),
(58, 39, 1, 'Super Cheese Burger', 5, 45000, 225000),
(59, 40, 1, 'Super Cheese Burger', 1, 45000, 45000),
(60, 41, 1, 'Super Cheese Burger', 2, 45000, 90000),
(61, 42, 4, 'Spicy Chicken Wings', 1, 38000, 38000),
(62, 43, 1, 'Super Cheese Burger', 5, 45000, 225000),
(63, 44, 1, 'Super Cheese Burger', 4, 45000, 180000),
(64, 45, 1, 'Super Cheese Burger', 2, 45000, 90000),
(65, 46, 8, 'Ayam Bakar', 2, 20000, 40000),
(66, 47, 1, 'Super Cheese Burger', 4, 45000, 180000),
(67, 48, 1, 'Super Cheese Burger', 3, 45000, 135000),
(68, 49, 1, 'Super Cheese Burger', 2, 45000, 90000),
(69, 50, 1, 'Super Cheese Burger', 3, 45000, 135000),
(70, 51, 1, 'Super Cheese Burger', 3, 45000, 135000),
(71, 52, 1, 'Super Cheese Burger', 1, 45000, 45000),
(72, 53, 1, 'Super Cheese Burger', 10, 45000, 450000),
(73, 53, 4, 'Spicy Chicken Wings', 7, 38000, 266000),
(74, 53, 6, 'Iced Lemon Tea', 10, 12000, 120000),
(75, 54, 1, 'Super Cheese Burger', 1, 45000, 45000);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `category` enum('food','drink') DEFAULT 'food',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_url`, `category`, `created_at`, `stock`) VALUES
(1, 'Super Cheese Burger', 45000, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=500&q=80', 'food', '2025-11-25 11:29:12', 2),
(2, 'Golden French Fries', 25000, 'https://images.unsplash.com/photo-1576107232684-1279f390859f?auto=format&fit=crop&w=500&q=80', 'food', '2025-11-25 11:29:12', 41),
(3, 'Coca Cola Zero', 15000, 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?auto=format&fit=crop&w=500&q=80', 'drink', '2025-11-25 11:29:12', 15),
(4, 'Spicy Chicken Wings', 38000, 'https://images.unsplash.com/photo-1608039829572-78524f79c4c7?auto=format&fit=crop&w=500&q=80', 'food', '2025-11-25 11:29:12', 10),
(5, 'Strawberry Sundae', 18000, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=500&q=80', 'drink', '2025-11-25 11:29:12', 0),
(6, 'Iced Lemon Tea', 12000, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=500&q=80', 'drink', '2025-11-25 11:29:12', 80),
(9, 'Ayam bakar', 200000, 'https://asset.kompas.com/crops/WTuA1Jn_cJEFlr9UgBhA-72n8yI=/3x0:700x465/1200x800/data/photo/2020/12/30/5fec5602f116e.jpg', 'food', '2025-11-27 07:23:50', 100);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('total_tables', '20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier','guest') DEFAULT 'guest',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(10, 'Customer', 'customer@gmail.com', '$2y$10$8tua5IuLrGBL19qlDLAzMeqT5Od58TeZuPBW8otkLBNCbbJNLZan6', 'guest', '2025-11-26 09:10:26'),
(12, 'Owner', 'owner@gmail.com', '$2y$10$77Jfdekt27eKUp.ojSkN/Ot2gCd6l1qD.mW/6NRpbe0gJIjoHq1Yy', 'admin', '2025-11-27 03:58:24'),
(13, 'Cashier', 'cashier@gmail.com', '$2y$10$oTS/DzsZxiQGNyOIXO6EoOYP27fV8q.GXs4hctbYnJ6QMyfJiYn02', 'cashier', '2025-11-27 03:59:06'),
(14, 'Mahdi', 'mahdi@gmail.com', '$2y$10$vzOlOYQ3nZOdMaJtGq8A9Op9hpWDU2c3ZaWJ9VEFcT0aeP6W.DbEe', 'cashier', '2025-11-27 07:17:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
