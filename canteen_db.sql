-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 04:34 PM
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
-- Database: `canteen_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_days` set('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `availability_status` enum('Available','Unavailable') DEFAULT 'Available',
  `description` text DEFAULT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`item_id`, `name`, `price`, `available_days`, `quantity`, `image_url`, `availability_status`, `description`, `added_on`) VALUES
(1, 'Chicken Biryani', 150.00, 'Saturday', 100, NULL, 'Available', 'Delicious chicken biryani made with aromatic spices.', '2024-12-21 05:23:09'),
(2, 'Chicken Biryani', 150.00, 'Saturday', 100, 'images/chicken_biryani.jpg', 'Available', 'Delicious chicken biryani made with aromatic spices.', '2024-12-21 05:24:51'),
(3, 'momo', 200.00, 'Saturday', 100, NULL, 'Available', 'kdjfvbkjefvjfv', '2024-12-21 05:29:36'),
(4, 'chaumin', 300.00, 'Saturday', 5, NULL, 'Available', 'whsdvwd', '2024-12-21 05:54:06'),
(5, 'pizza', 300.00, 'Saturday', 996, NULL, 'Available', 'wiehfiwefiuew', '2024-12-21 06:41:10');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oid` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oid`, `total_cost`, `created_at`, `status`) VALUES
(1, 450.00, '2024-12-21 15:03:30', 'Completed'),
(2, 300.00, '2024-12-21 15:07:57', 'Completed'),
(3, 450.00, '2024-12-21 15:08:02', 'Completed'),
(4, 150.00, '2024-12-21 15:08:08', 'Completed'),
(5, 500.00, '2024-12-21 15:08:14', 'Pending'),
(6, 800.00, '2024-12-21 15:08:19', 'Pending'),
(7, 950.00, '2024-12-21 15:08:25', 'Pending'),
(8, 150.00, '2024-12-21 15:08:30', 'Pending'),
(9, 450.00, '2024-12-21 15:21:45', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `oid`, `item_id`, `quantity`) VALUES
(1, 1, 2, 1),
(2, 1, 5, 1),
(3, 2, 5, 1),
(4, 3, 1, 1),
(5, 3, 5, 1),
(6, 4, 1, 1),
(7, 5, 1, 1),
(8, 5, 2, 1),
(9, 5, 3, 1),
(10, 6, 1, 1),
(11, 6, 2, 1),
(12, 6, 3, 1),
(13, 6, 4, 1),
(14, 7, 1, 1),
(15, 7, 2, 2),
(16, 7, 3, 1),
(17, 7, 4, 1),
(18, 8, 1, 1),
(19, 9, 2, 1),
(20, 9, 5, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oid` (`oid`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`oid`) REFERENCES `orders` (`oid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `food_items` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
