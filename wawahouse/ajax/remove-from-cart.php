<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Sepetten Ürün Çıkar
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
        echo json_encode(['success' => false, 'message' => 'Giriş yapmanız gerekiyor']);
        exit;
    }

    $userId = currentUser()['id'];
    $sepetId = isset($_POST['sepet_id']) ? (int)$_POST['sepet_id'] : 0;

    if ($sepetId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz sepet ID']);
        exit;
    }

    // Sepet öğesinin kullanıcıya ait olduğunu kontrol et
    $sepetItem = db()->fetchOne("SELECT * FROM sepet WHERE id = ? AND kullanici_id = ?", [$sepetId, $userId]);

    if (!$sepetItem) {
        echo json_encode(['success' => false, 'message' => 'Sepet öğesi bulunamadı']);
        exit;
    }

    // Sepetten sil
    db()->delete('sepet', 'id = :id', ['id' => $sepetId]);

    // Sepetteki toplam ürün sayısını al
    $cartCount = db()->fetchOne("SELECT SUM(miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

    // Sepet toplamını hesapla
    $cartTotal = db()->fetchOne("SELECT SUM(fiyat * miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

    echo json_encode([
        'success' => true,
        'message' => 'Ürün sepetten çıkarıldı',
        'cart_count' => (int)$cartCount,
        'cart_total' => (float)$cartTotal
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
