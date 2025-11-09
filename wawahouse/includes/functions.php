<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Genel Fonksiyonlar
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

// ============================================
// GÜVENLİK FONKSİYONLARI
// ============================================

/**
 * XSS koruması
 */
function clean(mixed $data): string|array {
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF Token doğrulama
 */
function verifyCsrfToken(string $token): bool {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * CSRF Token oluştur
 */
function generateCsrfToken(): string {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * CSRF Token input
 */
function csrfField(): string {
    $token = generateCsrfToken();
    return "<input type='hidden' name='csrf_token' value='{$token}'>";
}

/**
 * Şifre hash
 */
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_ARGON2ID);
}

/**
 * Şifre doğrulama
 */
function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

// ============================================
// URL ve YÖNLENDİRME
// ============================================

/**
 * URL oluştur
 */
function url(string $path = ''): string {
    return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Asset URL
 */
function asset(string $path): string {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Upload URL
 */
function upload(string $path): string {
    return url('assets/uploads/' . ltrim($path, '/'));
}

/**
 * Yönlendirme
 */
function redirect(string $url, int $statusCode = 302): never {
    header("Location: {$url}", true, $statusCode);
    exit;
}

/**
 * Geri dön
 */
function back(): never {
    $referer = $_SERVER['HTTP_REFERER'] ?? url();
    redirect($referer);
}

// ============================================
// SLUG ve SEO
// ============================================

/**
 * Slug oluştur (Türkçe karakter desteği)
 */
function createSlug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');

    $turkishChars = ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'];
    $englishChars = ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'];
    $text = str_replace($turkishChars, $englishChars, $text);

    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');

    return $text;
}

/**
 * Meta description kısalt
 */
function truncateDescription(string $text, int $length = 160): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

// ============================================
// TARİH ve ZAMAN
// ============================================

/**
 * Tarih formatla (Türkçe)
 */
function formatDate(string $date, string $format = 'd.m.Y H:i'): string {
    return date($format, strtotime($date));
}

/**
 * Görece zaman (örn: 2 saat önce)
 */
function timeAgo(string $datetime): string {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Az önce';
    } elseif ($diff < 3600) {
        $min = floor($diff / 60);
        return $min . ' dakika önce';
    } elseif ($diff < 86400) {
        $hour = floor($diff / 3600);
        return $hour . ' saat önce';
    } elseif ($diff < 2592000) {
        $day = floor($diff / 86400);
        return $day . ' gün önce';
    } else {
        return formatDate($datetime);
    }
}

// ============================================
// PARA ve SAYI
// ============================================

/**
 * Para formatla
 */
function formatPrice(float $price): string {
    return number_format($price, 2, ',', '.') . ' ' . CURRENCY_SYMBOL;
}

/**
 * KDV hesapla
 */
function calculateVAT(float $price): float {
    return $price * (VAT_RATE / 100);
}

/**
 * İndirim hesapla
 */
function calculateDiscount(float $price, float $discount, string $type = 'percent'): float {
    if ($type === 'percent') {
        return $price * ($discount / 100);
    }
    return $discount;
}

// ============================================
// DOSYA İŞLEMLERİ
// ============================================

/**
 * Dosya yükle
 */
function uploadFile(array $file, string $folder = 'products'): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return false;
    }

    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
        return false;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = UPLOAD_PATH . $folder . '/';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadPath . $filename)) {
        return $folder . '/' . $filename;
    }

    return false;
}

/**
 * Dosya sil
 */
function deleteFile(string $path): bool {
    $fullPath = UPLOAD_PATH . $path;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

/**
 * Resim resize
 */
function resizeImage(string $source, string $destination, int $width, int $height): bool {
    $imageInfo = getimagesize($source);
    if (!$imageInfo) {
        return false;
    }

    $sourceImage = match($imageInfo['mime']) {
        'image/jpeg' => imagecreatefromjpeg($source),
        'image/png' => imagecreatefrompng($source),
        'image/gif' => imagecreatefromgif($source),
        default => false
    };

    if (!$sourceImage) {
        return false;
    }

    $destImage = imagecreatetruecolor($width, $height);
    imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);

    return imagejpeg($destImage, $destination, 90);
}

// ============================================
// SAYFALAMA
// ============================================

