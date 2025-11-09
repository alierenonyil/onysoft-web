-- ============================================
-- WawaHouse E-Ticaret Sistemi
-- Database Schema
--
-- @package WawaHouse
-- @author Onysoft Veri Merkezi A.Ş.
-- @website www.onysoft.com.tr
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Veritabanı: wawahousesql
CREATE DATABASE IF NOT EXISTS `wawahousesql` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wawahousesql`;

-- ============================================
-- 1. Kategoriler Tablosu
-- ============================================
CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `ad` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sira` int(11) DEFAULT 0,
  `aktif` tinyint(1) DEFAULT 1,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `resim` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `aktif` (`aktif`),
  KEY `sira` (`sira`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. Ürünler Tablosu
-- ============================================
CREATE TABLE `urunler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `ozellikler` text DEFAULT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `indirimli_fiyat` decimal(10,2) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `cinsiyet` enum('kiz','erkek','unisex') DEFAULT 'unisex',
  `aktif` tinyint(1) DEFAULT 1,
  `one_cikan` tinyint(1) DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `goruntulenme` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `kategori_id` (`kategori_id`),
  KEY `aktif` (`aktif`),
  KEY `one_cikan` (`one_cikan`),
  KEY `created_at` (`created_at`),
  FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. Ürün Varyantları Tablosu
-- ============================================
CREATE TABLE `urun_varyantlari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urun_id` int(11) NOT NULL,
  `beden` varchar(50) NOT NULL,
  `renk` varchar(50) NOT NULL,
  `renk_kodu` varchar(7) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `ek_fiyat` decimal(10,2) DEFAULT 0.00,
  `sku` varchar(100) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `urun_id` (`urun_id`),
  KEY `stok` (`stok`),
  FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. Ürün Resimleri Tablosu
-- ============================================
CREATE TABLE `urun_resimleri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urun_id` int(11) NOT NULL,
  `resim` varchar(255) NOT NULL,
  `ana_resim` tinyint(1) DEFAULT 0,
  `sira` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `urun_id` (`urun_id`),
  KEY `ana_resim` (`ana_resim`),
  FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. Kullanıcılar Tablosu
-- ============================================
CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `rol` enum('admin','musteri') DEFAULT 'musteri',
  `aktif` tinyint(1) DEFAULT 1,
  `email_dogrulandi` tinyint(1) DEFAULT 0,
  `dogrulama_token` varchar(255) DEFAULT NULL,
  `sifre_sifirlama_token` varchar(255) DEFAULT NULL,
  `sifre_sifirlama_sure` datetime DEFAULT NULL,
  `son_giris` datetime DEFAULT NULL,
  `ip_adresi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `rol` (`rol`),
  KEY `aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. Adresler Tablosu
-- ============================================
CREATE TABLE `adresler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) NOT NULL,
  `ad` varchar(100) NOT NULL,
  `soyad` varchar(100) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `adres` text NOT NULL,
  `il` varchar(100) NOT NULL,
  `ilce` varchar(100) NOT NULL,
  `posta_kodu` varchar(10) DEFAULT NULL,
  `adres_tipi` enum('ev','is','diger') DEFAULT 'ev',
  `varsayilan` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. Siparişler Tablosu
