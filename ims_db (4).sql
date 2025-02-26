-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 06:14 AM
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
-- Database: `ims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value`
--

CREATE TABLE `attribute_value` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `attribute_parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `service_charge_value` varchar(255) NOT NULL,
  `vat_charge_value` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `currency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `active` varchar(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `user_type`, `active`, `created_at`) VALUES
(2, 'Peter', 2, '1', '0000-00-00 00:00:00'),
(3, 'John', 1, '1', '0000-00-00 00:00:00'),
(4, 'Michael', 2, '1', '0000-00-00 00:00:00'),
(5, 'Trevor', 1, '1', '0000-00-00 00:00:00'),
(6, 'Nyla', 2, '1', '0000-00-00 00:00:00'),
(7, 'Suzer', 1, '2', '0000-00-00 00:00:00'),
(8, 'Liza', 2, '1', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `permission`) VALUES
(1, 'Administrator', 'a:56:{i:0;s:10:\"createUser\";i:1;s:10:\"updateUser\";i:2;s:8:\"viewUser\";i:3;s:10:\"deleteUser\";i:4;s:11:\"createGroup\";i:5;s:11:\"updateGroup\";i:6;s:9:\"viewGroup\";i:7;s:11:\"deleteGroup\";i:8;s:11:\"createBrand\";i:9;s:11:\"updateBrand\";i:10;s:9:\"viewBrand\";i:11;s:11:\"deleteBrand\";i:12;s:14:\"createCategory\";i:13;s:14:\"updateCategory\";i:14;s:12:\"viewCategory\";i:15;s:14:\"deleteCategory\";i:16;s:11:\"createStore\";i:17;s:11:\"updateStore\";i:18;s:9:\"viewStore\";i:19;s:11:\"deleteStore\";i:20;s:15:\"createAttribute\";i:21;s:15:\"updateAttribute\";i:22;s:13:\"viewAttribute\";i:23;s:15:\"deleteAttribute\";i:24;s:13:\"createProduct\";i:25;s:13:\"updateProduct\";i:26;s:11:\"viewProduct\";i:27;s:13:\"deleteProduct\";i:28;s:11:\"createOrder\";i:29;s:11:\"updateOrder\";i:30;s:9:\"viewOrder\";i:31;s:11:\"deleteOrder\";i:32;s:11:\"viewReports\";i:33;s:13:\"updateCompany\";i:34;s:11:\"viewProfile\";i:35;s:13:\"updateSetting\";i:36;s:15:\"createCustomers\";i:37;s:15:\"updateCustomers\";i:38;s:13:\"viewCustomers\";i:39;s:15:\"deleteCustomers\";i:40;s:15:\"createMedicines\";i:41;s:15:\"updateMedicines\";i:42;s:13:\"viewMedicines\";i:43;s:15:\"deleteMedicines\";i:44;s:10:\"createUsed\";i:45;s:10:\"updateUsed\";i:46;s:8:\"viewUsed\";i:47;s:10:\"deleteUsed\";i:48;s:13:\"createHistory\";i:49;s:13:\"updateHistory\";i:50;s:11:\"viewHistory\";i:51;s:13:\"deleteHistory\";i:52;s:11:\"createStock\";i:53;s:11:\"updateStock\";i:54;s:9:\"viewStock\";i:55;s:11:\"deleteStock\";}');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `stock` varchar(255) NOT NULL DEFAULT '0',
  `dead_stock` int(11) NOT NULL,
  `active` varchar(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `name`, `stock`, `dead_stock`, `active`, `created_at`) VALUES
