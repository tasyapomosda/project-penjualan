-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for showcase_snack
CREATE DATABASE IF NOT EXISTS `showcase_snack` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `showcase_snack`;

-- Dumping structure for table showcase_snack.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.cache: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.cache_locks: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.debts
CREATE TABLE IF NOT EXISTS `debts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pembeli` varchar(255) NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `barang` varchar(255) NOT NULL,
  `qty` int NOT NULL,
  `nominal` int NOT NULL,
  `is_paid` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `debts_product_id_foreign` (`product_id`),
  CONSTRAINT `debts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table showcase_snack.debts: ~24 rows (approximately)
INSERT INTO `debts` (`id`, `nama_pembeli`, `product_id`, `barang`, `qty`, `nominal`, `is_paid`, `created_at`, `updated_at`) VALUES
	(1, 'Tasya', 3, 'Beng Beng', 1, 2500, 1, '2026-05-03 06:50:43', '2026-05-03 06:51:18'),
	(2, 'Tasya', 4, 'Aquviva', 1, 3000, 1, '2026-05-03 06:50:43', '2026-05-03 06:52:06'),
	(3, 'Tasya', 5, 'Chitato', 1, 2000, 0, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(4, 'Tasya', 9, 'Astor', 1, 1000, 0, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(5, 'Bu Lidya', 3, 'Beng Beng', 1, 2500, 0, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(6, 'Bu Lidya', 5, 'Chitato', 1, 2000, 0, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(7, 'Bu Lidya', 7, 'Topo', 1, 2000, 0, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(8, 'Bu Nurul', 2, 'Chocolatos', 1, 500, 0, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(9, 'Bu Nurul', 3, 'Beng Beng', 1, 2500, 0, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(10, 'Bu Nurul', 6, 'Riry', 1, 500, 0, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(11, 'Bu Nurul', 7, 'Topo', 1, 2000, 1, '2026-05-03 08:49:46', '2026-05-23 07:43:58'),
	(12, 'Bu Nurul', 8, 'Oreo Softcake', 1, 2000, 0, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(13, 'Pak Putut', 2, 'Chocolatos', 2, 1000, 0, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(14, 'Pak Putut', 3, 'Beng Beng', 2, 5000, 0, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(15, 'Pak Putut', 4, 'Aquviva', 1, 3000, 0, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(16, 'Pak Putut', 5, 'Chitato', 1, 2000, 1, '2026-05-03 09:46:49', '2026-06-21 09:43:18'),
	(17, 'Pak Putut', 8, 'Oreo Softcake', 1, 2000, 0, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(18, 'Pak Putut', 9, 'Astor', 1, 1000, 0, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(19, 'Tasya', 8, 'Oreo Softcake', 1, 2000, 0, '2026-05-18 01:27:51', '2026-05-18 01:27:51'),
	(20, 'Tasya', 9, 'Astor', 1, 1000, 1, '2026-05-18 01:27:51', '2026-07-06 15:00:59'),
	(21, 'Aryan', 3, 'Yakult', 1, 2500, 0, '2026-05-19 13:51:25', '2026-07-12 13:00:16'),
	(22, 'Aryan', 4, 'Aquviva', 1, 3000, 1, '2026-05-19 13:51:25', '2026-07-10 13:07:38'),
	(23, 'Aryan', 5, 'Chitato', 1, 2000, 1, '2026-05-19 13:51:25', '2026-06-17 16:26:35'),
	(24, 'Aryan', 9, 'Chitato', 2, 4000, 0, '2026-05-19 13:51:25', '2026-07-12 13:01:30'),
	(25, 'Tasya', 3, 'Beng Beng', 1, 2500, 1, '2026-05-19 14:05:02', '2026-06-17 16:16:10'),
	(26, 'Tasya', 4, 'Aquviva', 1, 3000, 1, '2026-05-19 14:05:02', '2026-06-21 09:32:01'),
	(27, 'Mba Ria', 4, 'Aquviva', 1, 3000, 1, '2026-06-22 02:07:40', '2026-07-09 00:45:55'),
	(28, 'Mba Ria', 5, 'Chitato', 1, 2000, 0, '2026-06-22 02:07:40', '2026-06-22 02:07:40'),
	(29, 'Mba Ria', 6, 'Riry', 2, 1000, 1, '2026-06-22 02:07:40', '2026-07-04 12:43:47'),
	(30, 'BU Lidya', 4, 'Aquviva', 2, 6000, 1, '2026-07-04 15:13:01', '2026-07-15 03:37:32'),
	(31, 'Tasya', 7, 'Topo', 1, 2000, 0, '2026-07-08 07:49:40', '2026-07-08 07:49:40'),
	(32, 'Tasya', 8, 'Oreo Softcake', 1, 2000, 1, '2026-07-08 07:49:40', '2026-07-10 13:07:11'),
	(33, 'Bu Lidya', 4, 'Aquviva', 1, 3000, 0, '2026-07-10 14:31:41', '2026-07-10 14:31:41');

-- Dumping structure for table showcase_snack.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.finance_records
CREATE TABLE IF NOT EXISTS `finance_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jenis_transaksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `debit` int NOT NULL DEFAULT '0',
  `kredit` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.finance_records: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.jobs: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.job_batches: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.kas_manuals
