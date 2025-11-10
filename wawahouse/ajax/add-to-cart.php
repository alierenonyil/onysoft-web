<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Sepete Ürün Ekle
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
    $urunId = isset($_POST['urun_id']) ? (int)$_POST['urun_id'] : 0;
    $varyantId = isset($_POST['varyant_id']) ? (int)$_POST['varyant_id'] : null;
    $miktar = isset($_POST['miktar']) ? (int)$_POST['miktar'] : 1;

    if ($urunId <= 0 || $miktar <= 0) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz ürün veya miktar']);
        exit;
    }

    // Ürünü kontrol et
    $urun = db()->fetchOne("SELECT * FROM urunler WHERE id = ? AND aktif = 1", [$urunId]);
    if (!$urun) {
        echo json_encode(['success' => false, 'message' => 'Ürün bulunamadı']);
        exit;
    }

    // Varyant kontrolü
    $varyant = null;
    if ($varyantId) {
        $varyant = db()->fetchOne("SELECT * FROM urun_varyantlari WHERE id = ? AND urun_id = ?", [$varyantId, $urunId]);
        if (!$varyant) {
            echo json_encode(['success' => false, 'message' => 'Varyant bulunamadı']);
            exit;
        }

        // Stok kontrolü
        if ($varyant['stok'] < $miktar) {
            echo json_encode(['success' => false, 'message' => 'Yeterli stok yok']);
            exit;
        }
    }

    // Fiyat hesapla
    $fiyat = $urun['indirimli_fiyat'] ?? $urun['fiyat'];

    // Kullanıcı giriş yapmışsa veritabanına ekle
    if (isLoggedIn()) {
        $userId = currentUser()['id'];

        // Sepette zaten var mı kontrol et
        $existing = db()->fetchOne(
            "SELECT * FROM sepet WHERE kullanici_id = ? AND urun_id = ? AND varyant_id " . ($varyantId ? "= ?" : "IS NULL"),
            $varyantId ? [$userId, $urunId, $varyantId] : [$userId, $urunId]
        );

        if ($existing) {
            // Mevcut miktarı artır
            $yeniMiktar = $existing['miktar'] + $miktar;

            // Stok kontrolü
            if ($varyant && $varyant['stok'] < $yeniMiktar) {
                echo json_encode(['success' => false, 'message' => 'Stok yetersiz. Maksimum ' . $varyant['stok'] . ' adet ekleyebilirsiniz']);
                exit;
            }

            db()->update('sepet', [
                'miktar' => $yeniMiktar
            ], 'id = :id', ['id' => $existing['id']]);
        } else {
            // Yeni kayıt ekle
            db()->insert('sepet', [
                'kullanici_id' => $userId,
                'urun_id' => $urunId,
                'varyant_id' => $varyantId,
                'miktar' => $miktar,
                'fiyat' => $fiyat
            ]);
        }

        // Sepetteki toplam ürün sayısını al
        $cartCount = db()->fetchOne("SELECT SUM(miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

        echo json_encode([
            'success' => true,
            'message' => 'Ürün sepete eklendi',
            'cart_count' => (int)$cartCount,
            'user_logged_in' => true
        ]);
    } else {
        // Giriş yapmamış kullanıcı için JSON döndür (localStorage'da tutulacak)
        echo json_encode([
            'success' => true,
            'message' => 'Ürün sepete eklendi',
            'user_logged_in' => false,
            'product' => [
                'id' => $urunId,
                'varyant_id' => $varyantId,
                'ad' => $urun['ad'],
                'slug' => $urun['slug'],
                'fiyat' => $fiyat,
                'miktar' => $miktar
            ]
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
