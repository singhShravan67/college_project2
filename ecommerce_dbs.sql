-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 07:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_dbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `session_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(3, 'qfr2u6iv6mva5l3ldmg4hdab7p', 1, 2, '2025-10-03 13:22:27', '2025-10-03 13:22:27'),
(4, 'qfr2u6iv6mva5l3ldmg4hdab7p', 4, 1, '2025-10-03 13:22:27', '2025-10-03 13:22:27'),
(8, 'me1jncab15k3jmm61o0od1d0iv', 1, 1, '2025-10-03 17:39:33', '2025-10-03 17:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `customer_name`, `email`, `phone`, `city`, `address`, `total_amount`, `payment_status`, `order_date`) VALUES
(1, 'ORD38C202', NULL, 'shravan', 'shravansingh@email.com', '9845373456', 'sirohi', 'rnfmx,', 998.00, 'pending', '2025-10-03 13:21:07'),
(2, 'ORD3A439D', NULL, 'shravan singh', 'shravansingh23@email.com', '6376435108', 'ahmedabad', 'indus university rancheda', 499.00, 'completed', '2025-10-05 05:05:23'),
(3, 'ORD21F439', NULL, 'shravan sinngh', 'praveensingh@email.com', '885956828', 'sirohi', 'Rajasthan sirohi', 1999.00, 'completed', '2025-10-05 05:26:26'),
(4, 'ORD723BA3', NULL, 'harsh', 'harsh23@email.com', '9637253745', 'ahmedabad', 'Gujrat ahmedabad', 499.00, 'completed', '2025-10-05 05:36:39'),
(5, 'ORDA1616A', 1, 'keval patel', 'kevalpatel23@email.com', '4567891230', 'ahmedabad', 'Gujarat Ahmedabad', 2499.00, 'completed', '2025-10-05 16:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'Classic Tee', 499.00, 2, 998.00),
(2, 2, 1, 'Classic Tee', 499.00, 1, 499.00),
(3, 3, 4, 'Wireless Earbuds', 1999.00, 1, 1999.00),
(4, 4, 1, 'Classic Tee', 499.00, 1, 499.00),
(5, 5, 2, 'Running Shoes', 2499.00, 1, 2499.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `sku`, `short_desc`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Classic Tee', 'Clothing', 499.00, 'CT-001', 'Comfort cotton tee', 'Soft cotton t-shirt in multiple sizes. Perfect for everyday wear.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(2, 'Running Shoes', 'Footwear', 2499.00, 'RS-002', 'Lightweight running shoes', 'Breathable and cushioned running shoes for maximum comfort.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(3, 'Leather Wallet', 'Accessories', 899.00, 'LW-003', 'Slim leather wallet', 'Full grain leather wallet with multiple card slots and cash compartment.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(4, 'Wireless Earbuds', 'Electronics', 1999.00, 'WE-004', 'True wireless earbuds', 'Noise-cancelling earbuds with long battery life and premium sound quality.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(5, 'Denim Jacket', 'Clothing', 2999.00, 'DJ-005', 'Stylish denim jacket', 'Classic denim jacket with modern cut and comfortable fit.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(6, 'Sunglasse', 'Accessories', 699.00, 'SG-006', 'UV protection sunglasses', 'Stylish frames with UV400 protection for your eyes.', '2025-10-03 13:15:01', '2025-10-05 16:53:00'),
(7, 'Backpack', 'Bags', 1599.00, 'BP-007', 'Travel backpack', 'Durable backpack with laptop compartment and multiple pockets.', '2025-10-03 13:15:01', '2025-10-03 13:15:01'),
(9, 'bags', 'bags', 500.00, 'Stylish denim jacket', 'Classic denim jacket ', 'good bags a school', '2025-10-05 16:51:50', '2025-10-05 16:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'shravan', 'harsh23@email.com', '$2y$10$u5UuL5dN8gxaoGJHXnMhm.wa34GeVB6JYHmPVmSbuMX1PizkV89ee', '7894561230', '2025-10-05 16:24:28', '2025-10-05 16:24:28'),
(2, 'praveen singh', 'praveensingh@gmail.com', '$2y$10$3fuJHgUFgmHWeleGFJJyZ.RDV6Z9YXXNamxmPYCoxcTKK7XzM1/9u', '9950568285', '2025-10-05 16:34:43', '2025-10-05 16:34:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_session` (`session_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_order_date` (`order_date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_price` (`price`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
