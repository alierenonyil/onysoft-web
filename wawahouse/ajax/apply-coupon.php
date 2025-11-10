<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * AJAX - Kupon Uygula
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
        echo json_encode(['success' => false, 'message' => 'Kupon kullanmak için giriş yapmalısınız']);
        exit;
    }

    $userId = currentUser()['id'];
    $kuponKodu = isset($_POST['kupon_kodu']) ? strtoupper(trim($_POST['kupon_kodu'])) : '';

    if (empty($kuponKodu)) {
        echo json_encode(['success' => false, 'message' => 'Kupon kodu giriniz']);
        exit;
    }

    // Sepet toplamını hesapla
    $araToplam = db()->fetchOne("SELECT SUM(fiyat * miktar) as total FROM sepet WHERE kullanici_id = ?", [$userId])['total'] ?? 0;

    if ($araToplam <= 0) {
        echo json_encode(['success' => false, 'message' => 'Sepetinizde ürün bulunmuyor']);
        exit;
    }

    // Kuponu kontrol et
    $kupon = db()->fetchOne("
        SELECT * FROM kuponlar
        WHERE kod = ? AND aktif = 1
        AND (gecerlilik_baslangic IS NULL OR gecerlilik_baslangic <= NOW())
        AND (gecerlilik_bitis IS NULL OR gecerlilik_bitis >= NOW())
        AND (kullanim_limiti IS NULL OR kullanilan < kullanim_limiti)
    ", [$kuponKodu]);

    if (!$kupon) {
        echo json_encode(['success' => false, 'message' => 'Geçersiz veya süresi dolmuş kupon kodu']);
        exit;
    }

    // Minimum tutar kontrolü
    if ($kupon['min_tutar'] > $araToplam) {
        echo json_encode([
            'success' => false,
            'message' => 'Bu kupon için minimum ' . formatPrice($kupon['min_tutar']) . ' alışveriş yapmalısınız'
        ]);
        exit;
    }

    // Kullanıcı bu kuponu daha önce kullanmış mı?
    $kullanildiMi = db()->fetchOne(
        "SELECT id FROM kupon_kullanim WHERE kupon_id = ? AND kullanici_id = ?",
        [$kupon['id'], $userId]
    );

    if ($kullanildiMi) {
        echo json_encode(['success' => false, 'message' => 'Bu kuponu daha önce kullandınız']);
        exit;
    }

    // İndirim hesapla
    $indirimTutari = 0;
    $kargoIndirimi = 0;

    if ($kupon['indirim_tipi'] === 'yuzde') {
        $indirimTutari = ($araToplam * $kupon['indirim_degeri']) / 100;
        $indirimAciklama = '%' . $kupon['indirim_degeri'] . ' indirim';
    } elseif ($kupon['indirim_tipi'] === 'tutar') {
        $indirimTutari = $kupon['indirim_degeri'];
        $indirimAciklama = formatPrice($kupon['indirim_degeri']) . ' indirim';
    } elseif ($kupon['indirim_tipi'] === 'bedava_kargo') {
        $kargoIndirimi = DEFAULT_SHIPPING_COST;
        $indirimAciklama = 'Ücretsiz kargo';
    }

    // Session'a kaydet
    $_SESSION['applied_coupon'] = [
        'id' => $kupon['id'],
        'kod' => $kupon['kod'],
        'indirim_tipi' => $kupon['indirim_tipi'],
        'indirim_tutari' => $indirimTutari,
        'kargo_indirimi' => $kargoIndirimi,
        'aciklama' => $indirimAciklama
    ];

    // Kargo ücreti hesapla
    $kargoUcreti = $araToplam >= FREE_SHIPPING_THRESHOLD ? 0 : DEFAULT_SHIPPING_COST;
    if ($kargoIndirimi > 0) {
        $kargoUcreti = 0;
    }

    // Yeni toplamı hesapla
    $yeniToplam = $araToplam + $kargoUcreti - $indirimTutari;

    echo json_encode([
        'success' => true,
        'message' => 'Kupon başarıyla uygulandı',
        'coupon' => [
            'kod' => $kupon['kod'],
            'indirim_tipi' => $kupon['indirim_tipi'],
            'indirim_tutari' => (float)$indirimTutari,
            'kargo_indirimi' => (float)$kargoIndirimi,
            'aciklama' => $indirimAciklama
        ],
        'totals' => [
            'ara_toplam' => (float)$araToplam,
            'kargo_ucreti' => (float)$kargoUcreti,
            'indirim' => (float)$indirimTutari,
            'genel_toplam' => (float)$yeniToplam
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Bir hata oluştu: ' . $e->getMessage()
    ]);
}
