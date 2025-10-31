-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 11:06 AM
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
-- Database: `uipbsit2_sipnserve`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT 'small',
  `price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size`, `price`) VALUES
(32, 9, 17, 1, 'small', 0.00),
(33, 9, 16, 1, 'small', 0.00),
(34, 9, 11, 1, 'small', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `size` varchar(10) DEFAULT 'small',
  `payment_method` varchar(20) NOT NULL DEFAULT 'Cash',
  `delivery_status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `price`, `total_price`, `status`, `created_at`, `size`, `payment_method`, `delivery_status`) VALUES
(14, 2, 15, 1, 0.00, 230.00, 'Cancelled', '2025-05-01 12:17:10', 'small', 'Cash', 'Pending'),
(15, 2, 14, 1, 0.00, 150.00, '', '2025-05-01 12:17:10', 'small', 'Cash', 'Pending'),
(16, 2, 13, 1, 0.00, 200.00, '', '2025-05-01 12:17:10', 'small', 'Cash', 'Pending'),
(18, 9, 14, 2, 0.00, 300.00, 'Cancelled', '2025-05-24 05:02:01', 'small', 'Cash', 'Pending'),
(19, 10, 16, 4, 0.00, 800.00, 'Cancelled', '2025-05-26 14:38:25', 'small', 'Cash', 'Pending'),
(20, 10, 17, 4, 0.00, 720.00, 'Cancelled', '2025-05-26 14:38:25', 'small', 'Cash', 'Pending'),
(21, 10, 17, 2, 0.00, 360.00, 'Cancelled', '2025-05-26 15:01:27', 'small', 'Cash', 'Pending'),
(22, 10, 11, 1, 0.00, 200.00, '', '2025-05-26 15:01:27', 'small', 'Cash', 'Pending'),
(23, 9, 15, 9, 0.00, 2070.00, '', '2025-05-27 03:01:16', 'small', 'Cash', 'Pending'),
(24, 9, 16, 17, 0.00, 3400.00, '', '2025-05-27 03:14:07', 'small', 'Cash', 'Pending'),
(1549, 11, 21, 1, 100.00, 100.00, 'Pending', '2025-10-31 10:01:01', 'small', 'COD', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price_small` decimal(10,2) DEFAULT NULL,
  `price_medium` decimal(10,2) DEFAULT NULL,
  `price_large` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','sold_out') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price_small`, `price_medium`, `price_large`, `description`, `image`, `added_date`, `status`) VALUES
(8, 'Espresso', 'Hot Coffee', 70.00, 90.00, 110.00, 'Strong and bold single shot of pure espresso.', 'Espresso.jpg', '2025-08-07 16:01:32', 'available'),
(9, 'Americano', 'Hot Coffee', 75.00, 95.00, 115.00, 'Espresso diluted with hot water for a smooth sip.', 'americano.jpg', '2025-08-07 16:01:32', 'available'),
(10, 'Café Latte', 'Hot Coffee', 90.00, 120.00, 140.00, 'Velvety steamed milk mixed with rich espresso.', 'CafeLatte.jpg', '2025-08-07 16:01:32', 'available'),
(11, 'Cappuccino', 'Hot Coffee', 90.00, 120.00, 140.00, 'Espresso topped with steamed milk and foam.', 'Cappuccino.jpg', '2025-08-07 16:01:32', 'available'),
(12, 'Caramel Macchiato', 'Hot Coffee', 100.00, 130.00, 150.00, 'Sweet caramel over steamed milk and espresso', 'caramel-macchiato.jpg', '2025-08-07 16:01:32', 'available'),
(13, 'Mocha Latte', 'Hot Coffee', 100.00, 130.00, 150.00, 'Espresso blended with chocolate and milk.', 'mocha-latte1.jpg', '2025-08-07 16:01:32', 'available'),
(14, 'Iced Americano', 'Iced Coffee', 80.00, 100.00, 120.00, 'Chilled espresso and water over ice.', 'iced americano1.jpg', '2025-08-07 16:01:32', 'available'),
(15, 'Iced Caramel Latte', 'Iced Coffee', 100.00, 130.00, 150.00, 'Sweet caramel with espresso and cold milk over ice.', 'iced caramel latte.jpg', '2025-08-07 16:01:32', 'available'),
(16, 'Cold Brew Coffee', 'Iced Coffee', 90.00, 120.00, 140.00, 'Smooth, low-acid coffee brewed cold for 12 hours.', 'cold-brew-coffee1.jpg', '2025-08-07 16:01:32', 'available'),
(17, 'Spanish Latte', 'Hot Coffee', 95.00, 125.00, 145.00, 'Espresso with a touch of condensed milk and steamed milk.', 'spanish latte.jpg', '2025-08-07 16:01:32', 'available'),
(20, 'gfgd', 'hot', NULL, NULL, NULL, 'ghfhg', 'Screenshot 2025-08-19 111035.png', '2025-09-16 14:42:34', 'available'),
(21, 'gdjgjk', 'Hot Coffee', 100.00, 120.00, 130.00, 'fhfhfh', 'Screenshot 2025-10-28 110349.png', '2025-10-31 13:53:13', 'available'),
(22, 'ffjgdgjdhg', 'Hot Coffee', 12.00, 15.00, 18.00, 'jkgjkfg', 'Screenshot 2025-08-12 085843.png', '2025-10-31 15:52:38', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `address`, `password`, `profile_img`, `status`, `reg_date`) VALUES
(2, 'yuri', 'admin1@gmail.com', 'Luna', '12345678', '438862048_1587600352040695_4503408526140979708_n.jpg', 'active', '2025-08-07 16:01:51'),
(9, 'yuri3', 'admin1@gmail.com', 'Luna', '12345678', '465564981_1722652808535448_2409145477272775063_n.jpg', 'active', '2025-08-07 16:01:51'),
(10, 'yuribib', 'admin@gmail.com', 'Luna', '12345678', '395998767_1488095715324493_5024333870062199256_n.jpg', 'active', '2025-08-07 16:01:51'),
(11, 'E', 'edrfjk@gmail.com', 'Luna', '12345678', 'prof.jpg', 'active', '2025-08-07 16:01:51'),
(12, 'new', 'new@gmail.com', 'luna', '12345678', 'WIN_20240909_16_19_02_Pro.jpg', 'active', '2025-08-07 16:01:51'),
(13, 'new1', 'new@gmail.com', 'luna', '12345678', 'WIN_20240909_16_19_02_Pro.jpg', 'active', '2025-08-07 16:01:51');

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1550;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
