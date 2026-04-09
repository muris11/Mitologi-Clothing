
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `proyek3` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `proyek3`;
DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Indonesia',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,2,'Rumah','Rifqy Customer','08123456789','Jl. Malioboro No. 123',NULL,'Yogyakarta','DI Yogyakarta','55271','Indonesia',1,'2026-04-01 14:17:56','2026-04-01 14:17:56'),(2,9,'Rumah','Rifqy','085773818846','VIlla Wanasari','Cibitung, Wanasari','Bekasi','Jawa Barat','17520','Indonesia',1,'2026-04-02 11:20:54','2026-04-02 11:20:54');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_variant_id_unique` (`cart_id`,`variant_id`),
  KEY `cart_items_variant_id_index` (`variant_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,NULL,'6cba464e-f665-4482-9aca-e1fb286e1d74','2026-03-30 08:11:11','2026-03-30 08:11:11'),(2,7,'10193a22-f33d-4e34-88b2-c17043877b23','2026-04-01 07:17:58','2026-04-01 07:21:56'),(3,8,'8b3dd3fb-a4a9-4c0a-8e60-4d14202faf63','2026-04-01 07:33:03','2026-04-01 07:33:03'),(4,9,'3feefa87-8498-409d-836a-fed2a8fc19a7','2026-04-01 07:33:54','2026-04-01 07:33:54'),(6,2,'a4335a4e-66e2-47ad-93dc-920eecf4de75','2026-04-01 14:17:31','2026-04-01 14:17:31'),(7,NULL,'beaf29bf-1dd8-44f2-a98e-5a6c668d42a4','2026-04-09 01:40:51','2026-04-09 01:40:51'),(8,NULL,'0ff8a165-7c34-4b1e-a174-e3dd26025924','2026-04-09 01:45:20','2026-04-09 01:45:20'),(9,NULL,'d84ad1f9-2294-48bb-900e-276043869ab6','2026-04-09 01:57:37','2026-04-09 01:57:37');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  UNIQUE KEY `categories_handle_unique` (`handle`),
  KEY `categories_handle_index` (`handle`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'T-Shirt','t-shirt','t-shirt','Koleksi kaos dengan desain mitologi Indonesia','categories/ZxtGK8STBZjIVYDnfJzmji8gJ3MlFCN8fkvX73T8.jpg',NULL,NULL,1,0,'2026-03-30 08:04:57','2026-04-01 12:30:13'),(2,'Hoodie','hoodie','hoodie','Hoodie premium dengan motif kustom','categories/Lc22UvPrGo5YrjTQLCNBQY7lLcN6uhyv8rQr9PPc.jpg',NULL,NULL,1,1,'2026-03-30 08:04:57','2026-04-01 12:30:36'),(3,'Jacket','jacket','jacket','Jaket berkualitas tinggi','categories/gnSlYBAt3hkqxhyd8EkDt4SUGhoLodsCCA9XeHNC.webp',NULL,NULL,1,2,'2026-03-30 08:04:57','2026-04-01 12:42:11'),(4,'Accessories','accessories','accessories','Aksesoris pelengkap gaya Anda',NULL,NULL,NULL,1,3,'2026-03-30 08:04:57','2026-03-30 08:04:57'),(5,'New Arrivals','new-arrivals','new-arrivals','Koleksi terbaru',NULL,NULL,NULL,1,4,'2026-03-30 08:04:57','2026-03-30 08:04:57'),(6,'Best Sellers','best-sellers','best-sellers','Produk terlaris',NULL,NULL,NULL,1,5,'2026-03-30 08:04:57','2026-03-30 08:04:57'),(7,'Homepage Carousel','hidden-homepage-carousel','hidden-homepage-carousel','Products shown in homepage carousel','categories/g2YL2KF95z4cDzbdO7fy5Bg0MlQSWtxn5zm9hWyi.jpg',NULL,NULL,1,99,'2026-03-30 08:04:57','2026-04-01 12:43:41'),(8,'Homepage Featured','hidden-homepage-featured-items','hidden-homepage-featured-items','Featured products on homepage','categories/ju998JnDBV93xPojbh81d3RXbbKRxqQNxCTIPQq8.jpg',NULL,NULL,1,98,'2026-03-30 08:04:58','2026-04-01 12:29:44');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `category_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_product_category_id_product_id_unique` (`category_id`,`product_id`),
  KEY `category_product_product_id_foreign` (`product_id`),
  CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `category_product` WRITE;
/*!40000 ALTER TABLE `category_product` DISABLE KEYS */;
INSERT INTO `category_product` VALUES (3,1,1),(7,1,3),(12,1,6),(4,2,2),(13,2,7),(8,3,4),(10,4,5),(2,5,1),(5,5,2),(9,5,4),(14,5,8),(1,6,1),(6,6,3),(11,6,6),(16,7,1),(17,7,2),(18,7,3),(19,7,4),(20,8,1),(21,8,2),(22,8,3);
/*!40000 ALTER TABLE `category_product` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `config_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `config_audit_logs_user_id_foreign` (`user_id`),
  KEY `config_audit_logs_group_created_at_index` (`group`,`created_at`),
  CONSTRAINT `config_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `config_audit_logs` WRITE;
/*!40000 ALTER TABLE `config_audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configurations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `is_sensitive` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configurations_key_unique` (`key`),
  KEY `configurations_group_index` (`group`),
  KEY `configurations_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `configurations` WRITE;
/*!40000 ALTER TABLE `configurations` DISABLE KEYS */;
/*!40000 ALTER TABLE `configurations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `facilities` WRITE;
/*!40000 ALTER TABLE `facilities` DISABLE KEYS */;
INSERT INTO `facilities` VALUES (1,'Workshop Pusat','Berlokasi di Indramayu, pusat operasi dan kontrol kualitas seluruh pesanan produksi pakaian. Dilengkapi dengan area produksi yang luas dan tim profesional.','facilities/jCQzwE6i3Y5pfO2FPEzSuSB2rZmUpuK5YdZF5Chq.jpg',1,1,'2026-03-30 08:05:01','2026-04-01 12:03:06'),(2,'Sablon Manual Presisi','Meja sablon banting presisi tinggi untuk menangani pesanan ribuan pcs dengan akurasi dan kecepatan maksimal. Mampu memproduksi hingga 5000 pcs per hari.','facilities/TR06h80DoziWexYztFTAYJh3rvbwc2kosm4v84WC.jpg',1,2,'2026-03-30 08:05:01','2026-04-01 12:03:40'),(3,'Mesin Sublimasi Digital','Printer sublimasi format besar untuk produksi jersey dan kain full pattern kualitas HD. Mendukung printing hingga lebar 1.6 meter dengan resolusi 1440 DPI.','facilities/8LxJQI6tTXQzGXt8MQPSzGrmpyLStjpzZNpXCWva.webp',1,3,'2026-03-30 08:05:01','2026-04-01 12:04:37'),(4,'Quality Control & Finishing','Area khusus untuk pengecekan detail pakaian (QC ketat), steam uap, dan packaging aman. Setiap produk melalui 3 tahap QC sebelum dikirim ke customer.','facilities/Ha9TAkouCjuSRbBuzs9PM7bvoMPP6gwbT8tBNgsS.jpg',1,4,'2026-03-30 08:05:01','2026-04-01 12:05:04'),(5,'Gudang Bahan Baku','Storage dengan kontrol kelembaban untuk menyimpan berbagai jenis kain dan bahan baku. Kapasitas penyimpanan hingga 10.000 meter kain.','facilities/FXPy3hNyaYRc8TMT6SAUlmzUhM8ITPaDaXSzrEaK.jpg',1,5,'2026-03-30 08:05:01','2026-04-01 12:05:40'),(6,'Ruang Desain & Pre-Production','Area khusus untuk proses desain, pembuatan film sablon, dan persiapan produksi. Dilengkapi dengan komputer desain dan mesin output film.','facilities/q6yppJxsKtyePckKhGOrEM2LxGmuCWPdHeNrS90n.webp',1,6,'2026-03-30 08:05:01','2026-04-01 12:06:05');
/*!40000 ALTER TABLE `facilities` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (1,'Garansi Tepat Waktu','Tim kami berkomitmen menyelesaikan pesanan sesuai deadline yang telah disepakati.','heroicon-o-clock',1,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(2,'Konsultasi Desain','Tim desainer kami siap membantu dari tahap konsep sampai hasil akhir tanpa biaya tambahan.','heroicon-o-pencil-square',1,2,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(3,'Garansi Bebas Pengembalian','Jika produk tidak sesuai spesifikasi, klaim garansi pengembalian bebas berlaku 7 hari.','heroicon-o-shield-check',1,3,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,'Mesin Produksi Sangat Cepat','Didukung oleh mesin produksi terkini berstandar operasional tinggi untuk kerja cerdas dan cepat.','heroicon-o-bolt',1,4,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(5,'Pengerjaan Tim Profesional','Dikerjakan oleh tim ahli dan berpengalaman untuk memastikan kualitas jahitan dan sablon terbaik.','heroicon-o-users',1,5,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(6,'Bonus Lebih','Order di atas ketentuan akan mendapatkan bonus gratis produk tambahan atau prioritas produksi.','heroicon-o-gift',1,6,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `hero_slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hero_slides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `hero_slides` WRITE;
/*!40000 ALTER TABLE `hero_slides` DISABLE KEYS */;
INSERT INTO `hero_slides` VALUES (1,'Mitologi Clothing','Vendor Konveksi Terpercaya di Yogyakarta. Spesialis Kaos, Kemeja, Jaket, dan Merchandise Komunitas.','hero-slides/69cdde272ff6b.webp','Konsultasi Gratis','https://wa.me/628123456789',1,1,'2026-03-30 08:05:00','2026-04-02 03:10:31'),(2,'Custom Apparel Berkualitas','Buat desain impianmu jadi nyata. Mulai dari 12 pcs, bebas custom desain dan bahan.','hero-slides/69cdd8b756bb0.webp','Lihat Katalog','/search',2,1,'2026-03-30 08:05:00','2026-04-02 02:47:19'),(3,'Promo Akhir Bulan','Diskon hingga 20% untuk pemesanan paket komunitas & organisasi. Berlaku sampai akhir bulan ini.','hero-slides/69cdd8ceae0d3.webp','Pesan Sekarang','https://wa.me/628123456789?text=Halo,%20saya%20mau%20tanya%20promo',3,1,'2026-03-30 08:05:00','2026-04-02 02:47:42');
/*!40000 ALTER TABLE `hero_slides` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_theme` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bg-gray-100 text-gray-800',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'Cotton Combed (24s & 30s)','Bahan adem, menyerap keringat. Standar distro untuk kenyamanan harian.','bg-green-100 text-green-800',1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(2,'Heavy Cotton (16s & 20s)','Tebal, solid, dan tahan lama. Cocok untuk pakaian oversized atau streetwear.','bg-slate-100 text-slate-800',2,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(3,'Cotton Fleece (280gsm & 330gsm)','Lembut di dalam, hangat dipakai. Bahan ideal untuk hoodie, crewneck, atau jaket ringan.','bg-indigo-100 text-indigo-800',3,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,'Baby Terry (Gramasi Menengah)','Tekstur unik yang nyaman, tidak setebal fleece namun tetap memberi kehangatan.','bg-blue-100 text-blue-800',4,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(5,'Drill (Nagata, American, dll)','Kuat, rapi, dan awet. Sering digunakan untuk kemeja PDH/PDL dan seragam kerja.','bg-amber-100 text-amber-800',5,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(6,'Lacoste (CVC & PE)','Bahan ikonik bertekstur pori untuk polo shirt, memberikan tampilan kasual dan profesional.','bg-teal-100 text-teal-800',6,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main-menu',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'All','/search','next-js-frontend-header-menu',0,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(2,'T-Shirt','/search/t-shirt','next-js-frontend-header-menu',1,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(3,'Hoodie','/search/hoodie','next-js-frontend-header-menu',2,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(4,'Jacket','/search/jacket','next-js-frontend-header-menu',3,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(5,'Accessories','/search/accessories','next-js-frontend-header-menu',4,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(6,'Home','/','next-js-frontend-footer-menu',0,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(7,'About','/about','next-js-frontend-footer-menu',1,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(8,'Terms & Conditions','/terms-conditions','next-js-frontend-footer-menu',2,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(9,'Privacy Policy','/privacy-policy','next-js-frontend-footer-menu',3,1,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(10,'FAQ','/faq','next-js-frontend-footer-menu',4,1,'2026-03-30 08:05:00','2026-03-30 08:05:00');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_01_000003_modify_users_table',1),(5,'2025_01_02_000001_create_categories_table',1),(6,'2025_01_02_000002_create_products_table',1),(7,'2025_01_02_000003_create_product_variants_table',1),(8,'2025_01_02_000004_create_product_options_table',1),(9,'2025_01_02_000005_create_product_images_table',1),(10,'2025_01_02_000006_create_category_product_table',1),(11,'2025_01_02_000007_create_variant_options_table',1),(12,'2025_01_03_000001_create_carts_table',1),(13,'2025_01_03_000002_create_cart_items_table',1),(14,'2025_01_04_000001_create_orders_table',1),(15,'2025_01_04_000002_create_order_items_table',1),(16,'2025_01_04_000003_create_shipping_addresses_table',1),(17,'2025_01_05_000001_create_user_interactions_table',1),(18,'2025_01_05_000002_create_site_settings_menus_pages_tables',1),(19,'2026_02_11_000001_add_tracking_number_to_orders_table',1),(20,'2026_02_12_004135_create_features_table',1),(21,'2026_02_12_004135_create_hero_slides_table',1),(22,'2026_02_12_004137_create_materials_table',1),(23,'2026_02_12_004137_create_testimonials_table',1),(24,'2026_02_12_004139_create_portfolio_items_table',1),(25,'2026_02_12_004140_create_order_steps_table',1),(26,'2026_02_12_005500_create_site_settings_table',1),(27,'2026_02_12_125129_add_options_to_product_variants_table',1),(28,'2026_02_12_220000_add_slug_description_to_portfolio_items',1),(29,'2026_02_13_100000_add_type_and_label_to_site_settings_table',1),(30,'2026_02_13_115331_add_type_to_order_steps_table',1),(31,'2026_02_16_000002_add_completed_to_orders_status',1),(32,'2026_02_21_185250_add_refund_fields_to_orders_table',1),(33,'2026_02_22_000001_create_webhook_events_table',1),(34,'2026_02_22_052356_create_product_reviews_table',1),(35,'2026_02_22_100000_add_performance_indexes',1),(36,'2026_02_22_134553_add_reserved_stock_to_product_variants_table',1),(37,'2026_02_23_130000_create_team_members_table',1),(38,'2026_02_23_212427_create_personal_access_tokens_table',1),(39,'2026_02_24_065118_add_soft_deletes_to_critical_tables',1),(40,'2026_02_28_190933_create_partners_table',1),(41,'2026_02_28_190934_create_printing_methods_table',1),(42,'2026_02_28_190934_create_product_pricings_table',1),(43,'2026_02_28_190935_create_facilities_table',1),(44,'2026_03_01_014702_add_is_active_to_features_table',1),(45,'2026_03_03_222045_create_addresses_table',1),(46,'2026_04_02_075235_add_indexes_to_cart_tables',2),(47,'2026_04_02_000000_create_configurations_table',3),(48,'2026_04_02_000001_create_config_audit_logs_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned NOT NULL,
  `product_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_variant_id_foreign` (`variant_id`),
  KEY `order_items_product_id_index` (`product_id`),
  KEY `order_items_order_id_index` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,3,23,'Barong Bali Graphic Tee','S / Black',169000.00,3,507000.00,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(2,1,7,51,'Hanoman Warrior Zip Hoodie','XXL / Grey',389000.00,3,1167000.00,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(3,2,2,16,'Naga Batak Heritage Hoodie','L / Maroon',349000.00,3,1047000.00,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(4,3,2,19,'Naga Batak Heritage Hoodie','XL / Maroon',349000.00,3,1047000.00,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(5,3,3,27,'Barong Bali Graphic Tee','L / Black',169000.00,2,338000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(6,3,5,35,'Kala Makara Snapback Cap','Navy',129000.00,1,129000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(7,4,2,20,'Naga Batak Heritage Hoodie','XXL / Navy',349000.00,2,698000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(8,5,2,19,'Naga Batak Heritage Hoodie','XL / Maroon',349000.00,1,349000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(9,5,7,50,'Hanoman Warrior Zip Hoodie','XXL / Black',389000.00,2,778000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(10,6,5,35,'Kala Makara Snapback Cap','Navy',129000.00,1,129000.00,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(11,7,5,34,'Kala Makara Snapback Cap','Black',129000.00,2,258000.00,'2026-04-01 07:24:00','2026-04-01 07:24:00'),(12,7,7,44,'Hanoman Warrior Zip Hoodie','M / Black',389000.00,2,778000.00,'2026-04-01 07:24:00','2026-04-01 07:24:00'),(13,8,8,52,'Jatayu Legend Oversized Tee','M / Black',199000.00,1,199000.00,'2026-04-01 07:36:51','2026-04-01 07:36:51'),(14,9,8,59,'Jatayu Legend Oversized Tee','XXL / Charcoal',199000.00,1,199000.00,'2026-04-01 07:43:18','2026-04-01 07:43:18'),(15,10,8,59,'Jatayu Legend Oversized Tee','XXL / Charcoal',199000.00,2,398000.00,'2026-04-02 11:58:06','2026-04-02 11:58:06'),(17,12,7,51,'Hanoman Warrior Zip Hoodie','XXL / Grey',389000.00,3,1167000.00,'2026-04-09 08:40:37','2026-04-09 08:40:37');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `order_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_steps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `step_number` int NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'langsung',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `order_steps` WRITE;
/*!40000 ALTER TABLE `order_steps` DISABLE KEYS */;
INSERT INTO `order_steps` VALUES (1,1,'langsung','Konsultasi Desain & Penawaran','Diskusikan desain, bahan, dan budget Anda dengan tim kami melalui WhatsApp atau datang langsung ke workshop.',1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(2,2,'langsung','DP & Produksi Dimulai','Pembayaran DP 50% untuk memulai proses produksi. Estimasi pengerjaan 7-14 hari kerja tergantung jumlah pesanan.',2,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(3,3,'langsung','QC & Pelunasan','Quality Control setiap produk sebelum dikirim. Kami kirimkan foto hasil produksi untuk approval sebelum pelunasan.',3,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,4,'langsung','Pengiriman / Ambil di Workshop','Pesanan dikirim ke alamat Anda via JNE/J&T/SiCepat atau bisa diambil langsung di workshop kami di Yogyakarta.',4,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(5,1,'ecommerce','Pilih Produk & Ukuran','Jelajahi katalog kami, pilih produk favorit, tentukan ukuran dan warna, lalu tambahkan ke keranjang.',5,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(6,2,'ecommerce','Checkout & Pembayaran','Isi alamat pengiriman, pilih metode pembayaran (Transfer Bank, GoPay, QRIS), dan selesaikan pembayaran.',6,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(7,3,'ecommerce','Konfirmasi & Packing','Pesanan diverifikasi otomatis, produk dipacking dengan aman menggunakan bubble wrap dan kardus premium.',7,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(8,4,'ecommerce','Pengiriman & Tracking','Pesanan dikirim via JNE/J&T/SiCepat. Anda akan mendapat nomor resi untuk tracking pesanan secara real-time.',8,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `order_steps` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','processing','shipped','delivered','completed','cancelled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tracking_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_order_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_payment_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `midtrans_response` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `refund_reason` text COLLATE utf8mb4_unicode_ci,
  `refund_requested_at` timestamp NULL DEFAULT NULL,
  `refund_admin_note` text COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_status_index` (`status`),
  KEY `orders_created_at_index` (`created_at`),
  KEY `orders_user_created_index` (`user_id`,`created_at`),
  KEY `orders_midtrans_txn_index` (`midtrans_transaction_id`),
  KEY `orders_midtrans_order_index` (`midtrans_order_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,2,'MC-20260330-BD77D2','completed',NULL,1674000.00,0.00,15000.00,1689000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-05 08:04:59','2026-03-05 08:04:59','2026-03-05 08:04:59',NULL),(2,3,'MC-20260330-BE4924','completed',NULL,1047000.00,0.00,15000.00,1062000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-10 08:04:59','2026-03-10 08:04:59','2026-03-10 08:04:59',NULL),(3,4,'MC-20260330-BEC15C','shipped',NULL,1514000.00,0.00,15000.00,1529000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-20 08:04:59','2026-03-20 08:04:59','2026-03-20 08:04:59',NULL),(4,5,'MC-20260330-C081D2','processing',NULL,698000.00,0.00,15000.00,713000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-25 08:05:00','2026-03-25 08:05:00','2026-03-25 08:05:00',NULL),(5,6,'MC-20260330-C126E1','pending',NULL,1127000.00,0.00,15000.00,1142000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-28 08:05:00','2026-03-28 08:05:00',NULL),(6,2,'MC-20260330-C1EBFD','processing',NULL,129000.00,0.00,15000.00,144000.00,'IDR','midtrans',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-27 08:05:00','2026-03-27 08:05:00','2026-03-27 08:05:00',NULL),(7,7,'MC-20260401-FC9B12','pending',NULL,1036000.00,0.00,0.00,1036000.00,'IDR',NULL,NULL,'MITOLOGI-69ccc80fc9b16-7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 07:23:59','2026-04-01 07:23:59',NULL),(8,7,'MC-20260401-39BCA6','pending',NULL,199000.00,0.00,0.00,199000.00,'IDR',NULL,NULL,'MITOLOGI-69cccb139bcaa-7',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 07:36:51','2026-04-01 07:36:51',NULL),(9,8,'MC-20260401-6D48A8','processing',NULL,199000.00,0.00,0.00,199000.00,'IDR',NULL,NULL,'MITOLOGI-69ccccf53cef0-8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-01 07:43:18','2026-04-08 05:45:38',NULL),(10,9,'MC-20260402-E3655D','completed','TEST1234',398000.00,0.00,0.00,398000.00,'IDR','qris',NULL,'MITOLOGI-69ce59ce36569-9',NULL,'settlement',NULL,NULL,NULL,NULL,NULL,'2026-04-02 11:58:37','2026-04-02 11:58:06','2026-04-02 12:10:21',NULL),(12,9,'MC-20260409-5EA9DE','completed','JNE321',1167000.00,0.00,0.00,1167000.00,'IDR','qris',NULL,'MITOLOGI-69d76605ea9e6-9',NULL,'settlement',NULL,NULL,NULL,NULL,NULL,'2026-04-09 08:41:02','2026-04-09 08:40:37','2026-04-09 08:42:38',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `body_summary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_handle_unique` (`handle`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'About','about','<h1>Tentang Mitologi Clothing</h1><p>Mitologi Clothing adalah vendor clothing asal Indramayu yang berdiri sejak 2022 dan bergerak dalam produksi berbagai jenis seragam dan merchandise untuk organisasi maupun instansi.</p><h2>Sejarah Kami</h2><p>Pada tahun 2015 terinspirasi oleh seorang teman yang sebagai afiliator konveksi yang sukses, terbentuklah RROArt. Lalu tanggal 20 November 2022 memulai bisnis Mitologi Clothing dari maklon sampai sekarang buka usaha sendiri.</p><h2>Visi</h2><p>Menjadi Brand yang dikenal dengan nuansa budaya dengan kualitas & Mutu terbaik</p><h2>Misi</h2><ol><li>Meningkatkan mutu dan standar pekerjaan</li><li>Menerapkan quality control yang tinggi</li><li>Memberikan pelayanan yang cepat dan mudah</li><li>Melakukan perniagaan sesuai aturan bernegara</li></ol><h2>Nilai-Nilai</h2><ul><li>Kejujuran</li><li>Produk yang berkualitas</li><li>Ketepatan waktu</li><li>Keberkahan usaha</li><li>Mengangkat budaya dan pariwisata di Indonesia</li></ul>','Vendor clothing terpercaya asal Indramayu sejak 2022, mengangkat budaya Nusantara melalui kualitas terbaik.',NULL,NULL,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(2,'Terms & Conditions','terms-conditions','<h1>Syarat & Ketentuan</h1><p>Dengan menggunakan layanan Mitologi Clothing, Anda menyetujui syarat dan ketentuan berikut:</p><h2>1. Pemesanan</h2><ul><li>Minimum order 12 pcs untuk custom production (kecuali produk satuan)</li><li>DP 50% diperlukan untuk memulai produksi</li><li>Pelunasan dilakukan setelah QC dan sebelum pengiriman</li></ul><h2>2. Produksi</h2><ul><li>Estimasi pengerjaan 7-14 hari kerja tergantung jumlah pesanan</li><li>Revisi desain hanya dilayani maksimal 3x</li><li>Produk yang sudah diproduksi tidak dapat dibatalkan</li></ul><h2>3. Garansi</h2><ul><li>Garansi tepat waktu: voucher 1 kaos gratis jika terlambat dari deadline</li><li>Garansi kualitas: klaim pengembalian berlaku 7 hari setelah terima barang</li><li>Garansi tidak berlaku untuk kerusakan akibat kesalahan pengguna</li></ul><h2>4. Pengiriman</h2><ul><li>Pengiriman via JNE/J&T/SiCepat atau ekspedisi lainnya</li><li>Ongkos kirim ditanggung pembeli</li><li>Resiko pengiriman menjadi tanggung jawab bersama hingga paket diterima</li></ul>','Syarat dan ketentuan penggunaan layanan Mitologi Clothing.',NULL,NULL,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(3,'Privacy Policy','privacy-policy','<h1>Kebijakan Privasi</h1><p>Mitologi Clothing berkomitmen menjaga privasi dan keamanan data Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p><h2>Informasi yang Kami Kumpulkan</h2><ul><li>Nama lengkap</li><li>Alamat email</li><li>Nomor telepon</li><li>Alamat pengiriman</li><li>Data pesanan dan riwayat transaksi</li></ul><h2>Penggunaan Informasi</h2><p>Informasi yang kami kumpulkan digunakan untuk:</p><ul><li>Memproses pesanan Anda</li><li>Menghubungi Anda terkait pesanan</li><li>Mengirimkan notifikasi promosi (dengan persetujuan Anda)</li><li>Meningkatkan kualitas layanan</li></ul><h2>Keamanan Data</h2><p>Kami menggunakan langkah-langkah keamanan yang sesuai untuk melindungi informasi pribadi Anda dari akses yang tidak sah, perubahan, pengungkapan, atau penghancuran.</p><h2>Pembagian Informasi</h2><p>Kami tidak akan menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan oleh hukum.</p>','Kebijakan privasi dan perlindungan data pelanggan Mitologi Clothing.',NULL,NULL,'2026-03-30 08:05:00','2026-03-30 08:05:00'),(4,'FAQ','faq','<h1>Pertanyaan yang Sering Diajukan</h1><h2>Pemesanan & Produksi</h2><dl><dt>Berapa minimum order?</dt><dd>Minimum order 12 pcs untuk custom production. Untuk produk satuan tersedia di katalog e-commerce.</dd><dt>Berapa lama proses produksi?</dt><dd>Estimasi 7-14 hari kerja tergantung jumlah pesanan dan kompleksitas desain.</dd><dt>Apakah bisa order sampel dulu?</dt><dd>Ya, kami menyediakan layanan pembuatan sampel dengan biaya yang dapat disesuaikan.</dd><dt>Bahan apa saja yang tersedia?</dt><dd>Kami menyediakan berbagai bahan: Cotton Combed (24s/30s), Cotton Fleece, Heavy Cotton, Baby Terry, Drill (American/Nagata/Japan), Lacoste (CVC/PE), dan Dry-fit untuk jersey.</dd></dl><h2>Pembayaran</h2><dl><dt>Metode pembayaran apa yang tersedia?</dt><dd>Kami menerima transfer bank (BRI, BJB), e-wallet (OVO, ShopeePay, DANA), dan QRIS.</dt><dt>Apakah wajib DP?</dt><dd>Ya, DP 50% diperlukan untuk memulai produksi.</dd></dl><h2>Pengiriman</h2><dl><dt>Ekspedisi apa yang digunakan?</dt><dd>Kami bekerja sama dengan JNE, J&T Express, SiCepat, AnterAja, dan Ninja Express.</dd><dt>Berapa lama pengiriman?</dt><dd>Jawa: 2-5 hari kerja. Luar Jawa: 5-10 hari kerja.</dd><dt>Apakah bisa ambil langsung di workshop?</dt><dd>Ya, Anda bisa mengambil pesanan langsung di workshop kami di Desa Leuwigede, Kec. Widasari, Kab. Indramayu.</dd></dl><h2>Garansi</h2><dl><dt>Apa saja garansi yang diberikan?</dt><dd>Garansi tepat waktu (voucher 1 kaos jika terlambat), garansi kualitas (klaim 7 hari), dan garansi layanan (voucher jika tidak dilayani dalam 1x24 jam).</dd><dt>Bagaimana cara klaim garansi?</dt><dd>Hubungi kami via WhatsApp dengan menyertakan foto produk dan bukti pembelian dalam waktu 7 hari setelah diterima.</dd></dl>','Pertanyaan yang sering diajukan tentang pemesanan, produksi, pembayaran, dan pengiriman.',NULL,NULL,'2026-03-30 08:05:00','2026-03-30 08:05:00');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'PT Brata Karya Indonesia','partners/1yKr1E6CENgkk8zr20c1k5HSFbGAOWNcHP75BRff.png','https://www.bratakarya.co.id','Corporate uniform & merchandise partner. Mitra strategis untuk produksi seragam perusahaan dan merchandise korporat.',1,1,'2026-03-30 08:05:01','2026-04-01 11:54:00'),(2,'Honda Vario LED Indonesia','partners/Q1UhJwtHRYCA0PmzgZSIqHgA61mPk9mzKPbNkwax.jpg','https://www.hondavarioled.id','Automotive community merchandise. Komunitas pecinta Honda Vario terbesar di Indonesia dengan anggota lebih dari 50.000 member.',1,2,'2026-03-30 08:05:01','2026-04-01 11:55:31'),(3,'HVC Jakarta','partners/1gup9fnZD3HK8VJ2VwBZeoEhJL6h4sJsi3u3hxSA.png','https://www.hvcjakarta.com','Honda Vario Club Jakarta official merch supplier. Supplier resmi merchandise untuk komunitas Honda Vario Club Jakarta.',1,3,'2026-03-30 08:05:01','2026-04-01 11:54:48'),(4,'Bali Screen Printing Community','partners/z8fzt0TBkMmvOwQqhCaXGsrQub9pWQxxBXmZ9k8R.png','https://www.baliscreenprinting.com','Event partnership & production support. Komunitas screen printing Bali yang mendukung produksi dan event-event kreatif.',1,4,'2026-03-30 08:05:01','2026-04-01 11:55:55');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (5,'App\\Models\\User',7,'auth-token','948ec8b366519960692952322a25490614c4922a6dee464410766d94491362b3','[\"*\"]','2026-04-01 07:38:43',NULL,'2026-04-01 07:36:16','2026-04-01 07:38:43'),(8,'App\\Models\\User',2,'auth-token','d468632280d309a56521a853e961af9ab944e9166c0f32183fd86eb58fc64656','[\"*\"]','2026-04-01 14:17:56',NULL,'2026-04-01 14:17:31','2026-04-01 14:17:56'),(10,'App\\Models\\User',10,'auth-token','33ec162b6310d76fd88ae8625bc9e5474948e39d14d6cfe20593417db0439d1c','[\"*\"]','2026-04-09 01:41:07',NULL,'2026-04-09 01:40:51','2026-04-09 01:41:07'),(11,'App\\Models\\User',11,'auth-token','f1c92db4ddf4e68d87346f5149c3187f12de564123ab7ecb3a2fbbb09eb03e1f','[\"*\"]','2026-04-09 01:45:21',NULL,'2026-04-09 01:45:20','2026-04-09 01:45:21'),(12,'App\\Models\\User',12,'auth-token','09ca79687eb6ebf02cdda5f749d205890ad97cffdc31df62309c7557771c88c3','[\"*\"]','2026-04-09 01:57:50',NULL,'2026-04-09 01:57:37','2026-04-09 01:57:50');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `portfolio_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolio_items_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `portfolio_items` WRITE;
/*!40000 ALTER TABLE `portfolio_items` DISABLE KEYS */;
INSERT INTO `portfolio_items` VALUES (1,'Kaos Panitia Festival Budaya UGM 2025','kaos-panitia-festival-budaya-ugm-2025','T-Shirt','Produksi 200 pcs kaos Cotton Combed 30s dengan sablon DTF full color untuk panitia Festival Budaya UGM. Warna dasar hitam dengan desain motif batik modern.','portfolio/WY05hzbM0i8isCN9Bsbz7Rr60nkIVhYXGSyyAXUO.webp',1,1,'2026-03-30 08:05:01','2026-04-01 11:48:36'),(2,'Hoodie Komunitas Pendaki Merapi','hoodie-komunitas-pendaki-merapi','T-Shirt','Produksi 50 pcs hoodie Cotton Fleece 330gsm untuk Komunitas Pendaki Merapi. Desain bordir logo di dada kiri dan sablon punggung.','portfolio/Az75uknGmO9SP7kfzweizOArL9BI8MAoTXpoun0L.jpg',2,1,'2026-03-30 08:05:01','2026-04-01 11:50:34'),(3,'Jersey Tim Futsal \"Garuda Muda FC\"','jersey-tim-futsal-garuda-muda-fc','T-Shirt','Produksi 30 pcs jersey Dryfit Benzema sublimation print untuk Tim Futsal Garuda Muda FC. Full print desain custom dengan nama dan nomor punggung.','portfolio/dWvwrN7e5go9uGZ0DgVCoScgFlCBmoYYzi2stSZ7.jpg',3,1,'2026-03-30 08:05:01','2026-04-01 11:51:10'),(4,'Kemeja PDH Angkatan 2024 FT UNY','kemeja-pdh-angkatan-2024-ft-uny','T-Shirt','Produksi 150 pcs kemeja PDH American Drill untuk angkatan 2024 Fakultas Teknik UNY. Bordir logo kampus dan nama di dada, warna krem.','portfolio/eLzc2OYNNrWMJCcyGpDh4iOe1yYZzV9iPw6pPqNS.jpg',4,1,'2026-03-30 08:05:01','2026-04-01 11:52:25'),(5,'Jaket Bomber Crew Event Musik Jogja','jaket-bomber-crew-event-musik-jogja','T-Shirt','Produksi 25 pcs jaket bomber parasut untuk crew event musik di Jogja. Bordir logo di dada dan punggung, warna hitam-gold.','portfolio/J94SZQmxTEbMEyvDw3cXUbb99Tz8yeQmU42klQVy.webp',5,1,'2026-03-30 08:05:01','2026-04-01 11:52:55'),(6,'Polo Shirt Seragam PT. Nusantara Jaya','polo-shirt-seragam-pt-nusantara-jaya','T-Shirt','Produksi 100 pcs polo shirt Lacoste CVC untuk seragam karyawan PT. Nusantara Jaya. Bordir logo perusahaan di dada kiri, warna navy dan putih.','portfolio/9gKlM40YqSlKJnx1eIU9WacbwNepBF0tthFrJgFo.jpg',6,1,'2026-03-30 08:05:01','2026-04-01 11:53:26');
/*!40000 ALTER TABLE `portfolio_items` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `printing_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `printing_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pros` json DEFAULT NULL,
  `price_range` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `printing_methods_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `printing_methods` WRITE;
/*!40000 ALTER TABLE `printing_methods` DISABLE KEYS */;
INSERT INTO `printing_methods` VALUES (1,'Sablon Plastisol','sablon-plastisol','Tinta sablon oil-based standar internasional dengan warna pekat, daya tahan tinggi, dan efek raster detail. Cocok untuk produksi massal dengan kualitas distro.','printing-methods/plastisol.jpg','[\"Warna cerah dan tajam\", \"Sangat awet (tidak mudah pecah/luntur)\", \"Standar distro brand besar\", \"Tahan hingga 50+ kali cuci\", \"Bisa efek khusus (glow in dark, puff, foil)\"]','Mulai dari Rp 45.000/pcs',1,1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(2,'Sablon Discharge','sablon-discharge','Tinta cabut warna yang menyerap dan menghilangkan warna asli kain, memberikan handfeel super lembut menyatu dengan kain. Ideal untuk kaos premium dengan kesan vintage.','printing-methods/discharge.jpg','[\"Handfeel sangat lembut\", \"Tidak panas saat dipakai (breathable)\", \"Warna natural dan pudar menyatu (vintage look)\", \"Menyatu dengan serat kain\", \"Cocok untuk bahan Cotton Combed\"]','Mulai dari Rp 55.000/pcs',1,2,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(3,'DTF (Direct to Film)','dtf-printing','Teknologi printing digital modern tanpa batasan warna. Cocok untuk desain full color seperti foto atau gradasi kompleks. Bisa diterapkan di berbagai jenis bahan.','printing-methods/dtf.jpg','[\"Bisa cetak desain full printing/foto\", \"Warna solid dan presisi tinggi\", \"Bisa order satuan tidak harus lusinan\", \"Tanpa minimal order\", \"Cocok untuk desain kompleks\"]','Mulai Rp 50.000 (Tergantung ukuran)',1,3,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,'Sublimation Printing','sublimation-printing','Teknik cetak khusus polyester/dry-fit di mana tinta menyublim ke dalam serat kain. Ideal untuk jersey basket/futsal, pakaian olahraga, dan produk dengan full print pattern.','printing-methods/sublimation.jpg','[\"Warna permanen masuk ke serat kain\", \"Full print seluruh bidang (all-over print)\", \"Anti luntur dan pudar\", \"Cocok untuk jersey olahraga\", \"Tidak terasa saat dipakai\"]','Mulai dari Rp 65.000 (Fullprint)',1,4,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `printing_methods` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Garuda+Oversize+Tee+1','Garuda Oversize Tee - Image 1',800,800,1,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(2,1,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Garuda+Oversize+Tee+2','Garuda Oversize Tee - Image 2',800,800,2,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(3,1,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Garuda+Oversize+Tee+3','Garuda Oversize Tee - Image 3',800,800,3,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(4,2,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Naga+Batak+Heritage+Hoodie+1','Naga Batak Heritage Hoodie - Image 1',800,800,1,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(5,2,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Naga+Batak+Heritage+Hoodie+2','Naga Batak Heritage Hoodie - Image 2',800,800,2,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(6,2,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Naga+Batak+Heritage+Hoodie+3','Naga Batak Heritage Hoodie - Image 3',800,800,3,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(7,3,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Barong+Bali+Graphic+Tee+1','Barong Bali Graphic Tee - Image 1',800,800,1,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(8,3,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Barong+Bali+Graphic+Tee+2','Barong Bali Graphic Tee - Image 2',800,800,2,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(9,3,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Barong+Bali+Graphic+Tee+3','Barong Bali Graphic Tee - Image 3',800,800,3,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(10,4,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Rangda+Dark+Bomber+Jacket+1','Rangda Dark Bomber Jacket - Image 1',800,800,1,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(11,4,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Rangda+Dark+Bomber+Jacket+2','Rangda Dark Bomber Jacket - Image 2',800,800,2,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(12,4,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Rangda+Dark+Bomber+Jacket+3','Rangda Dark Bomber Jacket - Image 3',800,800,3,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(13,5,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Kala+Makara+Snapback+Cap+1','Kala Makara Snapback Cap - Image 1',800,800,1,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(14,5,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Kala+Makara+Snapback+Cap+2','Kala Makara Snapback Cap - Image 2',800,800,2,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(15,5,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Kala+Makara+Snapback+Cap+3','Kala Makara Snapback Cap - Image 3',800,800,3,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(16,6,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Dewi+Sri+Embroidered+Tee+1','Dewi Sri Embroidered Tee - Image 1',800,800,1,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(17,6,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Dewi+Sri+Embroidered+Tee+2','Dewi Sri Embroidered Tee - Image 2',800,800,2,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(18,6,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Dewi+Sri+Embroidered+Tee+3','Dewi Sri Embroidered Tee - Image 3',800,800,3,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(19,7,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Hanoman+Warrior+Zip+Hoodie+1','Hanoman Warrior Zip Hoodie - Image 1',800,800,1,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(20,7,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Hanoman+Warrior+Zip+Hoodie+2','Hanoman Warrior Zip Hoodie - Image 2',800,800,2,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(21,7,'https://placehold.co/800x800/1a1a2e/e0e0e0?text=Hanoman+Warrior+Zip+Hoodie+3','Hanoman Warrior Zip Hoodie - Image 3',800,800,3,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(25,8,'products/gallery/3hrHFJdJa0aTcZfGxg5b41AUFN9ACFyQrD3iblet.png',NULL,NULL,NULL,0,'2026-04-01 07:18:49','2026-04-01 07:18:49');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `values` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_options_product_id_foreign` (`product_id`),
  CONSTRAINT `product_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_options` WRITE;
/*!40000 ALTER TABLE `product_options` DISABLE KEYS */;
INSERT INTO `product_options` VALUES (1,1,'Size','[\"S\", \"M\", \"L\", \"XL\", \"XXL\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(2,1,'Color','[\"Black\", \"White\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(3,2,'Size','[\"M\", \"L\", \"XL\", \"XXL\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(4,2,'Color','[\"Navy\", \"Black\", \"Maroon\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(5,3,'Size','[\"S\", \"M\", \"L\", \"XL\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(6,3,'Color','[\"Black\", \"Dark Green\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(7,4,'Size','[\"M\", \"L\", \"XL\"]','2026-03-30 08:04:58','2026-03-30 08:04:58'),(8,5,'Color','[\"Black\", \"Navy\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(9,6,'Size','[\"S\", \"M\", \"L\", \"XL\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(10,6,'Color','[\"White\", \"Cream\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(11,7,'Size','[\"M\", \"L\", \"XL\", \"XXL\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(12,7,'Color','[\"Black\", \"Grey\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(13,8,'Size','[\"M\", \"L\", \"XL\", \"XXL\"]','2026-03-30 08:04:59','2026-03-30 08:04:59'),(14,8,'Color','[\"Black\", \"Charcoal\"]','2026-03-30 08:04:59','2026-03-30 08:04:59');
/*!40000 ALTER TABLE `product_options` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_pricings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_pricings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `items` json DEFAULT NULL,
  `min_order` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_pricings` WRITE;
/*!40000 ALTER TABLE `product_pricings` DISABLE KEYS */;
INSERT INTO `product_pricings` VALUES (1,'Kaos Custom (T-Shirt)','[{\"name\": \"Cotton Combed 24s/30s (Basic)\", \"price_range\": \"Rp 45.000 - Rp 55.000 / pcs\"}, {\"name\": \"Cotton Bamboo (Premium)\", \"price_range\": \"Rp 65.000 - Rp 75.000+ / pcs\"}, {\"name\": \"Heavyweight Cotton (Streetwear)\", \"price_range\": \"Rp 70.000 - Rp 90.000 / pcs\"}, {\"name\": \"Polyester PE (Ekonomis)\", \"price_range\": \"Rp 35.000 - Rp 45.000 / pcs\"}, {\"name\": \"CVC (Cotton Viscose)\", \"price_range\": \"Rp 50.000 - Rp 60.000 / pcs\"}]','12 Pcs','Tergantung kuantitas pesanan, kesulitan sablon, dan jenis bahan. Semakin banyak harga lebih bisa disesuaikan. Harga sudah termasuk sablon 1 warna 1 sisi.',1,1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(2,'Hoodie & Sweater','[{\"name\": \"Cotton Fleece 240gsm\", \"price_range\": \"Rp 110.000 - Rp 135.000 / pcs\"}, {\"name\": \"Cotton Fleece 280-330gsm (Premium)\", \"price_range\": \"Rp 135.000 - Rp 150.000+ / pcs\"}, {\"name\": \"Crewneck Sweater\", \"price_range\": \"Rp 100.000 - Rp 120.000 / pcs\"}, {\"name\": \"Zip Hoodie (Full Zipper)\", \"price_range\": \"Rp 140.000 - Rp 165.000 / pcs\"}, {\"name\": \"Oversized Hoodie\", \"price_range\": \"Rp 145.000 - Rp 170.000 / pcs\"}]','12 Pcs','Termasuk sablon/bordir dasar. Harga dapat berubah tergantung kompleksitas desain dan jumlah warna.',1,2,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(3,'Jersey Fullprinting','[{\"name\": \"Jersey Lengan Pendek\", \"price_range\": \"Rp 50.000 - Rp 65.000 / pcs\"}, {\"name\": \"Jersey Lengan Panjang\", \"price_range\": \"Rp 60.000 - Rp 70.000 / pcs\"}, {\"name\": \"Satu Set (Baju + Celana)\", \"price_range\": \"Rp 85.000 - Rp 110.000 / set\"}, {\"name\": \"Jersey Kiper (Long Sleeve)\", \"price_range\": \"Rp 65.000 - Rp 75.000 / pcs\"}, {\"name\": \"Jersey Training (Rompi)\", \"price_range\": \"Rp 35.000 - Rp 45.000 / pcs\"}]','12 Pcs','Pilihan bahan: Dry-fit Benzema, MTis, Pique, Brazil. Harga sudah termasuk full print sublimation.',1,3,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,'Kemeja & Wearpack','[{\"name\": \"Kemeja PDH/PDL\", \"price_range\": \"Rp 130.000 - Rp 165.000 / pcs\"}, {\"name\": \"Wearpack Safety (Scotlight)\", \"price_range\": \"Rp 250.000 - Rp 270.000 / pcs\"}, {\"name\": \"Rompi / Semi-Jacket\", \"price_range\": \"Rp 120.000 - Rp 150.000 / pcs\"}, {\"name\": \"Kemeja Kerja (American Drill)\", \"price_range\": \"Rp 125.000 - Rp 145.000 / pcs\"}, {\"name\": \"Jaket Bomber\", \"price_range\": \"Rp 180.000 - Rp 220.000 / pcs\"}, {\"name\": \"Jaket Parka\", \"price_range\": \"Rp 200.000 - Rp 250.000 / pcs\"}]','12 Pcs','Pilihan bahan: Nagata Drill, American Drill, Japan Drill. Include bordir (punggung & 2 emblem).',1,4,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(5,'Polo Shirt (Kerah)','[{\"name\": \"Lacoste PE (Basic)\", \"price_range\": \"Rp 75.000 - Rp 85.000 / pcs\"}, {\"name\": \"Lacoste CVC (Premium)\", \"price_range\": \"Rp 85.000 - Rp 95.000 / pcs\"}, {\"name\": \"Lacoste Cotton 100%\", \"price_range\": \"Rp 90.000 - Rp 105.000 / pcs\"}, {\"name\": \"Pique Polo\", \"price_range\": \"Rp 80.000 - Rp 95.000 / pcs\"}]','12 Pcs','Cocok untuk seragam kantor, komunitas, atau event formal. Include bordir logo dada.',1,5,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(6,'Merchandise & Aksesoris','[{\"name\": \"Topi Custom (Min. 20 pcs)\", \"price_range\": \"Rp 20.000 - Rp 35.000 / pcs\"}, {\"name\": \"Tas Canvas (Min. 50 pcs)\", \"price_range\": \"Rp 40.000 - Rp 60.000 / pcs\"}, {\"name\": \"Waistbag (Min. 50 pcs)\", \"price_range\": \"Rp 55.000 - Rp 75.000 / pcs\"}, {\"name\": \"Lanyard (Min. 50 pcs)\", \"price_range\": \"Rp 15.000 - Rp 25.000 / pcs\"}, {\"name\": \"Tote Bag (Min. 30 pcs)\", \"price_range\": \"Rp 25.000 - Rp 40.000 / pcs\"}]','Varies','Minimum order berbeda untuk setiap produk. Harga dapat berubah tergantung bahan dan kompleksitas.',1,6,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `product_pricings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `rating` tinyint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  `admin_replied_at` timestamp NULL DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_reviews_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `product_reviews_user_id_foreign` (`user_id`),
  KEY `product_reviews_order_id_foreign` (`order_id`),
  KEY `product_reviews_is_visible_index` (`is_visible`),
  KEY `product_reviews_product_visible_index` (`product_id`,`is_visible`),
  CONSTRAINT `product_reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
INSERT INTO `product_reviews` VALUES (1,1,2,1,5,'Kualitas bahannya juara! Cotton combed 30s-nya lembut banget, sablon DTF-nya juga tahan lama. Sudah dicuci berkali-kali masih bagus.',NULL,NULL,1,'2026-03-20 08:05:00','2026-03-30 08:05:00'),(2,2,3,2,5,'Hoodie-nya tebal dan hangat, cocok banget buat di daerah Kaliurang. Desain Naga Batak-nya keren banget, banyak yang nanya beli dimana.',NULL,NULL,1,'2026-03-23 08:05:00','2026-03-30 08:05:00'),(3,3,4,1,4,'Desainnya unik dan kualitas oke. Cuma size-nya agak kecil, next order mau ambil satu size lebih besar.',NULL,NULL,1,'2026-03-26 08:05:00','2026-03-30 08:05:00'),(4,4,5,2,5,'Bomber jacket terbaik yang pernah saya punya! Bordir Rangda-nya super detail, dan bahannya water-resistant beneran. Worth every penny.',NULL,NULL,1,'2026-03-17 08:05:00','2026-03-30 08:05:00'),(5,6,6,1,5,'Bordirannya halus banget, detailnya luar biasa. Kaosnya juga nyaman dipakai seharian. Recommended!',NULL,NULL,1,'2026-03-09 08:05:00','2026-03-30 08:05:00'),(6,7,2,2,4,'Zip hoodie-nya keren, print Hanoman-nya detail. Kangaroo pocket-nya juga fungsional. Cuma ritsleting agak seret di awal.',NULL,NULL,1,'2026-03-26 08:05:00','2026-03-30 08:05:00'),(7,8,3,1,5,'Suka banget sama desain Jatayu-nya, artistik dan mythical. Oversize fit-nya pas, nggak kebesaran. Bahan adem juga.',NULL,NULL,1,'2026-03-02 08:05:00','2026-03-30 08:05:00'),(8,5,4,2,4,'Topinya bagus, bordirannya rapi. Bahan topinya tebal dan kokoh. Adjustable strap-nya juga nyaman.',NULL,NULL,1,'2026-03-06 08:05:00','2026-03-30 08:05:00');
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_for_sale` tinyint(1) NOT NULL DEFAULT '1',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `currency_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `stock` int NOT NULL DEFAULT '0',
  `reserved_stock` int NOT NULL DEFAULT '0',
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_index` (`product_id`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'S / Black',1,189000.00,'IDR',9,0,'MTG-GARUDA-BLA-S',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(2,1,'S / White',1,189000.00,'IDR',19,0,'MTG-GARUDA-WHI-S',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(3,1,'M / Black',1,189000.00,'IDR',23,0,'MTG-GARUDA-BLA-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(4,1,'M / White',1,189000.00,'IDR',50,0,'MTG-GARUDA-WHI-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(5,1,'L / Black',1,189000.00,'IDR',30,0,'MTG-GARUDA-BLA-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(6,1,'L / White',1,189000.00,'IDR',30,0,'MTG-GARUDA-WHI-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(7,1,'XL / Black',1,189000.00,'IDR',32,0,'MTG-GARUDA-BLA-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(8,1,'XL / White',1,189000.00,'IDR',32,0,'MTG-GARUDA-WHI-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(9,1,'XXL / Black',1,189000.00,'IDR',44,0,'MTG-GARUDA-BLA-XXL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(10,1,'XXL / White',1,189000.00,'IDR',20,0,'MTG-GARUDA-WHI-XXL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(11,2,'M / Navy',1,349000.00,'IDR',38,0,'MTG-NAGA-NAV-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(12,2,'M / Black',1,349000.00,'IDR',23,0,'MTG-NAGA-BLA-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(13,2,'M / Maroon',1,349000.00,'IDR',33,0,'MTG-NAGA-MAR-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(14,2,'L / Navy',1,349000.00,'IDR',9,0,'MTG-NAGA-NAV-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(15,2,'L / Black',1,349000.00,'IDR',48,0,'MTG-NAGA-BLA-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(16,2,'L / Maroon',1,349000.00,'IDR',15,0,'MTG-NAGA-MAR-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(17,2,'XL / Navy',1,349000.00,'IDR',9,0,'MTG-NAGA-NAV-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(18,2,'XL / Black',1,349000.00,'IDR',26,0,'MTG-NAGA-BLA-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(19,2,'XL / Maroon',1,349000.00,'IDR',28,0,'MTG-NAGA-MAR-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(20,2,'XXL / Navy',1,349000.00,'IDR',8,0,'MTG-NAGA-NAV-XXL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(21,2,'XXL / Black',1,349000.00,'IDR',15,0,'MTG-NAGA-BLA-XXL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(22,2,'XXL / Maroon',1,349000.00,'IDR',19,0,'MTG-NAGA-MAR-XXL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(23,3,'S / Black',1,169000.00,'IDR',44,0,'MTG-BARONG-BLA-S',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(24,3,'S / Dark Green',1,169000.00,'IDR',36,0,'MTG-BARONG-DAR-S',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(25,3,'M / Black',1,169000.00,'IDR',41,0,'MTG-BARONG-BLA-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(26,3,'M / Dark Green',1,169000.00,'IDR',33,0,'MTG-BARONG-DAR-M',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(27,3,'L / Black',1,169000.00,'IDR',33,0,'MTG-BARONG-BLA-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(28,3,'L / Dark Green',1,169000.00,'IDR',5,0,'MTG-BARONG-DAR-L',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(29,3,'XL / Black',1,169000.00,'IDR',12,0,'MTG-BARONG-BLA-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(30,3,'XL / Dark Green',1,169000.00,'IDR',34,0,'MTG-BARONG-DAR-XL',NULL,'2026-03-30 08:04:58','2026-03-30 08:04:58'),(31,4,'M',1,489000.00,'IDR',18,0,'MTG-RANGDA-M',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(32,4,'L',1,489000.00,'IDR',6,0,'MTG-RANGDA-L',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(33,4,'XL',1,489000.00,'IDR',5,0,'MTG-RANGDA-XL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(34,5,'Black',1,129000.00,'IDR',7,2,'MTG-KALA-BLACK',NULL,'2026-03-30 08:04:59','2026-04-01 07:23:59'),(35,5,'Navy',1,129000.00,'IDR',28,0,'MTG-KALA-NAVY',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(36,6,'S / White',1,219000.00,'IDR',38,0,'MTG-DEWI-WHI-S',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(37,6,'S / Cream',1,219000.00,'IDR',46,0,'MTG-DEWI-CRE-S',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(38,6,'M / White',1,219000.00,'IDR',14,0,'MTG-DEWI-WHI-M',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(39,6,'M / Cream',1,219000.00,'IDR',45,0,'MTG-DEWI-CRE-M',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(40,6,'L / White',1,219000.00,'IDR',14,0,'MTG-DEWI-WHI-L',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(41,6,'L / Cream',1,219000.00,'IDR',19,0,'MTG-DEWI-CRE-L',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(42,6,'XL / White',1,219000.00,'IDR',49,0,'MTG-DEWI-WHI-XL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(43,6,'XL / Cream',1,219000.00,'IDR',28,0,'MTG-DEWI-CRE-XL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(44,7,'M / Black',1,389000.00,'IDR',50,2,'MTG-HANOMAN-BLA-M',NULL,'2026-03-30 08:04:59','2026-04-01 07:23:59'),(45,7,'M / Grey',1,389000.00,'IDR',10,0,'MTG-HANOMAN-GRE-M',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(46,7,'L / Black',1,389000.00,'IDR',9,0,'MTG-HANOMAN-BLA-L',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(47,7,'L / Grey',1,389000.00,'IDR',23,0,'MTG-HANOMAN-GRE-L',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(48,7,'XL / Black',1,389000.00,'IDR',18,0,'MTG-HANOMAN-BLA-XL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(49,7,'XL / Grey',1,389000.00,'IDR',29,0,'MTG-HANOMAN-GRE-XL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(50,7,'XXL / Black',1,389000.00,'IDR',37,0,'MTG-HANOMAN-BLA-XXL',NULL,'2026-03-30 08:04:59','2026-03-30 08:04:59'),(51,7,'XXL / Grey',1,389000.00,'IDR',39,0,'MTG-HANOMAN-GRE-XXL',NULL,'2026-03-30 08:04:59','2026-04-09 08:41:02'),(52,8,'M / Black',1,199000.00,'IDR',9,3,'MTG-JATAYU-BLA-M','[]','2026-03-30 08:04:59','2026-04-09 01:41:02'),(53,8,'M / Charcoal',1,199000.00,'IDR',50,0,'MTG-JATAYU-CHA-M','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(54,8,'L / Black',1,199000.00,'IDR',42,0,'MTG-JATAYU-BLA-L','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(55,8,'L / Charcoal',1,199000.00,'IDR',50,0,'MTG-JATAYU-CHA-L','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(56,8,'XL / Black',1,199000.00,'IDR',42,0,'MTG-JATAYU-BLA-XL','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(57,8,'XL / Charcoal',1,199000.00,'IDR',32,0,'MTG-JATAYU-CHA-XL','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(58,8,'XXL / Black',1,199000.00,'IDR',23,0,'MTG-JATAYU-BLA-XXL','[]','2026-03-30 08:04:59','2026-04-01 07:18:49'),(59,8,'XXL / Charcoal',1,199000.00,'IDR',7,0,'MTG-JATAYU-CHA-XXL','[]','2026-03-30 08:04:59','2026-04-08 05:45:38');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `handle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_html` text COLLATE utf8mb4_unicode_ci,
  `available_for_sale` tinyint(1) NOT NULL DEFAULT '1',
  `featured_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_handle_unique` (`handle`),
  KEY `products_is_hidden_index` (`is_hidden`),
  KEY `products_created_at_index` (`created_at`),
  KEY `products_visible_created_index` (`is_hidden`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Garuda Oversize Tee','garuda-oversize-tee','Kaos oversize premium dengan desain Garuda yang ikonik. Dibuat dari bahan cotton combed 30s yang lembut dan nyaman.','<p>Kaos oversize premium dengan desain <strong>Garuda</strong> yang ikonik. Dibuat dari bahan cotton combed 30s yang lembut dan nyaman.</p><ul><li>Cotton Combed 30s</li><li>Oversize Fit</li><li>Sablon DTF Premium</li></ul>',1,NULL,NULL,NULL,'[\"t-shirt\", \"oversize\", \"garuda\", \"mythology\"]',0,'2026-03-30 08:04:58','2026-03-30 08:05:01',NULL),(2,'Naga Batak Heritage Hoodie','naga-batak-heritage-hoodie','Hoodie tebal dengan desain Naga Batak yang megah. Bahan fleece premium anti-pilling.','<p>Hoodie tebal dengan desain <strong>Naga Batak</strong> yang megah. Bahan fleece premium anti-pilling.</p>',1,NULL,NULL,NULL,'[\"hoodie\", \"naga\", \"batak\", \"heritage\"]',0,'2026-03-30 08:04:58','2026-03-30 08:05:01',NULL),(3,'Barong Bali Graphic Tee','barong-bali-graphic-tee','Desain eksklusif terinspirasi dari Barong Bali, simbol kebaikan dalam mitologi Bali.','<p>Desain eksklusif terinspirasi dari <strong>Barong Bali</strong>, simbol kebaikan dalam mitologi Bali.</p>',1,NULL,NULL,NULL,'[\"t-shirt\", \"barong\", \"bali\", \"mythology\"]',0,'2026-03-30 08:04:58','2026-03-30 08:05:01',NULL),(4,'Rangda Dark Bomber Jacket','rangda-dark-bomber-jacket','Bomber jacket dengan bordir Rangda yang detail. Material parasut premium, water-resistant.','<p>Bomber jacket dengan bordir <strong>Rangda</strong> yang detail. Material parasut premium, water-resistant.</p>',1,NULL,NULL,NULL,'[\"jacket\", \"rangda\", \"bomber\", \"premium\"]',0,'2026-03-30 08:04:58','2026-03-30 08:05:01',NULL),(5,'Kala Makara Snapback Cap','kala-makara-snapback-cap','Topi snapback dengan bordir Kala Makara. Adjustable strap, one size fits all.','<p>Topi snapback dengan bordir <strong>Kala Makara</strong>. Adjustable strap, one size fits all.</p>',1,NULL,NULL,NULL,'[\"cap\", \"accessories\", \"kala-makara\"]',0,'2026-03-30 08:04:59','2026-03-30 08:05:01',NULL),(6,'Dewi Sri Embroidered Tee','dewi-sri-embroidered-tee','Kaos dengan bordir halus Dewi Sri, dewi padi dan kesuburan dalam mitologi Jawa.','<p>Kaos dengan bordir halus <strong>Dewi Sri</strong>, dewi padi dan kesuburan dalam mitologi Jawa.</p>',1,NULL,NULL,NULL,'[\"t-shirt\", \"embroidered\", \"dewi-sri\", \"mythology\"]',0,'2026-03-30 08:04:59','2026-03-30 08:05:01',NULL),(7,'Hanoman Warrior Zip Hoodie','hanoman-warrior-zip-hoodie','Zip hoodie premium dengan print Hanoman sang ksatria kera. Full-zip dengan kangaroo pocket.','<p>Zip hoodie premium dengan print <strong>Hanoman</strong> sang ksatria kera. Full-zip dengan kangaroo pocket.</p>',1,NULL,NULL,NULL,'[\"hoodie\", \"hanoman\", \"zip\", \"premium\"]',0,'2026-03-30 08:04:59','2026-03-30 08:05:01',NULL),(8,'Jatayu Legend Oversized Tee','jatayu-legend-oversized-tee','Kaos oversize dengan ilustrasi epik Jatayu, burung mistis dalam epos Ramayana.','<p>Kaos oversize dengan ilustrasi epik <strong>Jatayu</strong>, burung mistis dalam epos Ramayana.</p>',1,'products/RW44atS1jLf9XYhBq2iYTY0KigFnlWMPqOwd2Ih4.png',NULL,NULL,'[\"t-shirt\", \"oversize\", \"jatayu\", \"legend\"]',0,'2026-03-30 08:04:59','2026-04-01 07:18:48',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `shipping_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipping_addresses_order_id_foreign` (`order_id`),
  CONSTRAINT `shipping_addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `shipping_addresses` WRITE;
/*!40000 ALTER TABLE `shipping_addresses` DISABLE KEYS */;
INSERT INTO `shipping_addresses` VALUES (1,1,'Rifqy Customer','08123456789','Jl. Malioboro No. 123','Yogyakarta','DI Yogyakarta','55271','2026-03-30 08:04:59','2026-03-30 08:04:59'),(2,2,'Anisa Rahmawati','08567891234','Jl. Kaliurang KM 12 No. 45','Sleman','DI Yogyakarta','55581','2026-03-30 08:04:59','2026-03-30 08:04:59'),(3,3,'Budi Santoso','08198765432','Jl. Sudirman No. 88','Jakarta','DKI Jakarta','10210','2026-03-30 08:05:00','2026-03-30 08:05:00'),(4,4,'Denny Kurniawan','08211234567','Jl. Diponegoro No. 32','Bandung','Jawa Barat','40115','2026-03-30 08:05:00','2026-03-30 08:05:00'),(5,5,'Siti Aminah','08534567890','Jl. Pemuda No. 15','Surabaya','Jawa Timur','60271','2026-03-30 08:05:00','2026-03-30 08:05:00'),(6,6,'Rifqy Customer','08123456789','Jl. Malioboro No. 123','Yogyakarta','DI Yogyakarta','55271','2026-03-30 08:05:00','2026-03-30 08:05:00'),(7,7,'Acong iky','05464646346','jalan subagja','wesel','jatim','7373727','2026-04-01 07:24:00','2026-04-01 07:24:00'),(8,8,'Acong ewn','08554346464646','jejsjsj','jsjsj','nsjsjsj','7373727','2026-04-01 07:36:51','2026-04-01 07:36:51'),(9,9,'aku Teat','+6285773818846','Bsbab','Hsha','Hsha','3727','2026-04-01 07:43:18','2026-04-01 07:43:18'),(10,10,'Rifqy','085773818846','VIlla Wanasari, Cibitung, Wanasari','Bekasi','Jawa Barat','17520','2026-04-02 11:58:06','2026-04-02 11:58:06'),(12,12,'Rifqy','085773818846','VIlla Wanasari, Cibitung, Wanasari','Bekasi','Jawa Barat','17520','2026-04-09 08:40:38','2026-04-09 08:40:38');
/*!40000 ALTER TABLE `shipping_addresses` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_name','Mitologi Clothing','text','Nama Situs','general','2026-03-30 08:04:55','2026-03-30 08:04:55'),(2,'site_tagline','Vendor Konveksi Terpercaya','text','Tagline','general','2026-03-30 08:04:55','2026-03-30 08:04:55'),(3,'site_description','Vendor clothing terpercaya asal Indramayu, memproduksi kaos, kemeja, jaket, dan merchandise lainnya sejak 2022.','textarea','Deskripsi Situs','general','2026-03-30 08:04:55','2026-03-30 08:04:55'),(4,'site_logo','settings/NAoLeBquNBJmT5Xevd49w4i6qhh44lV0YpmNcRUR.png','image','Logo Situs','general','2026-03-30 08:04:55','2026-04-01 11:46:25'),(5,'site_favicon',NULL,'image','Favicon','general','2026-03-30 08:04:55','2026-03-30 08:04:55'),(6,'company_founded_year','2022','text','Tahun Berdiri','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(7,'about_description_1','Mitologi Clothing adalah vendor clothing asal Indramayu yang berdiri sejak 2022 dan bergerak dalam produksi berbagai jenis seragam dan merchandise untuk organisasi maupun instansi.','textarea','Deskripsi Tentang Kami 1','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(8,'about_description_2','Dengan dukungan tim berpengalaman, peralatan berstandar operasional, serta komitmen pada kerja keras, kerja cerdas, dan evaluasi berkelanjutan, Mitologi terus bertransformasi menjadi entitas yang profesional dan terpercaya.','textarea','Deskripsi Tentang Kami 2','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(9,'about_short_history','Pada tahun 2015 terinspirasi oleh seorang teman yang sebagai afiliator konveksi yang sukses, terbentuklah RROArt. Lalu tanggal 20 November 2022 memulai bisnis Mitologi Clothing dari maklon sampai sekarang buka usaha sendiri.','textarea','Sejarah Singkat','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(10,'about_logo_meaning','Bentuk biru dari shape warna biru mengartikan huruf M yaitu awal kata dari Mitologi.','textarea','Arti Logo','about','2026-03-30 08:04:55','2026-03-30 08:09:32'),(11,'about_image','settings/w54aZjxoaLU28oDYJMCjnRAFKYKSsJRzjR8k4LHf.png','image','Gambar Tentang Kami','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(12,'founder_name','Rizky Rafalda Oktaviandri','text','Nama Founder','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(13,'founder_role','Founder Mitologi Clothing','text','Peran / Jabatan','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(14,'founder_story','Perjalanan kami dimulai dengan sebuah visi sederhana: menciptakan pakaian berkualitas yang tidak hanya nyaman dipakai, tetapi juga membawa nilai kebanggaan bagi setiap pemakainya. Kesuksesan awal kami memacu kami untuk membangun identitas vendor yang lebih besar dan profesional. Dengan komitmen, dedikasi, dan dukungan tim yang solid, Mitologi Clothing lahir untuk memberikan karya terbaik kepada pelanggan di seluruh Indonesia.','textarea','Kisah Pendiri','about','2026-03-30 08:04:55','2026-03-30 08:04:55'),(15,'founder_photo','settings/juwan7tEAS3RqkawBC2Cdu9a9AW8BqkXFerbbAH4.png','image','Foto Founder','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(16,'vision_text','Menjadi Brand yang dikenal dengan nuansa budaya dengan kualitas & Mutu terbaik','textarea','Visi Perusahaan','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(17,'mission_text','Meningkatkan mutu dan standar pekerjaan\r\nMenerapkan quality control yang tinggi\r\nMemberikan pelayanan yang cepat dan mudah\r\nMelakukan perniagaan sesuai aturan bernegara','textarea','Misi Perusahaan','about','2026-03-30 08:04:55','2026-03-30 08:23:54'),(18,'values_text','1. Kejujuran\r\n2. Produk yang berkualitas\r\n3. Ketepatan waktu\r\n4. Keberkahan usaha\r\n5. Mengangkat budaya dan pariwisata di Indonesia','textarea','Nilai-Nilai Perusahaan','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(19,'company_values_data','[{\"title\":\"Kejujuran\",\"desc\":\"Menjaga integritas dalam setiap aspek bisnis\",\"icon\":\"heroicon-o-shield-check\"},{\"title\":\"Produk Berkualitas\",\"desc\":\"Komitmen pada standar kualitas tinggi\",\"icon\":\"heroicon-o-star\"},{\"title\":\"Ketepatan Waktu\",\"desc\":\"Menghormati deadline dan komitmen\",\"icon\":\"heroicon-o-clock\"},{\"title\":\"Keberkahan Usaha\",\"desc\":\"Berkah dalam setiap transaksi\",\"icon\":\"heroicon-o-heart\"},{\"title\":\"Budaya Indonesia\",\"desc\":\"Mengangkat budaya dan pariwisata Indonesia\",\"icon\":\"heroicon-o-globe-alt\"}]','json','Data Nilai Perusahaan','vision_mission','2026-03-30 08:04:55','2026-03-30 08:04:55'),(20,'legal_company_name','Mitologi Clothing','text','Nama Perusahaan','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(21,'legal_address','Desa Leuwigede Kec. Widasari Kab. Indramayu 45271','text','Alamat Legal','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(22,'legal_business_field','Vendor Konveksi / Broker / Brand','text','Bidang Usaha','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(23,'legal_npwp','99.149.537.5-437.000','text','NPWP','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(24,'legal_nib','0910240041097','text','NIB','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(25,'legal_nmid','ID1024309638855','text','NMID','about','2026-03-30 08:04:55','2026-03-30 08:17:03'),(26,'services_data','[{\"title\":\"Produksi Kaos\",\"desc\":\"Memproduksi berbagai macam bahan jenis kaos mulai dari Cotton Combed, Polyester hingga Hyget dan menyediakan pola mulai dari croptop, reguler, oversize hingga wangki.\",\"image\":\"settings\\/services\\/oS8pzLsQ1xSwDPKMS288et5CpT8UFRVS1BLwR08b.webp\",\"materials\":\"Cotton Combed 30s, 24s, Polyester, Hyget\",\"keunggulan\":\"Jahitan Rapi, Sablon Awet, Bahan Nyaman\",\"min_order\":\"12 pcs (bisa 1 pcs untuk satuan)\"},{\"title\":\"Produksi Jaket\",\"desc\":\"Memproduksi berbagai macam bahan jenis jaket mulai dari bahan PE Fleece, Cotton Fleece, Taslan, Scuba hingga Tracktop dan menyediakan pola Hoodie, Crewneck, Croptop, Varsity.\",\"image\":\"settings\\/services\\/4hxFqiy6NwcKHEktj1yLuxd2o6w9Sl25i9IRHh21.jpg\",\"materials\":\"PE Fleece, Cotton Fleece, Taslan, Scuba, Parasut\",\"keunggulan\":\"Bahan Hangat, Resleting YKK, Tahan Angin\",\"min_order\":\"12 pcs\"},{\"title\":\"Produksi Kemeja\",\"desc\":\"Memproduksi berbagai macam bahan jenis kemeja mulai dari Nagata Drill, American Drill hingga Japan Drill dan menyediakan pola mulai dari kemeja, semi jaket, hingga rompi.\",\"image\":\"settings\\/services\\/oSXNiTl5V0JA5BrWn6JVQHP5ruiL2U4Yj2e8nCsF.webp\",\"materials\":\"Nagata Drill, American Drill, Japan Drill\",\"keunggulan\":\"Bahan Kuat, Pola Presisi, Nyaman Dipakai\",\"min_order\":\"12 pcs\"},{\"title\":\"Produksi Hoodie & Sweater\",\"desc\":\"Memproduksi hoodie dan sweater dengan bahan Cotton Fleece premium berbagai gramasi. Tersedia model Hoodie, Crewneck, Zip Hoodie, dan Oversized.\",\"image\":\"settings\\/services\\/8HuWOHrfnQRcammd0oG5YQCpHHUZLeBumIzA6sKl.jpg\",\"materials\":\"Cotton Fleece 240gsm, 280gsm, 330gsm\",\"keunggulan\":\"Bahan Tebal, Hangat, Jahitan Rapi\",\"min_order\":\"12 pcs\"},{\"title\":\"Produksi Jersey\",\"desc\":\"Memproduksi jersey olahraga dengan teknik sublimation printing full color. Cocok untuk jersey futsal, basket, sepak bola, dan jersey komunitas.\",\"image\":\"settings\\/services\\/HE6pJ0jzkCAQ55UZxf0tQZ8SbQAaf60MtwPMg7op.jpg\",\"materials\":\"Dry-fit Benzema, MTis, Pique, Brazil\",\"keunggulan\":\"Full Print, Warna Tajam, Bahan Adem\",\"min_order\":\"12 pcs\"},{\"title\":\"Produksi Seragam (PDH\\/PDL)\",\"desc\":\"Memproduksi seragam kerja, PDH, PDL, dan wearpack untuk instansi, perusahaan, atau organisasi. Include bordir logo dan nama.\",\"image\":\"settings\\/services\\/7bVC8OQ0PyidUpEwwonegUet0yHz4UC7HW1svFxU.jpg\",\"materials\":\"American Drill, Nagata Drill, Japan Drill\",\"keunggulan\":\"Bahan Kuat, Bordir Rapi, Fit Sempurna\",\"min_order\":\"12 pcs\"}]','json','Data Layanan','services','2026-03-30 08:04:55','2026-04-07 16:14:49'),(27,'guarantee_1_title','GARANSI TEPAT WAKTU','text','Judul Garansi 1','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(28,'guarantee_1_desc','Dapatkan voucher cashback 1 kaos gratis apabila pesananmu melebihi tanggal deadline.','textarea','Deskripsi Garansi 1','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(29,'guarantee_2_title','GARANSI KUALITAS LAYANAN','text','Judul Garansi 2','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(30,'guarantee_2_desc','Dapatkan voucher cashback 1 kaos gratis apabila tidak dilayani dalam 1x24 jam.','textarea','Deskripsi Garansi 2','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(31,'guarantee_3_title','GARANSI BEBAS PENGEMBALIAN','text','Judul Garansi 3','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(32,'guarantee_3_desc','Barang tidak sesuai spesifikasi? Produk reject? KLAIM GARANSI BERLAKU 7 HARI.','textarea','Deskripsi Garansi 3','guarantee','2026-03-30 08:04:55','2026-03-30 08:04:55'),(33,'guarantees_data','[{\"title\":\"Pengerjaan Tepat Waktu\",\"description\":\"Tim kami berkomitmen menyelesaikan pesanan sesuai deadline yang telah disepakati bersama.\",\"icon\":\"heroicon-o-clock\"},{\"title\":\"Bahan Premium Berkualitas\",\"description\":\"Kami hanya menggunakan bahan-bahan pilihan berstandar tinggi untuk setiap produksi.\",\"icon\":\"heroicon-o-star\"},{\"title\":\"Desain Custom Bebas\",\"description\":\"Tidak ada batasan kreativitas \\u2014 wujudkan desain apapun yang Anda inginkan bersama tim kami.\",\"icon\":\"heroicon-o-pencil-square\"},{\"title\":\"Harga Transparan & Kompetitif\",\"description\":\"Harga resmi tertera jelas tanpa biaya tersembunyi, cocok untuk komunitas, organisasi, dan UMKM.\",\"icon\":\"heroicon-o-currency-dollar\"},{\"title\":\"Garansi Pengembalian 7 Hari\",\"description\":\"Jika produk tidak sesuai spesifikasi, kami siap melakukan perbaikan atau penggantian produk.\",\"icon\":\"heroicon-o-shield-check\"},{\"title\":\"Konsultasi Desain Gratis\",\"description\":\"Tim desainer kami siap membantu dari tahap konsep sampai hasil akhir tanpa biaya tambahan.\",\"icon\":\"heroicon-o-chat-bubble-left-right\"}]','json','Mengapa Memilih Kami (Data)','beranda','2026-03-30 08:04:55','2026-03-30 08:04:55'),(34,'garansi_bonus_data','[{\"title\":\"Garansi Tepat Waktu\",\"description\":\"Dapatkan voucher cashback 1 kaos gratis apabila pesananmu melebihi tanggal deadline yang telah disepakati.\",\"icon\":\"heroicon-o-clock\"},{\"title\":\"Garansi Kualitas\",\"description\":\"Produk cacat produksi atau tidak sesuai spesifikasi? Klaim garansi bebas pengembalian berlaku 7 hari setelah terima barang.\",\"icon\":\"heroicon-o-shield-check\"},{\"title\":\"Bonus Order > 100 pcs\",\"description\":\"Order di atas 100 pcs: GRATIS 1 pcs kaos sablon + free sticker pack eksklusif + prioritas antrean produksi.\",\"icon\":\"heroicon-o-gift\"}]','json','Garansi & Bonus (Data)','beranda','2026-03-30 08:04:55','2026-03-30 08:04:55'),(35,'cta_title','Siap Memesan Seragam Impian Anda?','text','Judul CTA','cta','2026-03-30 08:04:55','2026-03-30 08:04:55'),(36,'cta_subtitle','Konsultasikan kebutuhan seragam Anda dengan tim ahli kami sekarang juga.','textarea','Subjudul CTA','cta','2026-03-30 08:04:55','2026-03-30 08:04:55'),(37,'cta_button_text','Hubungi Kami via WhatsApp','text','Teks Tombol CTA','cta','2026-03-30 08:04:55','2026-03-30 08:04:55'),(38,'cta_button_link','https://wa.me/6281322170902','text','Link Tombol CTA','cta','2026-03-30 08:04:55','2026-03-30 08:04:55'),(39,'contact_email','mitologiclothing@gmail.com','text','Email Kontak','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(40,'contact_phone','+62 813-2217-0902','text','Nomor Telepon','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(41,'contact_whatsapp','+62 813-2217-0902','text','Nomor WhatsApp','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(42,'contact_address','Desa Leuwigede Kec. Widasari Kab. Indramayu 45271','textarea','Alamat Lengkap','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(43,'contact_maps_embed','https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=mitologi clothing&amp;t=k&amp;z=17&amp;ie=UTF8&amp;iwloc=B&amp;output=embed','textarea','Google Maps Embed','contact','2026-03-30 08:04:55','2026-04-01 13:03:30'),(44,'operating_hours_weekday_label','Senin - Sabtu','text','Label Hari Kerja','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(45,'operating_hours_weekday','08.00 - 16.00 WIB','text','Jam Kerja','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(46,'operating_hours_weekend_label','Minggu','text','Label Hari Libur','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(47,'operating_hours_weekend','Tutup (Online Chat Only)','text','Jam Libur','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(48,'social_instagram','https://instagram.com/mitologiclothing','text','Instagram URL','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(49,'social_instagram_enabled','1','boolean','Tampilkan Instagram','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(50,'social_tiktok','https://tiktok.com/@mitologiclothing','text','TikTok URL','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(51,'social_tiktok_enabled','1','boolean','Tampilkan TikTok','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(52,'social_facebook','https://facebook.com/mitologiclothing','text','Facebook URL','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(53,'social_facebook_enabled','1','boolean','Tampilkan Facebook','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(54,'social_shopee','https://shopee.co.id/rizky_rafalda_oktaviandri','text','Shopee URL','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(55,'social_shopee_enabled','1','boolean','Tampilkan Shopee','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(56,'social_twitter',NULL,'text','Twitter/X URL','contact','2026-03-30 08:04:55','2026-04-01 12:48:23'),(57,'social_twitter_enabled','0','boolean','Tampilkan Twitter/X','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(58,'whatsapp_number','6281322170902','text','Nomor WhatsApp (Format: 628...)','contact','2026-03-30 08:04:55','2026-03-30 08:04:55'),(59,'payment_methods_data','[{\"bank\":\"Bank Rakyat Indonesia (BRI)\",\"account_number\":\"0165-0106-9217-507\",\"account_name\":\"RIZKY RAFALDA OKTAVIANDRI\"},{\"bank\":\"Bank BJB\",\"account_number\":\"0124211182100\",\"account_name\":\"RIZKY RAFALDA OKTAVIANDRI\"},{\"bank\":\"OVO\",\"account_number\":\"0813-2217-0902\",\"account_name\":\"RIZKY RAFALDA OKTAVIANDRI\"},{\"bank\":\"ShopeePay\",\"account_number\":\"0813-2217-0902\",\"account_name\":\"RIZKY RAFALDA OKTAVIANDRI\"},{\"bank\":\"DANA\",\"account_number\":\"0813-2217-0902\",\"account_name\":\"RIZKY RAFALDA OKTAVIANDRI\"},{\"bank\":\"QRIS\",\"account_number\":\"Tersedia\",\"account_name\":\"Tersedia via Chat\"}]','json','Data Metode Pembayaran','payment','2026-03-30 08:04:55','2026-03-30 08:04:55'),(60,'pricing_plastisol_data','[{\"title\":\"1 Sisi, 2 Warna\",\"short\":\"60K\",\"long\":\"70K\",\"popular\":true},{\"title\":\"1 Sisi, 3-4 Warna\",\"short\":\"65K\",\"long\":\"75K\",\"popular\":false},{\"title\":\"2 Sisi, 2 Warna\",\"short\":\"70K\",\"long\":\"80K\",\"popular\":false},{\"title\":\"2 Sisi, 3-4 Warna\",\"short\":\"75K\",\"long\":\"85K\",\"popular\":false}]','json','Data Harga Sablon Plastisol','pricing','2026-03-30 08:04:55','2026-03-30 08:04:55'),(61,'pricing_kemeja_data','[{\"title\":\"Kemeja PDH \\/ PDL (American Drill)\",\"price\":\"120K\"},{\"title\":\"Kemeja PDH \\/ PDL (Nagata Drill)\",\"price\":\"130K\"},{\"title\":\"Polo Shirt (Lacoste PE)\",\"price\":\"80K\"},{\"title\":\"Polo Shirt (Lacoste CVC)\",\"price\":\"90K\"},{\"title\":\"Wearpack \\/ PDL \\/ Korsa\",\"price\":\"140K\"}]','json','Data Harga Kemeja & Polo','pricing','2026-03-30 08:04:55','2026-03-30 08:04:55'),(62,'pricing_merchandise_data','[{\"title\":\"Topi Custom (Min. 20 pcs)\",\"price\":\"20K\"},{\"title\":\"Tas Canvas (Min. 50 pcs)\",\"price\":\"40K\"},{\"title\":\"Waistbag (Min. 50 pcs)\",\"price\":\"55K\"},{\"title\":\"Lanyard (Min. 50 pcs)\",\"price\":\"15K\"}]','json','Data Harga Merchandise','pricing','2026-03-30 08:04:55','2026-03-30 08:04:55'),(63,'pricing_addons_data','[{\"name\":\"Sablon DTF\",\"price\":\"+ Rp 15.000\\/pcs\"},{\"name\":\"Bordir Komputer\",\"price\":\"+ Rp 20.000\\/pcs\"},{\"name\":\"Label Woven\",\"price\":\"+ Rp 5.000\\/pcs\"},{\"name\":\"Packaging Custom\",\"price\":\"+ Rp 10.000\\/pcs\"}]','json','Data Add-ons','pricing','2026-03-30 08:04:55','2026-03-30 08:04:55'),(64,'moq_data','[{\"product\":\"Kaos Satuan (+Sablon)\",\"moq\":\"1 pcs\",\"materials\":\"PE, Cotton\"},{\"product\":\"Hoodie\\/Crewneck Satuan\",\"moq\":\"1 pcs\",\"materials\":\"PE Fleece, Fleece Cotton 280gsm\\/330gsm\"},{\"product\":\"Jersey Printing\",\"moq\":\"12 pcs\",\"materials\":\"Dry-fit, Emboss\"},{\"product\":\"Kemeja \\/ PDH \\/ Jaket\",\"moq\":\"12 pcs\",\"materials\":\"Drill, Fleece, Parasut\"},{\"product\":\"Topi Custom\",\"moq\":\"20 pcs\",\"materials\":\"Rafel, Drill\"},{\"product\":\"Merchandise (Tas, Lanyard)\",\"moq\":\"50 pcs\",\"materials\":\"Canvas, Lanyard Tissue\"}]','json','Minimum Order Qty','pricing','2026-03-30 08:04:55','2026-03-30 08:04:55'),(65,'seo_meta_title','Mitologi Clothing - Vendor Konveksi Indramayu','text','Meta Title','general','2026-03-30 08:04:55','2026-04-01 11:46:25'),(66,'seo_meta_description','Vendor konveksi terpercaya di Indramayu. Melayani pembuatan kaos, kemeja, jaket, jersey, dan merchandise dengan kualitas terbaik.','textarea','Meta Description','general','2026-03-30 08:04:55','2026-04-01 11:46:25'),(67,'seo_keywords','konveksi indramayu, vendor kaos, pembuatan seragam, konveksi murah, sablon kaos, vendor hoodie, pembuatan jersey','textarea','SEO Keywords','seo','2026-03-30 08:04:56','2026-03-30 08:04:56'),(68,'shipping_partners','[\"JNE\",\"J&T Express\",\"SiCepat\",\"AnterAja\",\"Ninja Express\"]','json','Ekspedisi Pengiriman','shipping','2026-03-30 08:04:56','2026-03-30 08:04:56'),(69,'shipping_coverage','Seluruh Indonesia','text','Jangkauan Pengiriman','shipping','2026-03-30 08:04:56','2026-03-30 08:04:56'),(70,'shipping_estimate_java','2-5 hari kerja','text','Estimasi Jawa','shipping','2026-03-30 08:04:56','2026-03-30 08:04:56'),(71,'shipping_estimate_outer','5-10 hari kerja','text','Estimasi Luar Jawa','shipping','2026-03-30 08:04:56','2026-03-30 08:04:56'),(72,'about_logo_meaning_detailed','[{\"letter\":\"M\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf M yaitu awal kata dari Mitologi.\"},{\"letter\":\"i\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf i yaitu huruf kedua dari Mitologi.\"},{\"letter\":\"t\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf t yaitu huruf ketiga dari Mitologi.\"},{\"letter\":\"o\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf o yaitu huruf keempat dari Mitologi.\"},{\"letter\":\"L\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf L dan L mirror yaitu huruf kelima dari Mitologi.\"},{\"letter\":\"o\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf o yaitu huruf keenam dari Mitologi.\"},{\"letter\":\"g\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf g dan g mirror yaitu huruf ketujuh dari Mitologi.\"},{\"letter\":\"i\",\"description\":\"Bentuk biru dari shape warna biru mengartikan huruf i yaitu huruf kedelapan dari Mitologi.\"}]','json','Arti Logo Detail per Huruf','about','2026-03-30 08:09:32','2026-03-30 08:09:32'),(73,'about_headline',NULL,'text',NULL,'about','2026-03-30 08:17:03','2026-03-30 08:17:03'),(74,'seo_og_image','settings/NgFLPIX1h1YkBeYnhmd86yGDZSoEuhMO1KZmwOZH.png','image',NULL,'general','2026-04-01 11:46:25','2026-04-01 11:46:25');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `team_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `level` tinyint unsigned NOT NULL DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_members_parent_id_foreign` (`parent_id`),
  CONSTRAINT `team_members_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `team_members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `team_members` WRITE;
/*!40000 ALTER TABLE `team_members` DISABLE KEYS */;
INSERT INTO `team_members` VALUES (1,'Rizky Rafalda Oktaviandri','Founder Mitologi Clothing','team-members/DEWZnn8U0VjYWTvj5IxE71p3Zz6X0JfWmGAmJ6wm.png',NULL,0,0,1,'2026-03-30 08:05:00','2026-03-30 08:16:01'),(2,'Aisatuzzahro Fuadi','Sewing','team-members/qdXSFCLAq1RLUn3CTrzGx02FOfSAz6EGZYVdWaWG.png',1,1,0,1,'2026-03-30 08:05:00','2026-04-01 11:57:21'),(3,'Irpan Faddil','Freelance Designer Grafis','team-members/b66YHwmUGJTbqec3PkeOWZRIizTGp1t7oTDaoc6Y.png',1,1,1,1,'2026-03-30 08:05:00','2026-04-01 11:57:54'),(4,'Sanu Senja','Freelance Pointilist','team-members/ZE1T2WH9xbu29qkjKP6vFkGfSQMwMoI8LgSbaSLq.png',1,1,2,1,'2026-03-30 08:05:00','2026-04-01 11:58:22'),(5,'Rizky Rafalda','Produksi Sablon','team-members/nPwpiN3RtAfkRzMJGnrsgieaAduLR36RRcPIRpm6.png',1,1,3,1,'2026-03-30 08:05:00','2026-04-01 11:58:42'),(6,'Nino','Cutting Tshirt','team-members/1EF0QusvWqn6N96nqKxZKY5fwiFKYCf7v99FURNG.png',2,2,0,1,'2026-03-30 08:05:00','2026-04-01 11:59:00'),(7,'Honobuka Studio','Spot Color','team-members/IsmrKURWiTIxN5xGvdrt59qrRDUI4Uj0zU79ZUOx.png',3,2,0,1,'2026-03-30 08:05:00','2026-04-01 11:59:20'),(8,'Hadistorsi','Senior Data Analyst','team-members/c6gzZngLAI0YL0IcIXIZlLP0NKaYDBy8BbUeTlUr.png',4,2,0,1,'2026-03-30 08:05:00','2026-04-01 11:59:36'),(9,'Nino Sablon','Asistant Sablon','team-members/KR7Okvv6exMLLZ0tMGhuQAb2Kbf24tz2V79D0Mbz.png',5,2,0,1,'2026-03-30 08:05:00','2026-04-01 12:01:37'),(10,'Ralema Studio','Setting Jersey','team-members/eWtV85fj5Xg7Vis92ndX1DYmPbTyXaqKD3KtEPS8.png',7,3,0,1,'2026-03-30 08:05:00','2026-04-01 12:01:55');
/*!40000 ALTER TABLE `team_members` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `avatar_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Budi Santoso','Ketua Panitia Event Kampus','Hasil sablonnya awet banget, bahannya juga adem. Rekomen buat yang mau bikin kaos panitia! Pengerjaan cepat dan sesuai deadline acara kami.',5,'testimonials/2xeqX6DUvTdvagUGkh0fwGMWyghCtG1gqSdbz3Fv.jpg',1,'2026-03-30 08:05:01','2026-04-01 11:56:36'),(2,'Siti Aminah','Owner Brand Lokal \"Nusantara Wear\"','Jahitannya rapi, deadline tepat waktu. Puas banget kerjasama sama Mitologi Clothing. Sudah 3 kali repeat order dan hasilnya selalu konsisten.',5,NULL,1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(3,'Rizky Pratama','Mahasiswa UGM ΓÇö Angkatan 2023','Pesen PDH angkatan disini, hasilnya memuaskan. Adminnya ramah dan fast respon. Harganya juga paling murah dibanding vendor lain yang kami survey.',5,NULL,1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(4,'Anisa Rahmawati','Koordinator Komunitas Hiking Jogja','Jersey komunitas kami bikin di Mitologi Clothing. Bahannya dryfit berkualitas, sablon DTF tahan lama meski sering dicuci setelah kegiatan outdoor.',5,NULL,1,'2026-03-30 08:05:01','2026-03-30 08:05:01'),(5,'Denny Kurniawan','Manager HRD PT. Karya Mandiri','Seragam kantor karyawan kami pesan di sini. Hasilnya profesional, bahan American Drill tebal dan nyaman untuk kerja harian. Akan order lagi tahun depan.',4,NULL,1,'2026-03-30 08:05:01','2026-03-30 08:05:01');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `user_interactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_interactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `type` enum('view','cart','purchase','wishlist') COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_interactions_product_id_foreign` (`product_id`),
  KEY `user_interactions_user_id_product_id_type_index` (`user_id`,`product_id`,`type`),
  CONSTRAINT `user_interactions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_interactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `user_interactions` WRITE;
/*!40000 ALTER TABLE `user_interactions` DISABLE KEYS */;
INSERT INTO `user_interactions` VALUES (1,1,1,'cart',3,'2026-03-30 08:05:00'),(2,1,2,'view',1,'2026-03-30 08:05:00'),(3,1,4,'view',1,'2026-03-30 08:05:00'),(4,1,5,'purchase',5,'2026-03-30 08:05:00'),(5,1,6,'cart',3,'2026-03-30 08:05:00'),(6,1,7,'view',1,'2026-03-30 08:05:00'),(7,2,1,'cart',3,'2026-03-30 08:05:00'),(8,2,2,'view',1,'2026-03-30 08:05:00'),(9,2,3,'view',1,'2026-03-30 08:05:00'),(10,2,4,'purchase',5,'2026-03-30 08:05:00'),(11,2,5,'view',1,'2026-03-30 08:05:00'),(12,2,6,'view',1,'2026-03-30 08:05:00'),(13,2,7,'purchase',5,'2026-03-30 08:05:00'),(14,2,8,'cart',3,'2026-03-30 08:05:00'),(15,3,2,'purchase',5,'2026-03-30 08:05:00'),(16,3,3,'view',1,'2026-03-30 08:05:00'),(17,3,4,'purchase',5,'2026-03-30 08:05:00'),(18,3,5,'view',1,'2026-03-30 08:05:00'),(19,3,6,'view',1,'2026-03-30 08:05:00'),(20,3,7,'view',1,'2026-03-30 08:05:00'),(21,3,8,'view',1,'2026-03-30 08:05:00'),(22,4,2,'purchase',5,'2026-03-30 08:05:00'),(23,4,5,'cart',3,'2026-03-30 08:05:00'),(24,4,6,'view',1,'2026-03-30 08:05:00'),(25,4,8,'view',1,'2026-03-30 08:05:00'),(26,5,2,'view',1,'2026-03-30 08:05:00'),(27,5,3,'view',1,'2026-03-30 08:05:00'),(28,5,4,'view',1,'2026-03-30 08:05:00'),(29,5,5,'cart',3,'2026-03-30 08:05:00'),(30,6,1,'view',1,'2026-03-30 08:05:00'),(31,6,2,'view',1,'2026-03-30 08:05:00'),(32,6,3,'view',1,'2026-03-30 08:05:00'),(33,6,4,'cart',3,'2026-03-30 08:05:00'),(34,6,5,'view',1,'2026-03-30 08:05:00'),(35,6,6,'view',1,'2026-03-30 08:05:00'),(36,6,7,'purchase',5,'2026-03-30 08:05:00'),(37,6,8,'view',1,'2026-03-30 08:05:00'),(38,1,1,'view',1,'2026-03-30 08:05:01'),(39,1,3,'view',1,'2026-03-30 08:05:01'),(40,7,5,'cart',3,'2026-04-01 07:23:17'),(41,7,5,'purchase',5,'2026-04-01 07:23:59'),(42,7,7,'purchase',5,'2026-04-01 07:23:59'),(43,7,8,'purchase',5,'2026-04-01 07:36:51'),(44,8,8,'cart',3,'2026-04-01 07:42:51'),(45,8,8,'purchase',5,'2026-04-01 07:43:18'),(46,9,8,'wishlist',3,'2026-04-01 11:01:49'),(47,9,8,'cart',3,'2026-04-01 13:11:02'),(48,9,8,'purchase',5,'2026-04-02 11:58:06'),(49,9,7,'cart',3,'2026-04-02 17:58:48'),(54,9,7,'purchase',5,'2026-04-09 08:40:37');
/*!40000 ALTER TABLE `user_interactions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Mitologi','admin@mitologiclothing.com',NULL,'avatars/r9mho8sMXMHGqx7SsLJJ8WRUE8BnNYWBjJrrAvVw.png',NULL,NULL,NULL,NULL,'admin','2026-03-30 08:04:56','$2y$12$5hymTioRnbqL5eJAD3LpeOqFBtyQ4aSibauOKCpfhHRVvMBbzX2E2',NULL,'2026-03-30 08:04:56','2026-04-01 11:49:11',NULL),(2,'Rifqy Customer','customer@demo.com','08123456789',NULL,'Jl. Malioboro No. 123','Yogyakarta','DI Yogyakarta','55271','customer','2026-03-30 08:04:56','$2y$12$rBCYYEYkOdyw.qDrR70OtOMF.DLlC6vT0yxn.rsfl.f7MmVLqXiHy',NULL,'2026-03-30 08:04:56','2026-03-30 08:04:56',NULL),(3,'Anisa Rahmawati','anisa@demo.com','08567891234',NULL,'Jl. Kaliurang KM 12 No. 45','Sleman','DI Yogyakarta','55581','customer','2026-03-30 08:04:57','$2y$12$jRnQVIofRFtZTKw9z4UvAOyA2O6uAE75trC20Tkqo8xHhiWSKdz9G',NULL,'2026-03-30 08:04:57','2026-03-30 08:04:57',NULL),(4,'Budi Santoso','budi@demo.com','08198765432',NULL,'Jl. Sudirman No. 88','Jakarta','DKI Jakarta','10210','customer','2026-03-30 08:04:57','$2y$12$Mf8dscA6qyE/82BLdc4vjemQBhMliC3PHaB9nccgYkviqsDCQ7rLe',NULL,'2026-03-30 08:04:57','2026-03-30 08:04:57',NULL),(5,'Denny Kurniawan','denny@demo.com','08211234567',NULL,'Jl. Diponegoro No. 32','Bandung','Jawa Barat','40115','customer','2026-03-30 08:04:57','$2y$12$0Xu955of/MzS4sxbLtp3tuqwGBbEHFoSAP83QOpI.VE8d/wjuA4MO',NULL,'2026-03-30 08:04:57','2026-03-30 08:04:57',NULL),(6,'Siti Aminah','siti@demo.com','08534567890',NULL,'Jl. Pemuda No. 15','Surabaya','Jawa Timur','60271','customer','2026-03-30 08:04:57','$2y$12$1MKwtUSxqHkCFjw/WBIVlOl9I9Btv5rxA9tLK1cAroMTGEBCLiNOW',NULL,'2026-03-30 08:04:57','2026-03-30 08:04:57',NULL),(7,'Acong','acong@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,'customer',NULL,'$2y$12$K50F7IsghTWpF8cpBOIeYuYRNgPt/gw.W24LtT3dF0m40Djjd3nWu',NULL,'2026-04-01 07:21:56','2026-04-01 07:21:56',NULL),(8,'aku','aku@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,'customer',NULL,'$2y$12$igddgNHVETGtWSH9MA6Ck.AcTCo5.KtkbCaiXraXYWTxz5hvYyTzi',NULL,'2026-04-01 07:33:03','2026-04-01 07:33:03',NULL),(9,'kamu','kamu@gmail.com','085773818846','avatars/59Thhq4oPzTZKqoVewL2bAcE96KNfx1zH25VwzsW.png','Vila Wanasari','Bekasi','Jawa Barat',NULL,'customer',NULL,'$2y$12$XFm32wE.40QX7xn56EFr0e1KcbE8tv9MMVHDJRqeM3Qp6ySnCWw9m',NULL,'2026-04-01 07:33:54','2026-04-09 08:43:25',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `variant_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variant_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `variant_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `variant_options_variant_id_foreign` (`variant_id`),
  CONSTRAINT `variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `variant_options` WRITE;
/*!40000 ALTER TABLE `variant_options` DISABLE KEYS */;
INSERT INTO `variant_options` VALUES (1,1,'Size','S'),(2,1,'Color','Black'),(3,2,'Size','S'),(4,2,'Color','White'),(5,3,'Size','M'),(6,3,'Color','Black'),(7,4,'Size','M'),(8,4,'Color','White'),(9,5,'Size','L'),(10,5,'Color','Black'),(11,6,'Size','L'),(12,6,'Color','White'),(13,7,'Size','XL'),(14,7,'Color','Black'),(15,8,'Size','XL'),(16,8,'Color','White'),(17,9,'Size','XXL'),(18,9,'Color','Black'),(19,10,'Size','XXL'),(20,10,'Color','White'),(21,11,'Size','M'),(22,11,'Color','Navy'),(23,12,'Size','M'),(24,12,'Color','Black'),(25,13,'Size','M'),(26,13,'Color','Maroon'),(27,14,'Size','L'),(28,14,'Color','Navy'),(29,15,'Size','L'),(30,15,'Color','Black'),(31,16,'Size','L'),(32,16,'Color','Maroon'),(33,17,'Size','XL'),(34,17,'Color','Navy'),(35,18,'Size','XL'),(36,18,'Color','Black'),(37,19,'Size','XL'),(38,19,'Color','Maroon'),(39,20,'Size','XXL'),(40,20,'Color','Navy'),(41,21,'Size','XXL'),(42,21,'Color','Black'),(43,22,'Size','XXL'),(44,22,'Color','Maroon'),(45,23,'Size','S'),(46,23,'Color','Black'),(47,24,'Size','S'),(48,24,'Color','Dark Green'),(49,25,'Size','M'),(50,25,'Color','Black'),(51,26,'Size','M'),(52,26,'Color','Dark Green'),(53,27,'Size','L'),(54,27,'Color','Black'),(55,28,'Size','L'),(56,28,'Color','Dark Green'),(57,29,'Size','XL'),(58,29,'Color','Black'),(59,30,'Size','XL'),(60,30,'Color','Dark Green'),(61,31,'Size','M'),(62,32,'Size','L'),(63,33,'Size','XL'),(64,34,'Color','Black'),(65,35,'Color','Navy'),(66,36,'Size','S'),(67,36,'Color','White'),(68,37,'Size','S'),(69,37,'Color','Cream'),(70,38,'Size','M'),(71,38,'Color','White'),(72,39,'Size','M'),(73,39,'Color','Cream'),(74,40,'Size','L'),(75,40,'Color','White'),(76,41,'Size','L'),(77,41,'Color','Cream'),(78,42,'Size','XL'),(79,42,'Color','White'),(80,43,'Size','XL'),(81,43,'Color','Cream'),(82,44,'Size','M'),(83,44,'Color','Black'),(84,45,'Size','M'),(85,45,'Color','Grey'),(86,46,'Size','L'),(87,46,'Color','Black'),(88,47,'Size','L'),(89,47,'Color','Grey'),(90,48,'Size','XL'),(91,48,'Color','Black'),(92,49,'Size','XL'),(93,49,'Color','Grey'),(94,50,'Size','XXL'),(95,50,'Color','Black'),(96,51,'Size','XXL'),(97,51,'Color','Grey'),(114,52,'Ukuran','M'),(115,52,'Warna','Black'),(116,53,'Ukuran','M'),(117,53,'Warna','Charcoal'),(118,54,'Ukuran','L'),(119,54,'Warna','Black'),(120,55,'Ukuran','L'),(121,55,'Warna','Charcoal'),(122,56,'Ukuran','XL'),(123,56,'Warna','Black'),(124,57,'Ukuran','XL'),(125,57,'Warna','Charcoal'),(126,58,'Ukuran','XXL'),(127,58,'Warna','Black'),(128,59,'Ukuran','XXL'),(129,59,'Warna','Charcoal');
/*!40000 ALTER TABLE `variant_options` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhook_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `order_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `webhook_events_event_id_unique` (`event_id`),
  KEY `webhook_events_provider_order_id_index` (`provider`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `webhook_events` WRITE;
/*!40000 ALTER TABLE `webhook_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `webhook_events` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