(1, 'Melonex Plus Injection', '6', 3, '1', '2025-02-16 19:45:57'),
(2, 'Anistomin', '11', 3, '1', '2025-02-16 19:46:11'),
(3, 'Beekom L', '21', 3, '1', '2025-02-16 19:46:25'),
(4, 'Conciplex', '0', 3, '1', '2025-02-16 19:46:44'),
(5, 'Ascovit c', '0', 3, '1', '2025-02-16 19:47:10'),
(6, 'Enrorex', '-10', 2, '1', '2025-02-16 19:47:28'),
(7, 'Gentavet', '0', 2, '1', '2025-02-16 19:47:40'),
(8, 'Atropine', '0', 1, '1', '2025-02-16 19:47:53'),
(9, 'Tilmotyle', '0', 1, '1', '2025-02-16 19:48:13'),
(10, 'Sarral', '0', 1, '1', '2025-02-16 19:48:24'),
(11, 'Pyrisafe', '0', 1, '1', '2025-02-16 19:48:33'),
(12, 'Catasol', '0', 2, '1', '2025-02-16 19:48:52'),
(13, 'Berenil', '0', 2, '1', '2025-02-16 19:54:18'),
(14, 'Meglurex', '0', 2, '1', '2025-02-23 17:14:19'),
(15, 'Urimin', '0', 2, '1', '2025-02-23 17:14:32'),
(16, 'ST. LA', '0', 2, '1', '2025-02-23 17:14:50'),
(17, 'Lymec', '0', 2, '1', '2025-02-23 17:15:02'),
(18, 'Tylozer', '0', 2, '1', '2025-02-23 17:15:16'),
(19, 'Tamik', '0', 1, '1', '2025-02-23 17:15:32'),
(20, 'Melonex Plain', '0', 1, '1', '2025-02-23 17:15:48'),
(21, 'Maxxtol xp', '0', 2, '1', '2025-02-23 17:16:13'),
(22, 'Rumeric', '0', 2, '1', '2025-02-23 17:16:23'),
(23, 'Nurorex', '0', 2, '1', '2025-02-23 17:16:35'),
(24, 'Tribivet', '0', 2, '1', '2025-02-23 17:16:47'),
(25, 'Kiviton H', '0', 5, '1', '2025-02-23 17:17:01'),
(26, 'Icvet Plus', '0', 4, '1', '2025-02-23 17:17:23'),
(27, 'Prednisolone', '0', 2, '1', '2025-02-23 17:17:46'),
(28, 'Prednisolone Tab 10 mg', '0', 1, '1', '2025-02-23 17:18:18'),
(29, 'Nimax cream', '0', 1, '1', '2025-02-23 17:18:35'),
(30, 'wisprec cream', '0', 1, '1', '2025-02-23 17:18:52'),
(31, 'Nealent cream', '0', 1, '1', '2025-02-23 17:19:09'),
(32, 'Duraret', '0', 2, '1', '2025-02-23 17:19:22'),
(33, 'Pragma', '0', 2, '1', '2025-02-23 17:19:32'),
(34, 'texableed', '0', 2, '1', '2025-02-23 17:19:46'),
(35, 'Bovispace', '0', 2, '1', '2025-02-23 17:19:56'),
(36, 'kito disatix', '0', 1, '1', '2025-02-23 17:20:07'),
(37, 'Rupitas', '0', 1, '1', '2025-02-23 17:20:15'),
(38, 'Nex bolic', '0', 1, '1', '2025-02-23 17:20:24');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_stock`
--

CREATE TABLE `medicine_stock` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_transactions`
--

CREATE TABLE `medicine_transactions` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_transactions`
--

INSERT INTO `medicine_transactions` (`id`, `customer_id`, `transaction_date`, `updated_at`) VALUES
(9, 2, '2025-02-18 00:44:51', '2025-02-18 00:44:51');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_transactions_bkp`
--

CREATE TABLE `medicine_transactions_bkp` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity_given` int(11) NOT NULL,
  `quantity_used` int(11) DEFAULT 0,
  `quantity_returned` int(11) DEFAULT 0,
  `transaction_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_transactions_bkp`
--

INSERT INTO `medicine_transactions_bkp` (`id`, `customer_id`, `medicine_id`, `quantity_given`, `quantity_used`, `quantity_returned`, `transaction_date`) VALUES
(1, 4, 2, 20, 10, 10, '2025-02-15 02:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_transaction_details`
--

CREATE TABLE `medicine_transaction_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity_given` int(11) NOT NULL,
  `quantity_used` int(11) DEFAULT 0,
  `quantity_returned` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_transaction_details`
--

INSERT INTO `medicine_transaction_details` (`id`, `transaction_id`, `medicine_id`, `quantity_given`, `quantity_used`, `quantity_returned`) VALUES
(1, 9, 6, 10, 0, 2),
(2, 9, 3, 5, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `date_time` varchar(255) NOT NULL,
  `gross_amount` varchar(255) NOT NULL,
  `service_charge_rate` varchar(255) NOT NULL,
  `service_charge` varchar(255) NOT NULL,
  `vat_charge_rate` varchar(255) NOT NULL,
  `vat_charge` varchar(255) NOT NULL,
  `net_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `paid_status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `medicine_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `bill_no`, `customer_name`, `customer_address`, `customer_phone`, `date_time`, `gross_amount`, `service_charge_rate`, `service_charge`, `vat_charge_rate`, `vat_charge`, `net_amount`, `discount`, `paid_status`, `user_id`, `qty`, `medicine_id`, `created_at`) VALUES
