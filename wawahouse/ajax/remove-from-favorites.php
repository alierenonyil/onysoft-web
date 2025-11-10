<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Favorilerden Çıkar
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
    // Kullanıcı giriş kontrolü
    if (!isLoggedIn()) {
        echo json_encode([
            'success' => false,
            'message' => 'Bu işlem için giriş yapmalısınız',
            'login_required' => true
        ]);
        exit;
    }

    $userId = currentUser()['id'];
    $urunId = isset($_POST['urun_id']) ? (int)$_POST['urun_id'] : 0;

    if ($urunId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz ürün ID']);
        exit;
    }

    // Favorilerden sil
    $deleted = db()->delete('favoriler', 'kullanici_id = :userId AND urun_id = :urunId', [
        'userId' => $userId,
        'urunId' => $urunId
    ]);

    if ($deleted > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Ürün favorilerden çıkarıldı'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Ürün favorilerde bulunamadı'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
