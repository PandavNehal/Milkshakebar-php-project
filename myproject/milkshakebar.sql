-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 05:24 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `milkshakebar`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(71, 15, 20, 3, '2025-12-05 03:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Fruit', '2025-09-25 13:42:47'),
(2, 'Chocolate', '2025-09-25 13:42:47'),
(3, 'Dryfruit', '2025-09-25 13:42:47'),
(4, 'Special', '2025-09-25 13:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `payment_method` enum('cash','online') DEFAULT 'cash',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pincode` varchar(10) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `payment_method`, `created_at`, `pincode`, `location`) VALUES
(18, 4, '2280.00', 'completed', 'cash', '2025-09-27 02:47:39', '395007', 'Vesu'),
(19, 4, '5000.00', 'completed', 'online', '2025-09-27 02:49:06', '395004', 'Katargam'),
(20, 7, '1070.00', 'canceled', 'cash', '2025-09-27 03:33:58', '395004', 'Katargam'),
(21, 7, '1760.00', 'completed', 'online', '2025-09-27 03:34:44', '395004', 'Katargam'),
(22, 7, '1530.00', 'canceled', 'online', '2025-09-27 03:35:44', '395004', 'Katargam'),
(23, 8, '4880.00', 'completed', 'cash', '2025-09-28 08:48:17', '395007', 'Vesu'),
(24, 9, '9980.00', 'completed', 'online', '2025-10-03 08:14:07', '395007', 'Vesu'),
(25, 10, '4600.00', 'completed', 'cash', '2025-11-15 15:27:49', '395004', 'Katargam'),
(26, 11, '1460.00', 'completed', 'cash', '2025-11-18 04:37:50', '395004', 'Katargam'),
(27, 12, '1430.00', 'canceled', 'online', '2025-11-18 06:57:26', '395004', 'Katargam'),
(28, 13, '2100.00', 'completed', 'cash', '2025-11-28 06:48:10', '395007', 'Vesu'),
(29, 14, '200.00', 'canceled', 'online', '2025-11-28 07:17:36', '395007', 'Vesu'),
(31, 17, '2800.00', 'pending', 'cash', '2025-12-03 06:06:19', '395004', 'Katargam'),
(32, 18, '3000.00', 'pending', 'online', '2025-12-05 04:35:20', '395007', 'Vesu'),
(33, 18, '1200.00', 'pending', 'cash', '2025-12-05 07:08:07', '395007', 'Vesu');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(21, 18, 7, 1, '280.00'),
(22, 18, 6, 1, '300.00'),
(23, 18, 5, 1, '280.00'),
(24, 18, 8, 1, '350.00'),
(25, 18, 4, 1, '220.00'),
(26, 18, 3, 1, '300.00'),
(27, 18, 2, 1, '200.00'),
(28, 18, 1, 1, '350.00'),
(29, 19, 2, 25, '200.00'),
(30, 20, 3, 1, '300.00'),
(31, 20, 1, 1, '350.00'),
(32, 20, 2, 1, '200.00'),
(33, 20, 4, 1, '220.00'),
(34, 21, 4, 8, '220.00'),
(35, 22, 2, 3, '200.00'),
(36, 22, 6, 1, '300.00'),
(37, 22, 7, 1, '280.00'),
(38, 22, 8, 1, '350.00'),
(39, 23, 2, 8, '200.00'),
(40, 23, 6, 10, '300.00'),
(41, 23, 7, 1, '280.00'),
(42, 24, 2, 11, '200.00'),
(43, 24, 6, 25, '300.00'),
(44, 24, 7, 1, '280.00'),
(45, 25, 3, 10, '300.00'),
(46, 25, 2, 8, '200.00'),
(47, 26, 2, 1, '200.00'),
(48, 26, 3, 2, '300.00'),
(49, 26, 4, 3, '220.00'),
(50, 27, 7, 1, '280.00'),
(51, 27, 8, 1, '350.00'),
(52, 27, 6, 1, '300.00'),
(53, 27, 3, 1, '300.00'),
(54, 27, 2, 1, '200.00'),
(55, 28, 15, 7, '300.00'),
(56, 29, 2, 1, '200.00'),
(58, 31, 10, 10, '280.00'),
(59, 32, 3, 10, '300.00'),
(60, 33, 2, 3, '200.00'),
(61, 33, 3, 2, '300.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `category`, `created_at`) VALUES
(1, 1, 'Mango Shake', 'Fresh mango milkshake', '350.00', 'mango.jpg', 'Fruit', '2025-09-25 13:43:17'),
(2, 1, 'Banana Shake', 'Sweet banana milkshake', '200.00', 'banana.jpg', 'Fruit', '2025-09-25 13:43:17'),
(3, 1, 'Strawberry Shake', 'Delicious strawberry milkshake', '300.00', 'strawberry.jpg', 'Fruit', '2025-09-25 13:43:17'),
(4, 1, 'Chikoo Shake', 'Rich chikoo milkshake', '220.00', 'chikoo.jpg', 'Fruit', '2025-09-25 13:43:17'),
(5, 1, 'Apple Shake', 'Healthy apple milkshake', '280.00', 'apple.jpg', 'Fruit', '2025-09-25 13:43:17'),
(6, 2, 'Oreo Shake', 'Crunchy Oreo milkshake', '300.00', 'oreo.jpg', 'Chocolate', '2025-09-25 13:43:34'),
(7, 2, 'Chocolate Shake', 'Classic chocolate milkshake', '280.00', 'chocolate.png', 'Chocolate', '2025-09-25 13:43:34'),
(8, 2, 'KitKat Shake', 'KitKat blended milkshake', '350.00', 'kitkat.jpg', 'Chocolate', '2025-09-25 13:43:34'),
(9, 2, 'Brownie Shake', 'Brownie flavored milkshake', '250.00', 'brownie.jpg', 'Chocolate', '2025-09-25 13:43:34'),
(10, 2, 'Nutella Shake', 'Nutella chocolate milkshake', '280.00', 'nutella.jpg', 'Chocolate', '2025-09-25 13:43:34'),
(11, 3, 'Badam Shake', 'Almond rich milkshake', '350.00', 'badam.avif', 'Dryfruit', '2025-09-25 13:43:57'),
(12, 3, 'Kaju Shake', 'Cashew flavored milkshake', '280.00', 'kaju.jpg', 'Dryfruit', '2025-09-25 13:43:57'),
(13, 3, 'Pistachio Shake', 'Pista flavored milkshake', '360.00', 'pista.jpeg', 'Dryfruit', '2025-09-25 13:43:57'),
(14, 3, 'Khajur Shake', 'Dates milkshake', '240.00', 'khajur.jpg', 'Dryfruit', '2025-09-25 13:43:57'),
(15, 3, 'Anjeer Shake', 'Fig milkshake', '300.00', 'anjeer.jpg', 'Dryfruit', '2025-09-25 13:43:57'),
(16, 4, 'Cold Coffee', 'Classic cold coffee shake', '250.00', 'cold_coffee.jpg', 'Special', '2025-09-25 13:44:18'),
(17, 4, 'Butterscotch Shake', 'Sweet butterscotch milkshake', '280.00', 'butterscotch.jpg', 'Special', '2025-09-25 13:44:18'),
(19, 4, 'Rose Shake', 'Refreshing rose flavored milkshake', '400.00', 'rose.png', 'Special', '2025-09-25 13:44:18'),
(20, 4, 'Caramel Shake', 'Caramel rich milkshake', '250.00', 'caramel.jpg', 'Special', '2025-09-25 13:44:18'),
(21, 4, 'Vanilla Shake', 'Classic creamy vanilla milkshake', '280.00', 'vanilla.png', 'Special', '2025-11-18 14:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `location`, `pincode`, `password`, `role`, `created_at`) VALUES
(4, 'nehal', 'neha@gmail.com', '2121212121', 'Katargam', '395004', '$2y$10$JA6UT8T6mYG8qU3SrIAgl.mYXdyGRDkoYrPa.dkzbD0zpTTiHciUq', 'user', '2025-09-27 02:46:45'),
(5, 'vvk', 'vvk@gmail.com', '2378385838', 'Katargam', '395004', '$2y$10$49TvE5wNtZhdvrxvs4CJt.CYU3s5tGN5lgZp/chpYvunnNc68kZkm', 'user', '2025-09-27 03:23:19'),
(7, 'awd', 'awd@gmail.com', '1212121212', 'Katargam', '395004', '$2y$10$8YqKca0S4vA/OoyEiAK1k..dwS33C.mYvq1LQTAoCEbMqC6pNmIfa', 'user', '2025-09-27 03:33:29'),
(8, 'bhavy', 'bhavy@gmail.com', '1231231231', 'Vesu', '395007', '$2y$10$PlrVNPG/NPESJCp4646tEuSCRMXgIe3HifYLeHqNoDNS6EGyLXr7a', 'user', '2025-09-28 08:47:26'),
(9, 'jagu', 'jagu@gmail.com', '1212121212', 'Vesu', '395007', '$2y$10$s/2451rVIn4T.XfpeVwTmu/pUsWbFlpFUuD0GKLIF8q32dxwpbqXy', 'user', '2025-10-03 08:13:33'),
(10, 'my', 'my@gmail.com', '1231231234', 'Vesu', '395007', '$2y$10$7plmigWsaQ.f.1dT/9Aym.WSaOdRAil17fIfMxI75jTc5ByaleZg6', 'user', '2025-11-15 15:27:23'),
(11, 'vvk', 'vvk123@gmail.com', '1234567890', 'Katargam', '395004', '$2y$10$TdiCNjY/N5A4Y5fE.CBSt.U8RA..z96Rqs8uWEkRKPwXvWiXhNoyu', 'user', '2025-11-18 04:36:53'),
(12, 'bhumi', 'bhumi@gmail.com', '2210221022', 'Katargam', '395004', '$2y$10$qvg4/e3B3ECNo/VU/Iksk.kHSAE6FqEt2xQfFiu69lP2Z9.a5N/Du', 'user', '2025-11-18 06:49:08'),
(13, 'nehalp', 'nehalp@gmail.com', '1233211233', 'Vesu', '395007', '$2y$10$cRitIuDSX4dmCzGXS7nIRe7I1SVkaXWir7BcNpNsLUeyOYdX2ZSxe', 'user', '2025-11-28 06:47:38'),
(14, '123', '123@gmail.com', '2121212323', 'Vesu', '395007', '$2y$10$10dpou0EcarclgMqiqAwzuXToyX/wUrlQAK6xL8EMWJYieRlxH7Vy', 'user', '2025-11-28 07:17:25'),
(15, 'Admin', 'admin@gmail.com', '', NULL, NULL, 'admin123', 'admin', '2025-12-03 05:31:15'),
(17, 'gbhumi', 'bhumi123@gmail.com', '9876543210', 'Katargam', '395004', '$2y$10$BOUWbghlLPlakUVhetuAx.HGdm8cowpkzVD0SMUEw9RQR7XNMSFm.', 'user', '2025-12-03 06:06:07'),
(18, 'pnehal', 'pnehal@gmail.com', '1234567890', 'Vesu', '395007', '$2y$10$KNXFBRHbYuptdcE63vCqNeEQUAbIGpXWPSqCdlo5a8NeSVADypsVe', 'user', '2025-12-05 04:35:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
