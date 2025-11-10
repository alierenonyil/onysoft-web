<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Sepet Sayısını Getir
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

// Sadece GET isteklerini kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek metodu']);
    exit;
}

try {
    // Kullanıcı giriş yapmışsa veritabanından al
    if (isLoggedIn()) {
        $userId = currentUser()['id'];
        $cartCount = db()->fetchOne("SELECT SUM(miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

        echo json_encode([
            'success' => true,
            'cart_count' => (int)$cartCount,
            'user_logged_in' => true
        ]);
    } else {
        // Giriş yapmamış kullanıcı için 0 döndür (localStorage'dan alınacak)
        echo json_encode([
            'success' => true,
            'cart_count' => 0,
            'user_logged_in' => false
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
