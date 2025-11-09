<?php
/**
 * WawaHouse E-Ticaret Sistemi
 * Ana Sayfa
 *
 * @package WawaHouse
 * @author Onysoft Veri Merkezi A.Ş.
 * @website www.onysoft.com.tr
 */

define('BASE_PATH', __DIR__);
$pageTitle = 'WawaHouse - Bebek Giyim ve Aksesuar';
require_once __DIR__ . '/includes/header.php';

// Öne çıkan ürünler
$featuredProducts = db()->fetchAll("
    SELECT u.*,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM urunler u
    WHERE u.aktif = 1 AND u.one_cikan = 1
    ORDER BY u.created_at DESC
    LIMIT 8
");

// Yeni ürünler
$newProducts = db()->fetchAll("
    SELECT u.*,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM urunler u
    WHERE u.aktif = 1
    ORDER BY u.created_at DESC
    LIMIT 8
");

// İndirimli ürünler
$discountProducts = db()->fetchAll("
    SELECT u.*,
           (SELECT resim FROM urun_resimleri WHERE urun_id = u.id AND ana_resim = 1 LIMIT 1) as resim
    FROM urunler u
    WHERE u.aktif = 1 AND u.indirimli_fiyat IS NOT NULL
    ORDER BY u.created_at DESC
    LIMIT 8
");
?>

<!-- Hero Slider -->
<section class="hero-slider">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center">
                <h1 class="display-4 fw-bold">
                    <i class="fas fa-baby-carriage"></i>
                    Bebeğiniz için En İyisi
                </h1>
                <p class="lead">%100 Organik Pamuklu Bebek Kıyafetleri</p>
                <a href="<?= url('urunler.php') ?>" class="btn btn-light btn-lg">
                    Alışverişe Başla
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Trust Badges -->
<section class="trust-badges">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="trust-badge">
                    <i class="fas fa-shipping-fast"></i>
                    <h5>Ücretsiz Kargo</h5>
                    <p>500 TL üzeri</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trust-badge">
                    <i class="fas fa-undo-alt"></i>
                    <h5>Kolay İade</h5>
                    <p>14 gün içinde</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trust-badge">
                    <i class="fas fa-lock"></i>
                    <h5>Güvenli Ödeme</h5>
                    <p>3D Secure</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="trust-badge">
                    <i class="fas fa-smile"></i>
                    <h5>Müşteri Memnuniyeti</h5>
                    <p>%100 Garanti</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="container my-5">
    <div class="section-title">
        <h2>
            <i class="fas fa-star text-warning"></i>
            Öne Çıkan Ürünler
        </h2>
    </div>

    <div class="row">
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($product['indirimli_fiyat']): ?>
                            <?php $discount = round((($product['fiyat'] - $product['indirimli_fiyat']) / $product['fiyat']) * 100); ?>
                            <span class="badge-discount">-%<?= $discount ?></span>
                        <?php endif; ?>
                        <a href="<?= url('urun/' . $product['slug']) ?>">
                            <img src="<?= upload($product['resim'] ?? 'products/default.jpg') ?>" alt="<?= clean($product['ad']) ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <a href="<?= url('urun/' . $product['slug']) ?>" class="product-title">
                            <?= clean($product['ad']) ?>
                        </a>
                        <div class="product-price">
                            <?php if ($product['indirimli_fiyat']): ?>
                                <?= formatPrice($product['indirimli_fiyat']) ?>
                                <span class="old-price"><?= formatPrice($product['fiyat']) ?></span>
                            <?php else: ?>
                                <?= formatPrice($product['fiyat']) ?>
                            <?php endif; ?>
                        </div>
                        <button class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart"></i>
                            Sepete Ekle
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- New Products -->
<?php if (!empty($newProducts)): ?>
<section class="container my-5">
    <div class="section-title">
        <h2>
            <i class="fas fa-sparkles"></i>
            Yeni Ürünler
        </h2>
    </div>

    <div class="row">
        <?php foreach ($newProducts as $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="product-card">
                    <div class="product-image">
                        <?php if ($product['indirimli_fiyat']): ?>
                            <?php $discount = round((($product['fiyat'] - $product['indirimli_fiyat']) / $product['fiyat']) * 100); ?>
                            <span class="badge-discount">-%<?= $discount ?></span>
                        <?php endif; ?>
                        <a href="<?= url('urun/' . $product['slug']) ?>">
                            <img src="<?= upload($product['resim'] ?? 'products/default.jpg') ?>" alt="<?= clean($product['ad']) ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <a href="<?= url('urun/' . $product['slug']) ?>" class="product-title">
                            <?= clean($product['ad']) ?>
                        </a>
                        <div class="product-price">
                            <?php if ($product['indirimli_fiyat']): ?>
                                <?= formatPrice($product['indirimli_fiyat']) ?>
                                <span class="old-price"><?= formatPrice($product['fiyat']) ?></span>
                            <?php else: ?>
                                <?= formatPrice($product['fiyat']) ?>
                            <?php endif; ?>
                        </div>
                        <button class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="fas fa-shopping-cart"></i>
                            Sepete Ekle
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter -->
<section class="trust-badges">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h3 class="mb-4">
                    <i class="fas fa-envelope"></i>
                    Kampanyalardan Haberdar Olun
                </h3>
                <form action="<?= url('bulten-kayit.php') ?>" method="POST" class="row g-2">
                    <div class="col-md-8">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="E-posta adresiniz" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Abone Ol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
$extraScripts = <<<'JS'
<script>
function addToCart(productId) {
    // AJAX ile sepete ekle
    $.ajax({
        url: '/ajax/add-to-cart.php',
        method: 'POST',
        data: { product_id: productId },
        success: function(response) {
            if (response.success) {
                alert('Ürün sepete eklendi!');
                location.reload();
            } else {
                alert('Bir hata oluştu: ' + response.message);
            }
        },
        error: function() {
            alert('Bir hata oluştu.');
        }
    });
}
</script>
JS;

require_once __DIR__ . '/includes/footer.php';
?>
