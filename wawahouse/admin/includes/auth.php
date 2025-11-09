<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Admin Yetkilendirme
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

require_once __DIR__ . '/../../config.php';

/**
 * Admin girişi kontrol et
 */
function checkAdminAuth(): void {
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
}

/**
 * Admin bilgilerini al
 */
function getAdminUser(): ?array {
    if (!isset($_SESSION['admin_id'])) {
        return null;
    }

    $admin = db()->fetchOne("SELECT * FROM kullanicilar WHERE id = ? AND rol = 'admin'", [$_SESSION['admin_id']]);
    return $admin ?: null;
}

/**
 * Admin girişi yap
 */
function adminLogin(string $email, string $password): bool {
    $user = db()->fetchOne("SELECT * FROM kullanicilar WHERE email = ? AND rol = 'admin' AND aktif = 1", [$email]);

    if (!$user || !verifyPassword($password, $user['sifre'])) {
        return false;
    }

    // Session oluştur
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_name'] = $user['ad_soyad'];
    $_SESSION['admin_role'] = $user['rol'];

    // Son giriş güncelle
    db()->update('kullanicilar', [
        'son_giris' => date('Y-m-d H:i:s'),
        'ip_adresi' => $_SERVER['REMOTE_ADDR']
    ], 'id = :id', ['id' => $user['id']]);

    // Log kaydet
    logAdminAction('login', 'kullanicilar', $user['id'], null, 'Admin girişi yaptı');

    return true;
}

/**
 * Admin çıkış
 */
function adminLogout(): void {
    if (isset($_SESSION['admin_id'])) {
        logAdminAction('logout', 'kullanicilar', $_SESSION['admin_id'], null, 'Admin çıkış yaptı');
    }

    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_role']);

    session_destroy();
}

/**
 * Admin aksiyonlarını logla
 */
function logAdminAction(
    string $aksiyon,
    ?string $tablo = null,
    ?int $kayitId = null,
    ?string $eskiDeger = null,
    ?string $aciklama = null
): void {
    if (!isset($_SESSION['admin_id'])) {
        return;
    }

    db()->insert('admin_loglari', [
        'admin_id' => $_SESSION['admin_id'],
        'aksiyon' => $aksiyon,
        'tablo' => $tablo,
        'kayit_id' => $kayitId,
        'eski_deger' => $eskiDeger,
        'aciklama' => $aciklama,
        'ip_address' => $_SERVER['REMOTE_ADDR']
    ]);
}

// Her admin sayfasında çalıştır
if (!str_contains($_SERVER['PHP_SELF'], 'login.php')) {
    checkAdminAuth();
}
