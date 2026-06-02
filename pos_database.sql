-- ============================================================
-- POSMaster - Database SQL Dump
-- Versi: Laravel 11
-- Dibuat: 2024
-- 
-- CARA IMPORT:
-- mysql -u root -p pos_db < pos_database.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Buat database (opsional)
-- CREATE DATABASE IF NOT EXISTS `pos_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `pos_db`;

-- ============================================================
-- Tabel: migrations
-- ============================================================
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: users
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`name`,`email`,`password`,`role`,`is_active`,`phone`,`created_at`,`updated_at`) VALUES
('Administrator','admin@pos.com','$2y$12$eGQjWMpfg2SKDP3bC3ztD.5SKc0F4kHsMK.BWM.OBTXpMiw5EfPq2','admin',1,'08123456789',NOW(),NOW()),
('Kasir 1','kasir@pos.com','$2y$12$eGQjWMpfg2SKDP3bC3ztD.5SKc0F4kHsMK.BWM.OBTXpMiw5EfPq2','kasir',1,'08987654321',NOW(),NOW());

-- ============================================================
-- Tabel: categories
-- ============================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6366f1',
  `icon` varchar(255) NOT NULL DEFAULT 'tag',
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`,`slug`,`color`,`icon`,`is_active`,`created_at`,`updated_at`) VALUES
('Makanan','makanan','#f59e0b','🍽️',1,NOW(),NOW()),
('Minuman','minuman','#3b82f6','🥤',1,NOW(),NOW()),
('Snack','snack','#10b981','🍪',1,NOW(),NOW()),
('Elektronik','elektronik','#8b5cf6','💡',1,NOW(),NOW()),
('Kebutuhan Rumah','kebutuhan-rumah','#ef4444','🏠',1,NOW(),NOW()),
('Kesehatan','kesehatan','#06b6d4','💊',1,NOW(),NOW());

-- ============================================================
-- Tabel: products
-- ============================================================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(15,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`category_id`,`name`,`slug`,`sku`,`barcode`,`purchase_price`,`selling_price`,`stock`,`min_stock`,`unit`,`created_at`,`updated_at`) VALUES
(1,'Nasi Goreng Spesial','nasi-goreng-spesial-1','MKN001','8990000000001',15000,25000,100,10,'porsi',NOW(),NOW()),
(1,'Mie Ayam Bakso','mie-ayam-bakso-2','MKN002','8990000000002',12000,20000,80,10,'porsi',NOW(),NOW()),
(1,'Ayam Bakar','ayam-bakar-3','MKN003','8990000000003',18000,30000,60,10,'porsi',NOW(),NOW()),
(2,'Es Teh Manis','es-teh-manis-4','MNM001','8990000000004',2000,5000,200,20,'gelas',NOW(),NOW()),
(2,'Jus Jeruk','jus-jeruk-5','MNM002','8990000000005',5000,12000,150,20,'gelas',NOW(),NOW()),
(2,'Kopi Hitam','kopi-hitam-6','MNM003','8990000000006',3000,8000,180,20,'gelas',NOW(),NOW()),
(2,'Teh Tarik','teh-tarik-7','MNM004','8990000000007',4000,10000,120,20,'gelas',NOW(),NOW()),
(3,'Keripik Singkong','keripik-singkong-8','SNK001','8990000000008',3000,6000,300,20,'pcs',NOW(),NOW()),
(3,'Chitato','chitato-9','SNK002','8990000000009',7000,12000,250,20,'pcs',NOW(),NOW()),
(3,'Oreo','oreo-10','SNK003','8990000000010',8000,14000,200,20,'pcs',NOW(),NOW()),
(4,'Baterai AA Energizer','baterai-aa-11','ELK001','8990000000011',15000,22000,50,10,'pack',NOW(),NOW()),
(5,'Sabun Mandi Lifebuoy','sabun-mandi-12','RMH001','8990000000012',5000,8500,100,10,'pcs',NOW(),NOW()),
(5,'Shampo Sunsilk','shampo-sunsilk-13','RMH002','8990000000013',12000,18000,80,10,'pcs',NOW(),NOW()),
(6,'Paracetamol 500mg','paracetamol-14','KSH001','8990000000014',3000,5000,200,20,'strip',NOW(),NOW()),
(6,'Vitamin C 1000mg','vitamin-c-15','KSH002','8990000000015',15000,25000,100,10,'botol',NOW(),NOW());

-- ============================================================
-- Tabel: transactions
-- ============================================================
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `change_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','debit','credit','qris','transfer') NOT NULL DEFAULT 'cash',
  `status` enum('completed','pending','cancelled','refunded') NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: transaction_items
-- ============================================================
DROP TABLE IF EXISTS `transaction_items`;
CREATE TABLE `transaction_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: expenses
-- ============================================================
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'operasional',
  `expense_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabel: settings
-- ============================================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`,`value`,`created_at`,`updated_at`) VALUES
('store_name','Toko Maju Jaya',NOW(),NOW()),
('store_address','Jl. Sudirman No. 123, Jakarta Pusat',NOW(),NOW()),
('store_phone','021-12345678',NOW(),NOW()),
('store_email','toko@majujaya.com',NOW(),NOW()),
('tax_percent','0',NOW(),NOW()),
('currency','IDR',NOW(),NOW()),
('receipt_footer','Terima kasih atas kunjungan anda!',NOW(),NOW()),
('logo','',NOW(),NOW());

-- ============================================================
-- Tabel: stock_movements
-- ============================================================
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_before` int(11) NOT NULL,
  `stock_after` int(11) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
