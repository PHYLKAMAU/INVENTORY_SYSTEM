-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2024 at 08:50 AM
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
-- Database: `inventory_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'LIBRARY'),
(2, 'ICT DEPARTMENT'),
(3, 'STORE'),
(4, 'PROCUREMENT'),
(5, 'COMMUNICATION CENTRE'),
(6, 'CORPORATE COMMUNICATION'),
(7, 'EXAMS'),
(8, 'RESEARCH');

-- --------------------------------------------------------

--
-- Table structure for table `returned_products`
--

CREATE TABLE `returned_products` (
  `id` int(11) NOT NULL,
  `stock_out_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `returned_by` varchar(100) NOT NULL,
  `date_returned` timestamp NOT NULL DEFAULT current_timestamp(),
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `returned_products`
--

INSERT INTO `returned_products` (`id`, `stock_out_id`, `quantity`, `returned_by`, `date_returned`, `reason`) VALUES
(1, 1, 4, 'csm', '2024-07-23 08:03:38', 'faulty'),
(2, 2, 3, 'Col ojuang', '2024-07-23 11:12:57', 'not interested'),
(3, 4, 6, 'phyl', '2024-07-23 11:18:55', 'faulty'),
(4, 1, 3, 'alfred mwenda', '2024-07-26 05:17:56', 'not needed');

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `lpo_number` varchar(255) NOT NULL,
  `date_received` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_in`
--

INSERT INTO `stock_in` (`id`, `product_name`, `quantity`, `supplier_id`, `lpo_number`, `date_received`) VALUES
(1, 'hp laptops', 8, 1, '5667', '2024-07-09 11:18:52'),
(2, 'N-COMPUTING', 8, 3, '1234', '2024-07-09 14:09:55'),
(3, 'polkjhgbv', 67, 2, '098765', '2024-07-11 08:42:26'),
(4, ' Canon cameras', 4, 2, '5987', '2024-07-12 10:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `stock_out`
--

CREATE TABLE `stock_out` (
  `id` int(11) NOT NULL,
  `stock_in_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `department` varchar(255) NOT NULL,
  `issued_to` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stock_out`
--

INSERT INTO `stock_out` (`id`, `stock_in_id`, `quantity`, `department`, `issued_to`, `date`) VALUES
(1, 1, 5, 'LIBRARY', 'CSM nakoru', '2024-07-11 09:16:42'),
(2, 2, 7, 'LIBRARY', 'Col ojuang', '2024-07-23 14:10:36'),
(3, 4, 1, 'CORPORATE COMMUNICATION', 'sgt Kibwoyo', '2024-07-23 14:11:10'),
(4, 2, 4, 'COMMUNICATION CENTRE', 'kamau', '2024-07-23 14:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `contact`, `address`) VALUES
(1, 'MUSUMBA CARLIN MULINDI', 'musumbacarlin@gmail.com', '0123456789', 'nairobi, kenya'),
(2, 'KAMAU PHYLIS', 'phyliskamau3902@gmail.com', '0110987654', '1234'),
(3, 'DANIEL OMBATI', 'ombati99@gmail.com', '0987654334', '678'),
(4, 'joel munene', 'joe21@gmail.com', '0987532', '5431');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1, 'PHYLIS WAMBUI', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.png', 1, '2024-07-29 08:49:32'),
(2, 'John Walker', 'special', 'a51dda7c7ff50b61eaea0444371f4a6a9301e501', 2, 'no_image.png', 1, '2021-04-04 19:53:26'),
(3, 'MARY', 'MARY', '68ba729f955f31c9b85b148daf9971f3f04434d9', 3, 'no_image.png', 1, '2024-07-26 08:09:21'),
(4, 'Natie Williams', 'natie', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'no_image.png', 1, NULL),
(5, 'Kevin', 'kevin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'no_image.png', 1, '2021-04-04 19:54:29'),
(6, 'stephen ', 'steve', '9ce5770b3bb4b2a1d59be2d97e34379cd192299f', 4, 'no_image.jpg', 1, '2024-07-29 08:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Admin', 1, 1),
(2, 'Special', 2, 1),
(3, 'USER', 3, 1),
(4, 'procurement manager', 4, 1),
(5, 'Store manager', 5, 1),
(6, 'Store Clerk', 6, 1),
(7, 'Department requestor', 7, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returned_products`
--
ALTER TABLE `returned_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_out_id` (`stock_out_id`);

--
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_in_id` (`stock_in_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_level` (`user_level`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_level` (`group_level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `returned_products`
--
ALTER TABLE `returned_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_out`
--
ALTER TABLE `stock_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `returned_products`
--
ALTER TABLE `returned_products`
  ADD CONSTRAINT `returned_products_ibfk_1` FOREIGN KEY (`stock_out_id`) REFERENCES `stock_out` (`id`);

--
-- Constraints for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD CONSTRAINT `stock_in_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_out`
--
ALTER TABLE `stock_out`
  ADD CONSTRAINT `stock_out_ibfk_1` FOREIGN KEY (`stock_in_id`) REFERENCES `stock_in` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
