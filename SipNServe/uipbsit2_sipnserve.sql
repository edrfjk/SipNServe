-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2025 at 05:33 AM
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
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(32, 9, 17, 1),
(33, 9, 16, 1),
(34, 9, 11, 1),
(53, 11, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `created_at`) VALUES
(14, 2, 15, 1, 230.00, 'Cancelled', '2025-05-01 12:17:10'),
(15, 2, 14, 1, 150.00, 'Confirmed', '2025-05-01 12:17:10'),
(16, 2, 13, 1, 200.00, 'Confirmed', '2025-05-01 12:17:10'),
(18, 9, 14, 2, 300.00, 'Cancelled', '2025-05-24 05:02:01'),
(19, 10, 16, 4, 800.00, 'Cancelled', '2025-05-26 14:38:25'),
(20, 10, 17, 4, 720.00, 'Cancelled', '2025-05-26 14:38:25'),
(21, 10, 17, 2, 360.00, 'Cancelled', '2025-05-26 15:01:27'),
(22, 10, 11, 1, 200.00, 'Confirmed', '2025-05-26 15:01:27'),
(23, 9, 15, 9, 2070.00, 'Confirmed', '2025-05-27 03:01:16'),
(24, 9, 16, 17, 3400.00, 'Confirmed', '2025-05-27 03:14:07'),
(25, 11, 15, 1, 230.00, 'Cancelled', '2025-08-07 03:13:55'),
(31, 11, 9, 7, 1050.00, 'Confirmed', '2025-08-10 03:18:38'),
(32, 11, 15, 7, 1610.00, 'Confirmed', '2025-08-10 03:20:47'),
(33, 11, 11, 5, 1000.00, 'Pending', '2025-08-10 04:58:18'),
(35, 11, 8, 1, 150.00, 'Cancelled', '2025-08-10 04:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','sold_out') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `description`, `image`, `added_date`, `status`) VALUES
(8, 'Espresso', 'Hot Coffee', 150.00, 'Strong and bold single shot of pure espresso.', 'Espresso.jpg', '2025-08-07 16:01:32', 'available'),
(9, 'Americano', 'Hot Coffee', 150.00, 'Espresso diluted with hot water for a smooth sip.', 'americano.jpg', '2025-08-07 16:01:32', 'available'),
(10, 'Café Latte', 'Hot Coffee', 200.00, 'Velvety steamed milk mixed with rich espresso.', 'CafeLatte.jpg', '2025-08-07 16:01:32', 'available'),
(11, 'Cappuccino', 'Hot Coffee', 200.00, 'Espresso topped with steamed milk and foam.', 'Cappuccino.jpg', '2025-08-07 16:01:32', 'available'),
(12, 'Caramel Macchiato', 'Hot Coffee', 250.00, 'Sweet caramel over steamed milk and espresso', 'caramel-macchiato.jpg', '2025-08-07 16:01:32', 'available'),
(13, 'Mocha Latte', 'Hot Coffee', 200.00, 'Espresso blended with chocolate and milk.', 'mocha-latte1.jpg', '2025-08-07 16:01:32', 'available'),
(14, 'Iced Americano', 'Iced Coffee', 150.00, 'Chilled espresso and water over ice.', 'iced americano1.jpg', '2025-08-07 16:01:32', 'available'),
(15, 'Iced Caramel Latte', 'Iced Coffee', 230.00, 'Sweet caramel with espresso and cold milk over ice.', 'iced caramel latte.jpg', '2025-08-07 16:01:32', 'available'),
(16, 'Cold Brew Coffee', 'Iced Coffee', 200.00, 'Smooth, low-acid coffee brewed cold for 12 hours.', 'cold-brew-coffee1.jpg', '2025-08-07 16:01:32', 'available'),
(17, 'Spanish Latte', 'Hot Coffee', 180.00, 'Espresso with a touch of condensed milk and steamed milk.', 'spanish latte.jpg', '2025-08-07 16:01:32', 'sold_out');

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
(2, 'yuri', 'admin1@gmail.com', 'Luna', '12345678', '438862048_1587600352040695_4503408526140979708_n.jpg', 'inactive', '2025-08-07 16:01:51'),
(9, 'yuri3', 'admin1@gmail.com', 'Luna', '12345678', '465564981_1722652808535448_2409145477272775063_n.jpg', 'inactive', '2025-08-07 16:01:51'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