CREATE TABLE IF NOT EXISTS `kas_manuals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('debit','kredit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` bigint NOT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.kas_manuals: ~8 rows (approximately)
INSERT INTO `kas_manuals` (`id`, `keterangan`, `tipe`, `nominal`, `tanggal`, `created_at`, `updated_at`) VALUES
	(10, 'Showcase', 'debit', 401500, '2026-07-03', '2026-07-12 06:09:15', '2026-07-12 06:09:15'),
	(11, 'Qris', 'debit', 73000, '2026-07-04', '2026-07-12 06:09:37', '2026-07-12 06:09:37'),
	(12, 'Yakult (4)', 'kredit', 40800, '2026-07-12', '2026-07-12 06:10:04', '2026-07-12 06:10:04'),
	(13, 'Showcase', 'debit', 75500, '2026-07-12', '2026-07-12 06:11:58', '2026-07-12 06:11:58'),
	(14, 'Qris', 'debit', 78000, '2026-07-12', '2026-07-12 06:12:17', '2026-07-12 06:13:11'),
	(15, 'Showcase', 'debit', 21500, '2026-07-12', '2026-07-12 06:13:24', '2026-07-12 06:13:24'),
	(16, 'Aquviva 2 pack', 'kredit', 44000, '2026-07-12', '2026-07-12 06:14:02', '2026-07-12 06:14:07'),
	(17, 'Lebihan', 'debit', 22700, '2026-07-12', '2026-07-12 06:14:27', '2026-07-12 06:14:27');

-- Dumping structure for table showcase_snack.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.migrations: ~14 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(20, '0001_01_01_000000_create_users_table', 1),
	(21, '0001_01_01_000001_create_cache_table', 1),
	(22, '0001_01_01_000002_create_jobs_table', 1),
	(23, '2026_04_25_031620_create_products_table', 1),
	(24, '2026_04_25_034032_create_transactions_table', 1),
	(25, '2026_04_26_142859_create_finance_records_table', 1),
	(26, '2026_04_26_163429_create_debts_table', 1),
	(27, '2026_04_28_141934_make_product_id_nullable_on_transactions_table', 1),
	(28, '2026_05_01_190630_create_kas_manuals_table', 1),
	(29, '2026_05_01_194657_add_kategori_to_products_table', 2),
	(30, '2026_05_01_194657_add_product_id_to_debts_table', 3),
	(31, '2026_05_03_133702_add_barang_to_debts_table', 4),
	(32, '2026_05_03_134603_fix_debts_columns', 5),
	(33, '2026_06_17_231204_add_nama_produk_manual_to_transactions_table', 6),
	(34, '2026_07_06_094534_add_tanggal_ke_kas_manual_table', 7);

