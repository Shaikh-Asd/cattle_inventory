-- Transaction History Tables
-- This SQL script creates the necessary tables for tracking transaction history

-- Table for storing transaction records
CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type` varchar(50) NOT NULL COMMENT 'Type of transaction (inward, outward, adjustment)',
  `reference_id` int(11) DEFAULT NULL COMMENT 'Reference to the original record (e.g., product ID)',
  `customer_id` int(11) DEFAULT NULL COMMENT 'Customer ID associated with the transaction',
  `user_id` int(11) DEFAULT NULL COMMENT 'User who performed the transaction',
  `notes` text DEFAULT NULL COMMENT 'Additional notes about the transaction',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  KEY `transaction_type` (`transaction_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for storing transaction details
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL COMMENT 'Reference to transaction_history.id',
  `medicine_id` int(11) NOT NULL COMMENT 'Medicine ID',
  `quantity` int(11) NOT NULL COMMENT 'Quantity involved in the transaction',
  `operation` varchar(20) NOT NULL COMMENT 'Operation type (add, deduct)',
  `previous_stock` int(11) DEFAULT NULL COMMENT 'Stock before transaction',
  `new_stock` int(11) DEFAULT NULL COMMENT 'Stock after transaction',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `medicine_id` (`medicine_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add foreign key constraints
ALTER TABLE `transaction_history`
  ADD CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

ALTER TABLE `transaction_history`
  ADD CONSTRAINT `transaction_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_history` (`id`) ON DELETE CASCADE;

ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE; 