(1, '', '5', '', '', '1739390067', '', '', '', '', '', '', '', 0, 5, '10,15', '13,12', '2025-02-12 19:54:27'),
(2, '', '5', '', '', '1739390124', '', '', '', '', '', '', '', 0, 5, '10,15,20', '13,12,2', '2025-02-12 19:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders_item`
--

CREATE TABLE `orders_item` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders_item`
--

INSERT INTO `orders_item` (`id`, `customer_id`, `order_id`, `product_id`, `qty`, `rate`, `amount`, `created_at`) VALUES
(1, 5, 1, 13, '20', '', '', '2025-02-12 19:54:27'),
(2, 5, 1, 12, '30', '', '', '2025-02-12 19:54:27'),
(3, 5, 2, 2, '20', '', '', '2025-02-12 19:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `medicine_id` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `description` text NOT NULL,
  `attribute_value_id` text DEFAULT NULL,
  `brand_id` text NOT NULL,
  `category_id` text NOT NULL,
  `store_id` int(11) NOT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `customer_id`, `medicine_id`, `sku`, `price`, `qty`, `image`, `description`, `attribute_value_id`, `brand_id`, `category_id`, `store_id`, `availability`, `created_at`) VALUES
(1, '', 6, '13,12,2', '', '', '20,25,30', '', '', NULL, '', '', 0, 1, '2025-02-12 19:42:40'),
(2, '', 6, '13,12,2,6', '', '', '20,25,30,10', '', '', NULL, '', '', 0, 1, '2025-02-12 19:53:41'),
(3, '', 4, '3', '', '', '21', '', '', NULL, '', '', 0, NULL, '2025-02-19 16:53:27'),
(4, '', 4, '2', '', '', '11', '', '', NULL, '', '', 0, NULL, '2025-02-19 19:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `phone`, `gender`, `role`) VALUES
(1, 'super admin', '$2y$10$UxcnPSXVtrElKG52jFMie.q0205U4FDlSGeBZJGBEM9C1AKLg.zKu', 'admin@gmail.com', 'john', 'doe', '65646546', 1, 'staff'),
(11, 'shafraz', '$2y$10$LK91ERpEJxortR86lkDjwu7MClazgIrvDqehqOnq5ZKm30elKAkUa', 'shafraz@gmail.com', 'mohamed', 'nizam', '0778650669', 1, 'staff'),
(12, 'jsmith', '$2y$10$WLS.lZeiEfyXYfR0l/wkXeRRuqazsgIAMC9//L44J4KkZGbbqcKYC', 'jsmith@sample.com', 'John', 'Smith', '2345678', 1, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(7, 6, 4),
(8, 7, 4),
(9, 8, 4),
(10, 9, 5),
(11, 10, 5),
(12, 11, 5),
(13, 12, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_stock`
--
ALTER TABLE `medicine_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_transactions`
--
ALTER TABLE `medicine_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `medicine_transactions_bkp`
--
ALTER TABLE `medicine_transactions_bkp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `medicine_transaction_details`
--
ALTER TABLE `medicine_transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attribute_value`
--
ALTER TABLE `attribute_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `medicine_stock`
--
ALTER TABLE `medicine_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_transactions`
--
ALTER TABLE `medicine_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medicine_transactions_bkp`
--
ALTER TABLE `medicine_transactions_bkp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medicine_transaction_details`
--
ALTER TABLE `medicine_transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicine_transactions`
--
ALTER TABLE `medicine_transactions`
  ADD CONSTRAINT `medicine_transactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `medicine_transactions_bkp`
--
ALTER TABLE `medicine_transactions_bkp`
  ADD CONSTRAINT `medicine_transactions_bkp_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `medicine_transactions_bkp_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `medicine_transaction_details`
--
ALTER TABLE `medicine_transaction_details`
  ADD CONSTRAINT `medicine_transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `medicine_transactions` (`id`),
  ADD CONSTRAINT `medicine_transaction_details_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
