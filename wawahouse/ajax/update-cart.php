<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Sepet Miktarı Güncelle
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
    $miktar = isset($_POST['miktar']) ? (int)$_POST['miktar'] : 1;

    if ($sepetId <= 0 || $miktar <= 0) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz parametreler']);
        exit;
    }

    // Sepet öğesini getir
    $sepetItem = db()->fetchOne("SELECT * FROM sepet WHERE id = ? AND kullanici_id = ?", [$sepetId, $userId]);

    if (!$sepetItem) {
        echo json_encode(['success' => false, 'message' => 'Sepet öğesi bulunamadı']);
        exit;
    }

    // Varyant varsa stok kontrolü yap
    if ($sepetItem['varyant_id']) {
        $varyant = db()->fetchOne("SELECT stok FROM urun_varyantlari WHERE id = ?", [$sepetItem['varyant_id']]);
        if ($varyant && $varyant['stok'] < $miktar) {
            echo json_encode([
                'success' => false,
                'message' => 'Stok yetersiz. Maksimum ' . $varyant['stok'] . ' adet ekleyebilirsiniz'
            ]);
            exit;
        }
    }

    // Miktarı güncelle
    db()->update('sepet', ['miktar' => $miktar], 'id = :id', ['id' => $sepetId]);

    // Sepet toplamlarını hesapla
    $cartCount = db()->fetchOne("SELECT SUM(miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;
    $cartTotal = db()->fetchOne("SELECT SUM(fiyat * miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

    // Güncellenmiş öğe toplamı
    $itemTotal = $sepetItem['fiyat'] * $miktar;

    echo json_encode([
        'success' => true,
        'message' => 'Sepet güncellendi',
        'cart_count' => (int)$cartCount,
        'cart_total' => (float)$cartTotal,
        'item_total' => (float)$itemTotal
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