-- ============================================
CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) NOT NULL,
  `siparis_no` varchar(50) NOT NULL,
  `toplam_tutar` decimal(10,2) NOT NULL,
  `ara_toplam` decimal(10,2) NOT NULL,
  `kdv_tutari` decimal(10,2) DEFAULT 0.00,
  `kargo_ucreti` decimal(10,2) DEFAULT 0.00,
  `kupon_indirim` decimal(10,2) DEFAULT 0.00,
  `kupon_kodu` varchar(50) DEFAULT NULL,
  `durum` enum('beklemede','onaylandi','hazirlaniyor','kargoda','teslim_edildi','iptal') DEFAULT 'beklemede',
  `odeme_yontemi` enum('kredi_karti','kapida_odeme','havale') NOT NULL,
  `odeme_durumu` enum('beklemede','odendi','iptal') DEFAULT 'beklemede',
  `kargo_takip_no` varchar(100) DEFAULT NULL,
  `kargo_firma` varchar(100) DEFAULT NULL,
  `teslimat_adresi` text NOT NULL,
  `fatura_adresi` text DEFAULT NULL,
  `notlar` text DEFAULT NULL,
  `ip_adresi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siparis_no` (`siparis_no`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `durum` (`durum`),
  KEY `created_at` (`created_at`),
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. Sipariş Detayları Tablosu
-- ============================================
CREATE TABLE `siparis_detaylari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siparis_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `varyant_id` int(11) DEFAULT NULL,
  `urun_ad` varchar(255) NOT NULL,
  `beden` varchar(50) DEFAULT NULL,
  `renk` varchar(50) DEFAULT NULL,
  `adet` int(11) NOT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `toplam` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siparis_id` (`siparis_id`),
  KEY `urun_id` (`urun_id`),
  FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. Sepet Tablosu
-- ============================================
CREATE TABLE `sepet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `urun_id` int(11) NOT NULL,
  `varyant_id` int(11) DEFAULT NULL,
  `adet` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `urun_id` (`urun_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. Kuponlar Tablosu
-- ============================================
CREATE TABLE `kuponlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kod` varchar(50) NOT NULL,
  `indirim_tipi` enum('yuzde','tutar','bedava_kargo') NOT NULL,
  `indirim_degeri` decimal(10,2) NOT NULL,
  `min_tutar` decimal(10,2) DEFAULT 0.00,
  `kullanim_limiti` int(11) DEFAULT NULL,
  `kullanim_sayisi` int(11) DEFAULT 0,
  `gecerlilik_baslangic` date DEFAULT NULL,
  `gecerlilik_bitis` date DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kod` (`kod`),
  KEY `aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 11. Kupon Kullanım Tablosu
-- ============================================
CREATE TABLE `kupon_kullanim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kupon_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `siparis_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kupon_id` (`kupon_id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `siparis_id` (`siparis_id`),
  FOREIGN KEY (`kupon_id`) REFERENCES `kuponlar` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 12. Yorumlar Tablosu
-- ============================================
CREATE TABLE `yorumlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urun_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `yorum` text NOT NULL,
  `puan` tinyint(1) NOT NULL CHECK (`puan` >= 1 AND `puan` <= 5),
  `onay` tinyint(1) DEFAULT 0,
  `admin_yanit` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `urun_id` (`urun_id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `onay` (`onay`),
  FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 13. Favoriler Tablosu
-- ============================================
CREATE TABLE `favoriler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullanici_urun` (`kullanici_id`, `urun_id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `urun_id` (`urun_id`),
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`urun_id`) REFERENCES `urunler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 14. Sliderlar Tablosu
-- ============================================
CREATE TABLE `sliderlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resim` varchar(255) NOT NULL,
  `baslik` varchar(255) DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `buton_text` varchar(100) DEFAULT NULL,
  `buton_link` varchar(255) DEFAULT NULL,
  `sira` int(11) DEFAULT 0,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sira` (`sira`),
  KEY `aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 15. Sayfalar Tablosu
-- ============================================
CREATE TABLE `sayfalar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icerik` longtext NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `menude_goster` tinyint(1) DEFAULT 1,
  `sira` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `aktif` (`aktif`),
  KEY `menude_goster` (`menude_goster`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 16. Ayarlar Tablosu
-- ============================================
CREATE TABLE `ayarlar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anahtar` varchar(100) NOT NULL,
  `deger` text DEFAULT NULL,
  `grup` varchar(50) DEFAULT 'genel',
  `aciklama` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anahtar` (`anahtar`),
  KEY `grup` (`grup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 17. Bülten Tablosu
-- ============================================
CREATE TABLE `bulten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 18. Kargo Firmaları Tablosu
-- ============================================
CREATE TABLE `kargo_firmalari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad` varchar(100) NOT NULL,
  `web_url` varchar(255) DEFAULT NULL,
  `api_url` varchar(255) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 19. İadeler Tablosu
-- ============================================
CREATE TABLE `iadeler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siparis_id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `neden` varchar(255) NOT NULL,
  `detay` text DEFAULT NULL,
  `durum` enum('beklemede','onaylandi','reddedildi') DEFAULT 'beklemede',
  `admin_notu` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siparis_id` (`siparis_id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `durum` (`durum`),
  FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 20. Stok Bildirimleri Tablosu
-- ============================================
CREATE TABLE `stok_bildirimleri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urun_id` int(11) NOT NULL,
  `varyant_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `bildirildi` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `urun_id` (`urun_id`),
  KEY `bildirildi` (`bildirildi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 21. Banka Logları Tablosu
-- ============================================
CREATE TABLE `banka_loglari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `siparis_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'TRY',
  `response_code` varchar(10) DEFAULT NULL,
  `response_message` text DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `request_data` longtext DEFAULT NULL,
  `response_data` longtext DEFAULT NULL,
  `status` enum('success','failed','pending') DEFAULT 'pending',
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `siparis_id` (`siparis_id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 22. Admin Logları Tablosu
-- ============================================
CREATE TABLE `admin_loglari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `aksiyon` varchar(100) NOT NULL,
  `tablo` varchar(100) DEFAULT NULL,
  `kayit_id` int(11) DEFAULT NULL,
  `eski_deger` longtext DEFAULT NULL,
  `yeni_deger` longtext DEFAULT NULL,
  `aciklama` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `aksiyon` (`aksiyon`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 23. Son Gezilen Ürünler Tablosu
-- ============================================
CREATE TABLE `son_gezilen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `urun_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  KEY `session_id` (`session_id`),
  KEY `urun_id` (`urun_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 24. SSS (Sıkça Sorulan Sorular) Tablosu
-- ============================================
CREATE TABLE `sss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soru` varchar(255) NOT NULL,
  `cevap` text NOT NULL,
  `kategori` varchar(100) DEFAULT 'genel',
  `sira` int(11) DEFAULT 0,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori` (`kategori`),
  KEY `aktif` (`aktif`),
  KEY `sira` (`sira`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 25. İletişim Mesajları Tablosu
-- ============================================
CREATE TABLE `iletisim_mesajlari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `konu` varchar(255) NOT NULL,
  `mesaj` text NOT NULL,
  `okundu` tinyint(1) DEFAULT 0,
  `yanit` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `okundu` (`okundu`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