/**
 * Sayfalama
 */
function paginate(int $total, int $perPage, int $currentPage = 1): array {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_previous' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
    ];
}

// ============================================
// VALIDATION
// ============================================

/**
 * Email doğrula
 */
function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Telefon doğrula (Türkiye)
 */
function validatePhone(string $phone): bool {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^(05)[0-9]{9}$/', $phone);
}

/**
 * Şifre gücü kontrol
 */
function validatePassword(string $password): bool {
    return strlen($password) >= PASSWORD_MIN_LENGTH;
}

// ============================================
// SESSION ve MESAJLAR
// ============================================

/**
 * Flash mesaj set
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'][$type] = $message;
}

/**
 * Flash mesaj al
 */
function getFlash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

/**
 * Başarı mesajı
 */
function success(string $message): void {
    setFlash('success', $message);
}

/**
 * Hata mesajı
 */
function error(string $message): void {
    setFlash('error', $message);
}

/**
 * Uyarı mesajı
 */
function warning(string $message): void {
    setFlash('warning', $message);
}

/**
 * Bilgi mesajı
 */
function info(string $message): void {
    setFlash('info', $message);
}

// ============================================
// KULLANICI İŞLEMLERİ
// ============================================

/**
 * Kullanıcı giriş yapmış mı?
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Admin mi?
 */
function isAdmin(): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Giriş yapılmamışsa yönlendir
 */
function requireLogin(string $redirectUrl = 'giris.php'): void {
    if (!isLoggedIn()) {
        redirect(url($redirectUrl));
    }
}

/**
 * Admin değilse yönlendir
 */
function requireAdmin(): void {
    if (!isAdmin()) {
        redirect(url());
    }
}

/**
 * Kullanıcı bilgisi al
 */
function currentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }

    $userId = $_SESSION['user_id'];
    return db()->fetchOne("SELECT * FROM kullanicilar WHERE id = ?", [$userId]);
}

// ============================================
// SİPARİŞ İŞLEMLERİ
// ============================================

/**
 * Sipariş numarası oluştur
 */
function generateOrderNumber(): string {
    return 'WH-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
}

/**
 * Kargo ücreti hesapla
 */
function calculateShipping(float $subtotal): float {
    if ($subtotal >= FREE_SHIPPING_THRESHOLD) {
        return 0;
    }
    return DEFAULT_SHIPPING_COST;
}

/**
 * Sipariş durumu rengi
 */
function getOrderStatusColor(string $status): string {
    return match($status) {
        'beklemede' => 'warning',
        'onaylandi' => 'info',
        'hazirlaniyor' => 'primary',
        'kargoda' => 'success',
        'teslim_edildi' => 'success',
        'iptal' => 'danger',
        default => 'secondary'
    };
}

/**
 * Sipariş durumu metni
 */
function getOrderStatusText(string $status): string {
    return match($status) {
        'beklemede' => 'Beklemede',
        'onaylandi' => 'Onaylandı',
        'hazirlaniyor' => 'Hazırlanıyor',
        'kargoda' => 'Kargoda',
        'teslim_edildi' => 'Teslim Edildi',
        'iptal' => 'İptal',
        default => 'Bilinmiyor'
    };
}

// ============================================
// SEPET İŞLEMLERİ
// ============================================

/**
 * Sepet session ID
 */
function getCartSessionId(): string {
    if (!isset($_SESSION['cart_session_id'])) {
        $_SESSION['cart_session_id'] = session_id();
    }
    return $_SESSION['cart_session_id'];
}

// ============================================
// DİĞER YARDIMCI FONKSİYONLAR
// ============================================

/**
 * Debug (sadece geliştirme ortamında)
 */
function dd(mixed ...$vars): never {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * JSON response
 */
function jsonResponse(array $data, int $statusCode = 200): never {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Random string
 */
function randomString(int $length = 32): string {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Site ayarı al
 */
function getSetting(string $key, mixed $default = null): mixed {
    static $settings = null;

    if ($settings === null) {
        $settings = [];
        $results = db()->fetchAll("SELECT anahtar, deger FROM ayarlar");
        foreach ($results as $row) {
            $settings[$row['anahtar']] = $row['deger'];
        }
    }

    return $settings[$key] ?? $default;
}