-- Dumping structure for table showcase_snack.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table showcase_snack.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_merk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga` int NOT NULL,
  `stok_awal` int NOT NULL,
  `stok_sekarang` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.products: ~9 rows (approximately)
INSERT INTO `products` (`id`, `name_merk`, `kategori`, `harga`, `stok_awal`, `stok_sekarang`, `created_at`, `updated_at`) VALUES
	(2, 'Chocolatos', 'Umum', 500, 20, 10, '2026-05-01 13:24:03', '2026-07-12 07:24:17'),
	(3, 'Beng Beng', 'Umum', 2500, 71, 50, '2026-05-01 13:24:16', '2026-07-15 03:58:05'),
	(4, 'Aquviva', 'Umum', 3000, 53, 32, '2026-05-01 13:24:27', '2026-07-10 14:31:41'),
	(5, 'Chitato', 'Umum', 2000, 60, 45, '2026-05-01 13:24:47', '2026-07-19 04:30:12'),
	(6, 'Riry', 'Umum', 500, 10, 0, '2026-05-01 13:25:17', '2026-07-10 13:09:10'),
	(7, 'Lotte Toppo', 'Umum', 2000, 54, 48, '2026-05-01 13:25:51', '2026-07-12 13:03:21'),
	(8, 'Oreo Softcake', 'Umum', 2000, 20, 9, '2026-05-01 13:26:20', '2026-07-10 15:21:18'),
	(9, 'Astor', 'Umum', 1000, 30, 16, '2026-05-01 13:26:41', '2026-07-10 15:21:18'),
	(10, 'Yupi', 'Umum', 500, 30, 30, '2026-05-01 13:27:12', '2026-05-01 13:27:12'),
	(12, 'Balt\'z', 'Umum', 6500, 56, 56, '2026-07-04 13:53:17', '2026-07-04 13:53:17');

-- Dumping structure for table showcase_snack.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('CLHCdwwgIAEnmZ3CDQQK6Z8z9vKY6URjJaT7qQrW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJPZUFrTW1uRG1oRnFtQ2UycEo0WVhOMWZONnpraWJLV093ZnRTeG41IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvaGFyaWFuIiwicm91dGUiOiJhZG1pbi5oYXJpYW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1785303985);

-- Dumping structure for table showcase_snack.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `nama_pembeli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `total_harga` int NOT NULL,
  `metode_bayar` enum('cash','qris','hutang') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk_manual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_product_id_foreign` (`product_id`),
  CONSTRAINT `transactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.transactions: ~94 rows (approximately)
