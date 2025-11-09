<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Sepet Sayfası
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 */

define('BASE_PATH', __DIR__);
$pageTitle = 'Sepetim - WawaHouse';
require_once __DIR__ . '/includes/header.php';

// Sepet ID belirle
if (isLoggedIn()) {
    $userId = $_SESSION['user_id'];
    $cartItems = db()->fetchAll("
        SELECT s.*, u.ad, u.slug, u.fiyat, u.indirimli_fiyat,
               (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim,
               v.beden, v.renk
        FROM sepet s
        INNER JOIN urunler u ON u.id = s.urun_id
        LEFT JOIN urun_varyantlari v ON v.id = s.varyant_id
        WHERE s.kullanici_id = ?
    ", [$userId]);
} else {
    $sessionId = getCartSessionId();
    $cartItems = db()->fetchAll("
        SELECT s.*, u.ad, u.slug, u.fiyat, u.indirimli_fiyat,
               (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim,
               v.beden, v.renk
        FROM sepet s
        INNER JOIN urunler u ON u.id = s.urun_id
        LEFT JOIN urun_varyantlari v ON v.id = s.varyant_id
        WHERE s.session_id = ?
    ", [$sessionId]);
}

// Toplam hesapla
$subtotal = 0;
foreach ($cartItems as $item) {
    $price = $item['indirimli_fiyat'] ?? $item['fiyat'];
    $subtotal += $price * $item['adet'];
}

$shipping = calculateShipping($subtotal);
$vat = calculateVAT($subtotal);
$total = $subtotal + $shipping;
?>

<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-shopping-cart"></i>
        Alışveriş Sepetim
    </h1>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Sepetiniz Boş</h4>
            <p>Henüz sepetinize ürün eklemediniz.</p>
            <a href="<?= url('urunler.php') ?>" class="btn btn-primary">
                Alışverişe Başla
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Fiyat</th>
                                    <th>Adet</th>
                                    <th>Toplam</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <?php
                                    $price = $item['indirimli_fiyat'] ?? $item['fiyat'];
                                    $itemTotal = $price * $item['adet'];
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= upload($item['resim'] ?? 'products/default.jpg') ?>"
                                                     alt="<?= clean($item['ad']) ?>"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                                     class="me-3">
                                                <div>
                                                    <strong><?= clean($item['ad']) ?></strong><br>
                                                    <?php if ($item['beden'] || $item['renk']): ?>
                                                        <small class="text-muted">
                                                            <?= clean($item['beden']) ?> - <?= clean($item['renk']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= formatPrice($price) ?></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm cart-quantity"
                                                   value="<?= $item['adet'] ?>" min="1" max="99"
                                                   data-cart-id="<?= $item['id'] ?>"
                                                   style="width: 70px;">
                                        </td>
                                        <td><strong><?= formatPrice($itemTotal) ?></strong></td>
                                        <td>
                                            <button class="btn btn-sm btn-danger remove-from-cart" data-cart-id="<?= $item['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Sipariş Özeti</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Ara Toplam:</span>
                            <strong><?= formatPrice($subtotal) ?></strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Kargo:</span>
                            <strong>
                                <?php if ($shipping > 0): ?>
                                    <?= formatPrice($shipping) ?>
                                <?php else: ?>
                                    <span class="text-success">ÜCRETSİZ</span>
                                <?php endif; ?>
                            </strong>
                        </div>

                        <?php if ($shipping > 0): ?>
                            <?php $remaining = FREE_SHIPPING_THRESHOLD - $subtotal; ?>
                            <?php if ($remaining > 0): ?>
                                <div class="alert alert-info small mb-3">
                                    <i class="fas fa-info-circle"></i>
                                    <?= formatPrice($remaining) ?> daha alışveriş yapın, kargo ücretsiz olsun!
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>TOPLAM:</strong>
                            <strong class="text-primary h5"><?= formatPrice($total) ?></strong>
                        </div>

                        <div class="d-grid gap-2">
                            <?php if (isLoggedIn()): ?>
                                <a href="<?= url('odeme.php') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card"></i>
                                    Ödemeye Geç
                                </a>
                            <?php else: ?>
                                <a href="<?= url('giris.php?redirect=odeme') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Giriş Yap ve Devam Et
                                </a>
                            <?php endif; ?>

                            <a href="<?= url('urunler.php') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-shopping-bag"></i>
                                Alışverişe Devam Et
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kupon Kodu -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">İndirim Kuponu</h6>
                        <form method="POST" action="ajax/apply-coupon.php">
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Kupon kodu">
                                <button class="btn btn-outline-primary" type="submit">
                                    Uygula
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
