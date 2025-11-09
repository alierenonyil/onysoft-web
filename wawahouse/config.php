<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Konfigürasyon Dosyası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

// Hata raporlama (Geliştirme ortamı için açık, canlıda kapatılmalı)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone ayarı
date_default_timezone_set('Europe/Istanbul');

// Session ayarları
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // HTTPS varsa 1 yapın

// ============================================
// DATABASE AYARLARI (Değiştirilebilir)
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'wawahousesql');
define('DB_USER', 'wawahousekullanici');
define('DB_PASS', '14531453aO.!');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// SITE AYARLARI (Değiştirilebilir)
// ============================================
define('SITE_URL', 'https://staravcisi.com/');
define('SITE_NAME', 'WawaHouse - Bebek Giyim ve Aksesuar');
define('SITE_EMAIL', 'info@wawahouse.com');
define('SITE_PHONE', '+90 232 XXX XX XX');
define('ADMIN_EMAIL', 'admin@wawahouse.com');

// ============================================
// GELİŞTİRİCİ BİLGİLERİ (Sabit - Değiştirmeyin)
// ============================================
define('DEVELOPER_NAME', 'Onysoft Veri Merkezi A.Ş.');
define('DEVELOPER_WEB', 'www.onysoft.com.tr');
define('DEVELOPER_URL', 'https://www.onysoft.com.tr');
define('DEVELOPER_EMAIL', 'info@onysoft.com.tr');
define('DEVELOPER_PHONE', 'İzmir, Türkiye');

// ============================================
// SMTP MAIL AYARLARI (Değiştirilebilir)
// ============================================
define('SMTP_HOST', 'mail.staravcisi.com');
define('SMTP_USER', 'noreply@wawahouse.com');
define('SMTP_PASS', 'mail_password_here');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // tls veya ssl
define('SMTP_FROM_NAME', 'WawaHouse');

// ============================================
// ZİRAAT BANKASI POS AYARLARI (Değiştirilebilir)
// ============================================
define('POS_CLIENT_ID', 'test_client_id');
define('POS_STORE_KEY', 'test_store_key');
define('POS_API_URL', 'https://test.pos.ziraatbank.com/api/');
define('POS_TEST_MODE', true); // Canlıda false yapın

// ============================================
// DOSYA YÜ KLEME AYARLARI
// ============================================
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('UPLOAD_PATH', __DIR__ . '/assets/uploads/');

// ============================================
// GÜVENLİK AYARLARI
// ============================================
define('CSRF_TOKEN_NAME', 'csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 dakika (saniye)
define('PASSWORD_MIN_LENGTH', 6);

// ============================================
// SAYFALAMA AYARLARI
// ============================================
define('PRODUCTS_PER_PAGE', 20);
define('ORDERS_PER_PAGE', 25);
define('ADMIN_PER_PAGE', 25);

// ============================================
// KARGO AYARLARI
// ============================================
define('FREE_SHIPPING_THRESHOLD', 500.00); // 500 TL üzeri ücretsiz kargo
define('DEFAULT_SHIPPING_COST', 29.90);

// ============================================
// VERGİ AYARLARI
// ============================================
define('VAT_RATE', 20); // KDV %20

// ============================================
// PARA BİRİMİ
// ============================================
define('CURRENCY', 'TL');
define('CURRENCY_SYMBOL', '₺');

// ============================================
// DİL AYARLARI
// ============================================
define('SITE_LANGUAGE', 'tr');
define('SITE_LOCALE', 'tr_TR');

// ============================================
// SESSION BAŞLAT
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================
// CSRF TOKEN OLUŞTUR
// ============================================
if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

// ============================================
// AUTOLOAD (Composer)
// ============================================
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// ============================================
// TEMEL FONKSİYONLAR
// ============================================
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';