INSERT INTO `transactions` (`id`, `product_id`, `nama_pembeli`, `jumlah`, `total_harga`, `metode_bayar`, `nama_produk_manual`, `created_at`, `updated_at`) VALUES
	(2, 3, 'Aryan', 1, 2500, 'cash', NULL, '2026-05-01 13:28:26', '2026-05-01 13:28:26'),
	(3, 6, 'Aryan', 1, 500, 'cash', NULL, '2026-05-01 13:28:26', '2026-05-01 13:28:26'),
	(4, 3, 'Tasya', 1, 2500, 'cash', NULL, '2026-05-01 13:28:48', '2026-05-01 13:28:48'),
	(5, 5, 'Tasya', 1, 2000, 'cash', NULL, '2026-05-01 13:28:48', '2026-05-01 13:28:48'),
	(6, 7, 'Tasya', 1, 2000, 'cash', NULL, '2026-05-01 13:28:48', '2026-05-01 13:28:48'),
	(8, 2, 'Bu lidya', 1, 500, 'cash', NULL, '2026-05-02 12:39:52', '2026-05-02 12:39:52'),
	(9, 3, 'Bu lidya', 1, 2500, 'cash', NULL, '2026-05-02 12:39:52', '2026-05-02 12:39:52'),
	(10, 4, 'Bu lidya', 2, 6000, 'cash', NULL, '2026-05-02 12:39:52', '2026-05-02 12:39:52'),
	(14, 3, 'Tasya', 1, 2500, 'cash', NULL, '2026-05-02 23:44:39', '2026-05-02 23:44:39'),
	(15, 4, 'Tasya', 1, 3000, 'cash', NULL, '2026-05-02 23:44:39', '2026-05-02 23:44:39'),
	(19, 2, 'nahar', 1, 500, 'cash', NULL, '2026-05-02 23:45:25', '2026-05-02 23:45:25'),
	(20, 3, 'nahar', 1, 2500, 'cash', NULL, '2026-05-02 23:45:25', '2026-05-02 23:45:25'),
	(21, 5, 'nahar', 1, 2000, 'cash', NULL, '2026-05-02 23:45:25', '2026-05-02 23:45:25'),
	(25, 3, 'Tasya', 1, 2500, 'hutang', NULL, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(26, 4, 'Tasya', 1, 3000, 'hutang', NULL, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(27, 5, 'Tasya', 1, 2000, 'hutang', NULL, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(28, 9, 'Tasya', 1, 1000, 'hutang', NULL, '2026-05-03 06:50:43', '2026-05-03 06:50:43'),
	(29, 4, 'Tasya (Pelunasan Hutang)', 1, 3000, 'cash', NULL, '2026-05-03 06:54:34', '2026-05-03 06:54:34'),
	(30, 3, 'Bu Lidya', 1, 2500, 'hutang', NULL, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(31, 5, 'Bu Lidya', 1, 2000, 'hutang', NULL, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(32, 7, 'Bu Lidya', 1, 2000, 'hutang', NULL, '2026-05-03 08:48:55', '2026-05-03 08:48:55'),
	(33, 2, 'Bu Nurul', 1, 500, 'hutang', NULL, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(34, 3, 'Bu Nurul', 1, 2500, 'hutang', NULL, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(35, 6, 'Bu Nurul', 1, 500, 'hutang', NULL, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(36, 7, 'Bu Nurul', 1, 2000, 'hutang', NULL, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(37, 8, 'Bu Nurul', 1, 2000, 'hutang', NULL, '2026-05-03 08:49:46', '2026-05-03 08:49:46'),
	(38, 2, 'Pak Putut', 2, 1000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(39, 3, 'Pak Putut', 2, 5000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(40, 4, 'Pak Putut', 1, 3000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(41, 5, 'Pak Putut', 1, 2000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(42, 8, 'Pak Putut', 1, 2000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(43, 9, 'Pak Putut', 1, 1000, 'hutang', NULL, '2026-05-03 09:46:49', '2026-05-03 09:46:49'),
	(44, 4, 'fatimah', 1, 3000, 'cash', NULL, '2026-05-04 06:01:03', '2026-05-04 06:01:03'),
	(45, 5, 'fatimah', 1, 2000, 'cash', NULL, '2026-05-04 06:01:04', '2026-05-04 06:01:04'),
	(46, 3, 'aryan', 1, 2500, 'cash', NULL, '2026-05-04 07:19:24', '2026-05-04 07:19:24'),
	(47, 4, 'aryan', 1, 3000, 'cash', NULL, '2026-05-04 07:19:24', '2026-05-04 07:19:24'),
	(48, 8, 'aryan', 1, 2000, 'cash', NULL, '2026-05-04 07:19:24', '2026-05-04 07:19:24'),
	(49, 9, 'aryan', 1, 1000, 'cash', NULL, '2026-05-04 07:19:24', '2026-05-04 07:19:24'),
	(50, 7, 'Tasya', 1, 2000, 'cash', NULL, '2026-05-18 01:25:17', '2026-05-18 01:25:17'),
	(51, 8, 'Tasya', 1, 2000, 'hutang', NULL, '2026-05-18 01:27:51', '2026-05-18 01:27:51'),
	(52, 9, 'Tasya', 1, 1000, 'hutang', NULL, '2026-05-18 01:27:51', '2026-05-18 01:27:51'),
	(53, 3, 'Aryan', 1, 2500, 'hutang', NULL, '2026-05-19 13:51:25', '2026-05-19 13:51:25'),
	(54, 4, 'Aryan', 1, 3000, 'hutang', NULL, '2026-05-19 13:51:25', '2026-05-19 13:51:25'),
	(55, 5, 'Aryan', 1, 2000, 'hutang', NULL, '2026-05-19 13:51:25', '2026-05-19 13:51:25'),
	(56, 9, 'Aryan', 1, 1000, 'hutang', NULL, '2026-05-19 13:51:25', '2026-05-19 13:51:25'),
	(57, 3, 'Tasya', 1, 2500, 'hutang', NULL, '2026-05-19 14:05:02', '2026-05-19 14:05:02'),
	(58, 4, 'Tasya', 1, 3000, 'hutang', NULL, '2026-05-19 14:05:02', '2026-05-19 14:05:02'),
	(59, 7, 'Bu Nurul (Pelunasan Hutang)', 1, 2000, 'cash', NULL, '2026-05-23 07:43:58', '2026-05-23 07:43:58'),
	(60, 4, 'Tasya', 1, 3000, 'qris', NULL, '2026-06-14 16:52:37', '2026-06-14 16:52:37'),
	(61, 5, 'Tasya', 1, 2000, 'qris', NULL, '2026-06-14 16:52:37', '2026-06-14 16:52:37'),
	(62, 9, 'Aryan', 2, 2000, 'qris', NULL, '2026-06-15 01:19:45', '2026-06-15 01:19:45'),
	(64, 4, 'Mba Ria', 1, 3000, 'qris', NULL, '2026-06-15 01:27:21', '2026-06-15 01:27:21'),
	(65, 5, 'Mba Ria', 1, 2000, 'qris', NULL, '2026-06-15 01:27:21', '2026-06-15 01:27:21'),
	(66, 9, 'Mba Ria', 1, 1000, 'qris', NULL, '2026-06-15 01:27:21', '2026-06-15 01:27:21'),
	(67, 4, 'Tachul', 1, 3000, 'cash', NULL, '2026-06-17 16:57:16', '2026-06-17 16:57:16'),
	(68, 7, 'Tachul', 1, 2000, 'cash', NULL, '2026-06-17 16:57:16', '2026-06-17 16:57:16'),
	(69, 8, 'Tachul', 1, 2000, 'cash', NULL, '2026-06-17 16:57:16', '2026-06-17 16:57:16'),
	(70, 3, 'Aryan', 1, 2500, 'cash', NULL, '2026-06-17 16:58:02', '2026-06-17 16:58:02'),
	(73, 9, 'Aryan', 1, 1000, 'cash', NULL, '2026-06-17 16:58:02', '2026-06-17 16:58:02'),
	(74, 3, 'Bu Nurul', 1, 2500, 'qris', NULL, '2026-06-21 09:30:31', '2026-06-21 09:30:31'),
	(75, 8, 'Bu Nurul', 1, 2000, 'qris', NULL, '2026-06-21 09:30:31', '2026-06-21 09:30:31'),
	(77, NULL, 'Tasya', 1, 3000, 'cash', 'Aquviva (Pelunasan Hutang)', '2026-06-21 09:41:13', '2026-06-21 09:41:13'),
	(78, NULL, 'Pak Putut', 1, 2000, 'qris', 'Chitato (Pelunasan Hutang)', '2026-06-21 09:43:18', '2026-06-21 09:43:18'),
	(79, 2, 'Tasya', 2, 1000, 'cash', NULL, '2026-06-21 10:47:33', '2026-06-21 10:47:33'),
	(80, 3, 'Tasya', 1, 2500, 'cash', NULL, '2026-06-21 10:47:33', '2026-06-21 10:47:33'),
	(82, 4, 'Mba Ria', 1, 3000, 'hutang', NULL, '2026-06-22 02:07:39', '2026-06-22 02:07:39'),
	(83, 5, 'Mba Ria', 1, 2000, 'hutang', NULL, '2026-06-22 02:07:40', '2026-06-22 02:07:40'),
	(84, 6, 'Mba Ria', 2, 1000, 'hutang', NULL, '2026-06-22 02:07:40', '2026-06-22 02:07:40'),
	(85, 3, 'Bu Nurul', 1, 2500, 'cash', NULL, '2026-06-29 07:18:36', '2026-06-29 07:18:36'),
	(86, 4, 'Bu Nurul', 1, 3000, 'cash', NULL, '2026-06-29 07:18:36', '2026-06-29 07:18:36'),
	(87, 9, 'Bu Nurul', 1, 1000, 'cash', NULL, '2026-06-29 07:18:36', '2026-06-29 07:18:36'),
	(88, NULL, 'Mba Ria', 2, 1000, 'qris', 'Riry (Pelunasan Hutang)', '2026-07-04 12:43:47', '2026-07-04 12:43:47'),
	(89, 4, 'BU Lidya', 2, 6000, 'hutang', NULL, '2026-07-04 15:13:01', '2026-07-04 15:13:01'),
	(90, NULL, 'Tasya', 1, 1000, 'qris', 'Astor (Pelunasan Hutang)', '2026-07-06 15:00:59', '2026-07-06 15:00:59'),
	(91, 8, 'Mba Ria', 1, 2000, 'qris', NULL, '2026-07-08 07:47:55', '2026-07-08 07:47:55'),
	(92, 9, 'Mba Ria', 1, 1000, 'qris', NULL, '2026-07-08 07:47:55', '2026-07-08 07:47:55'),
	(93, 7, 'Tasya', 1, 2000, 'hutang', NULL, '2026-07-08 07:49:40', '2026-07-08 07:49:40'),
	(94, 8, 'Tasya', 1, 2000, 'hutang', NULL, '2026-07-08 07:49:40', '2026-07-08 07:49:40'),
	(95, 3, 'Aryan', 1, 2500, 'cash', NULL, '2026-07-09 00:36:42', '2026-07-09 00:36:42'),
	(96, 4, 'Aryan', 1, 3000, 'cash', NULL, '2026-07-09 00:36:42', '2026-07-09 00:36:42'),
	(97, 4, 'Tasya', 1, 3000, 'qris', NULL, '2026-07-09 00:37:00', '2026-07-09 00:37:00'),
	(98, 5, 'Tasya', 1, 2000, 'qris', NULL, '2026-07-09 00:37:00', '2026-07-09 00:37:00'),
	(99, 8, 'Tasya', 1, 2000, 'qris', NULL, '2026-07-09 00:37:00', '2026-07-09 00:37:00'),
	(100, NULL, 'Mba Ria', 1, 3000, 'qris', 'Aquviva (Pelunasan Hutang)', '2026-07-09 00:45:55', '2026-07-09 00:45:55'),
	(101, 4, 'Bu Sukma', 1, 3000, 'cash', NULL, '2026-07-10 13:03:55', '2026-07-10 13:03:55'),
	(102, 8, 'Bu Sukma', 1, 2000, 'cash', NULL, '2026-07-10 13:03:56', '2026-07-10 13:03:56'),
	(103, 9, 'Bu Sukma', 1, 1000, 'cash', NULL, '2026-07-10 13:03:56', '2026-07-10 13:03:56'),
	(104, 4, 'Ena', 1, 3000, 'qris', NULL, '2026-07-10 13:04:17', '2026-07-10 13:04:17'),
	(105, 5, 'Ena', 1, 2000, 'qris', NULL, '2026-07-10 13:04:17', '2026-07-10 13:04:17'),
	(106, 9, 'Ena', 1, 1000, 'qris', NULL, '2026-07-10 13:04:17', '2026-07-10 13:04:17'),
	(107, 5, 'Pak Putut', 1, 2000, 'cash', NULL, '2026-07-10 13:05:52', '2026-07-10 13:05:52'),
	(108, NULL, 'Tasya', 1, 2000, 'qris', 'Oreo Softcake (Pelunasan Hutang)', '2026-07-10 13:07:11', '2026-07-10 13:07:11'),
	(109, NULL, 'Aryan', 1, 3000, 'cash', 'Aquviva (Pelunasan Hutang)', '2026-07-10 13:07:38', '2026-07-10 13:07:38'),
	(110, 4, 'Bu Lidya', 1, 3000, 'hutang', NULL, '2026-07-10 14:31:40', '2026-07-10 14:31:40'),
	(111, 2, 'Pak Agustin', 1, 500, 'cash', NULL, '2026-07-10 15:08:51', '2026-07-10 15:08:51'),
	(112, 3, 'Pak Agustin', 1, 2500, 'cash', NULL, '2026-07-10 15:08:52', '2026-07-10 15:08:52'),
	(113, 5, 'Nisa', 1, 2000, 'cash', NULL, '2026-07-10 15:21:18', '2026-07-10 15:21:18'),
	(114, 8, 'Nisa', 1, 2000, 'cash', NULL, '2026-07-10 15:21:18', '2026-07-10 15:21:18'),
	(115, 9, 'Nisa', 1, 1000, 'cash', NULL, '2026-07-10 15:21:18', '2026-07-10 15:21:18'),
	(116, 2, 'Aryan', 2, 1000, 'cash', NULL, '2026-07-12 07:24:17', '2026-07-12 07:24:17'),
	(117, NULL, 'BU Lidya', 2, 6000, 'qris', 'Aquviva (Pelunasan Hutang)', '2026-07-15 03:37:32', '2026-07-15 03:37:32'),
	(118, 3, 'Nuril', 2, 5000, 'cash', NULL, '2026-07-15 03:58:05', '2026-07-15 03:58:05'),
	(119, 5, 'Mba Ria', 1, 2000, 'cash', NULL, '2026-07-19 04:30:12', '2026-07-19 04:30:12');

-- Dumping structure for table showcase_snack.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table showcase_snack.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$BsGWQ9yh9M7eA2WbzQ4So.xJsi2oxzedtg.C.QqiRrz4G4KpFdUga', NULL, '2026-05-01 12:54:31', '2026-05-01 12:54:31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
