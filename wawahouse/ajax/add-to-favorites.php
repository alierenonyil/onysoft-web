<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Favorilere Ekle
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
            'message' => 'Favorilere eklemek için giriş yapmalısınız',
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

    // Ürünün var olduğunu kontrol et
    $urun = db()->fetchOne("SELECT id FROM urunler WHERE id = ? AND aktif = 1", [$urunId]);
    if (!$urun) {
        echo json_encode(['success' => false, 'message' => 'Ürün bulunamadı']);
        exit;
    }

    // Zaten favorilerde mi kontrol et
    $existing = db()->fetchOne(
        "SELECT id FROM favoriler WHERE kullanici_id = ? AND urun_id = ?",
        [$userId, $urunId]
    );

    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Ürün zaten favorilerde']);
        exit;
    }

    // Favorilere ekle
    db()->insert('favoriler', [
        'kullanici_id' => $userId,
        'urun_id' => $urunId
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Ürün favorilere eklendi'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
