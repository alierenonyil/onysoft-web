<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Bültene Kayıt
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

// Sadece POST isteklerini kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu']);
    exit;
}

try {
    $email = isset($_POST['email']) ? clean($_POST['email']) : '';

    // Email validasyonu
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email adresi gereklidir']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz email adresi']);
        exit;
    }

    // Email zaten kayıtlı mı kontrol et
    $existing = db()->fetchOne("SELECT id FROM bulten WHERE email = ?", [$email]);

    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Bu email adresi zaten kayıtlı']);
        exit;
    }

    // Bültene kaydet
    db()->insert('bulten', [
        'email' => $email,
        'aktif' => 1,
        'ip_adresi' => $_SERVER['REMOTE_ADDR']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Bültenimize başarıyla kaydoldunuz!'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
